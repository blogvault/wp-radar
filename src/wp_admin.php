<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('WPRadarWPAdmin')) :

	class WPRadarWPAdmin {
		public $wp_radar_info;

		function __construct() {
			$this->wp_radar_info = new WPRadarInfo();
		}

		public function mainUrl($_params = '') {
			return admin_url('admin.php?page='.$this->wp_radar_info->plugname.$_params);
		}

		public function menu() {
			$brand_name = $this->wp_radar_info->brand_name;
			$icon_name = $this->wp_radar_info->brand_icon;
			$plug_name = $this->wp_radar_info->plugname;
			add_menu_page($brand_name, $brand_name, 'manage_options', $plug_name, array($this, 'adminPage'),
				plugins_url($icon_name,  __FILE__));
		}

		public function showTestInitiationPage() {
			require_once dirname( __FILE__ ) . "/admin/test_initiation.php";
		}

		public function showTestProgressPage() {
			require_once dirname( __FILE__ ) . "/admin/test_progress.php";
		}

		public function adminPage() {
			wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css', __FILE__));
			wp_enqueue_style( 'wp_radar', plugins_url('css/wp_radar.css', __FILE__));
			if (isset($_REQUEST['test_started'])) {
				if (isset($_REQUEST['target_url'])) {
					$this->showTestProgressPage();
				} else {
					$error = "Test Url Not Setup Properly";
					$this->showTestInitiationPage();
				}
			} else {
				$this->showTestInitiationPage();
			}
		}
	}
endif;
