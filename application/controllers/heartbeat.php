<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * HeartBeat Controller
 * @copyright Kevin Hinds @ KevinHinds.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *	http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class heartbeat extends CI_Controller {

	/**
	 * heartbeat information to be returned as JSON array
	 *
	 * @var array
	 */
	public $heartBeatResponse = array ();

	/**
	 * the HTTP status code to return with the response
	 *
	 * @var int
	 */
	protected $HTTPStatusCode = 200;

	/**
	 * generate new heartbeat JSON respons
	 */
	public function generate() {

		/**
		 * setup the custom error codes handler in case it doesn't exist for
		 * this version of PHP
		 */
		$this->setupCustomHeadersOptions();

		/**
		 * full check memcache
		 */
		$this->checkMemcache();

		/**
		 * show site configured URLs and check services
		 */
		$this->showSiteInfo();

		/**
		 * return the JSON response with the HTTP status code
		 */
		http_response_code($this->HTTPStatusCode);
		echo json_encode(array (
				'HEARTBEAT RESPONSE [' . date(DATE_RFC2822) . ']',
				$this->heartBeatResponse
		), JSON_PRETTY_PRINT);
	}

	/**
	 * show the site basic URL configuration
	 */
	protected function showSiteInfo() {
		$this->addMessage('INFO', 'GOOGLE_ANALYTICS_ACCOUNT', GOOGLE_ANALYTICS_ACCOUNT);
		$this->addMessage('INFO', 'TOLLFREE_DEFAULT', TOLLFREE_DEFAULT);
		$this->addMessage('INFO', 'WW_DEFAULT', WW_DEFAULT);
		$this->addMessage('INFO', 'WEBSITE_NAME', WEBSITE_NAME);
		$this->addMessage('INFO', 'WEBSITE_DESCRIPTION', WEBSITE_DESCRIPTION);
		$this->addMessage('INFO', 'WEBSITE_TITLE', WEBSITE_TITLE);
		$this->addMessage('INFO', 'WEBSITE_META_VIEWPORT', WEBSITE_META_VIEWPORT);
	}

	/**
	 * check the headers of a URL and create a success/critical message
	 *
	 * @param string $url
	 */
	protected function checkURLStatus($constantName, $url, $expected = '200 OK') {
		$urlStatus = get_headers($url);
		$successfulResponse = preg_match('/' . $expected . '$/', $urlStatus[0]);
		$urlStatusInfo = array (
				$url.' '. ((! $successfulResponse) ? 'FAILED' : 'SUCCESSFUL') . ' RESPONSE',
				(! $successfulResponse) ? 'CRITICAL' : 'SUCCESS',
				$urlStatus
		);
		$this->addMessage((! $successfulResponse) ? 'CRITICAL' : 'SUCCESS', '' . $constantName . ' --> ' . $url, $urlStatusInfo);
	}

	/**
	 * test through memcache for anything wrong
	 */
	protected function checkMemcache() {
		$this->load->driver('cache');
		$memcache_obj = $this->cache->memcached_simple->getMemcachedInstance();
		$memcacheVersion = $memcache_obj->getVersion();
		if (! empty($memcacheVersion)) {
			$this->addMessage('SUCCESS', 'memcached version', $memcacheVersion);
		} else {
			$this->addMessage('CRITICAL', 'memcached version', 'memcached version could not be obtained');
		}

		/**
		 * is supported check
		 */
		if ($this->cache->memcached_simple->is_supported()) {
			$this->addMessage('SUCCESS', 'memcached code igniter support', 'memcached is supported in PHP');
		} else {
			$this->addMessage('CRITICAL', 'memcached code igniter support', 'memcached is not supported for PHP');
		}

		/**
		 * get/set checking
		 */
		$this->cache->memcached_simple->save('test', 'testing', 0);
		$testingValue = $this->cache->memcached_simple->get('test');
		if ($testingValue == 'testing') {
			$this->addMessage('SUCCESS', 'memcached simple save', 'a testing value was get and set');
		} else {
			$this->addMessage('ERROR', 'memcached simple save', 'simple testing value could not be get and set');
		}

		/**
		 * test increment function
		 */
		$memcache_obj->set('test_integer', 1);
		$memcache_obj->increment('test_integer', 2);
		$testInteger = $memcache_obj->get('test_integer');
		if ($testInteger == 3) {
			$this->addMessage('SUCCESS', 'memcached increment', 'test integer was incremented');
		} else {
			$this->addMessage('ERROR', 'memcached increment', 'test integer could not be incremented');
		}

		/**
		 * test decrment function
		 */
		$memcache_obj->decrement('test_integer', 1);
		$testInteger = $memcache_obj->get('test_integer');
		if ($testInteger == 2) {
			$this->addMessage('SUCCESS', 'memcached decrement', 'test integer was decremented');
		} else {
			$this->addMessage('ERROR', 'memcached decrement', 'test integer could not be decremented');
		}

		/**
		 * test delete key
		 */
		$memcache_obj->delete('test_integer');
		$testInteger = $memcache_obj->get('test_integer');
		if (empty($testInteger)) {
			$this->addMessage('SUCCESS', 'memcached delete', 'test integer was deleted');
		} else {
			$this->addMessage('ERROR', 'memcached delete', 'test integer could not be deleted');
		}

		/**
		 * check memcache replace
		 */
		$memcache_obj->set('test_integer', 1);
		$memcache_obj->replace('test_integer', 'abc');
		$testInteger = $memcache_obj->get('test_integer');
		if ($testInteger == 'abc') {
			$this->addMessage('SUCCESS', 'memcached replace', 'test integer was replaced with string');
		} else {
			$this->addMessage('ERROR', 'memcached replace', 'test integer could not be replaced with string');
		}

		/**
		 * output basic stats
		 */
		$this->addMessage('INFO', 'memcached status', $memcache_obj->getStats());
	}

	/**
	 * based on an incoming warning level, name and message related to the
	 * checked resource and add it to the overall heartbeat array to be returned
	 * as a JSON response
	 *
	 *
	 * @param INFO|WARNING|ERROR|CRITICAL $warnLevel
	 * @param string $name
	 * @param string $message
	 */
	protected function addMessage($warnLevel = 'SUCCESS', $name = '', $message = '') {
		$warnLevel = strtoupper($warnLevel);
		switch ($warnLevel) {
			case 'SUCCESS' :
			case 'INFO' :
				$this->heartBeatResponse[] = array (
				$name,
				$warnLevel,
				$message
				);
				break;

			case 'WARNING' :
			case 'ERROR' :
			case 'CRITICAL' :
				$this->HTTPStatusCode = 500;
				$this->heartBeatResponse[] = array (
						$name,
						$warnLevel,
						$message
				);
				break;

			default :
				$this->heartBeatResponse[] = array (
				$name,
				'SEVERITY UNKNOWN',
				$message
				);
				break;
		}
	}

	/**
	 * create PHP status code helper functions [backwards compatible]
	 *
	 * @return Ambigous <string, number>|unknown|multitype:
	 */
	private function setupCustomHeadersOptions() {

		/**
		 * http_response_code
		 * Get or Set the HTTP response code (PHP 5 >= 5.4.0)
		 * (function for current PHP version may not exist so create it here)
		 *
		 * @return Ambigous <string, number>
		 */
		if (! function_exists('http_response_code')) {

			function http_response_code($code = NULL) {
				if ($code !== NULL) {

					switch ($code) {
						case 100 :
							$text = 'Continue';
							break;
						case 101 :
							$text = 'Switching Protocols';
							break;
						case 200 :
							$text = 'OK';
							break;
						case 201 :
							$text = 'Created';
							break;
						case 202 :
							$text = 'Accepted';
							break;
						case 203 :
							$text = 'Non-Authoritative Information';
							break;
						case 204 :
							$text = 'No Content';
							break;
						case 205 :
							$text = 'Reset Content';
							break;
						case 206 :
							$text = 'Partial Content';
							break;
						case 300 :
							$text = 'Multiple Choices';
							break;
						case 301 :
							$text = 'Moved Permanently';
							break;
						case 302 :
							$text = 'Moved Temporarily';
							break;
						case 303 :
							$text = 'See Other';
							break;
						case 304 :
							$text = 'Not Modified';
							break;
						case 305 :
							$text = 'Use Proxy';
							break;
						case 400 :
							$text = 'Bad Request';
							break;
						case 401 :
							$text = 'Unauthorized';
							break;
						case 402 :
							$text = 'Payment Required';
							break;
						case 403 :
							$text = 'Forbidden';
							break;
						case 404 :
							$text = 'Not Found';
							break;
						case 405 :
							$text = 'Method Not Allowed';
							break;
						case 406 :
							$text = 'Not Acceptable';
							break;
						case 407 :
							$text = 'Proxy Authentication Required';
							break;
						case 408 :
							$text = 'Request Time-out';
							break;
						case 409 :
							$text = 'Conflict';
							break;
						case 410 :
							$text = 'Gone';
							break;
						case 411 :
							$text = 'Length Required';
							break;
						case 412 :
							$text = 'Precondition Failed';
							break;
						case 413 :
							$text = 'Request Entity Too Large';
							break;
						case 414 :
							$text = 'Request-URI Too Large';
							break;
						case 415 :
							$text = 'Unsupported Media Type';
							break;
						case 500 :
							$text = 'Internal Server Error';
							break;
						case 501 :
							$text = 'Not Implemented';
							break;
						case 502 :
							$text = 'Bad Gateway';
							break;
						case 503 :
							$text = 'Service Unavailable';
							break;
						case 504 :
							$text = 'Gateway Time-out';
							break;
						case 505 :
							$text = 'HTTP Version not supported';
							break;
						default :
							exit('Unknown http status code "' . htmlentities($code) . '"');
							break;
					}
					$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
					header($protocol . ' ' . $code . ' ' . $text);
					$GLOBALS['http_response_code'] = $code;
				} else {
					$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
				}
				return $code;
			}
		}

		/**
		 * This is a modified version of code
		 * from "stuart at sixletterwords dot com", at 14-Sep-2005 04:52.
		 *
		 * This version tries to emulate get_headers() function at PHP4. I think
		 * it works fairly well, and is simple. It is not the best emulation
		 * available, but it works.
		 *
		 * Features:
		 * - supports (and requires) full URLs.
		 * - supports changing of default port in URL.
		 * - stops downloading from socket as soon as end-of-headers is
		 * detected.
		 *
		 * Limitations:
		 * - only gets the root URL (see line with "GET / HTTP/1.1").
		 * - don't support HTTPS (nor the default HTTPS port).
		 */
		if (! function_exists('get_headers')) {

			function get_headers($url, $format = 0) {
				$url = parse_url($url);
				$end = "\r\n\r\n";
				$fp = fsockopen($url['host'], (empty($url['port']) ? 80 : $url['port']), $errno, $errstr, 5);
				if ($fp) {
					$out = "GET / HTTP/1.1\r\n";
					$out .= "Host: " . $url['host'] . "\r\n";
					$out .= "Connection: Close\r\n\r\n";
					$var = '';
					fwrite($fp, $out);
					while (! feof($fp)) {
						$var .= fgets($fp, 1280);
						if (strpos($var, $end))
							break;
					}
					fclose($fp);

					$var = preg_replace("/\r\n\r\n.*\$/", '', $var);
					$var = explode("\r\n", $var);
					if ($format) {
						foreach ($var as $i) {
							if (preg_match('/^([a-zA-Z -]+): +(.*)$/', $i, $parts))
								$v[$parts[1]] = $parts[2];
						}
						return $v;
					} else
						return $var;
				}
			}
		}
	}
}