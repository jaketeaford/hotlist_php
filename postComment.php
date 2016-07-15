<?php
    include_once('classes/vbAPI.php');

    $api = new vbAPI();

    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => 'apiTestUser',
        'vb_login_md5password' => md5('api123test')
    ));

    $result = $api->execRequest(array(
        'api_m' => 'newreply_postreply',
        'threadid' => '50327',
        'message' => 'testing new class constructor!'
    ));

    echo "<pre>";
    print_r($result);
    echo "</pre>";
?>