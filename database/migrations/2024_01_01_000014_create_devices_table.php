<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('device_name');
            $table->string('model_no')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->foreignId('product_grade_id')->nullable()->constrained('product_grades')->nullOnDelete();
            $table->string('registration_no')->nullable()->unique();
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['draft', 'submitted', 'registered', 'expired', 'suspended', 'rejected'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
