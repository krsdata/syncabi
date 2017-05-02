app.factory('httpService', ['$http', function ($http) {

    var serviceBase = 'http://infowaynic.com/syncabi-api/';
    var httpService = {
        createAccount: createAccount,
        login: login,
        resetPassword: resetPassword
    };

    function createAccount(user) {
        return $http.post(serviceBase + 'api/v1/user/register', user, { headers: { 'Content-Type': 'application/json' } }).then(function (response) {
            return response;
        });

    }

    function login(user) {
        return $http.post(serviceBase + 'api/v1/user/login', user, { headers: { 'Content-Type': 'application/json' } }).then(function (response) {
            return response;
        });

    }

    function resetPassword(user) {
        return $http.post(serviceBase + 'api/login/resetPassword/', user, { headers: { 'Content-Type': 'application/json' } }).then(function (response) {
            return response;
        });
    }

    return httpService;


}]);