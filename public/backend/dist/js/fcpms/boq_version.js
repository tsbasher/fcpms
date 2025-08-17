$(document).ready(function() {
    $('#package_id').change(function() {
        var package_id = $(this).val();
        debugger;
        url= get_boq_versions_by_package_url.replace('*', package_id);
        if (package_id) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    debugger;
                    $('#boq_version_id').empty();
                    $('#boq_version_id').append('<option value="">Select BOQ Version</option>');
                    $.each(data, function(key, value) {
                        $('#boq_version_id').append(`<option value="${value.id}" >${value.name} - ${value.version_date}</option>`);
                    });
                }
            });
        } else {
            $('#boq_version_id').empty();
            $('#boq_version_id').append('<option value="">Select BOQ Version</option>');
        }
        $('#boq_version_id').select2();
    });
});
