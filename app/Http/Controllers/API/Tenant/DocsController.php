<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Models\Order;
use App\Services\Docs\DownloadPdfBudgetService;
use App\Services\Docs\DownloadPdfOrderServiceService;
use App\Services\Image\LogoBase64;

class DocsController extends BaseApiController
{
    /**
     * @param Order $order
     * @param DownloadPdfOrderServiceService $downloadPdfOrderServiceService
     * @param LogoBase64 $logoBase64
     */
    public function downloadOrderService(Order $order, DownloadPdfOrderServiceService $downloadPdfOrderServiceService)
    {
        return $downloadPdfOrderServiceService->run($order);
    }

    /**
     * @param Order $order
     * @param DownloadPdfOrderServiceService $downloadPdfOrderServiceService
     * @param LogoBase64 $logoBase64
     */
    public function downloadBudget(Order $order, DownloadPdfBudgetService $downloadPdfBudgetService)
    {
        return $downloadPdfBudgetService->run($order);
    }
}
