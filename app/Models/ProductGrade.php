<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGrade extends Model
{
    protected $fillable = ['name', 'code', 'description', 'status'];
}
