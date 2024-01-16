<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Companies_Request extends FormRequest
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
            
            'company_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'vat_number' => ['integer'],
           
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'الاسم مطلوب.',
            'address.required' => 'العنوان مطلوب.',
            'company_name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'vat_number.string'=>'يجب ان يحتوي الرقم الضريبي على ارقام  فقط',

        ];
    }
}
