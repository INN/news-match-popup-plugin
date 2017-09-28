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
	 * Detailed admin messages.
	 *
	 * @var    array
	 * @since  0.1.0
	 */
	protected $admin_messages = array();

	/**
	 * Option name for storing admin notices and other things.
	 *
	 * current structure:
	 * array(
	 *     'messages' => array() // not expected to persist beyond one page load
	 * )
	 * @var array
	 * @since 0.1.0
	 */
	protected $option = 'news_match_popup_basics';

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

		// create the default popup: this plugin's goal.
		$this->create_popup();

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
		delete_option( $this->option );
	}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 */
	public function init() {
		add_action( 'all_admin_notices', array( $this, 'generic_admin_notices' ) );

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'news-match-popup-basics', false, dirname( $this->basename ) . '/languages/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Create a new popup post with our desired defaults
	 *
	 * @since 0.1.0
	 */
	public function create_popup() {
		// we may be editing this information at any time
		$option_data = get_option( $this->option, array() );
		// we need the current user's ID for this case.
		global $user_ID;

		// Create the post.
		$new_post = array(
			'post_title' => 'News Match Default Popup',
			'post_content' => 'The text, graphics, shortcodes and links in this area are displayed in your popup.',
			'post_status' => 'publish',
			'post_date' => date('Y-m-d H:i:s'),
			'post_author' => $user_ID,
			'post_type' => 'popup',
			'post_category' => array(0)
		);
		$post_id = wp_insert_post( $new_post );

		// If creating the post did not work, create an error message.
		if ( empty( $post_id ) || false == $post_id ) {
			$default_message = __( 'News Match Popup Basics encountered an error while creating the default popup.', 'news-match-popup-basics' );
			$details = sprintf(
				// translators:
				// %1$s is var_dumped contents of a PHP variable
				// %2$s is https://github.com/INN/newsmatch-popup-plugin/issues
				__( 'The post ID returned by <code>wp_insert_post</code> was <strong>%1$s</strong>: this is not right. <a href="%2$s">Please file a bug</a>.', 'news-match-popup-basics' ),
				var_dump( $post_id ),
				esc_attr( 'https://github.com/INN/newsmatch-popup-plugin/issues' )
			);
			$option_data['messages'][] = sprintf(
				'<div id="nmpb-message" class="error"><p>%1$s</p><p>%2$s</p></div>',
				wp_kses_post( $default_message ),
				wp_kses_post( $details )
			);

			// update the option before exiting.
			update_option( $this->option, $option_data );

			return false;
		}

		// to do: get the ID of an existing popup theme

		// Create the post meta.
		$meta = array(
			// key => value
			'popup_display' => 	array (
				'size' => 'large',
				'responsive_min_width' => '',
				'responsive_max_width' => '',
				'custom_width' => '500',
				'custom_height' => '380',
				'overlay_disabled' => '1',
				'animation_type' => 'slide',
				'animation_speed' => '350',
				'animation_origin' => 'center bottom',
				'position_fixed' => '1',
				'location' => 'center bottom',
				'position_bottom' => '0',
				'position_top' => '100',
				'position_left' => '0',
				'position_right' => '0',
				'overlay_zindex' => '1999999998',
				'zindex' => '1999999999',
				'responsive_min_width_unit' => 'px',
				'responsive_max_width_unit' => 'px',
				'custom_width_unit' => 'px',
				'custom_height_unit' => 'px',
			),
			'popup_close' =>array (
				'text' => '',
				'button_delay' => '0',
				'overlay_click' => 'true',
				'esc_press' => 'true',
			),
			'popup_title' => '',
			'popup_teme' => null, // This should be the ID of an existing popup theme, probably the default, ack
			'popup_triggers' => array(
				array (
					'type' => 'auto_open',
					'settings' =>
					array (
						'delay' => '500',
						'cookie' =>
						array (
							'name' =>
							array (
								0 => esc_attr( sprintf(
									'pum-%1$s',
									$post_id
								) ),
							),
						),
					),
				)
			),
			'popup_cookies' => array(
				array (
					'event' => 'on_popup_close',
					'settings' =>
					array (
						'name' => esc_attr( sprintf(
							'pum-%1$s',
							$post_id
						) ),
						'key' => '',
						'time' => '1 year',
						'path' => 1,
					),
				)
			),
			'popup_conditions' => array(
				array (
					0 =>
					array (
						'not_operand' => 0,
						'target' => 'is_front_page',
					),
				)
			),
			'popup_open_count' => 0,
			'popup_open_count_total' => 0,

		);
		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}

		// Success!
		// translators: %1$s is a wordpress admin URL and %2$s is the ID of the post (part of the url)
		$message = sprintf(
			__( 'Your new default popup has been created! <a href="%1$s%2$s">Edit it now</a>.', 'news-match-popup-basics' ),
			admin_url( 'post.php/?action=edit&post=' ),
			esc_attr( $post_id )
		);
		$this->admin_messages[] = sprintf(
			'<div id="nmpb-message" class="notice"><p>%1$s</p></div>',
			$message
		);

		error_log(var_export( $this->admin_messages, true));

		// add all messages to the admin messages queue
		$option_data['messages'] = $this->admin_messages;

		// update the option before success.
		update_option( $this->option, $option_data );

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

		// Add a dashboard notice, the contents of which were set in check_requirements().
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
		// Add detailed messages to $this->admin_messages array.
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( ! is_plugin_active( 'popup-maker/popup-maker.php' ) ) {

			// We set the message here, but the action that outputs it isn't rendered in this function.
			// Instead, the action requirements_not_met_notice is hooked inside check_requirements().
			$this->admin_messages[] = sprintf(
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
	 * Display admin notices from the plugin's option['messages'] information
	 *
	 * @since 0.1.0
	 */
	public function generic_admin_notices() {
		$option_data = get_option( $this->option, array() );
		if ( array_key_exists( 'messages', $option_data ) && ! empty( $option_data['messages'] ) ) {
			foreach ( $option_data['messages'] as $message ) {
				echo wp_kses_post( $message );
			}
		}

		// remove messages that we have displayed
		$option_data['messages'] = null;
		update_option( $this->option, $option_data );
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * This only affects the current page load, and needs to run before all_admin_notices.
	 *
	 * @since  0.1.0
	 */
	public function requirements_not_met_notice() {

		// translators: %1$s is the link to the plugins page in the dashboard.
		$default_message = sprintf( __( 'News Match Popup Basics is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'news-match-popup-basics' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->admin_messages && is_array( $this->admin_messages ) ) {
			$details = '<ul>';
			$details .= '<li>' . implode( '</li><br /><li>', $this->admin_messages ) . '</li>';
			$details .= '</ul>';
		}

		// Output errors.
		?>
		<div id="nmpb-message" class="error">
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
