@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <h4 class="text-center mb-4">Edit Service untuk Kendaraan: {{ $service->license_plate }}</h4>
                <form method="POST" action="{{ route('service.update', $service->id) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="vehicle_id" value="{{ $service->vehicle_id }}">
                    <div class="row">
                        <!-- Kategori Informasi Servis -->
                        <div class="col-md-12 mb-3">
                            <h5 class="fw-bold">Informasi Servis</h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="complaint" class="form-label">Keluhan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                <input type="text" name="complaint" class="form-control"
                                    placeholder="Masukkan keluhan kendaraan"
                                    value="{{ old('complaint', $service->complaint) }}" required>
                            </div>
                            @error('complaint')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="current_mileage" class="form-label">Kilometer Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                <input type="number" name="current_mileage" class="form-control"
                                    placeholder="Masukkan kilometer kendaraan"
                                    value="{{ old('current_mileage', $service->current_mileage) }}" required>
                            </div>
                            @error('current_mileage')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_date" class="form-label">Tanggal Service</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="service_date" id="service_date" class="form-control"
                                    value="{{ old('service_date', $service->service_date) }}" required>
                            </div>
                            @error('service_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label">Jenis Service</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tools"></i></span>
                                <select name="service_type" class="form-control" required>
                                    <option value="light"
                                        {{ old('service_type', $service->service_type) == 'light' ? 'selected' : '' }}>
                                        Ringan</option>
                                    <option value="medium"
                                        {{ old('service_type', $service->service_type) == 'medium' ? 'selected' : '' }}>
                                        Sedang</option>
                                    <option value="heavy"
                                        {{ old('service_type', $service->service_type) == 'heavy' ? 'selected' : '' }}>
                                        Berat</option>
                                </select>
                            </div>
                            @error('service_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="technician_name" class="form-label">Nama Teknisi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                <input type="text" name="technician_name" id="technician_name" class="form-control"
                                    placeholder="Masukkan nama teknisi"
                                    value="{{ old('technician_name', $service->technician_name) }}" required>
                            </div>
                            @error('technician_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Sparepart -->
                        <div
                            class="card-header rounded bg-danger text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-wrench"></i> &nbsp; Tambah Informasi Sparepart</h5>
                            <small class="text-right"><b>*</b> Hapus jika tidak diperlukan</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @if ($spareparts->isNotEmpty())
                                    <table class="table table-bordered" id="sparepartTable">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-wrench"></i> Nama Sparepart</th>
                                                <th><i class="fas fa-tag"></i> Harga Satuan</th>
                                                <th><i class="fas fa-plus-circle"></i> Jumlah yang Diambil</th>
                                                <th><i class="fas fa-calculator"></i> Subtotal</th>
                                                <th><i class="fas fa-trash-alt"></i> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('sparepart_id') && old('jumlah'))
                                                @foreach (old('sparepart_id') as $index => $sparepart_id)
                                                    <tr>
                                                        <td>
                                                            <select name="sparepart_id[]" class="form-control sparepart_id">
                                                                @foreach ($spareparts as $sparepart)
                                                                    <option value="{{ $sparepart->id_sparepart }}"
                                                                        {{ $sparepart->id_sparepart == $sparepart_id ? 'selected' : '' }}>
                                                                        {{ $sparepart->nama_sparepart }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('sparepart_id.' . $index)
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control harga"
                                                                value="{{ number_format($spareparts->where('id_sparepart', $sparepart_id)->first()->harga_jual ?? 0, 0, ',', '.') }}"
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="jumlah[]" class="form-control jumlah"
                                                                value="{{ old('jumlah')[$index] }}" min="1">
                                                            @error('jumlah.' . $index)
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control subtotal"
                                                                value="{{ number_format(($spareparts->where('id_sparepart', $sparepart_id)->first()->harga_jual ?? 0) * old('jumlah')[$index], 0, ',', '.') }}"
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @elseif (isset($service->serviceSpareparts) && $service->serviceSpareparts->isNotEmpty())
                                                @foreach ($service->serviceSpareparts as $serviceSparepart)
                                                    <tr data-sparepart-id="{{ $serviceSparepart->sparepart_id }}">
                                                        <td>
                                                            <select name="sparepart_id[]" class="form-control sparepart_id">
                                                                @foreach ($spareparts as $sparepart)
                                                                    <option value="{{ $sparepart->id_sparepart }}"
                                                                        {{ $sparepart->id_sparepart == $serviceSparepart->sparepart_id ? 'selected' : '' }}>
                                                                        {{ $sparepart->nama_sparepart }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('sparepart_id.' . $loop->index)
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control harga"
                                                                value="{{ number_format($serviceSparepart->sparepart->harga_jual, 0, ',', '.') }}"
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="jumlah[]" class="form-control jumlah"
                                                                value="{{ $serviceSparepart->quantity }}" min="1">
                                                            @error('jumlah.' . $loop->index)
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control subtotal"
                                                                value="{{ number_format($serviceSparepart->sparepart->harga_jual * $serviceSparepart->quantity, 0, ',', '.') }}"
                                                                readonly>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger removeRow"><i class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>                                        
                                    </table>
                                    <br>
                                    <button type="button" class="btn btn-primary" id="addRow">+ Tambah
                                        Sparepart</button>
                                @endif

                            </div>

                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>

                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        $(document).ready(function() {
                            // Event hapus baris tanpa menghapus data sparepart
                            $(document).on('click', '.removeRow', function() {
                                const row = $(this).closest('tr');
                                row.remove();
                                updateTotal();
                            });

                            // Fungsi update total keseluruhan
                            function updateTotal() {
                                let total = 0;
                                $('.subtotal').each(function() {
                                    total += parseFloat($(this).val()) || 0;
                                });
                                $('#totalHarga').text(total.toFixed(2));
                            }
                        });
                    });
                </script>
                <script>
document.addEventListener('DOMContentLoaded', function() {
    var allSpareparts = @json($spareparts->map(function($sparepart) {
        return [
            'id_sparepart' => $sparepart->id_sparepart,
            'nama_sparepart' => $sparepart->nama_sparepart,
            'harga_jual' => $sparepart->harga_jual
        ];
    }));

    function getAvailableSpareparts() {
        let selectedIds = [];
        $('select[name="sparepart_id[]"]').each(function() {
            let val = $(this).val();
            if(val) selectedIds.push(val);
        });
        return allSpareparts.filter(sparepart => !selectedIds.includes(sparepart.id_sparepart.toString()));
    }

    function updateAllDropdowns() {
        $('select[name="sparepart_id[]"]').each(function() {
            let currentValue = $(this).val();
            let availableSpareparts = getAvailableSpareparts();
            
            let existingOptions = $(this).find('option').filter((i, opt) => opt.value === currentValue);
            
            $(this).empty().append(existingOptions);
            
            availableSpareparts.forEach(sparepart => {
                if(sparepart.id_sparepart != currentValue) {
                    $(this).append(
                        $('<option>', {
                            value: sparepart.id_sparepart,
                            'data-harga': sparepart.harga_jual,
                            text: sparepart.nama_sparepart
                        })
                    );
                }
            });
        });
    }

    $('#addRow').on('click', function() {
        let availableSpareparts = getAvailableSpareparts();
        if(availableSpareparts.length === 0) {
            alert('Semua sparepart sudah dipilih!');
            return;
        }
        
        let optionsHtml = '<option value="">Pilih Sparepart</option>';
        availableSpareparts.forEach(sparepart => {
            optionsHtml += `<option value="${sparepart.id_sparepart}" data-harga="${sparepart.harga_jual}">${sparepart.nama_sparepart}</option>`;
        });

        const newRow = $(`
            <tr>
                <td>
                    <select name="sparepart_id[]" class="form-control sparepart_id">
                        ${optionsHtml}
                    </select>
                </td>
                <td><input type="text" class="form-control harga" readonly></td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1"></td>
                <td><input type="text" class="form-control subtotal" readonly></td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);
        
        $('#sparepartTable tbody').append(newRow);
        updateAllDropdowns();
        updateTotal();
    });

    $(document).on('change', '.sparepart_id', function() {
        updateAllDropdowns();
        
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('harga') || 0;
        $(this).closest('tr').find('.harga').val(formatCurrency(price));
        calculateSubtotal($(this).closest('tr'));
        updateTotal();
    });

    $(document).on('input', '.jumlah', function() {
        calculateSubtotal($(this).closest('tr'));
        updateTotal();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        updateAllDropdowns();
        updateTotal();
    });

    function calculateSubtotal(row) {
        const price = parseFloat(row.find('.harga').val().replace(/[^0-9,-]+/g,"")) || 0;
        const quantity = parseInt(row.find('.jumlah').val()) || 1;
        const subtotal = price * quantity;

        row.find('.subtotal').val(formatCurrency(subtotal));
    }

    function updateTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            let value = $(this).val().replace(/[^0-9,-]+/g, "") || "0";
            total += parseFloat(value);
        });
        $('#totalHarga').text(formatCurrency(total));
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR' 
        }).format(amount);
    }

    updateAllDropdowns();
});

                </script>
    
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(amount);
            }
    
            function calculateSubtotal(row) {
                const harga = parseFloat(row.querySelector('.harga').value.replace(/[^\d,-]/g, '')) || 0;
                const jumlah = parseInt(row.querySelector('.jumlah').value) || 1;
                const subtotal = harga * jumlah;
                row.querySelector('.subtotal').value = formatCurrency(subtotal);
            }
    
            document.addEventListener('input', function (event) {
                if (event.target.classList.contains('jumlah')) {
                    calculateSubtotal(event.target.closest('tr'));
                }
            });
    
            document.addEventListener('change', function (event) {
                if (event.target.classList.contains('sparepart_id')) {
                    const selectedOption = event.target.options[event.target.selectedIndex];
                    const harga = selectedOption.getAttribute('data-harga') || '0';
                    const row = event.target.closest('tr');
                    row.querySelector('.harga').value = formatCurrency(parseFloat(harga));
                    calculateSubtotal(row);
                }
            });
    
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('removeRow')) {
                    event.target.closest('tr').remove();
                }
            });
        });
    </script>
    
@endsection
