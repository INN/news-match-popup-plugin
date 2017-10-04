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
		register_setting( $this->key, $this->key );

		add_settings_section(
			$this->settings_section,
			esc_html__( $this->title, 'news-match-popup-basics' ),
			array( $this, 'settings_section_callback' ),
			$this->key
		);

		/*
		add_settings_field(
			$this->key
		);
		*/
		return true;
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
