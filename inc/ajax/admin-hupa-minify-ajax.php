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
$method = filter_var( $data->method, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
if ( ! $method ) {
	$method = filter_input( INPUT_POST, 'method', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
}

switch ( $method ) {
	case 'update_minify_settings':
		$responseJson->spinner = true;
		filter_var( $data->css_aktiv, FILTER_SANITIZE_STRING ) ? $css_aktiv = 1 : $css_aktiv = 0;
		filter_var( $data->js_aktiv, FILTER_SANITIZE_STRING ) ? $js_aktiv = 1 : $js_aktiv = 0;
		filter_var( $data->html_aktiv, FILTER_SANITIZE_STRING ) ? $html_aktiv = 1 : $html_aktiv = 0;

		//CSS OPTION
		filter_var( $data->css_groups_aktiv, FILTER_SANITIZE_STRING ) ? $css_groups_aktiv = 1 : $css_groups_aktiv = 0;
		filter_var( $data->css_import_aktiv, FILTER_SANITIZE_STRING ) ? $css_import_aktiv = 1 : $css_import_aktiv = 0;
		//JS OPTION
		filter_var( $data->js_groups_aktiv, FILTER_SANITIZE_STRING ) ? $js_groups_aktiv = 1 : $js_groups_aktiv = 0;
		filter_var( $data->wp_core_aktiv, FILTER_SANITIZE_STRING ) ? $wp_core_aktiv = 1 : $wp_core_aktiv = 0;
		filter_var( $data->wp_embed_aktiv, FILTER_SANITIZE_STRING ) ? $wp_embed_aktiv = 1 : $wp_embed_aktiv = 0;
		//HTML OPTION
		filter_var( $data->html_inline_css, FILTER_SANITIZE_STRING ) ? $html_inline_css = 1 : $html_inline_css = 0;
		filter_var( $data->html_inline_js, FILTER_SANITIZE_STRING ) ? $html_inline_js = 1 : $html_inline_js = 0;
		filter_var( $data->html_comment, FILTER_SANITIZE_STRING ) ? $html_comment = 1 : $html_comment = 0;

		//SERVER SETTINGS
		filter_var( $data->static_aktiv, FILTER_SANITIZE_STRING ) ? $static_aktiv = 1 : $static_aktiv = 0;
		$cache_type    = filter_var( $data->cache_type, FILTER_SANITIZE_NUMBER_INT );
		$memcache_host = filter_var( $data->memcache_host, FILTER_SANITIZE_STRING );
		$memcache_port = filter_var( $data->memcache_port, FILTER_SANITIZE_NUMBER_INT );
		$subfolder     = filter_var( $data->subfolder, FILTER_SANITIZE_STRING );

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

		$active_settings = filter_var( $data->active_settings, FILTER_SANITIZE_NUMBER_INT );

		filter_var( $data->develop_debug, FILTER_SANITIZE_STRING ) ? $develop_debug = 1 : $develop_debug = 0;
		filter_var( $data->develop_verkettung_aktiv, FILTER_SANITIZE_STRING ) ? $develop_verkettung_aktiv = 1 : $develop_verkettung_aktiv = 0;
		filter_var( $data->develop_cache_aktiv, FILTER_SANITIZE_STRING ) ? $develop_cache_aktiv = 1 : $develop_cache_aktiv = 0;
		$develop_cache_path = filter_var( $data->develop_cache_path, FILTER_SANITIZE_STRING );
		$develop_cache_time = filter_var( $data->develop_cache_time, FILTER_SANITIZE_NUMBER_INT );

		filter_var( $data->product_debug, FILTER_SANITIZE_STRING ) ? $product_debug = 1 : $product_debug = 0;
		filter_var( $data->product_verkettung_aktiv, FILTER_SANITIZE_STRING ) ? $product_verkettung_aktiv = 1 : $product_verkettung_aktiv = 0;
		filter_var( $data->produktion_cache_aktiv, FILTER_SANITIZE_STRING ) ? $produktion_cache_aktiv = 1 : $produktion_cache_aktiv = 0;
		$product_cache_path = filter_var( $data->product_cache_path, FILTER_SANITIZE_STRING );
		$product_cache_time = filter_var( $data->product_cache_time, FILTER_SANITIZE_NUMBER_INT );

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

		filter_var( $data->rsd_aktiv, FILTER_SANITIZE_STRING ) ? $rsd_aktiv = 1 : $rsd_aktiv = 0;
		filter_var( $data->rss_aktiv, FILTER_SANITIZE_STRING ) ? $rss_aktiv = 1 : $rss_aktiv = 0;
		filter_var( $data->rss_extra, FILTER_SANITIZE_STRING ) ? $rss_extra = 1 : $rss_extra = 0;
		filter_var( $data->live_writer, FILTER_SANITIZE_STRING ) ? $live_writer = 1 : $live_writer = 0;
		filter_var( $data->posts_rel, FILTER_SANITIZE_STRING ) ? $posts_rel = 1 : $posts_rel = 0;
		filter_var( $data->short_link, FILTER_SANITIZE_STRING ) ? $short_link = 1 : $short_link = 0;

		filter_var( $data->version_aktiv, FILTER_SANITIZE_STRING ) ? $version_aktiv = 1 : $version_aktiv = 0;
		filter_var( $data->emoji_aktiv, FILTER_SANITIZE_STRING ) ? $emoji_aktiv = 1 : $emoji_aktiv = 0;
		filter_var( $data->css_gutenberg_aktiv, FILTER_SANITIZE_STRING ) ? $css_gutenberg_aktiv = 1 : $css_gutenberg_aktiv = 0;

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
		filter_var( $data->stylesheet, FILTER_SANITIZE_STRING ) ? $stylesheet_aktiv = 1 : $stylesheet_aktiv = 0;
		filter_var( $data->map, FILTER_SANITIZE_STRING ) ? $map_aktiv = 1 : $map_aktiv = 0;
		filter_var( $data->line_comments_aktiv, FILTER_SANITIZE_STRING ) ? $line_comments_aktiv = 1 : $line_comments_aktiv = 0;
		$formatter_mode = filter_var( $data->formatter_mode, FILTER_SANITIZE_STRING );
		$destination    = filter_var( $data->destination, FILTER_SANITIZE_STRING );
		$source         = filter_var( $data->source, FILTER_SANITIZE_STRING );
		$map_option     = filter_var( $data->map_option, FILTER_SANITIZE_STRING );

		update_option( 'minify_scss_source', $source );
		update_option( 'minify_scss_destination', $destination );
		update_option( 'minify_scss_formatter', $formatter_mode );
		update_option( 'scss_stylesheet_aktiv', $stylesheet_aktiv );
		update_option( 'scss_map_aktiv', $map_aktiv );
		update_option( 'line_comments_aktiv', $line_comments_aktiv );
		update_option( 'minify_scss_map_option', $map_option );

		$responseJson->status = true;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );
		break;

	case 'activate_server_status':
		$active = filter_var( $_POST['activate'], FILTER_SANITIZE_STRING );
		$active ? $isActive = true : $isActive = false;

		update_option( 'server_status_aktiv', $isActive );
		$responseJson->status = true;
		break;

	case'reset_minify_settings':
		do_action('minify_plugin_set_defaults', 'set_defaults');
		$responseJson->method = $method;
		break;

	case 'change_ip_api_aktiv':
		get_option('ip_api_aktiv') ? update_option('ip_api_aktiv', 0) : update_option('ip_api_aktiv', 1);
		$responseJson->ip_api = (bool) get_option('ip_api_aktiv');
		$responseJson->method = $method;
		break;

	case 'change_statistik_menu':
		get_option('minify_show_status_menu') ? update_option('minify_show_status_menu', 0) : update_option('minify_show_status_menu', 1);
		$responseJson->show_menu = (bool) get_option('minify_show_status_menu');
		$responseJson->method = $method;
		break;
}