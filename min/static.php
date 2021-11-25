<?php
require 'static/lib.php';
require_once(ABSPATH . 'wp-admin/includes/file.php');


$query = "&b=scripts&f=wp-content/plugins/bs-formular/assets/public/js/bs-formular-public.js";
$cache_time = Minify\StaticService\get_cache_time();
$static_uri = "";
$type = "js";
//$static_uri = "?minify=static&s=$cache_time&g=js&z=.js";
$uri = Minify\StaticService\build_uri($static_uri, $query, $type);

//echo $uri;




//print_r($m);

 //$scriptName = 'static/gen.php';
// $requestUri = '/path/to/minify/static/1467084520/b=path/to/minify&f=quick-test.js'
//$uri = Minify\StaticService\build_uri($static_uri, $query, $type);
//echo $uri;
//Minify\StaticService\flush_cache();

