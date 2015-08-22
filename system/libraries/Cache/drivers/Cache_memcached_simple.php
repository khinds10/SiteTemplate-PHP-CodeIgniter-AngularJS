<?php
if (! defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Memcached Driver "Simple"
 * simplified memcache driver for values to later be read in JAVA
 *
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
class CI_Cache_memcached_simple extends CI_Driver {
	
	/**
	 * local memcached object
	 *
	 * @var object memcached
	 */
	private $_memcached;
	
	/**
	 * memcache config
	 *
	 * @var array
	 */
	protected $_memcache_conf = array (
			'default' => array (
					'default_host' => MEMCACHED_HOST,
					'default_port' => MEMCACHED_PORT,
					'default_weight' => 1 
			) 
	);

	/**
	 * fetch from cache by ID
	 *
	 * @param
	 *        	mixed		unique key id
	 * @return mixed on success/false on failure
	 */
	public function get($id) {
		return $this->_memcached->get($id);
	}

	/**
	 * getter for the private memcached instance for heartbeat requests
	 * @return object
	 */
	public function getMemcachedInstance() {
		return 	$this->_memcached;
	}
	
	/**
	 * save a simple data element to memcache without code igniter special array
	 *
	 * @param
	 *        	string		unique identifier
	 * @param
	 *        	mixed		data being cached
	 * @param
	 *        	int			time to live
	 * @return boolean true on success, false on failure
	 */
	public function save($id, $data, $ttl = 60) {
		if (get_class($this->_memcached) == 'Memcached') {
			return $this->_memcached->set($id, (string)$data, $ttl);
		} else if (get_class($this->_memcached) == 'Memcache') {
			return $this->_memcached->set($id, (string)$data, 0, $ttl);
		}
		return FALSE;
	}

	/**
	 * delete from cache
	 *
	 * @param
	 *        	mixed		key to be deleted.
	 * @return boolean true on success, false on failure
	 */
	public function delete($id) {
		return $this->_memcached->delete($id);
	}

	/**
	 * clean the cache
	 *
	 * @return boolean on failure/true on success
	 */
	public function clean() {
		return $this->_memcached->flush();
	}

	/**
	 * cache info
	 *
	 * @param
	 *        	null		type not supported in memcached
	 * @return mixed array on success, false on failure
	 */
	public function cache_info($type = NULL) {
		return $this->_memcached->getStats();
	}

	/**
	 * get cache metadata
	 *
	 * @param
	 *        	mixed		key to get cache metadata on
	 * @return mixed on failure, array on success.
	 */
	public function get_metadata($id) {
		$stored = $this->_memcached->get($id);
		
		if (count($stored) !== 3) {
			return FALSE;
		}
		
		list ( $data, $time, $ttl ) = $stored;
		
		return array (
				'expire' => $time + $ttl,
				'mtime' => $time,
				'data' => $data 
		);
	}

	/**
	 * setup memcached
	 */
	private function _setup_memcached() {
		/** try to load memcached server info from the config file */
		$CI = & get_instance();
		if ($CI->config->load('memcached', TRUE, TRUE)) {
			if (is_array($CI->config->config ['memcached'])) {
				$this->_memcache_conf = NULL;
				foreach ( $CI->config->config ['memcached'] as $name => $conf ) {
					$this->_memcache_conf [$name] = $conf;
				}
			}
		}
		
		$this->_memcached = new Memcached();
		
		foreach ( $this->_memcache_conf as $name => $cache_server ) {
			
			if (! array_key_exists('hostname', $cache_server)) {
				$cache_server ['hostname'] = $this->_default_options ['default_host'];
			}
			
			if (! array_key_exists('port', $cache_server)) {
				$cache_server ['port'] = $this->_default_options ['default_port'];
			}
			
			if (! array_key_exists('weight', $cache_server)) {
				$cache_server ['weight'] = $this->_default_options ['default_weight'];
			}
			
			$this->_memcached->addServer($cache_server ['hostname'], $cache_server ['port'], $cache_server ['weight']);
		}
	}

	/**
	 * is supported
	 *
	 * returns FALSE if memcached is not supported on the system.
	 * if it is, we setup the memcached object & return TRUE
	 */
	public function is_supported() {
		if (! extension_loaded('memcached')) {
			log_message('error', 'The Memcached Extension must be loaded to use Memcached Cache.');
			return FALSE;
		}
		
		$this->_setup_memcached();
		return TRUE;
	}
}