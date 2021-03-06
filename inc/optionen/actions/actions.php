<?php
namespace Hupa\Minify;
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'MinifyThemeWPOptionen' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\Minify\\MinifyThemeWPOptionen', 'init' ), 0 );

	class MinifyThemeWPOptionen {
		private static $instance;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *MinifyThemeWPOptionen constructor.
		 */
		public function __construct() {
			//JOB HTML OPTIMIZE
			if ( get_option( 'minify_html_aktiv' ) ) {
				add_action( 'get_header', 'Minify\\Compress\\minify_wp_html_compression_start' );
			}

			//JOB REMOVE (RSD)
			if ( get_option( 'minify_rsd_aktiv' ) ) {
				remove_action('wp_head', 'rsd_link');
			}

			//JOB REMOVE Feed Links
			if ( get_option( 'minify_rss_link' ) ) {
				remove_action('wp_head', 'feed_links', 2);
			}

			//JOB REMOVE Feed Links Extra
			if ( get_option( 'minify_rss_extra' ) ) {
				remove_action('wp_head', 'feed_links_extra', 3);
			}

			//JOB REMOVE Live Writer
			if ( get_option( 'minify_live_writer' ) ) {
				remove_action('wp_head', 'wlwmanifest_link');
			}

			//JOB REMOVE REL Links
			if ( get_option( 'minify_posts_rel' ) ) {
				remove_action('wp_head', 'adjacent_posts_rel_link');
			}

			//JOB REMOVE Shortlink Tag
			if ( get_option( 'minify_shortlink_aktiv' ) ) {
				remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
			}

			//JOB REMOVE Wordpress Information
			if ( get_option( 'minify_wp_version' ) ) {
				remove_action( 'wp_head', 'wp_generator' );
			}

			//JOB REMOVE WP EMOJI
			if ( get_option( 'minify_wp_emoji' ) ) {
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );
			}

			//JOB REMOVE Gutenberg Css In FrontEnd
			if ( get_option( 'minify_wp_block_css' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'minify_wp_remove_wp_block_library_css' ), 100 );
			}
		}

		public function minify_wp_remove_wp_block_library_css(): void {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
		}
	}
}



