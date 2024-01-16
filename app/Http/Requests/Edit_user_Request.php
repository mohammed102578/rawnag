<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Edit_user_Request extends FormRequest
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
            'name' => ['required', 'string', 'max:255','min:2'],
            'email' => ['required', 'string', 'email', 'max:255',],
            'group'=>'required|exists:groups,id',
            
           
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'ادخل عنوان بريد إلكتروني صالح.',
            'name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'email.unique'=>'هذا الايميل مستخدم من قبل', 
            'group.required'=>'اسم المجموعة مطلوبة',
            'group.exists'=>'اسم المجموعة غير موجود'
           
        ];
    }
}
