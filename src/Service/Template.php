<?php

namespace Archidekt\Service;

/**
 * Simple rendering class.
 */
class Template {

	/**
	 * Templates location.
	 *
	 * @var string
	 */
	private string $folder;

	/**
	 * @param string $folder
	 */
	public function __construct(string $folder = ARCHIDEKT_TEMPLATES_DIR) {
		$this->folder = $folder;
	}

	/**
	 * @param string|array $template_suggestions
	 * @param array $context
	 *
	 * @return string
	 */
	public function render($template_suggestions, array $context = []): string {
		if (!is_array($template_suggestions)) {
			$template_suggestions = [$template_suggestions];
		}

		// More specific suggestions are expected later in the array.
		$template_suggestions = array_reverse($template_suggestions);

		// Format template suggestions.
		$template_suggestions = array_map(function(string $suggestion) {
			return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $suggestion);
		}, $template_suggestions);

		// Look for each suggestion in the theme, then the plugin.
		foreach ($template_suggestions as $suggestion) {
			$theme_suggestions = [
				'templates' . DIRECTORY_SEPARATOR . 'archidekt' . DIRECTORY_SEPARATOR . $suggestion . '.php',
				'templates' . DIRECTORY_SEPARATOR . $suggestion . '.php',
				$suggestion . '.php',
			];

			$_found_template = locate_template($theme_suggestions, false);
			if ($_found_template) {
				$context['_found_template'] = $_found_template;
				return $this->renderTemplate($_found_template, $context);
			}

			$plugin_template_file = $this->folder . DIRECTORY_SEPARATOR . $suggestion . '.php';
			if (file_exists($plugin_template_file)) {
				$context['_found_template'] = $plugin_template_file;
				return $this->renderTemplate($plugin_template_file, $context);
			}
		}

		// If template isn't found by name, provide some debugging output.
		return "<!-- Template suggestions not found. " . implode(', ', $template_suggestions) . "-->";
	}

	/**
	 * Render in a clean context.
	 *
	 * @param string $found_template_file
	 * @param array $context
	 *
	 * @return false|string
	 */
	private function renderTemplate(string $found_template_file, array $context) {
		return (function() {
			ob_start();
			foreach (func_get_args()[1] as $key => $value) {
				${$key} = $value;
			}
			unset($key, $value);
			include func_get_args()[0];
			return ob_get_clean();
		})($found_template_file, $context);
	}

}
