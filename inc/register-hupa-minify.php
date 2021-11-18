<?php

namespace Hupa\Minify;

use JetBrains\PhpStorm\NoReturn;

defined('ABSPATH') or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
final class RegisterHupaMinifyPlugin
{
	private static $hupa_min_instance;
	private bool $dependencies;

	/**
	 * @return static
	 */
	public static function hupa_min_instance(): self
	{
		if (is_null(self::$hupa_min_instance)) {
			self::$hupa_min_instance = new self();
		}
		return self::$hupa_min_instance;
	}

	public function __construct()
	{
		$this->dependencies = $this->check_dependencies();
		add_action('admin_notices', array($this, 'showSitemapInfo'));
	}

	function showSitemapInfo()
	{
		if (get_transient('show_lizenz_info')) {
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
		add_action('init', array($this, 'hupa_minify_create_db'));
		//Load Textdomain
		add_action('init', array($this, 'load_hupa_minify_textdomain'));
		//REGISTER Minify Menu
		add_action('admin_menu', array($this, 'register_hupa_minify_menu'));
		// SITE GET Trigger
		add_action('template_redirect', array($this, 'hupa_minify_callback_trigger_check'));

		/**=========== AJAX ADMIN AND PUBLIC RESPONSE HANDLE ===========*/
		add_action('wp_ajax_HupaMinifyHandle', array($this, 'prefix_ajax_HupaMinifyHandle'));
		add_action('wp_ajax_nopriv_HupaMinifyNoAdmin', array($this, 'prefix_ajax_HupaMinifyNoAdmin'));
		add_action('wp_ajax_HupaMinifyNoAdmin', array($this, 'prefix_ajax_HupaMinifyNoAdmin'));
	}

	/**
	 * =======================================================
	 * =========== REGISTER HUPA Minify Textdomain ===========
	 * ========================================================
	 */
	public function load_hupa_minify_textdomain(): void
	{
		load_plugin_textdomain('hupa-minify', false, dirname(HUPA_MINIFY_SLUG_PATH) . '/language/');
	}

	/**
	 * ====================================================
	 * =========== REGISTER HUPA Dashboard Menu ===========
	 * ====================================================
	 */
	public function register_hupa_minify_menu(): void
	{
		$hook_suffix = add_menu_page(
			__('Minify', 'hupa-minify'),
			__('Minify', 'hupa-minify'),
			'manage_options',
			'hupa-minify',
			array($this, 'admin_hupa_minify_page'),
			'dashicons-editor-contract', 8
		);

		add_action('load-' . $hook_suffix, array($this, 'hupa_minify_load_ajax_admin_options_script'));
	}

	/**
	 * ===================================
	 * =========== ADMIN PAGES ===========
	 * ===================================
	 */
	public function admin_hupa_minify_page(): void
	{
		require 'admin-pages/hupa-minify-start.php';
	}

	public function hupa_minify_load_ajax_admin_options_script(): void
	{
		add_action('admin_enqueue_scripts', array($this, 'load_hupa_minify_admin_style'));
		$title_nonce = wp_create_nonce('hupa_minify_admin_handle');

		wp_register_script('hupa-minify-ajax', '', [], '', true);
		wp_enqueue_script('hupa-minify-ajax');
		wp_localize_script('hupa-minify-ajax', 'minify_ajax_obj', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => $title_nonce,
		));
	}

	/**
	 * =========================================
	 * =========== AJAX ADMIN HANDLE ===========
	 * =========================================
	 */
	public function prefix_ajax_HupaMinifyHandle(): void
	{
		$responseJson = null;
		check_ajax_referer('hupa_minify_admin_handle');
		require 'ajax/admin-hupa-minify-ajax.php';
		wp_send_json($responseJson);
	}

