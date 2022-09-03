<?php

namespace Hupa\Minify;

use JetBrains\PhpStorm\NoReturn;

defined( 'ABSPATH' ) or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
final class RegisterHupaMinifyPlugin {
	private static $hupa_min_instance;
	private bool $dependencies;

	/**
	 * @return static
	 */
	public static function hupa_min_instance(): self {
		if ( is_null( self::$hupa_min_instance ) ) {
			self::$hupa_min_instance = new self();
		}

		return self::$hupa_min_instance;
	}

	public function __construct() {
		$this->dependencies = $this->check_dependencies();
		add_action( 'admin_notices', array( $this, 'showSitemapInfo' ) );
	}

	function showSitemapInfo() {
		if ( get_transient( 'show_lizenz_info' ) ) {
			echo '<div class="error"><p>' .
			     'HUPA Minify ungültige Lizenz: Zum Aktivieren geben Sie Ihre Zugangsdaten ein.' .
			     '</p></div>';
		}
	}

	/**
	 * ============================================
	 * =========== REGISTER HUPA Minify ===========
	 * ============================================
	 */
	public function init_hupa_minify() {

		if ( ! $this->dependencies ) {
			return;
		}

		//Create | Update Database
		add_action( 'init', array( $this, 'hupa_minify_create_db' ) );

		//Load Textdomain
		add_action( 'init', array( $this, 'load_hupa_minify_textdomain' ) );
		//REGISTER Minify Menu
		add_action( 'admin_menu', array( $this, 'register_hupa_minify_menu' ) );
		//JOB WARNING ADD Plugin Settings Link
		add_filter( 'plugin_action_links_' . HUPA_MINIFY_SLUG_PATH, array( $this, 'minify_plugin_add_action_link' ) );

		// SITE GET Trigger
		add_action( 'template_redirect', array( $this, 'hupa_minify_callback_trigger_check' ) );

		/**=========== AJAX ADMIN AND PUBLIC RESPONSE HANDLE ===========*/
		add_action( 'wp_ajax_HupaMinifyHandle', array( $this, 'prefix_ajax_HupaMinifyHandle' ) );
		add_action( 'wp_ajax_nopriv_HupaMinifyNoAdmin', array( $this, 'prefix_ajax_HupaMinifyNoAdmin' ) );
		add_action( 'wp_ajax_HupaMinifyNoAdmin', array( $this, 'prefix_ajax_HupaMinifyNoAdmin' ) );
		/**=========== AJAX ADMIN FOLDER THREE HANDLE ===========*/
		add_action( 'wp_ajax_HupaMinifyFolder', array( $this, 'prefix_ajax_HupaMinifyFolder' ) );
		/**=========== AJAX ADMIN SERVER STATUS HANDLE ===========*/
		add_action( 'wp_ajax_HupaMinifyServer', array( $this, 'prefix_ajax_HupaMinifyServer' ) );
	}

	/**
	 * =======================================================
	 * =========== REGISTER HUPA Minify Textdomain ===========
	 * ========================================================
	 */
	public function load_hupa_minify_textdomain(): void {
		load_plugin_textdomain( 'hupa-minify', false, dirname( HUPA_MINIFY_SLUG_PATH ) . '/language/' );
	}

	/**
	 * ====================================================
	 * =========== REGISTER HUPA Dashboard Menu ===========
	 * ====================================================
	 */
	public function register_hupa_minify_menu(): void {
		add_menu_page(
			__( 'Minify', 'hupa-minify' ),
			__( 'Minify', 'hupa-minify' ),
			'manage_options',
			'hupa-minify',
			'',
			self::get_svg_icons('terminal'), 101
		);

		$hook_suffix = add_submenu_page(
			'hupa-minify',
			__( 'Minify - Settings', 'hupa-minify' ),
			__( 'Minify Settings ', 'hupa-minify' ),
			'manage_options',
			'hupa-minify',
			array( $this, 'admin_hupa_minify_page' )
		);

		add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );
		$hook_suffix = add_submenu_page(
			'hupa-minify',
			__( 'SCSS Compiler', 'hupa-minify' ),
			__( 'SCSS Compiler', 'hupa-minify' ),
			'manage_options',
			'hupa-minify-scss',
			array( $this, 'admin_hupa_minify_scss_page' ) );

