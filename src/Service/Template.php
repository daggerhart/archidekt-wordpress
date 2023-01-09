<?php

namespace Archidekt\Service;

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
	public function render($template_suggestions, array $context = []) {
		if (!is_array($template_suggestions)) {
			$template_suggestions = [$template_suggestions];
		}

		// More specific suggestions are expected later in the array.
		$template_suggestions = array_reverse($template_suggestions);

		// Format template suggestions.
		$template_suggestions = array_map(function(string $suggestion) {
			return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $suggestion);
		}, $template_suggestions);

		// Look for template overridden by theme.
		$_found_template = locate_template($template_suggestions, false);
		if ($_found_template) {
			ob_start();
			get_template_part($_found_template, null, $context);
			return ob_get_clean();
		}

		// Fallback to the default template in this plugin.
		$found_template_file = false;
		foreach ($template_suggestions as $suggestion) {
			$template_file = $this->folder . DIRECTORY_SEPARATOR . $suggestion . '.php';
			if (file_exists($template_file)) {
				$found_template_file = $template_file;
				break;
			}
		}

		if (!$found_template_file) {
			return "<!-- Template suggestions not found. " . implode(', ', $template_suggestions) . "-->";
		}

		// Render in a clean context.
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
