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

delete_option('minify_db_version');

delete_option( 'minify_sub_folder');
delete_option( 'minify_css_aktiv');
delete_option( 'minify_js_aktiv');
delete_option( 'minify_html_aktiv');
delete_option( 'minify_jquery_core_aktiv');
delete_option('minify_wp_embed_aktiv');
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

//SCSS
delete_option( 'minify_scss_source');
delete_option( 'minify_scss_destination');
delete_option( 'minify_scss_formatter');
delete_option( 'scss_stylesheet_aktiv');
delete_option( 'scss_map_aktiv');
delete_option('line_comments_aktiv');
delete_option('minify_scss_compiler_aktiv');
delete_option('minify_scss_map_option');

// SERVER STATUS
delete_option('minify_aktiv');
delete_option('server_status_aktiv');
delete_option('server_footer_aktiv');
delete_option('server_dashboard_aktiv');
delete_option('settings_server_status');
delete_option('php_menu_aktiv');
delete_option('sql_menu_aktiv');
delete_option('memcache_menu_aktiv');
delete_option('minify_server_linux');
delete_option('echtzeit_statistik_aktiv');
delete_option('ip_api_aktiv');
delete_option('server_shell_exec');


// Let's delete the Options
delete_option( 'wpss_settings_options' );
delete_option( 'wpss_db_advanced_info' );
delete_option( 'wpss_donate_notice' );

// Delete option for multisite
delete_site_option( 'wpss_settings_options' );
delete_site_option( 'wpss_db_advanced_info' );
delete_site_option( 'wpss_donate_notice' );


// Delete transients
delete_transient( 'wpss_server_location' );
delete_transient( 'wpss_cpu_count' );
delete_transient( 'wpss_cpu_core_count' );
delete_transient( 'wpss_server_os' );
delete_transient( 'wpss_db_software' );
delete_transient( 'wpss_db_version' );
delete_transient( 'wpss_db_max_connection' );
delete_transient( 'wpss_db_max_packet_size' );
delete_transient( 'wpss_db_disk_usage' );
delete_transient( 'wpss_db_index_disk_usage' );
delete_transient( 'wpss_php_max_upload_size' );
delete_transient( 'wpss_php_max_post_size' );
delete_transient( 'wpss_total_ram' );

// Delete option for multisite
delete_site_transient( 'wpss_server_location' );
delete_site_transient( 'wpss_cpu_count' );
delete_site_transient( 'wpss_cpu_core_count' );
delete_site_transient( 'wpss_server_os' );
delete_site_transient( 'wpss_db_software' );
delete_site_transient( 'wpss_db_version' );
delete_site_transient( 'wpss_db_max_connection' );
delete_site_transient( 'wpss_db_max_packet_size' );
delete_site_transient( 'wpss_db_disk_usage' );
delete_site_transient( 'wpss_db_index_disk_usage' );
delete_site_transient( 'wpss_php_max_upload_size' );
delete_site_transient( 'wpss_php_max_post_size' );


