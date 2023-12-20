<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('WPRadarInfo')) :
  class WPRadarInfo {
		public $plugname = 'bvwpradar';
		public $brand_name = 'WP-Radar';
		public $version = '1.0';
		public $slug = 'wp_radar/wp_radar.php';
		public $logo = '/img/bvlogo.svg';
		public $brand_icon = '/img/icon.png';
		public $author = 'Malcare Security';
		public $title = 'WordPress Security Testing Plugin - BlogVault';
		public static $secret_key_name = 'wp_radar_secret_key';

		public function __construct() {
			// Nothing do to here for now.
    }
	}
endif;
