<li class="nav-item dropdown">
    <a class="nav-link count-indicator position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill fs-4 {{ App\Models\Notification::where('is_read', false)->count() > 0 ? 'text-warning animate__animated animate__jello animate__infinite animate__slow' : 'text-dark' }}" id="notificationBell"></i>

    <!-- Only show the badge if there are unread notifications -->
    @if(App\Models\Notification::where('is_read', false)->count() > 0)
        <span class="count" style="position: absolute; left: 45%; width: 15px; height: 15px; border-radius: 50%; background: #F95F53; font-size: 10px; top: -5px; font-weight: 600; line-height: 1.2rem; text-align: center; display: flex; justify-content: center; align-items: center; box-shadow: 0 0 6px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out; transform: scale(1.2);">
            {{ App\Models\Notification::where('is_read', false)->count() }}
        </span>
    @endif

    </a>
    <ul class="dropdown-menu dropdown-menu-end navbar-dropdown shadow-lg rounded-3" aria-labelledby="notificationDropdown">
        <li class="dropdown-header px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
            <p class="mb-0 fw-bold text-dark">
                <i class="bi bi-bell-fill me-2 text-warning fs-5"></i>
                Anda memiliki <strong id="newNotificationCount">{{ App\Models\Notification::where('is_read', false)->count() }}</strong> notifikasi baru
            </p>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-eye-fill"></i>&nbsp; Lihat Semua
            </a>
        </li>
    
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

        <footer class="dropdown-footer text-center py-2 mt-2">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-arrow-right-circle"></i>&nbsp;&nbsp; Lihat Semua Notifikasi
            </a>
        </footer>
    </ul>
</li>