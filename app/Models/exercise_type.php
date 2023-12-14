<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exercise_type extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'exercise_type_id';
    protected $table = 'exercise_type';
    protected $fillable = ['exercise_type_name'];
}
