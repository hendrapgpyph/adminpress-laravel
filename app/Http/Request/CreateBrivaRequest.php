<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class CreateBrivaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'keterangan' => 'nullable|min:40|confirmed',
        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
