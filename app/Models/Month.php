<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month  extends Model
{
    use HasFactory;


    protected $table = 'months';

    protected $fillable = [
        'month_name','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id',  'month_name','created_at','updated_at');
    }



    public  function product(){

        return $this->belongsTo('App\Models\Product', 'product_id','id');
    }


}
