@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Card Container -->
    <div class="card shadow-lg border-0 animate__animated animate__fadeIn" 
         style="animation-duration: 1.5s; background-color: #f9fafb;">
        <!-- Card Header -->
        <div class="card-header text-white text-center py-3" style="background-color: #007bff;">
            <h4 class="mb-0"><i class="mdi mdi-wrench"></i> Service Details</h4>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Vehicle Information -->
            <h5 class="card-title text-center mb-4 animate__animated animate__fadeInDown" 
                style="animation-duration: 1.5s; color: #007bff; font-weight: bold;">
                Vehicle: <strong>{{ $service->vehicle->license_plate }}</strong> 
                <span class="badge" 
                      style="background-color: #17a2b8; color: #fff; font-size: 0.9em;">
                      {{ $service->vehicle->vehicle_type }}
                </span>
            </h5>

            <div class="row mb-5">
                <!-- Left Column -->
                <div class="col-md-6">
                    <!-- Vehicle Info -->
                    <div class="border p-3 rounded shadow-sm bg-white animate__animated animate__fadeInLeft" 
                         style="animation-duration: 1.5s; border-color: #e3e6f0;">
                        <h6 style="color: #6c757d;"><i class="mdi mdi-car"></i> Vehicle Information</h6>
                        <p><strong>Color:</strong> {{ $service->vehicle->color }}</p>
                        <p><strong>Production Year:</strong> {{ $service->vehicle->production_year }}</p>
                        <p><strong>Engine Code:</strong> {{ $service->vehicle->engine_code }}</p>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="border p-4 rounded shadow-sm bg-white mt-4 animate__animated animate__fadeInLeft" 
                         style="animation-duration: 1.5s; border-color: #e3e6f0;">
                        <h6 style="color: #6c757d;"><i class="mdi mdi-account"></i> Customer Information</h6>
                        <p><strong>Name:</strong> {{ $service->vehicle->customer->name }}</p>
                        <p><strong>Contact:</strong> {{ $service->vehicle->customer->contact }}</p>
                        <p><strong>Address:</strong> {{ $service->vehicle->customer->address }}</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <!-- Service Info -->
                    <div class="border p-3 rounded shadow-sm bg-white animate__animated animate__fadeInRight" 
                         style="animation-duration: 1.5s; border-color: #e3e6f0;">
                        <h6 style="color: #6c757d;"><i class="mdi mdi-information"></i> Service Information</h6>
                        <p><strong>Complaint:</strong> {{ $service->complaint }}</p>
                        <p><strong>Current Mileage:</strong> {{ $service->current_mileage }} km</p>
                        <p><strong>Service Fee:</strong> <span style="color: #28a745;">Rp. {{ number_format($service->service_fee, 0, ',', '.') }}</span></p>
                        <p><strong>Total Cost:</strong> <span style="color: #dc3545;">Rp. {{ number_format($service->total_cost, 0, ',', '.') }}</span></p>
                        <p><strong>Payment Received:</strong> <span style="color: #17a2b8;">Rp. {{ number_format($service->payment_received, 0, ',', '.') }}</span></p>
                        <p><strong>Change:</strong> Rp. {{ number_format($service->change, 0, ',', '.') }}</p>
                        <p><strong>Service Type:</strong> 
                            <span class="badge" style="background-color: #28a745; color: #fff;">
                                {{ ucfirst($service->service_type) }}
                            </span>
                        </p>
                        <p><strong>Service Date:</strong> 
                            <span style="color: #6c757d;">
                                {{ \Carbon\Carbon::parse($service->service_date)->format('d-m-Y') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Spareparts Information -->
            <h6 class="mb-3 animate__animated animate__fadeInUp" 
                style="animation-duration: 1.5s; color: #6c757d;">
                <i class="mdi mdi-tools"></i> Spareparts Used
            </h6>
            <div class="table-responsive">
                <table class="table table-hover animate__animated animate__fadeIn" style="animation-duration: 1.5s;">
                    <thead style="background-color: #007bff; color: #fff;">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($service->serviceSpareparts as $serviceSparepart)
                        <tr>
                            <td>{{ $serviceSparepart->sparepart->nama_sparepart }}</td>
                            <td>{{ $serviceSparepart->quantity }}</td>
                            <td>Rp. {{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        <!-- Action Buttons -->
        <div class="mt-3 d-flex flex-wrap gap-2 align-items-center animate__animated animate__fadeInUp" 
            style="animation-duration: 1.5s;">
            
            <!-- Back to Vehicle Button -->
            @if($service->vehicle)
            <a href="{{ route('vehicle.show', $service->vehicle->id) }}" class="btn btn-secondary btn-sm">
                <i class="mdi mdi-car me-2"></i> Kembali
            </a>
            @endif

            <!-- Print Button -->
            <button class="btn btn-primary btn-sm" onclick="printReceipt()">
                <i class="mdi mdi-printer me-2"></i> Cetak
            </button>

            <!-- Edit Button -->
            <a href="{{ route('service.edit', $service->id) }}" class="btn btn-warning btn-sm">
                <i class="mdi mdi-pencil me-2"></i> Edit
            </a>

            <!-- Delete Button -->
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
    function printReceipt() {
        var printContent = document.querySelector('.container').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>

@endsection