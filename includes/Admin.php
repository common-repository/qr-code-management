<?php


namespace RWC\QRCM;

use RWC\QRCM\RWC_Qrcode;
use RWC\QRCM\Plugin;

class Admin {
	public function __construct() {
		add_action( 'init', array( $this, 'register_rwcqrcm' ) );

		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_scripts' ) );
	}

	function register_rwcqrcm() {

		$labels = [
			"name"          => __( 'QR code management', Plugin::TEXTDOMAIN ),
			"singular_name" => __( 'QR code management', Plugin::TEXTDOMAIN ),
			"all_items"     => __( 'QR code list', Plugin::TEXTDOMAIN ),
		];

		$args = [
			"label"               => __( 'QR code management', Plugin::TEXTDOMAIN ),
			"labels"              => $labels,
			"description"         => "",
			"public"              => false,
			"publicly_queryable"  => false,
			"show_ui"             => true,
			"delete_with_user"    => false,
			"show_in_rest"        => false,
			"rest_base"           => "",
//			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive"         => false,
			"show_in_menu"        => true,
			"show_in_nav_menus"   => true,
			"exclude_from_search" => false,
			"capability_type"     => "post",
			"map_meta_cap"        => true,
			"hierarchical"        => false,
//			"rewrite"               => [ "slug" => "blog", "with_front" => true ],
//			"query_var"             => true,
			"supports"            => [ "title" ],
			"menu_position"       => 100,
		];

		register_post_type( "rwcqrcm", $args );
	}


	function add_submenu_page() {
//		add_menu_page( __( 'QR code management', Plugin::TEXTDOMAIN ), __( 'QR code management', Plugin::TEXTDOMAIN ), 'manage_options', 'rwcqrcm-setting', array(
//			$this,
//			'page_setting'
//		) );
		add_submenu_page( 'edit.php?post_type=rwcqrcm', __( 'Setting', Plugin::TEXTDOMAIN ), __( 'Setting', Plugin::TEXTDOMAIN ), 'manage_options', 'rwcqrcm-setting', array(
			$this,
			'page_setting'
		) );

//		add_submenu_page( 'rwcqrcm-setting', __( 'Add to', Plugin::TEXTDOMAIN ), __( '追加', Plugin::TEXTDOMAIN ), 'manage_options', 'rwcqrcm-add', array(
//			$this,
//			'page_add'
//		) );
//
//		add_submenu_page( 'rwcqrcm-setting', __( 'List', Plugin::TEXTDOMAIN ), __( '一覧', Plugin::TEXTDOMAIN ), 'manage_options', 'rwcqrcm-list', array(
//			$this,
//			'page_list'
//		) );
//
//		add_submenu_page( 'edit.php?post_type=rwcqrcm', __( 'Report', Plugin::TEXTDOMAIN ), __( 'Report', Plugin::TEXTDOMAIN ), 'manage_options', 'rwcqrcm-report', array(
//			$this,
//			'page_report'
//		) );

		//call register settings function
		add_action( 'admin_init', array( $this, 'register_sdss_settings' ) );
	}

	function register_sdss_settings() {
		//register our settings
		register_setting( 'rwc-qrcm-settings-group', 'rwc_qrcm' );
		register_setting( 'rwc-qrcm-settings-group', 'rwcqrcm' );
	}

	function page_setting() {
		include plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/templates/admin-qrcm-setting.php';
	}

	function page_report() {

		?>
        <div class="wrap acf-settings-wrap">

            <h1><?php esc_html_e( 'Report', Plugin::TEXTDOMAIN ); ?></h1>

            <div id="poststuff">

                <div id="post-body" class="metabox-holder columns-2">

                    <div id="postbox-container-2" class="postbox-container">

                        <canvas id="myChart" width="400" height="400"></canvas>

                    </div>
                </div>

            </div>
        </div>
		<?php
	}

	function get_custum_post_types() {
		$args       = array(
			'public' => true,
		);
		$post_types = get_post_types( $args );

		// メディアを除外
		unset( $post_types['attachment'] );

		$post_types['rwcqrcm'] = 'rwcqrcm';

		return $post_types;
	}

	function get_roles() {

		$roles = wp_roles();

		// 購読者を除外
		unset( $roles->roles['subscriber'] );
		unset( $roles->role_objects['subscriber'] );
		unset( $roles->role_names['subscriber'] );

		return $roles->roles;
	}

	function my_admin_style() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'rwcqrcm-admin_style', plugins_url( Plugin::PLUGIN_DIR . '/' ) . 'assets/css/admin.css' );
	}


	function my_admin_scripts() {
		wp_enqueue_media();

		// カスタムメディアアップローダー用のJavaScript
		wp_enqueue_script(
			'my-media-uploader',

			//**javasctiptの指定
			//*プラグインにしたとき
			plugins_url( "/assets/js/uploader.js", Plugin::PLUGIN_BASEFILE ),
			//*function.phpに記入した場合
			//get_bloginfo( 'stylesheet_directory' ) . '/paka3-uploader.js',

			array( 'jquery' ),
			filemtime( dirname( Plugin::PLUGIN_BASEFILE ) . '/assets/js/uploader.js' ),
			true
		);

		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_script(
			'my-color-picker', plugins_url( "/assets/js/color-picker.js", Plugin::PLUGIN_BASEFILE ), array( 'wp-color-picker' ), filemtime( dirname( Plugin::PLUGIN_BASEFILE ) . '/assets/js/color-picker.js' ), true
		);
		wp_enqueue_script(
			'qrcm-meta-box', plugins_url( "/assets/js/meta-box.js", Plugin::PLUGIN_BASEFILE ), array( 'jquery' ), filemtime( dirname( Plugin::PLUGIN_BASEFILE ) . '/assets/js/meta-box.js' ), true
		);
		wp_enqueue_script(
			'qrcm-qrcodeeditor', plugins_url( "/assets/js/qrcodeeditor.js", Plugin::PLUGIN_BASEFILE ), array(), filemtime( dirname( Plugin::PLUGIN_BASEFILE ) . '/assets/js/qrcodeeditor.js' ), true
		);
		wp_enqueue_script(
			'qrcm-use_qrcodeeditor', plugins_url( "/assets/js/use_qrcodeeditor.js", Plugin::PLUGIN_BASEFILE ), array( 'jquery' ), filemtime( dirname( Plugin::PLUGIN_BASEFILE ) . '/assets/js/use_qrcodeeditor.js' ), true
		);
	}
}