<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications.
     */
    public function index()
    {
        $notifications = $this->notificationService->getAll(auth()->user(), 20);
        $unreadCount = $this->notificationService->getUnreadCount(auth()->user());
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a single notification as read (AJAX).
     */
    public function markRead(Request $request)
    {
        $request->validate(['id' => 'required|string']);

        $success = $this->notificationService->markAsRead($request->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Notification marked as read.' : 'Notification not found.',
                'unread_count' => $this->notificationService->getUnreadCount(auth()->user())
            ]);
        }

        return redirect()->route('notifications.index')->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read (AJAX).
     */
    public function markAllRead(Request $request)
    {
        $count = $this->notificationService->markAllAsRead(auth()->user());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unread_count' => 0
            ]);
        }

        return redirect()->route('notifications.index')->with('success', 'All notifications marked as read.');
    }

    /**
     * Clear all notifications (AJAX).
     */
    public function clearAll(Request $request)
    {
        auth()->user()->notifications()->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared.',
                'unread_count' => 0
            ]);
        }

        return redirect()->route('notifications.index')->with('success', 'All notifications cleared.');
    }

    /**
     * Get unread count (AJAX for live badge).
     */
    public function unreadCount(Request $request)
    {
        $count = $this->notificationService->getUnreadCount(auth()->user());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);
        }

        return $count;
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function recent(Request $request)
    {
        $notifications = $this->notificationService->getRecent(auth()->user(), 5);
        $unreadCount = $this->notificationService->getUnreadCount(auth()->user());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        }

        return $notifications;
    }
}