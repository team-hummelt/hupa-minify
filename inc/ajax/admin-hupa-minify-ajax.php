<?php
defined( 'ABSPATH' ) or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

$responseJson = new stdClass();
$record       = new stdClass();
$data         = '';

if ( isset( $_POST['data'] ) ) {
	$data = apply_filters( 'array_to_object', $_POST['data'] );
}
$method = filter_var( $data->method, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH );
if ( ! $method ) {
	$method = filter_input( INPUT_POST, 'method', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH );
}

switch ( $method ) {
	case 'update_minify_settings':
		$responseJson->spinner = true;

		isset( $data->css_aktiv ) && is_string( $data->css_aktiv ) ? $css_aktiv = 1 : $css_aktiv = 0;
		isset( $data->js_aktiv ) && is_string( $data->js_aktiv ) ? $js_aktiv = 1 : $js_aktiv = 0;
		isset( $data->html_aktiv ) && is_string( $data->html_aktiv ) ? $html_aktiv = 1 : $html_aktiv = 0;

		//CSS OPTION
		isset( $data->css_groups_aktiv ) && is_string( $data->css_groups_aktiv ) ? $css_groups_aktiv = 1 : $css_groups_aktiv = 0;
		isset( $data->css_import_aktiv ) && is_string( $data->css_import_aktiv ) ? $css_import_aktiv = 1 : $css_import_aktiv = 0;
		//JS OPTION
		isset( $data->js_groups_aktiv ) && is_string( $data->js_groups_aktiv ) ? $js_groups_aktiv = 1 : $js_groups_aktiv = 0;
		isset( $data->wp_core_aktiv ) && is_string( $data->wp_core_aktiv ) ? $wp_core_aktiv = 1 : $wp_core_aktiv = 0;
		isset( $data->wp_embed_aktiv ) && is_string( $data->wp_embed_aktiv ) ? $wp_embed_aktiv = 1 : $wp_embed_aktiv = 0;
		//HTML OPTION
		isset( $data->html_inline_css ) && is_string( $data->html_inline_css ) ? $html_inline_css = 1 : $html_inline_css = 0;
		isset( $data->html_inline_js ) && is_string( $data->html_inline_js ) ? $html_inline_js = 1 : $html_inline_js = 0;
		isset( $data->html_comment ) && is_string( $data->html_comment ) ? $html_comment = 1 : $html_comment = 0;

		//SERVER SETTINGS
		isset( $data->static_aktiv ) && is_string( $data->static_aktiv ) ? $static_aktiv = 1 : $static_aktiv = 0;

		isset($data->cache_type) && is_numeric($data->cache_type) ? $cache_type = $data->cache_type : $cache_type = '';
		isset($data->memcache_host) && is_string($data->memcache_host) ? $memcache_host = $data->memcache_host : $memcache_host = '';
		isset($data->memcache_port) && is_numeric($data->memcache_port) ? $memcache_port = $data->memcache_port : $memcache_port = '';
		isset($data->subfolder) && is_string($data->subfolder) ? $subfolder = $data->subfolder : $subfolder = '';

		if ( ! $subfolder ) {
			$root    = explode( '/', HUPA_MINIFY_ROOT_PATH );
			$docRoot = explode( '/', $_SERVER['DOCUMENT_ROOT'] );
			$diff    = array_diff( $root, $docRoot );
			$diff ? $subfolder = implode( '/', $diff ) : $subfolder = '';
		}

		update_option( 'minify_css_aktiv', $css_aktiv );
		update_option( 'minify_js_aktiv', $js_aktiv );
		update_option( 'minify_html_aktiv', $html_aktiv );
		//CSS OPTION
		update_option( 'minify_css_groups_aktiv', $css_groups_aktiv );
		update_option( 'minify_css_bubble_import', $css_import_aktiv );
		//JS OPTION
		update_option( 'minify_js_groups_aktiv', $js_groups_aktiv );
		update_option( 'minify_jquery_core_aktiv', $wp_core_aktiv );
		update_option( 'minify_wp_embed_aktiv', $wp_embed_aktiv );
		//
		//HTML OPTION
		update_option( 'minify_html_inline_css', $html_inline_css );
		update_option( 'minify_html_inline_js', $html_inline_js );
		update_option( 'minify_html_comments', $html_comment );
		//SERVER SETTINGS
		update_option( 'minify_static_aktiv', $static_aktiv );
		update_option( 'minify_sub_folder', $subfolder );
		update_option( 'minify_cache_type', $cache_type );
		update_option( 'minify_memcache_host', $memcache_host );
		update_option( 'minify_memcache_port', $memcache_port );

		$responseJson->status = true;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );

		break;
	case'minify_ausgabe_settings':
		$responseJson->spinner = true;

		isset($data->active_settings) && is_numeric($data->active_settings) ? $active_settings = $data->active_settings : $active_settings = '';

		isset( $data->develop_debug ) && is_string( $data->develop_debug ) ? $develop_debug = 1 : $develop_debug = 0;
		isset( $data->develop_verkettung_aktiv ) && is_string( $data->develop_verkettung_aktiv ) ? $develop_verkettung_aktiv = 1 : $develop_verkettung_aktiv = 0;
		isset( $data->develop_cache_aktiv ) && is_string( $data->develop_cache_aktiv ) ? $develop_cache_aktiv = 1 : $develop_cache_aktiv = 0;
		isset( $data->develop_cache_path ) && is_string( $data->develop_cache_path ) ? $develop_cache_path = $data->develop_cache_path : $develop_cache_path = '';
		isset( $data->develop_cache_time ) && is_numeric( $data->develop_cache_time ) ? $develop_cache_time = $data->develop_cache_time : $develop_cache_time = '';

		isset( $data->product_debug ) && is_string( $data->product_debug ) ? $product_debug = 1 : $product_debug = 0;
		isset( $data->product_verkettung_aktiv ) && is_string( $data->product_verkettung_aktiv ) ? $product_verkettung_aktiv = 1 : $product_verkettung_aktiv = 0;
		isset( $data->produktion_cache_aktiv ) && is_string( $data->produktion_cache_aktiv ) ? $produktion_cache_aktiv = 1 : $produktion_cache_aktiv = 0;

		isset( $data->product_cache_path ) && is_string( $data->product_cache_path ) ? $product_cache_path = $data->product_cache_path : $product_cache_path = '';
		isset( $data->product_cache_time ) && is_numeric( $data->product_cache_time ) ? $product_cache_time = $data->product_cache_time : $product_cache_time = '';

		$develop_cache_path ? $dev_cache_path = $develop_cache_path : $dev_cache_path = HUPA_MINIFY_CACHE_PATH;
		$product_cache_path ? $prod_cache_path = $product_cache_path : $prod_cache_path = HUPA_MINIFY_CACHE_PATH;

		$settings_entwicklung = json_encode(
			[
				'min_cachePath' => $dev_cache_path,
				'cache_max_age' => $develop_cache_time,
				'cache_aktiv'   => $develop_cache_aktiv,
				'debug_aktiv'   => $develop_debug,
				'verkettung'    => $develop_verkettung_aktiv
			]
		);

		$settings_production = json_encode(
			[
				'min_cachePath' => $prod_cache_path,
				'cache_max_age' => $product_cache_time,
				'cache_aktiv'   => $produktion_cache_aktiv,
				'debug_aktiv'   => $product_debug,
				'verkettung'    => $product_verkettung_aktiv
			]
		);

		update_option( 'minify_settings_entwicklung', $settings_entwicklung );
		update_option( 'minify_settings_production', $settings_production );
		update_option( 'minify_settings_select', $active_settings );

		$responseJson->status = true;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );
		break;

	case'minify_wordpress_settings':
		$responseJson->spinner = true;

		isset( $data->rsd_aktiv ) && is_string( $data->rsd_aktiv ) ? $rsd_aktiv = 1 : $rsd_aktiv = 0;
		isset( $data->rss_aktiv ) && is_string( $data->rss_aktiv ) ? $rss_aktiv = 1 : $rss_aktiv = 0;
		isset( $data->rss_extra ) && is_string( $data->rss_extra ) ? $rss_extra = 1 : $rss_extra = 0;
		isset( $data->live_writer ) && is_string( $data->live_writer ) ? $live_writer = 1 : $live_writer = 0;
		isset( $data->posts_rel ) && is_string( $data->posts_rel ) ? $posts_rel = 1 : $posts_rel = 0;
		isset( $data->short_link ) && is_string( $data->short_link ) ? $short_link = 1 : $short_link = 0;

		isset( $data->version_aktiv ) && is_string( $data->version_aktiv ) ? $version_aktiv = 1 : $version_aktiv = 0;
		isset( $data->emoji_aktiv ) && is_string( $data->emoji_aktiv ) ? $emoji_aktiv = 1 : $emoji_aktiv = 0;
		isset( $data->css_gutenberg_aktiv ) && is_string( $data->css_gutenberg_aktiv ) ? $css_gutenberg_aktiv = 1 : $css_gutenberg_aktiv = 0;

		update_option( 'minify_rsd_aktiv', $rsd_aktiv );
		update_option( 'minify_rss_link', $rss_aktiv );
		update_option( 'minify_rss_extra', $rss_extra );
		update_option( 'minify_live_writer', $live_writer );
		update_option( 'minify_posts_rel', $posts_rel );
		update_option( 'minify_shortlink_aktiv', $short_link );

		update_option( 'minify_wp_version', $version_aktiv );
		update_option( 'minify_wp_block_css', $css_gutenberg_aktiv );
		update_option( 'minify_wp_emoji', $emoji_aktiv );

		$responseJson->status = true;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );
		break;

	case 'update_scss_settings':
		$responseJson->spinner = true;
		isset( $data->stylesheet ) && is_string( $data->stylesheet ) ? $stylesheet_aktiv = 1 : $stylesheet_aktiv = 0;
		isset( $data->map ) && is_string( $data->map ) ? $map_aktiv = 1 : $map_aktiv = 0;
		isset( $data->line_comments_aktiv ) && is_string( $data->line_comments_aktiv ) ? $line_comments_aktiv = 1 : $line_comments_aktiv = 0;

		isset( $data->formatter_mode ) && is_string( $data->formatter_mode ) ? $formatter_mode = esc_textarea($data->formatter_mode) : $formatter_mode = '';
		isset( $data->destination ) && is_string( $data->destination ) ? $destination = esc_textarea($data->destination) : $destination = '';
		isset( $data->source ) && is_string( $data->source ) ? $source = esc_textarea($data->source) : $source = '';
		isset( $data->map_option ) && is_string( $data->map_option ) ? $map_option = esc_textarea($data->map_option) : $map_option = '';
        isset( $data->scss_login_aktiv ) && is_string( $data->scss_login_aktiv ) ? $scss_login_aktiv = 1 : $scss_login_aktiv = 0;

		update_option( 'minify_scss_source', $source );
		update_option( 'minify_scss_destination', $destination );
		update_option( 'minify_scss_formatter', $formatter_mode );
		update_option( 'scss_stylesheet_aktiv', $stylesheet_aktiv );
		update_option( 'scss_map_aktiv', $map_aktiv );
		update_option( 'line_comments_aktiv', $line_comments_aktiv );
		update_option( 'minify_scss_map_option', $map_option );
        update_option( 'scss_login_aktiv', $scss_login_aktiv );
		$responseJson->status = true;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );
		break;

	case 'activate_server_status':
		$active = filter_var( $_POST['activate'], FILTER_UNSAFE_RAW );
		$active ? $isActive = true : $isActive = false;

		update_option( 'server_status_aktiv', $isActive );
		$responseJson->status = true;
		break;

	case'reset_minify_settings':
		do_action( 'minify_plugin_set_defaults', 'set_defaults' );
		$responseJson->method = $method;
		break;

	case 'change_ip_api_aktiv':
		get_option( 'ip_api_aktiv' ) ? update_option( 'ip_api_aktiv', 0 ) : update_option( 'ip_api_aktiv', 1 );
		$responseJson->ip_api = (bool) get_option( 'ip_api_aktiv' );
		$responseJson->method = $method;
		break;

	case 'change_statistik_menu':
		get_option( 'minify_show_status_menu' ) ? update_option( 'minify_show_status_menu', 0 ) : update_option( 'minify_show_status_menu', 1 );
		$responseJson->show_menu = (bool) get_option( 'minify_show_status_menu' );
		$responseJson->method    = $method;
		break;
}