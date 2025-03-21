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
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title">{{ $notification->message }}</h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }} | Jurusan {{ $notification->jurusan }}
                                </small>
                            </p>
                        </div>
                        <div class="d-flex flex-column flex-md-row align-items-center">
                            <!-- Jika sparepart tersedia, tampilkan tombol Edit -->
                            @if($notification->sparepart)
                                <a href="{{ route('sparepart.edit', $notification->sparepart->id_sparepart) }}" class="btn btn-sm btn-outline-primary mb-2 mb-md-0 me-md-2">Edit Sparepart</a>
                            @else
                                <span class="text-muted mb-2 mb-md-0 me-md-2">No Sparepart</span>
                            @endif

                            <!-- Tombol untuk menandai sebagai dibaca saja -->
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="m-0 me-md-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-success p-0">Tandai Dibaca</button>
                            </form>

                            <!-- Tombol gabungan: Tandai & Edit (jika sparepart ada) -->
                            @if($notification->sparepart)
                                <form method="POST" action="{{ route('notifications.read_and_edit', $notification->id) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-warning p-0">Tandai & Edit</button>
                                </form>
                            @endif
                        </div>
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