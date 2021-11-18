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

	$table_name = $wpdb->prefix . 'hupa_min_source';
		$charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE $table_name (
        id int(12) NOT NULL,
        type varchar(12) NOT NULL,
        aktiv tinyint(1) NOT NULL DEFAULT 1,
        path varchar(255) NOT NULL,
        source varchar(255) NOT NULL,
        src_id varchar(255) NOT NULL,
        version varchar(128) NULL DEFAULT 'unbekannt',
        min_group varchar(64) NOT NULL,
        group_aktiv mediumint(1) NOT NULL DEFAULT 1,
        filemtime varchar(24) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

	 	$table_name = $wpdb->prefix . 'hupa_min_settings';
		$charset_collate = $wpdb->get_charset_collate();
	    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        aktiv tinyint(1) NOT NULL DEFAULT 0,
        sub_folder varchar(128) NOT NULL DEFAULT '',
        css_aktiv tinyint(1) NOT NULL DEFAULT 0,
        js_aktiv tinyint(1) NOT NULL DEFAULT 0,
        html_aktiv tinyint(1) NOT NULL DEFAULT 0,
        groups_aktiv tinyint(1) NOT NULL DEFAULT 1,
        debug_aktiv tinyint(1) NOT NULL DEFAULT 0,
        cache_aktiv tinyint(1) NOT NULL DEFAULT 1,
        cache_type tinyint(2) NOT NULL DEFAULT 0,
        settings_select tinyint(2) NOT NULL DEFAULT 2, 
        settings_entwicklung text NOT NULL,
        settings_production text NOT NULL,
        css_bubble_import tinyint(1) NOT NULL DEFAULT 0,
       PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

	apply_filters('minify_set_default_settings', null);
	update_option("jal_hupa_minify_db_version", HUPA_MINIFY_PLUGIN_DB_VERSION);
}

function hupaMinifyPluginUpdateDBCheck()
{
	if (get_option('jal_hupa_minify_db_version') !== HUPA_MINIFY_PLUGIN_DB_VERSION) {
		hupa_minify_jal_install();
	}
}

add_action('minify_plugin_update_dbCheck', 'hupaMinifyPluginUpdateDBCheck');
//add_action('hupa_minify_plugin_create_db', 'hupa_minify_jal_install', false);
