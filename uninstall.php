<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option("hupa_minify_product_install_authorize");
delete_option("hupa_minify_client_id");
delete_option("hupa_minify_client_secret");
delete_option("hupa_minify_message");
delete_option("hupa_minify_access_token");

delete_option( 'minify_aktiv');
delete_option( 'minify_sub_folder');
delete_option( 'minify_css_aktiv');
delete_option( 'minify_js_aktiv');
delete_option( 'minify_html_aktiv');
delete_option( 'minify_jquery_core_aktiv');
delete_option( 'minify_jquery_core_footer');
delete_option( 'minify_wp_embed_aktiv');
delete_option( 'minify_groups_aktiv');
delete_option( 'minify_cache_type');
delete_option( 'minify_settings_select');
delete_option( 'minify_css_bubble_import');
delete_option( 'minify_settings_entwicklung');
delete_option( 'minify_settings_production');
delete_option( 'minify_wp_version');
delete_option( 'minify_wp_block_css');
delete_option( 'minify_wp_emoji');

delete_option('minify_style_css');
delete_option('minify_script_js');

