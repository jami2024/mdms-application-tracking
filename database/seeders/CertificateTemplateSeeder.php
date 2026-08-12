<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'company' => 'প্রতিষ্ঠান নিবন্ধন সনদ',
            'establishment' => 'এস্টাবলিশমেন্ট লাইসেন্স সনদ',
            'device' => 'মেডিকেল ডিভাইস নিবন্ধন সনদ',
            'mrp' => 'এমআরপি অনুমোদন সনদ',
        ];

        foreach ($modules as $module => $name) {
            CertificateTemplate::firstOrCreate(
                ['module' => $module],
                ['name' => $name, 'html_content' => $this->defaultHtml(), 'is_active' => true]
            );
        }
    }

    // Table-based layout throughout — mPDF's CSS support doesn't reliably
    // handle flexbox, so tables are the robust choice for the header and the
    // status/QR row. Border-radius on simple boxes does render in mPDF.
    private function defaultHtml(): string
    {
        return <<<'HTML'
<div style="border: 1px solid #d1d5db; border-radius: 14px; padding: 6px; font-family: kalpurush;">
  <div style="border: 2px solid #0b5c3e; border-radius: 10px; padding: 28px 34px;">

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 22px;">
      <tr>
        <td style="width: 78px; vertical-align: middle;">
          <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px; width: 66px;">
            {{gov_emblem}}
          </div>
        </td>
        <td style="vertical-align: middle; padding-left: 14px;">
          <div style="font-size: 12px; font-weight: bold; color: #0b5c3e; letter-spacing: 0.5px;">{{gov_name}}</div>
          <div style="font-size: 12px; font-weight: bold; color: #0b5c3e; letter-spacing: 0.5px; margin-top: 2px;">{{org_name}} (ডিজিডিএ)</div>
        </td>
      </tr>
    </table>

    <div style="font-size: 30px; font-weight: bold; color: #0b1220; line-height: 1.25; margin-bottom: 22px;">
      {{module_label}} সনদপত্র
    </div>

    <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px 20px; margin-bottom: 14px;">
      <div style="border-top: 3px solid #0b5c3e; width: 60px; margin-bottom: 12px;"></div>
      <div style="font-size: 13px; color: #1e293b; margin-bottom: 10px;">
        <b>সার্টিফিকেট নং:</b> <b>{{certificate_no}}</b>
      </div>
      <div style="border-top: 1px solid #e2e8f0; margin-bottom: 10px;"></div>
      <div style="font-size: 13px; color: #1e293b; line-height: 1.9;">
        <b>ধারক:</b><br>
        <b>প্রতিষ্ঠান:</b> {{entity_name}}<br>
        <b>ধরন:</b> {{organization_type_label}}<br>
        <b>ঠিকানা:</b> {{address}}<br>
        <b>টিন:</b> {{tin_no}} &nbsp;&nbsp;&nbsp; <b>বিন:</b> {{bin_no}}
      </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
      <tr>
        <td style="width: 58%; vertical-align: top; padding-right: 10px;">
          <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 18px; height: 100%;">
            <div style="border-top: 3px solid #0b5c3e; width: 40px; margin-bottom: 10px;"></div>
            <div style="font-size: 13px; color: #0b5c3e; font-weight: bold; margin-bottom: 6px;">✓ স্ট্যাটাস:</div>
            <div style="font-size: 12px; color: #1e293b; line-height: 1.7;">
              যথাযথভাবে তালিকাভুক্ত ও অনুমোদিত<br>
              মেয়াদ: {{validity_period}}
            </div>
          </div>
        </td>
        <td style="width: 42%; vertical-align: top;">
          <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;">
            {{qr_code}}
            <div style="font-size: 9px; color: #94a3b8; margin-top: 6px; line-height: 1.5;">
              ডিজিডিএ পোর্টালে সক্রিয় নিবন্ধন<br>যাচাই করতে স্ক্যান করুন
            </div>
          </div>
        </td>
      </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td style="width: 50%;">
          <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px;">
            <span style="font-size: 11px; color: #64748b;">ইস্যু:</span>
            <div style="font-size: 13px; font-weight: bold; color: #0b1220; margin-top: 2px;">{{issue_date}}</div>
          </div>
        </td>
        <td style="width: 10px;"></td>
        <td style="width: 50%;">
          <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px;">
            <span style="font-size: 11px; color: #64748b;">মেয়াদ শেষ:</span>
            <div style="font-size: 13px; font-weight: bold; color: #0b1220; margin-top: 2px;">{{expiry_date}}</div>
          </div>
        </td>
      </tr>
    </table>

  </div>
</div>
HTML;
    }
}
