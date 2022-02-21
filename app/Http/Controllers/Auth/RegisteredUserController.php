<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Jobs\SendRegistrationMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegistrationRequest $request)
    {
        $user = User::create($request->all());
        $user->assignRole('User');

        \Queue::push(new SendRegistrationMail($user, $request->input('password')));

        return redirect(RouteServiceProvider::HOME)->with('success','Check your mail');
    }
}
