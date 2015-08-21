<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Website Visitor's Model
 * 	visitor model to encapsulate custom analytics on each website visitor
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
class Model_visitor extends CI_Model {

    /**
     * returns TRUE/FALSE (boolean) if the user agent is a known web browser
     *
     * @var bool
     */
    public $isBrowser = false;

    /**
     * returns TRUE/FALSE (boolean) if the user agent is a known mobile device
     *
     * @var bool
     */
    public $isMobile = false;

    /**
     * returns TRUE/FALSE (boolean) if the user agent is a known tablet device
     *
     * @var bool
     */
    public $isTablet = false;

    /**
     * returns TRUE/FALSE (boolean) if the user agent is a known robot
     *
     * @var bool
     */
    public $isRobot = false;

    /**
     * returns TRUE/FALSE (boolean) if the user agent was referred from another site
     *
     * @var bool
     */
    public $isReferral = false;

    /**
     * returns a string containing the name of the web browser viewing your site
     *
     * @var string
     */
    public $browser = '';

    /**
     * returns a string containing the version number of the web browser viewing your site
     *
     * @var string
     */
    public $version = '';

    /**
     * returns a string containing the name of the mobile device viewing your site
     *
     * @var string
     */
    public $mobile = '';

    /**
     * returns a string containing the name of the robot viewing your site
     *
     * @var string
     */
    public $robot = '';

    /**
     * returns a string containing the platform viewing your site (Linux, Windows, OS X, etc.)
     *
     * @var string
     */
    public $platform = '';

    /**
     * the referrer, if the user agent was referred from another site
     *
     * @var string
     */
    public $referrer = '';

    /**
     * returns a string containing the full user agent string
     *
     * @var string
     */
    public $agentString = '';

    /**
     * lets you determine if the user agent accepts a particular language
     *
     * @var bool
     */
    public $acceptLangEnglish = true;

    /**
     * lets you determine if the user agent accepts a particular character set
     *
     * @var bool
     */
    public $acceptCharsetUTF8 = true;

    /**
     * the HAProxy determined IP address via sanitized HTTP_X_FORWARDED_FOR header
     *
     * @var string
     */
    public $ipAddress;

    /**
     * session keys that exist for each user to identify salesforce and google analytics identifiable information
     *
     * @var array
     */
    protected $visitorCampaignParameters = array(
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'traffic_id',
        'traffic_type',
        'referrer_id'
    );

    /**
     * construct visitor model
     * with all information about the website visitor
     */
    public function __construct() {
        parent::__construct();

        /**
         * get the visitor's IP from HAProxy
         */
        $this->ipAddress = $this->getClientIP();

        /**
         * browser info
         */
        $this->isBrowser = $this->agent->is_browser();
        if ($this->isBrowser) {
            $this->browser = $this->agent->browser();
        }

        /**
         * mobile device info
         */
        $this->isMobile = $this->agent->is_mobile();
        if ($this->isMobile) {
            $this->isTablet = $this->isTablet();
            $this->mobile = $this->agent->mobile();
        }

        /**
         * possible robot info, if it's not currently a known robot
         * more aggressively check against the HAProxy headers a 2nd time
         */
        $this->isRobot = $this->agent->is_robot();
        if (! $this->isRobot) {
            $this->isRobot = $this->isBotRequest();
        }
        if ($this->isRobot) {
            $this->robot = $this->agent->robot();
        }

        /**
         * get referral info if we're dealing with a user that was referred to the site
         */
        $this->isReferral = $this->agent->is_referral();
        if ($this->isReferral) {
            $this->referrer = $this->agent->referrer();
        }

        /**
         * remaining user agent info and if they accept our standard English/UTF-8 format
         */
        $this->version = $this->agent->version();
        $this->platform = $this->agent->platform();
        $this->agentString = $this->agent->agent_string();
        $this->acceptLangEnglish = $this->agent->accept_lang('en');
        $this->acceptCharsetUTF8 = $this->agent->accept_charset('utf-8');

        /**
         * the visitor is from US by default until determined otherwise
         */
        $this->setUserCountryCode('US');
        $this->setUserCountryName('United States of America');

        /**
         * persist available user traffic campaigns to session
         */
        $this->persistUserTrafficCampaigns();
    }

    /**
     * get user first name if known
     */
    public function getUserFirstName() {
        return $this->appsession->get('USER_FIRSTNAME');
    }

    /**
     * set user first name to session
     *
     * @param string $userFirstName
     */
    public function setUserFirstName($userFirstName = '') {
        $this->appsession->set('USER_FIRSTNAME', $userFirstName);
    }

