<?php

use RWC\QRCM\RWC_Qrcode;
use RWC\QRCM\Plugin;

?>
<div class="wrap">

    <h1><?php esc_html_e( 'QR code setting', Plugin::TEXTDOMAIN ); ?></h1>

    <form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
		<?php settings_fields( 'rwc-qrcm-settings-group' ); ?>
		<?php do_settings_sections( 'rwc-qrcm-settings-group' ); ?>

		<?php
		$qr_code_data_settings_list = array(
			'url'     => 'URL',
			'contact' => 'Contact',
			'event'   => 'Events',
			'free'    => 'Free',
		);

		$rwc_qrcm = get_option( 'rwc_qrcm' );

		if ( ! $rwc_qrcm ) {
			$rwc_qrcm = array(
				'display'        => array(
					'list' => array(
						'image'    => 'on',
						'download' => 'on'
					)
				),
				'qrdatasettings' => array(
					'url' => 'on',
				),
				'role'           => array(
					'post_type' => array(
						'page' => 'on'
					),
					'roles'     => array(
						'administrator' => 'on',
						'editor'        => 'on'
					)
				),
				'output'         => array(
					'format' => array(
						'png' => 'on'
					)
				),
				'redirect'       => array(
					'presence' => 'valid'
				),
			);
		}
		?>

        <div id="poststuff">

            <div id="post-body" class="metabox-holder">

                <div id="postbox-container-2" class="postbox-container">
                    <div class="rwc_border_box">
                        <h2><?php esc_html_e( 'Display settings', Plugin::TEXTDOMAIN ); ?></h2>

                        <div class="rwc_inner_box">
                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'List display', Plugin::TEXTDOMAIN ); ?></h3>

                            <div class="rwc_inner_box__body">
                                <input type="checkbox" name="rwc_qrcm[display][list][image]"
                                       id="rwc_qrcm_display_list_image" value="on"
									<?php if ( isset( $rwc_qrcm['display']['list']['image'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_display_list_image"><?php esc_html_e( 'Image', Plugin::TEXTDOMAIN ); ?></label>
                                <input type="checkbox" name="rwc_qrcm[display][list][download]"
                                       id="rwc_qrcm_display_list_download" value="on"
									<?php if ( isset( $rwc_qrcm['display']['list']['download'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_display_list_download"><?php esc_html_e( 'Download', Plugin::TEXTDOMAIN ); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="rwc_border_box">
                        <h2><?php esc_html_e( 'QR Code data settings', Plugin::TEXTDOMAIN ); ?></h2>

                        <div class="rwc_inner_box">
                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'Data format used', Plugin::TEXTDOMAIN ); ?></h3>

                            <div class="rwc_inner_box__body">
								<?php foreach ( $qr_code_data_settings_list as $qr_code_data_settings_key => $qr_code_data_settings_value ): ?>
                                    <input type="checkbox"
                                           name="rwc_qrcm[qrdatasettings][<?php echo esc_attr( $qr_code_data_settings_key ); ?>]"
                                           id="rwc_qrcm_qrdatasettings_<?php echo esc_attr( $qr_code_data_settings_key ); ?>"
                                           value="on"
										<?php if ( isset( $rwc_qrcm['qrdatasettings'][ $qr_code_data_settings_key ] ) ) {
											echo esc_attr( 'checked' );
										} ?>>
                                    <label for="rwc_qrcm_qrdatasettings_<?php echo esc_attr( $qr_code_data_settings_key ); ?>"><?php esc_html_e( $qr_code_data_settings_value, Plugin::TEXTDOMAIN ); ?></label>
								<?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="rwc_border_box">
                        <h2><?php esc_html_e( 'Role settings', Plugin::TEXTDOMAIN ); ?></h2>

                        <div class="rwc_inner_box">
                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'Custom post type settings', Plugin::TEXTDOMAIN ); ?></h3>

                            <div class="rwc_inner_box__body">
								<?php $post_types = $this->get_custum_post_types(); ?>
								<?php foreach ( $post_types as $post_type ): ?>
									<?php $post_type_object = get_post_type_object( $post_type ); ?>
                                    <input type="checkbox"
                                           name="rwc_qrcm[role][post_type][<?php esc_attr_e( $post_type ); ?>]"
                                           id="rwc_qrcm_role_post_type_<?php esc_attr_e( $post_type ); ?>"
                                           value="on"
										<?php if ( isset( $rwc_qrcm['role']['post_type'][ $post_type ] ) ) {
											echo esc_attr( 'checked' );
										} ?>>
                                    <label for="rwc_qrcm_role_post_type_<?php esc_attr_e( $post_type ); ?>"><?php esc_html_e( $post_type_object->label ); ?></label>
								<?php endforeach; ?>
                            </div>

                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'User settings', Plugin::TEXTDOMAIN ); ?></h3>

                            <div class="rwc_inner_box__body">
                                <input type="checkbox"
                                       name="rwc_qrcm[role][user][profile]"
                                       id="rwc_qrcm_role_user_profile"
                                       value="on"
									<?php if ( isset( $rwc_qrcm['role']['user']['profile'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_role_user_profile"><?php _e( 'Use with user profile', Plugin::TEXTDOMAIN ); ?></label>
                            </div>

                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'Available permissions', Plugin::TEXTDOMAIN ); ?></h3>

                            <div class="rwc_inner_box__body">
								<?php $roles = $this->get_roles(); ?>
								<?php foreach ( $roles as $key => $role ): ?>
                                    <input type="checkbox"
                                           name="rwc_qrcm[role][roles][<?php esc_attr_e( $key ); ?>]"
                                           id="rwc_qrcm_role_roles_<?php esc_attr_e( $key ); ?>"
                                           value="on"
										<?php if ( isset( $rwc_qrcm['role']['roles'][ $key ] ) ) {
											echo esc_attr( 'checked' );
										} ?>>
                                    <label for="rwc_qrcm_role_roles_<?php esc_attr_e( $key ); ?>"><?php esc_html_e( _x( $role['name'], 'User role' ) ); ?></label>
								<?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="rwc_border_box">
                        <h2><?php esc_html_e( 'Output setting', Plugin::TEXTDOMAIN ); ?></h2>

                        <div class="rwc_inner_box">
                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'Format', Plugin::TEXTDOMAIN ); ?></h3>
                            <div class="rwc_inner_box__body">
                                <input type="checkbox" name="rwc_qrcm[output][format][jpg]"
                                       id="rwc_qrcm_output_format_jpg" value="on"
									<?php if ( isset( $rwc_qrcm['output']['format']['jpg'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_output_format_jpg"><?php esc_html_e( 'jpeg', Plugin::TEXTDOMAIN ); ?></label>
                                <input type="checkbox" name="rwc_qrcm[output][format][png]"
                                       id="rwc_qrcm_output_format_png" value="on"
									<?php if ( isset( $rwc_qrcm['output']['format']['png'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_output_format_png"><?php esc_html_e( 'png', Plugin::TEXTDOMAIN ); ?></label>
                                <input type="checkbox" name="rwc_qrcm[output][format][eps]"
                                       id="rwc_qrcm_output_format_eps" value="on"
									<?php if ( isset( $rwc_qrcm['output']['format']['eps'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_output_format_eps"><?php esc_html_e( 'eps', Plugin::TEXTDOMAIN ); ?></label>
                                <input type="checkbox" name="rwc_qrcm[output][format][svg]"
                                       id="rwc_qrcm_output_format_svg" value="on"
									<?php if ( isset( $rwc_qrcm['output']['format']['svg'] ) ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_output_format_svg"><?php esc_html_e( 'svg', Plugin::TEXTDOMAIN ); ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="rwc_border_box">
                        <h2><?php esc_html_e( 'Redirect settings', Plugin::TEXTDOMAIN ); ?></h2>

                        <div class="rwc_inner_box">
                            <h3 class="rwc_inner_box__header"><?php esc_html_e( 'Presence', Plugin::TEXTDOMAIN ); ?></h3>
                            <div class="rwc_inner_box__body">
                                <input type="radio" name="rwc_qrcm[redirect][presence]"
                                       id="rwc_qrcm_redirect_presence_valid" value="valid"
									<?php if ( isset( $rwc_qrcm['redirect']['presence'] ) && $rwc_qrcm['redirect']['presence'] == 'valid' ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_redirect_presence_valid"><?php esc_html_e( 'Valid', Plugin::TEXTDOMAIN ); ?></label>
                                <input type="radio" name="rwc_qrcm[redirect][presence]"
                                       id="rwc_qrcm_redirect_presence_invalid" value="invalid"
									<?php if ( isset( $rwc_qrcm['redirect']['presence'] ) && $rwc_qrcm['redirect']['presence'] == 'invalid' ) {
										echo esc_attr( 'checked' );
									} ?>>
                                <label for="rwc_qrcm_redirect_presence_invalid"><?php esc_html_e( 'Invalid', Plugin::TEXTDOMAIN ); ?></label>
                            </div>
                        </div>
                    </div>


                    <div id="rwcqrcm_meta_box_qrcode_settings" class="postbox ">
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php esc_html_e( 'QR Code Settings', Plugin::TEXTDOMAIN ); ?></span></h2>
                        <div class="inside">
							<?php RWC_Qrcode::insert_metabox_qrcode_settings_fields_options(); ?>
                        </div>
                    </div>

                    <div id="rwcqrcm_meta_box_scaner_shortcode" class="postbox ">
                        <h2 class="hndle ui-sortable-handle"><span>qr code shortcode</span></h2>
                        <div class="inside">
                            <table class="form-table">
                                <tr class="field-label-size">
                                    <th><label for="field-logo-size-width">shorcode</label></th>
                                    <td>
                                        <input type="text" id="" value="[qrcm_scanner]" readonly>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <p class="submit">
			<?php submit_button( __( 'Save', Plugin::TEXTDOMAIN ), 'button-primary', false, false ); ?>
        </p>
    </form>
</div>