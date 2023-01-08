<?php

namespace Archidekt\Admin;

use Archidekt\Model\Settings;
use Archidekt\Service\Template;

/**
 * Settings page for the plugin.
 */
class SettingsPage {

	const CAPABILITY = 'manage_options';

	private Settings $settings;
	private Template $template;

	/**
	 * @param Settings $settings
	 * @param Template $template
	 */
	public function __construct(Settings $settings, Template $template) {
		$this->settings = $settings;
		$this->template = $template;
	}

	/**
	 * Register the new page.
	 *
	 * @return void
	 */
	public static function register() {
		$static = new static(
			Settings::instance(),
			new Template()
		);

		add_action('admin_init', [$static, 'adminInit']);
		add_action('admin_menu', [$static, 'adminMenu']);
	}

	/**
	 * Hook admin_menu.
	 *
	 * @return void
	 */
	public function adminMenu() {
		add_menu_page(
			'Archidekt',
			'Archidekt Options',
			static::CAPABILITY,
			'archidekt',
			[$this, 'pageContent']
		);
	}

	/**
	 * Top level menu callback function
	 */
	function pageContent() {
		// check user capabilities
		if ( ! current_user_can( static::CAPABILITY ) ) {
			return;
		}

		// add error/update messages

		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'archidekt_messages', 'archidekt_message', __( 'Settings Saved', 'archidekt' ), 'updated' );
		}

		// show error/update messages
		settings_errors( 'archidekt_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "archidekt"
				settings_fields( 'archidekt' );
				// output setting sections and their fields
				// (sections are registered for "archidekt", each field is registered to a specific section)
				do_settings_sections( 'archidekt' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Hook admin_init.
	 *
	 * @return void
	 */
	public function adminInit() {
		// Register a new setting for "archidekt" page.
		register_setting( 'archidekt', 'archidekt_settings' );

		$sections = [
			'archidekt_section_cache' => [
				'label' => __( 'Cache Settings', 'archidekt' ),
				'description_callback' => [$this, 'cacheSectionDescription'],
				'fields' => [
					'cacheEnabled' => [
						'callback' => 'renderCheckbox',
						'label' => __('Cache Enabled', 'archidekt'),
						'description' => __('Enable caching for faster site responses and to avoid rate limits on Archidekt.', 'archidekt'),
						'value' => $this->settings->cacheEnabled,
					],
					'cacheLifetime' => [
						'callback' => 'renderSelect',
						'label' => __('Cache Lifetime', 'archidekt'),
						'description' => __('Select how long the cache should last.', 'archidekt'),
						'value' => $this->settings->cacheLifetime,
						'options' => [
							0 => __('Forever', 'archidekt'),
							MONTH_IN_SECONDS => __('One Month', 'archidekt'),
							WEEK_IN_SECONDS => __('One Week', 'archidekt'),
							DAY_IN_SECONDS => __('One Day', 'archidekt'),
						],
					],
				],
			],
		];

		foreach ($sections as $section_id => $section) {
			// Add sections.
			add_settings_section(
				$section_id,
				$section['label'],
				$section['description_callback'],
				'archidekt'
			);

			// Add fields.
			foreach ($section['fields'] as $field_id => $field) {
				$field['id'] = $field_id;

				add_settings_field(
					$field_id,
					$field['label'],
					[$this, $field['callback']],
					'archidekt',
					$section_id,
					$field
				);
			}
		}
	}

	/**
	 * Developers section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function cacheSectionDescription(array $args = []) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'It is greatly encouraged to cache decks for as long as possible to avoid Archidekt rate limits.', 'archidekt' ); ?></p>
		<?php
	}

	/**
	 * Render settings checkbox.
	 *
	 * @param array $field
	 *
	 * @return void
	 */
	public function renderCheckbox(array $field = []) {
		print $this->template->render('form/checkbox', [
			'field' => $field,
		]);
	}

	/**
	 * Render settings select box.
	 *
	 * @param array $field
	 *
	 * @return void
	 */
	public function renderSelect(array $field = []) {
		print $this->template->render('form/select', [
			'field' => $field,
		]);
	}

}
