$(document).ready(function () {
    $('#boq_item_id').change(function () {
        var boq_item_id = $(this).val();
        debugger;
        url = get_unit_by_boq_item_url.replace('*', boq_item_id);
        if (boq_item_id) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    debugger;
                    $('#unit_id').val('');
                    $('#unit_name').val('');
                    $('#unit_id').val(data.id);
                    $('#unit_name').val(data.name);
                }
            });
        } else {
                    $('#unit_id').val('');
                    $('#unit_name').val('');
        }
    });

    $('#boq_sub_item_id').change(function () {
        var boq_sub_item_id = $(this).val();
        debugger;
        url = get_unit_by_boq_sub_item_url.replace('*', boq_sub_item_id);
        if (boq_sub_item_id) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    debugger;
                    $('#unit_id').val('');
                    $('#unit_name').val('');
                    $('#unit_id').val(data.id);
                    $('#unit_name').val(data.name);
                }
            });
        } else {
                    $('#unit_id').val('');
                    $('#unit_name').val('');
        }
    });
});
