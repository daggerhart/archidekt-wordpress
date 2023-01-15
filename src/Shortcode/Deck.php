<?php

namespace Archidekt\Shortcode;

use Archidekt\Service\ApiClient;
use Archidekt\Service\Template;
use Archidekt\ServicesContainer;

/**
 * Shortcode for displaying a deck.
 */
class Deck {

	const TAG = 'deck';

	/**
	 * @var ApiClient
	 */
	private ApiClient $client;

	/**
	 * @var Template
	 */
	private Template $template;

	/**
	 * @param ApiClient $client
	 * @param Template $template
	 */
	public function __construct(ApiClient $client, Template $template) {
		$this->client = $client;
		$this->template = $template;
	}

	/**
	 * @param ServicesContainer $container
	 *
	 * @return void
	 * @throws \Exception
	 */
	public static function register(ServicesContainer $container) {
		$static = new static(
			$container->get('api_client'),
			$container->get('template')
		);

		add_shortcode(static::TAG, [$static, 'shortcode']);
		wp_register_style(
			'archidekt-shortcode-deck',
			ARCHIDEKT_PLUGIN_URL . 'assets/css/shortcode-deck.css',
			[],
			(defined('WP_DEBUG') && WP_DEBUG) ? time() : ARCHIDEKT_SCRIPTS_VERSION,
		);
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function shortcode(array $attributes = []): string {
		$attributes = wp_parse_args($attributes, [
			'id' => NULL,
			'mode' => 'summary',
		]);

		wp_enqueue_style('archidekt-shortcode-deck');

		$deck = $this->client->getDeck($attributes['id']);
		$suggestions = [
			// Default to the summary if the given mode doesn't exist.
			'deck' . DIRECTORY_SEPARATOR . 'deck--summary',
			// More specific suggestions later in the array.
			'deck' . DIRECTORY_SEPARATOR . 'deck--' . $attributes['mode'],
		];

		return $this->template->render($suggestions, [
			'deck' => $deck,
			'deck_id' => $attributes['id'],
			'view_mode' => $attributes['mode'],
		]);
	}

}
