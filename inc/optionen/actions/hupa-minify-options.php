<?php

namespace Hupa\Minify;

use stdClass;

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

defined( 'ABSPATH' ) or die();


add_action( 'minify_plugin_set_defaults', function () {
	$args    = func_get_args();
	$argsNum = func_num_args();

	switch ( $args[0] ) {
		case 'check_settings':
			if ( ! get_option( 'minify_aktiv' ) ) {
				hupa_minify_set_settings();
				update_option( 'minify_aktiv', true );
			}
			break;
		case 'set_defaults':
			hupa_minify_set_settings();
			break;
	}
}, 10, 2 );

function hupa_minify_set_settings() {
	$default = hupa_minify_default_settings();
	update_option( 'minify_aktiv', $default->aktiv );
	update_option( 'minify_sub_folder', $default->sub_folder );
	update_option( 'minify_css_aktiv', $default->css_aktiv );
	update_option( 'minify_js_aktiv', $default->js_aktiv );
	update_option( 'minify_html_aktiv', $default->html_aktiv );
	update_option( 'minify_jquery_core_aktiv', $default->jquery_core_aktiv );
	update_option( 'minify_css_groups_aktiv', $default->css_groups_aktiv );
	update_option( 'minify_js_groups_aktiv', $default->js_groups_aktiv );
	update_option( 'minify_wp_embed_aktiv', $default->wp_embed_aktiv );
	update_option( 'minify_cache_type', $default->cache_type );
	update_option( 'minify_settings_select', $default->settings_select );
	update_option( 'minify_css_bubble_import', $default->css_bubble_import );

	update_option( 'minify_html_inline_css', $default->html_inline_css );
	update_option( 'minify_html_inline_js', $default->html_inline_js );
	update_option( 'minify_html_comments', $default->html_comment );

	update_option( 'minify_static_aktiv', $default->static_aktiv );
	update_option( 'minify_settings_entwicklung', $default->settings_entwicklung );
	update_option( 'minify_settings_production', $default->settings_production );
	update_option( 'minify_memcache_host', $default->memcache_host );
	update_option( 'minify_memcache_port', $default->memcache_port );

	update_option( 'minify_rsd_aktiv', $default->rsd_aktiv );
	update_option( 'minify_rss_link', $default->rss_link );
	update_option( 'minify_rss_extra', $default->rss_extra );
	update_option( 'minify_live_writer', $default->live_writer );
	update_option( 'minify_posts_rel', $default->posts_rel );
	update_option( 'minify_shortlink_aktiv', $default->short_link );

	update_option( 'minify_wp_version', $default->wp_version );
	update_option( 'minify_wp_block_css', $default->wp_block_css );
	update_option( 'minify_wp_emoji', $default->wp_emoji );
	update_option( 'settings_server_status', $default->settings_server_status );
	update_option('server_status_aktiv', $default->server_status_aktiv);
}

function hupa_minify_default_settings(): object {

	$root    = explode( '/', HUPA_MINIFY_ROOT_PATH );
	$docRoot = explode( '/', $_SERVER['DOCUMENT_ROOT'] );
	$diff    = array_diff( $root, $docRoot );
	$diff ? $subFolder = implode( '/', $diff ) : $subFolder = '';

	$settings = [
		'aktiv'             => 1,
		'sub_folder'        => $subFolder,
		'css_aktiv'         => 0,
		'js_aktiv'          => 0,
		'html_aktiv'        => 0,
		'jquery_core_aktiv' => 0,
		'wp_embed_aktiv'    => 0,
		'css_groups_aktiv'  => 1,
		'js_groups_aktiv'   => 1,
		'cache_type'        => 1,
		'settings_select'   => 2,
		'css_bubble_import' => 0,
		'static_aktiv'      => 0,
		'memcache_host'     => 'localhost',
		'memcache_port'     => 11211,

		'rsd_aktiv'   => 0,
		'rss_link'    => 0,
		'rss_extra'   => 0,
		'live_writer' => 0,
		'posts_rel'   => 0,
		'short_link'  => 0,

		'html_inline_css' => 1,
		'html_inline_js'  => 1,
		'html_comment'    => 1,

		'wp_version'             => 0,
		'wp_block_css'           => 0,
		'wp_emoji'               => 0,
		'server_status_aktiv'    => 0,
		'settings_entwicklung'   => json_encode(
			[
				'min_cachePath' => HUPA_MINIFY_CACHE_PATH,
				'cache_max_age' => 0,
				'cache_aktiv'   => 0,
				'debug_aktiv'   => 1,
				'verkettung'    => 1
			]
		),
		'settings_production'    => json_encode(
			[
				'min_cachePath' => HUPA_MINIFY_CACHE_PATH,
				'cache_max_age' => 86400,
				'cache_aktiv'   => 1,
				'debug_aktiv'   => 0,
				'verkettung'    => 0
			]
		),
		'settings_server_status' => json_encode(
			[
				'refresh_interval'  => 200,
				'bg_color_good'     => '#37BF91',
				'bg_color_average'  => '#d35400',
				'bg_color_bad'      => '#e74c3c',
				'footer_text_color' => '#8e44ad',
				'memcache_host'     => 'localhost',
				'memcache_port'     => 11211,
				'use_ipapi_pro'     => 0,
				'ipapi_pro_key'     => ''
			]
		)
	];

	return (object) $settings;
}