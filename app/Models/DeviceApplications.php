<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceApplications extends Model
{
    use HasFactory;

    protected $table = 'device_applications';

    protected $fillable = [

        'application_no',
        'applicant_id',
        'company_id',
        'product_name',
        'brand',
        'model',
        'country_of_origin',
        'corporate_address',
        'facility_address',
        'intended_use',
        'gmdn_code',
        'gmdn_query',
        'device_class',
        'eumdr_rule_1',
        'eumdr_rule_5',
        'eumdr_rule_8',
        'eumdr_rule_9',
        'eumdr_rule_11',
        'eumdr_class',
        'dossier_technical',
        'dossier_instructions',
        'dossier_conformity',
        'dossier_iso',
        'dossier_sale_certificate',
        'dossier_clinical_evaluation',
        'device_reg_fee',
        'dossier_fee',
        'vat',
        'total_amount',
        'is_payment_complete',
        'payment_method',
        'declaration',
        'step',
        'status',
        'manufacturer_name',
        'bd_contact_person',
        'bd_company_address',
        'bd_company_phone',
        'bd_company_email',
        'manufacturer_country',
        'manufacturer_address',
        'manufacturer_email',
        'manufacturer_phone',
        'already_imported',
        'imported_since',
        'conformity_certificate',
        'assessment_body',
        'commercial_use_since',
        'clinical_safety',
        'clinical_safety_details',
        'principle_use',
        'is_drug_device_combination',
        'is_new_drug',
        'is_kit',
        'device_sizes',
        'device_master_file_submitted',
        'device_master_file',
        'manufacturing_process',
        'sterilization_procedure',
        'release_procedure',
        'personnel',
        'floor_plan',
        'qms_details',
        'qms_manual',
        'tested_before_release',
        'testing_details',
        'withdrawn',
        'withdraw_reason',
        'recall_procedure',
        'recall_document',
        'export_countries',
        'agency_agreement',
        'free_sale_certificate',
        'product_dossier',
        'ec_certificate',
        'packaging_material',
        'country_origin_fsc',
        'reference_country_fsc',
        'action'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function device()
    {
        // belongs to relationship with the Device model
        return $this->belongsTo(Device::class, 'gmdn_code', 'id');
    }

}
