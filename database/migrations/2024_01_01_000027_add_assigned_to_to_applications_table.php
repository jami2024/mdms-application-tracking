<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Optional: when forwarding, the sender can hand the case to a
            // specific person at the next desk rather than leaving it open
            // to anyone holding that designation. Nullable — the queue still
            // falls back to designation-based matching when this is empty.
            $table->foreignId('assigned_to')->nullable()->after('current_step_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
        });
    }
};
