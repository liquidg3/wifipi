(function (angular, $, io) {

    //setup phone
    $(window).ready(function () {
        $('[phonenumber]').intlTelInput({
            utilsScript: '/public/bower_components/intl-tel-input/lib/libphonenumber/build/utils.js'
        });
    });


    var app = angular.module('captive', []),
        mac = $('#mac').html(),
        url = 'https://taysmacbook.ngrok.io',
    //url = 'https://appointments.spruce.me',
        socket = io(url);

    app.controller('MainController', ['$scope', 'com', function ($scope, com) {

        $scope.done = false;
        $scope.phoneNumberQuery = '';
        $scope.submitting = false;
        $scope.client = false;
        $scope.email = null;

        $scope.scrollToTop = function () {
            $("html, body").animate({scrollTop: 0}, "slow");
        };

        $scope.submit = function () {

            $scope.submitting = true;

            socket.emit('search-for-client', $scope.phoneNumberQuery, function (err, client) {

                $scope.submitting = false;

                if (err) {
                    alert(err);
                    return;
                }

                $scope.client = client;
                $scope.done = true;
                $scope.$apply();
                $scope.scrollToTop();

                if (client) {

                    $scope.whitelist();
                    socket.emit('captive-signup', $scope.phoneNumberQuery, null, mac, function (err, client) {

                    });
                }

            });
        };

        $scope.whitelist = function () {

            $.get('portal.php', function (response) {
                var json = JSON.parse(response);
                if (json.error) {
                    alert(json.error);
                }
            });

        };

        $scope.signup = function () {

            $scope.submitting = true;

            if (!socket.connected) {
                alert("I'm not able to connect to the Spruce service. Try again later.");
                return;
            }
            
            socket.emit('captive-signup', $scope.phoneNumberQuery, $scope.email, mac, function (err, client) {

                if (err) {
                    alert(err);
                    return;
                }

                $scope.newClient = client;
                $scope.$apply();
                $scope.whitelist();

            });
            
        };

        com(function () {

            socket.emit('captive-search', mac, function (err, client) {

                if (err) {
                    alert(err);
                    return;
                }

                if (client) {
                    $scope.done = true;
                    $scope.client = client;
                    $scope.$apply();
                    $scope.whitelist();
                }

            });

        });


    }]);

    app.factory('com', [function () {

        var queue = [],
            com = function (cb) {

                queue.push(cb);
                if (socket.connected) {
                    cb();
                }

            };

        socket.on('connect', function () {
            queue.forEach(function (cb) {
                cb();
            });
        });

        return com;

    }]);

}(angular, jQuery, io));