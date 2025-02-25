<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ServiceReportController extends Controller
{
    public function export(Request $request, $category)
    {
        $startDate = $request->input('start_date', Carbon::now()->subWeek()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        return Excel::download(new ServiceExport($startDate, $endDate, $category), 'laporan_service_' . $category . '_' . $startDate . '_to_' . $endDate . '.xlsx');
    }
}