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
        
        $query = Attendance::with(['church', 'recorder']);
        
        // Role-based filtering
        if ($user->hasRole('pastor')) {
            $query->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $query->whereIn('church_id', $churchIds);
        }
        
        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        
        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        } else {
            $query->where('year', now()->year);
        }
        
        $attendances = $query->latest('attendance_date')->paginate(20);
        $churches = $this->getChurchesForUser($user);
        
        // Calculate totals for the filtered period
        $totalsQuery = Attendance::query();
        
        if ($user->hasRole('pastor')) {
            $totalsQuery->where('church_id', $user->church_id);
        } elseif ($user->hasRole('archid')) {
            $churchIds = Church::where('archid_id', $user->id)->pluck('id');
            $totalsQuery->whereIn('church_id', $churchIds);
        }
        
        if ($request->filled('year')) {
            $totalsQuery->where('year', $request->year);
        } else {
            $totalsQuery->where('year', now()->year);
        }
        
        $totals = $totalsQuery->selectRaw('
            SUM(men_count) as total_men,
            SUM(women_count) as total_women,
            SUM(children_count) as total_children,
            SUM(total_count) as grand_total,
            AVG(total_count) as average_attendance
        ')->first();
        
        return view('attendances.index', compact('attendances', 'churches', 'totals'));
    }

    public function create()
    {
        $churches = $this->getChurchesForUser(auth()->user());
        return view('attendances.create', compact('churches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'attendance_date' => 'required|date',
            'service_type' => 'required|in:sunday_service,prayer_meeting,bible_study,youth_service,special_event,other',
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
        return view('attendances.edit', compact('attendance', 'churches'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'church_id' => 'required|exists:churches,id',
            'attendance_date' => 'required|date',
            'service_type' => 'required|in:sunday_service,prayer_meeting,bible_study,youth_service,special_event,other',
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
