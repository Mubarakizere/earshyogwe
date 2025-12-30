<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Church;
use App\Models\User;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Route to appropriate dashboard based on role
        if ($user->hasRole('boss')) {
            return $this->bossDashboard();
        } elseif ($user->hasRole('archid')) {
            return $this->archidDashboard();
        } elseif ($user->hasRole('pastor')) {
            return $this->pastorDashboard();
        }

        // Default dashboard for users without specific roles
        return view('dashboard');
    }

    private function bossDashboard()
    {
        $stats = [
            'total_churches' => Church::count(),
            'active_churches' => Church::where('is_active', true)->count(),
            'total_pastors' => User::role('pastor')->count(),
            'total_archids' => User::role('archid')->count(),
            'total_departments' => Department::count(),
        ];

        $recentChurches = Church::with(['pastor', 'archid'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.boss', compact('stats', 'recentChurches'));
    }

    private function archidDashboard()
    {
        $user = auth()->user();
        
        $assignedChurches = Church::where('archid_id', $user->id)
            ->with(['pastor', 'departments'])
            ->get();

        $stats = [
            'assigned_churches' => $assignedChurches->count(),
            'active_churches' => $assignedChurches->where('is_active', true)->count(),
            'total_departments' => $assignedChurches->sum(function($church) {
                return $church->departments->count();
            }),
        ];

        return view('dashboards.archid', compact('stats', 'assignedChurches'));
    }

    private function pastorDashboard()
    {
        $user = auth()->user();
        
        $church = Church::with(['departments', 'archid'])
            ->find($user->church_id);

        if (!$church) {
            return view('dashboards.pastor', [
                'church' => null,
                'message' => 'You are not assigned to any church yet. Please contact your administrator.'
            ]);
        }

        $stats = [
            'departments' => $church->departments->count(),
            'active_departments' => $church->departments->where('is_active', true)->count(),
        ];

        return view('dashboards.pastor', compact('church', 'stats'));
    }
}
