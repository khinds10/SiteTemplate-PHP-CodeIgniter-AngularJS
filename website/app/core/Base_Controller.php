<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');
    
/**
 * base controller with essentials for all controllers on the site
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
class Base_Controller extends CI_Controller {
	
	/**
	 * values for the views
	 *
	 * @var array
	 */
	public $values = array ();
	
	/**
	 * construct controller with libraries/helpers included
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * render a complete application webpage with complete header/footer & navigation
	 *
	 * @param string $view,
	 *        	relative path of the view file to render
	 * @param mixed $data        	
	 */
	public function renderCompletePage($view, $data = null) {
		$this->load->view ( 'global/header', $data );
		$this->load->view ( 'global/navigation', $data );
		$this->load->view ( $view, $data );
		$this->load->view ( 'global/footer', $data );
	}
		
	/**
	 * defensively determine
	 * if we're in production or not based on environment settings
	 */
	public function isProduction() {
		if (strtolower ( ENVIRONMENT ) == 'production') {
			return true;
		}
		if (strtolower ( ENVIRONMENT ) == 'prod') {
			return true;
		}
		return false;
	}
	
	/**
	 * defensively determine
	 * if we're in development or not based on environment settings
	 */
	public function isDevelopment() {
		if (strtolower ( ENVIRONMENT ) == 'development') {
			return true;
		}
		if (strtolower ( ENVIRONMENT ) == 'dev') {
			return true;
		}
		return false;
	}
	
	/**
	 * defensively determine
	 * if we're in QA or not based on environment settings
	 */
	public function isQA() {
		if (strtolower ( ENVIRONMENT ) == 'qa') {
			return true;
		}
		return false;
	}
}
