<?php
defined( 'ABSPATH' ) or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner MINIFY
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

global $hupa_server_class;

wp_add_dashboard_widget( 'wp_memory_dashboard', 'Server Übersicht', 'minify_status_dashboard_output' );

function minify_status_dashboard_output() {
	global $hupa_server_class;
	if ( current_user_can( 'manage_options' ) ) : ?>

        <ul>
        <li><strong><?php _e( 'Server OS', 'hupa-minify' ); ?></strong> :
            <span><?php echo $hupa_server_class->minify_server_os(); ?>&nbsp;/&nbsp;<?php echo ( PHP_INT_SIZE * 8 ) . __( 'Bit OS', 'hupa-minify' ); ?></span>
        </li>
        <li><strong><?php _e( 'Server Software', 'hupa-minify' ); ?></strong> :
            <span><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span></li>
        <li><strong><?php _e( 'Server IP', 'hupa-minify' ); ?></strong> :
            <span><?php echo( $hupa_server_class->minify_validate_ip_address( $hupa_server_class->minify_check_server_ip() ) ? $hupa_server_class->minify_check_server_ip() : "ERROR IP096T" ); ?></span>
        </li>
        <li><strong><?php _e( 'Server Port', 'hupa-minify' ); ?></strong> :
            <span><?php echo $_SERVER['SERVER_PORT']; ?></span></li>
        <li><strong><?php _e( 'Server Location', 'hupa-minify' ); ?></strong> :
            <span><?php echo $hupa_server_class->minify_check_server_location(); ?></span></li>
        <li><strong><?php _e( 'Server Hostname', 'hupa-minify' ); ?></strong> :
            <span><?php echo gethostname(); ?></span></li>
        <li><strong><?php _e( 'Site\'s Document Root', 'hupa-minify' ); ?></strong> :
            <span><?php echo $_SERVER['DOCUMENT_ROOT'] . '/'; ?></span></li>
        <li><strong><?php _e( 'Memcached Enabled', 'hupa-minify' ); ?></strong> :
            <span><?php echo( class_exists( 'Memcache' ) ? __( 'Yes', 'wp-sever-stats' ) : __( 'No', 'hupa-minify' ) ); ?></span>
        </li>
		<?php if ( $hupa_server_class->isShellEnabled() ) : ?>
            <li><strong><?php _e( 'Total CPUs', 'hupa-minify' ); ?></strong> :
                <span><?php echo $hupa_server_class->minify_check_cpu_count() . ' / ' . $hupa_server_class->minify_check_core_count() . __( 'Cores', 'hupa-minify' ); ?></span>
            </li>
            <li><strong><?php _e( 'Total RAM', 'hupa-minify' ); ?></strong> :
                <span><?php echo( is_numeric( $hupa_server_class->minify_check_total_ram() ) ? $hupa_server_class->minify_format_filesize_kB( $hupa_server_class->minify_check_total_ram() ) : $hupa_server_class->minify_check_total_ram() ); ?></span>
            </li>
            <li><strong><?php _e( 'Real Time Free RAM', 'hupa-minify' ); ?></strong> : <span
                        id="realtime_free_ram"></span></li>
            <li><strong><?php _e( 'Real Time RAM Usage', 'hupa-minify' ); ?></strong> : <span
                        id="realtime_ram_usage"></span></li>
		<?php endif; ?>
        <ul>
		<?php if ( $hupa_server_class->isShellEnabled() ) : ?>
            <div class="progressbar">
                <div style="border:1px solid #DDDDDD; background-color:#F9F9F9;	border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px;">
                    <div id="ram-usage-upper-div"
                         style="padding: 0px; border-width:0px; color:#FFFFFF;text-align:right; border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px; margin-top: -1px;">
                        <div id="ram-usage" style="padding:2px;"></div>
                    </div>
                </div>
            </div>

            <span style="line-height: 2.5em;"><strong><?php _e( 'Real Time CPU Load', 'hupa-minify' ) ?>:</strong></span>
            <div class="progressbar">
                <div style="border:1px solid #DDDDDD; background-color:#F9F9F9;	border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px;">
                    <div id="server-load-upper-div"
                         style="padding: 0px; border-width:0px; color:#FFFFFF;text-align:right; border-color: rgb(223, 223, 223); box-shadow: 0px 1px 0px rgb(255, 255, 255) inset; border-radius: 3px; margin-top: -1px;">
                        <div id="server-load" style="padding:2px;"></div>
                    </div>
                </div>
            </div>
		<?php endif; ?>
			<?php if ( class_exists( 'Memcache' ) ) : ?>
                <div class="wpss_show_buttons content-center">
                    <a href="<?php echo get_admin_url(); ?>admin.php?page=minify-server-memcache"
                       title="Checkout Memcached Info"
                       class="wpss_btn button button-small"><?php _e( 'Check More Memcached Info', 'hupa-minify' ); ?></a>
                </div>
			<?php endif; ?>
            <hr/>
            <ul>
                <li><strong><?php _e( 'Database Software', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_database_software(); ?></span></li>
                <li><strong><?php _e( 'Database Version', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_database_version(); ?></span></li>
                <li><strong><?php _e( 'Maximum No. of Connections', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_database_max_no_connection(); ?></span></li>
                <li><strong><?php _e( 'Maximum Packet Size', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_database_max_packet_size(); ?></span></li>
                <li><strong><?php _e( 'Database Disk Usage', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_database_disk_usage(); ?></span></li>
                <li><strong><?php _e( 'Index Disk Usage', 'hupa-minify' ); ?></strong> :
                    <span><?php echo $hupa_server_class->minify_index_disk_usage(); ?></span></li>
            </ul>
            <div class="wpss_show_buttons content-center">
                <a href="<?php echo get_admin_url(); ?>admin.php?page=minify-server-sql" title="Checkout More Database Info"
                   class="wpss_btn button button-small"><?php _e( 'Check More Database Info', 'hupa-minify' ); ?></a>
            </div>
            <hr/>
            <ul>
                <li><strong><?php _e('PHP Version', 'hupa-minify'); ?></strong> : <span><?php echo PHP_VERSION; ?></span></li>
                <li><strong><?php _e('PHP Max Upload Size', 'hupa-minify'); ?></strong> : <span><?php echo $hupa_server_class->minify_index_disk_usage(); ?></span></li>
                <li><strong><?php _e('PHP Max Post Size', 'hupa-minify'); ?></strong> : <span><?php echo $hupa_server_class->minify_php_max_post_size(); ?></span></li>
                <li><strong><?php _e('PHP Max Execution Time', 'hupa-minify'); ?></strong> : <span><?php echo $hupa_server_class->minify_php_max_execution_time() . " " . __("sec", "hupa-minify"); ?></span></li>
                <li><strong><?php _e('PHP Short Tag', 'hupa-minify'); ?></strong> : <span><?php echo $hupa_server_class->minify_php_short_tag(); ?></span></li>
                <li><strong><?php _e('PHP Memory Limit', 'hupa-minify'); ?></strong> : <span><?php echo $hupa_server_class->minify_check_limit(); ?></span></li>
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
                <a href="<?php echo get_admin_url(); ?>admin.php?page=minify-server-php" title="Checkout More PHP Info" class="wpss_btn button button-small"><?php _e('Check More PHP Info', 'hupa-minify'); ?></a>
            </div>
    			<?php if ($hupa_server_class->isShellEnabled()) : ?>
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