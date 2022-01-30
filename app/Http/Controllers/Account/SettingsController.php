<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Interfaces\AccountPageInterface;
use App\Models\Account\Setting;
use Illuminate\Http\UploadedFile;

class SettingsController extends Controller implements AccountPageInterface
{
    public function index()
    {
        $settings = Setting::whereUserId(\Auth::id())->first();

        return view('account.settings',compact('settings'));
    }

    public function post($request)
    {
        $avatar = $request['avatar'] ?? null;

        if ($avatar && is_a($avatar, UploadedFile::class))
            /**
             * @var UploadedFile $avatar
             */
            $avatarPath = $avatar->storePublicly('avatars');

        Setting::updateOrCreate(
            ['user_id' => \Auth::id()],
            array_merge(
                isset($avatarPath) ? ['avatar_url' => $avatarPath] : [],
                $request
            )
        );

        return redirect()->back()->with(['success'=>__('messages.saved')]);
    }
}
