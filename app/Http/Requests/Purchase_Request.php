<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Purchase_Request extends FormRequest
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
            'medicine' => 'required|exists:medicines,id',
            'quantity' => 'required',
            'supplier'=>'required|exists:suppliers,id',
            'exp_date' => 'required',
            'purchase_date' => 'required',
            'batch'=>'required',
            'price'=>'required',
            
           
        ];
    }

    public function messages()
    {
        return [
            'medicine.required'=>'اسم الدواء مطلوب',
            'medicine.exists'=>'اسم الدواء غير موجود', 
            'exp_date.required' => 'تاريخ الانتهاء مطلوب.',
            'exp_date.required' => 'تاريخ الاشتراء مطلوب.',
            'quantity.required' => '   الكمية مطلوبة.',
            'batch.required' => 'الباتش مطلوب.',    
            'supplier.required'=>'اسم المورد مطلوب',
            'supplier.exists'=>'اسم المورد غير موجود',
           
        ];
    }
}
