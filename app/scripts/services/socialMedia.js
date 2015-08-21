/**
 * OAuth Social Media Service
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
MainApp.factory("SocialMediaService", function() {

    /**
     * get the response back from facebook about the user and populate the current visitor in scope
     */
    facebookCallAPI = function(scope) {
	    FB.api('/me', function(response) {
	        scope.visitor.first = response.first_name;
	        scope.visitor.last = response.last_name;
	        scope.visitor.email = response.email;
	        scope.visitor.emailconfirmed = response.email;
	        scope.visitor.phone = '';
	        
	        /** apply to scope and persist to local browser storage */
	        scope.$apply();
	        facebookSaveLocalCache(scope.visitor.first, scope.visitor.last, scope.visitor.email, scope.visitor.phone);
	    });
    }

    /** save values from FB to local browser cache */
    facebookSaveLocalCache = function(first, last, email, phone) {
	    localStorage.setItem("FBFirst", first);
	    localStorage.setItem("FBLast", last);
	    localStorage.setItem("FBEmail", email);
	    localStorage.setItem("FBPhone", phone);
    }

    /** save values from LI to local browser cache */
    linkedInSaveLocalCache = function(first, last, email, phone) {
	    localStorage.setItem("LIFirst", first);
	    localStorage.setItem("LILast", last);
	    localStorage.setItem("LIEmail", email);
	    localStorage.setItem("LIPhone", phone);
    }

    /** get back the FB or LI visitor from 3rd party social media accounts */
    return {
	    getVisitorFacebook : function(scope) {
	        FB.getLoginStatus(function(response) {
		        if (response.status === 'connected') {
		            return facebookCallAPI(scope);
		        } else {
		            FB.login(function(response) {
			        if (response.authResponse) {
			            return facebookCallAPI(scope);
			        }
		            }, {
			        scope : 'public_profile,email'
		            });
		        }
	        });
	    },
	    getVisitorLinkedIn : function(scope) {
	        IN.Event.on(IN, 'auth', function() {
		        IN.API.Profile("me").fields("id", "emailAddress", "firstName", "lastName", "phoneNumbers", "mainAddress").result(function(me) {

		            var id = me.values[0].id;
		            var email = me.values[0].emailAddress;
		            var firstName = me.values[0].firstName;
		            var lastName = me.values[0].lastName;

		            var phoneChk = me.values[0].phoneNumbers;
		            if (!phoneChk)
			        var phoneChk = false;
		            else if (phoneChk['_total'] == 0)
			        phoneChk = false;

		            if (phoneChk !== false) {
			        var phone = me.values[0].phoneNumbers['values'][0]['phoneNumber'];
		            } else
			        var phone = false;

		            if (me.values[0].mainAddress) {
			        var addr = me.values[0].mainAddress;
		            } else
			        var addr = false;

		            if (firstName)
			        scope.visitor.first = firstName;

		            if (lastName)
			        scope.visitor.last = lastName;

		            if (email) {
			        scope.visitor.email = email;
		            	scope.visitor.emailconfirmed = email;
		            }

		            if (phone) scope.visitor.phone = phone;
		            scope.$apply();
		            linkedInSaveLocalCache(firstName, lastName, email, phone);
		        });
	        });
	        IN.User.authorize();
	    },
	    /** get back a FB visitor object from local browser cache */
	    facebookGetLocalCache : function() {
	        visitor = {};
	        visitor.first = localStorage.getItem("FBFirst");
	        visitor.last = localStorage.getItem("FBLast");
	        visitor.email = localStorage.getItem("FBEmail");
	        visitor.emailconfirmed = localStorage.getItem("FBEmail");
	        visitor.phone = localStorage.getItem("FBPhone");
	        return visitor;
	    },
	    /** get back a LI visitor object from local browser cache */
	    linkedINGetLocalCache : function() {
	        visitor = {};
	        visitor.first = localStorage.getItem("LIFirst");
	        visitor.last = localStorage.getItem("LILast");
	        visitor.email = localStorage.getItem("LIEmail");
	        visitor.emailconfirmed = localStorage.getItem("LIEmail");
	        visitor.phone = localStorage.getItem("LIPhone");
	        return visitor;
	    }
    };
});