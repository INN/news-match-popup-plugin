<?php
/**
 * Plugin Name: News Match Popup Basics
 * Plugin URI:  https://labs.inn.org
 * Description: An introduction to popups for Knight News Match program particpants, and others
 * Version:     0.1.0
 * Author:      innlabs
 * Author URI:  https://labs.inn.org
 * Donate link: https://labs.inn.org
 * License:     GPLv2 or later
 * Text Domain: news-match-popup-basics
 * Domain Path: /languages
 *
 * @link    https://labs.inn.org
 *
 * @package News_Match_Popup_Basics
 * @version 0.1.0
 */

/**
 * Copyright (c) 2017 innlabs (email : labs@inn.org)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


// Include additional php files here.
// require 'includes/something.php';

/**
 * Main initiation class.
 *
 * @since  0.1.0
 */
final class News_Match_Popup_Basics {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	const VERSION = '0.1.0';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  0.1.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    News_Match_Popup_Basics
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   0.1.0
	 * @return  News_Match_Popup_Basics A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  0.1.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.1.0
	 */
	public function plugin_classes() {
		// $this->plugin_class = new NMPB_Plugin_Class( $this );

	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  0.1.0
	 */
	public function _activate() {
		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 * Uninstall routines should be in uninstall.php.
	 *
	 * @since  0.1.0
	 */
	public function _deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'news-match-popup-basics', false, dirname( $this->basename ) . '/languages/' );

		// create the default popup: this plugin's goal.
		$this->create_popup();


		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Create a new popup post with our desired defaults
	 *
	 * @since 0.1.0
	 */
	public function create_popup() {
		return true;
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.1.0
	 *
	 * @return boolean True if requirements met, false if not.
	 */
	public function check_requirements() {

		// Bail early if plugin meets requirements.
		if ( $this->meets_requirements() ) {
			return true;
		}

		// Add a dashboard notice.
		add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

		// Deactivate our plugin.
		add_action( 'admin_init', array( $this, 'deactivate_me' ) );

		// Didn't meet the requirements.
		return false;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  0.1.0
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met.
	 *
	 * @since  0.1.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'popup-maker/popup-maker.php' ) ) {
			$this->activation_errors[] = sprintf(
				// translators: %1$s is a wordpress.org/plugins URL, %2$s is the name of that plugin.
				__( 'You must first install and activate the <a href="%1$s">%2$s</a> plugin.', 'news-match-popup-basics' ),
				esc_attr( 'https://wordpress.org/plugins/popup-maker/' ),
				esc_html( 'Popup Maker' )
			);
			return false;
		}

		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  0.1.0
	 */
	public function requirements_not_met_notice() {

		// translators: %1$s is the link to the plugins page in the dashboard.
		$default_message = sprintf( __( 'News Match Popup Basics is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'news-match-popup-basics' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->activation_errors && is_array( $this->activation_errors ) ) {
			$details = '<ul>';
			$details .= '<li>' . implode( '</li><br /><li>', $this->activation_errors ) . '</li>';
			$details .= '</ul>';
		}

		// Output errors.
		?>
		<div id="message" class="error">
			<p><?php echo wp_kses_post( $default_message ); ?></p>
			<?php echo wp_kses_post( $details ); ?>
		</div>
		<?php
	}
}

/**
 * Grab the News_Match_Popup_Basics object and return it.
 * Wrapper for News_Match_Popup_Basics::get_instance().
 *
 * @since  0.1.0
 * @return News_Match_Popup_Basics  Singleton instance of plugin class.
 */
function nmpb() {
	return News_Match_Popup_Basics::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( nmpb(), 'hooks' ) );

// Activation and deactivation.
register_activation_hook( __FILE__, array( nmpb(), '_activate' ) );
register_deactivation_hook( __FILE__, array( nmpb(), '_deactivate' ) );
