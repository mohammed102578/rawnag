<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;


    protected $table = 'invoices';

    protected $fillable = [
        'invoice_no','purchase_id','quantity','price','time','payment_method_id','user_id','company_id','created_at', 'updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('invoice_no','purchase_id','quantity','price','time','payment_method_id','user_id','company_id','created_at', 'updated_at');
    }



    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase','purchase_id','id');
    }
    public  function payment(){

        return $this->belongsTo('App\Models\Payment_method', 'payment_method_id','id');
    }


}
