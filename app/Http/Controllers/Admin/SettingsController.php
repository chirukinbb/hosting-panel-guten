<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings');
    }

    public function save(SettingsRequest $request)
    {
        foreach ($request->input('setting')  as $name=>$value){
            $setting = $request->file($name) ?
                $request->convertUploadedFile($name) :
                [
                    'value'=>$value,
                    'name'=>$name
                ];

            Setting::updateOrCreate($setting);
        }
    }
}
