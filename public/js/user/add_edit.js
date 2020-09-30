$.validator.addMethod("checkJson", function(value, element) {
    try {
        JSON.parse(value);
    } catch (err) {
        return false;
    }
    return true;
}, "Niepoprawny format JSON.");

$(document).ready(function() {
    $('form[name=product]').validate({
        lang: 'pl',
        rules: {
            'product[product_name]': {
                required: true,
                minlength: 3,
                maxlength: 255
            },
            'product[product_description]': {
                required: true,
                minlength: 3
            },
            'product[product_manufacturer_symbol]': {
                required: true,
                minlength: 3,
                maxlength: 45
            },
            'product[product_manufacturer_price]': {
                required: true,
                min: 0,
                number: true
            },
            'product[product_details]': {
                required: true,
                checkJson: true
            },
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