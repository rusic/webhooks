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

	//Make sure we have required data.
    if (empty($emailsource) || empty($siteId) || empty($listId) || empty($password)) {
        $app->halt(500,'Your hook is missing required GET parameters.');
    }

	$email = $app->request()->post($emailsource);
    $forename = $app->request()->post($forenamesource);
    $surname = $app->request()->post($surnamesource);
    $rid = $app->request()->post('id');
    
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