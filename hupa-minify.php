<?php

/**
 * WP Hupa Minify
 *
 *
 * @link              https://www.hummelt-werbeagentur.de/
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Hupa Minify
 * Plugin URI:        https://www.hummelt-werbeagentur.de/leistungen/
 * Description:       Minify ist ein HTTP-Server für JS- und CSS-Assets. Es komprimiert und kombiniert Dateien und stellt sie mit entsprechenden Headern bereit, die bedingtes GET oder langes Expires ermöglichen.
 * Version:           1.0.3
 * Author:            Jens Wiecker
 * License:           MIT License
 * Requires PHP:      7.4
 * Requires at least: 5.6
 * Tested up to:      5.9.1
 * Stable tag:        1.0.3
 */

defined( 'ABSPATH' ) or die();

//HUPA MINIFY CONSTANTS
const HUPA_MINIFY_MIN_DB_VERSION  = '1.0.1';
const HUPA_MINIFY_MIN_PHP_VERSION = '7.4';
const HUPA_MINIFY_MIN_WP_VERSION  = '5.6';
const HUPA_MINIFY_QUERY_VAR       = 'minify';
const HUPA_MINIFY_QUERY_VALUE     = 'min';
const MINIFY_SCSS_COMPILER_AKTIV  = true;

//PLUGIN VERSION
$plugin_data = get_file_data( dirname( __FILE__ ) . '/hupa-minify.php', array( 'Version' => 'Version' ), false );
define( "HUPA_MINIFY_PLUGIN_VERSION", $plugin_data['Version'] );

//PLUGIN ROOT PATH
define( 'HUPA_MINIFY_PLUGIN_DIR', dirname( __FILE__ ) );

//PLUGIN SLUG
define( 'HUPA_MINIFY_SLUG_PATH', plugin_basename( __FILE__ ) );
define( 'HUPA_MINIFY_BASENAME', plugin_basename( __DIR__ ) );

//PLUGIN URL
define( 'HUPA_MINIFY_PLUGIN_URL', plugins_url( 'hupa-minify' ) );

//PLUGIN INC DIR
const HUPA_MINIFY_INC = HUPA_MINIFY_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;

// if HUPA Starter Theme
$theme_data = wp_get_theme( 'hupa-starter' );
$hupaTheme  = $theme_data->exists();
define( 'HUPA_STARTER_THEME_AKTIV', $hupaTheme );

//PLUGIN ASSETS URL
define( 'HUPA_MINIFY_ASSETS_URL', plugins_url( 'hupa-minify' ) . '/assets/' );

//THEME ROOT
define( "HUPA_MINIFY_THEME_ROOT", wp_get_theme()->get_theme_root() );

//PLUGIN ABSOLUT PATH
require_once( ABSPATH . 'wp-admin/includes/file.php' );
$root_path = get_home_path();
$path      = rtrim( $root_path, DIRECTORY_SEPARATOR );
define( "HUPA_MINIFY_ROOT_PATH", $path );

//Cache Path
$cacheFolder = $root_path . 'minify-cache';
define( "HUPA_MINIFY_CACHE_PATH", sys_get_temp_dir() );


/**
 * REGISTER PLUGIN
 */

require 'inc/license/license-init.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hupa-minify-activator.php
 */
function activate_hupa_minify() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hupa-minify-activator.php';
	Hupa_Minify_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hupa-minify-deactivator.php
 */
function deactivate_hupa_minify() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hupa-minify-deactivator.php';
	Hupa_Minify_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hupa_minify' );
register_deactivation_hook( __FILE__, 'deactivate_hupa_minify' );

if ( get_option( 'hupa_minify_product_install_authorize' ) ) {
	delete_transient( 'show_minify_lizenz_info' );
	require 'inc/register-hupa-minify.php';
	require 'inc/optionen/optionen-init.php';
	require 'inc/enqueue.php';
	require 'inc/scss-server/scss-server.php';
	require 'min/vendor/autoload.php';
	require 'server-status/class-server-status.php';
	require 'inc/update-checker/autoload.php';

	if ( get_option( 'hupa_minify_server_api' )['update_aktiv'] == '1' ) {
		$hupaMinifyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
			get_option( 'hupa_minify_server_api' )['update_url'],
			__FILE__,
			HUPA_MINIFY_BASENAME
		);
		if ( get_option( 'hupa_minify_server_api' )['update_type'] == '1' ) {
			$hupaMinifyUpdateChecker->getVcsApi()->enableReleaseAssets();
		}
	}
}

function showWPMinifySitemapInfo() {
	if ( get_transient( 'show_minify_lizenz_info' ) ) {
		echo '<div class="error"><p>' .
		     'Hupa Minify ungültige Lizenz: Zum Aktivieren geben Sie Ihre Zugangsdaten ein.' .
		     '</p></div>';
	}
}

add_action( 'admin_notices', 'showWPMinifySitemapInfo' );