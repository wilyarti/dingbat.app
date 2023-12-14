<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workout extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $primaryKey = 'workout_id';
    protected $table = 'workout';
    protected $fillable = [
        'workout_id',
        'workout_type',
        'number_of_exercises',
        'muscles',
        'circuits',
        'exercises',
        'exercises_sets',
        'exercises_reps',
        'number_of_circuits',
        'circuit_sets',
        'circuit_reps',
        'circuits',
        'adapter',
        'adapter_array'
    ];
    protected $casts = [
        'muscles' => 'array',

        'exercises' => 'array',
        'exercises_sets' => 'array',
        'exercises_reps' => 'array',

        'circuits' => 'array',
        'circuit_sets' => 'array',
        'circuit_reps' => 'array',

        'adapter_array' => 'array',

    ];
    /**
     * @var int|mixed
     */
}
