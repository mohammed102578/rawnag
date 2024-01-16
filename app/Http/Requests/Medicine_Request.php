<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Medicine_Request extends FormRequest
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
            'generic_name' => ['required', 'string', 'max:255','min:2'],
            'medicine_name' => ['required', 'string', 'max:255','min:2'],
            'barcode' => ['required', 'string', 'max:255','min:2'],
            'packing'=>'required|exists:packings,id',
            
           
        ];
    }

    public function messages()
    {
        return [
            'generic_name.required' => 'الاسم مطلوب.',
            'generic_name.min'=>'يجب ان يحتوي  الاسم على حرفين على الاقل',
            'generic_name.string'=>'يجب ان يحتوي الاسم على الاحرف فقط',
            'medicine_name.required' => 'الاسم  العلمي مطلوب.',
            'medicine_name.min'=>'يجب ان يحتوي  الاسم العلمي على حرفين على الاقل',
            'medicine_name.string'=>'يجب ان يحتوي الاسم العلمي على الاحرف فقط',
            'barcode.required' => 'الباركود مطلوب.',
            'barcode.min'=>'يجب ان يحتوي  الباركود على حرفين على الاقل',
            'barcode.string'=>'يجب ان يحتوي الباركود على الاحرف فقط',  
            'packing.required'=>'اسم الوحدة مطلوبة',
            'packing.exists'=>'اسم الوحدة غير موجود'
           
        ];
    }
}
