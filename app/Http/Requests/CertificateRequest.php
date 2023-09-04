<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:json|max:2000'
        ];
    }

    public function messages()
    {
        return [            
            'file.required' => "File is required",
            'file.mimes'    => "File must be a json file",
            'file.max'      => "File should not be greater than 2mb"
        ];
    }
}
