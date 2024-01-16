<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generic_Name  extends Model
{
    use HasFactory;


    protected $table = 'generic_names';

    protected $fillable = [
        'generic_names','company_id','created_at','updated_at'
    ];



    public function scopeSelection($query)
    {
        return $query->select('id','generic_names','company_id','created_at','updated_at');
    }


}
