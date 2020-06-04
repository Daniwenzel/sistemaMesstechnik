$(function() {

    var dateFilter = $('#dateFilter');

    dateFilter.daterangepicker({
   // $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'YYYY-MM-DD'
        }
    });

    dateFilter.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' ' + picker.endDate.format('YYYY-MM-DD'));
    });

    dateFilter.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});