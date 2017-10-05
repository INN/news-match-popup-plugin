<?php
/*
 * This class contains the settings and functionality for the News Match Popup Basics plugin's suppression of popups containing mailchimp signup dialogs when the browser has been referred from a Mailchimp-analytics-using URL
 *
 * @since 0.1.1
 * @package News_Match_Popup_Basics
 */

class News_Match_Popup_Basics_Mailchimp {
	/**
	 * option key and option page slug
	 *
	 * @var string
	 * @since 0.1.1
	 */
	private $key = '';

	/*
	 * slug of settings group
	 *
	 * @var string $settings_group The settings group slug
	 */
	private $settings_group = '';

	/**
	 * slug of settings section
	 *
	 * @var string $settings_section The settings section slug
	 */
	private $settings_section = '';

	/**
	 * Options page title.
	 *
	 * @var string
	 * @since 0.1.1
	 */
	protected $title = '';

	/**
	 * set us up the vars from the plugin's single instance
	 * initialize the hooks
	 *
	 * @since 0.1.1
	 */
	public function __construct( $settings_key ) {
		$this->key = $settings_key;
		$this->settings_section = $settings_key . '_section';
		$this->settings_group = $settings_key . '_group';
		$this->title = esc_attr__( 'News Match Popup Basics', 'news-match-popup-basics' );

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ), 999 );
	}

	/**
	 * this should be hooked on admin_init
	 * it should register settings
	 *
	 * The settings section and settings group and option name were all registered in the save
	 *
	 * @since 0.1.1
	 * @todo sanitize settings
	 */
	public function register_settings() {
		register_setting( $this->key, $this->key, array( $this, 'setting_sanitizer' ) );

		add_settings_section(
			$this->settings_section,
			esc_html__( $this->title, 'news-match-popup-basics' ),
			array( $this, 'settings_section_callback' ),
			$this->key
		);

		add_settings_field(
			$this->key . '[mailchimp_toggle]',
			__( 'Popup prevention for Mailchimp visitors', 'news-match-popup-basics' ),
			array( $this, 'mailchimp_toggle' ),
			$this->key,
			$this->settings_section,
			array(
				'name' => $this->key . '[mailchimp_toggle]'
			)
		);

		add_settings_field(
			$this->key . '[mailchimp_campaign]',
			__( 'Mailchimp campaign ID', 'news-match-popup-basics' ),
			array( $this, 'mailchimp_campaign' ),
			$this->key,
			$this->settings_section,
			array(
				'name' => $this->key . '[mailchimp_campaign]'
			)
		);

		add_settings_field(
			$this->key . '[donate_toggle]',
			__( 'Donation page popup prevention', 'news-match-popup-basics' ),
			array( $this, 'donate_toggle' ),
			$this->key,
			$this->settings_section,
			array(
				'name' => $this->key . '[donate_toggle]'
			)
		);

		add_settings_field(
			$this->key . '[donate_urls]',
			__( 'Donation Page URLs', 'news-match-popup-basics' ),
			array( $this, 'donate_urls' ),
			$this->key,
			$this->settings_section,
			array(
				'name' => $this->key . '[donate_urls]'
			)
		);

		return true;
	}

	// @todo: clean these up so they have proper labels

	/**
	 * @todo
	 * Gather the settings values from the $_POST
	 * clean them up
	 * save them in the db
	 */
	public function settings_sanitizer( $value ) {
		error_log(var_export( $value, true));
		return $value;
	}

	/**
	 * Display the checkbox for toggling whether the plugin suppresses based on mailchimp campaign ID in the URL
	 *
	 * @since 0.1.1
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function mailchimp_toggle( $args ) {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['mailchimp_toggle'] ) || 'on' !== $option['mailchimp_toggle'] ) {
			$value = false;
		} else {
			$value = 'on';
		}

		echo sprintf(
			'<input name="%1$s" id="%1$s" type="checkbox" value="on" %2$s>',
			$args['name'],
			checked( $value, 'on', false )
		);
		echo sprintf(
			'<label for="%2$s">%1$s</label>',
			__( 'Checking this box will prevent popups containing a Mailchimp signup form with the HTML element ID <code>#mc_embed_signup</code> from appearing when visiting your site at a link with a <code>utm_source</code> parameter matching the one entered in the box below.', 'news-match-popup-basics' ),
			$args['name']
		);
	}
	
	/**
	 * Display text input for mailchimp campaign ID
	 *
	 * @since 0.1.1
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function mailchimp_campaign( $args ) {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['mailchimp_campaign'] ) || empty( $option['mailchimp_campaign'] ) ) {
			$value = '';
		} else {
			$value = esc_attr( $option['mailchimp_campaign'] );
		}

		echo sprintf(
			'<p><code>utm_source=</code><input name="%1$s" id="%1$s" type="text" value="%2$s"></p>',
			esc_attr( $args['name'] ),
			$value
		);
		echo sprintf(
			'<label for="%2$s">%1$s</label>',
			__( 'The campaign name can be found by examining outbound links from your Mailchimp newsletter, then carefully copying everything between <code>utm_source=</code> and <code>&amp;</code>.', 'news-match-popup-basics' ),
			esc_attr( $args['name'] )
		);

	}

	/**
	 * Display the checkbox for toggling whether the plugin suppresses based on donate page URLs
	 *
	 * @since 0.1.1
	 * @param array $args Optional arguments passed to callbacks registered with add_settings_field.
	 */
	public function donate_toggle( $args ) {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['donate_toggle'] ) || 'on' !== $option['donate_toggle'] ) {
			$value = false;
		} else {
			$value = 'on';
		}

		echo sprintf(
			'<input name="%1$s" id="%1$s" type="checkbox" value="on" %2$s>',
			esc_attr( $args['name'] ),
			checked( $value, 'on', false )
		);
		echo sprintf(
			'<label for="%2$s">%1$s</label>',
			__( 'Checking this box will prevent the popup from appearing on pages with URLs matching the URLs entered in the box below.', 'news-match-popup-basics' ),
			$args['name']
		);
	}

	/**
	 * Display text area input for donation page URLs where the mailchimp popup should not appear
	 *
	 * @todo: can this be done with better page targeting in the default plugin?
	 * 		- no, "NOT" targeting requires purchasing an additional plugin extension.
	 * @todo: better sanitizing of this on this side, not on the submit side
	 * @since 0.1.1
	 */
	public function donate_urls( $args ) {
		$option = get_option( $this->key, array() );
		if ( ! isset( $option['donate_urls'] ) || empty( $option['donate_urls'] ) ) {
			$value = '';
		} else {
			$value = esc_attr( $option['donate_urls'] );
		}

		echo sprintf(
			'<textarea name="%1$s" id="%1$s" type="checkbox" value="on" %2$s wrap="off" style="width: 100%%; display: block;"></textarea>',
			esc_attr( $args['name'] ),
			checked( $value, 'on', false )
		);

		// reminder to remove https://example.org .
		echo  '<label for="' . esc_attr( $args['name'] ) . '">';
		echo sprintf(
			// translators: %1$s is the current site's URL, in the form https://example.com .
			__( 'Each URL should be entered on a separate line. Please remove the opening %1$s from the URL, as it is not needed in this context.', 'news-match-popup-basics' ),
			esc_html( site_url() )
		);
		echo  '</label>';
	}

	/**
	 * Settings section display
	 *
	 * @since 0.1.1
	 */
	public function settings_section_callback() {
		echo wp_kses_post( sprintf(
			'<p>%1$s</p>',
			__( 'This page controls modifications that News Match Popup Basics plugin makes to Popup Maker popups on your site.', 'news-match-popup-basics' )
		));
	}

	/**
	 * Add menu options page
	 *
	 * @since 0.1.1
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page(
			'edit.php?post_type=popup',
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' )
		);
	}

	/**
	 * Admin page markup
	 *
	 * @since 0.1.1
	 */
	public function admin_page_display() {
		?>
		<div class="wrap options-page <?php echo esc_attr( $this->key ); ?>">
			<form method="post" action="options.php">
			<?php
				settings_fields( $this->settings_group );
				do_settings_sections( $this->key );
				submit_button();
	?>
	</form>
</div>
		<?php
	}

	/**
	 * forms
	 */

	/**
	 * verification of submission
	 */

	/**
	 * modify the things/output the js
	 */
}
