<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceSparepart;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $totalVisitorsToday = Customer::whereDate('created_at', $today)->count();

        $totalSpareparts = Sparepart::sum('jumlah');

        $totalSparepartsUsed = ServiceSparepart::whereHas('service', function ($query) use ($today) {
            $query->whereDate('service_date', $today);
        })->sum('quantity');

        // Perhitungan total profit (keuntungan jasa service + keuntungan sparepart)
        $serviceProfit = Service::whereDate('service_date', $today)->sum('service_fee');
        $sparepartProfit = ServiceSparepart::whereHas('service', function ($query) use ($today) {
            $query->whereDate('service_date', $today);
        })
        ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
        ->sum(DB::raw('service_spareparts.quantity * (spareparts.harga_jual - spareparts.harga_beli)'));

        $totalProfit = $serviceProfit + $sparepartProfit;

        $totalExpense = DB::table('service_spareparts')
            ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
            ->join('services', 'service_spareparts.service_id', '=', 'services.id')
            ->whereDate('services.service_date', $today)
            ->sum(DB::raw('service_spareparts.quantity * spareparts.harga_beli'));

        $totalUnpaid = Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('total_cost') - 
            Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('payment_received');

        $profitPercentage = $totalProfit > 0 ? (($totalProfit / max($totalProfit, 1)) * 100) : 0;
        $expensePercentage = $totalExpense > 0 ? (($totalExpense / max($totalExpense, 1)) * 100) : 0;
        $unpaidPercentage = $totalUnpaid > 0 ? (($totalUnpaid / max($totalUnpaid, 1)) * 100) : 0;

        $totalCustomers = Customer::count();
        $averageProfitPerCustomer = $totalCustomers > 0 ? $totalProfit / $totalCustomers : 0;

        $totalVisitors = Customer::count();
        $totalVehicles = Vehicle::count();

        $averageVehiclesPerCustomer = $totalCustomers > 0 ? $totalVehicles / $totalCustomers : 0;

        $chartData = Service::selectRaw('DATE(service_date) as date, SUM(total_cost) as total_profit')
            ->groupBy(DB::raw('DATE(service_date)'))
            ->orderBy(DB::raw('DATE(service_date)'), 'asc')
            ->get();

        $chartLabels = $chartData->pluck('date')->toArray();
        $chartValues = $chartData->pluck('total_profit')->toArray();

        $monthlyChartData = Service::selectRaw('MONTH(service_date) as month, SUM(total_cost) as total_profit')
            ->groupBy(DB::raw('MONTH(service_date)'))
            ->orderBy(DB::raw('MONTH(service_date)'), 'asc')
            ->get();

        $monthlyChartValues = $monthlyChartData->pluck('total_profit')->toArray();

        $vehiclesPerWeekData = [
            'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'datasets' => [[
                'label' => 'Jumlah Kendaraan per Minggu',
                'data' => [
                    Vehicle::whereDay('created_at', 'Monday')->count(),
                    Vehicle::whereDay('created_at', 'Tuesday')->count(),
                    Vehicle::whereDay('created_at', 'Wednesday')->count(),
                    Vehicle::whereDay('created_at', 'Thursday')->count(),
                    Vehicle::whereDay('created_at', 'Friday')->count(),
                    Vehicle::whereDay('created_at', 'Saturday')->count(),
                    Vehicle::whereDay('created_at', 'Sunday')->count(),
                ],
                'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                'borderColor' => 'rgba(153, 102, 255, 1)',
                'borderWidth' => 3,
                'fill' => true,
                'tension' => 0.4
            ]]
        ];

        $spareparts = Sparepart::orderBy('created_at', 'asc')->get();
        $vehicles = Vehicle::orderBy('created_at', 'asc')->get();

        $data = [
            'service_date' => $today,
            'total_profit' => $totalProfit,
        ];

        $customers = Customer::with('vehicles')->get();

        $userId = Auth::user()->jurusan;
        $jurusanNotif = Notification::all()->where('jurusan', 'like', $userId)->count();
        

        return view('home', compact(
            'spareparts', 'totalSpareparts', 'totalVisitorsToday', 'today', 'totalProfit', 'totalExpense', 'totalUnpaid',
            'profitPercentage', 'expensePercentage', 'unpaidPercentage', 'chartLabels', 'chartValues', 
            'totalVisitors', 'totalVehicles', 'data', 'averageVehiclesPerCustomer', 'totalSparepartsUsed', 'vehicles', 
            'averageProfitPerCustomer', 'monthlyChartValues', 'customers', 'vehiclesPerWeekData'
        ));
    }
}