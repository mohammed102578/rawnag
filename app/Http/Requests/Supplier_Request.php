<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Supplier_Request extends FormRequest
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
            'supplier_name' => ['required', 'string', 'max:255','min:2'],
            'email' => ['required', 'email', 'max:255'],
            'contact_number' => ['required', 'min:10'],
            'address'=>['required', 'string', 'max:255','min:2'],
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.required' => 'الاسم مطلوب.',
            'supplier_name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            'supplier_name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'ادخل عنوان بريد إلكتروني صالح.',
            'contact_number.required' => 'رقم التواصل  مطلوب.',
            'contact_number.min'=>'يجب ان يحتوي  الرقم على 10 احرف على الاقل',
            'address.required'=>'العنوان  مطلوب',
            'address.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'address.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            
            
        ];
    }
}
