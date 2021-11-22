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

delete_option( 'minify_sub_folder');
delete_option( 'minify_css_aktiv');
delete_option( 'minify_js_aktiv');
delete_option( 'minify_html_aktiv');
delete_option( 'minify_jquery_core_aktiv');
delete_option( 'minify_jquery_core_footer');

delete_option( 'minify_html_inline_css');
delete_option( 'minify_html_inline_js');
delete_option( 'minify_html_comments');

delete_option( 'minify_css_groups_aktiv');
delete_option( 'minify_js_groups_aktiv');
delete_option( 'minify_cache_type');
delete_option( 'minify_settings_select');
delete_option( 'minify_css_bubble_import');
delete_option( 'minify_settings_entwicklung');
delete_option( 'minify_settings_production');

delete_option( 'minify_static_aktiv');
delete_option( 'minify_memcache_host');
delete_option( 'minify_memcache_port');

delete_option('minify_style_css');
delete_option('minify_script_js');

delete_option( 'minify_rsd_aktiv');
delete_option( 'minify_rss_link');
delete_option( 'minify_rss_extra');
delete_option( 'minify_live_writer');
delete_option('minify_posts_rel');
delete_option('minify_shortlink_aktiv');

delete_option( 'minify_wp_version');
delete_option( 'minify_wp_block_css');
delete_option( 'minify_wp_emoji');

