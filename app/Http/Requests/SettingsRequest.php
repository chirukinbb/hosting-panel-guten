<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function convertUploadedFile(string $name): array
    {
        $path = $this->file($name)->storePublicly('settings');

        return [
            'name'=>$name,
            'value'=>$path
        ];
    }
}
