<?php
/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

defined( 'ABSPATH' ) or die();

function hupa_minify_jal_install() {
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;

	 	$table_name = $wpdb->prefix . 'min_settings_hp';
	/*	$charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL,
        betreff varchar(128) NULL,
        email_at varchar(50) NOT NULL,
        abs_ip varchar(50) NOT NULL,
        message text NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

	 */

	update_option("jal_hupa_minify_db_version", HUPA_MINIFY_PLUGIN_DB_VERSION);
}

function hupa_minify_plugin_update_dbCheck()
{
	if (get_option('jal_hupa_minify_db_version') != HUPA_MINIFY_PLUGIN_DB_VERSION) {
		hupa_minify_jal_install();
	}
}

add_action('minify_plugin_update_dbCheck', 'hupa_minify_plugin_update_dbCheck', false);
add_action('hupa_minify_plugin_create_db', 'hupa_minify_jal_install', false);
