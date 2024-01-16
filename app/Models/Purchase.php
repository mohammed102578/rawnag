<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase  extends Model
{
    use HasFactory;


    protected $table = 'purchases';

    protected $fillable = [
        'medicine_id','exp_date','quantity','company_id','batch','stop'
     ,'supplier_id','price','invoice_number','created_at','purchase_date','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id',  'medicine_id','exp_date','company_id','quantity',
        'batch','stop','supplier_id','price','purchase_date','invoice_number','created_at','updated_at');
    }


    public function scopeStop($query)
    {

        return $query->where('stop', 1);
    }

    public function supplier()
    {
        return  $this->belongsTo('App\Models\Supplier','supplier_id','id');
    }


    public  function medicine(){

        return $this->belongsTo('App\Models\Medicine','medicine_id','id');
    }


    public function invoice()
    {
        return  $this->hasMany('App\Models\Invoice','purchase_id','id');
    }



    public function log()
    {
        return $this->hasOne('App\Models\Log','purchase_id','id');
    }
}
