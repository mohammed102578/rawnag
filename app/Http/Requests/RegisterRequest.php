<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4'],
            'password_confirmation' => 'required|required_with:password|same:password',
        ];
    }

    public function messages()
    {
        return [
           
            'name.required' => 'الاسم مطلوب.',
            'name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'ادخل عنوان بريد إلكتروني صالح.',
            'password.required' => 'كلمة المرور مطلوب.',
            'email.unique'=>'هذا الايميل مستخدم من قبل',
            'password.min'=>'يجب ان يحتوي كلمة السر على 4 احرف على الاقل',
            'password_confirmation.required' =>'تاكيد كلمة المرور مطلوب',
            'password_confirmation.same' =>'كلمة السر غير متطابقين',
        ];
    }
}
