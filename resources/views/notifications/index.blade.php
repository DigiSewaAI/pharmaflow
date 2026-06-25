{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'PharmaFlow — Notifications')
@section('page-title', 'Notifications')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
    <div>
        <h2 style="font-size:22px;font-weight:700;color:#0f172a;" class="dark:text-slate-100">Notification Center</h2>
        <p style="color:#64748b;font-size:14px;" class="dark:text-slate-400">All your alerts in one place</p>
    </div>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-primary btn-sm" onclick="markAllRead()">
            <i class="fas fa-check-double"></i> Mark All Read
        </button>
        <button class="btn btn-danger btn-sm" onclick="clearAll()">
            <i class="fas fa-trash"></i> Clear All
        </button>
    </div>
</div>

<!-- Unread Count Badge -->
<div class="card mb-4">
    <div style="display:flex;align-items:center;gap:12px;">
        <i class="fas fa-bell" style="font-size:24px;color:#2563EB;"></i>
        <div>
            <span style="font-size:16px;font-weight:600;">{{ $unreadCount }} unread notifications</span>
            <span style="font-size:13px;color:#64748b;margin-left:12px;" class="dark:text-slate-400">
                {{ $notifications->total() - $unreadCount }} read
            </span>
        </div>
    </div>
</div>

<!-- Notifications List -->
<div style="display:flex;flex-direction:column;gap:10px;">
    @forelse($notifications as $notif)
        @php
            $isUnread = is_null($notif->read_at);
            $icon = $notif->icon ?? 'fa-bell text-gray-500';
            $bgClass = $isUnread ? 'border-l-4 border-blue-500 bg-blue-50/50 dark:bg-blue-900/10' : 'border-l-4 border-transparent';
        @endphp
        <div class="card {{ $bgClass }}" style="transition:all 0.2s;" id="notif-{{ $notif->id }}">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                <div style="display:flex;align-items:flex-start;gap:12px;flex:1;">
                    <div style="width:40px;height:40px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;" class="dark:bg-slate-700">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;color:#0f172a;" class="dark:text-slate-100">
                            {{ $notif->data['title'] ?? 'Notification' }}
                        </div>
                        <div style="font-size:13px;color:#64748b;margin-top:2px;" class="dark:text-slate-400">
                            {{ $notif->data['message'] ?? '' }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
                            {{ $notif->created_at->diffForHumans() }}
                            @if(!$isUnread)
                                <span style="margin-left:8px;color:#10b981;">✓ Read</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:6px;flex-shrink:0;">
                    @if($isUnread)
                        <button class="btn btn-outline btn-sm" onclick="markRead('{{ $notif->id }}')">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif
                    @if(isset($notif->data['url']))
                        <a href="{{ $notif->data['url'] }}" class="btn btn-outline btn-sm">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center;padding:60px 0;color:#94a3b8;">
            <i class="fas fa-bell-slash" style="font-size:48px;display:block;margin-bottom:16px;color:#cbd5e1;"></i>
            <p style="font-size:16px;font-weight:500;color:#64748b;">No notifications yet.</p>
            <p style="font-size:13px;margin-top:4px;">We'll notify you when something happens.</p>
        </div>
    @endforelse
</div>

<div style="margin-top:20px;">
    {{ $notifications->links() }}
</div>

<!-- Toast -->
<div id="toastContainer" class="toast-container"></div>

@endsection

@push('scripts')
<script>
    // ─── Mark single as read ───
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
                const el = document.getElementById('notif-' + id);
                if (el) {
                    el.classList.remove('border-l-4', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-900/10');
                    el.classList.add('border-l-4', 'border-transparent');
                    // Update unread count badge
                    document.querySelector('.card .font-semibold').textContent = data.unread_count + ' unread notifications';
                }
                showToast(data.message, 'success');
            }
        })
        .catch(err => showToast('Server error.', 'error'));
    }

    // ─── Mark all as read ───
    function markAllRead() {
        if (!confirm('Mark all notifications as read?')) return;
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
                document.querySelectorAll('.card.border-l-4.border-blue-500').forEach(el => {
                    el.classList.remove('border-l-4', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-900/10');
                    el.classList.add('border-l-4', 'border-transparent');
                });
                document.querySelector('.card .font-semibold').textContent = '0 unread notifications';
                showToast(data.message, 'success');
            }
        })
        .catch(err => showToast('Server error.', 'error'));
    }

    // ─── Clear all ───
    function clearAll() {
        if (!confirm('Delete all notifications?')) return;
        fetch('{{ route('notifications.clear-all') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => showToast('Server error.', 'error'));
    }

    // ─── Toast ───
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
        toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i> ${message}`;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(20px)'; setTimeout(() => toast.remove(), 300); }, 3500);
    }
</script>
@endpush