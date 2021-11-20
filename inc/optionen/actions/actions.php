<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
defined( 'ABSPATH' ) or die();

add_filter( 'get_hupa_minify_data', function () {

	$args    = func_get_args();
	$argsNum = func_num_args();

	global $wp_scripts;
	global $wp_styles;

	if ( ! $wp_scripts instanceof WP_Scripts ) {
		$wp_scripts = new WP_Scripts();
	}

	if ( ! $wp_styles instanceof WP_Styles ) {
		$wp_styles = new WP_Styles();
	}
	$scrArr      = [];
	$styleArr      = [];
	$modificated = '';


	foreach ( $wp_scripts->queue as $src ) {
		$srcFile = $wp_scripts->query( $src )->src;
		if ( ! $srcFile ) {
			continue;
		}
		$parse_url   = wp_parse_url( $srcFile );
		$path        = trim( $parse_url['path'], '/' );
		$filePath    = HUPA_MINIFY_ROOT_PATH . DIRECTORY_SEPARATOR . $path;
		$modificated = date( 'YmdHi', filemtime( $filePath ) );
		$scrArr[] = '//' . $path;
		//wp_deregister_script( $src );
	}

	$js['js'] = $scrArr;
	//update_option('minify_script_js', $js['js']);
	//wp_enqueue_script( 'minify-global-script', site_url() . '?minify=min&g=js&v=' . $modificated . '', array(), null, true );


	foreach ( $wp_styles->queue as $src ) {
		$srcFile = $wp_styles->query( $src )->src;
		if ( ! $srcFile ) {
			continue;
		}
		//$wp_styles->query( $src )->add_data( 'group', 'hupa-style' );
		$parse_url   = wp_parse_url( $srcFile );
		$path        = trim( $parse_url['path'], '/' );
		$filePath    = HUPA_MINIFY_ROOT_PATH . DIRECTORY_SEPARATOR . $path;

		$modificated = date( 'YmdHi', filemtime( $filePath ) );
			$style_item = [
				'path' => '//'.$path,
				'id' => $src
			];
			//$styleArr[] = '//'.$path;
		$styleArr[] = $style_item;
	}


	//$css['css'] = $styleArr;
	$styleData = json_encode($styleArr);
	//update_option('minify_style_css', $styleData);
	//wp_enqueue_style( 'minify-global-style', site_url() . '?minify=min&g=css&v=' . $modificated . '', array(), null );
	//return $wp_styles->queue;
}, 10, 3 );




add_action( 'wp_head', 'hupa_minify_get_head_data' );
function hupa_minify_get_head_data() {
	$scriptsStyle = apply_filters( 'get_hupa_minify_data', 'scripts' );
	global $wp_styles;
	if ( ! $wp_styles instanceof WP_Styles ) {
		$wp_styles = new WP_Styles();
	}


	//print_r($scriptsStyle);

}




//print_r( get_option('minify_groups_style_css'));
//print_r( get_option('minify_groups_script_js'));



