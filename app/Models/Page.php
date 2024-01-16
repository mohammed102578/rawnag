<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page  extends Model
{
    use HasFactory;


    protected $table = 'pages';

    protected $fillable = [
        'page_name','page_name_en','created_at','route_name','icon','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id',  'page_name','page_name_en','route_name','icon','created_at','updated_at');
    }


    public function permission(){
        return $this-> hasMany('App\Models\Page','page_id','id');
            }
        

}
