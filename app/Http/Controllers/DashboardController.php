<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceSparepart;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');
    
        // Total visitors created today
        $totalVisitorsToday = Customer::whereDate('created_at', $today)->count();
    
        // Total spareparts available
        $totalSpareparts = Sparepart::sum('jumlah');
    
        // Total spareparts used today
        $totalSparepartsUsed = ServiceSparepart::whereHas('service', function ($query) use ($today) {
            $query->whereDate('service_date', $today);
        })->sum('quantity');
    
        // Total profit today
        $totalProfit = Service::whereDate('service_date', $today)->sum('total_cost') -
                       Service::whereDate('service_date', $today)->sum('service_fee');
    
        // Total expenses based on sparepart purchases today
        $totalExpense = DB::table('service_spareparts')
            ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
            ->join('services', 'service_spareparts.service_id', '=', 'services.id')
            ->whereDate('services.service_date', $today)
            ->sum(DB::raw('service_spareparts.quantity * spareparts.harga_beli'));
    
        // Total unpaid balance today
        $totalUnpaid = Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('total_cost') -
            Service::whereDate('service_date', $today)
            ->where('payment_received', '<', DB::raw('total_cost'))
            ->sum('payment_received');
    
        // Percentages
        $profitPercentage = $totalProfit > 0 ? (($totalProfit / max($totalProfit, 1)) * 100) : 0;
        $expensePercentage = $totalExpense > 0 ? (($totalExpense / max($totalExpense, 1)) * 100) : 0;
        $unpaidPercentage = $totalUnpaid > 0 ? (($totalUnpaid / max($totalUnpaid, 1)) * 100) : 0;
    
        // Total customers and average profit per customer
        $totalCustomers = Customer::count();
        $averageProfitPerCustomer = $totalCustomers > 0 ? $totalProfit / $totalCustomers : 0;
    
        // Total visitors and vehicles
        $totalVisitors = Customer::count();
        $totalVehicles = Vehicle::count();
    
        // Average vehicles per customer
        $averageVehiclesPerCustomer = $totalVisitors > 0 ? $totalVehicles / $totalVisitors : 0;
    
        // Chart data for daily and monthly profit
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
    
        // Spareparts and vehicles data
        $spareparts = Sparepart::orderBy('created_at', 'asc')->get();
        $vehicles = Vehicle::orderBy('created_at', 'asc')->get();
    
        // Data for the view
        $data = [
            'service_date' => $today,
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