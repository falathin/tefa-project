<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use App\Models\ServiceSparepart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{

    public function filterJurusan(Request $request)
    {
        // $paymentStatus = $request->get('payment_status', 'all');
        // $request->session()->put('payment_status', $paymentStatus);

        $jurusanUser = Auth::user()->jurusan;
        $services = Service::all();
        $today = now()->format('Y-m-d');
        $filterJurusan = $request->get('filterJurusan', 'semua');
        $request->session()->put('filterJurusan', $filterJurusan);

        if ($filterJurusan != 'semua') {
            $dailyCustomerData = Service::whereDate('service_date', $today)
                ->with(['customer', 'vehicle'])
                ->where('jurusan', 'like', $filterJurusan)
                ->get()
                ->map(function ($service) {
                    return [
                        'customer_name' => $service->vehicle->customer->name ?? 'N/A',
                        'vehicle' => $service->vehicle->vehicle_type ?? 'N/A',
                        'income' => $service->total_cost,
                        'service_time' => $service->created_at->format('H:i:s'), // Add service time
                        'id' => $service->id
                    ];
                });
        } else {
            $dailyCustomerData = Service::whereDate('service_date', $today)
                ->with(['customer', 'vehicle'])
                ->get()
                ->map(function ($service) {
                    return [
                        'customer_name' => $service->vehicle->customer->name ?? 'N/A',
                        'vehicle' => $service->vehicle->vehicle_type ?? 'N/A',
                        'income' => $service->total_cost,
                        'service_time' => $service->created_at->format('H:i:s'), // Add service time
                        'id' => $service->id
                    ];
                });
        }

        $totalDailyIncome = $dailyCustomerData->sum('income');

        return view('tab/demographics', compact('dailyCustomerData', 'totalDailyIncome', 'services'));
    }

    public function index(Request $request)
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('m');
        $thisYear = now()->format('Y');

        if (Gate::allows('isBendahara')) {

            $filterJurusan = $request->get('filterJurusan', 'semua');
            $request->session()->put('filterJurusan', $filterJurusan);

            $totalSpareparts = Sparepart::sum('jumlah');
            $totalVisitorsToday = Customer::whereDate('created_at', $today)->count();
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
                'datasets' => [
                    [
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
                    ]
                ]
            ];

            $spareparts = Sparepart::orderBy('created_at', 'asc')->get();
            $vehicles = Vehicle::orderBy('created_at', 'asc')->get();

            $customers = Customer::with('vehicles')->get();
            $serviceIncomeDaily = Service::whereDate('service_date', $today)->sum('total_cost');
            $serviceIncomeMonthly = Service::whereMonth('created_at', $thisMonth)->sum('total_cost');
            $serviceIncomeYearly = Service::whereYear('created_at', $thisYear)->sum('total_cost');

            if ($filterJurusan != 'semua') {
                $dailyCustomerData = Service::whereDate('service_date', $today)
                    ->with(['customer', 'vehicle'])
                    ->where('jurusan', 'like', $filterJurusan)
                    ->get()
                    ->map(function ($service) {
                        return [
                            'customer_name' => $service->vehicle->customer->name ?? 'N/A',
                            'vehicle' => $service->vehicle->vehicle_type ?? 'N/A',
                            'income' => $service->total_cost,
                            'service_time' => $service->created_at->format('H:i:s'), // Add service time
                            'id' => $service->id
                        ];
                    });
            } else {
                $dailyCustomerData = Service::whereDate('service_date', $today)
                    ->with(['customer', 'vehicle'])
                    ->get()
                    ->map(function ($service) {
                        return [
                            'customer_name' => $service->vehicle->customer->name ?? 'N/A',
                            'vehicle' => $service->vehicle->vehicle_type ?? 'N/A',
                            'income' => $service->total_cost,
                            'service_time' => $service->created_at->format('H:i:s'), // Add service time
                            'id' => $service->id
                        ];
                    });
            }

            $totalDailyIncome = $dailyCustomerData->sum('income');
        } else {
            $jurusanUser = Auth::user()->jurusan;
            $totalSpareparts = Sparepart::where('jurusan', 'like', $jurusanUser)->count();
            $totalVisitorsToday = Customer::whereDate('created_at', $today)->where('jurusan', 'like', $jurusanUser)->count();

            // Perhitungan total profit (keuntungan jasa service + keuntungan sparepart)
            $serviceProfit = Service::whereDate('service_date', $today)->where('jurusan', 'like', $jurusanUser)->sum('service_fee');
            $sparepartProfit = ServiceSparepart::whereHas('service', function ($query) use ($today) {
                $query->whereDate('service_date', $today);
            })
                ->where('jurusan', 'like', $jurusanUser)
                ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
                ->sum(DB::raw('service_spareparts.quantity * (spareparts.harga_jual - spareparts.harga_beli)'));

            $totalProfit = $serviceProfit + $sparepartProfit;

            $totalExpense = DB::table('service_spareparts')
                ->join('spareparts', 'service_spareparts.sparepart_id', '=', 'spareparts.id_sparepart')
                ->join('services', 'service_spareparts.service_id', '=', 'services.id')
                ->whereDate('services.service_date', $today)
                ->where('services.jurusan', 'like', $jurusanUser)
                ->sum(DB::raw('service_spareparts.quantity * spareparts.harga_beli'));

            $totalUnpaid = Service::whereDate('service_date', $today)
                ->where('payment_received', '<', DB::raw('total_cost'))
                ->where('jurusan', 'like', $jurusanUser)
                ->sum('total_cost') -
                Service::whereDate('service_date', $today)
                ->where('payment_received', '<', DB::raw('total_cost'))
                ->where('jurusan', 'like', $jurusanUser)
                ->sum('payment_received');

            $profitPercentage = $totalProfit > 0 ? (($totalProfit / max($totalProfit, 1)) * 100) : 0;
            $expensePercentage = $totalExpense > 0 ? (($totalExpense / max($totalExpense, 1)) * 100) : 0;
            $unpaidPercentage = $totalUnpaid > 0 ? (($totalUnpaid / max($totalUnpaid, 1)) * 100) : 0;

            $totalCustomers = Customer::where('jurusan', 'like', $jurusanUser)->count();
            $averageProfitPerCustomer = $totalCustomers > 0 ? $totalProfit / $totalCustomers : 0;

            $totalVisitors = Customer::where('jurusan', 'like', $jurusanUser)->count();
            $totalVehicles = Vehicle::where('jurusan', 'like', $jurusanUser)->count();

            $chartData = Service::selectRaw('DATE(service_date) as date, SUM(total_cost) as total_profit')
                ->groupBy(DB::raw('DATE(service_date)'))
                ->orderBy(DB::raw('DATE(service_date)'), 'asc')
                ->where('jurusan', 'like', $jurusanUser)
                ->get();

            $chartLabels = $chartData->pluck('date')->toArray();
            $chartValues = $chartData->pluck('total_profit')->toArray();

            $monthlyChartData = Service::selectRaw('MONTH(service_date) as month, SUM(total_cost) as total_profit')
                ->groupBy(DB::raw('MONTH(service_date)'))
                ->orderBy(DB::raw('MONTH(service_date)'), 'asc')
                ->where('jurusan', 'like', $jurusanUser)
                ->get();

            $monthlyChartValues = $monthlyChartData->pluck('total_profit')->toArray();

            $vehiclesPerWeekData = [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'datasets' => [
                    [
                        'label' => 'Jumlah Kendaraan per Minggu',
                        'data' => [
                            Vehicle::whereDay('created_at', 'Monday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Tuesday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Wednesday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Thursday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Friday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Saturday')->where('jurusan', 'like', $jurusanUser)->count(),
                            Vehicle::whereDay('created_at', 'Sunday')->where('jurusan', 'like', $jurusanUser)->count(),
                        ],
                        'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                        'borderColor' => 'rgba(153, 102, 255, 1)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4
                    ]
                ]
            ];

            $spareparts = Sparepart::orderBy('created_at', 'asc')->where('jurusan', 'like', $jurusanUser)->get();
            $vehicles = Vehicle::orderBy('created_at', 'asc')->where('jurusan', 'like', $jurusanUser)->get();

            $customers = Customer::with('vehicles')->where('jurusan', 'like', $jurusanUser)->get();
            $serviceIncomeDaily = Service::whereDate('service_date', $today)->where('jurusan', 'like', $jurusanUser)->sum('total_cost');
            $serviceIncomeMonthly = Service::whereMonth('created_at', $thisMonth)->where('jurusan', 'like', $jurusanUser)->sum('total_cost');
            $serviceIncomeYearly = Service::whereYear('created_at', $thisYear)->where('jurusan', 'like', $jurusanUser)->sum('total_cost');

            $dailyCustomerData = Service::whereDate('service_date', $today)
                ->with(['customer', 'vehicle'])
                ->where('jurusan', 'like', $jurusanUser)
                ->get()
                ->map(function ($service) {
                    return [
                        'customer_name' => $service->vehicle->customer->name ?? 'N/A',
                        'vehicle' => $service->vehicle->vehicle_type ?? 'N/A',
                        'income' => $service->total_cost,
                        'service_time' => $service->created_at->format('H:i:s'), // Add service time
                        'id' => $service->id
                    ];
                });

            $totalDailyIncome = $dailyCustomerData->sum('income');
        }

        $totalSparepartsUsed = ServiceSparepart::whereHas('service', function ($query) use ($today) {
            $query->whereDate('service_date', $today);
        })->sum('quantity');


        $averageVehiclesPerCustomer = $totalCustomers > 0 ? $totalVehicles / $totalCustomers : 0;

        $data = [
            'service_date' => $today,
            'total_profit' => $totalProfit,
        ];

        $services = Service::all();

        // progress sampai sini
        return view('home', compact(
            'spareparts',
            'totalSpareparts',
            'totalVisitorsToday',
            'today',
            'totalProfit',
            'totalExpense',
            'totalUnpaid',
            'profitPercentage',
            'expensePercentage',
            'unpaidPercentage',
            'chartLabels',
            'chartValues',
            'totalVisitors',
            'totalVehicles',
            'data',
            'averageVehiclesPerCustomer',
            'totalSparepartsUsed',
            'vehicles',
            'averageProfitPerCustomer',
            'monthlyChartValues',
            'customers',
            'vehiclesPerWeekData',
            'serviceIncomeDaily',
            'serviceIncomeMonthly',
            'serviceIncomeYearly',
            'dailyCustomerData',
            'totalDailyIncome',
            'services'
        ));
    }
}
