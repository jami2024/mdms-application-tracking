<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Core
        'name', 'address', 'division_id', 'district_id', 'upazila_id',
        'contact_person', 'contact_phone', 'contact_email', 'owner_user_id', 'status',

        // Section 1: Applicant Identity & Security Verification
        'applicant_type', 'name_prefix', 'applicant_full_name', 'mobile_number', 'mobile_verified_at',
        'national_id', 'date_of_birth', 'nid_photo', 'gender', 'nationality', 'applicant_designation',
        'primary_email', 'email_verified_at',

        // Section 2: Statutory Business Credentials
        'organization_type', 'address_line_1', 'address_line_2', 'post_code', 'corporate_contact', 'fax_number',
        'trade_license_no', 'trade_license_file', 'tin_no', 'tin_file', 'bin_no', 'bin_file',
        'rjsc_registration_number', 'rjsc_file', 'irc_number', 'irc_file',

        // Section 3: Legal Undertaking
        'signed_declaration_file', 'declaration_signed_at',

        // Verification gate
        'verification_status', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'mobile_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'declaration_signed_at' => 'datetime',
            'date_of_birth' => 'date',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
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

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function application(): MorphOne
    {
        return $this->morphOne(Application::class, 'applicable');
    }

    public function isFullyVerified(): bool
    {
        return $this->verification_status === 'verified'
            && $this->mobile_verified_at !== null
            && $this->email_verified_at !== null;
    }
}
