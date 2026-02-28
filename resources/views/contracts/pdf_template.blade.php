<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ get_label('contract_pdf', 'Contract PDF') }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .company-info {
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .company-address {
            font-size: 10pt;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .contact-info {
            font-size: 10pt;
            color: #7f8c8d;
        }
        
        .contract-title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contract-number {
            text-align: right;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .contract-date {
            text-align: left;
            font-size: 12pt;
            margin-bottom: 30px;
        }
        
        .parties {
            margin: 20px 0;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        
        .party-title {
            font-weight: bold;
            font-size: 13pt;
            color: #2c3e50;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        
        .party-info {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        
        .article {
            margin: 20px 0;
        }
        
        .article-title {
            font-weight: bold;
            font-size: 13pt;
            color: #2c3e50;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        
        .article-content {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .sub-article {
            margin: 10px 0;
        }
        
        .sub-article-title {
            font-weight: bold;
            margin: 5px 0;
        }
        
        .sub-article-content {
            margin-left: 20px;
        }
        
        .total-value {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #27ae60;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #27ae60;
            border-radius: 5px;
            background-color: #ecf0f1;
        }
        
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 30px;
        }
        
        .signature-position {
            font-style: italic;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: center;
        }
        
        .items-table th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10pt;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding: 5px 0;
        }
        
        .page-number {
            position: fixed;
            bottom: 30px;
            right: 20px;
            font-size: 10pt;
            color: #7f8c8d;
        }
        
        .watermark {
            position: fixed;
            opacity: 0.1;
            z-index: -1;
            font-size: 100pt;
            font-weight: bold;
            color: #34495e;
            transform: rotate(-45deg);
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ get_label('contract', 'Contract') }}</div>
    
    <div class="header">
        <div class="company-info">{{ $companyInfo['name'] ?? get_label('modern_real_estate_company', 'Modern Real Estate Company') }}</div>
        <div class="company-address">{{ $companyInfo['address'] ?? get_label('aden_sanaa_st', 'Aden Sanaa Street') }}</div>
        <div class="contact-info">{{ get_label('phone', 'Phone') }}: {{ $companyInfo['phone'] ?? '0123456789' }} | {{ get_label('website', 'Website') }}: {{ $companyInfo['website'] ?? 'www.example.com' }}</div>
    </div>
    
    <div class="contract-title">{{ get_label('contract_for_work', 'Contract for Work') }}</div>
    
    <div style="display: flex; justify-content: space-between;">
        <div class="contract-number">{{ get_label('contract_no', 'Contract No') }}: {{ $contract->id ?? 'N/A' }}</div>
        <div class="contract-date">{{ get_label('date', 'Date') }}: {{ $contract->start_date ? format_date($contract->start_date) : now()->format('Y-m-d') }}</div>
    </div>
    
    <div class="parties">
        <div class="party-title">{{ get_label('party_one_owner', 'Party One (Owner)') }}</div>
        <div class="party-info"><strong>{{ get_label('represented_by', 'Represented by') }}:</strong> {{ $contract->owner_representative ?? $contract->createdBy->first_name . ' ' . $contract->createdBy->last_name ?? 'N/A' }}</div>
        <div class="party-info"><strong>{{ get_label('id_card', 'ID Card') }}:</strong> {{ $contract->owner_id_card ?? 'N/A' }}</div>
        <div class="party-info"><strong>{{ get_label('phone', 'Phone') }}:</strong> {{ $contract->owner_phone ?? 'N/A' }}</div>
    </div>
    
    <div class="parties">
        <div class="party-title">{{ get_label('party_two_contractor', 'Party Two (Contractor)') }}</div>
        <div class="party-info"><strong>{{ get_label('represented_by', 'Represented by') }}:</strong> {{ $contract->contractor_representative ?? ($contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : 'N/A') }}</div>
        <div class="party-info"><strong>{{ get_label('id_card', 'ID Card') }}:</strong> {{ $contract->contractor_id_card ?? 'N/A' }}</div>
        <div class="party-info"><strong>{{ get_label('phone', 'Phone') }}:</strong> {{ $contract->contractor_phone ?? 'N/A' }}</div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 1 - {{ get_label('contract_subject', 'Contract Subject') }}</div>
        <div class="article-content">
            {{ get_label('party_two_obligated_to_perform_work_according_to_schedule', 'Party two is obligated to perform work according to the approved schedule and drawings in the') }}
            {{ $contract->project->title ?? get_label('project', 'Project') }}
            {{ get_label('location_details', 'Location details') }}: {{ $contract->location ?? $contract->project->address ?? 'N/A' }}
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 2 - {{ get_label('party_two_obligations', 'Party Two Obligations') }}</div>
        <div class="article-content">
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.1</div>
                <div class="sub-article-content">{{ get_label('provide_skilled_labor', 'Provide skilled labor force capable of executing the work') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.2</div>
                <div class="sub-article-content">{{ get_label('responsible_for_materials_and_tools', 'Responsible for all materials received from party one, and must return unused materials, also provide all required machinery and tools') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.3</div>
                <div class="sub-article-content">{{ get_label('bear_costs_of_wasted_materials', 'Bear costs of wasted, lost, or mishandled materials') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.4</div>
                <div class="sub-article-content">{{ get_label('execute_work_continuously', 'Execute work continuously without interruption or delays, and not cause any delays') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.5</div>
                <div class="sub-article-content">{{ get_label('report_issues_before_execution', 'Report any observations before starting execution, notify party one in time about any errors or mistakes discovered, and not make any changes without written authorization from party one') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.6</div>
                <div class="sub-article-content">{{ get_label('party_one_rights_to_assign_to_other_contractors', 'Party one has the right to assign part of the work to another contractor if party two is unable to execute the work completely or repeatedly delays') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.7</div>
                <div class="sub-article-content">{{ get_label('party_one_rights_to_modify_work', 'Party one or his representative has the right to order removal of any work executed in violation of plans, specifications, or standards') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.8</div>
                <div class="sub-article-content">{{ get_label('no_sub_contracting_allowed', 'Party two is not allowed to subcontract work to another contractor, otherwise the contract is considered automatically terminated') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.9</div>
                <div class="sub-article-content">{{ get_label('suspension_of_work_conditions', 'In case of circumstances causing work stoppage for an indefinite period, party two will be notified to stop until further notice, and party two bears no obligations or penalties during the suspension period') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.10</div>
                <div class="sub-article-content">{{ get_label('additional_work_requirements', 'Additional work related to a specific item is accepted only with an official record signed by the project manager and approved by the company director') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.11</div>
                <div class="sub-article-content">{{ get_label('responsibility_for_worker_issues', 'Party two bears responsibility for any issues arising from him or his workers, considering site conditions') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 2.12</div>
                <div class="sub-article-content">{{ get_label('provide_safety_equipment', 'Provide safety and security requirements at the site for workers (work clothes + helmet + shoes)') }}</div>
            </div>
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 3 - {{ get_label('value_and_payment_method', 'Value and Payment Method') }}</div>
        <div class="article-content">
            {{ get_label('total_contract_value', 'Total contract value') }} ({{ $contract->value ?? '0.00' }} {{ $general_settings['currency_code'] ?? 'YER' }}).
            {{ get_label('payment_terms', 'Payment terms') }}: {{ $contract->payment_percentage ?? '35%' }} {{ get_label('as_advance_payment', 'as advance payment') }},
            {{ $contract->quality_guarantee_percentage ?? '15%' }} {{ get_label('as_quality_guarantee', 'as quality guarantee') }} {{ get_label('to_be_withheld_for_one_month_after_final_delivery', 'to be withheld for one month after final delivery') }}.
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 4 - {{ get_label('party_one_obligations', 'Party One Obligations') }}</div>
        <div class="article-content">
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 4.1</div>
                <div class="sub-article-content">{{ get_label('approve_and_supply_basic_materials', 'Approve and supply all basic materials') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 4.2</div>
                <div class="sub-article-content">{{ get_label('protect_contractor_equipment', 'Responsible for protecting and guarding party two equipment and tools at the site') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 4.3</div>
                <div class="sub-article-content">{{ get_label('provide_continuous_electricity', 'Provide continuous electricity to all work sites throughout the execution period') }}</div>
            </div>
        </div>
    </div>
    
    <div class="article">
        <div class="article-title">{{ get_label('article', 'Article') }} 5 - {{ get_label('general_provisions', 'General Provisions') }}</div>
        <div class="article-content">
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 5.1</div>
                <div class="sub-article-content">{{ get_label('contract_provisions_binding', 'Contract provisions are binding for both parties, and contract modification is only allowed with agreement of both parties and written approval') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 5.2</div>
                <div class="sub-article-content">{{ get_label('work_completion_condition', 'Work is not considered complete until accepted by the supervising engineer and party one') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 5.3</div>
                <div class="sub-article-content">{{ get_label('obey_party_one_directives', 'Party two and workers must comply with directives from party one and his representatives') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 5.4</div>
                <div class="sub-article-content">{{ get_label('dispute_resolution', 'In case of disputes on contract articles, they are resolved amicably or through arbitration or resorting to court under Yemeni laws') }}</div>
            </div>
            <div class="sub-article">
                <div class="sub-article-title">{{ get_label('sub_article', 'Sub Article') }} 5.5</div>
                <div class="sub-article-content">{{ get_label('attachments_part_of_contract', 'The offer and attached quantity table and specifications form an integral part of this contract') }}</div>
            </div>
        </div>
    </div>
    
    @if(isset($items) && count($items) > 0)
    <div class="article">
        <div class="article-title">{{ get_label('items_table', 'Items Table') }}</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ get_label('no', 'No') }}</th>
                    <th>{{ get_label('description', 'Description') }}</th>
                    <th>{{ get_label('unit', 'Unit') }}</th>
                    <th>{{ get_label('quantity', 'Quantity') }}</th>
                    <th>{{ get_label('unit_price', 'Unit Price') }}</th>
                    <th>{{ get_label('total', 'Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['description'] ?? 'N/A' }}</td>
                    <td>{{ $item['unit'] ?? 'N/A' }}</td>
                    <td>{{ $item['quantity'] ?? '0' }}</td>
                    <td>{{ format_currency($item['unit_price'] ?? 0) }}</td>
                    <td>{{ format_currency(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">{{ get_label('total_amount', 'Total Amount') }}:</td>
                    <td style="font-weight: bold; background-color: #e8f5e8;">
                        {{ format_currency(collect($items)->sum(function($item) {
                            return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
                        })) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="total-value">
        {{ get_label('total_contract_value', 'Total Contract Value') }}: {{ format_currency($contract->value ?? 0) }}
    </div>
    
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-position">{{ get_label('party_one_owner', 'Party One (Owner)') }}</div>
            <div class="signature-name">{{ $contract->owner_representative ?? $contract->createdBy->first_name . ' ' . $contract->createdBy->last_name ?? 'N/A' }}</div>
            <div>{{ get_label('signature', 'Signature') }}: ______________________</div>
            <div>{{ get_label('stamp_fingerprint', 'Stamp/Fingerprint') }}: ______________________</div>
        </div>
        <div class="signature-box">
            <div class="signature-position">{{ get_label('party_two_contractor', 'Party Two (Contractor)') }}</div>
            <div class="signature-name">{{ $contract->contractor_representative ?? ($contract->siteSupervisor ? $contract->siteSupervisor->first_name . ' ' . $contract->siteSupervisor->last_name : 'N/A') }}</div>
            <div>{{ get_label('signature', 'Signature') }}: ______________________</div>
            <div>{{ get_label('stamp_fingerprint', 'Stamp/Fingerprint') }}: ______________________</div>
        </div>
    </div>
    
    <div class="footer">
        {{ $companyInfo['address'] ?? get_label('aden_sanaa_st', 'Aden Sanaa Street') }} | {{ get_label('phone', 'Phone') }}: {{ $companyInfo['phone'] ?? '0123456789' }} | {{ get_label('website', 'Website') }}: {{ $companyInfo['website'] ?? 'www.example.com' }}
    </div>
    
    <div class="page-number">{{ get_label('page', 'Page') }} <span class="page"></span> {{ get_label('of', 'of') }} 2</div>
</body>
</html>