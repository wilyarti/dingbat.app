<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wizards extends Model
{
    use HasFactory;
    protected $table = 'wizards';
    protected $fillable = ['user_id','is_wizard'];
    protected $casts = [
        'user_id' => 'integer',
        'is_wizard' => 'boolean',
    ];
}
