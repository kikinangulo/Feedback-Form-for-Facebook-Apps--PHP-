<?php

// It's likely that the config array will be empty, but we should check anyway
if (empty($config) || !is_array($config))
{
	$config = array();
}

$config = array_merge($config, array(
	'ZENDESK_URL'		=> 'http://myaccount.zendesk.com/', // Remember to in http or https
	'ZENDESK_USER'		=> 'apiuser@mywebsite.com', // Agent or Admin, not restricted!
	'ZENDESK_PASS'		=> 'mypassword',
	'FB_APPNAME'		=> 'your_facebook_canvas_extension',
	'FB_APIKEY'			=> 'from_your_facebook_app',
	'FB_SECRET'			=> 'from_your_facebook_app',
	'BASE_URL'			=> 'http://www.mywebsite.com/testing/', // Must end with a trailing slash!
	
	// No need to touch this, unless you want to
	'SIG_KEY'			=> 'Secret Key'
	));

// Simply, this will turn the above array into a constant, defined by $key => $value
foreach( $config as $key => $value ) {
	define( $key, $value );
}

function genSig() {
	$secret = $sigkey;
	$args = array(
	'argument1' => 'sampleargument1',
	'argument2' => 'sampleargument2'); // insert the actual arguments for your request in place of these example args 
	$request_str = '';
	foreach ($args as $key => $value)
	{
	$request_str .= $key . '=' . $value;
	}
	$sig = $request_str . $secret;
	return md5($sig);
}

?>