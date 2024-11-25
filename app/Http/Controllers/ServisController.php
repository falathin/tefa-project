<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\ServiceSparepart;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServisController extends Controller
{
    // Fungsi untuk menampilkan halaman index servis dengan pagination
    public function index()
    {
        $servis = Servis::paginate(6);
        return view('servis.index', compact('servis'));
    }

    // Fungsi untuk menampilkan halaman form pembuatan servis baru
    public function create()
    {
        $spareparts = Sparepart::all();
        $kendaraans = Kendaraan::all();
        return view('servis.create', compact('spareparts', 'kendaraans'));
    }

    public function store(Request $request)
    {
        // Custom validation for sparepart_id and jumlah fields
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_polisi' => 'required|string|max:20|unique:kendaraans,no_polisi',
            'jenis_kendaraan' => 'required|string|max:50',
            'warna' => 'nullable|string|max:50',
            'kode_mesin' => 'nullable|string|max:50',
            'tahun_produksi' => 'nullable|string|max:4',
            'keluhan' => 'required|string',
            'kilometer_saat_ini' => 'required|integer',
            'harga_jasa' => 'required|numeric',
            'total_biaya' => 'required|numeric',
            'uang_masuk' => 'required|numeric',
            'jenis_servis' => 'required|in:ringan,sedang,berat',
            'sparepart_id.*' => 'required_with:jumlah.*|nullable|exists:spareparts,id_sparepart',
            'jumlah.*' => 'required_with:sparepart_id.*|nullable|integer|min:1',
        ]);

        // Create or retrieve Pelanggan
        $pelanggan = Pelanggan::firstOrCreate(
            ['nama_pelanggan' => $request->nama_pelanggan],
            ['kontak' => $request->kontak, 'alamat' => $request->alamat]
        );

        // Check for duplicate nomor_polisi
        $existingKendaraan = Kendaraan::where('no_polisi', $request->nomor_polisi)->first();
        if ($existingKendaraan) {
            return redirect()->back()->withErrors(['nomor_polisi' => 'Nomor Polisi sudah ada, buatlah nomor polisi yang lain.']);
        }

        // Create Kendaraan
        $kendaraan = Kendaraan::create([
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'kode_mesin' => $request->kode_mesin,
            'tahun_produksi' => $request->tahun_produksi,
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'no_polisi' => $request->nomor_polisi,
        ]);

        // Calculate change
        $total_biaya = $request->total_biaya;
        $uang_masuk = $request->uang_masuk;
        $kembalian = $uang_masuk - $total_biaya;

        // Create Servis
        $servis = Servis::create([
            'nomor_polisi' => $kendaraan->no_polisi,
            'keluhan' => $request->keluhan,
            'kilometer_saat_ini' => $request->kilometer_saat_ini,
            'harga_jasa' => $request->harga_jasa,
            'total_biaya' => $total_biaya,
            'uang_masuk' => $uang_masuk,
            'kembalian' => $kembalian,
            'jenis_servis' => $request->jenis_servis,
            'tanggal_servis' => $request->tanggal_servis,
        ]);

        // Calculate total profit from spareparts
        $total_keuntungan = 0;
        if ($request->sparepart_id) {
            foreach ($request->sparepart_id as $index => $sparepart_id) {
                $sparepart = Sparepart::find($sparepart_id);

                // Check stock availability
                if ($sparepart->jumlah < $request->jumlah[$index]) {
                    return redirect()->back()->withErrors(['sparepart_id' => 'Stok sparepart ' . $sparepart->nama_sparepart . ' tidak mencukupi. Tersisa ' . $sparepart->jumlah . ' unit.']);
                }

                // Calculate profit
                $keuntungan_per_sparepart = $sparepart->harga_jual - $sparepart->harga_beli;
                $total_keuntungan += $keuntungan_per_sparepart * $request->jumlah[$index];

                // Attach sparepart to servis
                DB::table('servis_sparepart')->insert([
                    'servis_id' => $servis->id,
                    'sparepart_id' => $sparepart_id,
                    'jumlah' => $request->jumlah[$index],
                ]);

                // Update stock
                $sparepart->decrement('jumlah', $request->jumlah[$index]);
            }
        }

        // Update Servis with profit
        $servis->update(['total_keuntungan' => $total_keuntungan]);

        // Redirect with success message
        return redirect()->route('servis.index')->with('success', 'Servis berhasil ditambahkan!');
    }

    public function show($id)
    {
        $servis = Servis::with(['spareparts', 'kendaraan.pelanggan'])
                        ->where('id_servis', $id)
                        ->firstOrFail();
    
        $serviceSparepart = ServiceSparepart::where('servis_id',$id)->get();
        
        return view('servis.show', compact('servis', 'serviceSparepart'));
    }      
    
    public function edit($id)
    {
        // Ambil data servis, spareparts, dan kendaraan untuk ditampilkan pada form edit
        $servis = Servis::findOrFail($id);
        $spareparts = Sparepart::all();
        $kendaraans = Kendaraan::all();

        return view('servis.edit', compact('servis', 'spareparts', 'kendaraans'));
    }

    // public function update(Request $request, $id)
    // {
    //     // Validasi data
    //     $request->validate([
    //         'nama_pelanggan' => 'required|string|max:255',
    //         'kontak' => 'required|string|max:255',
    //         'alamat' => 'nullable|string',
    //         'nomor_polisi' => 'required|string|max:20',
    //         'jenis_kendaraan' => 'required|string|max:50',
    //         'keluhan' => 'required|string',
    //         'kilometer_saat_ini' => 'required|integer',
    //         'harga_jasa' => 'required|integer',
    //         'total_biaya' => 'required|integer',
    //         'uang_masuk' => 'required|integer',
    //         'jenis_servis' => 'required|string',
    //     ]);

    //     $servis = Servis::findOrFail($id);
    //     $servis->update($request->except(['sparepart_id', 'jumlah']));

    //     // Update spareparts
    //     $spareparts = $request->input('sparepart_id');
    //     $jumlah = $request->input('jumlah');
        
    //     $servis->spareparts()->sync([]);
        
    //     foreach ($spareparts as $key => $sparepart_id) {
    //         $servis->spareparts()->attach($sparepart_id, ['jumlah' => $jumlah[$key]]);
    //     }

    //     return redirect()->route('servis.index')->with('success', 'Servis berhasil diperbarui');
    // }

}