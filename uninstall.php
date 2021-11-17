<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option("jal_hupa_minify_db_version");

delete_option("hupa_minify_product_install_authorize");
delete_option("hupa_minify_client_id");
delete_option("hupa_minify_client_secret");
delete_option("hupa_minify_message");
delete_option("hupa_minify_access_token");


