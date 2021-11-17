<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hupa_Minify
 * @subpackage Hupa_Minify/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Hupa_Minify_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$register = HUPA_MINIFY_INC . 'register-hupa-minify.php';
		unlink($register);
		delete_option("hupa_minify_product_install_authorize");
		delete_option("hupa_minify_client_id");
		delete_option("hupa_minify_client_secret");
		//delete_option("bs_formular_message");
		delete_option("hupa_minify_access_token");
		$infoTxt = 'aktiviert am ' . date('d.m.Y H:i:s')."\r\n";
		file_put_contents(HUPA_MINIFY_PLUGIN_DIR.'/hupa-minify.txt',$infoTxt,  FILE_APPEND | LOCK_EX);
		set_transient('show_lizenz_info', true, 5);
	}
}


