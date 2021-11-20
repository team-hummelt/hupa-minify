<?php
/**
 * Sets up MinApp controller and serves files
 *
 * DO NOT EDIT! Configure this utility via config.php and groupsConfig.php
 *
 * @package Minify
 */


use Minify\App;

$app = (require __DIR__ . '/bootstrap.php');
/* @var App $app */
$app->runServer();

