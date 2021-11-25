<?php

defined( 'ABSPATH' ) or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaMinifyWpLoaded {
	private static $instance;
	protected array $options;

	/**
	 * @return static
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->options = [
			'regExPath' => '/(wp-content.+|wp-include.+)/i',
			'regExMin'  => '@\.min\.@',
			'regInc'    => '@wp-include@'
		];
	}

	public function init_hupa_minify_wp_loaded() {

		if ( get_option( 'scss_stylesheet_aktiv' ) && MINIFY_SCSS_COMPILER_AKTIV ) {
			add_action( 'wp_loaded', array( $this, 'hupa_minify_set_scss_compiler_data' ) );
		}
		add_action( 'wp_loaded', array( $this, 'hupa_minify_get_header_css_data' ) );
		add_action( 'wp_loaded', array( $this, 'hupa_minify_get_footer_data' ) );
		add_action( 'init', array( $this, 'minify_delete_header_scripts' ) );
		add_filter( 'array_to_object', array( $this, 'hupaArrayToObject' ) );
	}

	public function hupa_minify_set_scss_compiler_data() {
		add_action( 'wp_enqueue_scripts', array( $this, 'hupa_minify_set_header_scss_compiler_file_data' ), 998, 2 );
	}

	public function hupa_minify_get_footer_data() {
		add_action( 'wp_enqueue_scripts', array( $this, 'hupa_minify_get_footer_file_data' ), 999, 2 );
	}

	public function hupa_minify_get_header_css_data() {
		add_action( 'wp_enqueue_scripts', array( $this, 'hupa_minify_get_header_css_file_data' ), 999, 2 );
	}

	// JOB SET COMPILER SCSS
	public function hupa_minify_set_header_scss_compiler_file_data() {
		$dir         = HUPA_MINIFY_THEME_ROOT . get_option( 'minify_scss_destination' );
		$fileArr     = [];
		$isStyle     = [];
		global $wp_styles;

		if ( is_dir( $dir ) ) {
			if ( ! $wp_styles instanceof WP_Styles ) {
				$wp_styles = new WP_Styles();
			}

			$src = array_diff( scandir( $dir ), array( '..', '.' ) );
			foreach ( $src as $tmp ) {
				$file = $dir . $tmp;
				if ( is_file( $file ) ) {
					$path_info = pathinfo( $file );
					if ( $path_info['extension'] !== 'css' ) {
						continue;
					}
					preg_match( $this->options['regExPath'], $path_info['dirname'], $matches );
					if ( ! $matches ) {
						continue;
					}
					$path      = $matches[0];
					$file_item = [
						'baseName' => $path_info['basename'],
						'fileName' => $path_info['filename'],
						'path'     => $path . DIRECTORY_SEPARATOR,
					];
					$fileArr[] = $file_item;
				}
			}
		}
		if ( $fileArr ) {
			foreach ( $wp_styles->queue as $style ) {
				$source    = $wp_styles->query( $style )->src;
				$isStyle[] = substr( $source, strrpos( $source, '/' ) + 1 );
			}
			foreach ( $fileArr as $tmp ) {
				if ( in_array( $tmp['baseName'], $isStyle ) ) {
					continue;
				}
				$file     = $tmp['path'] . $tmp['baseName'];

				if ( get_option( 'minify_css_aktiv' ) ) {
					$fileTime = '';
				} else {
					$fileTime = '?ver=' . filemtime( $file );
				}

				$fileId   = 'minify-css-' . str_replace( '.', '-', $tmp['fileName'] );
				preg_match( $this->options['regExMin'], $tmp['path'] . $tmp['baseName'] ) ? $min = true : $min = false;
				$min ? $g = 'singleMinCss' : $g = 'singleCss';
				if ( $wp_styles->query( $fileId )->handle ) {
					$wp_styles->remove( $fileId );
				}
				if ( get_option( 'minify_css_aktiv' ) ) {
					wp_enqueue_style( $fileId, site_url() . '?minify=min&g='.$g.'&p=' . $file . $fileTime , array(), null, null );
				} else {
					wp_enqueue_style( $fileId, site_url() . '/' . $file . $fileTime , array(), null, null );
				}
			}
		}
	}

	//JOB HEADER CSS
	public function hupa_minify_get_header_css_file_data() {
		if ( get_option( 'minify_css_aktiv' ) ):
			$minDevelop  = json_decode( get_option( 'minify_settings_entwicklung' ) );
			$minProduct  = json_decode( get_option( 'minify_settings_production' ) );
			$debug_aktiv = match ( get_option( 'minify_settings_select' ) ) {
				'1' => (bool) $minDevelop->debug_aktiv,
				'2' => (bool) $minProduct->debug_aktiv,
				default => false,
			};
			$debug_aktiv ? $debug = '&debug' : $debug = '';
			$styleArr = [];

			global $wp_styles;
			global $wp_scripts;
			if ( ! $wp_styles instanceof WP_Styles ) {
				$wp_styles = new WP_Styles();
			}

			if ( ! $wp_scripts instanceof WP_Scripts ) {
				$wp_scripts = new WP_Scripts();
			}

			foreach ( $wp_styles->queue as $style ) {
				$source = $wp_styles->query( $style )->src;
				if ( $source ) {
					preg_match( $this->options['regExPath'], $source, $matches );
					if ( ! $matches ) {
						continue;
					}
					$path = $matches[0];
					preg_match( $this->options['regExMin'], $path ) ? $min = true : $min = false;

					$file     = HUPA_MINIFY_ROOT_PATH . DIRECTORY_SEPARATOR . $path;
					$fileTime = '&v=' . filemtime( $file );
					$min ? $g = 'singleMinCss' : $g = 'singleCss';

					if ( get_option( 'minify_css_groups_aktiv' ) ) {
						$style_item = [
							'css' => '//' . $path,
						];
						$styleArr[] = $style_item;
						$wp_styles->remove( $style );
					} else {
						$wp_styles->remove( $style );
						wp_enqueue_style( $style, site_url() . '?minify=min&g=' . $g . '&p=' . $path . $fileTime . $debug, array(), null, null );
					}
				}
			}
			if ( get_option( 'minify_css_groups_aktiv' ) ) {
				$v = '&v=' . ( $_SERVER['REQUEST_TIME'] - $_SERVER['REQUEST_TIME'] % 86400 );
				wp_enqueue_style( 'minify-global-style', site_url() . '?minify=min&g=css' . $v . $debug, array(), null, null );
				$styleData = json_encode( $styleArr );
				update_option( 'minify_style_header_css', $styleData );
			}
		endif;
	}

	//JOB FOOTER SCRIPT
	public function hupa_minify_get_footer_file_data() {
		if ( get_option( 'minify_js_aktiv' ) ):
			$minDevelop  = json_decode( get_option( 'minify_settings_entwicklung' ) );
			$minProduct  = json_decode( get_option( 'minify_settings_production' ) );
			$debug_aktiv = match ( get_option( 'minify_settings_select' ) ) {
				'1' => (bool) $minDevelop->debug_aktiv,
				'2' => (bool) $minProduct->debug_aktiv,
				default => false,
			};

			$debug_aktiv ? $debug = '&debug' : $debug = '';

			global $wp_scripts;
			if ( ! $wp_scripts instanceof WP_Scripts ) {
				$wp_scripts = new WP_Scripts();
			}
			$scriptArr  = [];
			$doHeaderJS = $wp_scripts->do_head_items();

			foreach ( $wp_scripts->queue as $src ) {
				if ( in_array( $src, $doHeaderJS ) ) {
					continue;
				}
				$source = $wp_scripts->query( $src )->src;

				if ( $source ) {
					preg_match( $this->options['regExPath'], $source, $matches );
					if ( ! $matches ) {
						continue;
					}

					$path = $matches[0];
					preg_match( $this->options['regExMin'], $path ) ? $min = true : $min = false;
					$file     = HUPA_MINIFY_ROOT_PATH . DIRECTORY_SEPARATOR . $path;
					$fileTime = '&v=' . filemtime( $file );
					$min ? $g = 'singleMinJs' : $g = 'singleJs';

					if ( get_option( 'minify_js_groups_aktiv' ) ) {
						$script_item = [
							'js' => '//' . $path
						];
						$scriptArr[] = $script_item;
						$wp_scripts->remove( $src );
					} else {
						$wp_scripts->remove( $src );
						wp_enqueue_script( $src, site_url() . '?minify=min&g=' . $g . '&p=' . $path . $fileTime . $debug, array(), null, true );
					}
				}
			}

			if ( get_option( 'minify_js_groups_aktiv' ) ) {
				if ( get_option( 'minify_wp_embed_aktiv' ) ) {
					$embed_item  = [
						'js' => '//' . trim( $wp_scripts->registered['wp-embed']->src, DIRECTORY_SEPARATOR )
					];
					$scriptArr[] = $embed_item;
					$wp_scripts->remove( $wp_scripts->registered['wp-embed']->handle );
				}
				$v = '&v=' . ( $_SERVER['REQUEST_TIME'] - $_SERVER['REQUEST_TIME'] % 86400 );
				wp_enqueue_script( 'minify-global-script', site_url() . '?minify=min&g=js' . $v . $debug, array(), null, true );
				$scriptFooterData = json_encode( $scriptArr );
				update_option( 'minify_script_footer_js', $scriptFooterData );
			}
		endif;
	}

	public function minify_delete_header_scripts() {
		if ( get_option( 'minify_jquery_core_aktiv' ) ) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', false );
		}
	}

	/**
	 * @param $array
	 *
	 * @return object
	 */
	final public function hupaArrayToObject( $array ): object {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$array[ $key ] = self::hupaArrayToObject( $value );
			}
		}

		return (object) $array;
	}

	public function getHupaGenerateRandomId( $passwordlength = 12, $numNonAlpha = 1, $numNumberChars = 4, $useCapitalLetter = true ): string {
		$numberChars = '123456789';
		//$specialChars = '!$&?*-:.,+@_';
		$specialChars = '!$%&=?*-;.,+~@_';
		$secureChars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
		$stack        = $secureChars;
		if ( $useCapitalLetter ) {
			$stack .= strtoupper( $secureChars );
		}
		$count = $passwordlength - $numNonAlpha - $numNumberChars;
		$temp  = str_shuffle( $stack );
		$stack = substr( $temp, 0, $count );
		if ( $numNonAlpha > 0 ) {
			$temp  = str_shuffle( $specialChars );
			$stack .= substr( $temp, 0, $numNonAlpha );
		}
		if ( $numNumberChars > 0 ) {
			$temp  = str_shuffle( $numberChars );
			$stack .= substr( $temp, 0, $numNumberChars );
		}

		return str_shuffle( $stack );
	}
}

$hupa_minify_wp_loaded = HupaMinifyWpLoaded::instance();
$hupa_minify_wp_loaded->init_hupa_minify_wp_loaded();

