<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Generic tracking wrapper: every company/establishment/device/mrp submission
        // gets one applications row (polymorphic) so workflow, comments, payments
        // and certificates can all reference a single, module-agnostic entity.
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no')->unique();
            $table->morphs('applicable'); // applicable_type, applicable_id -> Company|Establishment|Device|MrpApplication
            $table->foreignId('workflow_config_id')->nullable()->constrained('workflow_configs')->nullOnDelete();
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->foreignId('applicant_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected', 'returned'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
