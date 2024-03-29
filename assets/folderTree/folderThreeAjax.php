<?php
defined( 'ABSPATH' ) or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
class HupaMinifyTreeView {

	public $files;
	public $folder;
	public $dir;

	/**
	 * @param $path
	 */
	function __construct( $path ) {

		$files = array();
		if ( file_exists( $path ) ) {
			if ( $path[ strlen( $path ) - 1 ] == '/' ) {
				$this->folder = $path;
			} else {
				$this->folder = $path . '/';
			}

			$this->dir = opendir( $path );
			while ( ( $file = readdir( $this->dir ) ) != false ) {
				$this->files[] = $file;
			}
			closedir( $this->dir );
		}
	}

	function create_tree() {

		if ( $this->files && count( $this->files ) > 2 ) {
			natcasesort( $this->files );
			$list = '<ul class="filetree" style="display: none;">';
			// Group folders first
			foreach ( $this->files as $file ) {
				if ( file_exists( $this->folder . $file ) && $file != '.' && $file != '..' && is_dir( $this->folder . $file ) ) {
					$root = htmlentities($this->folder . $file );
					$a = strlen(htmlentities(HUPA_MINIFY_THEME_ROOT));
					$e = strlen($root);
					$selectPath = substr($root,$a,$e) . DIRECTORY_SEPARATOR;
					$list .= '<li class="folder collapsed"><a data-folder="'.$selectPath.'" href="#" rel="' . htmlentities( $this->folder . $file ) . '/">' . htmlentities( $file ) . '</a></li>';
				}
			}
			// Group all files
			foreach ( $this->files as $file ) {
				if ( file_exists( $this->folder . $file ) && $file != '.' && $file != '..' && ! is_dir( $this->folder . $file ) ) {
					//JOB FILES AUSGEBLENDET
					//$ext = preg_replace('/^.*\./', '', $file);
					//$list .= '<li onclick="openModal(\''.$this->folder.$file.'\', \''.$file.'\')" class="file ext_' . $ext . '"><a href="#" rel="' . htmlentities( $this->folder . $file ) . '">' . htmlentities( $file ) . '</a></li>';
				}
			}
			$list .= '</ul>';
			return $list;
		}
	}
}

$path =  $_REQUEST['dir'];
$tree = new HupaMinifyTreeView( $path );
$responseJson = $tree->create_tree();
