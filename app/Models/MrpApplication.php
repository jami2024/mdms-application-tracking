<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MrpApplication extends Model
{
    use SoftDeletes;

    protected $table = 'mrp_applications';

    protected $fillable = [
        'company_id', 'device_id', 'proposed_mrp', 'approved_mrp', 'currency', 'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function application(): MorphOne
    {
        return $this->morphOne(Application::class, 'applicable');
    }
}
