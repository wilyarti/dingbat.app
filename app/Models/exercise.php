<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exercise extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'exercise_id';
    protected $table = 'exercise';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['exercise_name','exercise_link','exercise_type','muscle_id','equipment_id' ];
}
