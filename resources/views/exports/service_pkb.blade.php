<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <title>PKB Service</title>
  </head>
  <body style="font-family: Arial, sans-serif; font-size: 14px; background-color: #f5f5f5; color: #333; margin: 20px;">
    @php
      $jurusan = strtolower($service->jurusan);
      $emoji = ($jurusan == 'tkro') ? '🚗' : (($jurusan == 'tsm') ? '🏍️' : '🚘');
      
      // Penentuan emoji untuk kilometer: hijau (dekat), kuning (sedang), merah (jauh)
      $currentMileage = $service->current_mileage;
      if ($currentMileage < 50000) {
        $kmEmoji = '🟢';
      } elseif ($currentMileage < 150000) {
        $kmEmoji = '🟡';
      } else {
        $kmEmoji = '🔴';
      }
    @endphp
    <!-- Tabel Utama PKB Service -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #fff; border: 2px solid #000;">
      <!-- Baris Header -->
      <tr>
        <td colspan="5" style="text-align: center; font-weight: bold; font-size: 22px; padding: 10px; background-color: #dc3545; color: #fff; border: 2px solid #000; white-space: normal; word-break: break-word;">
          {{ $emoji }} PERINTAH KERJA BENGKEL </td>
      </tr>
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
      <!-- Informasi Pelanggan & Kendaraan -->
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">👤 Nama Pelanggan</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->vehicle->customer->name ?? '-' }}</td>
        <td style="border: 1px solid #000;"></td>
        <td rowspan="4" style="vertical-align: top; padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">
          <strong>🚦 No. Polisi:</strong> {{ $service->vehicle->license_plate ?? '-' }}<br>
          <strong>🚘 Tipe Kendaraan:</strong> {{ $service->vehicle->vehicle_type ?? '-' }}<br>
          <strong>🎨 Warna:</strong> {{ $service->vehicle->color ?? '-' }}<br>
          <strong>📅 Tahun:</strong> {{ $service->vehicle->production_year ?? '-' }}
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🔢 Kode Pelanggan</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->vehicle->customer->id ?? '-' }}</td>
        <td style="border: 1px solid #000;"></td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🏠 Alamat</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->vehicle->customer->address ?? '-' }}</td>
        <td style="border: 1px solid #000;"></td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">📞 Telepon</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->vehicle->customer->contact ?? '-' }}</td>
        <td style="border: 1px solid #000;"></td>
      </tr>
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
      <!-- Informasi Servis -->
      <tr>
        <td colspan="5" style="font-weight: bold; background-color: #e2efda; text-transform: uppercase; padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">🔧 Informasi Servis</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">⏱️ Kilometer</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <!-- Tampilkan emoji kilometer sesuai nilai -->
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          {{ $kmEmoji }} {{ $service->current_mileage }} KM
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">😷 Keluhan</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          {{ $service->complaint }}<br>
          <textarea style="width: 100%; min-height: 200px; border: 1px dashed #007bff; padding: 10px; margin-top: 5px; background-color: #fff; resize: vertical;" placeholder="Tuliskan keluhan tambahan di sini..."></textarea>
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">📅 Tanggal Servis</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->service_date }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">👨‍🔧 Teknisi</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->technician_name }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">📝 Catatan Tambahan</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          {{ $service->additional_notes }}<br>
          <textarea style="width: 100%; min-height: 200px; border: 1px dashed #007bff; padding: 10px; margin-top: 5px; background-color: #fff; resize: vertical;" placeholder="Tuliskan pekerjaan tambahan di sini..."></textarea>
        </td>
      </tr>
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
      <!-- Sparepart yang Digunakan -->
      <tr>
        <td colspan="5" style="font-weight: bold; background-color: #e2efda; text-transform: uppercase; padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">🔩🛠 Sparepart yang Digunakan</td>
      </tr>
      <tr>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🔧 Nama Sparepart</td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🧮 Jumlah</td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">💵 Harga Satuan</td>
        <td style="font-weight: bold; text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🧾 Subtotal</td>
        <td style="text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">&nbsp;</td>
      </tr>
      @php
        $numSpareparts = count($service->serviceSpareparts);
      @endphp
      @foreach($service->serviceSpareparts as $s)
      <tr>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">{{ $s->sparepart->nama_sparepart }}</td>
        <td style="padding: 10px 12px; text-align: center; border: 1px solid #000; white-space: normal; word-break: break-word;">{{ $s->quantity }}</td>
        <td style="padding: 10px 12px; text-align: right; border: 1px solid #000; white-space: normal; word-break: break-word;">Rp.{{ number_format($s->sparepart->harga_jual, 0, ',', '.') }}</td>
        <td style="padding: 10px 12px; text-align: right; border: 1px solid #000; white-space: normal; word-break: break-word;">Rp.{{ number_format($s->quantity * $s->sparepart->harga_jual, 0, ',', '.') }}</td>
        <td style="text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">&nbsp;</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
      <!-- Checklist Pekerjaan -->
      <tr>
        <td colspan="5" style="font-weight: bold; background-color: #e2efda; text-transform: uppercase; padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">✅ Pekerjaan (Checklist)</td>
      </tr>
      @php
        $numChecklists = count($service->checklists);
      @endphp
      @foreach($service->checklists as $c)
      <tr>
        <td colspan="3" style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">{{ $c->task }}</td>
        <td colspan="2" style="padding: 10px 12px; text-align: center; border: 1px solid #000; white-space: normal; word-break: break-word;">
          @if($c->is_completed)
            <span style="color: #28a745; font-weight: bold;">🟢 Selesai</span>
          @else
            <span style="color: #dc3545; font-weight: bold;">🔴 Belum Selesai</span>
          @endif
        </td>
      </tr>
      @endforeach
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
      <!-- Total Biaya -->
      <tr>
        <td colspan="3" style="text-align: right; font-weight: bold; padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">💰 Total Biaya</td>
        <td colspan="2" style="font-weight: bold; padding: 10px 12px; text-align: right; border: 1px solid #000; white-space: normal; word-break: break-word;">Rp.{{ number_format($service->total_cost, 0, ',', '.') }}</td>
      </tr>
      <!-- Informasi Tambahan -->      
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          @php
            $jurusan = strtolower(Auth::user()->jurusan);
            $emojiServiceType = ($jurusan == 'tkro') ? '🚗' : (($jurusan == 'tsm') ? '🏍️' : '🚘');
          @endphp
          {{ $emojiServiceType }} Tipe Servis
        </td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">
          @if(strtolower($service->service_type) == 'light')
            Ringan
          @elseif(strtolower($service->service_type) == 'medium')
            Sedang
          @elseif(strtolower($service->service_type) == 'heavy')
            Berat
          @else
            {{ $service->service_type }}
          @endif
        </td>
      </tr>      
      @if(strtolower($service->jurusan) == 'tkro')
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">⏱️ Interval Servis</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          @if(strtolower($service->service_type) == 'light')
            10.000 KM
          @elseif(strtolower($service->service_type) == 'medium')
            30.000 KM
          @elseif(strtolower($service->service_type) == 'heavy')
            50.000 KM
          @endif
        </td>
      </tr>
      @endif
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">📊 Status</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; text-align: center; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">
          @if($service->status == 1)
            <span style="color: #28a745; font-weight: bold;">🟢 Selesai</span>
          @else
            <span style="color: #dc3545; font-weight: bold;">🔴 Belum Selesai</span>
          @endif
        </td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🏫 Jurusan</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->jurusan }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">💸 Diskon</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">Rp.{{ number_format($service->diskon, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">💳 Metode Pembayaran</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">{{ $service->payment_method }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🔧 Biaya Servis</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">Rp.{{ number_format($service->service_fee, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">🤑 Pembayaran Diterima</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">Rp.{{ number_format($service->payment_received, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td style="font-weight: bold; color: #007bff; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">💵 Kembalian</td>
        <td style="padding: 10px 12px; border: 1px solid #000; white-space: normal; word-break: break-word;">:</td>
        <td colspan="3" style="padding-left: 5px; border: 1px solid #000; padding: 10px 12px; white-space: normal; word-break: break-word;">Rp.{{ number_format($service->change, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td colspan="5" style="padding: 10px; border: 1px solid #000;"></td>
      </tr>
    </table>

    @php
      // Menghitung estimasi waktu servis menggunakan PHP dengan multiplier yang lebih akurat:
      $tipeServis = strtolower($service->service_type);
      $waktuDasar = 20; // default
      if ($tipeServis === 'light') {
        $waktuDasar = 30;
      } elseif ($tipeServis === 'medium') {
        $waktuDasar = 60;
      } elseif ($tipeServis === 'heavy') {
        $waktuDasar = 90;
      }
      // Multiplier: tiap checklist 7 menit, tiap sparepart 3 menit
      $waktuChecklist = $numChecklists * 7;
      $waktuSparepart = $numSpareparts * 3;
      $totalWaktu = $waktuDasar + $waktuChecklist + $waktuSparepart;
    @endphp

    <!-- Kontainer Estimasi Waktu Servis -->
    <div style="padding: 10px; margin-top: 10px; border: 2px solid #28a745; background-color: #d4edda; font-weight: bold; color: #155724;" id="estimasiBox">
      ⏱️ Estimasi Waktu Servis: {{ $totalWaktu }} Menit
    </div>

    <br><br><br><br><br><br><br><br><br><br>
    <!-- Akhir dokumen PKB Service -->
  </body>
</html>