<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Base Pages Controller
 * base controller with essentials for all controllers on the site
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
class MY_Controller extends CI_Controller {

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

        /** global view values */
		$this->values ['angularJS'] = '<script src="' . getStaticResourceURL ( '/app/scripts/app.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/services/cookies.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/services/countryDropdown.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/services/socialMedia.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/directives/setFixedTop.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/controllers/index.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/controllers/modal.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/controllers/seoPages.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['angularJS'] .= '<script src="' . getStaticResourceURL ( '/app/scripts/controllers/sitePages.js' ) . '" type="text/javascript"></script>' . "\n\t";
		$this->values ['contactWW'] = $this->Model_visitor->getContactUsPhoneWorldWide ();
		$this->values ['contactTollFree'] = $this->Model_visitor->getContactUsPhone ();
		$this->values ['userCountryCode'] = $this->Model_visitor->getUserCountryCode ();
		$this->values ['referrerId'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'referrer_id' );
		$this->values ['trafficId'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'traffic_id' );
		$this->values ['trafficType'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'traffic_type' );
		$this->values ['utmCampaign'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'utm_campaign' );
		$this->values ['utmSource'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'utm_source' );
		$this->values ['utmMedium'] = $this->Model_visitor->getTrafficCampaignForUserByName ( 'utm_medium' );

	    /** default modal shown by thank you URL parameters for each page */
		$this->values ['modalWindowsByURLMapping'] = array (
				array (
						'showModalParams' => 'msg=thank-you',
						'modalWidowPartial' => '/global/modals/thankyou/contact.php',
						'trackingEvent' => array('category'=> 'Modal Window', 'action'=> 'Thank You', 'value'=> 'Thank you for contacting Us!')
				)
		);
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
	 * get existing script URL reliably
	 *
	 * @return null|string
	 */
	public function getScriptURL() {
		$scriptURL = null;
		if (! empty ( $_SERVER ['SCRIPT_URL'] )) {
			$scriptURL = $_SERVER ['SCRIPT_URL'];
		} elseif (! empty ( $_SERVER ['REDIRECT_URL'] )) {
			$scriptURL = $_SERVER ['REDIRECT_URL'];
		} elseif (! empty ( $_SERVER ['REQUEST_URI'] )) {
			$p = parse_url ( $_SERVER ['REQUEST_URI'] );
			$scriptURL = $p ['path'];
		} else {
			$this->logger->logError ( 'Couldn\'t determine $_SERVER["SCRIPT_URL"]' );
		}
		$_SERVER ['SCRIPT_URL'] = $scriptURL;
		return $scriptURL;
	}

	/**
	 * create the complete URL to show a particular modal
	 *
	 * @return string
	 */
	public function getShowModalFullURL($showModalParams = 'msg=thank-you') {
		$showModalURL = 'http://' . $_SERVER ['HTTP_HOST'] . $this->getScriptURL ();
		if (! empty ( $_SERVER ['QUERY_STRING'] )) {
			$showModalURL .= '?' . $_SERVER ['QUERY_STRING'];
		}
		if (substr_count ( $showModalURL, '?' )) {
			$showModalURL .= '&' . $showModalParams;
		} else {
			$showModalURL .= '?' . $showModalParams;
		}
		return $showModalURL;
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
}