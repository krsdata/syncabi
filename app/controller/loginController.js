app.controller('loginController', function ($scope, httpService, commonService, $rootScope) {
    
    init();

    function init()
    {
        $scope.user = {};
        if (commonService.checkUserLoggedIn()) {
            commonService.updatePath('home');
        }

    }
    $scope.login = function ()
    {
        $scope.loginForm.$setSubmitted(true);
        if ($scope.loginForm.$valid) {
            $rootScope.loaderIndicator = true;
            httpService.login($scope.user).then(function (result) {
                if (result.data.message == "Successfully logged in.") {
                    $scope.errorIndicator = false;
                    $rootScope.loaderIndicator = false;
                    var userRes = result.data.data;
                    commonService.setCookieValues('name', userRes.name);
                    commonService.setCookieValues('roleType', userRes.roleType); 
                    commonService.setCookieValues('userId', userRes.userId);
                    $rootScope.isLogin = true;
                    $rootScope.name = userRes.name;
                    commonService.updatePath('home');
                    


                }
                else {
                    $rootScope.loaderIndicator = false;
                    $rootScope.errorIndicator = true;
                    $scope.message = "email or password is wrong.Please enter correct details.";
                    
                }
            });
        }
        else {
            if ($scope.loginForm.$error.email)
            {
                
                $scope.errorIndicator = true;
                $scope.message = "Please enter a valid email address.";
            }
            else {
             
              $scope.errorIndicator = true;
              $scope.message = "Please enter required details.";
            }
            
        }
    }
    $scope.cancel = function()
    {
        $scope.user = null;
       
    }
    $scope.createAccount = function()
    {
        if ($scope.user.password === $scope.user.confirmPassword) {
            $scope.errorIndicator = false;
           
            $scope.signUpForm.$setSubmitted(true);
            if ($scope.signUpForm.$valid) {
                $scope.user.roleType = 1;
                $rootScope.loaderIndicator = true;
                httpService.createAccount($scope.user).then(function (result) {
                    if (result.data.code != 500 || result.data.message == "Registration successfully done.") {
                        $scope.user = {};
                        var userRes = result.data.data;
                        commonService.setCookieValues('name', userRes.name);
                        commonService.setCookieValues('userId', userRes.userId);
                        $rootScope.loaderIndicator = false;
                        $rootScope.isLogin = true;
                        $rootScope.name = userRes.name;
                        commonService.updatePath('home');
                       
                    }
                    else if (result.data.message == "The email has already been taken.") {
                        $rootScope.loaderIndicator = false;
                        $scope.errorIndicator = true;
                        $scope.message = "Account with this emailid already exists. Please try with different emailid. "
                    }
                });
            }
            else {
                if ($scope.signUpForm.$error.email) {
                    $scope.errorIndicator = true;
                    $scope.message = "Please enter a valid email address.";
                }
                else {
                    $scope.message = 'Please enter required details.';
                    $scope.errorIndicator = true;
                }
            }
        }
        else {
            $scope.message = "password and confirm password are not same.";
            $scope.errorIndicator = true;
            
        }
    }
});