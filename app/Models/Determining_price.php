<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Determining_price extends Model
{
    use HasFactory;

 
    protected $table = 'determining_prices';

    protected $fillable = [
        'medicine_id','price','company_id','created_at', 'updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id','medicine_id','company_id','price','created_at', 'updated_at');
    }



    public  function medicine(){

        return $this->belongsTo('App\Models\Medicine','medicine_id','id');
    }


}
