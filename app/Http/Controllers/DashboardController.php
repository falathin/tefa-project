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
    
        $totalVisitorsToday = Customer::whereDate('created_at', $today)->count();
    
        $totalSpareparts = Sparepart::sum('jumlah');
    
        $totalSparepartsUsed = ServiceSparepart::whereHas('service', function($query) use ($today) {
            $query->whereDate('service_date', $today);
        })->sum('quantity');
        
        $totalProfit = Service::whereDate('service_date', $today)->sum('total_cost') - Service::whereDate('service_date', $today)->sum('service_fee');
        
        $totalExpense = DB::table('service_spareparts')
            ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
            ->join('services', 'service_spareparts.service_id', '=', 'services.id')
            ->whereDate('services.service_date', $today)
            ->sum(DB::raw('service_spareparts.quantity * spareparts.harga_beli'));
        
        $totalUnpaid = Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('total_cost') - Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('payment_received');
        
        $profitPercentage = $totalProfit > 0 ? (($totalProfit / max($totalProfit, 1)) * 100) : 0;
        $expensePercentage = $totalExpense > 0 ? (($totalExpense / max($totalExpense, 1)) * 100) : 0;
        $unpaidPercentage = $totalUnpaid > 0 ? (($totalUnpaid / max($totalUnpaid, 1)) * 100) : 0;
    
        $totalCustomers = Customer::count();
        $averageProfitPerCustomer = $totalCustomers > 0 ? $totalProfit / $totalCustomers : 0;
    
        $totalVisitors = Customer::count();
        $totalVehicles = Vehicle::count();
    
        $averageVehiclesPerCustomer = $totalVisitors > 0 ? $totalVehicles / $totalVisitors : 0;
        
        $chartData = Service::selectRaw('DATE(service_date) as date, SUM(total_cost) as total_profit')
            ->groupBy('date')
            ->orderBy('service_date')
            ->get();
        
        $chartLabels = $chartData->pluck('date')->toArray();
        $chartValues = $chartData->pluck('total_profit')->toArray();
        
        $monthlyChartData = Service::selectRaw('MONTH(service_date) as month, SUM(total_cost) as total_profit')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $monthlyChartValues = $monthlyChartData->pluck('total_profit')->toArray();
    
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
            'averageProfitPerCustomer', 'monthlyChartValues'
        ));
    }    
}