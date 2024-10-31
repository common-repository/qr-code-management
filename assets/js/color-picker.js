jQuery(document).ready(function ($) {
    var options = {
        defaultColor: false,
        change: function (event, ui) {
        },
        clear: function () {
        },
        hide: true,
        palettes: true
    };
    $(".qrc-color-picker").wpColorPicker(options);
});