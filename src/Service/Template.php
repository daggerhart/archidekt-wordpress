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
	 * @param string $_template_name
	 * @param array $_context
	 *
	 * @return string
	 */
	public function render(string $_template_name, array $_context = []) {
		$_template_name = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_template_name);

		// Look for template overridden by theme.
		$_found_template = locate_template($_template_name, false);
		if ($_found_template) {
			ob_start();
			get_template_part($_template_name, NULL, $_context);
			return ob_get_clean();
		}

		// Fallback to the default template in this plugin.
		$_default_template_file = $this->folder . DIRECTORY_SEPARATOR . $_template_name . '.php';
		if (!file_exists($_default_template_file)) {
			return "<!-- template {$_template_name} not found -->";
		}

		ob_start();
		foreach ($_context as $key => $value) {
			${$key} = $value;
		}
		include $_default_template_file;
		return ob_get_clean();
	}

}
