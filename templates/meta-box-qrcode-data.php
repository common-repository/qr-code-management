<?php
/**
 * @var $rwcqrcm_qrdata_individual
 */
?>
<table class="form-table" role="presentation">
    <tr class="field-individual">
        <th><label><?php _e( 'Set individually', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
        <td>
            <input type="radio" name="rwcqrcm[qrdata][individual]" id="field-qrdata-individual-off"
                   value="off" <?php if ( ! $rwcqrcm_qrdata_individual || $rwcqrcm_qrdata_individual == 'off' ) {
				echo 'checked';
			} ?>>
            <label for="field-qrdata-individual-off"><?php _e( 'Not set', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
            <input type="radio" name="rwcqrcm[qrdata][individual]" id="field-qrdata-individual-on"
                   value="on" <?php if ( $rwcqrcm_qrdata_individual == 'on' ) {
				echo 'checked';
			} ?>>
            <label for="field-qrdata-individual-on"><?php _e( 'Set', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
        </td>
    </tr>
</table>
<div class="tabs" id="rwcqrcm_qrdata_tabs">
	<?php $rwcqrcm_qrdata_type_type_count = 0; ?>
	<?php if ( isset( $this->option['qrdatasettings']['url'] ) ) : ?>
        <input id="rwcqrcm_qrdata_url" type="radio" name="rwcqrcm[qrdata][type][type]"
               value="url" <?php if ( ! $rwcqrcm_qrdata_type_type || $rwcqrcm_qrdata_type_type == 'url' ) {
			echo 'checked';
		} ?>>
        <label class="tab_item" for="rwcqrcm_qrdata_url"><?php _e( 'URL', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
		<?php $rwcqrcm_qrdata_type_type_count ++; ?>
	<?php endif; ?>
	<?php if ( isset( $this->option['qrdatasettings']['contact'] ) ) : ?>
        <input id="rwcqrcm_qrdata_contact" type="radio" name="rwcqrcm[qrdata][type][type]"
               value="contact" <?php if ( $rwcqrcm_qrdata_type_type == 'contact' ) {
			echo 'checked';
		} ?>>
        <label class="tab_item"
               for="rwcqrcm_qrdata_contact"><?php _e( 'Contact', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
		<?php $rwcqrcm_qrdata_type_type_count ++; ?>
	<?php endif; ?>
	<?php if ( isset( $this->option['qrdatasettings']['event'] ) ) : ?>
        <input id="rwcqrcm_qrdata_event" type="radio" name="rwcqrcm[qrdata][type][type]"
               value="event" <?php if ( $rwcqrcm_qrdata_type_type == 'event' ) {
			echo 'checked';
		} ?>>
        <label class="tab_item"
               for="rwcqrcm_qrdata_event"><?php _e( 'Events', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
		<?php $rwcqrcm_qrdata_type_type_count ++; ?>
	<?php endif; ?>
	<?php if ( isset( $this->option['qrdatasettings']['free'] ) ) : ?>
        <input id="rwcqrcm_qrdata_free" type="radio" name="rwcqrcm[qrdata][type][type]"
               value="free" <?php if ( $rwcqrcm_qrdata_type_type == 'free' ) {
			echo 'checked';
		} ?>>
        <label class="tab_item"
               for="rwcqrcm_qrdata_free"><?php _e( 'Free', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
		<?php $rwcqrcm_qrdata_type_type_count ++; ?>
	<?php endif; ?>
    <style>
        .tab_item {
            width: calc(100% / <?php echo $rwcqrcm_qrdata_type_type_count; ?>);
        }
    </style>
    <div class="tab_content" id="rwcqrcm_qrdata_url_content">
        <table class="form-table" role="presentation">
            <tr class="field-margin">
                <th>
                    <label for="rwcqrcm_qrdata_url_permalink"><?php _e( 'URL', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                </th>
                <td>
                    <div class="tab_content_description">
                        <input type="url" id="rwcqrcm_qrdata_url_permalink" name="rwcqrcm[qrdata][url][permalink]"
                               value="<?php echo $rwcqrcm_qrdata_url_permalink; ?>"
                               size="50" placeholder="https://"/>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="tab_content" id="rwcqrcm_qrdata_contact_content">
        <div class="tab_content_description">
            <table class="form-table" role="presentation">
                <tbody>
                <tr class="field-type">
                    <th><label for="field-type"><?php _e( 'Type', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <select name="rwcqrcm[qrdata][contact][type]" id="field-type">
                            <option value="mecard" <?php if ( ! $rwcqrcm_qrdata_contact_type || $rwcqrcm_qrdata_contact_type == 'mecard' ) {
								echo 'selected';
							} ?>><?php _e( 'MeCard', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?>
                            </option>
                            <option value="vcard" <?php if ( $rwcqrcm_qrdata_contact_type == 'vcard' ) {
								echo 'selected';
							} ?>><?php _e( 'vCard', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?>
                            </option>
                        </select>
                        <p class="description"><?php _e( 'Select contact information QR code format', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-n">
                    <th><label for="field-n"><?php _e( 'Name', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][n]" id="field-n" class="regular-text"
                               placeholder="Web Marshal"
                               value="<?php echo $rwcqrcm_qrdata_contact_n; ?>">
                        <p class="description"><?php _e( 'A structured representation of the name of the person. When a field is divided by a comma (,), the first half is treated as the last name and the second half is treated as the first name.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-nickname">
                    <th><label for="field-nickname"><?php _e( 'Nickname', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][nickname]" id="field-nickname"
                               class="regular-text"
                               placeholder="WM_the_best" value="<?php echo $rwcqrcm_qrdata_contact_nickname; ?>">
                        <p class="description"><?php _e( 'Familiar name for the object represented by this MeCard/vCard.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-sound">
                    <th><label for="field-sound"><?php _e( 'Sound', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][sound]" id="field-sound" class="regular-text"
                               placeholder="WM_the_best"
                               value="<?php echo $rwcqrcm_qrdata_contact_sound; ?>">
                        <p class="description"><?php _e( 'Designates a text string to be set as the kana name in the phonebook.When a field is divided by a comma (,), the first half is treated as the last name and the second half is treated as the first name.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-tel">
                    <th><label for="field-tel"><?php _e( 'Phone', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][tel]" id="field-tel" class="regular-text"
                               placeholder="+7(978) 571-91-44" value="<?php echo $rwcqrcm_qrdata_contact_tel; ?>">
                        <p class="description"><?php _e( 'The canonical number string for a telephone number for telephony communication.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-tel-av">
                    <th><label for="field-tel-av"><?php _e( 'Videophone', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][tel_av]" id="field-tel-av"
                               class="regular-text"
                               placeholder="+7(978) 571-91-44"
                               value="<?php echo $rwcqrcm_qrdata_contact_tel_av; ?>">
                        <p class="description"><?php _e( 'The canonical string for a videophone number communication.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-email">
                    <th><label for="field-email"><?php _e( 'Email', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][email]" id="field-email" class="regular-text"
                               placeholder="web.marshal.ru@gmail.com"
                               value="<?php echo $rwcqrcm_qrdata_contact_email; ?>">
                        <p class="description"><?php _e( 'The address for electronic mail communication.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-adr">
                    <th><label for="field-adr"><?php _e( 'Address', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][adr]" id="field-adr" class="regular-text"
                               placeholder="Киевская улица, 160, офис 312, Симферополь, Республика Крым, Россия, 295043"
                               value="<?php echo $rwcqrcm_qrdata_contact_adr; ?>">
                        <p class="description"><?php _e( 'The physical delivery address. The fields divided by commas (,) denote PO box, room number, house number, city, prefecture, zip code and country, in order.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-bday">
                    <th><label for="field-bday"><?php _e( 'Birthday', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][bday]" id="field-bday"
                               class="regular-text" placeholder="2018-11-21"
                               value="<?php echo $rwcqrcm_qrdata_contact_bday; ?>">
                        <p class="description"><?php _e( '8 digits for date of birth: year (4 digits), month (2 digits) and day (2 digits), in order.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-title">
                    <th><label for="field-title"><?php _e( 'Title', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][title]" id="field-title" class="regular-text"
                               placeholder="Web Marshal"
                               value="<?php echo $rwcqrcm_qrdata_contact_title; ?>">
                        <p class="description"><?php _e( 'Position held in organization.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-org">
                    <th><label for="field-org"><?php _e( 'Organization', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][org]" id="field-org"
                               class="regular-text" placeholder="Web Marshal"
                               value="<?php echo $rwcqrcm_qrdata_contact_org; ?>">
                        <p class="description"><?php _e( 'Organization name.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-url">
                    <th><label for="field-url"><?php _e( 'URL', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][url]" id="field-url" class="regular-text"
                               placeholder="https://web-marshal.ru/" value="<?php echo $rwcqrcm_qrdata_contact_url; ?>">
                        <p class="description"><?php _e( 'A URL pointing to a website that represents the person in some way.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                <tr class="field-note">
                    <th><label for="field-note"><?php _e( 'Note', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label></th>
                    <td>
                        <input type="text" name="rwcqrcm[qrdata][contact][note]" id="field-note" class="regular-text"
                               value="<?php echo $rwcqrcm_qrdata_contact_note; ?>">
                        <p class="description"><?php _e( 'Specifies supplemental information to be set as memo in the phonebook.', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!--<div class="tab_content" id="map_content">-->
    <!--    <div class="tab_content_description">-->
    <!--        <p class="c-txtsp">総合の内容がここに入ります</p>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="tab_content" id="rwcqrcm_qrdata_event_content">
        <table class="form-table" role="presentation">

            <!--                    BEGIN:VCALENDAR-->
            <!--                    VERSION:2.0-->
            <!--                    PRODID:-//hacksw/handcal//NONSGML v1.0//EN-->
            <!--                    BEGIN:VEVENT-->
            <!--                    DTSTART:19970714T170000Z-->
            <!--                    DTEND:19970715T035959Z-->
            <!--                    SUMMARY:Bastille Day Party-->
            <!--                    END:VEVENT-->
            <!--                    END:VCALENDAR-->

            <tr class="field-margin">
                <th>
                    <label for="rwcqrcm_qrdata_event_dtstart"><?php _e( 'Start', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                </th>
                <td>
                    <input type="datetime-local" name="rwcqrcm[qrdata][event][dtstart]"
                           id="rwcqrcm_qrdata_event_dtstart"
                           class="regular-text"
                           value="<?php echo $rwcqrcm_qrdata_event_dtstart; ?>">
                </td>
            </tr>
            <tr class="field-margin">
                <th><label for="rwcqrcm_qrdata_event_dtend"><?php _e( 'End', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                </th>
                <td>
                    <input type="datetime-local" name="rwcqrcm[qrdata][event][dtend]" id="rwcqrcm_qrdata_event_dtend"
                           class="regular-text"
                           value="<?php echo $rwcqrcm_qrdata_event_dtend; ?>">
                </td>
            </tr>
            <tr class="field-margin">
                <th>
                    <label for="rwcqrcm_qrdata_event_summary"><?php _e( 'Summary', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                </th>
                <td>
                    <input type="text" name="rwcqrcm[qrdata][event][summary]" id="rwcqrcm_qrdata_event_summary"
                           class="regular-text"
                           value="<?php echo $rwcqrcm_qrdata_event_summary; ?>">
                </td>
            </tr>
        </table>
    </div>
    <div class="tab_content" id="rwcqrcm_qrdata_free_content">
        <table class="form-table" role="presentation">
            <tr class="field-margin">
                <th>
                    <label for="rwcqrcm_qrdata_free_freeinput"><?php _e( 'Free', \RWC\QRCM\Plugin::TEXTDOMAIN ); ?></label>
                </th>
                <td>
                    <div class="tab_content_description">
                        <textarea
                                id="rwcqrcm_qrdata_free_freeinput"
                                name="rwcqrcm[qrdata][free][freeinput]"><?php echo $rwcqrcm_qrdata_free_freeinput; ?></textarea>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>


