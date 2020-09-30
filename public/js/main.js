/**************************************************
 * GŁÓWNY PLIK JS 
 **************************************************/

/***** zmienna przechowywująca styl ładowania datatables *****/
var ajaxDivLoader = "<div class='text-center'><img src='/img/helpers/ajax-loader.gif' /></div>";



/***************** PACE - SYSTEM LOADING ******************/
window.paceOptions = {
    ajax: {
        trackMethods: ['GET', 'POST', 'PUT', 'DELETE', 'REMOVE']
    }
};



/************************ MODALE *************************/
/**
 * obsługa modala do zapytań czy usunąć daną pozycję
 * @param {string} link 
 * @param {string} title 
 * @param {string} content 
 */
function modalConfirmDelete(link, title = '', content = '') {
    $('.modal-submit-delete').attr('href', link);

    if (title != '') {
        $('.modal-confirm-delete-title').text(title);
    }
    if (content != '') {
        $('.modal-confirm-delete-body').text(content);
    }
    $('#modal-confirm-delete').modal();
} // end modalConfirmDelete



/************************ DATEPICKER *************************/
$.datepicker.regional['pl'] = {
    firstDay: 1,
    closeText: "Zamknij", // Display text for close link
    prevText: "Poprzedni", // Display text for previous month link
    nextText: "Następny", // Display text for next month link
    currentText: "Dzisiaj", // Display text for current month link
    monthNames: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"], // Names of months for drop-down and formatting
    monthNamesShort: ["Sty", "Lut", "Mar", "Kwi", "Maj", "Cze", "Lip", "Sie", "Wrz", "Paź", "Lis", "Gru"], // For formatting
    dayNames: ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota"], // For formatting
    dayNamesShort: ["Nd", "Pn", "Wt", "Śr", "Cz", "Pt", "Sb"], // For formatting
    dayNamesMin: ["Nd", "Pn", "Wt", "Śr", "Cz", "Pt", "Sb"], // Column headings for days starting at Sunday
    weekHeader: "Ty", // Column header for week of the year
    isRTL: false, // True if right-to-left language, false if left-to-right
    showMonthAfterYear: false, // True if the year select precedes month, false for month then year
    yearSuffix: "" // Additional text to append to the year in the month headers    
};
$.timepicker.regional['pl'] = {
    firstDay: 1,
    timeOnlyTitle: 'Wybierz godzinę',
    closeText: "Zamknij",
    currentText: "Dzisiaj",
    timeText: 'Aktualnie',
    hourText: 'Godzina',
    minuteText: 'Minuta',
    secondText: 'Sekunda',
    hourMin: 7,
    hourMax: 17,
    hourGrid: 2,
    stepMinute: 10,
    minuteGrid: 10
};
$.datepicker.setDefaults($.datepicker.regional['pl']);
$.timepicker.setDefaults($.timepicker.regional['pl']);

$(".datetimepicker").datetimepicker({
    // ##### ZOSTAWIAM DLA PRZYKŁADU    
    beforeShow: function(input, inst) {
        // $(".ui-datepicker").css('font-size', 12);
    },
    dateFormat: "yy-mm-dd",
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datetimepicker-inversely").datetimepicker({
    dateFormat: "dd-mm-yy",
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datepicker").datepicker({
    dateFormat: "yy-mm-dd",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".datepicker-inversely").datepicker({
    dateFormat: "dd-mm-yy",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});
$(".timepicker").timepicker({
    timeFormat: "HH:mm",
    locale: 'pl',
    showAnim: "fade",
    controlType: 'slider'
});