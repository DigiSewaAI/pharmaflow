{{-- resources/views/components/notification-dropdown.blade.php --}}
<div class="relative" x-data="{ open: false, unreadCount: {{ $unreadCount ?? 0 }}, notifications: [] }" 
     @click.away="open = false">
    <button class="topbar-btn relative" @click="open = !open; fetchNotifications()">
        <i class="fas fa-bell"></i>
        <span class="dot" x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" 
              style="top:-4px;right:-4px;font-size:10px;padding:1px 6px;border-radius:12px;background:#ef4444;color:#fff;border:2px solid #fff;min-width:18px;text-align:center;line-height:16px;display:inline-block;position:absolute;">
        </span>
    </button>

    <div x-show="open" x-cloak 
         style="display:none;position:absolute;right:0;top:100%;margin-top:8px;width:420px;max-width:90vw;background:white;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.15);border:1px solid #e2e8f0;overflow:hidden;z-index:1000;max-height:480px;display:flex;flex-direction:column;"
         class="dark:bg-slate-800 dark:border-slate-700">
        
        <div style="padding:12px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;" class="dark:border-slate-700">
            <span style="font-weight:600;font-size:14px;color:#0f172a;" class="dark:text-white">Notifications</span>
            <div style="display:flex;gap:8px;">
                <button @click="markAllRead()" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Mark all read</button>
                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:underline dark:text-blue-400">View all</a>
            </div>
        </div>

        <div style="overflow-y:auto;flex:1;padding:8px 0;" id="notificationList">
            <template x-if="notifications.length === 0">
                <div style="padding:30px 20px;text-align:center;color:#94a3b8;font-size:13px;">
                    <i class="fas fa-bell-slash" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                    No new notifications
                </div>
            </template>
            <template x-for="notif in notifications" :key="notif.id">
                <div class="notification-item" :class="{'bg-blue-50/50 dark:bg-blue-900/10': !notif.read_at}" 
                     style="padding:10px 20px;border-bottom:1px solid #f1f5f9;transition:background 0.2s;cursor:pointer;"
                     @click="markRead(notif.id)">
                    <div style="display:flex;gap:12px;align-items:flex-start;">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;" class="dark:bg-slate-700">
                            <i class="fas" :class="notif.icon || 'fa-bell text-gray-500'"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:500;font-size:13px;color:#0f172a;" class="dark:text-white" x-text="notif.data.title || 'Notification'"></div>
                            <div style="font-size:12px;color:#64748b;margin-top:2px;" class="dark:text-slate-400" x-text="notif.data.message || ''"></div>
                            <div style="font-size:10px;color:#94a3b8;margin-top:3px;" x-text="timeAgo(notif.created_at)"></div>
                        </div>
                        <div x-show="!notif.read_at" style="width:8px;height:8px;border-radius:50%;background:#2563EB;flex-shrink:0;margin-top:4px;"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function fetchNotifications() {
        fetch('{{ route('notifications.recent') }}')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                    // Update badge
                    updateBadge(data.unread_count);
                }
            })
            .catch(err => console.error('Failed to fetch notifications', err));
    }

    function markRead(id) {
        fetch('{{ route('notifications.mark-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.unreadCount = data.unread_count;
                updateBadge(data.unread_count);
                // Remove from list or update
                const idx = this.notifications.findIndex(n => n.id === id);
                if (idx !== -1) {
                    this.notifications[idx].read_at = new Date().toISOString();
                    this.notifications = [...this.notifications];
                }
            }
        })
        .catch(err => console.error('Failed to mark read', err));
    }

    function markAllRead() {
        fetch('{{ route('notifications.mark-all-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.unreadCount = 0;
                updateBadge(0);
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.notifications = [...this.notifications];
            }
        })
        .catch(err => console.error('Failed to mark all read', err));
    }

    function updateBadge(count) {
        const badge = document.querySelector('.topbar-btn .dot');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function timeAgo(date) {
        const diff = Math.floor((new Date() - new Date(date)) / 1000);
        if (diff < 60) return 'Just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        if (diff < 2592000) return Math.floor(diff / 86400) + 'd ago';
        return new Date(date).toLocaleDateString();
    }
</script>