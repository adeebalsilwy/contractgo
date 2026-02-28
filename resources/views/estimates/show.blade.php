@php
use App\Models\Tax;
@endphp
@extends('layout')
@section('title')
{{ get_label('view_estimate', 'View Estimate') }}
@endsection
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4" id="section-not-to-print">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('estimates-invoices') }}">{{ get_label('etimates_invoices', 'Estimates/Invoices') }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('view_estimate', 'View Estimate') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('estimates-invoices.estimate-pdf', $estimate_invoice->id) }}" class="ms-2">
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('view_pdf', 'View PDF') }}">
                    <i class="bx bx-file"></i> {{ get_label('pdf', 'PDF') }}
                </button>
            </a>
            <a href="{{ url('estimates-invoices') }}"><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('etimates_invoices', 'Estimates/Invoices') }}"><i class="bx bx-list-ul"></i></button></a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div id='section-to-print'>
                <!-- Header with company information -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <h3 class="text-primary fw-bold mb-1">الشركة العقارية الحديثة المحدودة</h3>
                        <h4 class="text-secondary">Modern Al-Aqariah Company Limited</h4>
                    </div>
                </div>
                
                <!-- Estimate details section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <h5 class="mb-3 text-center">{{ get_label('estimate_details', 'Estimate Details') }}</h5>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('estimate_number', 'رقم المستخلص') }}:</strong></div>
                                <div class="col-6">{{ $estimate_invoice->id }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('contract_number', 'رقم العقد') }}:</strong></div>
                                <div class="col-6">1</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('item', 'البند') }}:</strong></div>
                                <div class="col-6">- 11الجبسيات</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('estimate_date', 'تاريخ المستخلص') }}:</strong></div>
                                <div class="col-6">{{ $estimate_invoice->created_at->format('Y-m-d') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <h5 class="mb-3 text-center">{{ get_label('project_details', 'Project Details') }}</h5>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('project_name', 'اسم المشروع') }}:</strong></div>
                                <div class="col-6">مشروع بنك عدن الأول الاسلامي - كريتر</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('project_location', 'موقع المشروع') }}:</strong></div>
                                <div class="col-6">الجمهورية اليمنية - عدن - صيرة</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contractor details section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <h5 class="mb-3 text-center">{{ get_label('contractor_details', 'Contractor Details') }}</h5>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('engineer_name', 'اسم المهندس المشرف') }}:</strong></div>
                                <div class="col-6">—</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('contractor_name', 'اسم المقاول') }}:</strong></div>
                                <div class="col-6">محمد علي عبده وهبان</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <h5 class="mb-3 text-center">{{ get_label('financial_summary', 'Financial Summary') }}</h5>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('contract_value', 'قيمة العقد (YER)') }}:</strong></div>
                                <div class="col-6">{{ format_currency(13600.00) }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('net_value', 'الصافي') }}:</strong></div>
                                <div class="col-6">{{ format_currency($estimate_invoice->final_total) }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('total_to_date', 'إجمالي حتى تاريخه') }}:</strong></div>
                                <div class="col-6">{{ format_currency($estimate_invoice->final_total) }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6"><strong>{{ get_label('completion_percentage', 'نسبة الإنجاز') }}:</strong></div>
                                <div class="col-6">90.00%</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contractual data table -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="border p-3 rounded">
                            <h5 class="mb-3 text-center">{{ get_label('contractual_data', 'البيانات التعاقدية') }}</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th rowspan="2" class="align-middle text-center">#</th>
                                            <th rowspan="2" class="align-middle text-center">{{ get_label('description', 'الوصف') }}</th>
                                            <th rowspan="2" class="align-middle text-center">{{ get_label('unit', 'الوحدة') }}</th>
                                            <th colspan="3" class="text-center">{{ get_label('total_work_to_date', 'إجمالي الأعمال حتى تاريخه') }}</th>
                                            <th colspan="3" class="text-center">{{ get_label('previous_work_done', 'الأعمال المنجزة.previous') }}</th>
                                            <th colspan="3" class="text-center">{{ get_label('current_estimate_work', 'الأعمال المنجزة للمستخلص الحالي') }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">{{ get_label('quantity', 'الكمية') }}</th>
                                            <th class="text-center">{{ get_label('value', 'القيمة') }}</th>
                                            <th class="text-center">{{ get_label('percentage', 'النسبة') }}</th>
                                            <th class="text-center">{{ get_label('quantity', 'الكمية') }}</th>
                                            <th class="text-center">{{ get_label('value', 'القيمة') }}</th>
                                            <th class="text-center">{{ get_label('percentage', 'النسبة') }}</th>
                                            <th class="text-center">{{ get_label('quantity', 'الكمية') }}</th>
                                            <th class="text-center">{{ get_label('value', 'القيمة') }}</th>
                                            <th class="text-center">{{ get_label('percentage', 'النسبة') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($estimate_invoice->items) > 0)
                                            @php
                                                $totalQuantity = 0;
                                                $totalAmount = 0;
                                                $totalProgress = 0;
                                                
                                                // Calculate totals from the items
                                                foreach($estimate_invoice->items as $item) {
                                                    $totalQuantity += $item->pivot->qty ?? 1;
                                                    $totalAmount += $item->pivot->amount ?? 0;
                                                }
                                                
                                                $avgProgress = count($estimate_invoice->items) > 0 ? ($totalAmount / ($estimate_invoice->total ?? 1)) * 100 : 0;
                                            @endphp
                                            
                                            @foreach($estimate_invoice->items as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $item->title ?? $item->name ?? 'N/A' }}</td>
                                                <td class="text-center">{{ $item->unit->title ?? 'M²' }}</td>
                                                
                                                <!-- Total work until date -->
                                                <td class="text-end">{{ number_format($item->pivot->qty ?? 200.000, 3) }}</td>
                                                <td class="text-end">{{ format_currency($item->pivot->amount ?? 13600.00) }}</td>
                                                <td class="text-center">{{ number_format(($item->pivot->amount ?? 13600.00) / ($estimate_invoice->total ?? 1) * 100, 2) }}%</td>
                                                
                                                <!-- Previous work done -->
                                                <td class="text-end">{{ number_format(0, 3) }}</td>
                                                <td class="text-end">{{ format_currency(0) }}</td>
                                                <td class="text-center">{{ number_format(0, 2) }}%</td>
                                                
                                                <!-- Current estimate work done -->
                                                <td class="text-end">{{ number_format($item->pivot->qty ?? 180.000, 3) }}</td>
                                                <td class="text-end">{{ format_currency($item->pivot->amount ?? 12240.00) }}</td>
                                                <td class="text-center">{{ number_format((($item->pivot->amount ?? 12240.00) / ($estimate_invoice->total ?? 1)) * 100, 2) }}%</td>
                                            </tr>
                                            @endforeach
                                            
                                            <!-- Totals row -->
                                            <tr class="table-success fw-bold">
                                                <td colspan="3" class="text-end">{{ get_label('total', 'الإجمالي') }}</td>
                                                
                                                <!-- Total work until date -->
                                                <td class="text-end">{{ number_format($totalQuantity, 3) }}</td>
                                                <td class="text-end">{{ format_currency($totalAmount) }}</td>
                                                <td class="text-center">{{ number_format($avgProgress, 2) }}%</td>
                                                
                                                <!-- Previous work done -->
                                                <td class="text-end">{{ number_format(0, 3) }}</td>
                                                <td class="text-end">{{ format_currency(0) }}</td>
                                                <td class="text-center">{{ number_format(0, 2) }}%</td>
                                                
                                                <!-- Current estimate work done -->
                                                <td class="text-end">{{ number_format($totalQuantity, 3) }}</td>
                                                <td class="text-end">{{ format_currency($totalAmount) }}</td>
                                                <td class="text-center">{{ number_format($avgProgress, 2) }}%</td>
                                            </tr>
                                        @else
                                            <!-- Default sample data based on the example provided -->
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>{{ get_label('gypsum_board_work_incl_materials', 'عمل جبس بورد شامل المواد') }}</td>
                                                <td class="text-center">م1</td>
                                                
                                                <!-- Total work until date -->
                                                <td class="text-end">200.000</td>
                                                <td class="text-end">{{ format_currency(13600.00) }}</td>
                                                <td class="text-center">100.00%</td>
                                                
                                                <!-- Previous work done -->
                                                <td class="text-end">0.000</td>
                                                <td class="text-end">{{ format_currency(0.00) }}</td>
                                                <td class="text-center">0.00%</td>
                                                
                                                <!-- Current estimate work done -->
                                                <td class="text-end">180.000</td>
                                                <td class="text-end">{{ format_currency(12240.00) }}</td>
                                                <td class="text-center">90.00%</td>
                                            </tr>
                                            
                                            <!-- Totals row -->
                                            <tr class="table-success fw-bold">
                                                <td colspan="3" class="text-end">{{ get_label('total', 'الإجمالي') }}</td>
                                                
                                                <!-- Total work until date -->
                                                <td class="text-end">200.000</td>
                                                <td class="text-end">{{ format_currency(13600.00) }}</td>
                                                <td class="text-center">100.00%</td>
                                                
                                                <!-- Previous work done -->
                                                <td class="text-end">0.000</td>
                                                <td class="text-end">{{ format_currency(0.00) }}</td>
                                                <td class="text-center">0.00%</td>
                                                
                                                <!-- Current estimate work done -->
                                                <td class="text-end">180.000</td>
                                                <td class="text-end">{{ format_currency(12240.00) }}</td>
                                                <td class="text-center">90.00%</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Signatures section -->
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="border-top pt-2">
                            <div class="fw-bold">{{ get_label('engineering_side', 'الجهة الهندسية') }}</div>
                            <div class="mt-3">&nbsp;</div>
                            <div class="fst-italic">{{ get_label('signature', 'التوقيع') }}: .....................</div>
                            <div class="fst-italic mt-1">{{ get_label('seal', 'الختم/البصمة') }}: .................</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="border-top pt-2">
                            <div class="fw-bold">{{ get_label('contractor', 'المقاول') }}</div>
                            <div class="mt-3">&nbsp;</div>
                            <div class="fst-italic">{{ get_label('signature', 'التوقيع') }}: .....................</div>
                            <div class="fst-italic mt-1">{{ get_label('seal', 'الختم/البصمة') }}: .................</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="border-top pt-2">
                            <div class="fw-bold">{{ get_label('project_management', 'إدارة المشاريع') }}</div>
                            <div class="mt-3">&nbsp;</div>
                            <div class="fst-italic">{{ get_label('signature', 'التوقيع') }}: .....................</div>
                            <div class="fst-italic mt-1">{{ get_label('seal', 'الختم/البصمة') }}: .................</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection