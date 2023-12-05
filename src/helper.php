<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('WPRadarHelper')) :
	class WPRadarHelper {

		public static function get_int_val_of_string($string) {
			$sum = 0;
			for ($i = 0; $i < strlen($string); $i++) {
				$sum += ord($string[$i]);
			}
			return $sum;
		}

		public static function randomize_params($site_key, $params) {
			$rand_factor = self::get_int_val_of_string($site_key) % 26;

			$new_params = array();
			foreach ($params as $key => $value) {
				$new_key = '';

				foreach (str_split($key) as $char) {
					if (ctype_alpha($char)) {
						$offset = (ctype_upper($char) ? ord('A') : ord('a'));
						$new_char = chr((ord($char) - $offset + $rand_factor) % 26 + $offset);
						$new_key .= $new_char;
					} else {
						$new_key .= $char;
					}
				}
				$new_params[$new_key] = $value;
			}
			return $new_params;
		}

		public static function de_randomize_params($site_key, $params) {
			$rand_factor = self::get_int_val_of_string($site_key) % 26;

			$new_params = [];

			foreach ($params as $key => $value) {
				$new_key = '';

				foreach (str_split($key) as $char) {
					if (ctype_alpha($char)) {
						$offset = (ctype_upper($char) ? ord('A') : ord('a'));
						$new_char = chr((ord($char) - $offset - $rand_factor + 26) % 26 + $offset);
						$new_key .= $new_char;
					} else {
						$new_key .= $char;
					}
				}
				$new_params[$new_key] = $value;
			}

			return $new_params;
		}

		public static function find_vulnerability_id($vuln_list, $sig, $secret_key) {
			$vuln_id = false;
			foreach ($vuln_list as $vuln_rec) {

				$_vuln_id = $vuln_rec["id"];
				$derived_sig = self::get_signature($_vuln_id, $secret_key);
				if ($derived_sig == $sig) {
					$vuln_id = $_vuln_id;
					break;
				}
			}
			return $vuln_id;
		}

		public static function get_signature($vuln_id, $secret_key) {
			return hash('sha256', $vuln_id . 'wp_radar' . $secret_key);
		}


	}
endif;
?>
