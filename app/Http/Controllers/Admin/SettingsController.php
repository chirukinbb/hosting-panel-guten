<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\UserSetting;
use Illuminate\Http\UploadedFile;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function save(SettingsRequest $request)
    {
        foreach ($request->toArray()['setting']  as $name=>$value){
            if ($value && is_a($value, UploadedFile::class))
                /**
                 * @var UploadedFile $value
                 */
                $value = $value->storePublicly('avatars');

            UserSetting::updateOrCreate(
                ['name'=>$name],
                ['value'=>$value]
            );
        }

        return redirect()->back()->with(['success'=>__('messages.saved')]);
    }
}
