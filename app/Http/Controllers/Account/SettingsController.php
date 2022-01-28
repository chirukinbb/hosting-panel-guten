<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Interfaces\AccountPageInterface;
use App\Models\Account\Setting;

class SettingsController extends Controller implements AccountPageInterface
{

    public function index()
    {
        return view('account.settings');
    }

    public function post( $request)
    {
    }
}
