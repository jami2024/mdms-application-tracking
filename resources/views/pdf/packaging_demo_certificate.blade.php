<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        color: #000000;
        font-size: 10.5pt;
        line-height: 1.25;
    }

    .header {
        text-align: center;
        margin-bottom: 10px;
    }
    .header .gov-title {
        font-size: 11pt;
        font-weight: bold;
    }
    .header .ministry-title {
        font-size: 10pt;
        font-weight: bold;
    }
    .header .dgda-title {
        font-size: 10.5pt;
        font-weight: bold;
    }
    .header .address {
        font-size: 9pt;
    }

    .cert-title {
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
        text-decoration: underline;
        margin: 12px 0;
    }

    .declaration-text {
        text-align: left;
        margin-bottom: 12px;
        line-height: 1.3;
    }
    .underline-data {
        text-decoration: underline;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        vertical-align: top;
        padding: 2px 0;
    }

    .details-table td.label {
        width: 35%;
        font-weight: bold;
    }
    .details-table td.separator {
        width: 3%;
        font-weight: bold;
    }
    .details-table td.value {
        width: 62%;
        font-weight: bold;
        text-transform: uppercase;
    }

    .instructions-box {
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
    }
    .instructions-box .title {
        font-weight: bold;
        text-decoration: underline;
        font-size: 9.5pt;
    }
    .instructions-box .body-text {
        font-size: 8.5pt;
        font-weight: bold;
        margin-top: 3px;
    }

    .conditions-title {
        font-weight: bold;
        text-decoration: underline;
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .conditions-table td {
        font-size: 9pt;
        line-height: 1.2;
        padding-bottom: 4px;
    }
    .conditions-table td.num {
        width: 4%;
        font-weight: bold;
    }

    .memo-date-table {
        margin-top: 15px;
        font-size: 10.5pt;
        font-weight: bold;
    }

    .qr-code-box img {
        width: 20px;
        height: 20px;
    }
</style>

<div class="header">
    <div class="gov-title">GOVERNMENT OF THE PEOPLE'S REPUBLIC OF BANGLADESH</div>
    <div class="ministry-title">MINISTRY OF HEALTH &amp; FAMILY WELFARE</div>
    <div class="dgda-title">DIRECTORATE GENERAL OF DRUG ADMINISTRATION</div>
    <div class="address">Mohakhali, Dhaka-1212</div>
</div>

<div class="cert-title">CERTIFICATE OF REGISTRATION</div>

<div class="declaration-text">
    We hereby declare that <span class="underline-data">{{ $certificate->application->product_name ?? 'Catgut Plain' }}</span> manufactured by
    <span class="underline-data">{{ $certificate->application->manufacturer_details ?? 'M/S. Vigilanz Medical Devices SDN BHD., Penang, Malaysia' }}</span>
    and represented by <span class="underline-data">{{ $certificate->application->applicant->name ?? 'M/S. Genius Medical Devices Ltd., 218, Dr. Kudrat-e-Khuda Road, Dhanmondi, Dhaka, Bangladesh' }}</span>
    is registered with Directorate General of Drug Administration and Licensing Authority (Drugs) under Reg No
    <span class="underline-data">{{ $certificate['certificate_no'] ?? 'Not specified' }}</span>. The drug as described below is allowed to be imported into Bangladesh under The Drug Act 1940 (XXIII of 1940), The Drugs (Control) Ordinance, 1982 and The drugs (control) (Amendment) Act, 2006 subjected to the provision of import policy published by Government from time to time.
</div>

<table class="details-table" style="margin: 10px 0;">
    <tr>
        <td class="label">Name of the Product</td>
        <td class="separator">:</td>
        <td class="value">{{ $certificate->application->product_name ?? 'CATGUT PLAIN' }}</td>
    </tr>
    <tr>
        <td class="label">Generic &amp; Nature of Product</td>
        <td class="separator">:</td>
        <td class="value">{{ $certificate->application->generic_nature ?? 'Absorbable Sutures, Natural, Polished, Collagen' }}</td>
    </tr>
    <tr>
        <td class="label">Pack Size</td>
        <td class="separator">:</td>
        <td class="value">{{ $certificate->application->pack_size ?? 'Box x 12' }}</td>
    </tr>
</table>

<table style="margin: 10px 0;">
    <tr>
        <td style="width: 40%;">
            <div class="instructions-box">
                <div class="title">Instructions</div>
                <div class="body-text">
                    To be dispensed only by or on the prescription of a registered physician.
                </div>
            </div>
        </td>
        <td style="width: 60%; text-align: center; font-size: 9.5pt;">
            <strong>Director General</strong><br>
            <strong>Directorate General of Drug Administration</strong><br>
            &amp;<br>
            <strong>Licensing authority (Drugs)</strong><br>
            Govt. of the People's Republic of Bangladesh<br>
            <span style="font-size: 8pt;">Phone-9880803, dgda.gov@gmail.com</span>
        </td>
    </tr>
</table>

<div class="conditions-title">Conditions:</div>
<table class="conditions-table">
    <tr>
        <td class="num">1.</td>
        <td>Labeling should be done in accordance with the provision of the Drugs Act and Rules which require that the name and address of the manufacture, batch number, manufacturing date, expiry date, M.R.P. (Maximum Retail Price), DAR No. (Drug Administration Reg. Number) etc. should be displayed on the label of the container and also on the outer cover containing the container.</td>
    </tr>
    <tr>
        <td class="num">2.</td>
        <td>The registration will be valid for 5(five) years from its date of issue unless it is revoked, suspended or cancelled earlier.</td>
    </tr>
    <tr>
        <td class="num">3.</td>
        <td>The certificate will be treated as cancelled if any violation of the conditions and the name or the formula of this product is changed or modified qualitatively or quantitatively without due approval of the Licensing Authority.</td>
    </tr>
</table>

<table class="memo-date-table">
    <tr>
        <td style="width: 60%;">Memo No. <span style="text-decoration: underline;">{{ $certificate->memo_no ?? 'DGDA/15-5/016/(411)/2821' }}</span></td>
        <td style="width: 40%; text-align: right;">Date: <span style="text-decoration: underline;">{{ isset($certificate->issue_date) ? $certificate->issue_date->format('d/m/Y') : '25/02/2016' }}</span></td>
    </tr>
</table>

<table style="margin-top: 15px;">
    <tr>
        <td style="width: 50%; font-size: 9pt; font-weight: bold;">
            c.c to: {{ $certificate->application->applicant->name ?? 'M/S Genius Medical Devices Ltd.' }}<br>
            {{ $certificate->application->applicant->address ?? '218, Dr. Kudrat-e-Khuda Road, Dhanmondi, Dhaka, Bangladesh' }}
        </td>
        <td style="width: 50%; text-align: center; font-size: 9.5pt;">
            <div class="qr-code-box">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            </div>
            <strong>For Director General,</strong><br>
            <strong>Directorate General of Drug Administration</strong>
        </td>
    </tr>
</table>
