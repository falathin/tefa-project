<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Service;
use App\Models\ServiceSparepart;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua layanan
        $services = Service::with('spareparts')->get();
    
        // Hitung total keuntungan dan data untuk grafik
        $profitData = [];
        $totalProfit = 0;
    
        foreach ($services as $service) {
            foreach ($service->spareparts as $sparepart) {
                $profit = ($sparepart->harga_jual - $sparepart->harga_beli) * $sparepart->pivot->quantity;
                $totalProfit += $profit;
    
                // Cek apakah sparepart sudah ada dalam profitData
                $existingData = collect($profitData)->firstWhere('sparepart_name', $sparepart->nama_sparepart);
    
                if ($existingData) {
                    // Update keuntungan jika sparepart sudah ada
                    $existingData['profit'] += $profit;
                } else {
                    // Tambahkan data baru
                    $profitData[] = [
                        'sparepart_name' => $sparepart->nama_sparepart,
                        'profit' => $profit
                    ];
                }
            }
        }
    
        // Define additional variables (you can replace these with actual values or logic)
        $bounceRate = 45; // Example: 45% bounce rate
        $bounceRateChange = -5; // Example: -5% change in bounce rate
        $pageViews = 1200; // Example: 1200 page views
        $pageViewsChange = 10; // Example: 10% increase
        $newSessions = 400; // Example: 400 new sessions
        $newSessionsChange = 15; // Example: 15% increase in new sessions
        $avgTimeOnSite = '3:25'; // Example: Average time on site is 3 minutes and 25 seconds
        $avgTimeChange = 5; // Example: 5% increase in average time on site
    
        // Kirim data ke view
        return view('home', [
            'totalProfit' => $totalProfit,
            'profitData' => $profitData,
            'bounceRate' => $bounceRate,
            'bounceRateChange' => $bounceRateChange,
            'pageViews' => $pageViews,
            'pageViewsChange' => $pageViewsChange,
            'newSessions' => $newSessions,
            'newSessionsChange' => $newSessionsChange,
            'avgTimeOnSite' => $avgTimeOnSite,
            'avgTimeChange' => $avgTimeChange,
        ]);
    }
    
}