<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class set extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'set_id';
    protected $table = 'set';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'plan_id', 'week_id','exercises_index', 'exercise_id','workout_id','one_rep_max','weight','reps', 'date'];
    protected $casts = [
        'date' => 'date',
    ];
}
