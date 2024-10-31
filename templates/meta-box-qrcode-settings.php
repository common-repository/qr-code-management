<?php
/**
 * @var $rwcqrcm_qrsettings_individual
 * @var $rwcqrcm_qrsettings_margin
 * @var $rwcqrcm_qrsettings_correction_level
 * @var $rwcqrcm_qrsettings_label
 * @var $rwcqrcm_qrsettings_size_width
 * @var $rwcqrcm_qrsettings_color
 * @var $rwcqrcm_qrsettings_background_color
 * @var $rwcqrcm_qrsettings_logo
 * @var $rwcqrcm_qrsettings_logo_width
 * @var $rwcqrcm_qrsettings_logo_height
 * @var $rwcqrcm_qrsettings_background_image
 * @var $rwcqrcm_qrsettings_background_image_edit
 * @var $rwcqrcm_qrsettings_background_image_edit_topText
 * @var $rwcqrcm_qrsettings_background_image_edit_bottomText
 * @var $rwcqrcm_qrsettings_background_image_edit_fontFamiry
 * @var $rwcqrcm_qrsettings_background_image_edit_fontSize
 * @var $rwcqrcm_qrsettings_background_image_edit_fontColor
 * @var $rwcqrcm_qrsettings_background_image_edit_rad
 * @var $rwcqrcm_qrsettings_background_image_edit_topTextType
 * @var $rwcqrcm_qrsettings_background_image_edit_qrcodeSize
 */

use RWC\QRCM\Plugin;

$rwcqrcm_qrsettings_logo_has_value = '';
if ( $rwcqrcm_qrsettings_logo ) {
	$rwcqrcm_qrsettings_logo_has_value = 'has-value';
}
if ( $rwcqrcm_qrsettings_background_image ) {
	$rwcqrcm_qrsettings_background_image_has_value = 'has-value';
}

