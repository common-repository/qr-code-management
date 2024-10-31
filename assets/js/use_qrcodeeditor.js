jQuery.noConflict();
jQuery(document).ready(function ($) {

    var qrcm_canvas = document.getElementById("qrcm_canvas");
    var qrcm_ctx = qrcm_canvas.getContext("2d");

    var rad = qrcm_canvas.height * 0.4;
    var topText = "Hello circle TEXT!";
    var bottomText = "Hello circle TEXT! Hello circle TEXT!aaaa";
    var topTextType = 0;
    var fontSize = 40;
    var fontFamiry = 'Century';
    var fontColor = '#000000';
    var qrcodeSize = 50;
    var backgroundImageSrc = "images/bg.png";

    const centX = qrcm_canvas.width / 2;
    const centY = qrcm_canvas.height / 2;
    var backgroundImage = new Image();


    function initQrcodeEditor() {
        rad = qrcm_canvas.height * QRCMQrcodeEditorData.rad;
        topText = QRCMQrcodeEditorData.topText;
        bottomText = QRCMQrcodeEditorData.bottomText;
        fontSize = QRCMQrcodeEditorData.fontSize;
        fontFamiry = QRCMQrcodeEditorData.fontFamiry;
        fontColor = QRCMQrcodeEditorData.fontColor;
        qrcodeSize = QRCMQrcodeEditorData.qrcodeSize;
        backgroundImageSrc = QRCMQrcodeEditorData.backgroundImageSrc;

        qrcm_ctx.clearRect(0, 0, qrcm_canvas.width, qrcm_canvas.height)
        qrcm_ctx.font = fontSize + "px " + fontFamiry;
        qrcm_ctx.textAlign = "center";
        qrcm_ctx.textBaseline = "middle";
        qrcm_ctx.fillStyle = fontColor;

        // qrcm_ctx.strokeStyle = fontColor;
    }

    function drawBackgroundImage() {
        backgroundImage.src = backgroundImageSrc;
        backgroundImage.onload = () => {
            _redraw();
        }
    }

    function _redraw() {
        qrcm_ctx.clearRect(0, 0, qrcm_canvas.width, qrcm_canvas.height);
        qrcm_ctx.drawImage(backgroundImage, 0, 0, qrcm_canvas.width, qrcm_canvas.height);

        $('#qrcm_canvas_image').css('width', (qrcm_canvas.width * (qrcodeSize / 100)));

        insertText();
    }

    function insertText() {
        qrcm_ctx.font = fontSize + "px " + fontFamiry;
        qrcm_ctx.fillStyle = fontColor;

        if (topTextType === '1') {
            qrcm_ctx.fillCircleText(topText, centX, centY, rad, Math.PI * 1.5, null, 1, 1);
        } else {
            qrcm_ctx.fillCircleText(topText, centX, centY, rad, Math.PI * 1.5);
        }
        qrcm_ctx.fillCircleText(bottomText, centX, centY, rad, Math.PI * 0.5, null, 1, 1);

    }

    $(function () {

        $(window).on('load', function () {
            initQrcodeEditor();
            drawBackgroundImage();
        });

        $('input').on('change keyup', function () {

            topText = $('#qrcm_topText').val();
            bottomText = $('#qrcm_bottomText').val();
            fontFamiry = $('#qrcm_fontFamiry').val();
            fontColor = $('#qrcm_fontColor').val();
            fontSize = $('#qrcm_fontSize').val();
            rad = qrcm_canvas.height * $('#qrcm_rad').val();
            topTextType = $('input[name="qrcm_topTextType"]:checked').val();
            qrcodeSize = $('#qrcm_qrcodeSize').val();

            _redraw();
        });

        var options = {
            defaultColor: false,
            change: function (event, ui) {
                fontColor = $('#qrcm_fontColor').val();
                _redraw();
            },
            clear: function () {
            },
            hide: true,
            palettes: true
        };
        $(".qrc-background-image-color-picker").wpColorPicker(options);

        // qrcm_canvasを画像で保存
        $("#download").click(function () {
            //var base64 = qrcm_canvas.toDataURL("image/jpeg");
            document.getElementById("download").href = qrcm_canvas.toDataURL("image/png");
        });

    });
});
