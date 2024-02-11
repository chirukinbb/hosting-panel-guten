<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    function google()
    {
        $user = Socialite::driver('google')->user();

        if (!User::whereEmail($user->email)->exists())
            $this->register($user->email, $user->name, $user->avatar);

        $user = User::find($user->email);

        return view('token', ['token' => $user->createApiToken()]);
    }

    function login(Request $request)
    {
        if (!User::whereEmail($request->get('email')))
            $this->register($request->get('email'), null, null, $request->get('password'));

        $user = User::find($request->email);

        return view('token', ['token' => $user->createApiToken()]);
    }


    private function register(string $email, string $name = '', string $avatarUrl = '', string $password = '')
    {
        $userModel = User::getModel();

        $userModel->email = $email;
        $userModel->password = empty($password) ? Str::random(6) : $password;
        $userModel->name = empty($name) ? \Arr::first(explode('@', $email)) : $name;

        $userModel->save();

        $userModel->data()->create(['avatar_path' => $avatarUrl]);
    }
}
