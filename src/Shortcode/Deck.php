<?php

namespace Archidekt\Shortcode;

use Archidekt\Service\ApiClient;
use Archidekt\Service\Template;
use Archidekt\ServicesContainer;

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
	public function shortcode(array $attributes = []) {
		$attributes = wp_parse_args($attributes, [
			'id' => NULL,
			'mode' => 'summary',
		]);

		$deck = $this->client->getDeck($attributes['id']);

		wp_enqueue_style('archidekt-shortcode-deck');
		return $this->template->render('deck/deck--' . $attributes['mode'], [
			'deck' => $deck,
		]);
	}

}
