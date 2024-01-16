<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Determining_price_Request extends FormRequest
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
            'medicine' => ['required'],
            'price' => ['required'],
            
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'medicine.required' => 'اسم الدواء مطلوب.',
            
            'price.required' => 'السعر  مطلوب.',
            
            
            
        ];
    }
}
