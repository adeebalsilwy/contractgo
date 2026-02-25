@extends('layout')
@section('title', 'Item Pricing')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('home')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('items')}}">Items</a>
                    </li>
                    <li class="breadcrumb-item active">Item Pricing</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{route('item-pricing.create')}}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus"></i> Add Item Pricing
            </a>
        </div>
    </div>

    @if ($itemPricings->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table id="table" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Cost Price</th>
                            <th>Status</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemPricings as $pricing)
                        <tr>
                            <td>{{ $pricing->id }}</td>
                            <td>{{ $pricing->item->title ?? 'N/A' }}</td>
                            <td>{{ $pricing->unit->title ?? 'N/A' }}</td>
                            <td>{{ format_currency($pricing->price) }}</td>
                            <td>{{ format_currency($pricing->cost_price ?? 0) }}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="item-pricing-status" data-id="{{ $pricing->id }}" {{ $pricing->is_active ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>{{ $pricing->valid_from ? format_date($pricing->valid_from) : 'N/A' }}</td>
                            <td>{{ $pricing->valid_until ? format_date($pricing->valid_until) : 'N/A' }}</td>
                            <td>{{ $pricing->creator->first_name ?? 'N/A' }} {{ $pricing->creator->last_name ?? '' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('item-pricing.show', $pricing) }}">
                                            <i class="bx bx-show me-1"></i> View
                                        </a>
                                        <a class="dropdown-item" href="{{ route('item-pricing.edit', $pricing) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="{{ $pricing->id }}">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </a>
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
    @else
    <div class="card">
        <div class="card-body">
            <x-empty-state-card type="Item Pricing" />
        </div>
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item pricing?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle status toggle
    $('.item-pricing-status').change(function() {
        var id = $(this).data('id');
        var isActive = $(this).is(':checked');
        
        $.ajax({
            url: '{{ url("item-pricing") }}/' + id + '/toggle-status',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _method: 'POST'
            },
            success: function(response) {
                if(response.error) {
                    alert('Error updating status');
                    // Revert the checkbox state
                    $('.item-pricing-status[data-id="' + id + '"]').prop('checked', !isActive);
                } else {
                    // Success message could be shown here
                }
            },
            error: function(xhr) {
                alert('Error updating status');
                // Revert the checkbox state
                $('.item-pricing-status[data-id="' + id + '"]').prop('checked', !isActive);
            }
        });
    });

    // Handle delete button clicks
    var deleteId = null;
    $('.delete-btn').click(function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDeleteBtn').click(function() {
        if(deleteId) {
            $.ajax({
                url: '{{ url("item-pricing") }}/' + deleteId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.error) {
                        alert('Error deleting item pricing');
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting item pricing');
                }
            });
        }
        $('#deleteModal').modal('hide');
    });
});
</script>
@endsection