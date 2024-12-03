@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notifikasi</h1>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('notifications.index') }}" class="d-flex mb-3">
        <input type="text" name="search" class="form-control" placeholder="Cari Notifikasi" value="{{ request()->input('search') }}">
        <button type="submit" class="btn btn-primary ms-2">Cari</button>
        @if(request()->has('search'))
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary ms-2">Tutup Pencarian</a>
        @endif
    </form>

    <!-- Notification List -->
    <ul class="list-group">
        @foreach($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <p class="mb-1">{{ $notification->message }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <a href="{{ route('sparepart.edit', $notification->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0 ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-success p-0">Tandai Dibaca</button>
                </form>
            </li>
        @endforeach
    </ul>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection