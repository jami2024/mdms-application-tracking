<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_config_id', 'step_order', 'step_name', 'designation_id',
        'action_type', 'sla_days', 'can_reject', 'can_send_back',
    ];

    public function config(): BelongsTo
    {
        return $this->belongsTo(WorkflowConfig::class, 'workflow_config_id');
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }
}
