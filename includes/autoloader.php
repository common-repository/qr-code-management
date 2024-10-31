<?php

use RWC\QRCM\Plugin;

$baseDir = dirname( __FILE__ );

$class_map = array(
	'RWC\QRCM\Plugin' => $baseDir . '/Plugin.php',
	'RWC\QRCM\DB_Tables'  => $baseDir . '/DB_Tables.php',
	'RWC\QRCM\Admin'  => $baseDir . '/Admin.php',
	'RWC\QRCM\RWC_Qrcode'  => $baseDir . '/Qrcode.php',
);

spl_autoload_register(
	function ( $class ) use ( $class_map ) {
		if ( isset( $class_map[ $class ] ) ) {
			require_once $class_map[ $class ];

			return true;
		}
	},
	true,
	true
);

require_once( plugin_dir_path( RWCQRCM_PLUGIN_MAIN_FILE ) . '/vendor/autoload.php' );

Plugin::load( RWCQRCM_PLUGIN_MAIN_FILE );

