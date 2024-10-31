<?php

namespace RWC\QRCM;

class DB_Tables {

	private $rwc_qrcm_db_version = '1.3.0.2003262207';

	/**
	 * HDCP_DB_SETTING constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, ( 'rwc_qrcm_update_db_check' ) ) );
	}


	function rwc_qrcm_update_db_check() {
		if ( get_site_option( 'rwc_qrcm_db_version' ) != $this->rwc_qrcm_db_version ) {
//			$this->create_table_rwc_qrcm_log();
			$this->update_post_meta_rwcqrcm_permalink();
//			$this->update_option_rwcqrcm_setting();

			update_option( 'rwc_qrcm_db_version', $this->rwc_qrcm_db_version );
		}
	}

	/**
	 * v1.2.0 v1.3.0 convert
	 */
	function update_post_meta_rwcqrcm_permalink() {
		$args        = array(
			'post_type'      => 'rwcqrcm',
			'posts_per_page' => - 1,
		);
		$posts_array = get_posts( $args );
		foreach ( $posts_array as $post ) {
			$rwcqrcm_permalink = get_post_meta( $post->ID, 'rwcqrcm_permalink', true );
			if ( $rwcqrcm_permalink ) {
				update_post_meta( $post->ID, 'rwcqrcm_qrdata_url_permalink', $rwcqrcm_permalink );
				delete_post_meta( $post->ID, 'rwcqrcm_permalink' );
			}
		}
	}

	/**
	 * v1.3.0 v1.4.0 convert
	 */
	function update_option_rwcqrcm_setting() {
		$rwcqrcm = get_option( 'rwcqrcm' );
		$rwc_qrcm = get_option( 'rwc_qrcm' );


	}

	/**
	 * スタンプラリーログテーブル作成
	 */
	function create_table_rwc_qrcm_log() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'rwc_qrcm_log';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
	`log_id` bigint(20) UNSIGNED unique AUTO_INCREMENT,
	`post_id` bigint(20) UNSIGNED NOT NULL,
	`user_id` text NOT NULL,
	`create_date` datetime DEFAULT NULL
  ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
