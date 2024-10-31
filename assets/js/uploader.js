jQuery(document).ready(function ($) {
    var custom_uploader;
    /*##############################*/
    /* 画像選択ボタンがクリックされた場合の処理。*/
    /*##############################*/
    $('.plp-image-add, .plp-image-edit').on('click', function (e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        var dataFor = $(this).data('for');

        custom_uploader = wp.media({
            title: 'Select or Upload Media',
            // 以下のコメントアウトを解除すると画像のみに限定される。

            library: {
                type: 'image'
            },

            button: {
                text: 'Select'
            },
            multiple: false // falseにすると画像を1つしか選択できなくなる
        });
        custom_uploader.on('select', function () {
            var images = custom_uploader.state().get('selection');
            images.each(function (file) {
                $('#' + dataFor + '_id').attr('value', file.toJSON().id);
                $('#' + dataFor + '_image').attr('src', file.toJSON().url);
                $('#' + dataFor).addClass('has-value');
            });
        });
        custom_uploader.open();
    });
    /*##############################*/
    /* 削除がクリックされた場合の処理。*/
    /*##############################*/
    $(".plp-image-delete").live('click', function (e) {

        e.preventDefault();
        e.stopPropagation();

        var dataFor = $(this).data('for');

        $('#' + dataFor + '_id').attr('value', '');
        $('#' + dataFor + '_image').attr('src', '');
        $('#' + dataFor).removeClass('has-value');
    });
});