<?php
/**
 * Plugin Name: QR Code Management
 * Description: A plug-in that issues a QR code for public pages of posts, fixed pages, and custom post types. The QR code format is PNG, SVG, EPS, JPEG.
 * Version: 1.5.1
 * Author: Redwoodcity Inc.
 * Author URI: https://www.redwoodcity.jp/
 * Plugin URI: https://www.redwoodcity.jp/app/qrcm/
 * Text Domain: rwc-qrcm
 * Domain Path: /languages
 * License: GPLv2 or later
 */


if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('RWCQRCM_VERSION', '1.5.1');
define('RWCQRCM_PLUGIN_MAIN_FILE', __FILE__);

require_once(plugin_dir_path(RWCQRCM_PLUGIN_MAIN_FILE) . 'includes/autoloader.php');
