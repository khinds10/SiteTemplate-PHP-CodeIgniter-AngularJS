<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * SEO Pages Controller
 * 	SEO based pages for search engine optimization
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
class seoPages extends MY_Controller {

    /**
     * construct controller with libraries/helpers included from base controller
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * about us
     */
    public function about() {

        /** SEO meta tags (optional) */
        $this->values['websiteDescription'] = "my website is about xyz here";
        $this->values['websiteKeyWords'] = "seo keywords, comma, separated";
        $this->values['websiteTitle'] = "title for the about page here";
        $this->renderCompletePage('seoPages/about', $this->values);
    }
}
