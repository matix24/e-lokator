$(document).ready(function() {
    $('form[name=category]').validate({
        lang: 'pl',
        rules: {
            'category[category_name]': {
                required: true,
                minlength: 1,
                maxlength: 100
            },
            'category[category_order_by]': {
                min: 1,
                digits: true
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