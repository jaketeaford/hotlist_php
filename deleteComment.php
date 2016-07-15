<?php
    include('classes/vbAPI.php');
    $api = new vbAPI();

    echo "<strong>LOGIN RESULT</strong><pre>";
    print_r($api);
    echo "</pre>";

    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => 'WLF_Staff',
        'vb_login_md5password' => md5('Bi@dgt$n20161Lve')
    ));

    echo "<strong>LOGIN RESULT</strong><pre>";
    print_r($response);
    echo "</pre>";




    $data = $api->execRequest(array(
        'api_m' => 'api_init',
    ));


    echo "<strong>NEW API INIT OBJECT USING SESSIONHASH</strong><pre>";
    print_r($data);
    echo "</pre>";



    $editPostInfo = $result = $api->execRequest(array(
        'api_m' => 'editpost_editpost',
        'postid' => '203660'
    ));

    echo "<strong>EDITPOST INFORMATION</strong><pre>";
    print_r($editPostInfo);
    echo "</pre>";





    $result = $api->execRequest(array(
        'api_m' => 'editpost_deletepost',
        'postid' => '203660',
        'threadid' => '50327',
//        'posthash' => $editPostInfo->response->posthash,
//        'poststarttime' => $editPostInfo->response->poststarttime,
        'postinfo' => $editPostInfo->response
    ));

    echo "<strong>DELETEPOST RESULT</strong><pre>";
    print_r($result);
    echo "</pre>";

?>