<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class active_plan extends Model
{
    use HasFactory;
    protected $table = 'active_plan';
    protected $primaryKey = 'active_plan_id';
    protected $fillable = ['active_plan_id', 'user', 'plan', 'start_date', 'end_date'];
    protected $casts = [
        'plan' => 'integer',
        'user' => 'integer',
       // 'start_date' => 'timestamp',
      //  'end_date' => 'timestamp',
    ];
}
