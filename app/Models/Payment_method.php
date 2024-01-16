<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_method  extends Model
{
    use HasFactory;


    protected $table = 'payment_methods';

    protected $fillable = [
        'payment_method_name_ar', 'payment_method_name_en','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id', 'payment_method_name_ar', 'payment_method_name_en','company_id','created_at','updated_at');
    }



    public function invoice()
    {
        return   $this->hasMany('App\Models\Invoice','payment_method_id','id');
    }


}
