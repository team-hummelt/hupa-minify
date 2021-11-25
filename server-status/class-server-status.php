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
	protected $refresh_interval, $memcache_host, $memcache_port, $use_ipapi_pro, $ipapi_pro_key, $bg_color_good, $bg_color_average, $bg_color_bad, $footer_text_color, $server_load_nonce;
	
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
		if(get_option('server_status_aktiv')) {
			add_action('init', array($this, 'minify_check_limit'));
			add_action('wp_dashboard_setup', array($this, 'minify_add_dashboard'));
		}
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

	public function minify_add_dashboard()
	{
		wp_add_dashboard_widget('wp_memory_dashboard', 'Server Overview', array($this, 'minify_dashboard_output'));
	}

				public function minify_dashboard_output()
			{
				if (current_user_can('manage_options')) :?>
						<ul>
							<li><strong><?php _e('Server OS', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_server_os(); ?>&nbsp;/&nbsp;<?php echo (PHP_INT_SIZE * 8) . __('Bit OS', 'hupa-minify'); ?></span></li>
							<li><strong><?php _e('Server Software', 'hupa-minify'); ?></strong> : <span><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span></li>
							<li><strong><?php _e('Server IP', 'hupa-minify'); ?></strong> : <span><?php echo ($this->minify_validate_ip_address($this->minify_check_server_ip()) ? $this->minify_check_server_ip() : "ERROR IP096T"); ?></span></li>
							<li><strong><?php _e('Server Port', 'hupa-minify'); ?></strong> : <span><?php echo $_SERVER['SERVER_PORT']; ?></span></li>
							<li><strong><?php _e('Server Location', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_check_server_location(); ?></span></li>
							<li><strong><?php _e('Server Hostname', 'hupa-minify'); ?></strong> : <span><?php echo gethostname(); ?></span></li>
							<li><strong><?php _e('Site\'s Document Root', 'hupa-minify'); ?></strong> : <span><?php echo $_SERVER['DOCUMENT_ROOT'] . '/'; ?></span></li>
							<li><strong><?php _e('Memcached Enabled', 'hupa-minify'); ?></strong> : <span><?php echo (class_exists('Memcache') ? __('Yes', 'wp-sever-stats') : __('No', 'hupa-minify')); ?></span></li>
							<?php if ($this->isShellEnabled()) : ?>
							<li><strong><?php _e('Total CPUs', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_check_cpu_count() . ' / ' . $this->minify_check_core_count() . __('Cores', 'hupa-minify'); ?></span></li>
							<li><strong><?php _e('Total RAM', 'hupa-minify'); ?></strong> : <span><?php echo (is_numeric($this->minify_check_total_ram()) ? $this->minify_format_filesize_kB($this->minify_check_total_ram()) : $this->minify_check_total_ram()); ?></span></li>
							<li><strong><?php _e('Real Time Free RAM', 'hupa-minify'); ?></strong> : <span id="realtime_free_ram"></span></li>
							<li><strong><?php _e('Real Time RAM Usage', 'hupa-minify'); ?></strong> : <span id="realtime_ram_usage"></span></li>
							<?php endif; ?>
						<ul>
						<?php if ($this->isShellEnabled()) : ?>
						<div class="progressbar">
							<div style="border:1px solid #DDDDDD; background-color:#F9F9F9;	border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px;">
								<div id="ram-usage-upper-div" style="padding: 0px; border-width:0px; color:#FFFFFF;text-align:right; border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px; margin-top: -1px;">
									<div id="ram-usage" style="padding:2px;"></div>
								</div>
							</div>
						</div>

						<span style="line-height: 2.5em;"><strong><?php _e('Real Time CPU Load', 'hupa-minify') ?>:</strong></span>
						<div class="progressbar">
							<div style="border:1px solid #DDDDDD; background-color:#F9F9F9;	border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px;">
		            <div id="server-load-upper-div" style="padding: 0px; border-width:0px; color:#FFFFFF;text-align:right; border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px; margin-top: -1px;">
									<div id="server-load" style="padding:2px;"></div>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<?php if (class_exists('Memcache')) : ?>
							<div class="wpss_show_buttons content-center">
								<a href="<?php echo get_admin_url(); ?>admin.php?page=wpss_memcache_info" title="Checkout Memcached Info" class="wpss_btn button button-small"><?php _e('Check More Memcached Info', 'hupa-minify'); ?></a>
							</div>
						<?php endif; ?>
						<hr />
						<ul>
							<li><strong><?php _e('Database Software', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_database_software(); ?></span></li>
							<li><strong><?php _e('Database Version', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_database_version(); ?></span></li>
							<li><strong><?php _e('Maximum No. of Connections', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_database_max_no_connection(); ?></span></li>
							<li><strong><?php _e('Maximum Packet Size', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_database_max_packet_size(); ?></span></li>
							<li><strong><?php _e('Database Disk Usage', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_database_disk_usage(); ?></span></li>
							<li><strong><?php _e('Index Disk Usage', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_index_disk_usage(); ?></span></li>
						</ul>
						<div class="wpss_show_buttons content-center">
							<a href="<?php echo get_admin_url(); ?>admin.php?page=wpss_sql_info" title="Checkout More Database Info" class="wpss_btn button button-small"><?php _e('Check More Database Info', 'hupa-minify'); ?></a>
						</div>
						<hr />
						<ul>
							<li><strong><?php _e('PHP Version', 'hupa-minify'); ?></strong> : <span><?php echo PHP_VERSION; ?></span></li>
							<li><strong><?php _e('PHP Max Upload Size', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_index_disk_usage(); ?></span></li>
							<li><strong><?php _e('PHP Max Post Size', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_php_max_post_size(); ?></span></li>
							<li><strong><?php _e('PHP Max Execution Time', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_php_max_execution_time() . " " . __("sec", "hupa-minify"); ?></span></li>
							<li><strong><?php _e('PHP Short Tag', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_php_short_tag(); ?></span></li>
							<li><strong><?php _e('PHP Memory Limit', 'hupa-minify'); ?></strong> : <span><?php echo $this->minify_check_limit(); ?></span></li>
							<li><strong><?php _e('Real Time PHP Memory Usage', 'hupa-minify'); ?></strong> : <span id="mem_usage_mb"></span></li>
						</ul>
						<div class="progressbar">
							<div style="border:1px solid #DDDDDD; background-color:#F9F9F9;	border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px;">
		                        <div id="memory-load-upper-div" style="padding: 0px; border-width:0px; color:#FFFFFF;text-align:right; border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px; margin-top: -1px;">
		                        	<div id="memory-usage-pos" style="padding:2px;"></div>
								</div>
							</div>
						</div>
						<div class="wpss_show_buttons content-center">
							<a href="<?php echo get_admin_url(); ?>admin.php?page=wpss_php_info" title="Checkout More PHP Info" class="wpss_btn button button-small"><?php _e('Check More PHP Info', 'hupa-minify'); ?></a>
						</div>
						<?php if ($this->isShellEnabled()) : ?>
						<hr style="margin-top: 15px; margin-bottom: 0px;" />
						<span style="line-height: 2.5em; margin-left: auto; margin-right: auto; display: table;"><strong><?php _e('Server Uptime', 'hupa-minify') ?></strong></span>
						<div style="margin-top: 20px;">
							<div class="uptime" style="font-size: 20px;"></div>
						</div>
				<?php else : ?>

							<hr style="margin-top: 15px; margin-bottom: 15px;" />
							<p style="text-align: justify;"><strong><?php _e('Special Note', 'hupa-minify'); ?>:</strong> <?php _e('Hi, please note that PHP 
							<code>shell_exec()</code> function is either not enable in your hosting environment or not been given executable permission, 
							hence you won\'t be seeing the following results above: CPU/Core count, Real Time CPU Usage, Server Uptime, RAM details, Real Time RAM Usage. To see these details, 
							please ask your host to enable <code>shell_exec()</code> function and give it executable permission.', 'hupa-minify'); ?></p>
						<?php endif;
					endif;
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
				$db_disk_usage = __('N/A', 'wp-server-stats');
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

	public function minify_format_php_size($size)
	{
		if (!is_numeric($size)) {
			if (strpos($size, 'M') !== false) {
				$size = intval($size) * 1024 * 1024;
			} elseif (strpos($size, 'K') !== false) {
				$size = intval($size) * 1024;
			} elseif (strpos($size, 'G') !== false) {
				$size = intval($size) * 1024 * 1024 * 1024;
			}
		}
		return is_numeric($size) ? $this->minify_format_filesize($size) : $size;
	}


	public function minify_database_software()
	{
		$db_software = get_transient('wpss_db_software');
		if ($db_software === false) {
			global $wpdb;
			$db_software_query = $wpdb->get_row("SHOW VARIABLES LIKE 'version_comment'");
			$db_software_dump = $db_software_query->Value;
			if (!empty($db_software_dump)) {
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
				$php_max_post_size = __('N/A', 'wp-server-stats');
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
			$max_execute = __('N/A', 'wp-server-stats');
		}
		return $max_execute;
	}

	public function minify_php_short_tag()
	{
		if (ini_get('short_open_tag')) {
			$short_tag = __('On', 'wp-server-stats');
		} else {
			$short_tag = __('Off', 'wp-server-stats');
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
				$db_index_disk_usage = __('N/A', 'wp-server-stats');
			} else {
				$db_index_disk_usage = $this->minify_format_filesize($db_index_disk_usage);
				set_transient('wpss_db_index_disk_usage', $db_index_disk_usage, WEEK_IN_SECONDS);
			}
		}

		return $db_index_disk_usage;
	}

	public function minify_check_server_location()
	{
		$this->minify_fetch_data();
		$ipapi_pro_key = trim($this->ipapi_pro_key);
		//get the server ip
		$ip = $this->minify_check_server_ip();

		$server_location = get_transient('wpss_server_location');

		if ($server_location === false) {
			// lets validate the ip
			if ($this->minify_validate_ip_address($ip)) {
				if ($this->use_ipapi_pro == 'Yes' && !empty($ipapi_pro_key)) { // Use the pro version of IP-API query
					$query = @unserialize(file_get_contents('https://pro.ip-api.com/php/' . $ip . '?key=' . $ipapi_pro_key));
				} else { // Use the free version of IP-API
					$query = @unserialize(file_get_contents('https://ip-api.com/php/' . $ip));
				}
				if ($query && $query['status'] == 'success') {
					$server_location = $query['city'] . ', ' . $query['country'];
					set_transient('wpss_server_location', $server_location, WEEK_IN_SECONDS);
				} else {
					if (empty($query['message'])) {
						if ($this->use_ipapi_pro == 'Yes') {
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

	public function minify_fetch_data()
	{
		// assuming our wpss_settings_option entry in database's option table is already there
		// so lets try to fetch it
		$fetched_data = get_option('wpss_settings_options'); // $fetched_data will be an array

		if (!empty($fetched_data)) {

			// fetching the refresh_interval data
			if (!empty($fetched_data['refresh_interval'])) {
				$this->refresh_interval = $fetched_data['refresh_interval'];
			} else {
				$this->refresh_interval = 200; // default refresh interval is 200ms
			}

			// fetching memcache host
			if (!empty($fetched_data['memcache_host'])) {
				$this->memcache_host = $fetched_data['memcache_host'];
			} else {
				$this->memcache_host = 'localhost'; // default memcache host localhost
			}

			// fetching memcache port
			if (!empty($fetched_data['memcache_port'])) {
				$this->memcache_port = $fetched_data['memcache_port'];
			} else {
				$this->memcache_port = 11211; // default memcache port 11211
			}

			// fetching if using ip-api
			if (!empty($fetched_data['use_ipapi_pro'])) {
				$this->use_ipapi_pro = $fetched_data['use_ipapi_pro'];
			} else {
				$this->use_ipapi_pro = 'No';
			}

			// fetching the ip-api key
			if (!empty($fetched_data['ipapi_pro_key'])) {
				$this->ipapi_pro_key = $fetched_data['ipapi_pro_key'];
			} else {
				$this->ipapi_pro_key = '';
			}

			// fetching the bg_color_good
			if (!empty($fetched_data['bg_color_good'])) {
				$this->bg_color_good = $fetched_data['bg_color_good'];
			} else {
				$this->bg_color_good = "#37BF91";
			}

			// fetching the bg_color_average
			if (!empty($fetched_data['bg_color_average'])) {
				$this->bg_color_average = $fetched_data['bg_color_average'];
			} else {
				$this->bg_color_average = "#d35400";
			}

			// fetching the bg_color_bad
			if (!empty($fetched_data['bg_color_bad'])) {
				$this->bg_color_bad = $fetched_data['bg_color_bad'];
			} else {
				$this->bg_color_bad = "#e74c3c";
			}

			// fetching footer text color
			if (!empty($fetched_data['footer_text_color'])) {
				$this->footer_text_color = $fetched_data['footer_text_color'];
			} else {
				$this->footer_text_color = "#8e44ad";
			}
		} else {
			$this->refresh_interval = 200; // default refresh interval is 200ms
			$this->bg_color_good = "#37BF91";
			$this->bg_color_average = "#d35400";
			$this->bg_color_bad = "#e74c3c";
			$this->footer_text_color = "#8e44ad";
			$this->memcache_host = 'localhost';
			$this->memcache_port = 11211;
			$this->use_ipapi_pro = 'No';
			$this->ipapi_pro_key = '';
		}
	}
}
if (is_admin()) {
	$register_server_status = RegisterHupaServerStatus::instance();
	$register_server_status->server_status_init();
}

