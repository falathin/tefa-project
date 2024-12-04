<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceSparepart;
use App\Models\Vehicle; // Tambahkan model Vehicle

class DashboardController extends Controller
{
    public function index()
    {
        $totalProfit = 1500; // Calculate or fetch the total profit
        $totalExpense = 1000; // Example total expenses (replace with real data)
        $totalUnpaid = 500; // Example total unpaid amount (replace with real data)

        $profitPercentage = $totalProfit > 0 ? $totalProfit / 1500 * 100 : 0;
        $expensePercentage = $totalExpense > 0 ? $totalExpense / 1000 * 100 : 0;
        $unpaidPercentage = $totalUnpaid > 0 ? $totalUnpaid / 500 * 100 : 0;

        // Menghitung total pengunjung
        $totalVisitors = Customer::count(); // Hitung jumlah seluruh pelanggan

        // Menghitung total kendaraan
        $totalVehicles = Vehicle::count(); // Hitung jumlah kendaraan yang terdaftar

        // Data untuk chart (service history for example)
        $chartData = Service::selectRaw('DATE(service_date) as date, SUM(total_cost) as total_profit')
            ->groupBy('date')
            ->orderBy('service_date')
            ->get();

        return view('home', compact(
            'totalProfit', 'totalExpense', 'totalUnpaid', 
            'profitPercentage', 'expensePercentage', 'unpaidPercentage', 
            'chartData', 'totalVisitors', 'totalVehicles' // Pass total vehicles data
        ));
    }    
}