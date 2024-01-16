<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier  extends Model
{
    use HasFactory;


    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_name','email','contact_number','company_id','address','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id','supplier_name','company_id','email','contact_number','address','created_at','updated_at');
    }



    
    public function purchase()
    {
        return  $this->hasMany('App\Models\Purchase','supplier_id','id');
    }

}
