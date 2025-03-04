@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title text-primary d-flex align-items-center mb-0">
                        <i class="bi bi-list-check fs-4 me-2"></i> Daftar Pelanggan
                    </h5>
                    <a href="{{ route('customer.create') }}" class="btn btn-primary btn-sm px-4 py-2 hover-effect">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Pelanggan
                    </a>
                </div>

                <!-- Alert Sukses -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search Form -->
                <form class="d-flex" method="GET" action="{{ route('customer.index') }}">
                    <input type="text" name="search" class="form-control form-control-sm"
                        value="{{ $searchTerm ?? '' }}" placeholder="Cari pelanggan..." />
                    <button type="submit" class="btn hover-effect btn-outline-primary btn-sm ms-2 hover-effect">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <!-- Close Button -->
                    @if (!empty($searchTerm))
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="bi bi-x-circle"></i> Tutup
                        </a>
                    @endif
                </form>

                <!-- Tabel atau Pesan Jika Tidak Ada Data -->
                @if ($noData)
                    <div class="text-center my-5 text-muted">
                        <i class="bi bi-exclamation-circle fs-1"></i>
                        <p class="mt-3">Tidak ada data pelanggan ditemukan.</p>
                    </div>
                @else
                    <br>
                    <h6 class="text-primary mb-3">Pelanggan Aktif</h6>
                    <div class="table-responsive">
                        @if ($customers->isEmpty())
                            <div class="text-center my-5 text-muted">
                                <i class="bi bi-exclamation-circle fs-1"></i>
                                <p class="mt-3">Tidak ada data pelanggan ditemukan.</p>
                            </div>
                        @else
                            <table class="table table-hover table-striped table-bordered shadow-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>No HP</th>
                                        <th>Alamat</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->id }}</td>
                                            <td>{{ $customer->name }}</td>
                                            <td>{{ $customer->contact ?: 'Tidak ada data kontak' }}</td>
                                            <td>{{ $customer->address ?: 'Tidak ada data alamat' }}</td>
                                            <td class="d-flex gap-2">
                                                <a href="{{ route('customer.show', $customer->id) }}"
                                                    class="btn btn-info hover-effect btn-sm">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                                <a href="{{ route('customer.edit', $customer->id) }}"
                                                    class="btn btn-warning hover-effect btn-sm">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('customer.destroy', $customer->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn hover-effect btn-danger hover-effect btn-sm">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-lg">
                                {{ $customers->links('vendor.pagination.simple-bootstrap-5') }}
                            </ul>
                        </nav>
                    </div>
                    <br>
                    @if ($deletedCustomers->isNotEmpty())
                    <div class="mt-4">
                        <!-- Tombol Toggle -->
                        <button id="toggleDeletedCustomers" class="btn btn-primary btn-sm px-4 py-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#deletedCustomers" aria-expanded="false"
                            aria-controls="deletedCustomers">
                            <i id="arrowIcon" class="bi bi-arrow-bar-down me-2"></i> Pelanggan yang Dihapus
                        </button>
                
                        <!-- Div Collapse -->
                        <div class="collapse mt-3" id="deletedCustomers">
                            <!-- Form Pencarian -->
                            <div class="mt-3">
                                <form action="{{ route('customer.index') }}" method="GET" class="d-flex position-relative w-100">
                                    <input type="text" name="deletedSearch" class="form-control me-2 w-75"
                                        placeholder="Cari pelanggan yang dihapus..." value="{{ request('deletedSearch') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i> Cari
                                    </button>
                
                                    @if (request('deletedSearch'))
                                        <a href="{{ route('customer.index') }}"
                                            class="btn btn-outline-danger btn-sm position-absolute top-50 end-0 translate-middle-y">
                                            <i class="bi bi-x-circle"></i> Tutup
                                        </a>
                                    @endif
                                </form>
                            </div>
                
                            <!-- Tombol Hapus Semua -->
                            <div class="mt-3">
                                <form action="{{ route('customer.forceDeleteAll') }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua pelanggan yang telah dihapus secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash-fill me-1"></i> Hapus Semua
                                    </button>
                                </form>
                            </div>
                
                            <!-- Tabel Pelanggan Dihapus -->
                            <div class="table-responsive mt-3">
                                <table class="table table-hover table-striped table-bordered shadow-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>No HP</th>
                                            <th>Alamat</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deletedCustomers as $customer)
                                            <tr>
                                                <td>{{ $customer->id }}</td>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->contact ?: 'Tidak ada data kontak' }}</td>
                                                <td>{{ $customer->address ?: 'Tidak ada data alamat' }}</td>
                                                <td>
                                                    <!-- Button to Restore -->
                                                    <button class="btn btn-success btn-sm hover-effect" data-bs-toggle="modal"
                                                        data-bs-target="#restoreModal{{ $customer->id }}">
                                                        <i class="bi bi-arrow-clockwise"></i> Urungkan Hapus
                                                    </button>
                
                                                    <!-- Button to Permanently Delete -->
                                                    <button class="btn btn-danger btn-sm hover-effect" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal{{ $customer->id }}">
                                                        <i class="bi bi-trash"></i> Hapus Permanen
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                
                            <!-- Pagination -->
                            <div class="mt-4 d-flex justify-content-center">
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-lg">
                                        {{ $deletedCustomers->links('vendor.pagination.simple-bootstrap-5') }}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                
                    <!-- JavaScript untuk Toggle Icon dan Tutup Tabel -->
                @endif
                
                @endif
            </div>
        </div>
    </div>
@endsection