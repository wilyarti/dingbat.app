<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipment extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'equipment_id';
    protected $fillable = ['equipment_name'];

}
