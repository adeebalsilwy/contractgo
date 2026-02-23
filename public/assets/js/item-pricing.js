/**
 * Item Pricing JavaScript functionality
 * Handles dynamic price updates when selecting items and units
 */

$(document).ready(function() {
    // Handle item and unit selection to update price dynamically
    $('#item_id, #unit_id').on('change', function() {
        const itemId = $('#item_id').val();
        const unitId = $('#unit_id').val();
        
        if (itemId && unitId) {
            // Make AJAX request to get the price for selected item-unit combination
            $.ajax({
                url: '/item-pricing-by-item-unit',
                method: 'GET',
                data: {
                    item_id: itemId,
                    unit_id: unitId
                },
                success: function(response) {
                    if (response.price !== null) {
                        $('#price').val(response.price);
                        toastr.success('Price updated based on selected item and unit.');
                    } else {
                        // If no specific pricing found, you could optionally clear the price field
                        // or leave it as is for manual entry
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching price:', error);
                }
            });
        }
    });

    // Also handle item selection in tasks where item pricing is used
    $(document).on('change', '#item_pricing_id', function() {
        const itemPricingId = $(this).val();
        
        if (itemPricingId) {
            // If we have the item pricing ID, we could fetch the price
            // This would be implemented based on how the form is structured
        }
    });
});