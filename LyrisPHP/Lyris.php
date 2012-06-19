<?php
require_once 'xmlLib.php';

class Lyris {
    
    /**
     * Current version of the library
     *
     * Uses semantic versioning (http://semver.org/)
     *
     * @const string VERSION
     */
    const VERSION = '0.0.1';

    private $_baseUrl = 'https://www.elabs10.com/API/mailing_list.html';
    private $_password = false;
    private $_lastError = false;
    private $_siteId = false;
    private $_listId = false;
    private $_demographic = array();
    
    public function __construct($siteId, $listId, $password) {
        $this->_siteId = $siteId;
        $this->_listId = $listId;
        $this->_password = $password;
    }
    
    public function GetLastErrorMessage() {
        return $this->_lastError;
    }
    
    /**
    * Adds a contact to a list.
    **/
    public function addContact($email, $trigger = 'no') {
        
        //Build request data.
        $message = array('SITE_ID' => $this->_siteId,
                'MLID' => $this->_listId,
                'DATA' => array(
                    array('@attributes' => array('type' => 'extra', 'id' => 'password'), '@value' => $this->_password),
                    array('@attributes' => array('type' => 'email'), '@value' => $email),
                    array('@attributes' => array('type' => 'extra', 'id' => 'trigger'), '@value' => $trigger)
                )
            );
        
        if(!empty($this->_demographic)) {
            $message['DATA'] = array_merge($message['DATA'], $this->_demographic);
        }

        //Convert to XML.
        $xml = Array2XML::createXML('DATASET', $message);
        
        $response = $this->_httpPost('record','add',$xml->saveXML());
        
        if(strtolower($this->GetLastErrorMessage()) == 'email address already exists') {
            $response = $this->_httpPost('record','update',$xml->saveXML());
        }
        
        return $response->TYPE == 'success' ? true : false;
    }
    
    public function addDemographic($id, $value) {
        $this->_demographic[] = array('@attributes' => array('type' => 'demographic', 'id' => $id), '@value' => $value);
    }
    
    public function optOut($email) {

        //Build request data.
        $message = array('SITE_ID' => '2010003057',
                'MLID' => 194503,
                'DATA' => array(
                    array('@attributes' => array('type' => 'extra', 'id' => 'password'), '@value' => $this->_password),
                    array('@attributes' => array('type' => 'email'), '@value' => $email),
                    array('@attributes' => array('type' => 'extra', 'id' => 'state'), '@value' => 'unsubscribed')
                )
            );
        
        //Convert to XML.
        $xml = Array2XML::createXML('DATASET', $message);
        $response = $this->_httpPost('record','update',$xml->saveXML());
        
        return $response->TYPE == 'success' ? true : false;
        
    }

    private function _getFullUrl() {
        return $this->_baseUrl . (isset($this->_session_encoding) ? $this->_session_encoding : '');
    }

    private function _httpPost($type, $activity, $xmlString) {
        
        //Reset last error message.
        $this->_lastError = false;
        
        $postdata = array();
        $postdata['type']= $type;
        $postdata['activity'] =$activity;
        $postdata['input'] = trim($xmlString);
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$this->_baseUrl);
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        
        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        $xmlResponse = simplexml_load_string($result);
        
        if($xmlResponse->TYPE == 'error') {
            $this->_lastError = $xmlResponse->DATA;
        }
        
        return $xmlResponse;
    }
}
