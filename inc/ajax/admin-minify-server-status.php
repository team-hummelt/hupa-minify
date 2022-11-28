<?php
defined( 'ABSPATH' ) or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $hupa_server_class;
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
	case 'update_server_status_settings':
		$responseJson->spinner = true;
		$script_interval       = filter_var( trim( $data->script_interval ), FILTER_SANITIZE_NUMBER_INT );
		$memcache_host         = filter_var( trim( $data->memcache_host ), FILTER_UNSAFE_RAW );
		$memcache_port         = filter_var( trim( $data->memcache_port ), FILTER_SANITIZE_NUMBER_INT );
		$api_pro_key           = filter_var( trim( $data->api_pro_key ), FILTER_UNSAFE_RAW );

		$good_status_color           = filter_var( trim( $data->good_status_color ), FILTER_UNSAFE_RAW );
		$critical_status_color       = filter_var( trim( $data->critical_status_color ), FILTER_UNSAFE_RAW );
		$super_critical_status_color = filter_var( trim( $data->super_critical_status_color ), FILTER_UNSAFE_RAW );
		$footer_text_color           = filter_var( trim( $data->footer_text_color ), FILTER_UNSAFE_RAW );

		isset($data->status_aktiv) && is_string($data->status_aktiv) ? $status_aktiv = 1 : $status_aktiv = 0;
		isset($data->use_ipapi_pro) && is_string($data->use_ipapi_pro) ? $use_ipapi_pro = 1 : $use_ipapi_pro = 0;

		isset($data->echtzeit_aktiv) && is_string($data->echtzeit_aktiv) ? $echtzeit_aktiv = 1 : $echtzeit_aktiv = 0;
		isset($data->server_footer_aktiv) && is_string($data->server_footer_aktiv) ? $server_footer_aktiv = 1 : $server_footer_aktiv = 0;
		isset($data->server_dashboard_aktiv) && is_string($data->server_dashboard_aktiv) ? $server_dashboard_aktiv = 1 : $server_dashboard_aktiv = 0;

		isset($data->php_menu_aktiv) && is_string($data->php_menu_aktiv) ? $php_menu_aktiv = 1 : $php_menu_aktiv = 0;
		isset($data->sql_menu_aktiv) && is_string($data->sql_menu_aktiv) ? $sql_menu_aktiv = 1 : $sql_menu_aktiv = 0;
		isset($data->memcache_menu_aktiv) && is_string($data->memcache_menu_aktiv) ? $memcache_menu_aktiv = 1 : $memcache_menu_aktiv = 0;

		$script_interval ? $script_interval = strip_tags( stripslashes( $script_interval ) ) : $script_interval = 200;
		$good_status_color ? $good_status_color = strip_tags( stripslashes( $good_status_color ) ) : $good_status_color = '#37BF91';
		$critical_status_color ? $critical_status_color = strip_tags( stripslashes( $critical_status_color ) ) : $critical_status_color = '#d35400';
		$super_critical_status_color ? $super_critical_status_color = strip_tags( stripslashes( $super_critical_status_color ) ) : $super_critical_status_color = '#e74c3c';
		$footer_text_color ? $footer_text_color = strip_tags( stripslashes( $footer_text_color ) ) : $footer_text_color = '#8e44ad';


		if ( ! $hupa_server_class->minify_check_color( $good_status_color ) ) {
			$good_status_color = '#37BF91';
		}
		if ( ! $hupa_server_class->minify_check_color( $critical_status_color ) ) {
			$critical_status_color = '#d35400';
		}
		if ( ! $hupa_server_class->minify_check_color( $super_critical_status_color ) ) {
			$super_critical_status_color = '#e74c3c';
		}
		if ( ! $hupa_server_class->minify_check_color( $footer_text_color ) ) {
			$footer_text_color = '#8e44ad';
		}

		update_option( 'echtzeit_statistik_aktiv', $echtzeit_aktiv );
		update_option( 'server_footer_aktiv', $server_footer_aktiv );
		update_option( 'server_dashboard_aktiv', $server_dashboard_aktiv );

		update_option( 'php_menu_aktiv', $php_menu_aktiv );
		update_option( 'sql_menu_aktiv', $sql_menu_aktiv );
		update_option( 'memcache_menu_aktiv', $memcache_menu_aktiv );

		$settings_server_status = json_encode(
			[
				'refresh_interval'  => $script_interval,
				'bg_color_good'     => $good_status_color,
				'bg_color_average'  => $critical_status_color,
				'bg_color_bad'      => $super_critical_status_color,
				'footer_text_color' => $footer_text_color,
				'memcache_host'     => strip_tags( stripslashes( $memcache_host ) ),
				'memcache_port'     => strip_tags( stripslashes( $memcache_port ) ),
				'use_ipapi_pro'     => strip_tags( stripslashes( $use_ipapi_pro ) ),
				'ipapi_pro_key'     => strip_tags( stripslashes( $api_pro_key ) ),
			]
		);

		update_option( 'settings_server_status', $settings_server_status );

		$responseJson->status = true;
		$responseJson->status_aktiv = (bool) $status_aktiv;
		$responseJson->msg    = date( 'H:i:s', current_time( 'timestamp' ) );
		break;

	case'load_ajax_process':

		$stat = json_decode( get_option( 'settings_server_status' ) );
		$responseJson->refresh_interval = $stat->refresh_interval;
		$responseJson->bg_color_good    = $stat->bg_color_good;
		$responseJson->bg_color_average = $stat->bg_color_average;
		$responseJson->bg_color_bad     = $stat->bg_color_bad;

		if ( $hupa_server_class->isShellEnabled() ) {

			$cpu_load                  = trim( shell_exec( "echo $((`ps aux|awk 'NR > 0 { s +=$3 }; END {print s}'| cut -d . -f 1` / `cat /proc/cpuinfo | grep cores | grep -o '[0-9]' | wc -l`))" ) );
			$memory_usage_MB           = function_exists( 'memory_get_usage' ) ? round( memory_get_usage() / 1024 / 1024, 2 ) : 0;
			$memory_usage_pos          = round( ( ( $memory_usage_MB / (int) $hupa_server_class->minify_check_memory_limit_cal() ) * 100 ), 0 );
			$total_ram_server          = ( is_numeric( $hupa_server_class->minify_check_total_ram() ) ? (int) $hupa_server_class->minify_check_total_ram() : 0 );
			$free_ram_server           = ( is_numeric( $hupa_server_class->minify_check_free_ram() ) ? (int) $hupa_server_class->minify_check_total_ram() : 0 );
			$free_ram_server_formatted = ( is_numeric( $free_ram_server ) ? $hupa_server_class->minify_format_filesize_kB( $free_ram_server ) : "0 KB" );
			$used_ram_server           = ( $total_ram_server - $free_ram_server );
			$used_ram_server_formatted = ( is_numeric( $used_ram_server ) ? $hupa_server_class->minify_format_filesize_kB( $used_ram_server ) : "0 KB" );
			$ram_usage_pos             = round( ( ( $used_ram_server / $total_ram_server ) * 100 ), 0 );

			$uptime = trim( shell_exec( "cut -d. -f1 /proc/uptime" ) );

			$responseJson->cpu_load         = $cpu_load;
			$responseJson->memory_usage_MB  = $memory_usage_MB;
			$responseJson->memory_usage_pos = $memory_usage_pos;
			$responseJson->total_ram        = $total_ram_server;
			$responseJson->free_ram         = $free_ram_server;
			$responseJson->used_ram         = $free_ram_server_formatted;
			$responseJson->ram_usage_pos    = $ram_usage_pos;
			$responseJson->uptime           = $uptime;
			return $responseJson;
		} else {
			$memory_usage_MB  = function_exists( 'memory_get_usage' ) ? round( memory_get_usage() / 1024 / 1024, 2 ) : 0;
			$memory_usage_pos = round( $memory_usage_MB / (int) $hupa_server_class->minify_check_memory_limit_cal() * 100, 0 );
			$responseJson->cpu_load = null;
			$responseJson->memory_usage_MB = $memory_usage_MB;
			$responseJson->memory_usage_pos = $memory_usage_pos;
			$responseJson->uptime = null;
		}

		break;

	case'minify_cache_purge':
		global $wpdb;
		delete_option('wpss_db_advanced_info');
		delete_site_option('wpss_db_advanced_info');

		// Delete transients
		delete_transient('wpss_server_location');
		delete_transient('wpss_cpu_count');
		delete_transient('wpss_cpu_core_count');
		delete_transient('wpss_server_os');
		delete_transient('wpss_db_software');
		delete_transient('wpss_db_version');
		delete_transient('wpss_db_max_connection');
		delete_transient('wpss_db_max_packet_size');
		delete_transient('wpss_db_disk_usage');
		delete_transient('wpss_db_index_disk_usage');
		delete_transient('wpss_php_max_upload_size');
		delete_transient('wpss_php_max_post_size');
		delete_transient('wpss_total_ram');

		// Delete option for multisite
		delete_site_transient('wpss_server_location');
		delete_site_transient('wpss_cpu_count');
		delete_site_transient('wpss_cpu_core_count');
		delete_site_transient('wpss_server_os');
		delete_site_transient('wpss_db_software');
		delete_site_transient('wpss_db_version');
		delete_site_transient('wpss_db_max_connection');
		delete_site_transient('wpss_db_max_packet_size');
		delete_site_transient('wpss_db_disk_usage');
		delete_site_transient('wpss_db_index_disk_usage');
		delete_site_transient('wpss_php_max_upload_size');
		delete_site_transient('wpss_php_max_post_size');

		$responseJson->msg = "Der Server-Statistik-Cache wurde erfolgreich geleert!";
		break;
	case'load_footer_layout':
		if(!get_option( 'server_footer_aktiv' )){
			$responseJson->status = false;
			return $responseJson;
		}
		$html = $hupa_server_class->minify_footer_template();
		$responseJson->html = $html;
		$responseJson->status = true;
		break;
}
