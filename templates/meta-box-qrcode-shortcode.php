<?php
/**
 * @var $shortcode_text
 */

use RWC\QRCM\Plugin;


?>
<table class="form-table" role="presentation">
    <tr class="field-individual">
        <th><label for="rwcqrcm-shortcode"><?php _e( 'Shortcode', Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="text" id="rwcqrcm-shortcode" value="<?php echo esc_attr( $shortcode_text ); ?>" readonly>
        </td>
    </tr>
</table>
