<?php

namespace Archidekt;

use Archidekt\Admin\SettingsPage;
use Archidekt\Model\Settings;
use Archidekt\Service\ApiClient;
use Archidekt\Service\Template;
use Archidekt\Shortcode\Deck;
use Archidekt\Shortcode\DeckCategory;

class Plugin {

	/**
	 * @return ServicesContainer
	 */
	private static function buildContainer(): ServicesContainer {
		$container = new ServicesContainer();
		$container->add('settings', function() {
			return Settings::instance();
		});
		$container->add('api_client', function(ServicesContainer $container) {
			/** @var Settings $settings */
			$settings = $container->get('settings');
			return new ApiClient($settings->cacheEnabled(), $settings->cacheLifetime());
		});
		$container->add('template', function() {
			return new Template(ARCHIDEKT_TEMPLATES_DIR);
		});

		return $container;
	}

	/**
	 * @return ServicesContainer
	 */
	public static function container(): ServicesContainer {
		static $container = null;
		if (is_null($container)) {
			$container = static::buildContainer();
		}
		return $container;
	}

	/**
	 * Init plugin.
	 *
	 * @return void
	 */
	public static function bootstrap() {
		$plugin = new static();

		add_action('init', [$plugin, 'performUpdates'], 5);
		add_action('init', [$plugin, 'init']);
	}

	/**
	 * Perform update hooks when _DB_VERSION is incremented.
	 *
	 * @return void
	 */
	public function performUpdates() {
		$previous_version = (int) get_option('archidekt_db_version', 10000);
		if ($previous_version < ARCHIDEKT_DB_VERSION) {
			while ($previous_version < ARCHIDEKT_DB_VERSION) {
				$previous_version += 1;

				// Generic update hook.
				do_action('archidekt_update', $previous_version, ARCHIDEKT_DB_VERSION);
				// Version specific update hook.
				do_action('archidekt_update__' . $previous_version);
				// Save new version as we go.
				update_option('archidekt_db_version', $previous_version);
			}
		}
	}

	/**
	 * Hook init.
	 *
	 * @return void
	 */
	public function init() {
		SettingsPage::register(static::container());
		Deck::register(static::container());
		DeckCategory::register(static::container());
	}

}
