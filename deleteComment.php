<?php
    /*
        deleteComment.php
        Purpose:
            This was supposed to showcase how to delete a previously-posted thread comment using the vbAPI class,
            but vBulletin's documentation for the editpost_deletepost method is nonexistent past the name of the
            method. I've left my troubleshooting attempts in for posterity's sake, the last call to execRequest
            should be the only thing that is necessary to do (after logging in, of course) but there are no docs
            regarding required parameters and/or their names so it became a matter of trial-and-error.
    */

    include('classes/vbAPI.php');
    $api = new vbAPI();


    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => 'apiTestUser',
        'vb_login_md5password' => md5('')
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
        'postinfo' => $editPostInfo->response
    ));

    echo "<strong>DELETEPOST RESULT</strong><pre>";
    print_r($result);
    echo "</pre>";

?>