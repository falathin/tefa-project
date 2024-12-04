<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

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

        // Data untuk chart
        $chartData = [
            ['service_date' => '2024-12-01', 'total_profit' => 12.5],
            ['service_date' => '2024-12-02', 'total_profit' => 15.0],
        ];

        return view('home', compact(
            'totalProfit', 'totalExpense', 'totalUnpaid', 
            'profitPercentage', 'expensePercentage', 'unpaidPercentage', 
            'chartData', 'totalVisitors' // Pass total visitors data
        ));
    }    
}