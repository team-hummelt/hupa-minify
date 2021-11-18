<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

defined( 'ABSPATH' ) or die();

if(current_user_can('administrator')) {
	header( 'Content-Type: text/html' );
	phpinfo();
}