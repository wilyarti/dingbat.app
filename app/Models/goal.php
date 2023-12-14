<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class goal extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $primaryKey = 'goal_id';
    protected $table = 'goal';
    protected $fillable = [
        'goal_id', 'goal_name', 'goal_type_name', 'goal_description', 'user_id', 'table_name', 'table_key', 'goal_value', 'date',
        'table_primary_name', 'table_primary_key', 'table_primary_value', 'table_primary_target'
    ];
}
