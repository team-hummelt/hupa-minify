<?php
defined( 'ABSPATH' ) or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner HUPA Minify
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

$responseJson         = new stdClass();
$record               = new stdClass();
$responseJson->status = false;
$data                 = '';
$method               = filter_input( INPUT_POST, 'method', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH );

switch ($method) {
    case 'save_license_data':
        $client_id = filter_input( INPUT_POST, 'client_id', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH );
        $client_secret = filter_input( INPUT_POST, 'client_secret', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH );

        if(strlen($client_id) !== 12 || strlen($client_secret) !== 36) {
            $responseJson->status        = false;
            $responseJson->msg = 'Client ID oder Client secret sind nicht bekannt!';
            return;
        }

        if(get_option('hupa_minify_product_install_authorize')) {
            $responseJson->status = true;
            $responseJson->if_authorize = true;
            return;
        }

	    update_option('minify_license_url', site_url());
        if(!get_option('hupa_server_url')){
            update_option('hupa_server_url','https://start.hu-ku.com/theme-update/api/v2/');
        }

        update_option( "hupa_minify_client_id", $client_id );
        update_option( "hupa_minify_client_secret", $client_secret );

        $responseJson->status = true;
        $responseJson->send_url = apply_filters('get_hupa_minify_api_urls', 'authorize_url');
        $responseJson->if_authorize = get_option('hupa_minify_product_install_authorize');

        break;
}
