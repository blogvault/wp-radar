<?php
  
if (!defined('ABSPATH')) exit;
if (!class_exists('WPRadarWPAction')) :
  class WPRadarWPAction {

		public static function activate() {
			$secret_key = WordpressOpsHelper::getRandomString(32);
			WordpressOpsHelper::updateOption(WPRadarInfo::$secret_key_name, $secret_key);
		}

		public static function deactivate() {
			// Deactivate Handler
		}

		public static function uninstall() {
			// Uninstall Handler
		}
	}
endif;
