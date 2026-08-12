<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establishment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'name', 'license_no', 'address',
        'division_id', 'district_id', 'upazila_id',
        'license_issue_date', 'license_expiry_date', 'status',
    ];

    protected function casts(): array
    {
        return ['license_issue_date' => 'date', 'license_expiry_date' => 'date'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    public function application(): MorphOne
    {
        return $this->morphOne(Application::class, 'applicable');
    }
}
