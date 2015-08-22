/**
 * Country Dropdown Helper
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
MainApp.factory("CountryDropdownService", function($http) {
	
	/** get country name from user session and preselect the country dropdown */
	selectUserDefaultCountry = function(scope, selectId) {
		$http.get('/get-user-country-info/').then(function(result) {
			scope.countryName = result.data['country_name'].replace('&amp;','&');
			setSelectedIndexByValue(document.getElementById(selectId), scope.countryName);
		});
	}
	
	/** preselect dropdown by name */
	setSelectedIndexByValue = function(s, v) {
		for (var i = 0; i < s.options.length; i++) {
			if (s.options[i].text == v) {
				s.options[i].selected = true;
				return;
			}
		}
	}
	
    /** change user country, the dropdown UI mask must show the country name not the dropdown country code "value" */
    changeCountry = function(scope) {
    	$http.get('/get-country-name-by-code/?chosenCountry=' + scope.visitor.country).then(function(result) {
    	    scope.countryName = result.data.replace('&amp;','&'); 
    	});
    }
	
	/** expose the functions for public consumption */
	return {
		selectUserDefaultCountry : selectUserDefaultCountry,
		setSelectedIndexByValue : setSelectedIndexByValue,
		changeCountry : changeCountry
	};
});