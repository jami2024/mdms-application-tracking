<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('title'); // e.g. Sub-Director (SD), Assistant Director (AD)
            $table->string('short_code')->nullable(); // SD, AD, DD, GD, Admin
            $table->unsignedInteger('grade_level')->nullable(); // approval hierarchy level, lower = higher authority
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
