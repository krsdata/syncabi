
var app = angular.module('syncAbi', ['ui.router', 'ngCookies']);

app.config(function ($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/');

    $stateProvider


        .state('login', {
            url: '/',
            templateUrl: 'views/login.htm',
            controller : "loginController"
        })
        .state('home', {
            url: '/home',
            templateUrl: 'views/home.htm',
            controller: "homeController"
        })
        .state('signup', {
            url: '/signup',
            templateUrl: 'views/signUp.htm',
            controller: "loginController"
        })

});