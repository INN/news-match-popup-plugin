<?php
/*
 * This class contains the functionality for the News Match Popup Basics plugin's suppression of of all popups on pages that match the URLs defined in the News Match Popup Basics settings
 *
 * @since 0.1.1
 * @package News_Match_Popup_Basics
 */

class News_Match_Popup_Basics_Url_Exclude {
	/**
	 * option key
	 *
	 * @var string
	 * @since 0.1.1
	 */
	private $key = '';

	/**
	 * set us up the vars from the plugin's single instance
	 * initialize the hooks
	 *
	 * @since 0.1.1
	 */
	public function __construct( $settings_key ) {
		$this->key = $settings_key;
		add_action( 'wp_enqueue_scripts', array( $this, 'popmake_maybe_dequeue' ) );
	}

	/**
	 * Determine whether or not to prevent enqueueing of popups
	 * 
	 * @since 0.1.1
	 * @return Boolean whether or not the mailchimp_enqueue function was run.
	 */
	public function popmake_maybe_dequeue() {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['donate_toggle'] ) || 'on' !== $option['donate_toggle'] ) {
			return false;
		}
		if ( ! isset( $option['donate_urls'] ) || empty( $option['donate_urls'] ) ) {
			return false;
		}

		// check whether the present URL is one of those URLs
		$potential_urls = explode( PHP_EOL, $option['donate_urls'] );
		global $wp;
		$current_url = home_url( $wp->request );
		if ( ! is_404() ) {
			error_log(var_export( $current_url, true));
			error_log(var_export( $potential_urls, true));
		}

		$dequeue = false;
		foreach ( $potential_urls as $url ) {
			if ( false !== strpos ( $current_url, $url ) ) {
				$dequeue = true;
				continue;
			}
		}

		if ( $dequeue ) {
			$this->dequeue();
			return true
		}

		return false;
	}

	/**
	 * Dequeue all the URLs
	 *
	 * @since 0.1.1
	 */
	public function dequeue() {
		error_log(var_export( 'gonna dequeue everything popmake-related nao', true));
		remove_action( 'wp_enqueue_scripts', 'popmake_preload_popups', 11 );
		remove_action( 'wp_footer', 'popmake_render_popups', 1 );
	}
}
