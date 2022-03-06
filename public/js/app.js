$(function() {
    APP.upload.init();
});

var APP = APP || {};

APP.upload = {
    stockInfoDiv: $('#stock_info_div'),

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
        var self = this;
        $('#stock_info').click(function() {
            self.stockInfoDiv.html('Calculating...');

            $.getJSON($(this).data('route'),{
                stock: $('#stock').val(),
                start_datepicker: $('#start_datepicker').val(),
                end_datepicker: $('#end_datepicker').val()
            }).done(function(data) {
                self.stockInfoDiv.html(data);
                setTimeout(function() {
                    self.stockInfoDiv
                        .find('.table-success')
                        .removeClass('table-success');
                }, 2000);
            }).fail(function( jqxhr, textStatus, error ) {
                self.stockInfoDiv.html(jqxhr.responseJSON);
            });
        });
    }

};
