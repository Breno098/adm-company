<?php

namespace App\Http\Controllers\API\Administrator\Auth;

use App\Http\Controllers\API\Bases\BaseApiAuthenticateController;

class LoginController extends BaseApiAuthenticateController
{
    public function __construct()
    {
        $this->checkUserIsAdmin();
    }
}
