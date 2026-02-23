@extends('layouts.app')

@section('page_title', get_label('item_pricing', 'Item Pricing'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ get_label('item_pricing_list', 'Item Pricing List') }}</h4>
                        @if(permission('item_pricing_write'))
                            <a href="{{ route('item-pricing.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ get_label('create_item_pricing', 'Create Item Pricing') }}
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="data_table">
                                <thead>
                                    <tr>
                                        <th>{{ get_label('item', 'Item') }}</th>
                                        <th>{{ get_label('unit', 'Unit') }}</th>
                                        <th>{{ get_label('price', 'Price') }}</th>
                                        <th>{{ get_label('description', 'Description') }}</th>
                                        <th>{{ get_label('actions', 'Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemPricings as $itemPricing)
                                        <tr>
                                            <td>{{ $itemPricing->item->title ?? 'N/A' }}</td>
                                            <td>{{ $itemPricing->unit->title ?? 'N/A' }}</td>
                                            <td>{{ number_format($itemPricing->price, 2) }}</td>
                                            <td>{{ Str::limit($itemPricing->description, 50) }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if(permission('item_pricing_read'))
                                                            <a class="dropdown-item" href="{{ route('item-pricing.show', $itemPricing->id) }}">
                                                                <i class="bx bx-show-alt me-1"></i> {{ get_label('view', 'View') }}
                                                            </a>
                                                        @endif
                                                        @if(permission('item_pricing_write'))
                                                            <a class="dropdown-item" href="{{ route('item-pricing.edit', $itemPricing->id) }}">
                                                                <i class="bx bx-edit-alt me-1"></i> {{ get_label('edit', 'Edit') }}
                                                            </a>
                                                        @endif
                                                        @if(permission('item_pricing_delete'))
                                                            <form method="POST" action="{{ route('item-pricing.destroy', $itemPricing->id) }}" id="delete-form-{{ $itemPricing->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete({{ $itemPricing->id }})">
                                                                    <i class="bx bx-trash me-1"></i> {{ get_label('delete', 'Delete') }}
                                                                </a>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $itemPricings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("{{ get_label('are_you_sure_you_want_to_delete_this_item', 'Are you sure you want to delete this item?') }}")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection