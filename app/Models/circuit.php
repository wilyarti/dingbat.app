<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class circuit extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'circuit_id';
    protected $table = 'circuit';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['circuit_name','circuit_link','equipment_id','number_of_exercises',  'exercise_list','exercise_types'];
    protected $casts = [
        'exercise_list' => 'array',
        'exercise_types' => 'array',

    ];
}
