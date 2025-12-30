<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Church;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $this->getFilteredQuery($request, $user);
        
        $attendances = $query->latest('attendance_date')->paginate(20);
        $churches = $this->getChurchesForUser($user);
        $serviceTypes = \App\Models\ServiceType::where('is_active', true)->get();
        
        $totalsQuery = $this->getFilteredQuery($request, $user);
        
        $totals = $totalsQuery->selectRaw('
            SUM(men_count) as total_men,
            SUM(women_count) as total_women,
            SUM(children_count) as total_children,
            SUM(total_count) as grand_total,
            AVG(total_count) as average_attendance
        ')->first();
        
        return view('attendances.index', compact('attendances', 'churches', 'totals', 'serviceTypes'));
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = $this->getFilteredQuery($request, $user);
        $attendances = $query->latest('attendance_date')->get();

        $filename = "attendance_export_" . date('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($attendances) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Date', 'Church', 'Service Type', 'Service Name', 'Men', 'Women', 'Children', 'Total', 'Notes', 'Recorded By']);

            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->attendance_date->format('Y-m-d'),
                    $attendance->church->name,
                    $attendance->serviceType ? $attendance->serviceType->name : 'N/A',
                    $attendance->service_name,
                    $attendance->men_count,
                    $attendance->women_count,
                    $attendance->children_count,
                    $attendance->total_count,
                    $attendance->notes,
                    $attendance->recorder ? $attendance->recorder->name : 'N/A'
                ]);
            }
            fclose($file);
        }, $filename);
    }

    private function getFilteredQuery(Request $request, $user)
    {
        $query = Attendance::with(['church', 'recorder', 'serviceType']);
        
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        if ($request->filled('church_id') && ($user->hasRole('boss') || $user->hasRole('archid'))) {
            $query->where('church_id', $request->church_id);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('attendance_date', '<=', $request->end_date);
        }

        if (!$request->filled('start_date') && !$request->filled('end_date') && $request->filled('year')) {
            $query->where('year', $request->year);
        } elseif (!$request->filled('start_date') && !$request->filled('end_date')) {
             $query->where('year', now()->year);
        }

        return $query;
    }

    public function create()
    {
        $churches = $this->getChurchesForUser(auth()->user());
        $serviceTypes = \App\Models\ServiceType::where('is_active', true)->get();
        return view('attendances.create', compact('churches', 'serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'attendance_date' => 'required|date',
            'service_type_id' => 'required|exists:service_types,id',
            'service_name' => 'nullable|string|max:255',
            'men_count' => 'required|integer|min:0',
            'women_count' => 'required|integer|min:0',
            'children_count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        Attendance::create([
            ...$validated,
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance recorded successfully!');
    }

    public function edit(Attendance $attendance)
    {
        $churches = $this->getChurchesForUser(auth()->user());
        $serviceTypes = \App\Models\ServiceType::where('is_active', true)->get();
        return view('attendances.edit', compact('attendance', 'churches', 'serviceTypes'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'attendance_date' => 'required|date',
            'service_type_id' => 'required|exists:service_types,id',
            'service_name' => 'nullable|string|max:255',
            'men_count' => 'required|integer|min:0',
            'women_count' => 'required|integer|min:0',
            'children_count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance updated successfully!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Attendance deleted successfully!');
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
}
