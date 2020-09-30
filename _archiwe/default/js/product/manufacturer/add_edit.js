$(document).ready(function() {
    $('form[name=manufacturer]').validate({
        lang: 'pl',
        rules: {
            'manufacturer[manufacturer_name]': {
                required: true,
                minlength: 1,
                maxlength: 250
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});