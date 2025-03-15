<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Service;

class ServicePKBExport implements FromView
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function view(): View
    {
        return view('exports.service_pkb', [
            'service' => $this->service
        ]);
    }
}