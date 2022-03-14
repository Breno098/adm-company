<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Models\Order;
use App\Services\Docs\DownloadPdfBudgetService;
use App\Services\Docs\DownloadPdfServiceOrderService;
use App\Services\Docs\DownloadPdfReceiptService;
use App\Services\Docs\DownloadPdfWarrantyOrderService;

class DocsController extends BaseApiController
{
    /**
     * @param Order $order
     * @param DownloadPdfServiceOrderService $downloadPdfServiceOrderService
     */
    public function downloadServiceOrder(Order $order, DownloadPdfServiceOrderService $downloadPdfServiceOrderService)
    {
        return $downloadPdfServiceOrderService->run($order);
    }

    /**
     * @param Order $order
     * @param DownloadPdfOrderServiceService $downloadPdfOrderServiceService
     */
    public function downloadBudget(Order $order, DownloadPdfBudgetService $downloadPdfBudgetService)
    {
        return $downloadPdfBudgetService->run($order);
    }

    /**
     * @param Order $order
     * @param DownloadPdfOrderServiceService $downloadPdfOrderServiceService
     */
    public function downloadReceipt(Order $order, DownloadPdfReceiptService $downloadPdfReceiptService)
    {
        return $downloadPdfReceiptService->run($order);
    }

     /**
     * @param Order $order
     * @param DownloadPdfWarrantyOrderService $downloadPdfWarrantyOrderService
     */
    public function downloadWarrantyOrder(Order $order, DownloadPdfWarrantyOrderService $downloadPdfWarrantyOrderService)
    {
        return $downloadPdfWarrantyOrderService->run($order);
    }
}
