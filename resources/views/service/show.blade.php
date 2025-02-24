@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <!-- Card Container -->
        <div class="card shadow-lg border-0 animate_animated animatefadeIn animate_delay-1s"
            style="animation-duration: 1.5s; background-color: #f9fafb;">
            <!-- Card Header -->
            <div class="card-header text-white text-center py-3 animate_animated animate_fadeInDown"
                style="animation-duration: 1.5s; animation-delay: 0.2s; background-color: #007bff;">
                <h4 class="mb-0"><i class="mdi mdi-wrench"></i> Detail Servis</h4>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Informasi Kendaraan -->
                <h5 class="card-title text-center mb-4 animate_animated animate_zoomIn"
                    style="animation-duration: 1.5s; color: #007bff; font-weight: bold;">
                    Kendaraan: <strong>{{ $service->vehicle->license_plate }}</strong>
                    <span class="badge" style="background-color: #17a2b8; color: #fff; font-size: 0.9em;">
                        {{ $service->vehicle->vehicle_type }}
                    </span>
                </h5>

                <div class="row mb-5">
                    <!-- Kolom Kiri -->
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <!-- Informasi Kendaraan -->
                        <div class="border p-3 rounded shadow-sm bg-white animate_animated animate_fadeInLeft"
                            style="animation-duration: 1.5s; animation-delay: 0.5s; border-color: #e3e6f0;">
                            <h6 class="text-muted"><i class="mdi mdi-car"></i> Informasi Kendaraan</h6>
                            <p><strong>Warna:</strong> {{ $service->vehicle->color }}</p>
                            <p><strong>Tahun Produksi:</strong> {{ $service->vehicle->production_year }}</p>
                            <p><strong>Kode Mesin:</strong> {{ $service->vehicle->engine_code }}</p>
                        </div>

                        <!-- Informasi Pelanggan -->
                        <div class="border p-3 rounded shadow-sm bg-white mt-4 animate_animated animate_fadeInLeft"
                            style="animation-duration: 1.5s; animation-delay: 0.7s; border-color: #e3e6f0;">
                            <h6 class="text-muted"><i class="mdi mdi-account"></i> Informasi Pelanggan</h6>
                            <p><strong>Nama:</strong> {{ $service->vehicle->customer->name }}</p>
                            <p><strong>Kontak:</strong> {{ $service->vehicle->customer->contact }}</p>
                            <p><strong>Alamat:</strong> {{ $service->vehicle->customer->address }}</p>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <!-- Informasi Servis -->
                        <div class="border p-3 rounded shadow-sm bg-white animate_animated animate_fadeInRight"
                            style="animation-duration: 1.5s; animation-delay: 0.5s; border-color: #e3e6f0;">
                            <h6 class="text-muted"><i class="mdi mdi-information"></i> Informasi Servis</h6>
                            <p><strong><i class="fas fa-exclamation-circle text-warning"></i> Keluhan:</strong>
                                {{ $service->complaint }}</p>
                            <p><strong><i class="fas fa-tachometer-alt text-info"></i> Jarak Tempuh:</strong>
                                {{ $service->current_mileage }} km</p>
                            <p><strong><i class="fas fa-wrench text-primary"></i> Jasa Pelayanan:</strong> <span
                                    style="color: #28a745;">Rp.
                                    {{ number_format($service->service_fee, 0, ',', '.') }}</span></p>

                            <!-- Kolom Diskon -->
                            <p><strong><i class="fas fa-tags text-danger"></i> Diskon:</strong> <span
                                    style="color: #dc3545;">{{ number_format($service->diskon, 0, ',', '.') }}%</span></p>

                            <p><strong><i class="fas fa-receipt text-success"></i> Total Biaya:</strong> <span
                                    style="color: #dc3545;">Rp.
                                    {{ number_format($service->total_cost, 0, ',', '.') }}</span></p>
                            <p><strong><i class="fas fa-cash-register text-warning"></i> Pembayaran Diterima:</strong> <span
                                    style="color: #17a2b8;">Rp.
                                    {{ number_format($service->payment_received, 0, ',', '.') }}</span></p>
                            <p><strong><i class="fas fa-hand-holding-usd text-success"></i> Kembalian:</strong> Rp.
                                {{ number_format($service->change, 0, ',', '.') }}</p>
                            <p><strong><i class="fas fa-calendar-alt text-primary"></i> Tanggal Servis:</strong>
                                <span
                                    class="text-muted">{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}</span>
                            </p>
                            <p><strong><i class="fas fa-check-circle text-success"></i> Status Pembayaran:</strong>
                                <span id="payment-status" class="badge" style="color: #fff;">
                                    {{ $service->change > 0 ? 'Lunas' : ($service->change < 0 ? 'Hutang' : 'Belum Bayar') }}
                                </span>
                            </p>
                            
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    let change = {{ $service->change }};
                                    let isPaid = {{ $service->isPaid() ? 'true' : 'false' }}; // Cek dari backend apakah pembayaran sudah dilakukan
                                    let paymentStatus = document.getElementById("payment-status");
                            
                                    if (change > 0) {
                                        paymentStatus.innerText = "Lunas";
                                        paymentStatus.style.backgroundColor = "#28a745"; // Hijau
                                    } else if (change < 0) {
                                        paymentStatus.innerText = "Hutang";
                                        paymentStatus.style.backgroundColor = "#dc3545"; // Merah
                                    } else {
                                        // Jika change == 0, cek apakah memang sudah dibayar atau belum
                                        if (isPaid) {
                                            paymentStatus.innerText = "Lunas";
                                            paymentStatus.style.backgroundColor = "#28a745"; // Hijau
                                        } else {
                                            paymentStatus.innerText = "Belum Bayar";
                                            paymentStatus.style.backgroundColor = "#ffc107"; // Kuning
                                        }
                                    }
                                });
                            </script>
                            
                            <p><strong><i class="fas fa-tools text-warning"></i> Status Servis:</strong>
                                <span class="badge"
                                    style="background-color: {{ $service->status ? '#28a745' : '#FBA518' }}; color: #fff;">
                                    {{ $service->status ? 'Selesai' : 'Belum selesai' }}
                                </span>
                            </p>
                            <p><strong><i class="fas fa-cogs text-secondary"></i> Tipe Servis:</strong>
                                <span class="badge" style="background-color: #28a745; color: #fff;">
                                    {{ ucfirst($service->service_type == 'light' ? 'Ringan' : ($service->service_type == 'medium' ? 'Sedang' : 'Berat')) }}
                                </span>
                            </p>

                            <!-- Informasi Teknisi -->
                            <p><strong><i class="fas fa-user-cog text-dark"></i> Nama Teknisi:</strong>
                                {{ $service->technician_name }}</p>

                            <!-- Catatan Tambahan -->
                            <p><strong><i class="fas fa-sticky-note text-info"></i> Catatan Tambahan:</strong>
                                {{ $service->additional_notes ?? 'Tidak ada catatan tambahan' }}</p>
                        </div>
                    </div>

                </div>

                <div class="border p-3 rounded shadow-sm bg-white animate_animated animatefadeIn animate_delay-1s"
                    style="animation-duration: 1.5s; background-color: white; border-color: #e3e6f0;">
                    <div class="card-body">
                        <h6 class="mb-4 text-muted">
                            <i class="fas fa-tasks"></i> Pekerjaan
                        </h6>

                        @if (!Gate::allows('isBendahara'))
                            <!-- Form Tambah Item Pekerjaan -->
                            <form action="{{ route('service.addChecklist', $service->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="task" class="form-control form-control-sm"
                                        placeholder="Tambah item pekerjaan baru" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> Tambah Pekerjaan
                                </button>
                            </form>
                        @endif

                        <!-- Daftar Checklist -->
                        <ul class="list-group mt-4" id="checklist-list">
                            @foreach ($service->checklists->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        }) as $date => $checklists)
                                <!-- Section for Date -->
                                <li class="list-group-item bg-primary text-white fw-bold">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                                </li>

                                <!-- Checklist Items for the Date -->

                                @foreach ($checklists as $checklist)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center w-100">
                                            <!-- Checkbox with Task -->
                                            <form action="{{ route('service.updateChecklistStatus', $checklist->id) }}"
                                                method="POST" class="d-flex align-items-center w-100">
                                                @csrf
                                                @method('PATCH')
                                                @if (!Gate::allows('isBendahara'))
                                                    <input type="checkbox" name="is_completed" onchange="this.form.submit()"
                                                        class="form-check-input me-3"
                                                        {{ $checklist->is_completed ? 'checked' : '' }}>
                                                @endif

                                                <span
                                                    class="flex-grow-1 {{ $checklist->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                                    {{ $checklist->task }}
                                                </span>
                                            </form>
                                        </div>


                                        <!-- Status Badge (Hidden on Small Screens) -->
                                        <span
                                            class="badge {{ $checklist->is_completed ? 'bg-success' : 'bg-danger' }} d-none d-sm-inline-block">
                                            <i
                                                class="fas {{ $checklist->is_completed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $checklist->is_completed ? 'Selesai' : 'Tertunda' }}
                                        </span>
                                        <!-- Time and Actions -->
                                        @if (!Gate::allows('isBendahara'))
                                            <div class="ms-3 text-end">
                                                <small class="text-muted">Ditambahkan:
                                                    {{ $checklist->created_at->format('H:i') }}</small>
                                                <div class="dropdown d-inline-block ms-2">
                                                    <button class="btn btn-sm btn-light" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <button class="dropdown-item edit-btn text-warning"
                                                                data-id="{{ $checklist->id }}">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('service.deleteChecklist', $checklist->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </li>

                                    <!-- Form Edit (Hidden by Default) -->
                                    <li class="list-group-item" id="edit-form-container-{{ $checklist->id }}"
                                        style="display: none;">
                                        <form action="{{ route('service.updateChecklistTask', $checklist->id) }}"
                                            method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')

                                            <!-- Input for Task -->
                                            <input type="text" name="task"
                                                class="form-control form-control-sm me-3" value="{{ $checklist->task }}"
                                                required style="font-size: 0.875rem;">

                                            <!-- Save Button -->
                                            <button type="submit" class="btn btn-sm btn-success me-2"
                                                style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">
                                                <i class="fas fa-check"></i>
                                                <span class="d-none d-sm-inline-block">Simpan</span>
                                            </button>

                                            <!-- Cancel Button -->
                                            <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn"
                                                data-id="{{ $checklist->id }}"
                                                style="font-size: 0.75rem; padding: 0.375rem 0.75rem;">
                                                <i class="fas fa-times"></i>
                                                <span class="d-none d-sm-inline-block">Batal</span>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>

                        <!-- Script JS -->
                        <script>
                            document.querySelectorAll('.edit-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const checklistId = this.getAttribute('data-id');
                                    const formContainer = document.getElementById(edit - form - container - $ {
                                        checklistId
                                    });
                                    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
                                });
                            });

                            document.querySelectorAll('.cancel-edit-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const checklistId = this.getAttribute('data-id');
                                    const formContainer = document.getElementById(edit - form - container - $ {
                                        checklistId
                                    });
                                    formContainer.style.display = 'none';
                                });
                            });
                        </script>
                    </div>
                </div>


                <br>
                <!-- Informasi Sparepart -->
                <h6 class="mb-3 mt-3 animate_animated animate_fadeInUp"
                    style="animation-duration: 1.5s; animation-delay: 0.8s; color: #6c757d;">
                    <i class="mdi mdi-tools"></i> Sparepart yang Digunakan
                </h6>
                <div class="table-responsive border p-3 rounded shadow-sm bg-white">
                    <table class="table table-hover animate_animated animate_zoomIn"
                        style="animation-duration: 1.5s; animation-delay: 0.9s;">
                        <thead style="background-color: #007bff; color: #fff;">
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($service->serviceSpareparts as $serviceSparepart)
                                <tr class="animate_animated animate_flipInX">
                                    <td>{{ $serviceSparepart->sparepart->nama_sparepart }}</td>
                                    <td>{{ $serviceSparepart->quantity }}</td>
                                    <td>Rp. {{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada sparepart yang digunakan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- informasi pembayaran --}}
                <h6 class="mb-3 mt-3 animate_animated animate_fadeInUp"
                    style="animation-duration: 1.5s; animation-delay: 0.8s; color: #6c757d;">
                    <i class="mdi mdi-cash"></i> Informasi Pembayaran
                </h6>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('service.updatePayment', $service->id) }}" method="POST"
                    class="p-4 border rounded shadow bg-white">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="service_fee" class="form-label fw-bold text-primary">Jasa Pelayanan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i
                                        class="fas fa-dollar-sign"></i></span>
                                <input type="text" class="form-control border-primary" id="service_fee"
                                    placeholder="Masukkan Biaya Jasa"
                                    value="{{ old('service_fee', number_format($service->service_fee, 0, ',', '.')) }}">
                                <input type="hidden" name="service_fee" id="service_fee_asli"
                                    value="{{ old('service_fee', $service->service_fee) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="diskon" class="form-label fw-bold text-danger">Diskon (%)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white"><i
                                        class="fas fa-percentage"></i></span>
                                <input type="text" class="form-control border-danger" id="diskon" name="diskon"
                                    placeholder="Masukkan Diskon"
                                    value="{{ old('diskon', number_format($service->diskon, 0, ',', '.')) }}">
                                <input type="hidden" name="diskon" id="diskon_asli"
                                    value="{{ old('diskon', $service->diskon) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="total_cost" class="form-label fw-bold text-success">Total Biaya</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><i
                                        class="fas fa-calculator"></i></span>
                                <input type="text" id="total_cost" class="form-control border-success" readonly
                                    value="{{ old('total_cost', number_format($service->total_cost, 0, ',', '.')) }}">
                                <input type="hidden" name="total_cost" id="total_cost_asli"
                                    value="{{ old('total_cost', $service->total_cost) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_received" class="form-label fw-bold text-warning">Pembayaran
                                Diterima</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-white"><i
                                        class="fas fa-credit-card"></i></span>
                                <input type="text" name="payment_received" id="payment_received"
                                    class="form-control border-warning"
                                    value="{{ old('payment_received', number_format($service->payment_received, 0, ',', '.')) }}">
                                <input type="hidden" name="payment_received" id="payment_received_asli"
                                    value="{{ old('payment_received', $service->payment_received) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="change" class="form-label fw-bold text-info">Kembalian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-info text-white"><i
                                        class="fas fa-money-bill-wave"></i></span>
                                <input type="text" name="change" id="change" class="form-control border-info"
                                    readonly value="{{ old('change', number_format($service->change, 0, ',', '.')) }}">
                                <input type="hidden" name="change" id="change_asli"
                                    value="{{ old('change', $service->change) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label fw-bold text-dark">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-select border-dark">
                                <option value="cash" class="text-dark"
                                    {{ old('payment_method', $service->payment_method) == 'cash' ? 'selected' : '' }}>Bayar
                                    Cash</option>
                                <option value="cooperative" class="text-dark"
                                    {{ old('payment_method', $service->payment_method) == 'cooperative' ? 'selected' : '' }}>
                                    Kooperasi</option>
                                <option value="administration" class="text-dark"
                                    {{ old('payment_method', $service->payment_method) == 'administration' ? 'selected' : '' }}>
                                    Tata Usaha</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 w-100">Update Pembayaran</button>
                </form>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        let spareparts = @json($service->serviceSpareparts);
                        let totalSparepart = spareparts.reduce((total, item) => total + (item.sparepart.harga_jual * item
                            .quantity), 0);

                        function formatRibuan(angka) {
                            return new Intl.NumberFormat("id-ID").format(angka);
                        }

                        function unformat(angka) {
                            return parseInt(angka.replace(/\D/g, "")) || 0;
                        }

                        function updateTotalCost() {
                            let serviceFee = unformat(document.getElementById("service_fee").value);
                            let diskon = unformat(document.getElementById("diskon").value) / 100;
                            let total = (serviceFee + totalSparepart) * (1 - diskon);

                            document.getElementById("total_cost").value = formatRibuan(total);
                            document.getElementById("total_cost_asli").value = total;
                            updateChange();
                        }

                        function updateChange() {
                            let totalCost = unformat(document.getElementById("total_cost").value);
                            let paymentReceived = unformat(document.getElementById("payment_received").value);
                            let change = paymentReceived - totalCost;

                            document.getElementById("change").value = formatRibuan(change);
                            document.getElementById("change_asli").value = change;
                        }

                        document.getElementById("service_fee").addEventListener("input", function() {
                            this.value = formatRibuan(this.value.replace(/\D/g, ""));
                            document.getElementById("service_fee_asli").value = unformat(this.value);
                            updateTotalCost();
                        });

                        document.getElementById("diskon").addEventListener("input", function() {
                            this.value = formatRibuan(this.value.replace(/\D/g, ""));
                            document.getElementById("diskon_asli").value = unformat(this.value);
                            updateTotalCost();
                        });

                        document.getElementById("payment_received").addEventListener("input", function() {
                            this.value = formatRibuan(this.value.replace(/\D/g, ""));
                            document.getElementById("payment_received_asli").value = unformat(this.value);
                            updateChange();
                        });

                        updateTotalCost();
                    });
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        function formatRibuan(angka) {
                            return new Intl.NumberFormat("id-ID").format(angka);
                        }

                        function unformat(angka) {
                            return parseInt(angka.replace(/\./g, "")) || 0;
                        }

                        function updateFormattedInput(inputId, hiddenId) {
                            let input = document.getElementById(inputId);
                            let hiddenInput = document.getElementById(hiddenId);

                            if (input && hiddenInput) {
                                input.value = formatRibuan(hiddenInput.value);
                                input.addEventListener("input", function() {
                                    let rawValue = unformat(this.value);
                                    hiddenInput.value = rawValue;
                                    this.value = formatRibuan(rawValue);
                                });
                            }
                        }

                        // Format angka saat halaman dimuat
                        updateFormattedInput("service_fee", "service_fee_asli");
                        updateFormattedInput("diskon", "diskon_asli");
                        updateFormattedInput("total_cost", "total_cost_asli");
                        updateFormattedInput("payment_received", "payment_received_asli");
                        updateFormattedInput("change", "change_asli");
                    });
                </script>

                <br><br>
                <!-- Action Buttons -->
                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center animate_animated animate_fadeInUp"
                    style="animation-duration: 1.5s; animation-delay: 1s;">

                    <!-- Cetak Button -->
                    <button id="printBtn" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-printer me-2"></i> Cetak
                    </button>

                    <!-- Salin Laporan Button -->
                    <button id="copyReportBtn" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-content-copy me-2"></i> Salin Laporan
                    </button>

                    @if (!Gate::allows('isBendahara'))
                        <!-- Kembali ke Kendaraan -->
                        @if ($service->vehicle)
                            <a href="{{ route('vehicle.show', $service->vehicle->id) }}" class="btn btn-dark btn-sm">
                                <i class="mdi mdi-car me-2"></i> Detail kendaraan
                            </a>
                        @endif

                        <!-- Edit Button -->
                        <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil me-2"></i> Edit
                        </a>

                        <!-- Hapus Button -->
                        <form action="{{ route('service.destroy', $service->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                <i class="mdi mdi-delete me-2"></i> Hapus
                            </button>
                        </form>
                </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.getElementById('copyReportBtn').addEventListener('click', function() {
                    var vehicleInfo =
                        `*🚗 Informasi Kendaraan:*\nNomor Polisi: ${'{{ $service->vehicle->license_plate }}'} (${{ $service->vehicle->vehicle_type }})\nWarna: ${'{{ $service->vehicle->color }}'}\nTahun Produksi: ${
                    '{{ $service->vehicle->production_year }}'}\nKode Mesin: ${'{{ $service->vehicle->engine_code }}'}`;

                    var customerInfo = ** 👤Informasi Pelanggan: ** \nNama: $ {
                        '{{ $service->vehicle->customer->name }}'
                    }\
                    nKontak: $ {
                        '{{ $service->vehicle->customer->contact }}'
                    }\
                    nAlamat: $ {
                        '{{ $service->vehicle->customer->address }}'
                    };

                    var serviceInfo = ** 🛠Informasi Servis: ** \nKeluhan: $ {
                        '{{ $service->complaint }}'
                    }\
                    nKilometer Saat Ini: $ {
                        '{{ $service->current_mileage }}'
                    }
                    km\ nBiaya Servis: Rp.$ {
                        '{{ number_format($service->service_fee, 0, ',', '.') }}'
                    }\
                    nTotal Biaya: Rp.$ {
                        '{{ number_format($service->total_cost, 0, ',', '.') }}'
                    }\
                    nPembayaran Diterima: Rp.$ {
                        '{{ number_format($service->payment_received, 0, ',', '.') }}'
                    }\
                    nKembalian: Rp.$ {
                        '{{ number_format($service->change, 0, ',', '.') }}'
                    }\
                    nJenis Servis: $ {
                        '{{ ucfirst($service->service_type) }}'
                    }\
                    nTanggal Servis: $ {
                        '{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}'
                    };

                    var sparepartsInfo = '🔧 Sparepart yang Digunakan:\n';

                    // Loop through the service spareparts dynamically
                    @foreach ($service->serviceSpareparts as $serviceSparepart)
                        sparepartsInfo +=
                            Nama: ** $ {
                                '{{ $serviceSparepart->sparepart->nama_sparepart }}'
                            } ** | Jumlah: $ {
                                '{{ $serviceSparepart->quantity }}'
                            } | Harga: Rp.$ {
                                '{{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}'
                            }\
                        n;
                    @endforeach

                    var checklistInfo = '📝 Pekerjaan yang Dikerjakan:\n';

                    // Loop through the service checklists dynamically
                    @foreach ($service->checklists as $checklist)
                        checklistInfo +=
                            - ** $ {
                                '{{ $checklist->task }}'
                            } ** $ {
                                '{{ $checklist->is_completed ? '✅ Selesai' : '❌ Tertunda' }}'
                            }\
                        n;
                    @endforeach

                    // Adding Additional Notes and Technician Name
                    var additionalNotes = ** 📝Catatan Tambahan: ** \n$ {
                        '{{ $service->additional_notes }}'
                    };
                    var technicianName = ** 👨‍🔧Nama Teknisi: ** \n$ {
                        '{{ $service->technician_name }}'
                    };

                    var linkInfo = \nAda masalah ? Telepon via WhatsApp : [Chat dengan Jamat](https: //wa.me/6285715467500);

                        var fullReport =
                            $ {
                                vehicleInfo
                            }\
                        n\ n$ {
                            customerInfo
                        }\
                        n\ n$ {
                            serviceInfo
                        }\
                        n\ n$ {
                            sparepartsInfo
                        }\
                        n\ n$ {
                            checklistInfo
                        }\
                        n\ n$ {
                            additionalNotes
                        }\
                        n\ n$ {
                            technicianName
                        }
                        $ {
                            linkInfo
                        };

                        var textarea = document.createElement('textarea'); textarea.value = fullReport; document.body
                        .appendChild(textarea);

                        textarea.select(); document.execCommand('copy');

                        document.body.removeChild(textarea);

                        alert('Laporan berhasil disalin ke clipboard! 📋');
                    });
    </script>

    <script>
        document.getElementById('printBtn').addEventListener('click', function() {

            const printContent = document.querySelector('.container').innerHTML;
            const newWindow = window.open('', '', 'width=300,height=600');

            newWindow.document.write(`
                <html>
                    <head>
                        <title>Detail Servis</title>
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
                        <style>
                            body {
                                font-family: 'Courier New', monospace;
                                font-size: 12px;
                                line-height: 1.5;
                                margin: 0;
                                padding: 0;
                                color: #333;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: auto;
                                text-align: center;
                            }
                            h1 {
                                font-size: 20px;
                                font-weight: bold;
                                text-align: center;
                                margin: 0;
                                padding: 10px 0;
                                text-transform: uppercase;
                                letter-spacing: 1px;
                            }
                            h5 {
                                font-size: 16px;
                                text-align: center;
                                font-weight: normal;
                                margin: 10px 0;
                            }
                            .container {
                                width: 100%;
                                max-width: 350px;
                                padding: 20px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                border-radius: 10px;
                                background-color: #fff;
                                margin: 0 auto;
                                text-align: left;
                            }
                            .card-header {
                                font-weight: bold;
                                font-size: 14px;
                                border-bottom: 2px solid #007bff;
                                padding: 6px 0;
                                color: #007bff;
                                text-transform: uppercase;
                            }
                            .list-group-item {
                                padding: 8px 0;
                                border-bottom: 1px dashed #ddd;
                                font-size: 12px;
                                display: flex;
                                justify-content: space-between;
                            }
                            .list-group-item strong {
                                font-weight: bold;
                                text-transform: uppercase;
                            }
                            .total {
                                border-top: 2px solid #000;
                                margin-top: 20px;
                                padding-top: 10px;
                                font-size: 14px;
                                font-weight: bold;
                                text-align: center;
                            }
                            .footer {
                                font-size: 10px;
                                text-align: center;
                                margin-top: 15px;
                                color: #888;
                            }
                            @media print {
                                body {
                                    padding: 0;
                                    margin: 0;
                                }
                                .container {
                                    width: 100%;
                                    margin: 0;
                                    padding: 10px;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1><i class="bi bi-wrench"></i> Detail Servis</h1>
                            <h5><i class="bi bi-info-circle"></i> Kendaraan: {{ $service->vehicle->license_plate }}</h5>
                            <div class="card">
                                <div class="card-header">Informasi Kendaraan</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <span><i class="bi bi-car"></i> <strong>Warna:</strong></span>
                                            <span>{{ $service->vehicle->color }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-calendar"></i> <strong>Tahun Produksi:</strong></span>
                                            <span>{{ $service->vehicle->production_year }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-hash"></i> <strong>Kode Mesin:</strong></span>
                                            <span>{{ $service->vehicle->engine_code }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">Informasi Pelanggan</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <span><i class="bi bi-person"></i> <strong>Nama:</strong></span>
                                            <span>{{ $service->vehicle->customer->name }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-phone"></i> <strong>Kontak:</strong></span>
                                            <span>{{ $service->vehicle->customer->contact }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-house"></i> <strong>Alamat:</strong></span>
                                            <span>{{ $service->vehicle->customer->address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">Sparepart yang Digunakan</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @foreach ($service->serviceSpareparts as $serviceSparepart)
                                        <div class="list-group-item">
                                            <span><i class="bi bi-gear"></i> <strong>{{ $serviceSparepart->sparepart->nama_sparepart }}</strong></span>
                                            <span>{{ $serviceSparepart->quantity }} pcs</span>
                                            <span>Rp. {{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">Informasi Pekerjaan</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <ol>
                                        @foreach ($service->checklists as $checklist)
                                        <div class="list-group-item">
                                            <li>
                                            <span>{{ $checklist->task }}</span>
                                            </li>
                                        </div>
                                        @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header">Informasi Servis</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <span><i class="bi bi-exclamation-circle"></i> <strong>Keluhan:</strong></span>
                                            <span>{{ $service->complaint }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-tachometer"></i> <strong>Kilometer Saat Ini:</strong></span>
                                            <span>{{ $service->current_mileage }} km</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-cash-stack"></i> <strong>Biaya Servis:</strong></span>
                                            <span>Rp. {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-cash-coin"></i> <strong>Total Biaya:</strong></span>
                                            <span>Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-credit-card"></i> <strong>Pembayaran Diterima:</strong></span>
                                            <span>Rp. {{ number_format($service->payment_received, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-cash"></i> <strong>Kembalian:</strong></span>
                                            <span>Rp. {{ number_format($service->change, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-wrench"></i> <strong>Jenis Servis:</strong></span>
                                            <span>{{ ucfirst($service->service_type) }}</span>
                                        </div>
                                        <div class="list-group-item">
                                            <span><i class="bi bi-calendar-event"></i> <strong>Tanggal Servis:</strong></span>
                                            <span>{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="total">
                                <p><strong>Total:</strong> Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</p>
                            </div>
                            <div class="footer">
                                <p><i class="bi bi-emoji-smile"></i> Terima kasih atas kunjungan Anda!</p>
                                <p>Teknisi: {{ ucfirst($service->technician_name) }}</p>
                            </div>
                        </div>
                    </body>
                </html>
                `);

            newWindow.document.close();
            newWindow.print();

        });
    </script>
@endsection
