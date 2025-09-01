<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SparepartsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SparepartExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'jurusan' => 'nullable|string'
        ]);

        $fromInput = $request->input('from_date'); // bisa null
        $toInput   = $request->input('to_date');   // bisa null

        // jurusan user yang login
        $userJurusan = auth()->user()->jurusan ?? null;

        // default: jurusan dari form (kalau ada)
        $jurusan = $request->input('jurusan');

        // kalau user bukan General dan ada jurusan user, override filter dengan jurusan user
        if ($userJurusan && $userJurusan !== 'General') {
            $jurusan = $userJurusan;
        }

        // buat nama file
        $rangePart = 'all_dates';
        if ($fromInput || $toInput) {
            $fromPart = $fromInput ? Carbon::parse($fromInput)->format('Y-m-d') : 'start';
            $toPart   = $toInput ? Carbon::parse($toInput)->format('Y-m-d') : 'end';
            $rangePart = $fromPart . '_to_' . $toPart;
        }

        $filename = 'spareparts_' . $rangePart . ($jurusan ? "_{$jurusan}" : '') . '.xlsx';

        return Excel::download(new SparepartsExport($fromInput, $toInput, $jurusan), $filename);
    }
}