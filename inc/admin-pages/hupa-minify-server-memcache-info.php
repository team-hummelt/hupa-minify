<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $hupa_server_class;
$stat = json_decode(get_option('settings_server_status'));
?>
<div class="wp-bs-starter-wrapper">
	<div class="container">
		<div id="minifyThemeRoot" data-folder="<?= HUPA_MINIFY_THEME_ROOT ?>" class="card card-license shadow-sm bg-light">
			<h5 class="card-header d-flex align-items-center bg-hupa py-4">
				<i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
				HUPA&nbsp; <?= __( 'Memcache', 'hupa-minify' ) ?> </h5>
			<div class="card-body pb-4" style="min-height: 72vh">
				<div class="d-flex align-items-center">
					<h5 class="card-title"><i
							class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Memcached Information', 'hupa-minify' ) ?>
						<small class="small d-block mt-2">
							<?php _e('This page will show you the in-depth information about your memcache server', 'hupa-minify'); ?>
						</small>
					</h5>
				</div>
				<hr>
				<?php
				$memcachedinfo = '';
				$uptime = '';
				$cache_hit = '';
				$cache_miss = '';
				if (class_exists('Memcache')) {
				$memcached_obj = new Memcache;

				$memcached_obj->addServer($stat->memcache_host, $stat->memcache_port);

				$memcachedinfo = $memcached_obj->getStats();

				if (!empty($memcachedinfo)) {
                    if($memcachedinfo['get_hits']){
	                    $cache_hit = (($memcachedinfo['get_hits'] / $memcachedinfo['cmd_get']) * 100);
	                    $cache_hit = round($cache_hit, 2);
                    } else {
                        $cache_hit = 0;
                    }
					$cache_miss = 100 - $cache_hit;
					$usage = round((($memcachedinfo['bytes'] / $memcachedinfo['limit_maxbytes']) * 100), 2);
					$uptime = number_format_i18n(($memcachedinfo['uptime'] / 60 / 60 / 24));
				}
				?>
					<div class="wrap wpss_info">
					<table class="widefat table table-bordered table-striped">
							<thead>
								<tr>
									<th><?php _e('Variable Name', 'hupa-minify'); ?></th>
									<th><?php _e('Value', 'hupa-minify'); ?></th>
									<th><?php _e('Description', 'hupa-minify'); ?></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td><?php _e('Variable Name', 'hupa-minify'); ?></td>
									<td><?php _e('Value', 'hupa-minify'); ?></td>
									<td><?php _e('Description', 'hupa-minify'); ?></td>
								</tr>
							</tfoot>
							<tbody>
								<tr>
									<td class="e"><?php _e('pid', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $memcachedinfo['pid']; ?></td>
									<td class="v"><?php _e('Process ID', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('uptime', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $uptime; ?></td>
									<td class="v"><?php _e('Number of days since the process was started', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('version', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $memcachedinfo['version']; ?></td>
									<td class="v"><?php _e('Memcached Version', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('usage_user', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $memcachedinfo['usage_user']; ?></td>
									<td class="v"><?php _e('Number of seconds the cpu has devoted to the process as the user', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('usage_system', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $memcachedinfo['usage_system']; ?></td>
									<td class="v"><?php _e('Number of seconds the cpu has devoted to the process as the system', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('curr_items', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['curr_items']); ?></td>
									<td class="v"><?php _e('Total number of items currently in memcached', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('total_items', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['total_items']); ?></td>
									<td class="v"><?php _e('Total number of items that have passed through memcached', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('bytes', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $hupa_server_class->minify_format_filesize($memcachedinfo['bytes']); ?></td>
									<td class="v"><?php _e('Memory size currently used by <code>curr_items</code>', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('limit_maxbytes', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $hupa_server_class->minify_format_filesize($memcachedinfo['limit_maxbytes']); ?></td>
									<td class="v"><?php _e('Maximum memory size allocated to memcached', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('curr_connections', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['curr_connections']); ?></td>
									<td class="v"><?php _e('Total number of open connections to memcached', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('total_connections', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['total_connections']); ?></td>
									<td class="v"><?php _e('Total number of connections opened since memcached started running', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('connection_structures', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['connection_structures']); ?></td>
									<td class="v"><?php _e('Number of connection structures allocated by the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cmd_get', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cmd_get']); ?></td>
									<td class="v"><?php _e('Total GET commands issued to the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cmd_set', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cmd_set']); ?></td>
									<td class="v"><?php _e('Total SET commands issued to the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cmd_flush', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cmd_flush']); ?></td>
									<td class="v"><?php _e('Total FLUSH commands issued to the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('get_hits', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['get_hits']) . '(' . $cache_hit . '%)'; ?></td>
									<td class="v"><?php _e('Total number of times a GET command was <strong>able</strong> to retrieve and return data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('get_misses', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['get_misses']) . '(' . $cache_miss . '%)'; ?></td>
									<td class="v"><?php _e('Total number of times a GET command was <strong>unable</strong> to retrieve and return data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('delete_hits', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['delete_hits']); ?></td>
									<td class="v"><?php _e('Total number of times a DELETE command was <strong>able</strong> to delete data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('delete_misses', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['delete_misses']); ?></td>
									<td class="v"><?php _e('Total number of times a DELETE command was <strong>unable</strong> to delete data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('incr_hits', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['incr_hits']); ?></td>
									<td class="v"><?php _e('Total number of times a INCR command was <strong>able</strong> to increment a value', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('incr_misses', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['incr_misses']); ?></td>
									<td class="v"><?php _e('Total number of times a INCR command was <strong>unable</strong> to increment a value', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('decr_hits', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['decr_hits']); ?></td>
									<td class="v"><?php _e('Total number of times a DECR command was <strong>able</strong> to decrement a value', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('decr_misses', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['decr_misses']); ?></td>
									<td class="v"><?php _e('Total number of times a DECR command was <strong>unable</strong> to decrement a value', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cas_hits', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cas_hits']); ?></td>
									<td class="v"><?php _e('Total number of times a CAS command was <strong>able</strong> to compare and swap data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cas_misses', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cas_misses']); ?></td>
									<td class="v"><?php _e('Total number of times a CAS command was <strong>unable</strong> to compare and swap data', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('cas_badval', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['cas_badval']); ?></td>
									<td class="v"><?php _e('The "cas" command is some kind of Memcached\'s way to avoid locking. "cas" calls with bad identifier are counted in this stats key', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('bytes_read', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $hupa_server_class->minify_format_filesize($memcachedinfo['bytes_read']); ?></td>
									<td class="v"><?php _e('Total number of bytes input into the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('bytes_written', 'hupa-minify'); ?></td>
									<td class="v"><?php echo $hupa_server_class->minify_format_filesize($memcachedinfo['bytes_written']); ?></td>
									<td class="v"><?php _e('Total number of bytes written by the server', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('evictions', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['evictions']); ?></td>
									<td class="v"><?php _e('Number of valid items removed from cache to free memory for new items', 'hupa-minify'); ?></td>
								</tr>
								<tr>
									<td class="e"><?php _e('reclaimed', 'hupa-minify'); ?></td>
									<td class="v"><?php echo number_format_i18n($memcachedinfo['reclaimed']); ?></td>
									<td class="v"><?php _e('Number of items reclaimed', 'hupa-minify'); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php
		} // end if class_exists( 'Memcache' )
		?>


			</div>
		</div>
	</div>
</div>
