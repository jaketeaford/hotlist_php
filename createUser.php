<?php
    include('classes/vbAPI.php');
    $api = new vbAPI();

    $newUserInfo = $api->execRequest(array(
        'api_m' => 'register_addmember',
        'agree' => true,
        'username' => 'apiTestUser2',
        'email' => 'jaketeaford+wfapi2@gmail.com',
        'emailconfirm' => 'jaketeaford+wfapi2@gmail.com',
        'password_md5' => md5('api123test'),
        'passwordconfirm_md5' => md5('api123test'),

    ));

    echo "<strong>NEW USER INFORMATION</strong><pre>";
    print_r($newUserInfo);
    echo "</pre>";
?>