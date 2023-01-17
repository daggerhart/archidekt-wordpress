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
		add_action('admin_post_clear-archidekt-cache', [$static, 'clearCache']);
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

		// Show error/update messages.
		settings_errors('archidekt_messages');
		$message_groups = $this->getMessages();
		$this->deleteMessages();
		foreach ($message_groups as $type => $messages) {
			foreach ($messages as $message) {
				echo "<div class='notice {$type} settings-error is-dismissible'>" .
					"<p><strong>{$message}</strong></p>" .
					"</div>";
			}
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('archidekt');
				do_settings_sections('archidekt');
				submit_button('Save Settings');
				?>
			</form>

			<form action="admin-post.php" method="post">
				<p>Clear the stored deck and cards caches.</p>
				<input type="hidden" name="option_page" value="archidekt">
				<input type="hidden" name="action" value="clear-archidekt-cache">
				<?php
				wp_nonce_field('clear-archidekt-cache');
				submit_button('Clear Archidekt Cache', 'secondary');
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Clear deck caches.
	 *
	 * @return void
	 */
	public function clearCache() {
		global $wpdb;

		$transients = $wpdb->get_col($wpdb->prepare("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_archidekt/%'"));
		foreach ($transients as $transient) {
			delete_transient($transient);
		}

		$deck_ids = array_map(function($transient) {
			return explode('/', $transient)[1];
		}, $transients);

		$this->addMessage("Cache cleared for deck ids: " . implode(', ', $deck_ids));
		wp_safe_redirect(admin_url('options-general.php?page=archidekt&cache-cleared=1'));
		exit;
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
		print $this->template->renderPluginTemplate('form/checkbox', [
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
		print $this->template->renderPluginTemplate('form/select', [
			'field' => $field,
		]);
	}

	/**
	 * Get settings messages.
	 *
	 * @return array
	 */
	protected function getMessages(): array {
		return get_transient('archidekt_settings_messages') ?: [];
	}

	/**
	 * Add a settings message.
	 *
	 * @param string $message
	 * @param string $type
	 *
	 * @return void
	 */
	protected function addMessage(string $message, string $type = 'updated') {
		$cache = get_transient('archidekt_settings_messages') ?: [];
		$cache[$type][] = $message;
		set_transient('archidekt_settings_messages', $cache, 30);
	}

	/**
	 * Delete settings messages.
	 *
	 * @return void
	 */
	protected function deleteMessages() {
		delete_transient('archidekt_settings_messages');
	}

}
