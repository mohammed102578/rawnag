<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store_Request extends FormRequest
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
            'medicine_id' => 'required|exists:medicines,id',
            
            
           
        ];
    }

    public function messages()
    {
        return [
            'medicine_id.required'=>'اسم الدواء مطلوبة',
            'medicine_id.exists'=>'اسم الدواء غير موجود', 
            
           
        ];
    }
}
