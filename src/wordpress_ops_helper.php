<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('WordpressOpsHelper')) :
  class WordpressOpsHelper {

		public static function get_wp_user($role) {
			$args = array('role' => $role);
			$users_query = new WP_User_Query($args);
			$results = $users_query->get_results();
			if (!empty($results) && is_array($results)) {
				$user = $results[0];
				return $user;
			}
			return false;
		}

		public static function get_wp_post(){
			$args = array('post_type' => 'post', 'post_status' => 'publish');
			$posts_query = new WP_Query($args);
			if ($posts_query->have_posts()) {
				$posts_query->the_post();
				return get_post();
			}
			return false;
		}

		public static function getOption($key) {
			$res = false;
			if (function_exists('get_site_option')) {
				$res = get_site_option($key, false);
			}
			if ($res === false) {
				$res = get_option($key, false);
			}
			return $res;
		}

		public static function updateOption($key, $value) {
			if (function_exists('update_site_option')) {
				return update_site_option($key, $value);
			} else {
				return update_option($key, $value);
			}
		}

		public static function getRandomString($length = 30, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
			$str = '';
			$size = strlen($chars);
			for ($i = 0; $i < $length; $i++) {
				$str .= $chars[mt_rand(0, $size - 1)];
			}
			return $str;
		}

		public static function getDBPrefix() {
			global $wpdb;
			return $wpdb->prefix;
		}

		public static function isCurrentUserAdmin() {
			return current_user_can('manage_options');
		}

		public static function isWPAdminPage() {
			return is_admin();
		}
  }
endif;
?>