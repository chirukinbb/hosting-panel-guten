<?php

namespace App\Http\Controllers;

use App\Interfaces\AccountPageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function dashboard()
    {
        return view('account.dashboard');
    }

    public function page($page)
    {
        $controllerObj = $this->getControllerObj($page);

        return $controllerObj ? $controllerObj->index() : redirect()->route('account');
    }

    public function save($page,Request $request)
    {
        $controllerObj = $this->getControllerObj($page);

        return $controllerObj ? $controllerObj->post($request->toArray()) : redirect()->route('account');
    }

    private function getControllerObj(string $page)
    {
        $controllerName  = 'App\Http\Controllers\Account\\'.Str::ucfirst($page).'Controller';

        return (class_exists($controllerName) && in_array(AccountPageInterface::class, class_implements($controllerName))) ?
            app($controllerName) : false;
    }
}
