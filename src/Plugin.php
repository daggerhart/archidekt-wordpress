<?php

namespace Archidekt;

use Archidekt\Admin\SettingsPage;

class Plugin {

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

  public function init() {
    SettingsPage::register();
  }
  
}