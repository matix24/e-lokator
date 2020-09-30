$(document).ready(function() {
    $('.dataTable').DataTable({
        ajax: {
            url: manufacturerListUrl,
            type: "POST",
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
            [1, 'asc']
        ],
        info: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { "name": "m.id_manufacturer", "targets": 0, "orderable": true },
            { "name": "m.manufacturer_name", "targets": 1, "orderable": true },
            { "name": "m.manufacturer_created_at", "targets": 2, "orderable": true },
            { "name": "m.manufacturer_updated_at", "targets": 3, "orderable": true },
            { "name": "buttons", "targets": 4, "orderable": false }
        ]
    });
});

// USUWANIE POZYCJI
$(document).on('click', '.manufacturer-to-delete', function() {
    modalConfirmDelete($(this).attr('data-link'));
});