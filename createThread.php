<?php
    /*
        createThread.php
        Purpose:
            Showcases an example of how to post a new thread using the vbAPI class
    */

    include_once('classes/vbAPI.php');

    $api = new vbAPI();

    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => '',
        'vb_login_md5password' => md5('')
    ));

    $result = $api->execRequest(array(
        'api_m' => 'newthread_postthread',
        'forumid' => '69',
        'message' => 'test message from createThread.php',
        'subject' => 'your subject here',
    ));

    echo "<a href='http://ec2-54-151-5-248.us-west-1.compute.amazonaws.com/threads/{$result->show->threadid}'>Here's your new thread!</a>"
?>