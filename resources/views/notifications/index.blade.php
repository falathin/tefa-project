@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4">Notifikasi</h1>

    <!-- Search Bar -->
    <form method="GET" action="{{ route('notifications.index') }}" class="d-flex mb-4">
        <input type="text" name="search" class="form-control" placeholder="Cari Notifikasi" value="{{ request()->input('search') }}">
        <button type="submit" class="btn btn-primary ms-2">Cari</button>
        @if(request()->has('search'))
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary ms-2">Tutup Pencarian</a>
        @endif
    </form>

    <!-- Notification List -->
    <div class="row">
        @foreach($notifications as $notification)
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="card-title">{{ $notification->message }}</h5>
                            <p class="card-text"><small class="text-muted">{{ $notification->created_at->diffForHumans() }} | Jurusan {{ $notification->jurusan }}</small></p>
                        </div>

                        <!-- Check if there's a related sparepart and show the edit button -->
                        @if($notification->sparepart)
                            <a href="{{ route('sparepart.edit', $notification->sparepart->id_sparepart) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        @else
                            <span class="text-muted">No Sparepart</span>
                        @endif
                        
                        <!-- Mark notification as read -->
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0 ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-success p-0">Tandai Dibaca</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $notifications->links('vendor.pagination.simple-bootstrap-5') }}
    </div>
</div>
@endsection