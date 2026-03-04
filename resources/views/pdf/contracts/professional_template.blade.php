<!DOCTYPE html>
<html dir="{{ $isRtl ?? true ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() ?? 'ar' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('contracts.contract') }} {{ $contractNumber ?? $contract->id }} - {{ $contract->title ?? __('contracts.contract') }}</title>

    <style>
        @page { margin: 14mm 14mm 14mm 14mm; size: A4; }

        /* PDF-friendly Arabic font (لو عندك Tajawal) */
        @font-face{
            font-family:'Tajawal';
            src:url('{{ public_path('vendor/gpdf/fonts/Tajawal-Normal.ttf') }}') format('truetype');
            font-weight:normal;
        }
        @font-face{
            font-family:'Tajawal';
            src:url('{{ public_path('vendor/gpdf/fonts/Tajawal-Bold.ttf') }}') format('truetype');
            font-weight:bold;
        }

        *{ box-sizing:border-box; }

        body{
            margin:0; padding:0;
            font-family:'Tajawal','DejaVu Sans','Arial',sans-serif;
            font-size:12pt;
            line-height:1.65;
            color:#000;
            direction:rtl;
            text-align:right;
        }

        /* Header layout (logo LEFT) */
        .header{
            width:100%;
            border-bottom:1px solid #000;
            padding:8px 0 10px 0;
            margin-bottom:10px;
        }
        .header-table{
            width:100%;
            border-collapse:collapse;
        }
        .header-table td{
            vertical-align:middle;
            padding:0;
        }

        .logo-cell{
            width:18%;
            text-align:left; /* شعار يسار الصفحة */
            direction:ltr;
        }
        .logo{
            width:75px;
            height:auto;
        }

        .company-cell{
            width:82%;
            text-align:center;
        }
        .company-name{
            font-weight:bold;
            font-size:16pt;
            margin:0;
        }
        .company-sub{
            font-weight:bold;
            font-size:12pt;
            margin:2px 0 0 0;
        }

        /* Top meta line (رقم العقد + رقم البند) */
        .meta-line{
            width:100%;
            margin:10px 0 6px 0;
        }
        .meta-table{
            width:100%;
            border-collapse:collapse;
        }
        .meta-table td{
            padding:0;
            font-size:12pt;
            font-weight:bold;
        }
        .meta-right{ text-align:right; }
        .meta-left{ text-align:left; direction:ltr; }

        /* Title */
        .doc-title{
            text-align:center;
            font-weight:bold;
            font-size:18pt;
            margin:8px 0 10px 0;
            letter-spacing:1px;
        }

        /* Contract intro */
        .intro{
            margin-top:8px;
            font-size:12pt;
        }

        /* Article styling (PDF looks like plain text with bold headings) */
        .article{
            margin:10px 0;
        }
        .article-title{
            font-weight:bold;
            margin:0 0 3px 0;
        }
        .article-body{
            margin:0;
            text-align:justify;
        }
        .clause{
            margin:3px 0;
        }

        /* Items table (as in PDF) */
        .items-title{
            margin:14px 0 6px 0;
            font-weight:bold;
            text-align:center;
        }
        table.items{
            width:100%;
            border-collapse:collapse;
            font-size:11pt;
        }
        table.items th, table.items td{
            border:1px solid #000;
            padding:6px 6px;
            text-align:center;
            vertical-align:middle;
        }
        table.items th{
            font-weight:bold;
        }
        .desc{
            text-align:right;
        }

        /* Signatures (two blocks like PDF) */
        .signatures{
            width:100%;
            margin-top:12px;
        }
        .sig-table{
            width:100%;
            border-collapse:collapse;
        }
        .sig-table td{
            width:50%;
            text-align:center;
            vertical-align:top;
            padding-top:8px;
            font-weight:bold;
        }
        .sig-name{
            margin-top:6px;
            font-weight:bold;
        }
        .sig-line{
            margin-top:6px;
            font-weight:normal;
        }

        /* Footer like PDF */
        .footer{
            position:fixed;
            left:0; right:0;
            bottom:8mm;
            text-align:center;
            font-size:10pt;
        }
        .page-no{
            position:fixed;
            left:0; right:0;
            bottom:3mm;
            text-align:center;
            font-size:10pt;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(!empty($companyInfo['logo']))
                        <img class="logo" src="{{ public_path('storage/' . $companyInfo['logo']) }}" alt="logo">
                    @endif
                </td>
                <td class="company-cell">
                    <div class="company-name">
                        {{ $companyInfo['name_ar'] ?? __('contracts.default_company_ar') }}
                    </div>
                    <div class="company-sub">
                        {{ $companyInfo['subtitle_ar'] ?? __('contracts.default_company_sub_ar') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- META LINE -->
    <div class="meta-line">
        <table class="meta-table">
            <tr>
                <td class="meta-right">
                    {{ __('contracts.contract_no') }}: {{ $contractNumber ?? $contract->id }}
                </td>
                <td class="meta-left">
                    {{ __('contracts.item_no') }} {{ $itemNo ?? 11 }}: - {{ $itemDescription ?? ($contract->title ?? __('contracts.default_item')) }}
                </td>
            </tr>
        </table>
    </div>

    <!-- TITLE -->
    <div class="doc-title">{{ __('contracts.contract_title') }}</div>

    <!-- INTRO -->
    <div class="intro">
        {{ __('contracts.intro_day') }} {{ $contractDay ?? '—' }}
        {{ __('contracts.intro_date') }} {{ $contractDate ?? '—' }}
        {{ __('contracts.intro_hijri') }} {{ $contractHijri ?? '—' }}.
    </div>

    <div class="article">
        <p class="article-body">
            <strong>{{ __('contracts.party1') }}</strong>
            {{ __('contracts.party1_owner') }}:
            {{ $companyInfo['name_ar'] ?? __('contracts.default_company_ar') }},
            {{ __('contracts.represented_by') }} {{ $ownerRepresentative ?? ($contract->quantityApprover->first_name ?? $contract->createdBy->first_name ?? __('contracts.default_owner_rep')) }}
            {{ $contract->quantityApprover->last_name ?? $contract->createdBy->last_name ?? '' }},
            {{ __('contracts.id_card') }}: ({{ $ownerId ?? '—' }}),
            {{ __('contracts.phone') }}: {{ $ownerPhone ?? '—' }}.
            <br>
            <strong>{{ __('contracts.party2') }}</strong>
            ({{ __('contracts.contractor') }}):
            {{ __('contracts.represented_by') }}
            {{ $contract->client->first_name ?? __('contracts.default_contractor') }}
            {{ $contract->client->last_name ?? '' }},
            {{ __('contracts.id_card') }}: ({{ $contractorId ?? '—' }}),
            {{ __('contracts.phone') }}: {{ $contractorPhone ?? '—' }}.
        </p>

        <p class="article-body">
            {{ __('contracts.after_agreement') }}
        </p>
    </div>

    <!-- ARTICLES (مطابقة نصوص PDF) -->
    <div class="article">
        <div class="article-title">{{ __('contracts.article_1_title') }}</div>
        <p class="article-body">
            {{ __('contracts.article_1_body', ['project' => ($contract->project->title ?? $contract->title ?? __('contracts.default_project'))]) }}
        </p>
    </div>

    <div class="article">
        <div class="article-title">{{ __('contracts.article_2_title') }}</div>
        <div class="article-body">
            <div class="clause"><strong>{{ __('contracts.clause_2_1_title') }}</strong> {{ __('contracts.clause_2_1_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_2_title') }}</strong> {{ __('contracts.clause_2_2_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_3_title') }}</strong> {{ __('contracts.clause_2_3_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_4_title') }}</strong> {{ __('contracts.clause_2_4_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_5_title') }}</strong> {{ __('contracts.clause_2_5_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_6_title') }}</strong> {{ __('contracts.clause_2_6_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_7_title') }}</strong> {{ __('contracts.clause_2_7_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_8_title') }}</strong> {{ __('contracts.clause_2_8_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_9_title') }}</strong> {{ __('contracts.clause_2_9_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_10_title') }}</strong> {{ __('contracts.clause_2_10_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_11_title') }}</strong> {{ __('contracts.clause_2_11_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_2_12_title') }}</strong> {{ __('contracts.clause_2_12_body') }}</div>
        </div>
    </div>

    <div class="article">
        <div class="article-title">{{ __('contracts.article_3_title') }}</div>
        <p class="article-body">
            {{ __('contracts.article_3_body', ['amount' => number_format($contractValue ?? $contract->value ?? 0, 2)]) }}
        </p>
    </div>

    <div class="article">
        <div class="article-title">{{ __('contracts.article_4_title') }}</div>
        <div class="article-body">
            <div class="clause"><strong>{{ __('contracts.clause_4_1_title') }}</strong> {{ __('contracts.clause_4_1_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_4_2_title') }}</strong> {{ __('contracts.clause_4_2_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_4_3_title') }}</strong> {{ __('contracts.clause_4_3_body') }}</div>
        </div>
    </div>

    <div class="article">
        <div class="article-title">{{ __('contracts.article_5_title') }}</div>
        <div class="article-body">
            <div class="clause"><strong>{{ __('contracts.clause_5_1_title') }}</strong> {{ __('contracts.clause_5_1_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_5_2_title') }}</strong> {{ __('contracts.clause_5_2_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_5_3_title') }}</strong> {{ __('contracts.clause_5_3_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_5_4_title') }}</strong> {{ __('contracts.clause_5_4_body') }}</div>
            <div class="clause"><strong>{{ __('contracts.clause_5_5_title') }}</strong> {{ __('contracts.clause_5_5_body') }}</div>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <div class="items-title">{{ __('contracts.items_table_title') }}</div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:6%">{{ __('contracts.col_no') }}</th>
                <th style="width:46%">{{ __('contracts.col_desc') }}</th>
                <th style="width:10%">{{ __('contracts.col_unit') }}</th>
                <th style="width:10%">{{ __('contracts.col_qty') }}</th>
                <th style="width:14%">{{ __('contracts.col_rate') }}</th>
                <th style="width:14%">{{ __('contracts.col_total') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $rows = [];

                if(isset($contract->quantities) && $contract->quantities->count() > 0){
                    foreach($contract->quantities as $q){
                        $rows[] = [
                            'desc'  => $q->item->name ?? $q->item_description ?? __('contracts.default_item_row'),
                            'unit'  => $q->unit->name ?? $q->unit ?? 'م2',
                            'qty'   => (float)($q->quantity ?? 0),
                            'rate'  => (float)($q->rate ?? $q->unit_price ?? 0),
                            'total' => (float)($q->amount ?? ((float)($q->quantity ?? 0) * (float)($q->rate ?? 0))),
                        ];
                    }
                } else {
                    // fallback like PDF sample
                    $rows[] = [
                        'desc'  => $contract->title ?? __('contracts.default_item_row'),
                        'unit'  => 'م2',
                        'qty'   => 200,
                        'rate'  => 68,
                        'total' => (float)($contractValue ?? $contract->value ?? 0),
                    ];
                }

                $grand = 0;
                foreach($rows as $r){ $grand += (float)$r['total']; }
            @endphp

            @foreach($rows as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="desc">{{ $r['desc'] }}</td>
                    <td>{{ $r['unit'] }}</td>
                    <td>{{ rtrim(rtrim(number_format($r['qty'], 3), '0'), '.') }}</td>
                    <td>{{ rtrim(rtrim(number_format($r['rate'], 3), '0'), '.') }}</td>
                    <td>{{ number_format($r['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SIGNATURES -->
    <div class="signatures">
        <table class="sig-table">
            <tr>
                <td>
                    {{ __('contracts.sig_party1') }}<br>
                    <div class="sig-name">
                        {{ $contract->quantityApprover->first_name ?? $contract->createdBy->first_name ?? __('contracts.default_owner_rep') }}
                        {{ $contract->quantityApprover->last_name ?? $contract->createdBy->last_name ?? '' }}
                    </div>
                    <div class="sig-line">{{ __('contracts.sig_signature') }}: .....................</div>
                    <div class="sig-line">{{ __('contracts.sig_stamp') }}: .................</div>
                </td>

                <td>
                    {{ __('contracts.sig_party2') }}<br>
                    <div class="sig-name">
                        {{ $contract->client->first_name ?? __('contracts.default_contractor') }}
                        {{ $contract->client->last_name ?? '' }}
                    </div>
                    <div class="sig-line">{{ __('contracts.sig_signature') }}: .....................</div>
                    <div class="sig-line">{{ __('contracts.sig_stamp') }}: .................</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER (exact style like PDF) -->
    <div class="footer">
        {{ $companyInfo['address'] ?? __('contracts.default_address') }}
        | {{ __('contracts.phone_label') }}: {{ $companyInfo['phone'] ?? __('contracts.default_phone') }}
        | {{ $companyInfo['website'] ?? __('contracts.default_website') }}
    </div>

    <div class="page-no">
        {{ __('contracts.page') }} {PAGE_NUM} {{ __('contracts.of') }} {PAGE_COUNT}
    </div>

</body>
</html>