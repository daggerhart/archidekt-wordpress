<?php
/**
 * Plugin Name:       Archidekt
 * Description:       Display Archidekt information in a WordPress site.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            daggerhart
 * Author URI:        https://daggerhartfarms.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
define('ARCHIDEKT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ARCHIDEKT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ARCHIDEKT_PLUGIN_FILE', __FILE__);
define('ARCHIDEKT_DB_VERSION', 10001);
define('ARCHIDEKT_SCRIPTS_VERSION', 10001);
define('ARCHIDEKT_TEMPLATES_DIR', ARCHIDEKT_PLUGIN_DIR . 'templates');

require_once __DIR__ . '/vendor/autoload.php';
\Archidekt\Plugin::bootstrap();
