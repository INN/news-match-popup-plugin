<?php
/*
 * This class contains the functionality for the News Match Popup Basics plugin's suppression of popups containing mailchimp signup dialogs when the browser has been referred from a Mailchimp-analytics-using URL
 *
 * @since 0.1.1
 * @package News_Match_Popup_Basics
 */

class News_Match_Popup_Basics_Mailchimp {
	/**
	 * option key
	 *
	 * @var string
	 * @since 0.1.1
	 */
	private $key = '';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $url = '';

	/**
	 * set us up the vars from the plugin's single instance
	 * initialize the hooks
	 *
	 * @since 0.1.1
	 */
	public function __construct( $settings_key, $url ) {
		$this->key = $settings_key;
		$this->url = $url;
		add_action( 'wp_enqueue_scripts', array( $this, 'mailchimp_maybe_enqueue' ), 9 );
	}

	/**
	 * maybe modify it?
	 * 
	 * @since 0.1.1
	 * @return Boolean whether or not the mailchimp_enqueue function was run.
	 */
	public function mailchimp_maybe_enqueue() {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['mailchimp_toggle'] ) || 'on' !== $option['mailchimp_toggle'] ) {
			return false;
		}
		if ( ! isset( $option['mailchimp_campaign'] ) || empty( $option['mailchimp_campaign'] ) ) {
			return false;
		}

		$this->mailchimp_enqueue( $option );
		return true;
	}

	/**
	 * Modify the things/output the js
	 *
	 * @param array $option The options array for this plugin
	 * @since 0.1.1
	 * @since Popup Maker v1.6.6
	 */
	public function mailchimp_enqueue( $options = array() ) {
		wp_register_script(
			'news-match-popup-basics-mailchimp',
			$this->url . 'js/exclude.js',
			array( 'jquery', 'popup-maker-site' ), // depends upon both of these
			null,
			true
		);
		wp_localize_script(
			'news-match-popup-basics-mailchimp',
			'news_match_popup_basics_mailchimp',
			array(
				'campaign' => $options['mailchimp_campaign']
			)
		);
		wp_enqueue_script(
			'news-match-popup-basics-mailchimp'
		);
	}
}
