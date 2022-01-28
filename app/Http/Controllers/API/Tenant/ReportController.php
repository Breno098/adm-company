<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Status;
use App\Services\Role\IndexService;
use Illuminate\Http\Request;

class ReportController extends BaseApiController
{
    public function finance()
    {
        $orders = Order::all();

        return $this->sendResponse($orders);
    }
}
