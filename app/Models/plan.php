<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan extends Model
{
    use HasFactory;
    /*
     * plan_id: Int <pk>
price: Real
plan_name: String
owner: User
weeks: Weeks []
number_of_weeks: Int
     */
    protected $table = 'plan';
    protected $fillable = ['plan_id', 'price', 'plan_name', 'owner', 'number_of_weeks', 'weeks', 'is_clone'];
    protected $casts = [
        'price' => 'double',
        'plan_name' => 'string',
        'owner' => 'integer',
        'number_of_weeks' => 'integer',
        'weeks' => 'array',
    ];
    protected $primaryKey = 'plan_id';
}
