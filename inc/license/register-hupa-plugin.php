<?php

namespace Hupa\FormLicense;

defined('ABSPATH') or die();

/**
 * REGISTER HUPA MINIFY
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class RegisterHupaMinify
{
    private static $hupa_minify_instance;
    private string $plugin_dir;

    /**
     * @return static
     */
    public static function hupa_minify_instance(): self
    {
        if (is_null(self::$hupa_minify_instance)) {
            self::$hupa_minify_instance = new self();
        }
        return self::$hupa_minify_instance;
    }

    public function __construct(){
        $file_path_from_plugin_root = str_replace(WP_PLUGIN_DIR . '/', '', __DIR__);
        $path_array = explode('/', $file_path_from_plugin_root);
        $plugin_folder_name = reset($path_array);
        $this->plugin_dir = $plugin_folder_name;
    }

    public function init_hupa_minify(): void
    {

        // TODO REGISTER LICENSE MENU
        if(!get_option('hupa_minify_product_install_authorize')) {
            add_action('admin_menu', array($this, 'register_license_hupa_minify_plugin'));
        }
        add_action('wp_ajax_HupaMinifyLicenceHandle', array($this, 'prefix_ajax_HupaMinifyLicenceHandle'));
        add_action( 'init', array( $this, 'hupa_minify_license_site_trigger_check' ) );
        add_action( 'template_redirect',array($this, 'hupa_minify_license_callback_trigger_check' ));
    }

    /**
     * ==================================================
     * =========== REGISTER PLUGIN ADMIN MENU ===========
     * ==================================================
     */

    public function register_license_hupa_minify_plugin(): void
    {
        $hook_suffix = add_menu_page(
            __('Minify', 'hupa-minify'),
            __('Minify', 'hupa-minify'),
            'manage_options',
            'hupa-minify-license',
            array($this, 'hupa_minify_license'),
            'dashicons-lock', 2
        );
        add_action('load-' . $hook_suffix, array($this, 'hupa_minify_load_ajax_admin_options_script'));
    }


    public function hupa_minify_license(): void
    {
        require 'activate-hupa-minify-page.php';
    }

    /**
     * =========================================
     * =========== ADMIN AJAX HANDLE ===========
     * =========================================
     */

    public function hupa_minify_load_ajax_admin_options_script(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'load_hupa_minify_admin_style'));
        $title_nonce = wp_create_nonce('hupa_minify_license_handle');
        wp_register_script('hupa-minify-ajax-script', '', [], '', true);
        wp_enqueue_script('hupa-minify-ajax-script');
        wp_localize_script('hupa-minify-ajax-script', 'hupa_minify_license_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce
        ));
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_HupaMinifyLicenceHandle(): void {
        $responseJson = null;
        check_ajax_referer( 'hupa_minify_license_handle' );
        require 'hupa-minify-license-ajax.php';
        wp_send_json( $responseJson );
    }

    /*===============================================
       TODO GENERATE CUSTOM SITES
    =================================================
    */
    public function hupa_minify_license_site_trigger_check(): void {
        global $wp;
        $wp->add_query_var( $this->plugin_dir );
    }

    function hupa_minify_license_callback_trigger_check(): void {
        $file_path_from_plugin_root = str_replace(WP_PLUGIN_DIR . '/', '', __DIR__);
        $path_array = explode('/', $file_path_from_plugin_root);
        $plugin_folder_name = reset($path_array);
        //$requestUri = base64_encode($plugin_folder_name);
       if ( get_query_var( $this->plugin_dir ) === $this->plugin_dir) {
            require 'api-request-page.php';
            exit;
        }
    }

    /**
     * ====================================================
     * =========== THEME ADMIN DASHBOARD STYLES ===========
     * ====================================================
     */

    public function load_hupa_minify_admin_style(): void
    {
        wp_enqueue_style('hupa-minify-license-style',plugins_url('hupa-minify') . '/inc/license/assets/license-backend.css', array(), '');
        wp_enqueue_script('js-hupa-minify-license', plugins_url('hupa-minify') . '/inc/license/license-script.js', array(), '', true );
    }
}

$register_hupa_minify = RegisterHupaMinify::hupa_minify_instance();
if (!empty($register_hupa_minify)) {
	$register_hupa_minify->init_hupa_minify();
}
