<?php

defined('ABSPATH') or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

final class HupaMinifyWpLoaded {
	private static $instance;

	/**
	 * @return static
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init_hupa_minify_wp_loaded() {
		add_action('after_setup_theme', array($this, 'hupa_minify_get_file_data'));
	}

	public function hupa_minify_get_file_data() {
		add_filter('wp_enqueue_scripts', array($this, 'hupa_minify_get_head_file_data'),999 , 2);
	}

	public function hupa_minify_get_head_file_data(){

		global $wp_scripts;
		global $wp_styles;
		if ( ! $wp_scripts instanceof WP_Scripts ) {
			$wp_scripts = new WP_Scripts();
		}

		if ( ! $wp_styles instanceof WP_Styles ) {
			$wp_styles = new WP_Styles();
		}

		$styleArr = [];
		$scriptArr = [];
		$modificated = '';
		foreach ($wp_styles->queue as $style) {
			$srcFile = $wp_styles->query( $style )->src;
			if(!$srcFile) {
				continue;
			}

			$wpParse = wp_parse_url($srcFile);
			$modificated = date( 'YmdHi', filemtime( HUPA_MINIFY_ROOT_PATH . $wpParse['path']));
			$style_item = [
				'css' => '//'.trim($wpParse['path'], '/'),
			];

			$styleArr[] = $style_item;
			wp_dequeue_style($style);
		}

		wp_enqueue_style( 'minify-global-style', site_url() . '?minify=min&g=css&v=' . $modificated . '', array(), null );
		print_r($wp_scripts->registered['jquery-core']);
		print_r($wp_scripts->registered['jquery-migrate']);
		print_r($wp_scripts->registered['wp-embed']);
		//comment-reply
		foreach ($wp_scripts->queue as $script) {
			$srcFile = $wp_scripts->query( $script )->src;
			if(!$srcFile) {
				continue;
			}

			$wpParse = wp_parse_url($srcFile);
			$modificated = date( 'YmdHi', filemtime( HUPA_MINIFY_ROOT_PATH . $wpParse['path']));
			$script_item = [
				'js' => '//'.trim($wpParse['path'], '/'),
			];

			$scriptArr[] = $script_item;
			wp_deregister_script( $script );

		}
		//wp_deregister_script('wp-embed');
		wp_enqueue_script( 'minify-global-script', site_url() . '?minify=min&g=js&v='.$modificated, array(), null, true );

		$styleData = json_encode($styleArr);
		$scriptData = json_encode($scriptArr);
		update_option('minify_style_css', $styleData);
		update_option('minify_script_js', $scriptData);

	}

	public function delete_jquery_wp_core() {
		wp_deregister_script('jquery');
		wp_register_script('jquery', false);
	}
}

$hupa_minify_wp_loaded = HupaMinifyWpLoaded::instance();
$hupa_minify_wp_loaded->init_hupa_minify_wp_loaded();

