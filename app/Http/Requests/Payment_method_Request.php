<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Payment_method_Request extends FormRequest
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
            'payment_method_name_ar' => ['required', 'string', 'max:255','min:2'],
            'payment_method_name_en' => ['required', 'string', 'max:255','min:2'],



        ];
    }

    public function messages()
    {
        return [
            'payment_method_name_ar.required' => 'طريقة الدفع مطلوب .',
            'payment_method_name_ar.min'=>'يجب ان يحتوي  طريقة الدفع  . على حرفين على الاقل',

            'payment_method_name_ar.string'=>'يجب ان يحتوي طريقة الدفع  . على الاحرف فقط',

            'payment_method_name_en.required' => 'طريقة الدفع مطلوب .',
            'payment_method_name_en.min'=>'يجب ان يحتوي  طريقة الدفع  . على حرفين على الاقل',

            'payment_method_name_en.string'=>'يجب ان يحتوي طريقة الدفع  . على الاحرف فقط',


        ];
    }
}
