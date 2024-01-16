<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;


    protected $table = 'licenses';

    protected $fillable = [
        'company_id','license','expiered_date'
    ];







    public function scopeSelection($query)
    {
        return $query->select('company_id','license','expiered_date');
    }


    public function company()
    {
        return  $this->belongsTo('App\Models\Company','company_id','id');
    }



}
