<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Church;
use App\Models\Expense;
use App\Models\Giving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('boss')) {
            return $this->bossDashboard();
        } elseif ($user->hasRole('archid')) {
            return $this->archidDashboard($user);
        } elseif ($user->hasRole('pastor')) {
            return $this->pastorDashboard($user);
        } elseif ($user->hasRole('finance')) {
            return $this->financeDashboard();
        } elseif ($user->hasRole('hr')) {
            return $this->hrDashboard();
        } elseif ($user->hasRole('evangelism')) {
            return $this->evangelismDashboard();
        } else {
            return view('dashboard'); // Fallback default
        }
    }

    private function financeDashboard()
    {
        // Similar to Boss but focused purely on Money
        $currentYear = now()->year;
        
        $totalIncome = Giving::where('year', $currentYear)->sum('amount');
        $totalExpenses = Expense::where('year', $currentYear)->where('status', 'approved')->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        
        // Pending Expenses for Finance to review (if they have approval rights)
        $pendingExpenses = Expense::where('status', 'pending')->count();

        // Recent Transactions
        $recentGivings = Giving::with('church', 'givingType')->latest('date')->take(10)->get();
        $recentExpenses = Expense::with('church', 'expenseCategory')->latest('date')->take(10)->get();

        $monthlyStats = $this->getMonthlyStats(); // Global stats

        return view('dashboards.finance', compact(
            'totalIncome', 
            'totalExpenses', 
            'netBalance', 
            'pendingExpenses',
            'recentGivings', 
            'recentExpenses',
            'monthlyStats'
        ));
    }

    private function hrDashboard()
    {
        // Placeholder for HR Dashboard
        // We'd need to fetch Workers, Contracts expiring soon, etc.
        // Assuming models: Worker, Contract exist (from Phase 7)
        // If not, I will mock basic counts or use User model if that's what we have.
        // Checking task.md, Phase 7 HR is done. So models should exist.
        
        $totalWorkers = \App\Models\Worker::count();
        $expiringContracts = \App\Models\Contract::where('end_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now())
            ->where('status', 'active')
            ->count();
            
        $recentHires = \App\Models\Worker::latest('created_at')->take(5)->get();

        return view('dashboards.hr', compact('totalWorkers', 'expiringContracts', 'recentHires'));
    }

    private function evangelismDashboard()
    {
        // Placeholder for Evangelism Dashboard
        // Fetching Evangelism stats
        // Assuming EvangelismReport, EvangelismImpact models exist (Phase 5)
        
        $totalConverts = \App\Models\EvangelismImpact::sum('converts');
        $totalBaptized = \App\Models\EvangelismImpact::sum('baptized');
        $totalNewMembers = \App\Models\EvangelismImpact::sum('new_members');
        
        $recentReports = \App\Models\EvangelismReport::with('church')->latest('report_date')->take(5)->get();

        return view('dashboards.evangelism', compact('totalConverts', 'totalBaptized', 'totalNewMembers', 'recentReports'));
    }

    private function bossDashboard()
    {
        // 1. Overview Stats (All Time or Current Year - let's do Current Year for relevance)
        $currentYear = now()->year;
        
        $totalIncome = Giving::where('year', $currentYear)->sum('amount');
        $totalExpenses = Expense::where('year', $currentYear)->where('status', 'approved')->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        
        $totalAttendance = Attendance::where('year', $currentYear)->sum('total_count');

        // 2. Recent Transactions (Last 5 Givings and Last 5 Expenses)
        $recentGivings = Giving::with('church', 'givingType')->latest('date')->take(5)->get();
        $recentExpenses = Expense::with('church', 'expenseCategory')->latest('date')->take(5)->get();

        // 3. Church Performance (Top 5 by Income)
        $topChurches = Church::withSum(['givings' => function ($query) use ($currentYear) {
            $query->where('year', $currentYear);
        }], 'amount')
        ->orderByDesc('givings_sum_amount')
        ->take(5)
        ->get();

        // 4. Monthly Trends (Income vs Expense for Chart)
        $monthlyStats = $this->getMonthlyStats();

        // 5. Population Stats (Sum of latest census for current year)
        $totalPopulation = \App\Models\PopulationCensus::where('year', $currentYear)
            ->sum(DB::raw('men_count + women_count + youth_count + children_count + infants_count'));

        return view('dashboards.boss', compact(
            'totalIncome', 
            'totalExpenses', 
            'netBalance', 
            'totalAttendance', 
            'totalPopulation',
            'recentGivings', 
            'recentExpenses',
            'topChurches',
            'monthlyStats'
        ));
    }

    private function archidDashboard($user)
    {
        $currentYear = now()->year;
        $churchIds = Church::where('archid_id', $user->id)->pluck('id');

        $totalIncome = Giving::whereIn('church_id', $churchIds)->where('year', $currentYear)->sum('amount');
        $totalExpenses = Expense::whereIn('church_id', $churchIds)->where('year', $currentYear)->where('status', 'approved')->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        
        $totalAttendance = Attendance::whereIn('church_id', $churchIds)->where('year', $currentYear)->sum('total_count');

        $recentGivings = Giving::whereIn('church_id', $churchIds)->with('church', 'givingType')->latest('date')->take(5)->get();
        $recentExpenses = Expense::whereIn('church_id', $churchIds)->with('church', 'expenseCategory')->latest('date')->take(5)->get();

        $myChurches = Church::where('archid_id', $user->id)
            ->withSum(['givings' => function ($query) use ($currentYear) {
                $query->where('year', $currentYear);
            }], 'amount')
            ->get();

        $monthlyStats = $this->getMonthlyStats($churchIds);

        $totalPopulation = \App\Models\PopulationCensus::whereIn('church_id', $churchIds)
            ->where('year', $currentYear)
            ->sum(DB::raw('men_count + women_count + youth_count + children_count + infants_count'));

        return view('dashboards.archid', compact(
            'totalIncome', 
            'totalExpenses', 
            'netBalance', 
            'totalAttendance', 
            'totalPopulation',
            'recentGivings', 
            'recentExpenses',
            'myChurches',
            'monthlyStats'
        ));
    }

    private function pastorDashboard($user)
    {
        $currentYear = now()->year;
        $churchId = $user->church_id;

        if (!$churchId) {
            return view('dashboard')->with('warning', 'You are not assigned to any church.');
        }

        $totalIncome = Giving::where('church_id', $churchId)->where('year', $currentYear)->sum('amount');
        $totalExpenses = Expense::where('church_id', $churchId)->where('year', $currentYear)->where('status', 'approved')->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
        
        $totalAttendance = Attendance::where('church_id', $churchId)->where('year', $currentYear)->sum('total_count');

        $recentGivings = Giving::where('church_id', $churchId)->with('givingType')->latest('date')->take(5)->get();
        $recentExpenses = Expense::where('church_id', $churchId)->with('expenseCategory')->latest('date')->take(5)->get();

        // Pending Approvals count (for expenses entered by this pastor)
        $pendingExpenses = Expense::where('church_id', $churchId)->where('status', 'pending')->count();

        $monthlyStats = $this->getMonthlyStats([$churchId]);

        $totalPopulation = \App\Models\PopulationCensus::where('church_id', $churchId)
            ->where('year', $currentYear)
            ->sum(DB::raw('men_count + women_count + youth_count + children_count + infants_count'));

        return view('dashboards.pastor', compact(
            'totalIncome', 
            'totalExpenses', 
            'netBalance', 
            'totalAttendance', 
            'totalPopulation',
            'recentGivings', 
            'recentExpenses',
            'pendingExpenses',
            'monthlyStats'
        ));
    }

    private function getMonthlyStats($churchIds = null)
    {
        $currentYear = now()->year;
        
        // Initialize arrays for all 12 months
        $incomeData = array_fill(1, 12, 0);
        $expenseData = array_fill(1, 12, 0);

        // Income Query
        $incomeQuery = Giving::select(DB::raw('month, SUM(amount) as total'))
            ->where('year', $currentYear)
            ->groupBy('month');
            
        if ($churchIds) {
            $incomeQuery->whereIn('church_id', $churchIds);
        }
        
        $incomes = $incomeQuery->pluck('total', 'month');
        foreach ($incomes as $month => $total) {
            $incomeData[$month] = $total;
        }

        // Expense Query
        $expenseQuery = Expense::select(DB::raw('month, SUM(amount) as total'))
            ->where('year', $currentYear)
            ->where('status', 'approved')
            ->groupBy('month');

        if ($churchIds) {
            $expenseQuery->whereIn('church_id', $churchIds);
        }

        $expenses = $expenseQuery->pluck('total', 'month');
        foreach ($expenses as $month => $total) {
            $expenseData[$month] = $total;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'income' => array_values($incomeData),
            'expenses' => array_values($expenseData),
        ];
    }
}