	/**
	 * ==========================================
	 * =========== AJAX PUBLIC HANDLE ===========
	 * ==========================================
	 */
	public function prefix_ajax_HupaMinifyNoAdmin(): void
	{
		$responseJson = null;
		check_ajax_referer('hupa_minify_public_handle');
		require 'ajax/public-hupa-minify-ajax.php';
		wp_send_json($responseJson);
	}

	/**
	 * =======================================================
	 * =========== PLUGIN CREATE / UPDATE DATABASE ===========
	 * =======================================================
	 */
	public function hupa_minify_create_db(): void
	{
		//ADD Trigger
		global $wp;
		$wp->add_query_var(HUPA_MINIFY_QUERY_VAR);
		// Check DB
		require 'optionen/filter/database/hupa-minify-database.php';
		do_action('minify_plugin_update_dbCheck');
	}

	/**
	 * ===============================================
	 * =========== PLUGIN SITE GET TRIGGER ===========
	 * ===============================================
	 */
	function hupa_minify_callback_trigger_check(): void
	{
		if (get_query_var(HUPA_MINIFY_QUERY_VAR) == HUPA_MINIFY_QUERY_VALUE) {
			require HUPA_MINIFY_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'min/index.php';
			exit();
		}
		if (get_query_var(HUPA_MINIFY_QUERY_VAR) == 'info') {
			require HUPA_MINIFY_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'min/server-info.php';
			exit();
		}
		if (get_query_var(HUPA_MINIFY_QUERY_VAR) == 'server') {
			require 'admin-pages/server-info.php';
			exit();
		}
	}

	/**
	 * ======================================
	 * =========== VERSIONS CHECK ===========
	 * ======================================
	 */
	public function check_dependencies(): bool
	{
		global $wp_version;
		if (version_compare(PHP_VERSION, HUPA_MINIFY_MIN_PHP_VERSION, '<') || $wp_version < HUPA_MINIFY_MIN_WP_VERSION) {
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
	public function maybe_self_deactivate(): void
	{
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins(HUPA_MINIFY_SLUG_PATH);
		add_action('admin_notices', array($this, 'self_deactivate_notice'));
	}

	/**
	 * ==============================================
	 * =========== DEACTIVATE-ADMIN-NOTIZ ===========
	 * ==============================================
	 */
	#[NoReturn] public function self_deactivate_notice()
	{
		echo sprintf('<div class="error" style="margin-top:5rem"><p>' . __('This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'lva-buchungssystem') . '</p></div>', HUPA_MINIFY_MIN_PHP_VERSION, HUPA_MINIFY_MIN_WP_VERSION);
		exit();
	}

	/**
	 * ==========================================================
	 * =========== HUPA Minify ADMIN DASHBOARD STYLES ===========
	 * ==========================================================
	 */
	public function load_hupa_minify_admin_style(): void
	{
		//TODO FontAwesome / Bootstrap
		wp_enqueue_style('hupa-minify-admin-bs-style', HUPA_MINIFY_ASSETS_URL . 'css/bs/bootstrap.min.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false);
		// TODO ADMIN ICONS
		wp_enqueue_style('hupa-minify-admin-icons-style', HUPA_MINIFY_ASSETS_URL . 'css/font-awesome.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false);
		// TODO DASHBOARD STYLES
		wp_enqueue_style('hupa-minify-admin-dashboard-style', HUPA_MINIFY_ASSETS_URL . 'css/admin-dashboard-style.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false);

		wp_enqueue_script('hupa-minify-bs', HUPA_MINIFY_ASSETS_URL . 'js/bs/bootstrap.bundle.min.js', array(),HUPA_MINIFY_PLUGIN_VERSION, true);
		wp_enqueue_script('jquery');
		wp_enqueue_script('hupa-minify-options', HUPA_MINIFY_ASSETS_URL . 'js/hupa-minify.js', array(),HUPA_MINIFY_PLUGIN_VERSION, true);
	}

}//endClass

global $register_hupa_minify;
$register_hupa_minify = RegisterHupaMinifyPlugin::hupa_min_instance();
$register_hupa_minify->init_hupa_minify();



