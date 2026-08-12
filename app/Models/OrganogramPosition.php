<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrganogramPosition extends Model
{
    protected $fillable = ['organization_id', 'designation_id', 'parent_id', 'user_id', 'order', 'status'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrganogramPosition::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(OrganogramPosition::class, 'parent_id');
    }

    public function incumbent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
