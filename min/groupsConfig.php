<?php
defined( 'ABSPATH' ) or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

$method       = filter_input( INPUT_GET, 'g', FILTER_UNSAFE_RAW );
$getFile      = filter_input( INPUT_GET, 'f', FILTER_UNSAFE_RAW );
$location     = filter_input( INPUT_GET, 'l', FILTER_UNSAFE_RAW );
$file         = filter_input( INPUT_GET, 'p', FILTER_UNSAFE_RAW );
$cssArr       = [];
$scrArr       = [];
$singleCss    = [];
$singleSrc    = [];
$singleMinSrc = [];
$singleMinCss = [];
$footerSrcArr = [];

switch ( $method ) {
    case 'js':
    case 'singleJs':
        $min_serveOptions['maxAge'] = 86400 * 7;
        break;
    case 'css':
        $min_serveOptions['contentTypeCharset'] = 'iso-8859-1';
        break;
}


switch ( $method ) {
    case 'singleJs':
        $scrArr[]  = $file;
        $singleSrc = new Minify_Source( array(
            'filepath' => $file,
        ) );
        break;
    case 'singleMinJs':
        $singleMinSrc = new Minify_Source( array(
            'filepath' => $file,
            'minifier' => 'Minify::nullMinifier',
        ) );
        break;
    case'js':
        $path = json_decode( get_option( 'minify_script_footer_js' ) );
        foreach ( $path as $tmp ) {
            $scrArr[] = $tmp->js;
        }
        break;
    case 'singleMinCss':
        $singleMinCss = new Minify_Source( array(
            'filepath' => $file,
            'minifier' => 'Minify::nullMinifier',
        ) );
        break;
    case 'singleCss':
        $cssArr[] = $file;
        break;
    case 'css':
        $path = json_decode( get_option( 'minify_style_header_css' ) );
        foreach ( $path as $tmp ) {
            $cssArr[] = $tmp->css;
        }
        break;
    case'scss':
        /*  $singleMinCss = new Minify_ScssCssSource($file);
          $scss = new Minify_ScssCssSource();*/
        $cssArr[] = $file;
        break;
}

return array(
    'singleJs'     => $singleSrc,
    'singleMinJs'  => $singleMinSrc,
    'singleMinCss' => $singleMinCss,
    'singleCss'    => $cssArr,
    'js'           => $scrArr,
    'css'          => $cssArr,
    'scss'         => $cssArr
);





