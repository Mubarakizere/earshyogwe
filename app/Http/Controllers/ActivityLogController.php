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

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(50);
        $modules = ActivityLog::distinct()->pluck('module');
        $users = \App\Models\User::all(); // Provide user list for filter

        return view('activity-logs.index', compact('logs', 'modules', 'users'));
    }
}
