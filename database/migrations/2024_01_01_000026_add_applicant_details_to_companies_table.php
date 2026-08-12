<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // --- Section 1: Applicant Identity & Security Verification ---
            $table->enum('applicant_type', ['corporate', 'direct_importer', 'local_agent', 'foreign_enterprise'])->nullable()->after('name');
            $table->enum('name_prefix', ['mr', 'ms', 'dr'])->nullable()->after('applicant_type');
            $table->string('applicant_full_name')->nullable()->after('name_prefix');
            $table->string('mobile_number', 20)->nullable()->after('applicant_full_name');
            $table->timestamp('mobile_verified_at')->nullable()->after('mobile_number');
            $table->string('national_id', 20)->unique()->nullable()->after('mobile_verified_at');
            $table->date('date_of_birth')->nullable()->after('national_id');
            $table->string('nid_photo')->nullable()->after('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('nid_photo');
            $table->string('nationality')->default('Bangladesh')->after('gender');
            $table->string('applicant_designation')->nullable()->after('nationality');
            $table->string('primary_email')->unique()->nullable()->after('applicant_designation');
            $table->timestamp('email_verified_at')->nullable()->after('primary_email');

            // --- Section 2: Statutory Business Credentials ---
            $table->enum('organization_type', ['private_limited', 'public_ltd', 'proprietorship', 'partnership', 'hospital_institute'])->nullable()->after('email_verified_at');
            $table->text('address_line_1')->nullable()->after('address');
            $table->text('address_line_2')->nullable()->after('address_line_1');
            $table->string('post_code', 10)->nullable()->after('address_line_2');
            $table->string('corporate_contact')->nullable()->after('contact_phone');
            $table->string('fax_number')->nullable()->after('corporate_contact');

            // Business documents — file paths alongside the existing *_no columns
            $table->string('trade_license_file')->nullable()->after('trade_license_no');
            $table->string('tin_file')->nullable()->after('tin_no');
            $table->string('bin_file')->nullable()->after('bin_no');
            $table->string('rjsc_registration_number')->nullable()->after('bin_file');
            $table->string('rjsc_file')->nullable()->after('rjsc_registration_number');
            $table->string('irc_number')->nullable()->after('rjsc_file');
            $table->string('irc_file')->nullable()->after('irc_number');

            // --- Section 3: Legal Undertaking ---
            $table->string('signed_declaration_file')->nullable()->after('irc_file');
            $table->timestamp('declaration_signed_at')->nullable()->after('signed_declaration_file');

            // --- Identity/KYC verification gate (separate from the workflow `status`) ---
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('status');
            $table->text('rejection_reason')->nullable()->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_type', 'name_prefix', 'applicant_full_name', 'mobile_number', 'mobile_verified_at',
                'national_id', 'date_of_birth', 'nid_photo', 'gender', 'nationality', 'applicant_designation',
                'primary_email', 'email_verified_at', 'organization_type', 'address_line_1', 'address_line_2',
                'post_code', 'corporate_contact', 'fax_number', 'trade_license_file', 'tin_file', 'bin_file',
                'rjsc_registration_number', 'rjsc_file', 'irc_number', 'irc_file', 'signed_declaration_file',
                'declaration_signed_at', 'verification_status', 'rejection_reason',
            ]);
        });
    }
};
