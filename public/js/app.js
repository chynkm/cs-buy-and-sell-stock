$(function() {
    APP.upload.init();
});

var APP = APP || {};

APP.upload = {

    init: function() {
        this.initializeDatepicker();
        this.getStockInfo();
    },

    initializeDatepicker: function() {
        $('#start_datepicker, #end_datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    },

    getStockInfo: function() {
        $('#stock_info').click(function() {
            $.getJSON($(this).data('route'),{
                stock: $('#stock').val(),
                start_datepicker: $('#start_datepicker').val(),
                end_datepicker: $('#end_datepicker').val()
            }).done(function(data) {
                $('#stock_info_table').html(data);
            }).fail(function( jqxhr, textStatus, error ) {
                // @todo needs to be completed
            });
        });
    }

};
