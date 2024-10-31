<?php

namespace RWC\QRCM;

class Plugin {
//    const PLUGIN_NAME = 'Prevent leaving page';
	const PLUGIN_DIR = 'qr-code-management';
	const TEXTDOMAIN = 'rwc-qrcm';
	const LANGUAGE_DIR = 'languages';
	const PLUGIN_BASEFILE = WP_PLUGIN_DIR . '/' . self::PLUGIN_DIR . '/' . self::TEXTDOMAIN . '.php';
	private $db_tables = null;
	private $admin = null;
	private $qrcode = null;

	/**
	 * Main instance of the plugin.
	 *
	 * @since 1.0.0
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Sets the plugin main file.
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 *
	 * @since 1.0.0
	 *
	 */
	public function __construct( $main_file ) {
	}

	/**
	 * Registers the plugin with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		load_plugin_textdomain( self::TEXTDOMAIN, false, dirname( plugin_basename( self::PLUGIN_BASEFILE ) ) . '/' . self::LANGUAGE_DIR );
		$this->admin = new Admin();

		$this->qrcode = new RWC_Qrcode();
		$this->db_tables = new DB_Tables();
	}

	/**
	 * Retrieves the main instance of the plugin.
	 *
	 * @return Plugin Plugin main instance.
	 * @since 1.0.0
	 *
	 */
	public static function instance() {
		return static::$instance;
	}

	/**
	 * Loads the plugin main instance and initializes it.
	 *
	 * @param string $main_file Absolute path to the plugin main file.
	 *
	 * @return bool True if the plugin main instance could be loaded, false otherwise.
	 * @since 1.0.0
	 *
	 */
	public static function load( $main_file ) {
		if ( null !== static::$instance ) {
			return false;
		}

		static::$instance = new static( $main_file );
		static::$instance->register();

		return true;
	}
}

