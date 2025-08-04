$(document).ready(function() {
    $('#district_id').change(function() {
        var districtId = $(this).val();
        debugger;
        url= upazila_url.replace('*', districtId);
        if (districtId) {
            $.ajax({
                url: url,
                type: 'GET',
                data: { district_id: districtId },
                success: function(data) {
                    debugger;
                    $('#upazila_id').empty();
                    $('#upazila_id').append('<option value="">Select Upazila</option>');
                    $.each(data, function(key, value) {
                        $('#upazila_id').append(`<option value="${value.id}">${value.name}</option>`);
                    });
                }
            });
        } else {
            $('#upazila_id').empty();
            $('#upazila_id').append('<option value="">Select Upazila</option>');
        }
        $('#upazila_id').select2();
    });
});
