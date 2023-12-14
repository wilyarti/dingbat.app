<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exercise_settings extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'exercise_setting_id';
    protected $table = 'exercise_setting';
    protected $fillable = ['user','exercise_setting_type', 'exercise_settings_key','exercise_settings_value'];
    protected $casts = [
        'exercise_settings_key' => 'array',
        'exercise_settings_value' => 'array',
    ];
}
