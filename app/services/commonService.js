app.factory('commonService', ['$state', '$window', '$cookies', '$rootScope', function ($state, $window, $cookies, $rootScope) {
    var commonService = {
        updatePath: updatePath,
        scrollToTop: scrollToTop,
        setCookieValues: setCookieValues,
        reloadRoute: reloadRoute,
        deleteCookieValues: deleteCookieValues,
        checkUserLoggedIn: checkUserLoggedIn
    };

    function updatePath(pathValue) {
        $state.go(pathValue);
    }

    function scrollToTop() {
        $window.scrollTo(0, 0);
    }

    function setCookieValues(dataType, data) {
        if (dataType == "name")
            $cookies.put("name", data);
        if (dataType == "roleType")
            $cookies.put("roleType", data);
    }
    function deleteCookieValues(dataType) {
        if (dataType == "name")
            $cookies.remove("name");
        if (dataType == "roleType")
            $cookies.remove("roleType");

    }
    function reloadRoute() {
        //$route.reload();
    }

    function checkUserLoggedIn() {
        var userID = $cookies.get("userId");
        if (userID) {
            $rootScope.isLogin = true;
            $rootScope.userID = userID;
            $rootScope.name = $cookies.get("name");
            return true;
        }
        else {
            $rootScope.isLogin = false;
            return false;
        }
    }
    return commonService;


}]);