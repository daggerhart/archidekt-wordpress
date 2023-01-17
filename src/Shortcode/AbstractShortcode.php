<?php

namespace Archidekt\Shortcode;

use Archidekt\Service\ApiClient;
use Archidekt\Service\Template;
use Archidekt\ServicesContainer;

/**
 * Base shortcode.
 */
abstract class AbstractShortcode {

	/**
	 * @var ApiClient
	 */
	protected ApiClient $client;

	/**
	 * @var Template
	 */
	protected Template $template;

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

		add_shortcode($static::tag(), [$static, 'shortcode']);
		wp_register_style(
			'archidekt-shortcode-deck',
			ARCHIDEKT_PLUGIN_URL . 'assets/css/shortcode-deck.css',
			[],
			(defined('WP_DEBUG') && WP_DEBUG) ? time() : ARCHIDEKT_SCRIPTS_VERSION,
		);
	}

	/**
	 * The shortcode tag.
	 *
	 * @return string
	 */
	abstract public static function tag(): string;

	/**
	 * Perform the shortcode work.
	 *
	 * @param array $attributes
	 * @param string $content
	 *
	 * @return string
	 */
	abstract public function shortcode(array $attributes, string $content = ''): string;

	/**
	 * Clean up multiline shortcode content.
	 *
	 * @param string $content
	 *
	 * @return array|string|string[]
	 */
	protected function cleanShortcodeContent(string $content) {
		// Parse nested shortcodes and add formatting.
		$content = trim(wpautop(do_shortcode($content)));

		// Remove '</p>' from the start of the string.
		if (substr($content, 0, 4) == '</p>') {
			$content = substr($content, 4);
		}

		// Remove '<p>' from the end of the string.
		if (substr($content, -3, 3 ) == '<p>') {
			$content = substr($content, 0, -3);
		}

		// Remove any instances of '<p></p>'.
		$content = str_replace('<p></p>', '', $content);

		return $content;
	}

	/**
	 * @param string $error
	 *
	 * @return string
	 */
	protected function errorComment(string $error): string {
		return "<!-- {$error} -->";
	}
}
