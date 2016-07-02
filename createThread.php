<?php
    include_once('classes/vbAPI.php');

    $api = new vbAPI();
    $api->init();
    $response = $api->login('apiTestUser', 'api123test');
    $result = $api->createNewThread('69', 'IT LIVES!', 'IT WORKS YESSSSS');
    echo "<pre>";
    print_r($result);
    echo "</pre>";
?>