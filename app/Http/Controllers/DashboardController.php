<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceSparepart;
use App\Models\Vehicle;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
    
        // Total Pengunjung Hari Ini
        $totalVisitorsToday = Customer::whereDate('created_at', $today)->count();
    
        // Total Sparepart
        $totalSpareparts = Sparepart::sum('jumlah');
    
        // Total Sparepart yang Digunakan Hari Ini
        $totalSparepartsUsed = ServiceSparepart::whereHas('service', function($query) use ($today) {
            $query->whereDate('service_date', $today);
        })->sum('quantity');
        
        // Mengambil Data Keuntungan, Pengeluaran, dan Belum Dibayar untuk Hari Ini
        $totalProfit = Service::whereDate('service_date', $today)->sum('total_cost') - Service::whereDate('service_date', $today)->sum('service_fee');
        
        // Total Pengeluaran Berdasarkan Harga Beli Sparepart yang Digunakan Hari Ini
        $totalExpense = DB::table('service_spareparts')
            ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
            ->join('services', 'service_spareparts.service_id', '=', 'services.id')
            ->whereDate('services.service_date', $today)
            ->sum(DB::raw('service_spareparts.quantity * spareparts.harga_beli'));
        
        // Total Belum Dibayar Berdasarkan Pembayaran yang Belum Diterima
        $totalUnpaid = Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('total_cost') - Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('payment_received');
        
        // Menghitung Persentase Keuntungan, Pengeluaran, dan Belum Dibayar
        $profitPercentage = $totalProfit > 0 ? (($totalProfit / max($totalProfit, 1)) * 100) : 0;
        $expensePercentage = $totalExpense > 0 ? (($totalExpense / max($totalExpense, 1)) * 100) : 0;
        $unpaidPercentage = $totalUnpaid > 0 ? (($totalUnpaid / max($totalUnpaid, 1)) * 100) : 0;
    
        // Menghitung Keuntungan Rata-rata per Pelanggan
        $totalCustomers = Customer::count();
        $averageProfitPerCustomer = $totalCustomers > 0 ? $totalProfit / $totalCustomers : 0;
    
        // Total Pengunjung dan Kendaraan
        $totalVisitors = Customer::count();
        $totalVehicles = Vehicle::count();
    
        // Rata-rata Kendaraan per Pengunjung
        $averageVehiclesPerCustomer = $totalVisitors > 0 ? $totalVehicles / $totalVisitors : 0;
        
        // Data Chart (Grafik Profit per Hari)
        $chartData = Service::selectRaw('DATE(service_date) as date, SUM(total_cost) as total_profit')
            ->groupBy('date')
            ->orderBy('service_date')
            ->get();
        
        $chartLabels = $chartData->pluck('date')->toArray();
        $chartValues = $chartData->pluck('total_profit')->toArray();
        
        // Mengambil Semua Spareparts dan Kendaraan untuk Ditampilkan di View
        $spareparts = Sparepart::all();
        $vehicles = Vehicle::all();
    
        $data = [
            'service_date' => '2024-12-05',
            'total_profit' => $totalProfit,
        ];
    
        return view('home', compact(
            'spareparts', 'totalSpareparts', 'totalVisitorsToday', 'today', 'totalProfit', 'totalExpense', 'totalUnpaid',
            'profitPercentage', 'expensePercentage', 'unpaidPercentage', 'chartLabels', 'chartValues', 
            'totalVisitors', 'totalVehicles', 'data', 'averageVehiclesPerCustomer', 'totalSparepartsUsed', 'vehicles', 
            'averageProfitPerCustomer' // Pass the average profit per customer
        ));
    }    
}