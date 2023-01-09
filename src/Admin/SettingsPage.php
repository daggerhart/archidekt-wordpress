<?php

namespace Archidekt\Admin;

use Archidekt\Model\Settings;
use Archidekt\Service\Template;
use Archidekt\ServicesContainer;

/**
 * Settings page for the plugin.
 */
class SettingsPage {

	const CAPABILITY = 'manage_options';

	/**
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * @var Template
	 */
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
	public static function register(ServicesContainer $container) {
		$static = new static(
			$container->get('settings'),
			$container->get('template')
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
		add_submenu_page(
			'options-general.php',
			'Archidekt',
			'Archidekt Settings',
			static::CAPABILITY,
			'archidekt',
			[$this, 'pageContent']
		);
	}

	/**
	 * Top level menu callback function
	 */
	function pageContent() {
		if (!current_user_can(static::CAPABILITY)) {
			return;
		}

		if (isset($_GET['settings-updated'])) {
			add_settings_error('archidekt_messages', 'archidekt_message', __( 'Settings Saved', 'archidekt' ), 'updated');
		}

		// Show error/update messages.
		settings_errors( 'archidekt_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'archidekt' );
				do_settings_sections( 'archidekt' );
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
		register_setting( 'archidekt', 'archidekt_settings' );

		$sections = [
			'archidekt_section_cache' => [
				'label' => __( 'Cache Settings', 'archidekt' ),
				'description_callback' => [$this, 'renderSectionDescription' ],
				'description' => __( 'It is greatly encouraged to cache decks for as long as possible to avoid Archidekt rate limits.', 'archidekt' ),
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
			$section['id'] = $section_id;

			// Add sections.
			add_settings_section(
				$section_id,
				$section['label'],
				$section['description_callback'],
				'archidekt',
				$section,
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
	 * Section description callback function.
	 *
	 * @param array $section The settings array, defining title, id, callback.
	 */
	public function renderSectionDescription(array $section = []) {
		?>
		<p id="<?= esc_attr( $section['id'] ); ?>"><?= $section['description']; ?></p>
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
