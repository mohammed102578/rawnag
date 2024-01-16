<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packing  extends Model
{
    use HasFactory;


    protected $table = 'packings';

    protected $fillable = [
        'packing_name','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id',  'packing_name','company_id','created_at','updated_at');
    }



    public function medicine()
    {
        return   $this->hasMany('App\Models\Medicine','packing_id','id');
    }

}
