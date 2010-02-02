<?php

/*
* Copyright 2009 - Jake Holman on behalf of Zendesk
*/

class zd_ticket {
	
	public $xml_source = null;	

	public function __construct ($xml_source) {
	
	$url = ZENDESK_URL . 'tickets.xml';		
		
	$ch = curl_init();
	$request = $xml_source;
	$headers = array('Content-type: application/xml','Content-Length: ' . strlen($request));
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERPWD, 'facebook@zendesk.com:98awbef1');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	$http_result = curl_exec($ch);
	$error       = curl_error($ch);
	$http_code   = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

	curl_close($ch);
	
	// Set result set to return
	$result = array();
		if ($error) {
		$result['error'] .= $error;
	} elseif (preg_match("/Location: .*\/tickets\/(\d*).xml/", $http_result, $matches)) {
	    $ticket_id = $matches[1];
	    $result['message'] .= '200';
		$result['ticketID'] .= $ticket_id;
		return $result['ticketID'];
	}
	
	//print json_encode($result);
	return true;
	
	}
	
}

?>