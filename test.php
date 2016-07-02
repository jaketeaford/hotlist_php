<?php


$forumUrl = 'http://54.177.162.70/';
$apikey = 'bxHhcpa8';

//Parameters
$clientname = "mysuperclient";
$clientversion ="0.1";
$platformname = "example";
$platformversion ="2.5";
$uniqueid = "1234";
$apiurl= $forumUrl .'api.php';


error_reporting(E_ALL);

///////////////////////////////////////// ACTION

if(isset($_POST['username']) && isset($_POST['password'])) {
    $userName = $_POST['username'];
    $password = $_POST['password'];

    if($userName != '' && $password != '') {
        $jsarray = initApiRequest($clientname, $clientversion, $platformname, $platformversion, $uniqueid, $apiurl);
        $jsarray = doAuthenticate($jsarray, $userName, $password, $apikey, $apiurl);

        echo "<hr>";
        if($jsarray->{'response'}->{'errormessage'}[0] == "redirect_login") {
            echo 'login success! ('. $jsarray->{"session"}->{'userid'} .') '. $jsarray->{'response'}->{'errormessage'}[1];

        } else {
            echo "login failed!";
        }
        echo "<hr>";
    } else {
        echo "userName == '' || password == ''";
    }
}
///////////////////////////////////////// FUNCTIONS

function initApiRequest($clientname, $clientversion, $platformname, $platformversion, $uniqueid, $apiurl) {
    $init_request =  $apiurl .'?api_m=api_init&clientname='. $clientname .'&clientversion='.$clientversion .'&platformname='.$platformname .'&platformversion=' .$platformversion .'&uniqueid='.$uniqueid;
    $jsarray = doRestRequest($init_request);
    return $jsarray;
}

function doAuthenticate($jsarray, $userName, $password, $apikey, $apiurl) {
    $password = md5($password);
    $requestparams = array('api_m' => 'login_login', 'vb_login_username' => $userName, 'vb_login_md5password' => $password, 'cookieuser' => 1);

    $api_sig = buildApiSig($jsarray, $apikey, $requestparams);
    $api_registration_information = buildApiRegistrationString($jsarray, $api_sig);
    $login_request =  buildRequestString($requestparams, $api_registration_information);

    $url = $apiurl .'?api_m=login_login';
    $jsarray = doRestRequestUrlencoded($login_request, $url);
    return $jsarray;
}

function buildApiSig($jsarray, $apikey, $requestparams) {
    ksort($requestparams);

    $login_string = http_build_query($requestparams, '', '&');

    $apiaccesstoken = urlencode($jsarray->{'apiaccesstoken'});
    $apiclientid = urlencode($jsarray->{'apiclientid'});
    $secret = urlencode($jsarray->{'secret'});

    $api_sig = urlencode(md5($login_string . $apiaccesstoken . $apiclientid . $secret . $apikey));
    return $api_sig;
}

function buildApiRegistrationString($jsarray, $api_sig) {
    $apiaccesstoken = urlencode($jsarray->{'apiaccesstoken'});
    $apiclientid = urlencode($jsarray->{'apiclientid'});
    $apiversion = urlencode($jsarray->{'apiversion'});

    $api_registration_information = 'api_c='. $apiclientid .'&api_s='. $apiaccesstoken .'&api_sig='. $api_sig .'&api_v='. $apiversion;
    return $api_registration_information;
}

function buildRequestString($requestparams, $api_registration_information) {
    unset($requestparams['api_m']);
    echo "<hr>". http_build_query($requestparams, '', '&') ."<hr>";
    $login_request =  '&'. $api_registration_information .'&'. http_build_query($requestparams, '', '&');
    return $login_request;
}

function doRestRequest($restRequest) {
    echo "<br>DO REST REQUEST[". $restRequest ."]<br>";

    $content = '';
    //Open and read the content
    $fp = fopen($restRequest, 'r');

    // keep reading until there's nothing left
    while ($line = fread($fp, 1024)) {
        $content .= $line;
    }
    fclose($fp);


    echo "<hr>";
    var_dump(json_decode($content, true));
    echo "<hr>";

    //decode the content
    $jsarray = json_decode($content);
    return $jsarray;
}

function doRestRequestUrlencoded($restRequest, $url) {
    echo "<br>DO REST REQUEST[". $restRequest ."]<br>";
    $context_options = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: application/x-www-form-urlencoded",
            'content' => $restRequest
        )
    );
    $context = stream_context_create($context_options);

    $content = '';
    //Open and read the content
    $fp = fopen($url.$restRequest, 'r', false, $context);

    // keep reading until there's nothing left
    while ($line = fread($fp, 1024)) {
        $content .= $line;
    }
    fclose($fp);

    echo "<hr>";
    var_dump(json_decode($content, true));
    echo "<hr>";

    //decode the content
    $jsarray = json_decode($content);
    return $jsarray;
}

function doRestRequestUrlencodedPOST($restRequest, $url) {
    echo "<br>DO REST REQUEST[". $restRequest ."]<br>";
    $context_options = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: application/x-www-form-urlencoded",
            'content' => $restRequest
        )
    );
    $context = stream_context_create($context_options);

    $content = '';
    //Open and read the content
    $fp = fopen($url.$restRequest, 'r', false, $context);

    // keep reading until there's nothing left
    while ($line = fread($fp, 1024)) {
        $content .= $line;
    }
    fclose($fp);

    echo "<hr>";
    var_dump(json_decode($content, true));
    echo "<hr>";

    //decode the content
    $jsarray = json_decode($content);
    return $jsarray;
}
?>
<html>
<head>
</head>
<body>
<form action="test.php" method="post">
    <p>Username: <input type="text" name="username" /></p>
    <p>Password: <input type="password" name="password" /></p>
    <p><input type="submit" /></p>
</form>
</body>
</html>