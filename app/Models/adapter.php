<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adapter extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $primaryKey = 'adapter_id';
    protected $table = 'adapter';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = [
        'adapter_name', 'adapter_class_name'
    ];
}
