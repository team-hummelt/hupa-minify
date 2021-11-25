<?php

namespace Minifi\ScssPhp;
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

defined( 'ABSPATH' ) or die();

use Exception;
use Leafo\ScssPhp\Formatter\Compact;
use Leafo\ScssPhp\Formatter\Compressed;
use Leafo\ScssPhp\Formatter\Crunched;
use Leafo\ScssPhp\Formatter\Debug;
use Leafo\ScssPhp\Formatter\Expanded;
use Leafo\ScssPhp\Formatter\Nested;
use Leafo\ScssPhp\Compiler;

require HUPA_MINIFY_PLUGIN_DIR . '/scssphp/autoload.php';

class HupaMinifyScssPlugin {
	private static $instance;
	protected string $in_dir;
	protected string $out_dir;
	protected string $cache_dir;
	protected string $formatter;
	protected string $map_option;
	protected string $line_comments;
	protected string $scss_file_name;
	protected string $css_file_name;
	protected string $tmp_css;
	protected string $destination_dir;
	protected string $destination_uri;
	protected string $regExUriPath = '/(wp-content.+|wp-include.+)/i';
	protected string $regExMin = '@\.min\.@';
	protected array $parsedFiles;

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
		$this->in_dir        = get_option( 'minify_scss_source' );
		$this->out_dir       = get_option( 'minify_scss_destination' );
		$this->formatter     = get_option( 'minify_scss_formatter' );
		$this->map_option    = get_option( 'scss_map_aktiv' );
		$this->line_comments = get_option( 'line_comments_aktiv' );
		$tmp                 = sys_get_temp_dir();
		$this->tmp_css       = substr( $tmp, strrpos( $tmp, '/' ) );
	}

	/**
	 * @throws Exception
	 */
	public function compileFile() {


		$source_dir      = HUPA_MINIFY_THEME_ROOT . $this->in_dir;
		$destination_dir = HUPA_MINIFY_THEME_ROOT . $this->out_dir;
		$extension = '.css';
		if ( ! is_dir( $source_dir ) ) {
			return null;
		}
		if ( ! $this->check_if_dir( $destination_dir ) ) {
			return null;
		}

		$src = array_diff( scandir( $source_dir ), array( '..', '.' ) );
		if ( $src ) {
			foreach ( $src as $tmp ) {
				$pi = pathinfo( $source_dir . $tmp );

				if ( $pi['extension'] === 'scss' ) {
					$this->scss_file_name  = $pi['basename'];

					if($this->formatter == 'compressed' || $this->formatter == 'crunched') {
						$extension = '.min.css';
					} else {
						$extension = '.css';
					}

					$this->css_file_name   = $pi['filename'] . $extension;
					$cssDestination        = $destination_dir . $this->css_file_name;
					$source                = $source_dir . $pi['basename'];
					$this->destination_dir = $destination_dir;
					preg_match( $this->regExUriPath, $destination_dir, $matches );
					if ( ! $matches ) {
						continue;
					}
					$this->destination_uri = site_url() . '/'. str_replace('\\','/',$matches[0]) ;
					$this->minifiyCompiler( $source, $cssDestination );
					//file_put_contents($cssDestination, $compile, LOCK_EX);
				}
			}
		}
	}

	protected function check_if_dir( $dir ): bool {
		if ( ! is_dir( $dir ) ) {
			if ( ! mkdir( $dir, 0777, true ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function minifiyCompiler( $source, $out = null ) {

		//weiter laufen, auch wenn der Benutzer das Skript durch Schließen des Browsers, des Terminals usw. "stoppt".
		ignore_user_abort( true );
		// Skriptlaufzeit auf unbegrenzt setzen
		set_time_limit( 0 );
		$vars = array();
		// Compiler Instance
		$scssCompiler = new Compiler();
		//@Import Path
		$pi = pathinfo( $source );
		$scssCompiler->addImportPath( $pi['dirname'] . '/' );
		//Genauigkeit
		$scssCompiler->setNumberPrecision( 10 );

		//Format Ausgabe
		switch ( $this->formatter ) {
			case 'compact':
				$scssCompiler->setFormatter( Compact::class );
				break;
			case 'nested':
				$scssCompiler->setFormatter( Nested::class );
				break;
			case 'compressed':
				$scssCompiler->setFormatter( Compressed::class );
				break;
			case 'expanded':
				$scssCompiler->setFormatter( Expanded::class );
				break;
			case 'crunched':
				$scssCompiler->setFormatter( Crunched::class );
				break;
			case 'debug':
				$scssCompiler->setFormatter( Debug::class );
				break;
		}

		if ( $this->map_option ) {
			switch ( get_option( 'minify_scss_map_option' ) ) {
				case 'map_file':
					$scssCompiler->setSourceMap( Compiler::SOURCE_MAP_FILE );
					$scssCompiler->setSourceMapOptions( array(
						'sourceMapWriteTo'  => $this->destination_dir . str_replace( "/", "_", $this->css_file_name ) . ".map",
						// relative or full url to the above .map file
						'sourceMapURL'      => $this->destination_uri . str_replace( "/", "_", $this->css_file_name ) . ".map",
						// (optional) relative or full url to the .css file
						'sourceMapFilename' => $this->css_file_name,
						// url location of .css file
						// partial path (server root) removed (normalized) to create a relative url
						'sourceMapBasepath' => HUPA_MINIFY_ROOT_PATH,
					));
					break;
				case 'map_inline':
					$scssCompiler->setSourceMap( Compiler::SOURCE_MAP_INLINE );
					break;
			}
		} else {
			$scssCompiler->setSourceMap( Compiler::SOURCE_MAP_NONE );
		}

		if ( $this->line_comments ) {
			$scssCompiler->setLineNumberStyle( Compiler::LINE_COMMENTS );
		}
		$scssCompiler->setVariables( $vars );

		$compiled = $scssCompiler->compile( file_get_contents( $source ), $source );
		if ( $out !== null ) {
			return file_put_contents( $out, $compiled );
		}

		return $compiled;
	}
}

if ( MINIFY_SCSS_COMPILER_AKTIV && is_admin() ) {
	$sccs_compiler = HupaMinifyScssPlugin::instance();
	try {
		$sccs_compiler->compileFile();
	} catch ( Exception $e ) {
	}
}

