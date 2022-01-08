<?php

namespace App\Services\EmployeeReceipt;

use App\Models\EmployeeReceipt;
use App\Services\Image\LogoBase64;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class DownloadPdfEmployeeReceiptService
{
    /**
     * @var PDF $pdf
    */
    private PDF $pdf;

    /**
     * @var LogoBase64 $logoBase64
    */
    private LogoBase64 $logoBase64;

    /**
     * @param PDF $pdf
     * @param LogoBase64 $logoBase64
     */
    public function __construct(PDF $pdf, LogoBase64 $logoBase64)
    {
        $this->pdf = $pdf;
        $this->logoBase64 = $logoBase64;
    }

    /**
     * @param EmployeeReceipt $employeeReceipt
     * @param  array  $data
     *
     * @return mixed
     */
    public function run(EmployeeReceipt $employeeReceipt)
    {
        if($employeeReceipt->has_file){
            return $employeeReceipt->download();
        }

        $this->pdf->loadView('docs.employee-receipt', [
            'employeeReceipt' => $employeeReceipt,
            'url' => $this->logoBase64->run()
        ]);

        $path = $this->pathFile($employeeReceipt);

        Storage::put($path, $this->pdf->download()->getOriginalContent());

        $path = Storage::url($path);

        $employeeReceipt->update([
            'path' => $path
        ]);

        return $this->pdf->download($path);
    }

    /**
     * @param string|null $name
     *
     * @return string|null
     */
    private function pathFile(EmployeeReceipt $employeeReceipt)
    {
        $now = now()->format('d_m_Y_H_i_s');

        $nameWithoutSpaces = str_replace(' ', '_', $employeeReceipt->employee->name);

        $company_id = $employeeReceipt->company->id ?? '';

        return "receipts/recibo_pagamento_{$company_id}_{$nameWithoutSpaces}_{$now}.pdf";
    }
}
