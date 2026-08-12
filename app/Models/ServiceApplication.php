<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ServiceApplication extends Model
{
    protected $table = 'service_applications';

    protected $fillable = [
        'applicant_name', 'mobile_number', 'email', 'nid',
        'company_name', 'designation', 'tin', 'document_type',
        'document_number', 'document_path', 'remarks', 'created_by', 'status'
    ];
}
