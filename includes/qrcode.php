<?php

namespace RWC\QRCM;

use RWC\QRCM\Plugin;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;

class RWC_Qrcode {

	private $option;

	public function __construct() {

		$this->option = get_option( 'rwc_qrcm' );

		add_filter( 'manage_posts_columns', array( $this, 'add_posts_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'custom_posts_column' ), 10, 2 );
		add_filter( 'manage_pages_columns', array( $this, 'add_posts_columns' ) );
		add_action( 'manage_pages_custom_column', array( $this, 'custom_posts_column' ), 10, 2 );

		// ユーザープロフィール編集画面にフィールドを追加する
		add_action( 'show_user_profile', array( $this, 'add_profile_rwcqrcm_fields' ) );
		// 自分のプロフィール編集画面にフィールドを追加する
		add_action( 'edit_user_profile', array( $this, 'add_profile_rwcqrcm_fields' ) );
		add_action( 'profile_update', array( $this, 'update_profile_rwcqrcm_fields' ) );
		add_action( 'manage_users_columns', array( $this, 'add_profile_columns' ), 10, 3 );
		add_action( 'manage_users_custom_column', array( $this, 'custom_profile_column' ), 10, 3 );


		add_action( 'wp_ajax_qrcode_download', array( $this, 'wp_ajax_qrcode_download' ) );
		add_action( 'init', array( $this, 'redirect_qrcode' ) );

		add_action( 'admin_menu', array( $this, 'add_rwcqrcm_fields' ) );
		add_action( 'save_post', array( $this, 'save_rwcqrcm_fields' ) );


		add_shortcode( 'qrcm', array( $this, 'qrcm_shortcode' ) );
		add_shortcode( 'qrcm_scanner', array( $this, 'qrcm_scanner_shortcode' ) );
	}

	function redirect_qrcode() {

		if ( $this->option['redirect']['presence'] !== 'valid' ) {
			return;
		}

		$qrc = isset( $_GET["qrc"] ) ? sanitize_text_field( $_GET["qrc"] ) : '';

		if ( ! $qrc ) {
			return;
		}

		global $wpdb;
		$rwc_qrcm_redirect_codes1 = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_value from {$wpdb->postmeta} WHERE meta_key = 'rwc_qrcm_redirect_code' AND meta_value LIKE BINARY '%s';",
				$qrc
			)
		);
		$rwc_qrcm_redirect_codes2 = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_value from {$wpdb->usermeta} WHERE meta_key = 'rwc_qrcm_redirect_code' AND meta_value LIKE BINARY '%s';",
				$qrc
			)
		);
		$rwc_qrcm_redirect_codes  = $rwc_qrcm_redirect_codes1 + $rwc_qrcm_redirect_codes2;

		foreach ( $rwc_qrcm_redirect_codes as $rwc_qrcm_redirect_code ) {

			$user_id = '';
			if ( ! $_COOKIE["rwc_qrcm_user_id"] ) {
				$user_id = sha1( uniqid( rand(), true ) );
				setcookie( 'rwc_qrcm_user_id', $user_id, time() + ( 3600 * 24 * 7 ), '/' );
			} else {
				$user_id = $_COOKIE["rwc_qrcm_user_id"];
			}

			global $wpdb;
			$wpdb->insert(
				$wpdb->prefix . 'rwc_qrcm_log',
				array(
					'post_id'     => $rwc_qrcm_redirect_code->post_id,
					'user_id'     => $user_id,
					'create_date' => date_i18n( 'Y-m-d H:i:s' )
				),
				array(
					'%d',
					'%s',
					'%s'
				)
			);

			if ( get_post_type( $rwc_qrcm_redirect_code->post_id ) == 'rwcqrcm' ) {
				wp_redirect( get_post_meta( $rwc_qrcm_redirect_code->post_id, 'rwcqrcm_qrdata_url_permalink', true ), 301 );
				exit;
			}
			wp_redirect( get_permalink( $rwc_qrcm_redirect_code->post_id ) );
			exit;
		}

	}


	function add_posts_columns( $columns ) {
		global $post_type;

		if ( ! isset( $this->option['role']['post_type'][ $post_type ] ) ) {
			return $columns;
		}

		$current_roles      = wp_get_current_user()->roles;
		$current_roles_flag = false;
		foreach ( $current_roles as $current_role ) {
			if ( isset( $this->option['role']['roles'][ $current_role ] ) ) {
				$current_roles_flag = true;
			}
		}
		if ( ! $current_roles_flag ) {
			return $columns;
		}

		$author = $columns['author'];
		$date   = $columns['date'];

		unset( $columns['author'] );
		unset( $columns['date'] );

		$columns['rwc_qrcm'] = __( 'QR Code', Plugin::TEXTDOMAIN );

		$columns['author'] = __( 'Author' );
		$columns['date']   = $date;

		return $columns;
	}

	function custom_posts_column( $column_name, $post_id ) {
		if ( $column_name == 'rwc_qrcm' ) {
			$rwc_qrcm_redirect_code = get_post_meta( $post_id, 'rwc_qrcm_redirect_code', true );

			$this->create_qrcode( $post_id, 'post' );
			$this->display_qrcode( $post_id, 'post' );
		}
	}

	function create_qrcode( $post_id, $type ) {
		$upload_dir = wp_upload_dir();
		$target_url = $upload_dir['baseurl'] . '/rwc_qrcm/' . $type . '/';
		$target     = $upload_dir['basedir'] . '/rwc_qrcm/' . $type . '/';
		if ( ! file_exists( $target ) ) {
			wp_mkdir_p( $target );
		}

		// code発行
		$rwc_qrcm_redirect_code = $this->get_redirect_code( $post_id, $type );

		$data = $this->get_qrcode_data( $post_id, $type );

		if ( ! file_exists( $target . $post_id . '/' ) ) {
			wp_mkdir_p( $target . $post_id . '/' );
		}

		$this->write_qrcode( $post_id, $data, $type );
	}

	function get_redirect_code( $post_id, $type ) {
		$rwc_qrcm_redirect_code = '';
		if ( $type == 'post' ) {
			$rwc_qrcm_redirect_code = get_post_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		} elseif ( $type == 'user' ) {
			$rwc_qrcm_redirect_code = get_user_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		}

		if ( ! $rwc_qrcm_redirect_code ) {
			global $wpdb;
			$rwc_qrcm_redirect_code1    = $wpdb->get_col( "SELECT meta_value from {$wpdb->postmeta} WHERE meta_key = 'rwc_qrcm_redirect_code';" );
			$rwc_qrcm_redirect_code2    = $wpdb->get_col( "SELECT meta_value from {$wpdb->usermeta} WHERE meta_key = 'rwc_qrcm_redirect_code';" );
			$rwc_qrcm_redirect_code_all = $rwc_qrcm_redirect_code1 + $rwc_qrcm_redirect_code2;
			$rwc_qrcm_redirect_code     = $this->generate_redirect_code( $rwc_qrcm_redirect_code_all );

			if ( $type == 'post' ) {
				update_post_meta( $post_id, 'rwc_qrcm_redirect_code', $rwc_qrcm_redirect_code );
			} elseif ( $type == 'user' ) {
				update_user_meta( $post_id, 'rwc_qrcm_redirect_code', $rwc_qrcm_redirect_code );
			}


		}

		return $rwc_qrcm_redirect_code;
	}

	function get_qrcode_data( $post_id, $type ) {
		/**
		 * @var $rwcqrcm_qrdata_individual
		 * @var $rwcqrcm_qrdata_type_type
		 * @var $rwcqrcm_qrdata_url_permalink
		 * @var $rwcqrcm_qrdata_contact_n
		 * @var $rwcqrcm_qrdata_contact_nickname
		 * @var $rwcqrcm_qrdata_contact_sound
		 * @var $rwcqrcm_qrdata_contact_tel
		 * @var $rwcqrcm_qrdata_contact_tel_av
		 * @var $rwcqrcm_qrdata_contact_email
		 * @var $rwcqrcm_qrdata_contact_adr
		 * @var $rwcqrcm_qrdata_contact_bday
		 * @var $rwcqrcm_qrdata_contact_title
		 * @var $rwcqrcm_qrdata_contact_org
		 * @var $rwcqrcm_qrdata_contact_url
		 * @var $rwcqrcm_qrdata_contact_note
		 * @var $rwcqrcm_qrdata_contact_type
		 * @var $rwcqrcm_qrdata_event_summary
		 * @var $rwcqrcm_qrdata_event_dtstart
		 * @var $rwcqrcm_qrdata_event_dtend
		 * @var $rwcqrcm_qrdata_free_freeinput
		 */
		$rwc_qrcm_redirect_code = $this->get_redirect_code( $post_id, $type );
		$url                    = home_url( '/' ) . '?qrc=' . $rwc_qrcm_redirect_code;

		$data = self::get_qrdata( $type, $post_id );
		extract( $data );

//		print_r($data);

		if ( $rwcqrcm_qrdata_individual !== 'on' ) {
			if ( $type == 'user' ) {
				return get_author_posts_url( $post_id );
			} elseif ( get_post_type( $post_id ) !== 'rwcqrcm' ) {
				if ( $this->option['redirect']['presence'] !== 'valid' ) {
					return get_permalink( $post_id );
				} else {
					return $url;
				}
			}
		}

		// type


		if ( $rwcqrcm_qrdata_type_type == 'url' ) {
			// url
			if ( $this->option['redirect']['presence'] !== 'valid' ) {
				return $rwcqrcm_qrdata_url_permalink;
			} else {
				return $url;
			}
		} else if ( $rwcqrcm_qrdata_type_type == 'contact' ) {
			// contact

			$fields = array();

			$fields_data = array(
				'N'        => $rwcqrcm_qrdata_contact_n,
				'NICKNAME' => $rwcqrcm_qrdata_contact_nickname,
				'SOUND'    => $rwcqrcm_qrdata_contact_sound,
				'TEL'      => $rwcqrcm_qrdata_contact_tel,
				'TEL-AV'   => $rwcqrcm_qrdata_contact_tel_av,
				'EMAIL'    => $rwcqrcm_qrdata_contact_email,
				'ADR'      => $rwcqrcm_qrdata_contact_adr,
				'BDAY'     => $rwcqrcm_qrdata_contact_bday,
				'TITLE'    => $rwcqrcm_qrdata_contact_title,
				'ORG'      => $rwcqrcm_qrdata_contact_org,
				'URL'      => $rwcqrcm_qrdata_contact_url,
				'NOTE'     => $rwcqrcm_qrdata_contact_note,
			);

			foreach ( $fields_data as $name => $field ) {
				$fields[] = "{$name}:{$field}";
			}

			if ( $rwcqrcm_qrdata_contact_type == 'mecard' ) {
				$text = $text = 'MECARD:' . implode( ';', $fields ) . ';';

				return $text;
			} else if ( $rwcqrcm_qrdata_contact_type == 'vcard' ) {
				$fields = implode( "\n", $fields );
				$text   = <<<CARD
BEGIN:VCARD
VERSION:3.0
{$fields}
END:VCARD
CARD;

				return $text;
			}

		} else if ( $rwcqrcm_qrdata_type_type == 'event' ) {
			// event
			$fields_data = array(
				'SUMMARY'     => $rwcqrcm_qrdata_event_summary,
				'DESCRIPTION' => '',
				'LOCATION'    => '',
				'DTSTART'     => date( 'Ymd\THis\Z', strtotime( $rwcqrcm_qrdata_event_dtstart ) ),
				'DTEND'       => date( 'Ymd\THis\Z', strtotime( $rwcqrcm_qrdata_event_dtend ) ),

			);

			$fields = array();
			foreach ( $fields_data as $name => $field ) {
				$fields[] = "{$name}:{$field}";
			}
			$fields = implode( "\n", $fields );

			$text = <<<EVENT
BEGIN:VEVENT
{$fields}
END:VEVENT
EVENT;

			return $text;

		} else if ( $rwcqrcm_qrdata_type_type == 'free' ) {
			// free

			return $rwcqrcm_qrdata_free_freeinput;
		}


		return $data;
	}

	function write_qrcode( $post_id, $data, $type ) {
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
		 */
		$upload_dir = wp_upload_dir();
		$target     = $upload_dir['basedir'] . '/rwc_qrcm/' . $type . '/';

		$rwc_qrcm_redirect_code = $this->get_redirect_code( $post_id, $type );

		$qrCode = new QrCode( $data );

		$data = $this->get_create_settings( $type, $post_id );
		extract( $data );

		if ( $rwcqrcm_qrsettings_size_width ) {
			$qrCode->setSize( $rwcqrcm_qrsettings_size_width );
		}

		if ( $rwcqrcm_qrsettings_margin ) {
			$qrCode->setMargin( $rwcqrcm_qrsettings_margin );
		}

		if ( $rwcqrcm_qrsettings_correction_level && in_array( $rwcqrcm_qrsettings_correction_level, array(
				'LOW',
				'MEDIUM',
				'QUARTILE',
				'HIGH'
			) ) ) {
			$qrCode->setErrorCorrectionLevel( ErrorCorrectionLevel::{$rwcqrcm_qrsettings_correction_level}() );
		}

		if ( $rwcqrcm_qrsettings_label ) {
			$qrCode->setLabel( $rwcqrcm_qrsettings_label, 16, null, LabelAlignment::CENTER() );
		}

		if ( $rwcqrcm_qrsettings_color ) {
			$colorcode = preg_replace( "/#/", "", $rwcqrcm_qrsettings_color );

			//「******」という形になっているはずなので、2つずつ「**」に区切る
			//そしてhexdec関数で変換して配列に格納する
			$rgb[0] = hexdec( substr( $colorcode, 0, 2 ) );
			$rgb[1] = hexdec( substr( $colorcode, 2, 2 ) );
			$rgb[2] = hexdec( substr( $colorcode, 4, 2 ) );

			$qrCode->setForegroundColor( [ 'r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => 1 ] );
		}

		if ( $rwcqrcm_qrsettings_background_color ) {
			$colorcode = preg_replace( "/#/", "", $rwcqrcm_qrsettings_background_color );

			//「******」という形になっているはずなので、2つずつ「**」に区切る
			//そしてhexdec関数で変換して配列に格納する
			$rgb[0] = hexdec( substr( $colorcode, 0, 2 ) );
			$rgb[1] = hexdec( substr( $colorcode, 2, 2 ) );
			$rgb[2] = hexdec( substr( $colorcode, 4, 2 ) );

			$qrCode->setBackgroundColor( [ 'r' => $rgb[0], 'g' => $rgb[1], 'b' => $rgb[2], 'a' => 1 ] );
		}

		$rwcqrcm_qrsettings_logo_path = '';
		if ( $rwcqrcm_qrsettings_logo ) {
			$rwcqrcm_qrsettings_logo_path = get_attached_file( $rwcqrcm_qrsettings_logo );
		}

		if ( $rwcqrcm_qrsettings_logo_path && file_exists( $rwcqrcm_qrsettings_logo_path ) ) {
			try {
				$qrCode->setLogoPath( $rwcqrcm_qrsettings_logo_path );
			} catch ( \Exception $e ) {
				// error
			}
		}

		if ( ! $rwcqrcm_qrsettings_logo_width ) {
			$rwcqrcm_qrsettings_logo_width = '10%';
		}

		if ( ! $rwcqrcm_qrsettings_logo_height ) {
			$rwcqrcm_qrsettings_logo_height = '10%';
		}

		if ( $rwcqrcm_qrsettings_logo_path ) {
			$data = $qrCode->getData();

			if ( false !== strpos( $rwcqrcm_qrsettings_logo_width, '%' ) ) { // if size set as percent
				$rwcqrcm_qrsettings_logo_width = $this->clear_digits( $rwcqrcm_qrsettings_logo_width ) * $data['inner_width'] / 100;
			}

			if ( false !== strpos( $rwcqrcm_qrsettings_logo_height, '%' ) ) { // if size set as percent
				$rwcqrcm_qrsettings_logo_height = $this->clear_digits( $rwcqrcm_qrsettings_logo_height ) * $data['inner_height'] / 100;
			}

			$qrCode->setLogoSize( intval( $rwcqrcm_qrsettings_logo_width ), intval( $rwcqrcm_qrsettings_logo_height ) );
		}

		$qrCode_type = 'svg';
		$qrCode->setWriterByName( $qrCode_type );
		$qrCode->writeFile( $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.' . $qrCode_type );

		if ( isset( $this->option['output']['format']['png'] ) ) {
			$qrCode_type = 'png';
			$qrCode->setWriterByName( $qrCode_type );
			$qrCode->writeFile( $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.' . $qrCode_type );
		}
		if ( isset( $this->option['output']['format']['eps'] ) ) {
			$qrCode_type = 'eps';
			$qrCode->setWriterByName( $qrCode_type );
			$qrCode->writeFile( $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.' . $qrCode_type );
		}
		if ( isset( $this->option['output']['format']['jpg'] ) ) {
			$qrCode_type = 'png';
			$qrCode->setWriterByName( $qrCode_type );
			$qrCode->writeFile( $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.' . $qrCode_type );

			$image = @imagecreatefrompng( $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.png' );
			imagejpeg( $image, $target . $post_id . '/' . $rwc_qrcm_redirect_code . '.jpg' );
			// メモリの解放
			imagedestroy( $image );
		}
	}

	function clear_digits( $text ) {
		return intval( preg_replace( '@[^\d]+@si', '', $text ) );
	}

	function display_qrcode( $post_id, $type, $display = true ) {

		if ( $type == 'post' ) {
			$post = get_post( $post_id );

			$post_status = array( 'publish', 'private' );

			if ( ! in_array( $post->post_status, $post_status ) ) {
				return;
			}
		}

		if ( $display ) {
			echo '<div class="column-rwc_qrcm-flex">';
			echo $this->get_qrcode_image( $post_id, $type );
			echo '<div class="column-rwc_qrcm-flex-download">';
			echo $this->get_qrcode_download( $post_id, $type );
			echo '</div>';
			echo '</div>';
		} else {
			return '<div class="column-rwc_qrcm-flex">' . $this->get_qrcode_image( $post_id, $type ) . '<div class="column-rwc_qrcm-flex-download">' .
			       $this->get_qrcode_download( $post_id, $type ) . '</div>' . '</div>';
		}
	}

	function get_qrcode_image( $post_id, $type ) {

		if ( ! isset( $this->option['display']['list']['image'] ) ) {
			return;
		}

		$upload_dir = wp_upload_dir();
		$target_url = $upload_dir['baseurl'] . '/rwc_qrcm/' . $type . '/';
		if ( $type == 'post' ) {
			$rwc_qrcm_redirect_code = get_post_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		} elseif ( $type == 'user' ) {
			$rwc_qrcm_redirect_code = get_user_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		}

		return '<div><img src="' . $target_url . $post_id . '/' . $rwc_qrcm_redirect_code . '.svg"></div>';
	}

	function get_qrcode_download( $post_id, $type ) {

		if ( ! isset( $this->option['display']['list']['download'] ) ) {
			return;
		}

		$html = '';

		$ajaxurl = admin_url( 'admin-ajax.php' ) . '?action=qrcode_download&nonce=' . wp_create_nonce( 'qrcode_download' ) . '&type=' . $type;
		if ( isset( $this->option['output']['format']['png'] ) ) {
			$html .= sprintf(
				'<a href="%s" target="_blank" class="button">%s<i class="dashicons dashicons-download"></i></a>',
				$ajaxurl . '&post_id=' . $post_id . '&format=png',
				__( 'png', Plugin::TEXTDOMAIN )
			);
		}
		if ( isset( $this->option['output']['format']['svg'] ) ) {
			$html .= sprintf(
				'<a href="%s" target="_blank" class="button">%s<i class="dashicons dashicons-download"></i></a>',
				$ajaxurl . '&post_id=' . $post_id . '&format=svg',
				__( 'svg', Plugin::TEXTDOMAIN )
			);
		}
		if ( isset( $this->option['output']['format']['eps'] ) ) {
			$html .= '<a href="' . $ajaxurl . '&post_id=' . $post_id . '&format=eps' . '" target="_blank" class="button">' . __( 'eps', Plugin::TEXTDOMAIN ) . '<i class="dashicons dashicons-download"></i></a>';
		}
		if ( isset( $this->option['output']['format']['jpg'] ) ) {
			$html .= sprintf(
				'<a href="%s" target="_blank" class="button">%s<i class="dashicons dashicons-download"></i></a>',
				$ajaxurl . '&post_id=' . $post_id . '&format=jpg',
				__( 'jpeg', Plugin::TEXTDOMAIN )
			);
		}

		return $html;
	}

	function wp_ajax_qrcode_download() {
		nocache_headers();

		$nonce   = isset( $_GET["nonce"] ) ? sanitize_text_field( $_GET["nonce"] ) : '';
		$post_id = isset( $_GET["post_id"] ) ? sanitize_text_field( $_GET["post_id"] ) : '';
		$format  = isset( $_GET["format"] ) ? sanitize_text_field( $_GET["format"] ) : '';
		$type    = isset( $_GET["type"] ) ? sanitize_text_field( $_GET["type"] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'qrcode_download' ) ) {
			return 'false';
		}
		if ( ! $post_id || ! $format ) {
			return 'false';
		}

		if ( $type == 'post' ) {
			$rwc_qrcm_redirect_code = get_post_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		} elseif ( $type == 'user' ) {
			$rwc_qrcm_redirect_code = get_user_meta( $post_id, 'rwc_qrcm_redirect_code', true );
		}
		if ( ! $rwc_qrcm_redirect_code ) {
			return 'false';
		}

		if ( ! isset( $this->option['output']['format'][ $format ] ) ) {
			return 'false';
		}

		header( 'Content-type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename="' . $rwc_qrcm_redirect_code . '.' . $format . '"' );

		$upload_dir = wp_upload_dir();
		$target_url = $upload_dir['baseurl'] . '/rwc_qrcm/' . $type . '/';

		readfile( $target_url . $post_id . '/' . $rwc_qrcm_redirect_code . '.' . $format );
		exit;
	}

	function generate_redirect_code( $redirect_code ) {
		// ランダムな英数字の生成
		$str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPUQRSTUVWXYZ';

		$create_redirect_code = substr( str_shuffle( $str ), 0, 10 );

		if ( in_array( $create_redirect_code, $redirect_code ) ) {
			return $this->generate_redirect_code( $redirect_code );
		} else {
			return $create_redirect_code;
		}
	}

	// 固定カスタムフィールドボックス
	function add_rwcqrcm_fields() {
		//add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
		//第4引数のpostをpageに変更すれば固定ページにオリジナルカスタムフィールドが表示されます(custom_post_typeのslugを指定することも可能)。
		//第5引数はnormalの他にsideとadvancedがあります。
		if ( isset( $this->option['role']['post_type'] ) ) {
			add_meta_box( 'rwcqrcm_meta_box_qrcode', __( 'QR Code Data', Plugin::TEXTDOMAIN ), array(
				$this,
				'insert_metabox_qrcode_data_fields'
			), array_keys( $this->option['role']['post_type'] ), 'normal' );
			add_meta_box( 'rwcqrcm_meta_box_qrcode_settings', __( 'QR Code Settings', Plugin::TEXTDOMAIN ), array(
				$this,
				'insert_metabox_qrcode_settings_fields'
			), array_keys( $this->option['role']['post_type'] ), 'normal' );
			add_meta_box( 'rwcqrcm_meta_box_qrcode_shortcode', __( 'QR Code Shortcode', Plugin::TEXTDOMAIN ), array(
				$this,
				'insert_metabox_qrcode_shortcode_fields'
			), array_keys( $this->option['role']['post_type'] ), 'normal' );
		}
	}


	// カスタムフィールドの入力エリア
	function insert_metabox_qrcode_data_fields( $post ) {
		$data = $this->get_qrdata( 'post', $post->ID );
		extract( $data );

		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-data.php';
	}

	// カスタムフィールドの入力エリア
	function insert_metabox_qrcode_settings_fields( $post ) {

		$data = $this->get_qrsetting( 'post', $post->ID );
		extract( $data );

		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-settings.php';
	}

	// カスタムフィールドの入力エリア
	function insert_metabox_qrcode_shortcode_fields( $post ) {

		$shortcode_text = sprintf( "[qrcm post_id=\"%d\"]", $post->ID );

		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-shortcode.php';
	}

	// カスタムフィールドの値を保存
	function save_rwcqrcm_fields( $post_id ) {

		if ( ! empty( $_POST['rwcqrcm']['qrdata'] ) ) { //題名が入力されている場合
			foreach ( $_POST['rwcqrcm']['qrdata'] as $key1 => $value1 ) {
				if ( $key1 == 'individual' ) {
					update_post_meta( $post_id, 'rwcqrcm_qrdata_' . $key1, $value1 ); //値を保存
					continue;
				}
				foreach ( $value1 as $key2 => $value2 ) {
					if ( $_POST['rwcqrcm']['qrdata'][ $key1 ][ $key2 ] ) {
						update_post_meta( $post_id, 'rwcqrcm_qrdata_' . $key1 . '_' . $key2, $value2 ); //値を保存
					} else {
						delete_post_meta( $post_id, 'rwcqrcm_qrdata_' . $key1 . '_' . $key2 ); //値を保存
					}
				}
			}
		}

		if ( ! empty( $_POST['rwcqrcm']['qrsettings'] ) ) { //題名が入力されている場合
			foreach ( $_POST['rwcqrcm']['qrsettings'] as $key1 => $value1 ) {
				if ( $_POST['rwcqrcm']['qrsettings'][ $key1 ] ) {
					update_post_meta( $post_id, 'rwcqrcm_qrsettings_' . $key1, $value1 ); //値を保存
				} else {
					delete_post_meta( $post_id, 'rwcqrcm_qrsettings_' . $key1 ); //値を保存
				}
			}
		}
	}

	function add_profile_rwcqrcm_fields( $user ) {

		if ( ! isset( $this->option['role']['user']['profile'] ) ) {
			return;
		}

		$qrdata = self::get_qrdata( 'user', $user->ID );
		extract( $qrdata );

		$qrsetting = self::get_qrsetting( 'user', $user->ID );
		extract( $qrsetting );

		$shortcode_text = sprintf( "[qrcm user_id=\"%d\"]", $user->ID );

		echo '<div id="rwcqrcm_meta_box_qrcode_data" class="postbox ">';
		echo '<h2 class="hndle ui-sortable-handle"><span>' . __( 'QR Code Data', Plugin::TEXTDOMAIN ) . '</span></h2>';
		echo '<div class="inside">';
		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-data.php';
		echo '</div></div>';
		echo '<div id="rwcqrcm_meta_box_qrcode_settings" class="postbox ">';
		echo '<h2 class="hndle ui-sortable-handle"><span>' . __( 'QR Code Settings', Plugin::TEXTDOMAIN ) . '</span></h2>';
		echo '<div class="inside">';
		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-settings.php';
		echo '</div></div>';
		echo '<div id="rwcqrcm_meta_box_qrcode_shortcode" class="postbox ">';
		echo '<h2 class="hndle ui-sortable-handle"><span>' . __( 'QR Code Shortcode', Plugin::TEXTDOMAIN ) . '</span></h2>';
		echo '<div class="inside">';
		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-shortcode.php';
		echo '</div></div>';
	}

	function update_profile_rwcqrcm_fields() {
		$user_id = $_POST['user_id'];

		if ( ! empty( $_POST['rwcqrcm']['qrdata'] ) ) { //題名が入力されている場合
			foreach ( $_POST['rwcqrcm']['qrdata'] as $key1 => $value1 ) {
				if ( is_array( $value1 ) ) {
					foreach ( $value1 as $key2 => $value2 ) {
						update_user_meta( $user_id, 'rwcqrcm_qrdata_' . $key1 . '_' . $key2, $value2 ); //値を保存
					}
				} else {
					update_user_meta( $user_id, 'rwcqrcm_qrdata_' . $key1, $value1 ); //値を保存
				}
			}
		}
		if ( ! empty( $_POST['rwcqrcm']['qrsettings'] ) ) { //題名が入力されている場合
			foreach ( $_POST['rwcqrcm']['qrsettings'] as $key1 => $value1 ) {
				update_user_meta( $user_id, 'rwcqrcm_qrsettings_' . $key1, $value1 ); //値を保存
			}
		}
	}

	function add_profile_columns( $columns ) {

		if ( ! isset( $this->option['role']['user']['profile'] ) ) {
			return $columns;
		}

		$columns['rwc_qrcm'] = __( 'QR Code', Plugin::TEXTDOMAIN );

		return $columns;
	}

	function custom_profile_column( $custom_column, $column_name, $user_id ) {

		if ( ! isset( $this->option['role']['user']['profile'] ) ) {
			return $custom_column;
		}

		if ( $column_name == 'rwc_qrcm' ) {
			$this->create_qrcode( $user_id, 'user' );
			$custom_column = $this->display_qrcode( $user_id, 'user', false );
		}

		return $custom_column;
	}

	// option page
	public static function insert_metabox_qrcode_settings_fields_options() {

		$data = self::get_qrsetting( 'options' );
		extract( $data );

		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/meta-box-qrcode-settings.php';
	}

	public static function get_qrsetting( $type, $id = '' ) {

		$qrsetting_fields = array(
			'individual',
			'margin',
			'correction_level',
			'label',
			'logo',
			'logo_width',
			'logo_height',
			'size_width',
			'color',
			'background_color',
			'background_image',
			'background_image_edit_topText',
			'background_image_edit_bottomText',
			'background_image_edit_fontFamiry',
			'background_image_edit_fontSize',
			'background_image_edit_fontColor',
			'background_image_edit_rad',
			'background_image_edit_topTextType',
			'background_image_edit_qrcodeSize',
		);

		$data = array();

		if ( $type == 'options' ) {
			// options
			$rwcqrcm            = get_option( 'rwcqrcm' );
			$rwcqrcm_qrsettings = $rwcqrcm['qrsettings'];

			foreach ( $qrsetting_fields as $qrsetting_field ) {
				if ( isset( $rwcqrcm_qrsettings[ $qrsetting_field ] ) ) {
					$data[ 'rwcqrcm_qrsettings_' . $qrsetting_field ] = $rwcqrcm_qrsettings[ $qrsetting_field ];
				}
			}
		} elseif ( $type == 'user' ) {
			// usesr meta
			foreach ( $qrsetting_fields as $qrsetting_field ) {
				$data[ 'rwcqrcm_qrsettings_' . $qrsetting_field ] = get_user_meta( $id, 'rwcqrcm_qrsettings_' . $qrsetting_field, true );
			}
		} else {
			// post meta
			foreach ( $qrsetting_fields as $qrsetting_field ) {
				$data[ 'rwcqrcm_qrsettings_' . $qrsetting_field ] = get_post_meta( $id, 'rwcqrcm_qrsettings_' . $qrsetting_field, true );
			}
		}

		return $data;
	}

	public static function get_qrdata( $type, $id = '' ) {

		$qrdata_fields = array(
			'individual',
			// type
			'type_type',
			// url
			'url_permalink',
			// contact
			'contact_type',
			'contact_n',
			'contact_nickname',
			'contact_sound',
			'contact_tel',
			'contact_tel_av',
			'contact_email',
			'contact_adr',
			'contact_bday',
			'contact_title',
			'contact_org',
			'contact_url',
			'contact_note',
			// event
			'event_dtstart',
			'event_dtend',
			'event_summary',
			// free
			'free_freeinput',
		);

		$data = array();

		if ( $type == 'user' ) {
			// usesr meta
			foreach ( $qrdata_fields as $qrdata_field ) {
				$data[ 'rwcqrcm_qrdata_' . $qrdata_field ] = get_user_meta( $id, 'rwcqrcm_qrdata_' . $qrdata_field, true );
			}
		} else {
			// post meta
			foreach ( $qrdata_fields as $qrdata_field ) {
				$data[ 'rwcqrcm_qrdata_' . $qrdata_field ] = get_post_meta( $id, 'rwcqrcm_qrdata_' . $qrdata_field, true );
			}
		}

		return $data;
	}

	function get_create_data( $type, $id ) {

		$qrdata_fields = array(
			'individual',
			// type
			'type_type',
			// url
			'url_permalink',
			// contact
			'contact_type',
			'contact_n',
			'contact_nickname',
			'contact_sound',
			'contact_tel',
			'contact_tel_av',
			'contact_email',
			'contact_adr',
			'contact_bday',
			'contact_title',
			'contact_org',
			'contact_url',
			'contact_note',
			// event
			'event_dtstart',
			'event_dtend',
			'event_summary',
			// free
			'free_freeinput',
		);

		$individual = false;

		$data = array();


		$individual = $this->get_qrcode_meta( $id, 'rwcqrcm_qrdata_individual', $type );
		if ( $individual == 'on' ) {
			$data = array();
			foreach ( $qrdata_fields as $qrsetting_field ) {
				$data[ 'rwcqrcm_qrdata_' . $qrsetting_field ] = $this->get_qrcode_meta( $id, 'rwcqrcm_qrdata_' . $qrsetting_field, $type );
			}
		}

		return $data;
	}

	function get_create_settings( $type, $id ) {

		$qrsetting_fields = array(
			'margin',
			'correction_level',
			'label',
			'logo',
			'logo_width',
			'logo_height',
			'size_width',
			'color',
			'background_color',
			'background_image',
		);
		$individual       = false;

		$data = array();

		// options
		$rwcqrcm            = get_option( 'rwcqrcm' );
		$rwcqrcm_qrsettings = $rwcqrcm['qrsettings'];

		foreach ( $qrsetting_fields as $qrsetting_field ) {
			if ( isset( $rwcqrcm_qrsettings[ $qrsetting_field ] ) ) {
				$data[ 'rwcqrcm_qrsettings_' . $qrsetting_field ] = $rwcqrcm_qrsettings[ $qrsetting_field ];
			}
		}

		$individual = $this->get_qrcode_meta( $id, 'rwcqrcm_qrsettings_individual', $type );
		if ( $individual == 'on' ) {
			$data = array();
			foreach ( $qrsetting_fields as $qrsetting_field ) {
				$data[ 'rwcqrcm_qrsettings_' . $qrsetting_field ] = $this->get_qrcode_meta( $id, 'rwcqrcm_qrsettings_' . $qrsetting_field, $type );
			}
		}

		return $data;
	}

	function get_qrcode_meta( $id, $field, $type ) {
		if ( $type == 'post' ) {
			return get_post_meta( $id, $field, true );
		} elseif ( $type == 'user' ) {
			return get_user_meta( $id, $field, true );
		}

		return false;
	}

	/**
	 * @param $atts
	 *
	 * @return string|void
	 */
	function qrcm_shortcode( $atts ) {

		/**
		 * @var $post_id
		 * @var $user_id
		 */
		extract( shortcode_atts( array(
			'post_id' => '',
			'user_id' => '',
		), $atts ) );

		if ( $post_id ) {
			return $this->get_qrcode_image( $post_id, 'post' );
		} elseif ( $user_id ) {
			return $this->get_qrcode_image( $user_id, 'user' );
		}

		if ( is_singular() ) {
			global $post;

			if ( ! isset( $this->option['role']['post_type'][ get_post_type() ] ) ) {
				return;
			}

			return $this->get_qrcode_image( $post->ID, 'post' );
		} elseif ( is_author() ) {
			return $this->get_qrcode_image( get_queried_object()->data->ID, 'user' );
		}
	}

	function qrcm_scanner_shortcode() {

		wp_enqueue_script( 'qrcm-scanner-redirect-script', plugins_url( "/assets/js/qr-scanner-redirect.js", Plugin::PLUGIN_BASEFILE ) );

		$id = 'qrcmscannerredirect';

		$settings_arr = array(
			'force'                  => false,
			'disableButton'          => false,
			'titleScanQRCode'        => __( 'QR Code Management', Plugin::TEXTDOMAIN ),
			'titleRedirect'          => __( 'Forwarding', Plugin::TEXTDOMAIN ),
			"contentRedirect"        => __( "Would you like to redirect to the url \"%URL\"", Plugin::TEXTDOMAIN ),
			"titleSelectDevice"      => __( "Select device", Plugin::TEXTDOMAIN ),
			"titleWaitPermission"    => __( "Wait for your permission", Plugin::TEXTDOMAIN ),
			"titlePermissonFailed"   => __( "Permission failed", Plugin::TEXTDOMAIN ),
			"contentPermissonFailed" => __( "Your browser has no permission for the camera. Please activate the permission.", Plugin::TEXTDOMAIN ),
		);

		$settings = json_encode( $settings_arr );

		return <<<__HTML__
    <div id="{$id}" />
    <script type="application/javascript" >
      window.{$id} = {
        settings: {$settings}
      }
    </script>
__HTML__;

	}
}
