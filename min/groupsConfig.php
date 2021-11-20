<?php
defined('ABSPATH') or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */


$method = filter_input(INPUT_GET, 'g', FILTER_SANITIZE_STRING);

$cssArr = [];
$scrArr = [];
switch ($method){
    case 'css':
        $path = json_decode(get_option('minify_style_css'));
        foreach ($path as $tmp){
            $cssArr[] = $tmp->css;
        }
        break;
    case 'js':
        $path = json_decode(get_option('minify_script_js'));
        foreach ($path as $tmp){
            $scrArr[] = $tmp->js;
        }
        break;
}

return array(
    'js'=> $scrArr,
    'css'=> $cssArr,
);





