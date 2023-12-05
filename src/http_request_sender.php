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

		public static function uploadFile($url, $name, $content) {
			$curl = curl_init();
		
			$tempFile = tempnam(sys_get_temp_dir(), 'uploadfile');

			if ($tempFile === false) {
				return false;
			}

			file_put_contents($tempFile, $content);
		
			$fileField = new CURLFile($tempFile, 'text/plain', $name);
		
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, ['fileToUpload' => $fileField]);
		
			$resp_body = curl_exec($curl);
		
			if ($resp_body === false) {
				return false;
			}
		
			$resp_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
		
			unlink($tempFile);
		
			return array('resp_body' => $resp_body, 'resp_code' => $resp_code);
		}
	}
endif;

?>