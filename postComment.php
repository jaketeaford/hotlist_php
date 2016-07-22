<?php
    /*
        postComment.php
        Purpose:
            Showcases an example of how to post a comment to an existing thread using the vbAPI class
    */
    include_once('classes/vbAPI.php');

    $api = new vbAPI();

    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => 'apiTestUser',
        'vb_login_md5password' => md5('')
    ));

    $result = $api->execRequest(array(
        'api_m' => 'newreply_postreply',
        'threadid' => '50327', // this can be any thread id, using 50327 for example purposes
        'message' => 'test comment content'
    ));

    // displaying response data for example and debugging purposes
    echo "<pre>";
    print_r($result);
    echo "</pre>";
?>