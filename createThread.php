<?php
    include_once('classes/vbAPI.php');

    $api = new vbAPI();

    $response = $api->execRequest(array(
        'api_m' => 'login_login',
        'vb_login_username' => 'apiTestUser',
        'vb_login_md5password' => md5('api123test')
    ));

    $result = $api->execRequest(array(
        'api_m' => 'newthread_postthread',
        'forumid' => '69',
        'message' => 'posted this from new execrequest after re-init',
        'subject' => 'execRequest test re-init',
    ));

    echo "<a href='http://ec2-54-151-5-248.us-west-1.compute.amazonaws.com/threads/{$result->show->threadid}'>Here's your new thread!</a>"
?>