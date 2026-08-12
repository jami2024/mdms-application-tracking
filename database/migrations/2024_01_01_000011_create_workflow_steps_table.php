<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ordered steps of a workflow, each tied to a designation short_code
        // (SD, AD, DD, GD, Admin) that is authorised to act on that step.
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_config_id')->constrained('workflow_configs')->cascadeOnDelete();
            $table->unsignedInteger('step_order');
            $table->string('step_name'); // e.g. "SD Review", "AD Approval"
            $table->foreignId('designation_id')->constrained('designations')->cascadeOnDelete();
            $table->enum('action_type', ['review', 'approve', 'reject', 'forward', 'sign'])->default('review');
            $table->unsignedInteger('sla_days')->nullable();
            $table->boolean('can_reject')->default(true);
            $table->boolean('can_send_back')->default(true);
            $table->timestamps();

            $table->unique(['workflow_config_id', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
