<?php

namespace App\Services\Docs;

use App\Models\Company;
use App\Models\EmployeeReceipt;
use App\Models\Order;
use App\Services\Image\LogoBase64;
use Barryvdh\DomPDF\PDF;

class DownloadPdfReceiptService
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
     * @param Order $order
     *
     * @return mixed
     */
    public function run(Order $order)
    {
        $this->pdf->loadView('docs.receipt', [
            'urlLogo' => $this->logoBase64->run(),
            'company' => Company::first(),
            'order' => $order
        ]);

        $now = now()->format('d_m_Y_H_i_s');

        return config('app.docs_order_type') === 'stream' ? $this->pdf->stream(): $this->pdf->download("recibo_{$order->id}_{$now}.pdf");
    }
}
