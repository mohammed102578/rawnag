<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class packing_Request extends FormRequest
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
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            
            'name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            
           
        ];
    }
}
