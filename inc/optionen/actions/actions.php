<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
defined( 'ABSPATH' ) or die();

add_filter('get_hupa_minify_data', function() {

	$args = func_get_args();
	$argsNum = func_num_args();

	global $wp_scripts;
	global $wp_styles;

	if (! $wp_scripts instanceof WP_Scripts ) {
		$wp_scripts = new WP_Scripts();
	}

	if (! $wp_styles instanceof WP_Styles ) {
		$wp_styles = new WP_Styles();
	}
	$retArr = [];
	$scriptArr = [];
	if($args[0]){
		switch ($args[0]){
			case 'scripts':
				foreach ($wp_scripts->queue as $src) {
					$srcFile = $wp_scripts->query($src)->src;
					if(!$srcFile){
						continue;
					}

					$wp_scripts->query($src)->add_data('group','hupa-script');
					$parse_url = wp_parse_url($srcFile);
					$ret_items = [
						'type' => 'js',
						'path' => trim($parse_url['path'],'/'),
						'id' => $src,
						'ver'=> $wp_scripts->query($src)->ver,
						'group' => $wp_scripts->query($src)->extra['group']
					];
					$retArr[] = $ret_items;
					//wp_deregister_script( $src);
				}
				break;
			case 'styles':
				foreach ($wp_styles->queue as $src) {
					$srcFile = $wp_styles->query($src)->src;
					if ( ! $srcFile ) {
						continue;
					}
					$wp_styles->query($src)->add_data('group','hupa-style');
					$parse_url = wp_parse_url($srcFile);
					$ret_items = [
						'type' => 'css',
						'path' => trim($parse_url['path'],'/'),
						'id' => $src,
						'ver'=> $wp_styles->query($src)->ver,
						'group' => $wp_styles->query($src)->extra['group']
					];
					$retArr[] = $ret_items;
				}
				break;
			case 'data':
				foreach ($wp_scripts->queue as $src) {
					$srcFile = $wp_scripts->query($src)->extra;
					if(!$srcFile['data']){
						continue;
					}
					$wp_scripts->query($src)->add_data('group','hupa-data');
					$ret_items = [
						'type' => 'data',
						'path'=> $srcFile['data'],
						'id' => $src,
						'ver'=> 'unbekannt',
						'group' => $wp_scripts->query($src)->extra['group'],
					];

					$retArr[] = $ret_items;
				}
				break;

			case 'footer':
				foreach ($wp_scripts->in_footer as $src) {
					if(!$src){
						continue;
					}
					$retArr[] = $src;
				}
				break;
		}
	}
	return $retArr;
}, 10, 3);


add_action( 'wp_head', 'hupa_minify_get_head_data');
function hupa_minify_get_head_data() {
	$scripts = apply_filters('get_hupa_minify_data','scripts');
	$styles = apply_filters('get_hupa_minify_data','styles');
	$data = apply_filters('get_hupa_minify_data','data');
	$inFooter = apply_filters('get_hupa_minify_data','footer');

	$scrMinArr = [];
	foreach ($scripts as $tmp){
		$scrMinArr[] = '//'.$tmp['path'];
	}
	$src_item[] = [
		'js' => implode(', ',$scrMinArr)
	];
	//print_r($src_item);
	print_r($scripts);
	//print_r($styles);
}



