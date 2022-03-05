$(function() {
    APP.upload.init();
});

var APP = APP || {};

APP.upload = {

    init: function() {
        this.initializeDatepicker();
    },

    initializeDatepicker: function() {
        $('#start_datepicker, #end_datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd'
        });
    },

};
