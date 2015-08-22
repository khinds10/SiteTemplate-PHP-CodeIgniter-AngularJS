<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * RESTful Services Class
 * restful class to delegate CURL requests to local server's webservices
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
class restful {

    /**
     * local cURL handle
     */
    public $ch;

    /**
     * construct restful webservice class with local cURL and CodeIgniter objects
     */
    public function __construct() {
        $this->_ci = & get_instance();
        $this->_ci->load->library('logger');
        $this->ch = curl_init();
    }
    
    /**
     * issue an HTTP GET request to a webservice
     *
     * @param string $url            
     * @param array $headers,
     *            optional headers to pass to the request
     */
    public function httpGet($url, $headers = array()) {
        $this->setCurlOptions($url, array(), $headers);
        $response = curl_exec($this->ch);
        
        /**
         * debug all the cURL calls
         */
        logger::logVarsDebug(array(
            'url' => $url,
            'headers' => $headers,
            'response' => logger::toString($response)
        ));
        
        return $response;
    }

    /**
     * issue an HTTP GET request to a webservice expecting a JSON formatted response
     *
     * @param string $url            
     */
    public function httpGetJSON($url) {
        return json_decode($this->httpGet($url, array(
            'Content-Type:application/json'
        )));
    }

    /**
     * issue an HTTP POST request to a webservice with fields
     *
     * @param string $url            
     * @param array $postFields,
     *            array of name value fields if HTTP POST
     * @param array $headers,
     *            optional headers to pass to the request
     */
    public function httpPost($url, $postFields, $headers = array()) {
        $this->setCurlOptions($url, $postFields, $headers);
        $response = curl_exec($this->ch);
                
        /**
         * debug all the cURL calls
         */
        logger::logVarsDebug(array(
            'url' => $url,
            'headers' => $headers,
            'response' => logger::toString($response)
        ));        
        return $response;
    }

    /**
     * issue an HTTP POST request to a webservice with fields expecting a JSON formatted response
     *
     * @param string $url            
     * @param array $headers,
     *            optional headers to pass to the request
     */
    public function httpPostJSON($url, $postFields) {
        return json_decode($this->httpPost($url, $postFields, array(
            'Content-Type:application/json'
        )));
    }

    /**
     * setup the cURL options for the POST/GET cURL request to be done
     *
     * @param string $url            
     * @param array $postFields,
     *            array of name value fields if HTTP POST
     * @param array $headers,
     *            optional headers to apply to the request
     */
    protected function setCurlOptions($url, $postFields = array(), $headers = array()) {
        
    	/**
    	 * setup the cURL options on the $ch object
    	 */
    	curl_setopt_array($this->ch, array(
	    	CURLOPT_URL => $url,
	    	CURLOPT_RETURNTRANSFER => true,
	    	CURLOPT_CONNECTTIMEOUT => CURL_WAIT_TO_CONNECT_SECONDS,
	    	CURLOPT_TIMEOUT => CURL_WAIT_FOR_RESPONSE_SECONDS
    	));
    	
        /**
         * if post fields present, then we must be doing an HTTP POST
         */
        if (count($postFields)) {
            $postFieldsEncoded = array();
            foreach ($postFields as $key => $value) {
                $postFieldsEncoded[] = $key . "=" . $value;
            }
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($this->ch, CURLOPT_POST, count($postFieldsEncoded));
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, implode("&", $postFieldsEncoded));
        }
        
        /**
         * add any headers if needed
         */
        if (count($headers)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        }
    }

    /**
     * class destructor to close the cURL object
     */
    public function __destruct() {
        curl_close($this->ch);
    }

    /**
     * parse XML with graceful error handling to error log
     *
     * @param string $XMLString            
     */
    public static function parseXMLFromString($XMLString = "") {
        $ci = & get_instance();
        $ci->load->library('logger');
        libxml_use_internal_errors(true);
        $results = simplexml_load_string($XMLString);
        if ($results === false) {
            $errorMessage = "Failed loading XML\n";
            $errorMessage .= print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), TRUE);
            foreach (libxml_get_errors() as $error) {
                $errorMessage .= "\t" . $error->message;
            }
            logger::logError($errorMessage);
        }
        return $results;
    }
}