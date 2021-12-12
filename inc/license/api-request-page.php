<?php
defined('ABSPATH') or die();

/**
 * REGISTER HUPA MINIFY
 * @package Hummelt & Partner HUPA Minify
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $minify_license_exec;
$data = json_decode(file_get_contents("php://input"));

if($data->make_id == 'make_exec'){
	global $minify_license_exec;
	$makeJob = $minify_license_exec->make_api_exec_job($data);
	$backMsg =  [
		'msg' => $makeJob->msg,
		'status' => $makeJob->status,
	];
	echo json_encode($backMsg);
	exit();
}

if($data->client_id !== get_option('hupa_minify_client_id')){
    $backMsg =  [
        'client_id' => get_option('hupa_minify_client_id'),
        'reply' => 'falsche Client ID',
        'status' => false,
    ];
    echo json_encode($backMsg);
    exit();
}
require_once ABSPATH . 'wp-admin/includes/plugin.php';
switch ($data->make_id) {
    case '1':
        $message = json_decode($data->message);
        $backMsg =  [
            'client_id' => get_option('hupa_minify_client_id'),
            'reply' => 'Plugin deaktiviert',
            'status' => true,
        ];

        update_option('hupa_minify_message',$message->msg);
        delete_option('hupa_minify_product_install_authorize');
        delete_option('hupa_minify_client_id');
        delete_option('hupa_minify_client_secret');
	    deactivate_plugins( HUPA_MINIFY_SLUG_PATH );
	    set_transient('show_minify_lizenz_info', true, 5);
        break;
    case'send_versions':
        $backMsg = [
            'status' => true,
            'theme_version' => 'v'.HUPA_MINIFY_PLUGIN_VERSION,
        ];
        break;
    default:
        $backMsg = [
          'status' => false
        ];
}

$response = new stdClass();
if($data) {
    echo json_encode($backMsg);
}
