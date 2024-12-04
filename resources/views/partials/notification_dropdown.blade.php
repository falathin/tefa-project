<li class="nav-item dropdown">
    <a class="nav-link count-indicator position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
        <!-- Conditional Class for Notification Bell -->
        <i class="bi bi-bell-fill fs-4 {{ App\Models\Notification::where('is_read', false)->count() > 0 ? 'text-warning animate__animated animate__jello animate__infinite animate__slow' : 'text-dark' }}"></i>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle py-1 px-2 fs-8" id="notificationCount">
            {{ App\Models\Notification::where('is_read', false)->count() }}
        </span>                    
    </a>
    <ul class="dropdown-menu dropdown-menu-end navbar-dropdown shadow-lg rounded-3" aria-labelledby="notificationDropdown">
        <!-- Header Notifikasi -->
        <li class="dropdown-header px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
            <p class="mb-0 fw-bold text-dark">
                <i class="bi bi-bell-fill me-2 text-warning fs-5"></i>
                Anda memiliki <strong id="newNotificationCount">{{ App\Models\Notification::where('is_read', false)->count() }}</strong> notifikasi baru
            </p>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-eye-fill"></i>&nbsp; Lihat Semua
            </a>
        </li>
    
        <!-- Daftar Notifikasi -->
        @if(App\Models\Notification::where('is_read', false)->count() > 0)
            @foreach(App\Models\Notification::where('is_read', false)->latest()->take(5)->get() as $notification)
            <li class="dropdown-item d-flex justify-content-between align-items-start px-3 py-2">
                <div class="flex-grow-1">
                    <p class="mb-1 text-truncate text-dark" style="max-width: 200px;">
                        <i class="bi bi-info-circle-fill text-warning me-2"></i>
                        {{ $notification->message }}
                    </p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <div class="d-flex flex-column align-items-end ms-3">

                    <!-- Mark notification as read -->
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-success p-0">
                            <i class="bi bi-check-circle-fill"></i>&nbsp;&nbsp; Tandai Dibaca
                        </button>
                    </form>
                </div>
            </li>
            <li><hr class="dropdown-divider"></li>
            @endforeach
        
        @else
            <li class="dropdown-item text-center py-3 d-flex justify-content-center align-items-center">
                <p class="mb-0 text-muted animate__animated animate__jello animate__infinite animate__slow">
                    <i class="bi bi-emoji-smile-fill text-success"></i>
                    &nbsp;&nbsp; Tidak ada notifikasi baru
                </p>
            </li>
        @endif                            

        <!-- Footer -->
        <li class="dropdown-footer text-center py-2 mt-2">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-arrow-right-circle"></i>&nbsp;&nbsp; Lihat Semua Notifikasi
            </a>
        </li>
    </ul>
</li>
<script>
    // Function to fetch unread notification count dynamically
    function updateNotificationCount() {
        fetch('{{ route('notifications.unreadCount') }}')
            .then(response => response.json())
            .then(data => {
                const unreadCount = data.count;

                // Update the badge and new notification count
                const badge = document.getElementById('notificationCount');
                const newNotificationCount = document.getElementById('newNotificationCount');
                const bellIcon = document.querySelector('.nav-link .bi-bell-fill');
                
                // Update badge text
                badge.textContent = unreadCount;
                newNotificationCount.textContent = unreadCount;

                // Change icon style if there are unread notifications
                if (unreadCount > 0) {
                    bellIcon.classList.add('text-warning');
                    bellIcon.classList.add('animate__animated', 'animate__jello', 'animate__infinite', 'animate__slow');
                } else {
                    bellIcon.classList.remove('text-warning');
                    bellIcon.classList.remove('animate__animated', 'animate__jello', 'animate__infinite', 'animate__slow');
                    bellIcon.classList.add('text-dark');
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
    }

    // Update notification count every 5 seconds
    setInterval(updateNotificationCount, 5000);

    // Initial update when the page loads
    document.addEventListener('DOMContentLoaded', updateNotificationCount);
</script>