<?php
include "ConfigSettings.php";
include "./MCAPI.class.php";

class MailChimp extends PHPUnit_Framework_TestCase
{
    public function testHasEndpoint()
    {
        $ch = curl_init(LOCAL_URL.'/mailchimp'); 
        curl_setopt($ch, CURLOPT_HEADER, 1); 
        $c = curl_exec($ch); 
        $responseCode = curl_getinfo($ch);
        
        //Will return a 200 because GET.
        $this->assertEquals(200, $responseCode['http_code']);
        
        
        $ch = curl_init(LOCAL_URL.'/mailchimp'); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        $c = curl_exec($ch); 
        $responseCode = curl_getinfo($ch);
        
        //Will return a 500 because required parameters are not supplied.
        $this->assertEquals(500, $responseCode['http_code']);
        
    }
    
    public function testAddToList()
    {
        
        $hookUrl = '/mailchimp?email=custom1&forename=custom2&surname=custom3&listid='.MAIL_CHIMP_LIST_ID.'&apikey='.MAIL_CHIMP_API_KEY;
        
        $emailUniq = time();
        $email = 'tom.'.$emailUniq.'@simpleweb.co.uk';
        
        $fields = array('custom1' => $email, 'custom2' => 'Tom', 'custom3' => 'Holder');
        
        //Do the post.
        $ch = curl_init(LOCAL_URL.$hookUrl); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
        $c = curl_exec($ch); 
        $response = curl_getinfo($ch);
        
        $this->assertEquals(200, $response['http_code']);
        
        //Try and retrieve the added user from mailchimp.
        $api = new MCAPI(MAIL_CHIMP_API_KEY);

        $retval = $api->listMemberInfo(MAIL_CHIMP_LIST_ID, $email);

        $this->assertFalse((bool)$api->errorCode);
        
        $this->assertEquals($email, $retval['data'][0]['merges']['EMAIL']);
        $this->assertEquals('Tom', $retval['data'][0]['merges']['FNAME']);
        $this->assertEquals('Holder', $retval['data'][0]['merges']['LNAME']);
        
    }
    
    public function testAddToListWithoutName()
    {
        
        $hookUrl = '/mailchimp?email=custom1&listid='.MAIL_CHIMP_LIST_ID.'&apikey='.MAIL_CHIMP_API_KEY;
        
        $emailUniq = time();
        $email = 'tom.'.$emailUniq.'@simpleweb.co.uk';
        
        $fields = array('custom1' => $email);
        
        //Do the post.
        $ch = curl_init(LOCAL_URL.$hookUrl); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
        $c = curl_exec($ch); 
        $response = curl_getinfo($ch);
        
        $this->assertEquals(200, $response['http_code']);
        
        //Try and retrieve the added user from mailchimp.
        $api = new MCAPI(MAIL_CHIMP_API_KEY);

        $retval = $api->listMemberInfo(MAIL_CHIMP_LIST_ID, $email);

        $this->assertFalse((bool)$api->errorCode);
        
        $this->assertEquals($email, $retval['data'][0]['merges']['EMAIL']);
        $this->assertEquals("", $retval['data'][0]['merges']['FNAME']);
        $this->assertEquals("", $retval['data'][0]['merges']['LNAME']);
        
    }
    
}