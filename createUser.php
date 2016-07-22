<?php

    /*
        createUser.php
        Purpose:
            Showcases an example of how to register a new user using the vbAPI class, just fill in the empty strings
            where necessary. Since this is a call to register a new member, logging in before making the request is
            not necessary.
    */

    include('classes/vbAPI.php');
    $api = new vbAPI();

    $newUserInfo = $api->execRequest(array(
        'api_m' => 'register_addmember',
        'agree' => true,
        'username' => '',
        'email' => '',
        'emailconfirm' => '',
        'password_md5' => md5(''),
        'passwordconfirm_md5' => md5(''),

    ));

    echo "<strong>NEW USER INFORMATION</strong><pre>";
    print_r($newUserInfo);
    echo "</pre>";
?>