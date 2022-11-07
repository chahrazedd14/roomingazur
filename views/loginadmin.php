<?php
declare (strict_types=1);

use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

require_once "apiconfig.php";


require_once __DIR__ . '/vendor/autoload.php';

//
// THIS IS A PROOF OF CONCEPT! DO NOT USE IN PRODUCTION!!!
//

$https = false;
if (isset($_SERVER['HTTPS'])) {
    $https = true;
} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO']) {
    $https = true;
}

// Get the root op the application
$host = sprintf('%s://%s', ($https ? 'https' : 'http'), $_SERVER['HTTP_HOST']);

// Simple PHP routing
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = rtrim($path, '/');

if (isset($_GET['q'])) {
    $requestPath = $_GET['q'];
}

$user = null;

// If we run buit-in PHP web server, we want static files to be served directly
if ('cli-server' === php_sapi_name()) {
    $staticExtensions = ['jpg', 'jpeg', 'gif', 'png', 'ico', 'js', 'css'];
    $currentExtension = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($currentExtension, $staticExtensions)) {
        return false;
    }
}

session_name(APP_SESS_ID);
session_start();


if (isset($_SESSION['code'])) {
    header("Location: dashadmin.php");
    exit();
}

// Checking for messages
$style = 'success';
$displayMessage = '';
if (isset($_GET['type']) && isset($_GET['message'])) {
    $styles = ['success', 'error'];
    if (in_array($_GET['type'], $styles)) {
        $style = $_GET['type'];
    }
    $displayMessage = $_GET['message'];
}


if (isset($_REQUEST['submit'])) {
    $oAuthClient = new GenericProvider([
        'clientId' => OAUTH_APP_ID,
        'clientSecret' => CLIENT_SECRET_VALUE,
        'redirectUri' => OAUTH_REDIRECT_URI,
        'urlAuthorize' => OAUTH_AUTHORITY . OAUTH_AUTHORIZE_ENDPOINT,
        'urlAccessToken' => OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT,
        'urlResourceOwnerDetails' => '',
        'scopes' => OAUTH_SCOPES,
    ]);

    $authUrl = $oAuthClient->getAuthorizationUrl();
    $_SESSION['oauthState'] = $oAuthClient->getState();
    header('Location: ' . $authUrl);
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MMV Romming</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">


</head>

<body class="text-center" id="login">


<form class="form-signin" method="POST" action="loginadmin">
    <div class="content --center">
        <div class="logo"></div>
        <button class="button __login --github" name="submit" type="submit">
             Se connecter avec microsoft
        </button>
    </div>

</form>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>
</body>
</html>



















