<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['import', 'export']);
            $table->string('module'); // users, companies, devices, payments, reports...
            $table->string('file_path')->nullable();
            $table->unsignedInteger('total_rows')->nullable();
            $table->unsignedInteger('success_rows')->nullable();
            $table->unsignedInteger('failed_rows')->nullable();
            $table->enum('status', ['processing', 'completed', 'failed'])->default('processing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_export_logs');
    }
};
