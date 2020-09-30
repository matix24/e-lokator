$(function() {
    let mfpDataTable;
    $(document).ready(function() {
        mfpDataTable = $('.dataTable').DataTable({
            ajax: {
                url: mfpListUrl,
                type: "POST",
                data: function(d) {
                    return $.extend({}, d, {
                        "filter": {
                            "id_partner": $('select[name=idPartner]').val(),
                            "id_manufacturer": $('select[name=idManufacturer]').val(),
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
                { "name": "mfp.id_manufacturer_for_partner", "targets": 0, "orderable": true },
                { "name": "p.partner_name", "targets": 1, "orderable": true },
                { "name": "m.manufacturer_name", "targets": 2, "orderable": true },
                { "name": "p.partner_default_margin", "targets": 3, "orderable": true },
                { "name": "mfp.partner_special_profit", "targets": 4, "orderable": true },
                { "name": "mfp.partner_created_at", "targets": 5, "orderable": true },
                { "name": "mfp.partner_updated_at", "targets": 6, "orderable": true },
                { "name": "buttons", "targets": 7, "orderable": false },
            ]
        });
    }); // end document ready

    // USUWANIE POZYCJI
    $(document).on('click', '.mfp-to-delete', function() {
        modalConfirmDelete($(this).attr('data-link'));
    });

    // FILTROWANIE - CZYSZCZENIE
    $(document).on('click', '.btn-filter-clear', function() {
        $('select[name=idPartner]').val(-1);
        $('select[name=idManufacturer]').val(-1);
        mfpDataTable.draw();
    });

    // FILTROWANIE
    $(document).on('click', '.btn-filter', function() {
        // if ($('.form-filter').valid()) {
        mfpDataTable.draw();
        // }
    });

}); // end function