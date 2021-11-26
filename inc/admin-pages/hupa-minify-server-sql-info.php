<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $hupa_server_class;
?>
<div class="wp-bs-starter-wrapper">
	<div class="container">
		<div id="minifyThemeRoot" data-folder="<?= HUPA_MINIFY_THEME_ROOT ?>" class="card card-license shadow-sm bg-light">
			<h5 class="card-header d-flex align-items-center bg-hupa py-4">
				<i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
				HUPA&nbsp; <?= __( 'Database', 'hupa-minify' ) ?> </h5>
			<div class="card-body pb-4" style="min-height: 72vh">
				<div class="d-flex align-items-center">
					<h5 class="card-title"><i
							class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Database Information', 'hupa-minify' ) ?>
						<small class="small d-block mt-2">
							<?php _e('This page will show you the in-depth information about your database', 'hupa-minify'); ?>
						</small>
					</h5>
				</div>
				<hr>
				<div class="wrap wpss_info ">
					<h5><?php _e('Basic Database Information', 'hupa-minify'); ?></h5>
					<table class="widefat table table-bordered table-striped">
						<thead>
						<tr>
							<th><?php _e('Variable Name', 'hupa-minify'); ?></th>
							<th><?php _e('Value', 'hupa-minify'); ?></th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<td class="e"><?php _e('Variable Name', 'hupa-minify'); ?></td>
							<td><?php _e('Value', 'hupa-minify'); ?></td>
						</tr>
						</tfoot>
						<tbody>
						<tr>
							<td class="e"><?php _e('Database Software', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_software(); ?></td>
						</tr>
						<tr>
							<td class="e"><?php _e('Database Version', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_version(); ?></td>
						</tr>
						<tr>
							<td class="e"><?php _e('Maximum No. of Connections', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_max_no_connection(); ?></td>
						</tr>
						<tr>
							<td class="e"><?php _e('Maximum Packet Size', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_max_packet_size(); ?></td>
						</tr>
						<tr>
							<td class="e"><?php _e('Database Disk Usage', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_disk_usage(); ?></td>
						</tr>
						<tr>
							<td class="e"><?php _e('Index Disk Usage', 'hupa-minify'); ?></td>
							<td class="v"><?php echo $hupa_server_class->minify_database_disk_usage(); ?></td>
						</tr>
						</tbody>
					</table>
					<div class="clear give-some-space"></div>
					<hr />
					<h5><?php _e('Advanced Database Information', 'hupa-minify'); ?></h5>
					<table class="widefat table table-bordered table-striped">
						<thead>
						<tr>
							<th><?php _e('Variable Name', 'hupa-minify'); ?></th>
							<th><?php _e('Value', 'hupa-minify'); ?></th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<td><?php _e('Variable Name', 'hupa-minify'); ?></td>
							<td><?php _e('Value', 'hupa-minify'); ?></td>
						</tr>
						</tfoot>
						<tbody>
						<?php
						if (get_option('wpss_db_advanced_info')) {
							$dbinfo = get_option('wpss_db_advanced_info');
						} else {
							global $wpdb;
							$dbversion = $wpdb->get_var("SELECT VERSION() AS version");
							$dbinfo = $wpdb->get_results("SHOW VARIABLES");
							update_option('wpss_db_advanced_info', $dbinfo);
						}

						if (!empty($dbinfo)) {
							foreach ($dbinfo as $info) {
								echo '<tr><td class="e">' . $info->Variable_name . '</td><td class="v">' . htmlspecialchars($info->Value) . '</td></tr>';
							}
						} else {
							echo '<tr><td>' . __('Something went wrong!', 'hupa-minify') . '</td><td>' . __('Something went wrong!', 'hupa-minify') . '</td></tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
