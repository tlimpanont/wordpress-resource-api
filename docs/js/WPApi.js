var WPApi = angular.module('WPApi', []).
  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when('/posts/:name', {templateUrl: 'templates/resourceDetail.html',   controller: "ResourceDetailController"})
}]).
controller("MainController", function($scope) {
	console.log($scope);
});



function ResourceDetailController($scope, $routeParams, $location)
{	
	$scope.name = $routeParams.name;
	angular.element("li").removeClass("active");
	angular.element('li[data-type="'+$scope.name+'"]').addClass("active");
}	