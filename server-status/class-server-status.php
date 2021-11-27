<?php

namespace Hupa\Status;
defined( 'ABSPATH' ) or die();

/**
 * Hupa Minify Plugin
 * @package Hummelt & Partner
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */
final class RegisterHupaServerStatus {
	private static $instance;
	var $memory = false;
	// declaring the protected variables
	protected  $use_ipapi_pro;
	
	/**
	 * @return static
	 */
	public static function instance(): self {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {

	}

	public function server_status_init() {
		if(!$this->isShellEnabled()){
			update_option('server_status_aktiv', false);
		}
		if(get_option('server_status_aktiv') ) {
			add_action('init', array($this, 'minify_check_limit'));
			if(get_option('server_footer_aktiv') && !HUPA_STARTER_THEME_AKTIV){
				add_filter('admin_footer_text', array($this, 'minify_add_footer'));
			}
			add_action('admin_bar_menu', array($this, 'add_server_stat_admin_bar_menu_item'), 100);
			//JOB WARNING ADD Plugin DASHBOARD WIDGET
			add_action('wp_dashboard_setup', array($this, 'minify_server_status_add_dashboard'));
		}
	}

	//DASHBOARD WIDGET
	public function minify_server_status_add_dashboard() {
		require HUPA_MINIFY_INC . 'dashboard-widget/wp-dashboard-widget.php';
	}

	public function isShellEnabled(): bool {
		if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))))) {
			$returnVal = shell_exec('cat /proc/cpuinfo');
			if (!empty($returnVal)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function minify_add_footer($content)
	{
		if (current_user_can('manage_options')) :
			$stat = json_decode(get_option('settings_server_status'));
			//check if the content is empty or not
			if (!empty($content)) {
				$start = " | ";
			} else {
				$start = "";
			}

			if ($this->isShellEnabled()) {
				$content .= $start . '<strong style="color: ' . $stat->footer_text_color . ';">' . __('PHP Memory', 'hupa-minify') . ' : <span id="mem_usage_mb_footer"></span>'
				            . ' ' . __('of', 'hupa-minify') . ' ' . $this->minify_check_limit() . ' (<span id="memory-usage-pos-footer"></span> '
				            . __('used', 'hupa-minify') . ')</strong> | <strong style="color: ' . $stat->footer_text_color . ';">' . __('RAM', 'hupa-minify') . ' : <span id="ram_usage_footer"></span> ' . __('of', 'hupa-minify') . ' ' . (is_numeric($this->minify_check_total_ram()) ? $this->minify_format_filesize_kB($this->minify_check_total_ram()) : $this->minify_check_total_ram()) . ' (<span id="ram-usage-pos-footer"></span> ' . __('used', 'hupa-minify') . ')</strong> | <strong style="color: ' . $stat->footer_text_color . ';">' . __('CPU Load', 'hupa-minify')
				            . ': <span id="cpu_load_footer"></span></strong>';
			} else {
				$content .= $start . '<strong style="color: ' . $stat->footer_text_color . ';">' . __('Memory', 'hupa-minify') . ' : <span id="mem_usage_mb_footer"></span>'
				            . ' ' . __('of', 'hupa-minify') . ' ' . $this->minify_check_limit() . ' (<span id="memory-usage-pos-footer"></span> '
				            . __('used', 'hupa-minify') . ')</strong>';
			}
			return $content;
		endif;
	}

	public function minify_footer_template(){
		$content = '';
		if (current_user_can('manage_options')) :
			$stat = json_decode(get_option('settings_server_status'));
			//check if the content is empty or not

			if ($this->isShellEnabled()) {
				$content .= '<div class="">';
				$content .=  '<strong style="color: ' . $stat->footer_text_color . ';">' . __('PHP Memory', 'hupa-minify') . ' : <span id="mem_usage_mb_footer"></span>'
				            . ' ' . __('of', 'hupa-minify') . ' ' . $this->minify_check_limit() . ' (<span id="memory-usage-pos-footer"></span> '
				            . __('used', 'hupa-minify') . ')</strong> | <strong style="color: ' . $stat->footer_text_color . ';">' . __('RAM', 'hupa-minify') . ' : <span id="ram_usage_footer"></span> ' . __('of', 'hupa-minify') . ' ' . (is_numeric($this->minify_check_total_ram()) ? $this->minify_format_filesize_kB($this->minify_check_total_ram()) : $this->minify_check_total_ram()) . ' (<span id="ram-usage-pos-footer"></span> ' . __('used', 'hupa-minify') . ')</strong> | <strong style="color: ' . $stat->footer_text_color . ';">' . __('CPU Load', 'hupa-minify')
				            . ': <span id="cpu_load_footer"></span></strong>';
				$content .= '</div>';
			} else {
				$content .= '<strong style="color: ' . $stat->footer_text_color . ';">' . __('Memory', 'hupa-minify') . ' : <span id="mem_usage_mb_footer"></span>'
				            . ' ' . __('of', 'hupa-minify') . ' ' . $this->minify_check_limit() . ' (<span id="memory-usage-pos-footer"></span> '
				            . __('used', 'hupa-minify') . ')</strong>';
			}
			return $content;
		endif;
	}

	public function add_server_stat_admin_bar_menu_item($admin_bar) {
		if( defined( 'WPSERVERSTATS_ADMINBAR_DISABLE' ) ) {
			if( constant( 'WPSERVERSTATS_ADMINBAR_DISABLE' ) ) {
				// Do nothing as the user don't need the purge cache option
			} else {
				$admin_bar->add_menu(
					array(
						'id' => 'wpss-cache-purge',
						'title' => 'Cache leeren',
						'href' => '#'
					)
				);
			}
		} else {
			$admin_bar->add_menu(
				array(
					'id' => 'wpss-cache-purge',
					'title' => 'Cache leeren',
					'href' => '#'
				)
			);
		}
	}

	public function minify_server_os()
	{
		$server_os = get_transient('minify_server_os');

		if ($server_os === false) {
			$os_detail = php_uname();
			$just_os_name = explode(" ", trim($os_detail));
			$server_os = $just_os_name[0];
			set_transient('minify_server_os', $server_os, WEEK_IN_SECONDS);
		}

		return $server_os;
	}

	public function minify_check_cpu_count()
	{
		$cpu_count = get_transient('wpss_cpu_count');
		if ($cpu_count === false) {
			if ($this->isShellEnabled()) {
				$cpu_count = shell_exec('cat /proc/cpuinfo |grep "physical id" | sort | uniq | wc -l');
				set_transient('wpss_cpu_count', $cpu_count, WEEK_IN_SECONDS);
			} else {
				$cpu_count = 'ERROR EXEC096T';
			}
		}

		return $cpu_count;
	}

	public function minify_check_core_count()
	{
		$cpu_core_count = get_transient('wpss_cpu_core_count');
		if ($cpu_core_count === false) {
			if ($this->isShellEnabled()) {
				$cpu_core_count = shell_exec("echo \"$((`cat /proc/cpuinfo | grep cores | grep -o '[0-9]' | uniq` * `cat /proc/cpuinfo |grep 'physical id' | sort | uniq | wc -l`))\"");
				set_transient('wpss_cpu_core_count', $cpu_core_count, WEEK_IN_SECONDS);
			} else {
				$cpu_core_count = 'ERROR EXEC096T';
			}
		}

		return $cpu_core_count;
	}

	public function minify_validate_ip_address($ip): bool {
		if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
			return true; // $ip is a valid IP address
		} else {
			return false; // $ip is NOT a valid IP address
		}
	}

	public function minify_database_disk_usage()
	{
		$db_disk_usage = get_transient('wpss_db_disk_usage');
		if ($db_disk_usage === false) {
			global $wpdb;
			$db_disk_usage = 0;
			$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
			foreach ($tablesstatus as $tablestatus) {
				$db_disk_usage += $tablestatus->Data_length;
			}
			if (empty($db_disk_usage)) {
				$db_disk_usage = __('N/A', 'hupa-minify');
			} else {
				$db_disk_usage = $this->minify_format_filesize($db_disk_usage);
				set_transient('wpss_db_disk_usage', $db_disk_usage, WEEK_IN_SECONDS);
			}
		}

		return $db_disk_usage;
	}
	
	public function minify_check_server_ip(): string {
		return trim(gethostbyname(gethostname()));
	}

	public function minify_check_total_ram(): string {
		$total_ram = get_transient('wpss_total_ram');

		if ($total_ram === false) {
			if ($this->isShellEnabled()) {
				$total_ram = shell_exec("grep -w 'MemTotal' /proc/meminfo | grep -o -E '[0-9]+'");
				set_transient('wpss_total_ram', $total_ram, WEEK_IN_SECONDS);
			} else {
				$total_ram = 'ERROR EXEC096T';
			}
		}

		return trim($total_ram);
	}

	public function minify_format_filesize_kB($kiloBytes)
	{
		if (($kiloBytes / pow(1024, 4)) > 1) {
			return number_format_i18n(($kiloBytes / pow(1024, 4)), 0) . ' ' . __('PB', 'hupa-minify');
		} elseif (($kiloBytes / pow(1024, 3)) > 1) {
			return number_format_i18n(($kiloBytes / pow(1024, 3)), 0) . ' ' . __('TB', 'hupa-minify');
		} elseif (($kiloBytes / pow(1024, 2)) > 1) {
			return number_format_i18n(($kiloBytes / pow(1024, 2)), 0) . ' ' . __('GB', 'hupa-minify');
		} elseif (($kiloBytes / 1024) > 1) {
			return number_format_i18n($kiloBytes / 1024, 0) . ' ' . __('MB', 'hupa-minify');
		} elseif ($kiloBytes >= 0) {
			return number_format_i18n($kiloBytes / 1, 0) . ' ' . __('KB', 'hupa-minify');
		} else {
			return __('Unknown', 'hupa-minify');
		}
	}

	public function minify_format_filesize($bytes)
	{
		if (($bytes / pow(1024, 5)) > 1) {
			return number_format_i18n(($bytes / pow(1024, 5)), 0) . ' ' . __('PB', 'hupa-minify');
		} elseif (($bytes / pow(1024, 4)) > 1) {
			return number_format_i18n(($bytes / pow(1024, 4)), 0) . ' ' . __('TB', 'hupa-minify');
		} elseif (($bytes / pow(1024, 3)) > 1) {
			return number_format_i18n(($bytes / pow(1024, 3)), 0) . ' ' . __('GB', 'hupa-minify');
		} elseif (($bytes / pow(1024, 2)) > 1) {
			return number_format_i18n(($bytes / pow(1024, 2)), 0) . ' ' . __('MB', 'hupa-minify');
		} elseif ($bytes / 1024 > 1) {
			return number_format_i18n($bytes / 1024, 0) . ' ' . __('KB', 'hupa-minify');
		} elseif ($bytes >= 0) {
			return number_format_i18n($bytes, 0) . ' ' . __('bytes', 'hupa-minify');
		} else {
			return __('Unknown', 'hupa-minify');
		}
	}

	public function minify_check_limit(): bool|string {
		$memory_limit = ini_get('memory_limit');
		if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
			if ($matches[2] == 'G') {
				$memory_limit = $matches[1] . ' ' . 'GB'; // nnnG -> nnn GB
			} else if ($matches[2] == 'M') {
				$memory_limit = $matches[1] . ' ' . 'MB'; // nnnM -> nnn MB
			} else if ($matches[2] == 'K') {
				$memory_limit = $matches[1] . ' ' . 'KB'; // nnnK -> nnn KB
			} else if ($matches[2] == 'T') {
				$memory_limit = $matches[1] . ' ' . 'TB'; // nnnT -> nnn TB
			} else if ($matches[2] == 'P') {
				$memory_limit = $matches[1] . ' ' . 'PB'; // nnnP -> nnn PB
			}
		}
		return $memory_limit;
	}

	public function minify_check_memory_limit_cal(): int {
		return (int)ini_get('memory_limit');
	}

	public function minify_format_php_size($size)
	{
		if (!is_numeric($size)) {
			if ( str_contains( $size, 'M' ) ) {
				$size = intval($size) * 1024 * 1024;
			} elseif ( str_contains( $size, 'K' ) ) {
				$size = intval($size) * 1024;
			} elseif ( str_contains( $size, 'G' ) ) {
				$size = intval($size) * 1024 * 1024 * 1024;
			}
		}
		return is_numeric($size) ? $this->minify_format_filesize($size) : $size;
	}


	public function minify_database_software()
	{
		$db_software = get_transient('wpss_db_software');
		$software = '';
		if ($db_software === false) {
			global $wpdb;
			$db_software_dump = $wpdb->get_var("SELECT VERSION() AS version from DUAL");
			if (preg_match('/\d+(?:\.\d+)+/', $db_software_dump, $matches)) {
				$software = str_replace([$matches[0],'-','/','_'], '',$db_software_dump);
			} else {
				$db_software_query = $wpdb->get_row("SHOW VARIABLES LIKE 'version_comment'");
				$db_software_dump = $db_software_query->Value;
			}

			if (!empty($software)) {
				set_transient('wpss_db_software', $software, WEEK_IN_SECONDS);
			} elseif (!empty($db_software_dump)){
				$db_soft_array = explode(" ", trim($db_software_dump));
				$db_software = $db_soft_array[0];
				set_transient('wpss_db_software', $db_software, WEEK_IN_SECONDS);
			} else {
				$db_software = __('N/A', 'hupa-minify');
			}
		}

		return $db_software;
	}

	public function minify_php_max_post_size()
	{
		$php_max_post_size = get_transient('wpss_php_max_post_size');
		if ($php_max_post_size === false) {
			if (ini_get('post_max_size')) {
				$php_max_post_size = ini_get('post_max_size');
				$php_max_post_size = $this->minify_format_php_size($php_max_post_size);
				set_transient('wpss_php_max_post_size', $php_max_post_size, WEEK_IN_SECONDS);
			} else {
				$php_max_post_size = __('N/A', 'hupa-minify');
			}
		}

		return $php_max_post_size;
	}

	public function minify_database_version()
	{
		$db_version = get_transient('wpss_db_version');
		if ($db_version === false) {
			global $wpdb;
			$db_version_dump = $wpdb->get_var("SELECT VERSION() AS version from DUAL");
			if (preg_match('/\d+(?:\.\d+)+/', $db_version_dump, $matches)) {
				$db_version = $matches[0]; //returning the first match
				set_transient('wpss_db_version', $db_version, WEEK_IN_SECONDS);
			} else {
				$db_version = __('N/A', 'hupa-minify');
			}
		}
		return $db_version;
	}

	public function minify_php_max_execution_time()
	{
		if (ini_get('max_execution_time')) {
			$max_execute = ini_get('max_execution_time');
		} else {
			$max_execute = __('N/A', 'hupa-minify');
		}
		return $max_execute;
	}

	public function minify_php_short_tag()
	{
		if (ini_get('short_open_tag')) {
			$short_tag = __('On', 'hupa-minify');
		} else {
			$short_tag = __('Off', 'hupa-minify');
		}
		return $short_tag;
	}

	public function minify_database_max_no_connection()
	{
		$db_max_connection = get_transient('wpss_db_max_connection');
		if ($db_max_connection === false) {
			global $wpdb;
			$connection_max_query = $wpdb->get_row("SHOW VARIABLES LIKE 'max_connections'");
			$db_max_connection = $connection_max_query->Value;
			if (empty($db_max_connection)) {
				$db_max_connection = __('N/A', 'hupa-minify');
			} else {
				$db_max_connection = number_format_i18n($db_max_connection, 0);
				set_transient('wpss_db_max_connection', $db_max_connection, WEEK_IN_SECONDS);
			}
		}

		return $db_max_connection;
	}

	public function minify_database_max_packet_size()
	{
		$db_max_packet_size = get_transient('wpss_db_max_packet_size');

		if ($db_max_packet_size === false) {
			global $wpdb;
			$packet_max_query = $wpdb->get_row("SHOW VARIABLES LIKE 'max_allowed_packet'");
			$db_max_packet_size = $packet_max_query->Value;
			if (empty($db_max_packet_size)) {
				$db_max_packet_size = __('N/A', 'hupa-minify');
			} else {
				$db_max_packet_size = $this->minify_format_filesize($db_max_packet_size);
				set_transient('wpss_db_max_packet_size', $db_max_packet_size, WEEK_IN_SECONDS);
			}
		}
		return $db_max_packet_size;
	}

	public function minify_index_disk_usage()
	{
		$db_index_disk_usage = get_transient('wpss_db_index_disk_usage');
		if ($db_index_disk_usage === false) {
			global $wpdb;
			$db_index_disk_usage = 0;
			$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
			foreach ($tablesstatus as $tablestatus) {
				$db_index_disk_usage += $tablestatus->Index_length;
			}
			if (empty($db_index_disk_usage)) {
				$db_index_disk_usage = __('N/A', 'hupa-minify');
			} else {
				$db_index_disk_usage = $this->minify_format_filesize($db_index_disk_usage);
				set_transient('wpss_db_index_disk_usage', $db_index_disk_usage, WEEK_IN_SECONDS);
			}
		}
		return $db_index_disk_usage;
	}

	public function minify_check_server_location()
	{
		$stat = json_decode(get_option('settings_server_status'));
		$ipapi_pro_key = trim($stat->ipapi_pro_key);
		//get the server ip
		$ip = $this->minify_check_server_ip();
		if(get_option('ip_api_aktiv')){
			$server_location = get_transient('wpss_server_location');
		} else {
			$server_location = 'nicht aktiviert';
		}

		if ($server_location === false) {
			// lets validate the ip
			if ($this->minify_validate_ip_address($ip)) {
				if ($stat->use_ipapi_pro && !empty($ipapi_pro_key)) { // Use the pro version of IP-API query
					$query = @unserialize(file_get_contents('https://pro.ip-api.com/php/' . $ip . '?key=' . $ipapi_pro_key));
				} else { // Use the free version of IP-API
					$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
				}
				if ($query && $query['status'] == 'success') {
					$server_location = $query['city'] . ', ' . $query['country'];
					set_transient('wpss_server_location', $server_location, WEEK_IN_SECONDS);
				} else {
					if (empty($query['message'])) {
						if ($stat->use_ipapi_pro) {
							$server_location = 'You\'ve provided a wrong IP-API Pro Key';
						} else {
							$server_location = $query['status'];
						}
					} else {
						$server_location = $query['message'];
					}
				}
			} else {
				$server_location = "ERROR IP096T";
			}
		}

		return $server_location;
	}

	public function minify_check_free_ram(): string {
		if ($this->isShellEnabled()) {
			$free_ram = shell_exec("grep -w 'MemFree' /proc/meminfo | grep -o -E '[0-9]+'");

			if( !is_null( $this->minify_check_ram_cache() ) || !is_null( $this->minify_check_ram_buffer() ) ) {
				$ram_cache = is_null( $this->minify_check_ram_cache() ) ? 0 : (int) $this->minify_check_ram_cache();
				$ram_buffer = is_null( $this->minify_check_ram_buffer() ) ? 0 : (int) $this->minify_check_ram_buffer();
				$free_ram_final = (int) $free_ram + $ram_cache + $ram_buffer;
			} else {
				$free_ram_final = $free_ram;
			}
		} else {
			$free_ram_final = 'ERROR EXEC096T';
		}

		return trim($free_ram_final);
	}

	public function minify_check_ram_cache(): string {
		if ($this->isShellEnabled()) {
			$ram_cache = shell_exec("grep -w 'Cached' /proc/meminfo | grep -o -E '[0-9]+'");
		} else {
			$ram_cache= 'ERROR EXEC096T';
		}

		return trim($ram_cache);
	}

	public function minify_check_ram_buffer(): string {
		if ($this->isShellEnabled()) {
			$ram_buffer = shell_exec("grep -w 'Buffers' /proc/meminfo | grep -o -E '[0-9]+'");
		} else {
			$ram_buffer= 'ERROR EXEC096T';
		}

		return trim($ram_buffer);
	}

	/**
	 * Function that will check if value is a valid HEX color.
	 */
	public function minify_check_color($value): bool {

		if (preg_match('/^#[a-f0-9]{6}$/i', $value)) { // if user insert a HEX color with #
			return true;
		}

		return false;
	}
}
global $hupa_server_class;
if (is_admin()) {
	$hupa_server_class = RegisterHupaServerStatus::instance();
	$hupa_server_class->server_status_init();
}

