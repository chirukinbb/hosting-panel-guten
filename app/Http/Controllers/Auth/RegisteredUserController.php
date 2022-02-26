<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Jobs\SendRegistrationMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\Admin\UserRepository;

class RegisteredUserController extends Controller
{
    public function __construct(protected UserRepository $repository)
    {
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(RegistrationRequest $request)
    {
        $this->repository->create($request->all());

        return redirect(RouteServiceProvider::HOME)->with('success','Check your mail');
    }
}
