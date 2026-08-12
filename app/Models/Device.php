<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'device_name', 'model_no', 'manufacturer', 'country_of_origin',
        'product_grade_id', 'registration_no', 'registration_date', 'expiry_date', 'status',
    ];

    protected function casts(): array
    {
        return ['registration_date' => 'date', 'expiry_date' => 'date'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function productGrade(): BelongsTo
    {
        return $this->belongsTo(ProductGrade::class);
    }

    public function application(): MorphOne
    {
        return $this->morphOne(Application::class, 'applicable');
    }
}
