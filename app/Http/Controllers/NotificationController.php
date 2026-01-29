<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display all notifications with filtering.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'all');
        $category = $request->get('category');
        
        $query = $user->notifications();
        
        // Filter by read status
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        // Filter by category if provided
        if ($category) {
            $query->where('data->category', $category);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get notification stats
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'today' => $user->notifications()->whereDate('created_at', today())->count(),
        ];
        
        // Get categories for filter dropdown
        $categories = [
            'expenses' => '🧾 Expenses',
            'activities' => '📅 Activities',
            'diocese' => '⛪ Diocese',
            'contracts' => '📋 Contracts',
            'evangelism' => '✝️ Evangelism',
        ];
        
        return view('notifications.index', compact('notifications', 'filter', 'category', 'stats', 'categories'));
    }

    /**
     * Mark notification as read and redirect.
     */
    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return redirect($notification->data['action_url'] ?? route('notifications.index'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all read notifications.
     */
    public function destroyRead()
    {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();
        return back()->with('success', 'All read notifications deleted.');
    }

    /**
     * Show notification settings page.
     */
    public function settings()
    {
        $user = auth()->user();
        $preferences = $user->notification_preferences;
        
        $categories = [
            'expenses' => ['name' => 'Expenses', 'description' => 'New expenses, approvals, and status updates', 'icon' => '🧾'],
            'activities' => ['name' => 'Activities', 'description' => 'Deadlines, progress reminders, and status changes', 'icon' => '📅'],
            'diocese' => ['name' => 'Diocese', 'description' => 'Transfers and receipt verifications', 'icon' => '⛪'],
            'contracts' => ['name' => 'Contracts', 'description' => 'Contract expirations and renewals', 'icon' => '📋'],
            'evangelism' => ['name' => 'Evangelism', 'description' => 'Evangelism report submissions', 'icon' => '✝️'],
        ];
        
        return view('notifications.settings', compact('preferences', 'categories'));
    }

    /**
     * Update notification settings.
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $preferences = [
            'email' => $request->boolean('email_enabled'),
            'push' => $request->boolean('push_enabled'),
            'database' => true, // Always keep database notifications
            'channels' => [],
        ];
        
        // Process per-category settings
        foreach (['expenses', 'activities', 'diocese', 'contracts', 'evangelism'] as $category) {
            $preferences['channels'][$category] = [
                'email' => $request->boolean("channels.{$category}.email"),
                'push' => $request->boolean("channels.{$category}.push"),
                'database' => true,
            ];
        }
        
        $user->update(['notification_preferences' => $preferences]);
        
        return back()->with('success', 'Notification preferences saved successfully.');
    }

    /**
     * Get unread notification count for AJAX.
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Get latest notifications for dropdown (AJAX).
     */
    public function latest()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'] ?? 'New Notification',
                    'icon' => $notification->data['icon'] ?? 'bell',
                    'category' => $notification->data['category'] ?? 'general',
                    'action_url' => route('notifications.read', $notification->id),
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
