/**
 * Currency No-Fractions
 * 	special filter on currency prices to remove cents after the decimal place 
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
MainApp.filter('noFractionCurrency', [ '$filter', '$locale', function(filter, locale) {
	var currencyFilter = filter('currency');
	var formats = locale.NUMBER_FORMATS;
	return function(amount, num, currencySymbol) {
		if (num === 0) num = -1;
		var value = currencyFilter(amount, currencySymbol);
		var sep = value.indexOf(formats.DECIMAL_SEP) + 1;
		var symbol = '';
		if (sep < value.indexOf(formats.CURRENCY_SYM)) symbol = ' ' + formats.CURRENCY_SYM;
		return value.substring(0, sep + num) + symbol;
	};
} ]);