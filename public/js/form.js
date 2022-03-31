$(document).ready(function() {
    $('#check-multiple-sheets').click(function() {
        $(this).is(':checked') ? $('#div-sheet-name').show() : $('#div-sheet-name').hide();
    })

    ($('#check-multiple-sheets').is(':checked')) ? $('#div-sheet-name').show() : $('#div-sheet-name').hide();
})