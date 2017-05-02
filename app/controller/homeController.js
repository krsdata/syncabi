app.controller('homeController', function ($scope, $rootScope, commonService) {
    init();

    function init()
    {
        if (commonService.checkUserLoggedIn())
        {
            commonService.updatePath('login');
        }
       
    }
});