    /**
     * get user last name if known
     */
    public function getUserLastName() {
        return $this->appsession->get('USER_LASTNAME');
    }

    /**
     * set user last name to session
     *
     * @param string $userLastName
     */
    public function setUserLastName($userLastName = '') {
        $this->appsession->set('USER_LASTNAME', $userLastName);
    }

    /**
     * get user email if known
     */
    public function getUserEmail() {
        return $this->appsession->get('USER_EMAIL');
    }

    /**
     * set user email to session
     *
     * @param string $userEmail
     */
    public function setUserEmail($userEmail = '') {
        $this->appsession->set('USER_EMAIL', $userEmail);
    }

    /**
     * get user phone if known
     */
    public function getUserPhone() {
        return $this->appsession->get('USER_PHONE');
    }

    /**
     * set user phone to session
     *
     * @param string $userPhone
     */
    public function setUserPhone($userPhone = '') {
        $this->appsession->set('USER_PHONE', $userPhone);
    }

    /**
     * get phone number vistor should use to contact us
     *
     * @return string
     */
    public function getContactUsPhone() {
        return $this->appsession->get('TOLLFREE_PHONE');
    }

    /**
     * set phone number vistor should use to contact us
     *
     * @param string $phoneNumber
     */
    public function setContactUsPhone($phoneNumber = '') {
        $this->appsession->set('TOLLFREE_PHONE', $phoneNumber);
    }

    /**
     * get phone number vistor should use to contact us from a worldwide location
     *
     * @var string
     */
    public function getContactUsPhoneWorldWide() {
        return $this->appsession->get('WW_PHONE');
    }

    /**
     * set phone number vistor should use to contact us from a worldwide location
     *
     * @param string $phoneNumber
     */
    public function setContactUsPhoneWorldWide($phoneNumber = '') {
        $this->appsession->set('WW_PHONE', $phoneNumber);
    }
    
	/**
	 * get user traffic campaign value by session key if it exists for list of available keys
	 *
	 * @param string $name        	
	 */
	public function getTrafficCampaignForUserByName($name) {
		if (in_array ( $name, $this->visitorCampaignParameters )) {
			return $this->appsession->get ( $name );
		}
	}

    /**
     * set user traffic campaign value by session key if it exists for list of available keys
     *
     * @param string $name
     * @param string $value
     */
    public function setTrafficCampaignForUserByName($name, $value) {
        if (in_array($name, $this->visitorCampaignParameters)) {
            if (! empty($value)) {
                $this->appsession->set($name, $value);
            }
        }
    }

    /**
     * for all available session keys based on user traffic campaigns
     * persist them to session if they exist inside the users incoming request
     */
    protected function persistUserTrafficCampaigns() {
        foreach ($this->visitorCampaignParameters as $visitorCampaignParameter) {
            $this->setTrafficCampaignForUserByName($visitorCampaignParameter, $this->input->get_post($visitorCampaignParameter, TRUE));
        }
    }

    /**
     * get visitor known country code
     */
    public function getUserCountryCode() {
        return $this->appsession->get('USER_COUNTRY_CODE');
    }

    /**
     * set the known country code for visitor
     *
     * @param string $countryCode
     */
    public function setUserCountryCode($countryCode = 'US') {
        $this->appsession->set('USER_COUNTRY_CODE', $countryCode);
    }

    /**
     * get visitor known country name
     */
    public function getUserCountryName() {
        return $this->appsession->get('USER_COUNTRY');
    }

    /**
     * set the known country name for visitor
     *
     * @param string $countryName
     */
    public function setUserCountryName($countryName = 'United States') {
        $this->appsession->set('USER_COUNTRY', $countryName);
    }

    /**
     * get the client IP address
     * via sanitized HAProxy HTTP_X_FORWARDED_FOR if possible
     *
     * @return Ambigous <string, integer>
     */
    protected function getClientIP() {
        $ipaddress = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ipaddress;
    }

    /**
     * determine if mobile device requesting page view
     *
     * @return boolean
     */
    protected function isTablet() {
        $aMobileUA = array(
            '/ipad/i' => 'iPad'
        );
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * is the request from a bot?
     * via custom set HAProxy X-Bot-Recognized header
     *
     * @return boolean
     */
    protected function isBotRequest() {
        $isBot = false;
        foreach (getallheaders() as $name => $value) {
            if ($name == "X-Bot-Recognized") {
                if (strtolower($value) == 'true') {
                    $isBot = true;
                }
            }
        }
        return $isBot;
    }
}
