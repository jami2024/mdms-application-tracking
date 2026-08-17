<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Application extends Model
{
    protected $fillable = [
        'application_no', 'applicable_type', 'applicable_id', 'workflow_config_id',
        'current_step_id', 'assigned_to', 'applicant_id', 'status', 'submitted_at', 'decided_at',
    ];

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime', 'decided_at' => 'datetime'];
    }

    public function applicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function workflowConfig(): BelongsTo
    {
        return $this->belongsTo(WorkflowConfig::class);
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ApplicationWorkflowLog::class)->latest('acted_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ApplicationComment::class)->latest();
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function ServiceApplications()
    {
        return $this->belongsTo(ServiceApplication::class, 'applicable_id', 'id');
    }

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     try {
    //         $id = Crypt::decrypt($value);
    //     } catch (DecryptException $e) {
    //         abort(404);
    //     }

    //     return $this->where('id', $id)->firstOrFail();
    // }
}
