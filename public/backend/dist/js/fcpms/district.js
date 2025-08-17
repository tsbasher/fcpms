$(document).ready(function() {
    $('#division_id').change(function() {
        var divisionId = $(this).val();
        debugger;
        url= district_url.replace('*', divisionId);
        if (divisionId) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    debugger;
                    $('#district_id').empty();
                    $('#district_id').append('<option value="">Select District</option>');
                    $.each(data, function(key, value) {
                        $('#district_id').append(`<option value="${value.id}" >${value.name}</option>`);
                    });
                }
            });
        } else {
            $('#district_id').empty();
            $('#district_id').append('<option value="">Select District</option>');
        }
        $('#district_id').select2();
    });
});
