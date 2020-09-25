$(function() {

    definirFiltroPeriodo($('#dateFilter'));
    // var dateFilter = $('#dateFilter');

    // dateFilter.daterangepicker({
    //     autoUpdateInput: false,
    //     locale: {
    //         cancelLabel: 'Clear',
    //         format: 'YYYY-MM-DD'
    //     }
    // });

    // dateFilter.on('apply.daterangepicker', function(ev, picker) {
    //     $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ' + picker.endDate.format('YYYY-MM-DD'));
    // });

    // dateFilter.on('cancel.daterangepicker', function(ev, picker) {
    //     $(this).val('');
    // });
});

window.definirFiltroPeriodo = function(elemento, drops = "auto") {
    elemento.daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        },
        drops: drops,
        opens: "left"
    });

    elemento.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ' + picker.endDate.format('YYYY-MM-DD'));
    });

    elemento.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
}