?>
<table class="form-table" role="presentation">
    <tr class="field-individual">
        <th><label><?php _e( 'Set individually', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="radio" name="rwcqrcm[qrsettings][individual]" id="rwcqrcm-qrsettings-individual-off"
                   value="off" <?php if ( ! $rwcqrcm_qrsettings_individual || $rwcqrcm_qrsettings_individual == 'off' ) {
				echo 'checked';
			} ?>>
            <label for="rwcqrcm-qrsettings-individual-off"><?php _e( 'Not set', Plugin::TEXTDOMAIN ); ?></label>
            <input type="radio" name="rwcqrcm[qrsettings][individual]" id="rwcqrcm-qrsettings-individual-on"
                   value="on" <?php if ( $rwcqrcm_qrsettings_individual == 'on' ) {
				echo 'checked';
			} ?>>
            <label for="rwcqrcm-qrsettings-individual-on"><?php _e( 'Set', Plugin::TEXTDOMAIN ); ?></label>
        </td>
    </tr>
</table>
<table class="form-table" role="presentation" id="rwcqrcm-qrsettings">
    <tr class="field-margin">
        <th><label for="field-margin"><?php _e( 'Margin', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="number" name="rwcqrcm[qrsettings][margin]" id="field-margin" class="regular-text"
                   placeholder="10" value="<?php echo $rwcqrcm_qrsettings_margin; ?>">
            <p class="description"><?php _e( 'Specify border size around QR code in px', Plugin::TEXTDOMAIN ); ?></p>
        </td>
    </tr>
    <tr class="field-correction-level">
        <th>
            <label for="field-correction-level"><?php _e( 'Correction level', Plugin::TEXTDOMAIN ); ?></label>
        </th>
        <td>
            <select name="rwcqrcm[qrsettings][correction_level]" id="field-correction-level">
                <option value="LOW" <?php if ( ! $rwcqrcm_qrsettings_correction_level || $rwcqrcm_qrsettings_correction_level == 'LOW' ) {
					echo 'selected';
				} ?>>
					<?php _e( 'Level L – up to 7%&nbsp;damage', Plugin::TEXTDOMAIN ); ?>
                </option>
                <option value="MEDIUM" <?php if ( $rwcqrcm_qrsettings_correction_level == 'MEDIUM' ) {
					echo 'selected';
				} ?>>
					<?php _e( 'Level M – up to 15%&nbsp;damage', Plugin::TEXTDOMAIN ); ?>
                </option>
                <option value="QUARTILE" <?php if ( $rwcqrcm_qrsettings_correction_level == 'QUARTILE' ) {
					echo 'selected';
				} ?>>
					<?php _e( 'Level Q – up to 25%&nbsp;damage', Plugin::TEXTDOMAIN ); ?>
                </option>
                <option value="HIGH" <?php if ( $rwcqrcm_qrsettings_correction_level == 'HIGH' ) {
					echo 'selected';
				} ?>>
					<?php _e( 'Level H – up to 30%&nbsp;damage', Plugin::TEXTDOMAIN ); ?>
                </option>
            </select>
            <p class="description"><?php _e( 'There are different amounts of “backup” data depending on how much damage the QR code is expected to suffer in its intended environment.', Plugin::TEXTDOMAIN ); ?></p>
        </td>
    </tr>
    <tr class="field-label">
        <th><label for="field-label"><?php _e( 'Label', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" name="rwcqrcm[qrsettings][label]" id="field-label" class="regular-text"
                   value="<?php echo $rwcqrcm_qrsettings_label; ?>">
            <p class="description"><?php _e( 'Optional text label below QR code.', Plugin::TEXTDOMAIN ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label
                    for="rwcqrcm_qrsettings_size_width"><?php _e( 'Size', Plugin::TEXTDOMAIN ); ?></label>
        </th>
        <td>
            <input type="number" id="rwcqrcm_qrsettings_size_width" name="rwcqrcm[qrsettings][size_width]"
                   class="regular-text"
                   value="<?php echo $rwcqrcm_qrsettings_size_width; ?>"
                   placeholder="100">
            <p class="description"><?php _e( 'Input a square value, Default:100', Plugin::TEXTDOMAIN ); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label
                    for="rwcqrcm_qrsettings_color"><?php _e( 'Color', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="rwcqrcm_qrsettings_color" name="rwcqrcm[qrsettings][color]"
                   value="<?php echo $rwcqrcm_qrsettings_color; ?>"
                   class="qrc-color-picker">
        </td>
    </tr>
    <tr>
        <th scope="row"><label
                    for="rwcqrcm_qrsettings_background_color"><?php _e( 'Background Color', Plugin::TEXTDOMAIN ); ?></label>
        </th>
        <td>
            <input type="text" id="rwcqrcm_qrsettings_background_color" name="rwcqrcm[qrsettings][background_color]"
                   value="<?php echo $rwcqrcm_qrsettings_background_color; ?>" class="qrc-color-picker">
        </td>
    </tr>
    <tr class="field-logo">
        <th><?php _e( 'Logo', Plugin::TEXTDOMAIN ); ?></th>
        <td>
            <div id="rwcqrcm_qrsettings_logo"
                 class="plp-image-uploader <?php echo $rwcqrcm_qrsettings_logo_has_value; ?>"
                 data-preview_size="medium" data-library="all" data-mime_types=""
                 data-uploader="wp">
                <input type="hidden" id="rwcqrcm_qrsettings_logo_id"
                       name="rwcqrcm[qrsettings][logo]"
                       value="<?php echo $rwcqrcm_qrsettings_logo; ?>">
                <div class="show-if-value image-wrap" style="max-width: 300px">
                    <img id="rwcqrcm_qrsettings_logo_image" data-name="image"
                         src="<?php echo wp_get_attachment_url( $rwcqrcm_qrsettings_logo ); ?>" alt="">
                    <div class="plp-actions">
                        <a href="#" class="plp-image-edit"
                           data-for="rwcqrcm_qrsettings_logo"><?php _e( 'Edit', Plugin::TEXTDOMAIN ); ?></a>
                        <a href="#" class="plp-image-delete"
                           data-for="rwcqrcm_qrsettings_logo"><?php _e( 'Delete', Plugin::TEXTDOMAIN ); ?></a>
                    </div>
                </div>
                <div class="hide-if-value">
                    <p><?php _e( 'No image selected', Plugin::TEXTDOMAIN ); ?> <a
                                class="button plp-image-add" data-for="rwcqrcm_qrsettings_logo"
                                href="#"><?php _e( 'Add an image', Plugin::TEXTDOMAIN ); ?></a></p>
                </div>
            </div>

            <p class="description"><?php _e( 'Optional logo image at center of QR code.', Plugin::TEXTDOMAIN ); ?></p>

            <!--            <input id="field-logo" name="rwcqrcm[qrsettings][logo_id]" type="hidden"-->
            <!--                   value="--><?php //echo $rwcqrcm_qrsettings_logo_id; ?><!--">-->
            <!--            <img src="https://demo.redwoodcity.jp/plugin/wp-content/uploads/2019/10/C330_01-150x150.jpg"-->
            <!--                 id="wqm-picsrc">-->
            <!--            <button type="button" id="wqm_logo_path_upload" class="button button-primary button-large"-->
            <!--                    style="display:none;">-->
            <!--                Select logo image-->
            <!--            </button>-->
            <!--            <button type="button" id="wqm_logo_path_delete" class="button button-add-media button-large" style="">-->
            <!--                Delete logo image-->
            <!--            </button>-->
            <!--            <span class="description">Optional logo image at center of QR code.</span>-->
        </td>
    </tr>
    <tr class="field-label-size">
        <th><label for="field-logo-size-width"><?php _e( 'Logo size', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <div class="logo-sizes">
                <input type="number" name="rwcqrcm[qrsettings][logo_width]" id="field-logo-size-width"
                       placeholder="width"
                       value="<?php echo $rwcqrcm_qrsettings_logo_width; ?>">
                <input type="number" name="rwcqrcm[qrsettings][logo_height]" id="field-logo-size-height"
                       placeholder="height" value="<?php echo $rwcqrcm_qrsettings_logo_height; ?>">
            </div>
            <p class="description"><?php _e( 'Set logo image size in pixels or percents.', Plugin::TEXTDOMAIN ); ?></p>
        </td>
    </tr>
    <!--    <tr>-->
    <!--        <th scope="row"></th>-->
    <!--        <td>-->
    <!--            <div class="qrc_prev_manage">-->
    <!--                <h3 class="prev-heading">Preview</h3>-->
    <!--                <img id="qrious">-->
    <!--            </div>-->
    <!--            <img src="https://demo.redwoodcity.jp/plugin/wp-content/plugins/qr-code-composer/admin/img/qr_code_pro.png"-->
    <!--                 alt="Qr Code Preview">-->
    <!--        </td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th scope="row">Shortcode</th>-->
    <!--        <td><code>[qr_code_composer]</code></td>-->
    <!--    </tr>-->

    <tr class="field-background_image">
        <th><?php _e( 'Background image', Plugin::TEXTDOMAIN ); ?></th>
        <td>
            <div id="rwcqrcm_qrsettings_background_image"
                 class="plp-image-uploader <?php echo $rwcqrcm_qrsettings_background_image_has_value; ?>"
                 data-preview_size="medium" data-library="all" data-mime_types=""
                 data-uploader="wp">
                <input type="hidden" id="rwcqrcm_qrsettings_background_image_id"
                       name="rwcqrcm[qrsettings][background_image]"
                       value="<?php echo $rwcqrcm_qrsettings_background_image; ?>">
                <div class="show-if-value image-wrap" style="max-width: 300px">
                    <img id="rwcqrcm_qrsettings_background_image_image" data-name="image"
                         src="<?php echo wp_get_attachment_url( $rwcqrcm_qrsettings_background_image ); ?>" alt="">
                    <div class="plp-actions">
                        <a href="#" class="plp-image-edit"
                           data-for="rwcqrcm_qrsettings_background_image"><?php _e( 'Edit', Plugin::TEXTDOMAIN ); ?></a>
                        <a href="#" class="plp-image-delete"
                           data-for="rwcqrcm_qrsettings_background_image"><?php _e( 'Delete', Plugin::TEXTDOMAIN ); ?></a>
                    </div>
                </div>
                <div class="hide-if-value">
                    <p><?php _e( 'No image selected', Plugin::TEXTDOMAIN ); ?> <a
                                class="button plp-image-add" data-for="rwcqrcm_qrsettings_background_image"
                                href="#"><?php _e( 'Add an image', Plugin::TEXTDOMAIN ); ?></a></p>
                </div>
            </div>
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'edit background image', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="radio" name="rwcqrcm[qrsettings][background_image_edit]"
                   id="rwcqrcm-qrsettings-background_image_edit-off"
                   value="off" <?php if ( ! $rwcqrcm_qrsettings_background_image_edit || $rwcqrcm_qrsettings_background_image_edit == 'off' ) {
				echo 'checked';
			} ?>>
            <label for="rwcqrcm-qrsettings-background_image_edit-off"><?php _e( 'Not set', Plugin::TEXTDOMAIN ); ?></label>
            <input type="radio" name="rwcqrcm[qrsettings][background_image_edit]"
                   id="rwcqrcm-qrsettings-background_image_edit-on"
                   value="on" <?php if ( $rwcqrcm_qrsettings_background_image_edit == 'on' ) {
				echo 'checked';
			} ?>>
            <label for="rwcqrcm-qrsettings-background_image_edit-on"><?php _e( 'Set', Plugin::TEXTDOMAIN ); ?></label>
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'edit background image preview', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <div class="qrcm_canvas_block">
                <canvas id="qrcm_canvas" width="600" height="600"></canvas>
                <img id="qrcm_canvas_image"
                     src="<?php echo plugins_url( "/assets/images/sample_qrcode.png", Plugin::PLUGIN_BASEFILE ); ?>"
                     alt="Sample QR Code">
                <script>
                    var QRCMQrcodeEditorData = {
                        rad: <?php echo $rwcqrcm_qrsettings_background_image_edit_rad; ?>,
                        topText: "<?php echo $rwcqrcm_qrsettings_background_image_edit_topText; ?>",
                        topTextType: <?php echo $rwcqrcm_qrsettings_background_image_edit_topTextType; ?>,
                        bottomText: "<?php echo $rwcqrcm_qrsettings_background_image_edit_bottomText; ?>",
                        fontSize: <?php echo $rwcqrcm_qrsettings_background_image_edit_fontSize; ?>,
                        fontFamiry: '<?php echo $rwcqrcm_qrsettings_background_image_edit_fontFamiry; ?>',
                        fontColor: '<?php echo $rwcqrcm_qrsettings_background_image_edit_fontColor; ?>',
                        qrcodeSize: <?php echo $rwcqrcm_qrsettings_background_image_edit_qrcodeSize; ?>,
                        backgroundImageSrc: "<?php echo wp_get_attachment_url( $rwcqrcm_qrsettings_background_image ); ?>"
                    }
                </script>
            </div>
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Top text', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="qrcm_topText" name="rwcqrcm[qrsettings][background_image_edit_topText]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_topText; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Bottom text', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="qrcm_bottomText" name="rwcqrcm[qrsettings][background_image_edit_bottomText]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_bottomText; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Font famiry', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="qrcm_fontFamiry" name="rwcqrcm[qrsettings][background_image_edit_fontFamiry]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_fontFamiry; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label for="qrcm_fontSize"><?php _e( 'Font size', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="number" id="qrcm_fontSize" name="rwcqrcm[qrsettings][background_image_edit_fontSize]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_fontSize; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Font color', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="qrcm_fontColor" class="qrc-background-image-color-picker"
                   name="rwcqrcm[qrsettings][background_image_edit_fontColor]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_fontColor; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Text position', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="range" id="qrcm_rad" max="0.45" step="0.01"
                   name="rwcqrcm[qrsettings][background_image_edit_rad]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_rad; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Top text type', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <label><input type="radio" id="qrcm_topTextType_inside"
                          name="rwcqrcm[qrsettings][background_image_edit_topTextType]" value="0" checked>
                Inside</label>
            <label><input type="radio" id="qrcm_topTextType_outside"
                          name="rwcqrcm[qrsettings][background_image_edit_topTextType]" value="1">
                Outside</label>
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'Qrcode size', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="range" id="qrcm_qrcodeSize" max="100" step="1"
                   name="rwcqrcm[qrsettings][background_image_edit_qrcodeSize]"
                   value="<?php echo $rwcqrcm_qrsettings_background_image_edit_qrcodeSize; ?>">
        </td>
    </tr>
    <tr class="field-background_image_edit">
        <th><label><?php _e( 'save background image', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <a href="#" id="download" download="canvas.png">save</a>
        </td>
    </tr>
</table>
