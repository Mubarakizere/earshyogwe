<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only Boss can view audit logs
        if (!auth()->user()->hasRole('boss')) {
            abort(403);
        }

        $query = ActivityLog::with('user');

        // Filters
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Clone query for stats to respect filters
        $statsQuery = clone $query;

        $logs = $query->latest()->paginate(20)->withQueryString();
        $modules = ActivityLog::distinct()->pluck('module');
        $users = \App\Models\User::all(); // Provide user list for filter

        // Stats Calculation
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'top_user' => null,
        ];

        // Calculate Top User (Most active in the filtered result or overall if no filter)
        // Optimization: DB query for top user
        $topUserStats = ActivityLog::select('user_id', \DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->with('user')
            ->first();
            
        if ($topUserStats && $topUserStats->user) {
            $stats['top_user'] = $topUserStats->user;
            $stats['top_user_count'] = $topUserStats->total;
        }

        return view('activity-logs.index', compact('logs', 'modules', 'users', 'stats'));
    }
}
