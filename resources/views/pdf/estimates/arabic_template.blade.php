<!DOCTYPE html>
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ __('estimate_pdf') ?? 'Statement' }} - {{ $estimate->name ?? 'Document' }}</title>

    <style>
        @page {
            margin: 12mm 12mm 14mm 12mm;
            size: A4;
        }

        /* Fonts */
        @font-face {
            font-family: 'Tajawal';
            src: url('{{ public_path('vendor/gpdf/fonts/Tajawal-Normal.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Tajawal';
            src: url('{{ public_path('vendor/gpdf/fonts/Tajawal-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        * { box-sizing: border-box; }

        body{
            margin:0;
            padding:0;
            font-family:'Tajawal','DejaVu Sans',Arial,sans-serif;
            font-size:11pt;
            color:#000;
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }

        /* ---------- Layout helpers (PDF-safe) ---------- */
        .container{
            width:100%;
        }

        .top-header{
            width:100%;
            border-bottom:1px solid #000;
            padding:6px 0 8px 0;
            margin-bottom:10px;
        }

        .top-header-table{
            width:100%;
            border-collapse:collapse;
        }
        .top-header-table td{
            vertical-align:middle;
            padding:0;
        }

        .company-en{
            font-weight:bold;
            font-size:12pt;
            text-align:left;
            direction:ltr;
        }
        .company-ar{
            font-weight:bold;
            font-size:12pt;
            text-align:right;
            direction:rtl;
        }

        /* ---------- Info block (like PDF) ---------- */
        .info-block{
            width:100%;
            margin:8px 0 10px 0;
        }

        .info-table{
            width:100%;
            border-collapse:collapse;
        }

        .info-table td{
            padding:4px 6px;
            vertical-align:top;
        }

        .info-label{
            white-space:nowrap;
            font-weight:bold;
        }

        .info-value{
            font-weight:normal;
        }

        .info-sep{
            height:6px;
        }

        .meta-row{
            width:100%;
            border-top:1px solid #000;
            border-bottom:1px solid #000;
            padding:6px 0;
            margin:8px 0 10px 0;
        }

        .meta-table{
            width:100%;
            border-collapse:collapse;
        }
        .meta-table td{
            padding:3px 6px;
            vertical-align:middle;
        }

        .meta-title{
            font-weight:bold;
        }

        .amount{
            font-weight:bold;
        }

        /* ---------- Main detailed table (same grouping as PDF) ---------- */
        .details-table{
            width:100%;
            border-collapse:collapse;
            border:1px solid #000;
            font-size:10pt;
        }

        .details-table th,
        .details-table td{
            border:1px solid #000;
            padding:5px 6px;
            text-align:center;
            vertical-align:middle;
        }

        .details-table thead th{
            font-weight:bold;
        }

        .group-head{
            font-weight:bold;
        }

        .desc{
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }

        .totals-row td{
            font-weight:bold;
        }

        /* ---------- Signatures row ---------- */
        .signatures{
            width:100%;
            margin-top:14px;
        }
        .signatures-table{
            width:100%;
            border-collapse:collapse;
        }
        .signatures-table td{
            width:33.33%;
            text-align:center;
            padding-top:10px;
            font-weight:bold;
        }

        /* Optional: small footer spacing */
        .spacer-8{ height:8px; }
    </style>
</head>

<body>
<div class="container">

    <!-- Header: English left + Arabic right (مثل PDF) -->
    <div class="top-header">
        <table class="top-header-table">
            <tr>
                <td class="company-en">
                    {{ $companyInfo['name_en'] ?? 'Modern Al-Aqariah Company Limited' }}
                </td>
                <td class="company-ar">
                    {{ $companyInfo['name_ar'] ?? 'الشركة العقارية الحديثة المحدودة' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Block: Engineer / Contractor / Location / Project (كما يظهر في أعلى الـ PDF) -->
    <div class="info-block">
        <table class="info-table">
            <tr>
                <td style="width:50%;">
                    <span class="info-label">{{ __('supervising_engineer_name') ?? 'اسم المهندس المشرف' }}</span>
                    <span class="info-value">: {{ $estimate->engineer_name ?? '—' }}</span>
                </td>
                <td style="width:50%;">
                    <span class="info-label">{{ __('contractor_name') ?? 'اسم المقاول' }}</span>
                    <span class="info-value">: {{ $estimate->contractor_name ?? 'محمد علي عبده وهبان' }}</span>
                </td>
            </tr>
            <tr class="info-sep"><td colspan="2"></td></tr>
            <tr>
                <td style="width:50%;">
                    <span class="info-label">{{ __('project_location') ?? 'موقع المشروع' }}</span>
                    <span class="info-value">: {{ $estimate->project_location ?? 'الجمهورية اليمنية - عدن - صيرة' }}</span>
                </td>
                <td style="width:50%;">
                    <span class="info-label">{{ __('project_name') ?? 'اسم المشروع' }}</span>
                    <span class="info-value">: {{ $estimate->name ?? 'مشروع بنك عدن الأول الاسلامي - كريتر' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Meta row: Net / Statement No / Contract Value / Contract No / Item / Date / Total to date / Completion -->
    <div class="meta-row">
        <table class="meta-table">
            <tr>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('net') ?? 'الصافي' }}</span>:
                    <span class="amount">{{ number_format($estimate->net_value ?? 0, 2) }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('statement_no') ?? 'رقم المستخلص' }}</span>:
                    <span class="amount">{{ $estimate->statement_no ?? ($estimate->id ?? 1) }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('contract_value') ?? 'قيمة العقد (YER)' }}</span>:
                    <span class="amount">{{ number_format($estimate->contract_value ?? 0, 2) }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('contract_no') ?? 'رقم العقد' }}</span>:
                    <span class="amount">{{ $estimate->contract_id ?? 1 }}</span>
                </td>
            </tr>

            <tr>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('item') ?? 'البند' }}</span>:
                    <span class="amount">{{ $estimate->item_description ?? '11 - الجبسيات' }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('statement_date') ?? 'تاريخ المستخلص' }}</span>:
                    <span class="amount">{{ $estimate->created_at ? format_date($estimate->created_at) : now()->format('Y-m-d') }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('total_to_date') ?? 'إجمالي حتى تاريخه' }}</span>:
                    <span class="amount">{{ number_format($estimate->total_to_date ?? 0, 2) }}</span>
                </td>
                <td style="width:25%;">
                    <span class="meta-title">{{ __('completion_percentage') ?? 'نسبة الإنجاز' }}</span>:
                    <span class="amount">{{ number_format($estimate->completion_percentage ?? 0, 2) }}%</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Detailed table (بنفس تجميع الأعمدة الموجود في الـ PDF) -->
    <table class="details-table">
        <thead>
            <tr>
                <th rowspan="2" style="width:4%;">{{ __('no') ?? 'م' }}</th>
                <th rowspan="2" style="width:22%;">{{ __('description') ?? 'الوصف' }}</th>
                <th rowspan="2" style="width:6%;">{{ __('unit') ?? 'الوحدة' }}</th>

                <th colspan="4" class="group-head">{{ __('contract_data') ?? 'البيانات التعاقدية' }}</th>
                <th colspan="3" class="group-head">{{ __('total_work_to_date') ?? 'إجمالي الأعمال حتى تاريخه' }}</th>
                <th colspan="3" class="group-head">{{ __('previous_work_done') ?? 'الأعمال المنجزة السابقة' }}</th>
                <th colspan="3" class="group-head">{{ __('current_statement_work') ?? 'الأعمال المنجزة للمستخلص الحالي' }}</th>
            </tr>
            <tr>
                <!-- Contractual data -->
                <th>{{ __('qty') ?? 'الكمية' }}</th>
                <th>{{ __('price') ?? 'سعر' }}</th>
                <th>{{ __('value') ?? 'القيمة' }}</th>
                <th>{{ __('percentage') ?? 'النسبة' }}</th>

                <!-- Total to date -->
                <th>{{ __('qty') ?? 'الكمية' }}</th>
                <th>{{ __('total') ?? 'إجمالي' }}</th>
                <th>{{ __('percentage') ?? 'النسبة' }}</th>

                <!-- Previous -->
                <th>{{ __('qty') ?? 'الكمية' }}</th>
                <th>{{ __('total') ?? 'إجمالي' }}</th>
                <th>{{ __('percentage') ?? 'النسبة' }}</th>

                <!-- Current -->
                <th>{{ __('qty') ?? 'الكمية' }}</th>
                <th>{{ __('total') ?? 'إجمالي' }}</th>
                <th>{{ __('percentage') ?? 'النسبة' }}</th>
            </tr>
        </thead>

        <tbody>
        @php
            $rows = (isset($items) && count($items) > 0) ? $items : null;

            $sum_contract_value = 0;
            $sum_to_date_total  = 0;
            $sum_prev_total     = 0;
            $sum_curr_total     = 0;

            $sum_contract_qty   = 0;
            $sum_to_date_qty    = 0;
            $sum_prev_qty       = 0;
            $sum_curr_qty       = 0;
        @endphp

        @if($rows)
            @foreach($rows as $index => $item)
                @php
                    // Contractual
                    $c_qty   = (float) ($item->pivot->qty ?? 0);
                    $c_price = (float) ($item->pivot->price ?? 0);
                    $c_val   = (float) ($item->pivot->amount ?? 0);

                    // To date (إجمالي حتى تاريخه)
                    $td_qty  = (float) ($item->pivot->to_date_qty ?? $item->pivot->current_qty ?? 0);
                    $td_val  = (float) ($item->pivot->to_date_amount ?? $item->pivot->current_amount ?? 0);

                    // Previous
                    $p_qty   = (float) ($item->pivot->previous_qty ?? 0);
                    $p_val   = (float) ($item->pivot->previous_amount ?? 0);

                    // Current
                    $cu_qty  = (float) ($item->pivot->current_qty ?? 0);
                    $cu_val  = (float) ($item->pivot->current_amount ?? 0);

                    $sum_contract_qty   += $c_qty;
                    $sum_contract_value += $c_val;

                    $sum_to_date_qty    += $td_qty;
                    $sum_to_date_total  += $td_val;

                    $sum_prev_qty       += $p_qty;
                    $sum_prev_total     += $p_val;

                    $sum_curr_qty       += $cu_qty;
                    $sum_curr_total     += $cu_val;

                    $c_perc  = ($sum_contract_value > 0) ? ($c_val / max($sum_contract_value, 1)) * 100 : 0;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="desc">{{ $item->name ?? $item['name'] ?? '—' }}</td>
                    <td>{{ $item->unit->name ?? $item['unit'] ?? 'م2' }}</td>

                    <!-- Contractual -->
                    <td>{{ number_format($c_qty, 3) }}</td>
                    <td>{{ number_format($c_price, 3) }}</td>
                    <td>{{ number_format($c_val, 2) }}</td>
                    <td>{{ number_format(($sum_contract_value>0 ? ($c_val/$sum_contract_value)*100 : 0), 2) }}%</td>

                    <!-- Total to date -->
                    <td>{{ number_format($td_qty, 3) }}</td>
                    <td>{{ number_format($td_val, 2) }}</td>
                    <td>{{ number_format(($sum_contract_value>0 ? ($td_val/$sum_contract_value)*100 : 0), 2) }}%</td>

                    <!-- Previous -->
                    <td>{{ number_format($p_qty, 3) }}</td>
                    <td>{{ number_format($p_val, 2) }}</td>
                    <td>{{ number_format(($sum_contract_value>0 ? ($p_val/$sum_contract_value)*100 : 0), 2) }}%</td>

                    <!-- Current -->
                    <td>{{ number_format($cu_qty, 3) }}</td>
                    <td>{{ number_format($cu_val, 2) }}</td>
                    <td>{{ number_format(($sum_contract_value>0 ? ($cu_val/$sum_contract_value)*100 : 0), 2) }}%</td>
                </tr>
            @endforeach

            @php
                $to_date_perc = ($sum_contract_value>0) ? ($sum_to_date_total/$sum_contract_value)*100 : 0;
                $prev_perc    = ($sum_contract_value>0) ? ($sum_prev_total/$sum_contract_value)*100 : 0;
                $curr_perc    = ($sum_contract_value>0) ? ($sum_curr_total/$sum_contract_value)*100 : 0;
            @endphp

            <tr class="totals-row">
                <td colspan="3" class="desc">{{ __('total') ?? 'الإجمالي' }}</td>

                <!-- Contractual totals -->
                <td>{{ number_format($sum_contract_qty, 3) }}</td>
                <td>—</td>
                <td>{{ number_format($sum_contract_value, 2) }}</td>
                <td>100.00%</td>

                <!-- To date totals -->
                <td>{{ number_format($sum_to_date_qty, 3) }}</td>
                <td>{{ number_format($sum_to_date_total, 2) }}</td>
                <td>{{ number_format($to_date_perc, 2) }}%</td>

                <!-- Previous totals -->
                <td>{{ number_format($sum_prev_qty, 3) }}</td>
                <td>{{ number_format($sum_prev_total, 2) }}</td>
                <td>{{ number_format($prev_perc, 2) }}%</td>

                <!-- Current totals -->
                <td>{{ number_format($sum_curr_qty, 3) }}</td>
                <td>{{ number_format($sum_curr_total, 2) }}</td>
                <td>{{ number_format($curr_perc, 2) }}%</td>
            </tr>

        @else
            <!-- Fallback مطابق لأمثلة PDF -->
            <tr>
                <td>1</td>
                <td class="desc">{{ __('gypsum_board_work_incl_materials') ?? 'عمل جبسبورد شامل المواد' }}</td>
                <td>م2</td>

                <td>200.000</td>
                <td>68.000</td>
                <td>13600.00</td>
                <td>90.00%</td>

                <td>180.000</td>
                <td>12240.00</td>
                <td>90.00%</td>

                <td>0.000</td>
                <td>0.00</td>
                <td>0.00%</td>

                <td>180.000</td>
                <td>12240.00</td>
                <td>90.00%</td>
            </tr>
            <tr class="totals-row">
                <td colspan="3" class="desc">{{ __('total') ?? 'الإجمالي' }}</td>

                <td>200.000</td>
                <td>—</td>
                <td>13600.00</td>
                <td>100.00%</td>

                <td>180.000</td>
                <td>12240.00</td>
                <td>90.00%</td>

                <td>0.000</td>
                <td>0.00</td>
                <td>0.00%</td>

                <td>180.000</td>
                <td>12240.00</td>
                <td>90.00%</td>
            </tr>
        @endif
        </tbody>
    </table>

    <div class="spacer-8"></div>

    <!-- Signatures (3 أعمدة مثل PDF) -->
    <div class="signatures">
        <table class="signatures-table">
            <tr>
                <td>{{ __('engineering_side') ?? 'الجهة الهندسية' }}</td>
                <td>{{ __('contractor') ?? 'المقاول' }}</td>
                <td>{{ __('project_management') ?? 'إدارة المشاريع' }}</td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>