		add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );

		/**
		 * ==========================================
		 * =========== SERVER STATUS MENU ===========
		 * ==========================================
		 */

		if(get_option('minify_show_status_menu')):
		add_menu_page(
			__( 'Server Stats', 'hupa-minify' ),
			__( 'Server Stats', 'hupa-minify' ),
			'manage_options',
			'minify_server_stats',
			'',
			self::get_svg_icons('graph'), 101
		);

		$hook_suffix = add_submenu_page(
			'minify_server_stats',
			__( 'Server Stats - General Settings', 'hupa-minify' ),
			__( 'Settings', 'hupa-minify' ),
			'manage_options',
			'minify_server_stats',
			array( $this, 'admin_hupa_minify_server_info_page' ) );

		add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );

		if(get_option('server_status_aktiv') ):

			if(get_option('php_menu_aktiv')) {
			$hook_suffix = add_submenu_page(
				'minify_server_stats',
				__( 'Server Stats - PHP Information', 'hupa-minify' ),
				__( 'PHP Information', 'hupa-minify' ),
				'manage_options',
				'minify-server-php',
				array( $this, 'admin_hupa_minify_server_php_details' ) );

			add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );
		}

			if(get_option('sql_menu_aktiv')) {
				$hook_suffix = add_submenu_page(
					'minify_server_stats',
					__( 'Server Stats - Database Information', 'hupa-minify' ),
					__( 'Database Information', 'hupa-minify' ),
					'manage_options',
					'minify-server-sql',
					array( $this, 'admin_hupa_minify_server_sql_details' ) );

				add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );
			}

		if ( class_exists( 'Memcache' ) && get_option('memcache_menu_aktiv')) {
			$hook_suffix = add_submenu_page(
				'minify_server_stats',
				__( 'Server Stats - Memcache Information', 'hupa-minify' ),
				__( 'Memcache Information', 'hupa-minify' ),
				'manage_options',
				'minify-server-memcache',
				array( $this, 'admin_hupa_minify_server_memcache_details' ) );

			add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );

		}
		endif;
		/** OPTIONS PAGE */
		if(get_option('server_status_aktiv') ) {
			$hook_suffix = add_options_page(
				__( 'Change the Server Stats Settings', 'hupa-minify' ),
				__( 'Server Stats', 'hupa-minify' ),
				'manage_options',
				'minify-server-options',
				array( $this, 'minify_server_options_page' )
			);

			add_action( 'load-' . $hook_suffix, array( $this, 'hupa_minify_load_ajax_admin_options_script' ) );
		}

		endif;
	}

	/**
	 * ============================================
	 * =========== PLUGIN SETTINGS LINK ===========
	 * ============================================
	 */
	public static function minify_plugin_add_action_link( $data ) {
		// check permission
		if ( ! current_user_can( 'manage_options' ) ) {
			return $data;
		}

		return array_merge(
			$data,
			array(
				sprintf(
					'<a href="%s">%s</a>',
					add_query_arg(
						array(
							'page' => 'hupa-minify'
						),
						admin_url( 'admin.php' )
					),
					__( "Settings", "hupa-minify" )
				)
			)
		);
	}

	/**
	 * ===================================
	 * =========== ADMIN PAGES ===========
	 * ===================================
	 */
	public function admin_hupa_minify_page(): void {
		require 'admin-pages/hupa-minify-start.php';
	}

	public function admin_hupa_minify_scss_page(): void {
		require 'admin-pages/hupa-minify-scss.php';
	}

	/**
	 * ===========================================
	 * =========== SERVER STATUS PAGES ===========
	 * ===========================================
	 */
	public function admin_hupa_minify_server_info_page() {
		require 'admin-pages/hupa-minify-server-info.php';
	}

	public function admin_hupa_minify_server_php_details() {
		require 'admin-pages/hupa-minify-server-php-info.php';
	}

	public function admin_hupa_minify_server_sql_details() {
		require 'admin-pages/hupa-minify-server-sql-info.php';
	}

	public function admin_hupa_minify_server_memcache_details() {
		require 'admin-pages/hupa-minify-server-memcache-info.php';
	}

	/** OPTIONS PAGE */
	public function minify_server_options_page() {
		require 'admin-pages/minify-server-options-page.php';
	}

	/**
	 * ===================================================
	 * =========== ADMIN PAGES Localize Script ===========
	 * ===================================================
	 */
	public function hupa_minify_load_ajax_admin_options_script(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_hupa_minify_admin_style' ) );
	}

	/**
	 * =========================================
	 * =========== AJAX ADMIN HANDLE ===========
	 * =========================================
	 */
	public function prefix_ajax_HupaMinifyHandle(): void {
		$responseJson = null;
		check_ajax_referer( 'hupa_minify_admin_handle' );
		require 'ajax/admin-hupa-minify-ajax.php';
		wp_send_json( $responseJson );
	}

	public function prefix_ajax_HupaMinifyFolder() {
		$responseJson = null;
		check_ajax_referer( 'hupa_minify_admin_handle' );
		require HUPA_MINIFY_PLUGIN_DIR . '/assets/folderTree/folderThreeAjax.php';
		wp_send_json( $responseJson );
	}

	public function prefix_ajax_HupaMinifyServer() {
		if ( is_admin() ) {
			$responseJson = null;
			check_ajax_referer( 'hupa_minify_admin_handle' );
			require 'ajax/admin-minify-server-status.php';
			wp_send_json( $responseJson );
		}
	}

	/**
	 * ==========================================
	 * =========== AJAX PUBLIC HANDLE ===========
	 * ==========================================
	 */
	public function prefix_ajax_HupaMinifyNoAdmin(): void {
		$responseJson = null;
		check_ajax_referer( 'hupa_minify_public_handle' );
		require 'ajax/public-hupa-minify-ajax.php';
		wp_send_json( $responseJson );
	}

	/**
	 * =======================================================
	 * =========== PLUGIN CREATE / UPDATE DATABASE ===========
	 * =======================================================
	 */
	public function hupa_minify_create_db(): void {
		//ADD Trigger
		global $wp;
		$wp->add_query_var( HUPA_MINIFY_QUERY_VAR );

		//SET DEFAULT SETTINGS
		require 'optionen/actions/hupa-minify-options.php';
		do_action( 'minify_plugin_set_defaults', 'check_update' );
	}

	/**
	 * ===============================================
	 * =========== PLUGIN SITE GET TRIGGER ===========
	 * ===============================================
	 */
	function hupa_minify_callback_trigger_check(): void {
		if ( get_query_var( HUPA_MINIFY_QUERY_VAR ) == HUPA_MINIFY_QUERY_VALUE ) {
			require HUPA_MINIFY_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'min/index.php';
			exit();
		}
	}

	/**
	 * ======================================
	 * =========== VERSIONS CHECK ===========
	 * ======================================
	 */
	public function check_dependencies(): bool {
		global $wp_version;
		if ( version_compare( PHP_VERSION, HUPA_MINIFY_MIN_PHP_VERSION, '<' ) || $wp_version < HUPA_MINIFY_MIN_WP_VERSION ) {
			$this->maybe_self_deactivate();

			return false;
		}

		return true;
	}

	/**
	 * =======================================
	 * =========== SELF-DEACTIVATE ===========
	 * =======================================
	 */
	public function maybe_self_deactivate(): void {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins( HUPA_MINIFY_SLUG_PATH );
		add_action( 'admin_notices', array( $this, 'self_deactivate_notice' ) );
	}

	/**
	 * ==============================================
	 * =========== DEACTIVATE-ADMIN-NOTIZ ===========
	 * ==============================================
	 */
	public function self_deactivate_notice() {
		echo sprintf( '<div class="error" style="margin-top:5rem"><p>' . __( 'This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'lva-buchungssystem' ) . '</p></div>', HUPA_MINIFY_MIN_PHP_VERSION, HUPA_MINIFY_MIN_WP_VERSION );
		exit();
	}

	/**
	 * ==========================================================
	 * =========== HUPA Minify ADMIN DASHBOARD STYLES ===========
	 * ==========================================================
	 */
	public function load_hupa_minify_admin_style(): void {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		//TODO FontAwesome / Bootstrap
		wp_enqueue_style( 'hupa-minify-admin-bs-style', HUPA_MINIFY_ASSETS_URL . 'css/bs/bootstrap.min.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );
		// TODO ADMIN ICONS
		wp_enqueue_style( 'hupa-minify-admin-icons-style', HUPA_MINIFY_ASSETS_URL . 'css/font-awesome.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );
		// TODO DASHBOARD STYLES
		wp_enqueue_style( 'hupa-minify-admin-dashboard-style', HUPA_MINIFY_ASSETS_URL . 'css/admin-dashboard-style.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );

		wp_enqueue_script( 'hupa-minify-bs', HUPA_MINIFY_ASSETS_URL . 'js/bs/bootstrap.bundle.min.js', array(), HUPA_MINIFY_PLUGIN_VERSION, true );
		wp_enqueue_script( 'hupa-minify-options', HUPA_MINIFY_ASSETS_URL . 'js/hupa-minify.js', array(), HUPA_MINIFY_PLUGIN_VERSION, true );
		if ( $page == 'hupa-minify-scss' ) {
			wp_enqueue_style( 'hupa-minify-scss-style', HUPA_MINIFY_ASSETS_URL . 'folderTree/filetree.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );
			wp_enqueue_script( 'hupa-minify-scss-three', HUPA_MINIFY_ASSETS_URL . 'folderTree/folderTree.js', array(), HUPA_MINIFY_PLUGIN_VERSION, true );
		}
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	private static function get_svg_icons($name): string
	{
		$icon = '';
		switch ($name) {
			case'terminal':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-terminal" viewBox="0 0 16 16">
                         <path d="M6 9a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3A.5.5 0 0 1 6 9zM3.854 4.146a.5.5 0 1 0-.708.708L4.793 6.5 3.146 8.146a.5.5 0 1 0 .708.708l2-2a.5.5 0 0 0 0-.708l-2-2z"/>
                         <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h12z"/>
                         </svg>';
				break;
			case'graph':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                         <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z"/>
                         </svg>';
				break;
			default:
		}
		return 'data:image/svg+xml;base64,' . base64_encode($icon);

	}

}//endClass

$register_hupa_minify = RegisterHupaMinifyPlugin::hupa_min_instance();
$register_hupa_minify->init_hupa_minify();



