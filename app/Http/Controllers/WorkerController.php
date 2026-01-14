<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Church;
use App\Models\Department;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function export()
    {
        $user = auth()->user();
        $query = Worker::with(['church', 'department']);
        
        // Apply same filters as index
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }

        $workers = $query->get();

        $filename = "workers_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['First Name', 'Last Name', 'Church', 'Department', 'Position', 'Status', 'Phone', 'Email', 'Employment Date']);

        foreach ($workers as $worker) {
            fputcsv($handle, [
                $worker->first_name,
                $worker->last_name,
                $worker->church->name ?? 'N/A',
                $worker->department->name ?? 'N/A',
                $worker->position,
                $worker->status,
                $worker->phone,
                $worker->email,
                $worker->employment_date ? $worker->employment_date->format('Y-m-d') : '',
            ]);
        }
        fclose($handle);
        exit;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // 1. Base Query with Relations
        $query = Worker::with(['institution', 'activeContract']);
        
        // 2. Role-based Scope
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // 3. Advanced Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('church_id') && ($user->hasRole('boss') || $user->hasRole('archid'))) {
            $query->where('church_id', $request->church_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Status filter (Active/Inactive/All)
        if ($request->filled('status')) {
             $query->where('status', $request->status);
        }
        
        $workers = $query->latest()->paginate(20)->withQueryString();
        
        // 4. Data for Filters
        $churches = $this->getChurchesForUser($user);
        $departments = $this->getDepartmentsForUser($user);
        
        // 5. Global Stats (Scoped to User Role, ignoring temporary filters)
        $statsQuery = Worker::query();
        if ($user->hasRole('pastor')) {
            $statsQuery->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $statsQuery->whereIn('church_id', $churchIds);
        }

        // Optimized Stats
        $allWorkers = (clone $statsQuery)->where('status', 'active')->get(); // Get only active for retirement stats

        $stats = [
            'total' => (clone $statsQuery)->count(), // All workers (active + inactive)
            'retiring_soon' => $allWorkers->filter(fn($w) => $w->years_to_retirement !== null && $w->years_to_retirement >= 0 && $w->years_to_retirement <= 2)->count(),
            'overdue' => $allWorkers->filter(fn($w) => $w->years_to_retirement !== null && $w->years_to_retirement < 0)->count(),
        ];
        
        return view('workers.index', compact('workers', 'churches', 'departments', 'stats'));
    }

    public function create()
    {
        $this->authorize('create worker');
        $institutions = \App\Models\Institution::active()->orderBy('name')->get();
        
        return view('workers.create', compact('institutions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create worker');
        
        // DEBUG LOGGING - Track file uploads
        \Log::info('=== WORKER CREATION ATTEMPT ===', [
            'has_files' => $request->hasFile('documents'),
            'files_count' => $request->file('documents') ? count($request->file('documents')) : 0,
            'content_length' => $request->header('Content-Length'),
        ]);
        
        // Log each file's details
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                if ($file) {
                    \Log::info("File #{$index}", [
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getMimeType(),
                        'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                        'is_valid' => $file->isValid(),
                        'error_code' => $file->getError(),
                    ]);
                }
            }
        }
        
        try {
        $validated = $request->validate([
            'church_id' => 'nullable|exists:churches,id',
            'institution_id' => 'nullable|exists:institutions,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'national_id' => 'nullable|string|max:16',
            'education_qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:workers,email,NULL,id,deleted_at,NULL',
            'phone' => 'nullable|string|max:20',
            'district' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'job_title' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'document_names.*' => 'nullable|string|max:255',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/gif|max:20480',
        ]);

        $worker = Worker::create($validated);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                if ($file) {
                    $documentName = $request->document_names[$index] ?? 'Document ' . ($index + 1);
                    
                    // Get original extension
                    $extension = $file->getClientOriginalExtension();
                    
                    // Generate unique filename with extension
                    $filename = uniqid() . '_' . time() . '.' . $extension;
                    $path = $file->storeAs('worker_documents', $filename, 'local');
                    
                    \Log::info("File saved with extension", [
                        'original_name' => $file->getClientOriginalName(),
                        'saved_path' => $path,
                        'extension' => $extension,
                    ]);
                    
                    $worker->documents()->create([
                        'document_name' => $documentName,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('workers.index')
            ->with('success', 'Worker added successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('=== VALIDATION FAILED ===', [
                'errors' => $e->errors(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('=== ERROR CREATING WORKER ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Worker $worker)
    {
        $worker->load(['institution', 'documents']);
        return view('workers.show', compact('worker'));
    }

    public function edit(Worker $worker)
    {
        $this->authorize('edit worker');
        $institutions = \App\Models\Institution::active()->orderBy('name')->get();
        
        return view('workers.edit', compact('worker', 'institutions'));
    }

    public function update(Request $request, Worker $worker)
    {
        $this->authorize('edit worker');
        $validated = $request->validate([
            'institution_id' => 'nullable|exists:institutions,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'national_id' => 'nullable|string|max:16',
            'education_qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:workers,email,' . $worker->id . ',id,deleted_at,NULL',
            'phone' => 'nullable|string|max:20',
            'district' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'job_title' => 'required|string|max:255',
            'employment_date' => 'required|date',
            'birth_date' => 'nullable|date',
            'status' => 'required|in:active,retired,terminated',
            'document_names.*' => 'nullable|string|max:255',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/gif|max:20480',
        ]);

        $worker->update($validated);

        // Handle new document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                if ($file) {
                    $documentName = $request->document_names[$index] ?? 'Document ' . ($index + 1);
                    
                    // Get original extension
                    $extension = $file->getClientOriginalExtension();
                    
                    // Generate unique filename with extension
                    $filename = uniqid() . '_' . time() . '.' . $extension;
                    $path = $file->storeAs('worker_documents', $filename, 'local');
                    
                    $worker->documents()->create([
                        'document_name' => $documentName,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        return redirect()->route('workers.index')
            ->with('success', 'Worker updated successfully!');
    }

    public function destroy(Worker $worker)
    {
        $this->authorize('delete worker');
        $worker->delete();

        return redirect()->route('workers.index')
            ->with('success', 'Worker deleted successfully!');
    }

    public function trashed(Request $request)
    {
        $this->authorize('delete worker');
        
        $user = auth()->user();
        $query = Worker::onlyTrashed()->with(['institution']);
        
        // Apply same role-based filtering as index
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // Search in trashed workers
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $workers = $query->latest('deleted_at')->paginate(20)->withQueryString();
        
        return view('workers.trashed', compact('workers'));
    }

    public function restore($id)
    {
        $this->authorize('create worker'); // Need create permission to restore
        
        $worker = Worker::onlyTrashed()->findOrFail($id);
        $worker->restore();
        
        return redirect()->route('workers.trashed')
            ->with('success', 'Worker restored successfully!');
    }

    public function forceDelete($id)
    {
        $this->authorize('delete worker');
        
        $worker = Worker::onlyTrashed()->findOrFail($id);
        
        // Delete associated documents from storage
        foreach ($worker->documents as $doc) {
            \Storage::disk('local')->delete($doc->file_path);
            $doc->delete();
        }
        
        $worker->forceDelete();
        
        return redirect()->route('workers.trashed')
            ->with('success', 'Worker permanently deleted!');
    }

    public function destroyDocument(\App\Models\WorkerDocument $document)
    {
        $this->authorize('edit worker');
        $document->delete();

        return back()->with('success', 'Document deleted successfully!');
    }

    public function downloadDocument(\App\Models\WorkerDocument $document)
    {
        // Allow any user with worker management permissions to download documents
        if (!\Gate::any(['manage all workers', 'manage assigned workers', 'manage own workers'])) {
            abort(403, 'This action is unauthorized.');
        }
        
        $filePath = storage_path('app/private/' . $document->file_path);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            \Log::error('File not found for download', [
                'document_id' => $document->id,
                'expected_path' => $filePath,
                'stored_path' => $document->file_path,
            ]);
            abort(404, 'File not found');
        }
        
        // Get file extension
        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
        
        // For PDFs, display inline in browser instead of downloading
        if ($extension === 'pdf') {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $document->document_name . '.pdf"'
            ]);
        }
        
        // For other files, force download with proper extension
        $filename = $document->document_name;
        if (!str_ends_with(strtolower($filename), '.' . $extension)) {
            $filename .= '.' . $extension;
        }
        
        return response()->download($filePath, $filename);
    }

    private function getChurchesForUser($user)
    {
        if ($user->hasRole('boss')) {
            return Church::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            return Church::where('archid_id', $user->id)->where('is_active', true)->get();
        } else {
            return Church::where('id', $user->church_id)->get();
        }
    }

    private function getDepartmentsForUser($user)
    {
        if ($user->hasRole('boss')) {
            return Department::where('is_active', true)->get();
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            return Department::whereIn('church_id', $churchIds)->where('is_active', true)->get();
        } else {
            return Department::where('church_id', $user->church_id)->where('is_active', true)->get();
        }
    }
}
