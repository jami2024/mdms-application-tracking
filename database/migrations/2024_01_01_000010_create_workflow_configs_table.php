<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One config per application module, e.g. "Device Registration Workflow"
        Schema::create('workflow_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('module'); // company | establishment | device | mrp
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_configs');
    }
};
