<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift  extends Model
{
    use HasFactory;


    protected $table = 'shifts';

    protected $fillable = [
        'shift_name','start_time','end_time','company_id','created_at','updated_at'
    ];







    public function scopeSelection($query)
    {
        return $query->select('id','shift_name','start_time','end_time','company_id','created_at','updated_at');
    }


}
