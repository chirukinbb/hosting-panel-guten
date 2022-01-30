<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function convertUploadedFile(string $name): array
    {$this->dd($this->file($name));
        $path = $this->file($name)->storePublicly('settings');

        return [
            'name'=>$name,
            'value'=>$path
        ];
    }
}
