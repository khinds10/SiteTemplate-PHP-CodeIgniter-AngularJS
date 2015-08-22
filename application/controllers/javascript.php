<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Javascript Controller
 * include JS files that has to be populated from server side values
 * also include the small AJAX requests that AngularJS will implement
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
class javascript extends MY_Controller {

    /**
     * construct controller with libraries/helpers included from base controller
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * render the Google Analytics JS with application configuration
     */
    public function googleAnalytics() {
        if (! $this->Model_visitor->isRobot) {
	    	$this->setCacheHeaders();
            $this->values['ipAddress'] = $this->Model_visitor->ipAddress;
            $this->load->view('javascript/googleAnalytics', $this->values);
        }
    }
    
    /**
     * tell CDNs to cache our JS for 30 days
     * 	they're only created by PHP dynamically to populate config values
     */
    protected function setCacheHeaders() {
    	$secondsCached = 2592000;
    	$ts = gmdate("D, d M Y H:i:s", time() + $secondsCached) . " GMT";
    	header("Expires: $ts");
    	header("Pragma: cache");
    	header("Cache-Control: public, max-age=$secondsCached");
    }
        
    /**
     * for given country code get the country's name
     */
    public function getCountryNameByCode() {
        global $websiteCountriesList;
        $countryCode = $this->input->get('chosenCountry', TRUE);
        if (empty($countryCode)) {
            $countryCode = "US";
        }
        echo $websiteCountriesList[$countryCode];
    }

    /**
     * get the current code country code / country name of the visitor
     */
    public function getCurrentCountryInfo() {
        echo json_encode(array(
            'country_code' => $this->Model_visitor->getUserCountryCode(),
            'country_name' => $this->Model_visitor->getUserCountryName()
        ));
    }
}
