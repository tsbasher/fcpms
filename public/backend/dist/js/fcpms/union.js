$(document).ready(function() {
    $('#upazila_id').change(function() {
        var upazilaId = $(this).val();
        debugger;
        url= union_url.replace('*', upazilaId);
        if (upazilaId) {
            $.ajax({
                url: url,
                type: 'GET',
                data: { upazila_id: upazilaId },
                success: function(data) {
                    debugger;
                    $('#union_id').empty();
                    $('#union_id').append('<option value="">Select Union</option>');
                    $.each(data, function(key, value) {
                        $('#union_id').append(`<option value="${value.id}">${value.name}</option>`);
                    });
                }
            });
        } else {
            $('#union_id').empty();
            $('#union_id').append('<option value="">Select Union</option>');
        }
        $('#union_id').select2();
    });
});
