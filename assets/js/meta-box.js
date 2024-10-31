jQuery(document).ready(function ($) {

    $(function () {
        if ($('.rwcqrcm_page_rwcqrcm-setting').length) {
            return;
        }
        if ($('#field-qrdata-individual-off').prop('checked')) {
            $('#rwcqrcm_qrdata_tabs').hide();
        }
        if ($('#field-qrdata-individual-on').prop('checked')) {
            $('#rwcqrcm_qrdata_tabs').show();
        }
        $('#field-qrdata-individual-on,#field-qrdata-individual-off').on('click', function () {
            $('#rwcqrcm_qrdata_tabs').slideToggle();
        });

        if ($('#rwcqrcm-qrsettings-individual-off').prop('checked')) {
            $('#rwcqrcm-qrsettings').hide();
        }
        if ($('#rwcqrcm-qrsettings-individual-on').prop('checked')) {
            $('#rwcqrcm-qrsettings').show();
        }
        $('#rwcqrcm-qrsettings-individual-on,#rwcqrcm-qrsettings-individual-off').on('click', function () {
            $('#rwcqrcm-qrsettings').slideToggle();
        });
    });
});