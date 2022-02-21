<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetLinkRequest;
use App\Jobs\SendResetLinkMail;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(ResetLinkRequest $request)
    {
        \Queue::push(new SendResetLinkMail($request->input('email')));

        return redirect()->back()->with('success', 'Check your mail');
    }
}
