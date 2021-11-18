<?php
namespace Hupa\Minify;
use stdClass;

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

defined('ABSPATH') or die();


if (!class_exists('HupaMinifyFilter')) {
	add_action( 'plugin_loaded', array( 'Hupa\\Minify\\HupaMinifyFilter', 'init' ), 0 );

	class HupaMinifyFilter {
		//STATIC INSTANCE
		private static $instance;
		private string $table_settings = 'hupa_min_settings';
		private string $table_source = 'hupa_min_source';

		/**
		 * @return static
		 */
		public static function init(): self
		{
			if (is_null(self::$instance)) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct()
		{
			//Render Menu Select
			add_filter('minify_set_default_settings', array($this, 'minifySetDefaultSettings'));
			// GET SETTINGS
			add_filter('get_hupa_minify_settings', array($this, 'HupaMinifySettingsByArgs'));

			//SOURCES
			add_filter('set_minify_source', array($this, 'hupaMinifySetMinSource'));
			add_filter('update_minify_source', array($this, 'hupaMinifyUpdateMinSource'));
		}


		public function minifySetDefaultSettings(){
			$settings = $this->hupa_minify_default_settings();
			$this->hupaMinifySetDefaultSettings($settings);
		}

		public function hupaMinifySetMinSource($record):object{
			$return = new stdClass();
			$return->status = false;
			global $wpdb;
			$table = $wpdb->prefix . $this->table_source;

			$wpdb->insert(
				$table,
				array(
					'type' => $record->type,
					'aktiv' => $record->aktiv,
					'path' => $record->path,
					'source' => $record->source,
					'src_id' => $record->src_id,
					'version' => $record->version,
					'min_group' => $record->min_group,
					'group_aktiv' => $record->group_aktiv,
					'filemtime' => $record->filemtime
				),
				array('%d', '%d', '%s','%s', '%s', '%s', '%d', '%d', '%s')
			);

			if (!$wpdb->insert_id) {
				$return->status = false;
				$return->msg = 'Daten konnten nicht gespeichert werden!';
				$return->id = false;
				return $return;
			}
			$return->status = true;
			$return->msg = 'Daten gespeichert!';
			$return->id = $wpdb->insert_id;

			return $return;
		}

		public function hupaMinifyUpdateMinSource($record):object{
			$return = new stdClass();
			$return->status = false;
			global $wpdb;
			$table = $wpdb->prefix . $this->table_source;
			$id = HUPA_MINIFY_SETTINGS_ID;
			$wpdb->update(
				$table,
				array(
					'type' => $record->type,
					'aktiv' => $record->aktiv,
					'path' => $record->path,
					'source' => $record->source,
					'src_id' => $record->src_id,
					'version' => $record->version,
					'min_group' => $record->min_group,
					'group_aktiv' => $record->group_aktiv,
					'filemtime' => $record->filemtime
				),
				array('id' => $id ),
				array('%d', '%d', '%s','%s', '%s', '%s', '%d', '%d', '%s'),
				array('%d')
			);

			if (!$wpdb->insert_id) {
				$return->status = false;
				$return->msg = 'Daten konnten nicht gespeichert werden!';
				$return->id = false;
				return $return;
			}
			$return->status = true;
			$return->msg = 'Daten gespeichert!';
			$return->id = $wpdb->insert_id;

			return $return;
		}


		protected function hupa_minify_default_settings():object {

			$root =explode('/',HUPA_MINIFY_ROOT_PATH);
			$docRoot = explode('/',$_SERVER['DOCUMENT_ROOT']);
			$diff = array_diff($root,$docRoot);
			$diff ? $subFolder = implode('/',$diff) : $subFolder = '';

			$settings = [
				'aktiv' => 1,
				'sub_folder' => $subFolder,
				'css_aktiv' => 0,
				'js_aktiv' => 0,
				'html_aktiv' => 0,
				'groups_aktiv' => 1,
				'debug_aktiv' => 0,
				'cache_aktiv' => 1,
				'cache_type' => 0,
				'settings_select' => 1,
				'settings_entwicklung' => json_encode([
					'min_allowDebugFlag' => true,
					'min_errorLogger' => true,
					'min_cachePath' => 'C:\\WINDOWS\\Temp',
					'max-Age' => 0
				]),
				'settings_production' => json_encode( [
				'min_allowDebugFlag' => false,
				'min_errorLogger' => false,
				'min_cachePath' => '/tmp',
				'max-Age' => 86400
			]),
				'css_bubble_import' => 0
			];

			return (object) $settings;
		}

		protected function hupaMinifySetDefaultSettings($record):object
		{
			$return = new stdClass();
			$return->status = false;
			$ifSettings = $this->HupaMinifySettingsByArgs();
			if($ifSettings->status){
				return  $return;
			}

			global $wpdb;
			$table = $wpdb->prefix . $this->table_settings;
			$wpdb->insert(
				$table,
				array(
				'sub_folder' => $record->sub_folder,
				'settings_entwicklung' => $record->settings_entwicklung,
				'settings_production' => $record->settings_production
				),
				array('%s', '%s', '%s')
			);

			if (!$wpdb->insert_id) {
				$return->status = false;
				$return->msg = 'Daten konnten nicht gespeichert werden!';
				$return->id = false;
				return $return;
			}
			$return->status = true;
			$return->msg = 'Daten gespeichert!';
			$return->id = $wpdb->insert_id;

			return $return;
		}

		public function HupaMinifySettingsByArgs($args = null, $fetchMethod = true): object
		{
			global $wpdb;
			$return = new stdClass();
			$return->status = false;
			$fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
			$table = $wpdb->prefix . $this->table_settings;
			$result = $wpdb->$fetch("SELECT * FROM {$table} {$args}");
			if (!$result) {
				return $return;
			}

			$return->status = true;
			$return->record = $result;

			return $return;
		}

	}//endClass
}