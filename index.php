<?php
require 'Slim/Slim.php';

//With custom settings
$app = new Slim();

//GET route
$app->post('/mailchimp', function () use($app) {

    require_once 'MCAPI.class.php';
    
    $emailField = $app->request()->get('email');
    $listId = $app->request()->get('listid');
    $apiKey = $app->request()->get('apikey');
    
    //Make sure we have required data.
    if (empty($emailField) || empty($listId) || empty($apiKey)) {
        $app->halt(500,'Your hook is missing required GET parameters.');
    }
    
    $email = $app->request()->post($app->request()->get('email'));
    $forename = $app->request()->post($app->request()->get('forename'));
    $surname = $app->request()->post($app->request()->get('surname'));
    $rid = $app->request()->post('id');
    
    //Make sure we have required data.
    if (empty($email)) {
        $app->halt(500,'Your hook is missing email address.');
    }
    
    //If double opt in parameter is present, subscribe with double opt-in.
    $doubleOptIn = false;
    if (!is_null($app->request()->get('doubleoptin'))) {
        $doubleOptIn=true;
    }
    
    $api = new MCAPI($apiKey);

    $merge_vars = array('FNAME'=>$forename, 'LNAME'=>$surname);

    if (!empty($rid)) {
        $merge_vars['RID'] = $rid;
    }
    
    // Subscribe to mailchimp
    $retval = $api->listSubscribe($listId, $email, $merge_vars, 'html', $doubleOptIn);

    if ($api->errorCode){
        $app->halt(500,'Unable to run listSubscribe.'.$api->errorCode.': '.$api->errorMessage);
    } else {
        echo "Subscribed to list.";
    }

});

$app->get('/mailchimp', function () use($app) {
    $app->render('mailchimp.php');
});

$app->get('/', function () use($app) {
    $app->render('index.php');
});

$app->run();