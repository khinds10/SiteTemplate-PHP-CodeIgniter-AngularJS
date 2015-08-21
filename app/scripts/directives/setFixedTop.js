/**
 * Set Fixed Top
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
MainApp.directive("setFixedTop", function ($window) {
    return function(scope, element, attrs) {
    	
    	/** get element position from top of the screen */
    	var element = document.getElementById(attrs.id);
    	var offsetTop = element.getBoundingClientRect().top;
    	
    	/** bind window scroll to apply the fixed top else relative styling to the element */
        angular.element($window).bind("scroll", function() {
            if (this.pageYOffset > offsetTop) {
            	element.style.top = '0px';
            	element.style.position = 'fixed';
            } else {
            	element.style.position = 'relative';
            }
        });
    };
});