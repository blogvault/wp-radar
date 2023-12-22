<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('HttpRequestSender')) :
	class HttpRequestSender {

		public static function defaultHeaders() {
			return array(
				"User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.97 Safari/537.36"
			); 
		}

		public static function request($url, $query_params = array(), $method = 'GET', $data = null, $headers = array()) {
			$url .= '?' . http_build_query($query_params);

			$headers = array_merge(self::defaultHeaders(), $headers);

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			if ($data !== null) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			}

			$resp_body = curl_exec($ch);

			if ($resp_body === false) {
				return false;
			}
			$resp_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			return array('resp_body' => $resp_body, 'resp_code' => $resp_code);
		}

		public static function requestWithFileUpload($url, $query_params = array(), $post_params = array(), $headers = array(),
				$files = array()) {

			$url .= '?' . http_build_query($query_params);

			$headers = array_merge(self::defaultHeaders(), $headers);

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$temp_files = array();
			foreach ($files as $name => $content) {
				$temp_file = tempnam(sys_get_temp_dir(), 'wp-radar-tempfile-');

				if ($temp_file === false) {
					foreach ($temp_files as $temp_file) {
						unlink($temp_file);
					}
					return false;
				}
				$temp_files[] = $temp_file;

				file_put_contents($temp_file, $content);

				$post_params[$name] = new CURLFile($temp_file, 'text/plain', $name);
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);

			$resp_body = curl_exec($ch);

			foreach ($temp_files as $temp_file) {
				unlink($temp_file);
			}

			if ($resp_body === false) {
				return false;
			}
			$resp_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			return array('resp_body' => $resp_body, 'resp_code' => $resp_code);
		}
	}
endif;

?>