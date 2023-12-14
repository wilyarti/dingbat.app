<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class muscle extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'muscle_id';
    protected $fillable = ['muscle_name'];
}
