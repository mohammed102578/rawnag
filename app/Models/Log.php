<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;


    protected $table = 'logs';

    protected $fillable = [
        'purchase_id','quantity','price','company_id','user_id','created_at', 'updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id','purchase_id','company_id','quantity','price','user_id','created_at', 'updated_at');
    }


    public function purchase()
    {
        return  $this->belongsTo('App\Models\Purchase','purchase_id','id');
    }




}
