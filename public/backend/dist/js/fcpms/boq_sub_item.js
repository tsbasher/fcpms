$(document).ready(function() {
    $('#boq_item_id').change(function() {
        var boq_item_id = $(this).val();
        debugger;
        url= get_boq_sub_item_by_boq_item_url.replace('*', boq_item_id);
        if (boq_item_id) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    debugger;
                    $('#boq_sub_item_id').empty();
                    $('#boq_sub_item_id').append('<option value="">Select BOQ Sub Item</option>');
                    $.each(data, function(key, value) {
                        $('#boq_sub_item_id').append(`<option value="${value.id}" >${value.code}.${value.name}</option>`);
                    });
                }
            });
        } else {
            $('#boq_sub_item_id').empty();
            $('#boq_sub_item_id').append('<option value="">Select BOQ Sub Item</option>');
        }
        $('#boq_sub_item_id').select2();
    });
});
