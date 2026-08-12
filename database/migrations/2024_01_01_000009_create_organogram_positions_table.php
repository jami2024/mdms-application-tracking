<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Represents the org chart tree: a "post" (seat) that a designation sits at
        // within an organization, optionally filled by a user, with a parent post
        // for hierarchy/reporting-line + workflow routing.
        Schema::create('organogram_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('designation_id')->constrained('designations')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('organogram_positions')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // current incumbent
            $table->unsignedInteger('order')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organogram_positions');
    }
};
