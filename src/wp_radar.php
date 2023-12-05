<?php

/*
Plugin Name: WP-Radar
Plugin URI: https://www.malcare.com/blog/introducing-wp-radar/
Description: This is a plugin to test the Security of the website.
Version: 1.0
Author: Sumit Sharma
 */

if (!defined('ABSPATH')) exit;

require_once dirname( __FILE__ ) . '/wp_actions.php';
require_once dirname( __FILE__ ) . '/info.php';
require_once dirname(__FILE__) . '/vulnerability_simulator.php';
require_once dirname(__FILE__) . '/wordpress_ops_helper.php';
require_once dirname( __FILE__ ) . '/helper.php';

register_uninstall_hook(__FILE__, array('WPRadarWPAction', 'uninstall'));
register_activation_hook(__FILE__, array('WPRadarWPAction', 'activate'));
register_deactivation_hook(__FILE__, array('WPRadarWPAction', 'deactivate'));

if (is_admin()) {
	require_once dirname( __FILE__ ) . '/wp_admin.php';
	$wpadmin = new WPRadarWPAdmin();
	add_action('admin_menu', array($wpadmin, 'menu'));
} else if((array_key_exists('bvplugname', $_REQUEST)) && ($_REQUEST['bvplugname'] == "wp_radar")) { //CLIENT_SIDE
	if (isset($_REQUEST['vuln_id']) && isset($_REQUEST['target_url']) && isset($_REQUEST['site_key'])) {
		require_once dirname(__FILE__) . '/http_request_sender.php';
		VulnerabilitySimulator::simulate($_REQUEST['vuln_id'], $_REQUEST['target_url'], $_REQUEST['site_key']);
	}
} else if (isset($_REQUEST['sig'])) { //SERVER_SIDE
	$secret_key =	WordpressOpsHelper::getOption(WPRadarInfo::$secret_key_name);
	if (isset($secret_key) && $secret_key !== false) {
		$vuln_id = WPRadarHelper::find_vulnerability_id(VulnerabilitySimulator::vulnerabilityList(), $_REQUEST['sig'], $secret_key);
		if (isset($vuln_id) && $vuln_id !== false) {
			require_once dirname(__FILE__) . '/vulnerability_acceptor.php';
			$params = $_REQUEST['params'];
			if (isset($_REQUEST['is_rand'])) {
				$randomized_params = array();
				if (isset($_REQUEST['params']) && is_array($_REQUEST['params'])) {
					$randomized_params = $_REQUEST['params'];
					$params = WPRadarHelper::de_randomize_params($secret_key, $randomized_params);
				}
			}
			VulnerabilityAcceptor::try_exploit($vuln_id, $params);
		} else {
			http_response_code(404);
			die("Secret Did Not Match.");
		}
	}
}
