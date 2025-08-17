$(document).ready(function () {
    $('#boq_part_id').change(function () {
        var boq_part_id = $(this).val();
        debugger;
        url = get_boq_item_by_boq_part_url.replace('*', boq_part_id);
        if (boq_part_id) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    debugger;
                    $('#boq_item_id').empty();
                    $('#boq_sub_item_id').empty();
                    $('#boq_sub_item_id').append('<option value="">Select BOQ Sub Item</option>');
                    $('#boq_item_id').append('<option value="">Select BOQ Item</option>');
                    $.each(data, function (key, value) {
                        $('#boq_item_id').append(`<option value="${value.id}" >${value.code}.${value.name}</option>`);
                    });
                }
            });
        } else {
            $('#boq_item_id').empty();
            $('#boq_sub_item_id').empty();
            $('#boq_item_id').append('<option value="">Select BOQ Item</option>');
            $('#boq_sub_item_id').append('<option value="">Select BOQ Sub Item</option>');
        }
        $('#boq_item_id').select2();
        $('#boq_sub_item_id').select2();
    });
});
