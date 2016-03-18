<?php

$cmd = "/usr/sbin/arp " . $_SERVER['REMOTE_ADDR'];
$mac = shell_exec($cmd);
$error = false;
$done = false;
$name = false;
$existing = false;

if (!$mac) {
    echo 'Could not detect your devices address. Please try again later.';
    return;
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" ng-app="captive"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" ng-app="captive"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" ng-app="captive"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" ng-app="captive"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Spruce Public Wifi</title>
    <meta name="description" content="A description">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/bower_components/intl-tel-input/build/css/intlTelInput.css">
    <link rel="stylesheet" href="/public/_compiled/index.css">

</head>

<body class="page-body index" ng-controller="MainController">

<div class="container-fluid">

    <div ng-show="!done">
        <h1>Get Spruce Wifi</h1>

        <p>
            Enter your mobile number to get free wifi and to join our rewards program!
        </p>

        <form ng-submit="submit()">

            <div class="form-group">
                <input name="phone" type="tel" phonenumber ng-model="phoneNumberQuery" placeholder="720-555-5555"/>
            </div>

            <div class="submit">
                <button class="button" type="submit" ng-show="!submitting">Let Me On</button>
                <button class="button" type="button" ng-show="submitting" disabled>Thinking...</button>
            </div>

        </form>

        <p>*This is designed to make sure people don't abuse the Wifi. We don't track what you are doing on the
            Internet, that would be creepy. But, please don't do anything that will get us in trouble.</p>
    </div>

    <div ng-show="done && client">
        <h1>Welcome Back {{ client.first_name }}</h1>

        <p>
            You now have Internet!
        </p>
    </div>

    <form ng-submit="signup();" ng-show="done && !client && !newClient">
        <h1>Almost There!</h1>
        <p>Welcome to Spruce! What is your email?</p>
        <div class="form-group">
            <input type="email" ng-model="email "/>
        </div>
        <div class="submit">
            <button class="button" ng-show="!submitting" type="submit">Done</button>
            <button class="button ng-hide" ng-show="submitting" disabled type="button">Thinking...</button>
        </div>
    </form>

    <div ng-show="done && newClient">
        <h1>All Set!</h1>
        <p>You should now have internet and should also get a link for your first reward.</p>
    </div>


</div>
<div id="mac" style="display: none;"><?php echo $mac; ?></div>

<img class="footer-logo" src="/public/images/logo.png"/>

<script src="public/js/jquery-1.11.1.min.js"></script>
<script src="public/bower_components/intl-tel-input/build/js/intlTelInput.min.js"></script>
<script src="public/bower_components/angular/angular.js"></script>
<script src="public/bower_components/socket.io-client/socket.io.js"></script>
<script src="public/js/captive.js"></script>


</body>
</html>
