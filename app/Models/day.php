<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class day extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'day_id';
    protected $table = 'day';
    protected $fillable = ['day_id','number_of_workouts', 'workouts'];
    protected $casts = [
        'number_of_workouts' => 'array',
        'workouts' => 'array',
    ];
}
