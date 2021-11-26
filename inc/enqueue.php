<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker Plugin
 * @package Jens Wiecker Hupa Minify
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

if ( ! function_exists( 'hupa_minify_public_style' ) ) {
    function hupa_minify_public_style() {

	    /**
	     * ==========================================
	     * =========== AJAX PUBLIC HANDLE ===========
	     * ==========================================
	     */
	    $title_nonce = wp_create_nonce('hupa_minify_public_handle');
	    wp_register_script('hupa-minify-public-ajax', '', [], '', true);
	    wp_enqueue_script('hupa-minify-public-ajax');
	    wp_localize_script('hupa-minify-public-ajax', 'public_minify_ajax_obj', array(
		    'ajax_url' => admin_url('admin-ajax.php'),
		    'nonce' => $title_nonce,
	    ));
    }
}
add_action( 'wp_enqueue_scripts', 'hupa_minify_public_style' );

if ( ! function_exists( 'hupa_minify_admin_style' ) ) {

    function hupa_minify_admin_style() {
        // TODO DASHBOARD WP STYLES
        wp_enqueue_style( 'hupa-minify-form-admin-custom-tools', HUPA_MINIFY_ASSETS_URL . '/css/tools.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );
        wp_enqueue_style( 'hupa-minify-fonts', HUPA_MINIFY_ASSETS_URL . '/css/Glyphter.css', array(), HUPA_MINIFY_PLUGIN_VERSION, false );

	    wp_enqueue_style( 'minify-flipclock', HUPA_MINIFY_ASSETS_URL . 'css/server-stats/flipclock.min.css', array(), '0.7.3' );
	    wp_enqueue_style( 'minify-server-status', HUPA_MINIFY_ASSETS_URL . 'css/server-stats/wp-server-stats-admin.css', array(), HUPA_MINIFY_PLUGIN_VERSION );
	    wp_enqueue_style( 'wp-color-picker' );

	    wp_register_script( 'minify-server-status-script', HUPA_MINIFY_ASSETS_URL . 'js/server-stats/minify-main-status.js', array(
		    'jquery',
		    'wp-color-picker'
	    ), HUPA_MINIFY_PLUGIN_VERSION, true );
	    wp_enqueue_script( 'minify-server-status-script' );

	    wp_register_script( 'minify-script-flipclock', HUPA_MINIFY_ASSETS_URL . 'js/server-stats/flipclock.min.js', array( 'jquery' ), '0.7.3', true );
	    wp_enqueue_script( 'minify-script-flipclock' );

	    $title_nonce = wp_create_nonce( 'hupa_minify_admin_handle' );

	    wp_register_script( 'hupa-minify-ajax', '', [], '', true );
	    wp_enqueue_script( 'hupa-minify-ajax' );
	    wp_localize_script( 'hupa-minify-ajax', 'minify_ajax_obj', array(
		    'ajax_url' => admin_url( 'admin-ajax.php' ),
		    'nonce'    => $title_nonce,
	    ) );
    }
}
add_action( 'admin_enqueue_scripts', 'hupa_minify_admin_style' );
