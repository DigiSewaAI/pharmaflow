<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

class NotificationService
{
    /**
     * Send a notification to a user (stored in database).
     *
     * @param User $user
     * @param array $data
     * @param string $type (sale, stock, expiry, system)
     * @return Notification
     */
    public function send(User $user, array $data, string $type = 'system')
    {
        $notification = Notification::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\DatabaseNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => array_merge($data, ['type' => $type]),
            'read_at' => null,
        ]);

        // Optionally broadcast for real-time
        // event(new \App\Events\NewNotification($user->id, $notification));

        return $notification;
    }

    /**
     * Send a notification to multiple users.
     *
     * @param array $userIds
     * @param array $data
     * @param string $type
     * @return void
     */
    public function sendToMany(array $userIds, array $data, string $type = 'system')
    {
        $users = User::whereIn('id', $userIds)->get();
        foreach ($users as $user) {
            $this->send($user, $data, $type);
        }
    }

    /**
     * Mark a notification as read.
     *
     * @param string $notificationId
     * @return bool
     */
    public function markAsRead(string $notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @param User $user
     * @return int
     */
    public function markAllAsRead(User $user)
    {
        return Notification::where('notifiable_id', $user->id)
                           ->whereNull('read_at')
                           ->update(['read_at' => now()]);
    }

    /**
     * Get unread notification count for a user.
     *
     * @param User $user
     * @return int
     */
    public function getUnreadCount(User $user)
    {
        return Notification::where('notifiable_id', $user->id)
                           ->whereNull('read_at')
                           ->count();
    }

    /**
     * Get all notifications for a user with pagination.
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(User $user, int $perPage = 20)
    {
        return Notification::where('notifiable_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);
    }

    /**
     * Get recent notifications for a user (for dropdown).
     *
     * @param User $user
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecent(User $user, int $limit = 5)
    {
        return Notification::where('notifiable_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->limit($limit)
                           ->get();
    }
}