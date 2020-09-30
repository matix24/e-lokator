$(document).ready(function() {
    $('form[name=partner]').validate({
        lang: 'pl',
        rules: {
            'partner[partner_name]': {
                required: true,
                minlength: 1,
                maxlength: 250
            },
            'partner[partner_default_margin]': {
                required: true,
                min: 0,
                number: true
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