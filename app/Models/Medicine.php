<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;


    protected $table = 'medicines';

    protected $fillable = [
        'medicine_name','generic_name','packing_id','barcode','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id', 'medicine_name','generic_name','packing_id','barcode','created_at','updated_at');
    }



    public function determining_price()
    {
       return $this->hasOne('App\Models\Determining_price','medicine_id','id');
    }


    public function purchase()
    {
       return $this->hasOne('App\Models\Purchase','medicine_id','id');
    }



    public function packing()
    {
        return  $this->belongsTo('App\Models\Packing','packing_id','id');
    }


    public function invoice()
    {
        return  $this->hasMany('App\Models\Invoice','medicine_id','id');
    }
    public function log()
    {
        return $this->hasOne('App\Models\Log','medicine_id','id');
    }

    

}
?>