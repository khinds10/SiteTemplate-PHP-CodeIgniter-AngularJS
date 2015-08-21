<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Site Pages Controller
 * main site pages to present to end users
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
class sitePages extends MY_Controller {

    /**
     * construct controller with libraries/helpers included from base controller
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * FAQs
     */
    public function faqs() {

        /** SEO meta tags (optional) */
        $this->values['websiteDescription'] = "website description here";
        $this->values['websiteKeyWords'] = "faq keywords";
        $this->values['websiteTitle'] = "faq keywords page title";
        $this->renderCompletePage('sitePages/faqs', $this->values);
    }
}
