<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://sudowp.com
 * @since             1.0.0
 * @package           SudoWP_Adminer
 *
 * @wordpress-plugin
 * Plugin Name:       SudoWP Adminer (Security Fork)
 * Plugin URI:        https://github.com/Sudo-WP/sudowp-adminer
 * Description:       A secure, zero-trust database management tool. Fixes critical SSRF vulnerabilities by enforcing local connections only.
 * Version:           1.5.0
 * Author:            SudoWP, WP Republic
 * Author URI:        https://sudowp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sudowp-adminer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SudoWP_Adminer {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	public function register_menu() {
		add_menu_page(
			'SudoWP Adminer',
			'SudoWP Adminer',
			'manage_options', // Capability Check: Only Admins
			'sudowp-adminer',
			array( $this, 'render_admin_page' ),
			'dashicons-database',
			60
		);
	}

	public function render_admin_page() {
		// Calculate the URL to the protected secure loader
		$loader_url = plugin_dir_url( __FILE__ ) . 'inc/adminer/index.php';
		?>
		<div class="wrap" style="margin:0; padding:0; overflow:hidden;">
			<h1 style="display:none;">SudoWP Database Manager</h1>
			<iframe src="<?php echo esc_url( $loader_url ); ?>" width="100%" height="800px" style="border:none; margin-top:10px;"></iframe>
		</div>
		<script>
			// Little script to maximize the iframe height
			jQuery(document).ready(function($) {
				var height = $(window).height() - 100;
				$('iframe').height(height);
			});
		</script>
		<?php
	}
}

new SudoWP_Adminer();