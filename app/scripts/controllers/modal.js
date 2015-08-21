/**
 * ModalController
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
 
var modalControllers = angular.module("modalControllers", []);

/** setup modal window with a function to toggle it open and closed  */
modalControllers.controller("defaultModalController", [ '$scope', '$http', function($scope, $http) {
	
    $scope.modalData = {
    	message : '',
    	modalShown : false,
    }
    
    /** toggle on and off the modal by function */
    $scope.toggleModal = function() {
    	$scope.modalData.modalShown = !$scope.modalData.modalShown;
    	$scope.trackGAEvent();
    };
    
    /** if we have valid modal event tracking for it being shown, then persist to GA */
    $scope.trackGAEvent = function() {
    	if ($scope.modalData.modalShown) {
    		if (typeof($scope.eventTracked.category) != 'undefined' && $scope.eventTracked.category != '') {
        		customGATracking.eventTrack($scope.eventTracked.category, $scope.eventTracked.action, $scope.eventTracked.value);	
        	}	
    	}
    };
} ]);

/** google analytics event tracking on the modal window to fire if requested */
modalControllers.directive('trackModalShownEvent', function() {
    return {
        restrict: 'A',
        link: function(scope, elem, attr) {
        	scope.eventTracked = JSON.parse(attr.trackModalShownEvent);
        }
    }
 });

/** determine what URL parameters make the modal window appear automatically */
modalControllers.directive('showModalByUrlParams', function() {
    return {
        restrict: 'A',
        link: function(scope, elem, attr) {
        	
            /** open the modal window if it's a show modal url */
            if(window.location.search.indexOf(attr.showModalByUrlParams) > -1) {
            	scope.modalData.modalShown = true;
            	scope.trackGAEvent();
            }
        }
    }
 });