<?php

    $cmd        = "/usr/sbin/arp ".$_SERVER['REMOTE_ADDR'];
    $mac        = shell_exec($cmd);
    $error      = false;
    $done       = false;
    $name       = false;
    $existing   = false;

    if (!$mac) {
        echo 'Could not detect your devices address. Please try again later.';
        return;
    }

    if (isset($_POST['phone']) && $_POST['phone']) {

//        $domain         = 'https://appointments.spruce.me';
        $domain         = 'https://taysmacbookpro.local:8080';
        $matches        = [];

        preg_match('/..:..:..:..:..:../', $mac , $matches);

        $url            = $domain . '/captive/signup?phone=' . urlencode($_POST['phone']) . '&mac=' . urlencode($matches[0]);
        $results        = @file_get_contents($url);
        $results        = @json_decode($results);

        if ($results && $results->success) {

            //they are good, add them to the white list
            $done       = true;
            $name       = isset($results->client) ? $results->client->first_name : 'friend';
            $existing   = isset($results->client);

        } else {

            $error = "Crap, something broke. Let a Spruce teammate know and we will check it out.";

        }

    }
//    echo $mac;
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

<div class="container-fluid" ng-show="!done">

    <?php if (!$done) { ?>

        <h1>Get Spruce Wifi</h1>

        <p>
            Enter your mobile number and we'll text you the code, it takes 2 seconds.
        </p>

        <form method="post">

            <div class="form-group">
                <input name="phone" type="tel" phonenumber ng-model="phoneNumberQuery" placeholder="720-555-5555"/>
            </div>

            <div class="submit">
                <button class="button" type="submit">Let Me On</button>
            </div>

        </form>

        <p>*This is designed to make sure people don't abuse the Wifi. We don't track what you are doing on the Internet, that would be creepy. But, please don't do anything that will get us in trouble.</p>

    <?php } else { ?>

        <h1>Welcome <?php if ($existing) { ?>back<?php } ?> <?php echo $name; ?></h1>

        <p>
            You should be getting a text soon, hold tight!
        </p>


    <?php } ?>

</div>

<img class="footer-logo" src="/public/images/logo.png" />

<script src="/public/js/jquery-1.11.1.min.js"></script>
<script src="/public/bower_components/intl-tel-input/build/js/intlTelInput.min.js"></script>


<script>

    $('[phonenumber]').intlTelInput({
        utilsScript: '/public/bower_components/intl-tel-input/lib/libphonenumber/build/utils.js'
    });

    <?php if ($error) { ?>
    alert("<?php echo htmlentities($error); ?>");
    <?php } ?>


</script>



</body>
</html>