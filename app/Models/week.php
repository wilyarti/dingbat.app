<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class week extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'week_id';
    protected $table = 'week';
    protected $fillable = ['week_name','user_id','week_id','number_of_days','number_of_workouts', 'workouts','workouts_indexs', 'cardio', 'cardio_indexs'];
    protected $casts = [
        'number_of_days' => 'integer',
        'number_of_workouts' => 'integer',
        'workouts' => 'array',
        'workouts_indexs' => 'array',
        'cardio' => 'array',
        'cardio_indexs' => 'array',
    ];
}
