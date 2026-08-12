<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Designation extends Model
{
    protected $fillable = ['organization_id', 'title', 'short_code', 'grade_level', 'status'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
