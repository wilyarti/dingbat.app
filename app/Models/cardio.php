<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cardio extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'cardio_id';
    protected $table = 'cardio';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['cardio_name','cardio_text'];
    protected $casts = [
        'cardio_text' => 'array',
    ];
}
