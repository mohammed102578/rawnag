<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;


    protected $table = 'companies';

    protected $fillable = [
        'company_name','address','vat_number','created_at', 'updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id','company_name','address','vat_number','created_at', 'updated_at');
    }


    public  function user(){
        return $this -> hasMany('App\Models\User','company_id','id');
    }


    public  function license(){
        return $this -> hasMany('App\Models\License','company_id','id');
    }




}
