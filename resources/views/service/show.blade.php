@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <!-- Card Container -->
        <div class="card shadow-lg border-0 animate__animated animate__fadeIn animate__delay-1s"
            style="animation-duration: 1.5s; background-color: #f9fafb;">
            <!-- Card Header -->
            <div class="card-header text-white text-center py-3 animate__animated animate__fadeInDown"
                style="animation-duration: 1.5s; animation-delay: 0.2s; background-color: #007bff;">
                <h4 class="mb-0"><i class="mdi mdi-wrench"></i> Detail Servis</h4>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Informasi Kendaraan -->
                <h5 class="card-title text-center mb-4 animate__animated animate__zoomIn"
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
                        <div class="border p-3 rounded shadow-sm bg-white animate__animated animate__fadeInLeft"
                            style="animation-duration: 1.5s; animation-delay: 0.5s; border-color: #e3e6f0;">
                            <h6 class="text-muted"><i class="mdi mdi-car"></i> Informasi Kendaraan</h6>
                            <p><strong>Warna:</strong> {{ $service->vehicle->color }}</p>
                            <p><strong>Tahun Produksi:</strong> {{ $service->vehicle->production_year }}</p>
                            <p><strong>Kode Mesin:</strong> {{ $service->vehicle->engine_code }}</p>
                        </div>

                        <!-- Informasi Pelanggan -->
                        <div class="border p-3 rounded shadow-sm bg-white mt-4 animate__animated animate__fadeInLeft"
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
                        <div class="border p-3 rounded shadow-sm bg-white animate__animated animate__fadeInRight"
                            style="animation-duration: 1.5s; animation-delay: 0.5s; border-color: #e3e6f0;">
                            <h6 class="text-muted"><i class="mdi mdi-information"></i> Informasi Servis</h6>
                            <p><strong>Keluhan:</strong> {{ $service->complaint }}</p>
                            <p><strong>Jarak Tempuh:</strong> {{ $service->current_mileage }} km</p>
                            <p><strong>Jasa Pelayanan:</strong> <span style="color: #28a745;">Rp.
                                    {{ number_format($service->service_fee, 0, ',', '.') }}</span></p>
                            <p><strong>Total Biaya:</strong> <span style="color: #dc3545;">Rp.
                                    {{ number_format($service->total_cost, 0, ',', '.') }}</span></p>
                            <p><strong>Pembayaran Diterima:</strong> <span style="color: #17a2b8;">Rp.
                                    {{ number_format($service->payment_received, 0, ',', '.') }}</span></p>
                            <p><strong>Kembalian:</strong> Rp. {{ number_format($service->change, 0, ',', '.') }}</p>
                            <p><strong>Tipe Servis:</strong>
                                <span class="badge" style="background-color: #28a745; color: #fff;">
                                    {{ ucfirst($service->service_type == 'light' ? 'ringan' : ($service->service_type == 'medium' ? 'sedang' : 'berat')) }}
                                </span>
                            </p>
                            <p><strong>Tanggal Servis:</strong>
                                <span
                                    class="text-muted">{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}</span>
                            </p>
                            <p><strong>Status Pembayaran:</strong>
                                <span class="badge"
                                    style="background-color: {{ $service->isPaid() ? '#28a745' : '#dc3545' }}; color: #fff;">
                                    {{ $service->isPaid() ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border p-3 rounded shadow-sm bg-white animate__animated animate__fadeIn animate__delay-1s"
                    style="animation-duration: 1.5s; background-color: white; border-color: #e3e6f0;">
                    <div class="card-body">
                        <h6 class="mb-4 text-muted">
                            <i class="fas fa-tasks"></i> Pekerjaan
                        </h6>
                    
                        <form action="{{ route('service.addChecklist', $service->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="task" class="form-control form-control-sm" placeholder="Tambah item pekerjaan baru" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-circle"></i> Tambah Pekerjaan
                            </button>
                        </form>
                    
                        <ul class="list-group mt-4" id="checklist-list">
                            @foreach ($service->checklists as $checklist)
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-md-row">
                                    <!-- Desktop and mobile views for checklist item -->
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <!-- Checkbox and Task Description -->
                                        <form action="{{ route('service.updateChecklistStatus', $checklist->id) }}" method="POST" class="d-flex align-items-center w-100">
                                            @csrf
                                            @method('PATCH')
                                            <input type="checkbox" name="is_completed" onchange="this.form.submit()" {{ $checklist->is_completed ? 'checked' : '' }} class="form-check-input me-2">
                                            <span class="ms-3 {{ $checklist->is_completed ? 'text-decoration-line-through' : '' }}">{{ $checklist->task }}</span>
                                        </form>
                                        <span class="d-none d-md-block">
                                            <i class="fas md-block sm-block {{ $checklist->is_completed ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' }}"></i>
                                            {{ $checklist->is_completed ? 'Selesai' : 'Tertunda' }}
                                        </span>
                                        <small class="text-muted ms-3 d-none d-md-block">Ditambahkan pada: {{ $checklist->created_at->format('d M Y') }}</small>
                                    </div>
                        
                                    <!-- Dropdown Menu (Desktop) -->
                                    <div class="dropdown ms-3 d-none d-md-block">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButton-{{ $checklist->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $checklist->id }}">
                                            <button class="btn btn-link edit-btn" data-id="{{ $checklist->id }}" data-task="{{ $checklist->task }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <li>
                                                <form action="{{ route('service.deleteChecklist', $checklist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                        
                                    <!-- Mobile view: display dropdown and edit form -->
                                    <div class="dropdown ms-3 d-md-none">
                                        <button class="btn btn-link p-0" type="button" id="dropdownMenuButtonMobile-{{ $checklist->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonMobile-{{ $checklist->id }}">
                                            <button class="btn btn-link edit-btn" data-id="{{ $checklist->id }}" data-task="{{ $checklist->task }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <li>
                                                <form action="{{ route('service.deleteChecklist', $checklist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                        
                                    <!-- Edit Form Container (Mobile and Desktop) -->
                                    <div class="edit-form-container" id="edit-form-container-{{ $checklist->id }}" style="display: none; padding: 5px;">
                                        <form action="{{ route('service.updateChecklistTask', $checklist->id) }}" method="POST" class="mt-2">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group" style="display: flex; gap: 5px; align-items: center;">
                                                <input type="text" name="task" value="{{ $checklist->task }}" class="form-control form-control-sm" required
                                                    style="padding: 6px 10px; font-size: 12px; border-radius: 4px; border: 1px solid #ccc; width: 100%;">
                                                <button type="submit" class="btn btn-warning btn-sm" style="padding: 5px 10px; font-size: 12px; border-radius: 4px; background-color: #ff9800; color: white; border: none;">
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                                <button type="button" class="btn btn-secondary cancel-edit-btn btn-sm" data-id="{{ $checklist->id }}"
                                                    style="padding: 5px 10px; font-size: 12px; border-radius: 4px; background-color: #607d8b; color: white; border: none;">
                                                    <i class="fas fa-times"></i> Batal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>                        
                    
                        <script>
                            document.querySelectorAll('.edit-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const checklistId = this.getAttribute('data-id');
                                    const formContainer = document.getElementById(`edit-form-container-${checklistId}`);
                                    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
                                });
                            });
                    
                            document.querySelectorAll('.cancel-edit-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    const checklistId = this.getAttribute('data-id');
                                    const formContainer = document.getElementById(`edit-form-container-${checklistId}`);
                                    formContainer.style.display = 'none';
                                });
                            });
                        </script>
                    </div>
                </div>

                <br>
                <!-- Informasi Sparepart -->
                <h6 class="mb-3 mt-3 animate__animated animate__fadeInUp"
                    style="animation-duration: 1.5s; animation-delay: 0.8s; color: #6c757d;">
                    <i class="mdi mdi-tools"></i> Sparepart yang Digunakan
                </h6>
                <div class="table-responsive border p-3 rounded shadow-sm bg-white">
                    <table class="table table-hover animate__animated animate__zoomIn"
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
                                <tr class="animate__animated animate__flipInX">
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
                <br><br>

                <!-- Action Buttons -->
                <div class="mt-3 d-flex flex-wrap gap-2 align-items-center animate__animated animate__fadeInUp"
                    style="animation-duration: 1.5s; animation-delay: 1s;">
                    <!-- Kembali ke Kendaraan -->
                    @if ($service->vehicle)
                        <a href="{{ route('vehicle.show', $service->vehicle->id) }}" class="btn btn-dark btn-sm">
                            <i class="mdi mdi-car me-2"></i> Kembali
                        </a>
                    @endif

                    <!-- Cetak Button -->
                    <button id="printBtn" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-printer me-2"></i> Cetak
                    </button>

                    <!-- Salin Laporan Button -->
                    <button id="copyReportBtn" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-content-copy me-2"></i> Salin Laporan
                    </button>

                    <!-- Edit Button -->
                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                        <i class="mdi mdi-pencil me-2"></i> Edit
                    </a>

                    <!-- Hapus Button -->
                    <form action="{{ route('service.destroy', $service->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="mdi mdi-delete me-2"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.getElementById('copyReportBtn').addEventListener('click', function() {
            // Define variables for each section of the report with bold text, emojis, and a link
            var vehicleInfo =
                `**🚗 Informasi Kendaraan:**\nNomor Polisi: ${'{{ $service->vehicle->license_plate }}'} (${'{{ $service->vehicle->vehicle_type }}'})\nWarna: ${'{{ $service->vehicle->color }}'}\nTahun Produksi: ${'{{ $service->vehicle->production_year }}'}\nKode Mesin: ${'{{ $service->vehicle->engine_code }}'}`;
    
            var customerInfo =
                `**👤 Informasi Pelanggan:**\nNama: ${'{{ $service->vehicle->customer->name }}'}\nKontak: ${'{{ $service->vehicle->customer->contact }}'}\nAlamat: ${'{{ $service->vehicle->customer->address }}'}`;
    
            var serviceInfo =
                `**🛠️ Informasi Servis:**\nKeluhan: ${'{{ $service->complaint }}'}\nKilometer Saat Ini: ${'{{ $service->current_mileage }}'} km\nBiaya Servis: Rp. ${'{{ number_format($service->service_fee, 0, ',', '.') }}'}\nTotal Biaya: Rp. ${'{{ number_format($service->total_cost, 0, ',', '.') }}'}\nPembayaran Diterima: Rp. ${'{{ number_format($service->payment_received, 0, ',', '.') }}'}\nKembalian: Rp. ${'{{ number_format($service->change, 0, ',', '.') }}'}\nJenis Servis: ${'{{ ucfirst($service->service_type) }}'}\nTanggal Servis: ${'{{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}'}`;
    
            var sparepartsInfo = '**🔧 Sparepart yang Digunakan:**\n';
    
            // Loop through the spare parts and append their details with bold text and emojis
            @foreach ($service->serviceSpareparts as $serviceSparepart)
                sparepartsInfo +=
                    `Nama: **${'{{ $serviceSparepart->sparepart->nama_sparepart }}'}** | Jumlah: ${'{{ $serviceSparepart->quantity }}'} | Harga: Rp. ${'{{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}'}\n`;
            @endforeach
    
            // Collecting checklist items
            var checklistInfo = '**📝 Pekerjaan yang Dikerjakan:**\n';
            
            @foreach ($service->checklists as $checklist)
                checklistInfo += `- **${'{{ $checklist->task }}'}** ${'{{ $checklist->is_completed ? "✅ Selesai" : "❌ Tertunda" }}'}\n`;
            @endforeach
    
            // Update the phone number link to use WhatsApp
            var linkInfo = `\nAda masalah? Telepon via WhatsApp: [Chat dengan Jamat](https://wa.me/6285715467500)`;
    
            // Combine all the sections into one report
            var fullReport = `${vehicleInfo}\n\n${customerInfo}\n\n${serviceInfo}\n\n${sparepartsInfo}\n\n${checklistInfo}${linkInfo}`;
    
            // Create a temporary textarea element to hold the content
            var textarea = document.createElement('textarea');
            textarea.value = fullReport;
            document.body.appendChild(textarea);
    
            // Select the text in the textarea and copy it
            textarea.select();
            document.execCommand('copy');
    
            // Remove the textarea element from the DOM
            document.body.removeChild(textarea);
    
            // Optionally, show an alert to the user confirming the action
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
                                height: 100vh;
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
                                <div class="card-header">
                                    Informasi Kendaraan
                                </div>
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
                                <div class="card-header">
                                    Informasi Pelanggan
                                </div>
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
                                <div class="card-header">
                                    Informasi Servis
                                </div>
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
        
                            <div class="card mt-3">
                                <div class="card-header">
                                    Sparepart yang Digunakan
                                </div>
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
        
                            <div class="total">
                                <p><strong>Total:</strong> Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</p>
                            </div>
        
                            <div class="footer">
                                <p><i class="bi bi-emoji-smile"></i> Terima kasih atas kunjungan Anda!</p>
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