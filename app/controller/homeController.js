app.controller('homeController', function ($scope, $rootScope, commonService) {
    init();

    function init()
    {
        commonService.checkUserLoggedIn();
        if ($rootScope.userID)
        {
            commonService.updatePath('login');
        }
    }
});