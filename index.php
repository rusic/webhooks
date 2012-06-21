<?php
require 'Slim/Slim.php';

//With custom settings
$app = new Slim();

//Mailchimp help route
$app->get('/mailchimp', function () use($app) {
    $app->render('mailchimp.php');
});

//Mailchimp webhook
$app->post('/mailchimp', function () use($app) {

    require_once 'MCAPI.class.php';

    $emailField = $app->request()->get('email');
    $listId = $app->request()->get('listid');
    $apiKey = $app->request()->get('apikey');

    //Make sure we have required data.
    if (empty($emailField) || empty($listId) || empty($apiKey)) {
        $app->halt(500,'Your hook is missing required GET parameters.');
    }

    $email = $app->request()->post($emailField);
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
    $retval = $api->listSubscribe($listId, $email, $merge_vars, 'html', $doubleOptIn, true, true, true);

    if ($api->errorCode){
        $app->halt(500,'Unable to run listSubscribe.'.$api->errorCode.': '.$api->errorMessage);
    } else {
        echo "Subscribed to list.";
    }

});

//Lyris help route
$app->get('/lyris', function () use($app) {
    $app->render('lyris.php');
});

//Lyris webhook
$app->post('/lyris', function () use($app) {

    require_once 'LyrisPHP/Lyris.php';

    $emailsource = $app->request()->get('emailsource');
    $siteId = $app->request()->get('siteid');
    $listId = $app->request()->get('listid');
    $password = $app->request()->get('pass');
    $forenamesource = $app->request()->get('forenamesource');
    $surnamesource = $app->request()->get('surnamesource');
    $ridtarget = $app->request()->get('ridtarget');
    $custom1source = $app->request()->get('custom1source');
    $custom2source = $app->request()->get('custom2source');
    $custom3source = $app->request()->get('custom3source');
    $custom4source = $app->request()->get('custom4source');
    $custom5source = $app->request()->get('custom5source');
    $custom1target = $app->request()->get('custom1target');
    $custom2target = $app->request()->get('custom2target');
    $custom3target = $app->request()->get('custom3target');
    $custom4target = $app->request()->get('custom4target');
    $custom5target = $app->request()->get('custom5target');

    //Make sure we have required data.
    if (empty($emailsource) || empty($siteId) || empty($listId) || empty($password)) {
        $app->halt(500,'Your hook is missing required GET parameters.');
    }

    $email = $app->request()->post($emailsource);
    $forename = $app->request()->post($forenamesource);
    $surname = $app->request()->post($surnamesource);
    $rid = $app->request()->post('id');

    $custom1 = $app->request()->post($custom1source);
    $custom2 = $app->request()->post($custom2source);
    $custom3 = $app->request()->post($custom3source);
    $custom4 = $app->request()->post($custom4source);
    $custom5 = $app->request()->post($custom5source);

    //Make sure we have required data.
    if (empty($email)) {
        $app->halt(500,'Your hook is missing email address.');
    }

    $lyris = new Lyris($siteId, $listId, $password);

    if (!empty($forenamesource) && !empty($forename)) {
        $lyris->addDemographic(1, $forename);
    }

    if (!empty($surnamesource) && !empty($surname)) {
        $lyris->addDemographic(2, $surname);
    }

    if (!empty($ridtarget) && !empty($rid)) {
        $lyris->addDemographic($ridtarget, $rid);
    }

    if (!empty($custom1source) && !empty($custom1)) {
        $lyris->addDemographic($custom1target, $custom1);
    }

    if (!empty($custom2source) && !empty($custom2)) {
        $lyris->addDemographic($custom2target, $custom2);
    }

    if (!empty($custom3source) && !empty($custom3)) {
        $lyris->addDemographic($custom3target, $custom3);
    }

    if (!empty($custom4source) && !empty($custom4)) {
        $lyris->addDemographic($custom4target, $custom4);
    }

    if (!empty($custom5source) && !empty($custom5)) {
        $lyris->addDemographic($custom5target, $custom5);
    }

    if ($lyris->addContact($email)) {
        echo 'Ok';
    } else {
        echo 'Failed';
    }

});

$app->get('/', function () use($app) {
    $app->render('index.php');
});

$app->run();