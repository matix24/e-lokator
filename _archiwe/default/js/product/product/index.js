$(function() {
    let productDataTable;
    $(document).ready(function() {
        productDataTable = $('.dataTable').DataTable({
            ajax: {
                url: productListUrl,
                type: "POST",
                data: function(d) {
                    return $.extend({}, d, {
                        "filter": {
                            "id_category": $('select[name=idCategory]').val(),
                            "id_manufacturer": $('select[name=idManufacturer]').val(),
                            "product_price_from": $('input[name=productPriceFrom]').val(),
                            "product_price_to": $('input[name=productPriceTo]').val(),
                            "product_date_added_from": $('input[name=productDateAddedFrom]').val(),
                            "product_date_added_to": $('input[name=productDateAddedTo]').val(),
                        }
                    });
                },
                beforeSend: function(xhr) {},
                complete: function(json) {}
            },
            language: {
                processing: ajaxDivLoader,
                zeroRecords: "Brak wyników, wybierz inne kryteria wyszukiwania lub wyczyść filtrowanie",
                info: "Strona _PAGE_ z _PAGES_",
                infoEmpty: "",
                search: "Szukaj:",
                lengthMenu: "Pozycji _MENU_",
                paginate: {
                    first: "Pierwsza",
                    last: "Ostatnia",
                    next: "Następna",
                    previous: "Poprzednia"
                }
            },
            ordering: true,
            processing: true,
            serverSide: true,
            paging: true,
            searching: true,
            lengthChange: true,
            lengthMenu: [
                [50, 100, 500, 1000, 5000, 10000, -1],
                [50, "100", "500", "1 000", "5 000", "10 000", "All"]
            ],
            iDisplayStart: 0,
            order: [
                [0, 'asc']
            ],
            info: true,
            autoWidth: false,
            responsive: true,
            columnDefs: [
                { name: "p.id_product", targets: 0, orderable: true },
                { name: "p.product_manufacturer_symbol", targets: 1, orderable: true },
                { name: "p.product_name", targets: 2, orderable: true },
                { name: "p.product_description", targets: 3, orderable: true },
                { name: "c.category_name", targets: 4, orderable: true },
                { name: "m.manufacturer_name", targets: 5, orderable: true },
                { name: "p.product_manufacturer_price", targets: 6, orderable: true },
                { name: "p.product_created_at", targets: 7, orderable: true },
                { name: "p.product_updated_at", targets: 8, orderable: true },
                { name: "buttons", targets: 9, orderable: false },
            ]
        });

        // WALIDACJA FILTROWANIA
        $('.form-filter').validate({
            lang: 'pl',
            rules: {
                'productPriceFrom': {
                    required: false,
                    min: 0
                },
                'productPriceTo': {
                    required: false,
                    min: 0
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

    }); // end document ready

    // USUWANIE POZYCJI
    $(document).on('click', '.product-to-delete', function() {
        modalConfirmDelete($(this).attr('data-link'));
    });


    // FILTROWANIE - CZYSZCZENIE
    $(document).on('click', '.btn-filter-clear', function() {
        $('select[name=idCategory]').val(-1);
        $('select[name=idManufacturer]').val(-1);
        $('input[name=productPriceFrom]').val(null);
        $('input[name=productPriceTo]').val(null);
        $('input[name=productDateAddedFrom]').val(null);
        $('input[name=productDateAddedTo]').val(null);
        productDataTable.draw();
    });

    // FILTROWANIE
    $(document).on('click', '.btn-filter', function() {
        if ($('.form-filter').valid()) {
            productDataTable.draw();
        }
    });

}); // end function