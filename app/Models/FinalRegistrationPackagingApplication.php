<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class FinalRegistrationPackagingApplication extends Model
{
    protected $table = 'packaging_applications';
    protected $guarded = [];

    public function application(): MorphOne
    {
        return $this->morphOne(Application::class, 'applicable');
    }
}
