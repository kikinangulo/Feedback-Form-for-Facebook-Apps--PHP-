<?

// Load in the Config File with Constant
require_once 'config.php';
// Include the Facebook Library
include_once 'php/facebook.php';

// Load in the Facebook Class (loaded from 'php/facebook.php')
$facebook = new Facebook(FB_APIKEY, FB_SECRET);

// Start the Frame, and ensure there's a logged in Facebook User
$facebook->require_frame();
$user = $facebook->require_login();

$prefix = ($_REQUEST['fb_sig_user']) ? 'fb_sig' : FB_APIKEY;

 if( isset($_REQUEST[$prefix.'_session_key']) ){
    session_name( $_REQUEST[$prefix.'_session_key'] );
    session_start();

    $_SESSION['fb_user']        = $_REQUEST[$prefix.'_user'];
    $_SESSION['fb_session_key'] = $_REQUEST[$prefix.'_session_key'];
    $_SESSION['fb_expires']     = $_REQUEST[$prefix.'_expires'];
    $_SESSION['fb_in_canvas']   = $_REQUEST[$prefix.'_in_canvas'];
    $_SESSION['fb_time']        = $_REQUEST[$prefix.'_time'];
    $_SESSION['fb_profile_update_time'] = $_REQUEST[$prefix.'_profile_update_time'];
    $_SESSION['fb_api_key']     = $_REQUEST[$prefix.'_api_key'];
 } else {
    // Just so there *is* a session for times when there is no fb session
    session_start();
 }

// Get the Sessions Key, if there is one
$session_key = $facebook->api_client->session_key;
// Get the Auth Signature
$sig = genSig();
// Set the current Facebook User
$current_user = $facebook->api_client->users_getLoggedInUser($api_key, $session_key, microtime(true), $sig, 1.0);

// Start running through checks on the form
if($_POST) {
	$display = "";
	if (!array_key_exists('email', $_POST) || !array_key_exists('contents', $_POST) || !array_key_exists('subject', $_POST)) {
		$display .= "<br />Missing form fields, please fill in all fields before proceeding.";
	}
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email'])) {
		$display .= "<br />Email format is incorrect.";
	}
	if ($_POST['subject'] == "") {
		$display .= "<br />Subject can't be empty.";
	}
	if ($_POST['contents'] == "") {
		$display .= "<br />Content can't be empty.";
	}
	if($display=="") {
		require_once 'Zendesk.lib.php';
		
		// Set the XML needed to process the ticket
		$xml_source = "<ticket><requester-email>" . $_POST["email"] . "</requester-email><subject>" . $_POST["subject"] . "</subject><description>" . $_POST["contents"] . "</description><via-id>5</via-id><set-tags>fb_buddhamachine</set-tags></ticket>";

		// Initiate processing
		$process_ticket = new zd_ticket($xml_source);
		
		if(!$process_ticket) {
			$display = "<br />There was an error, please make sure the form was filled in correctly.";
		}
		else {
			$display = "<br />Your ticket was submitted, thank you.";
		}
	}
}
else {
	$display = "<br />There was an error, please make sure the form was filled in correctly.";
}

?>
<html>
<head>
	<style>
	body {
		width:760px;
		font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;
	}
	.top {
		width: 759px;
		height: 55px;
		background: url(<?=BASE_URL?>images/bg.jpg) no-repeat;
		padding: 0;
		margin: 0;
	}
	.middle {
		width: 759px;
		height: 380px;
		background: url(<?=BASE_URL?>images/bg.jpg) 0 -55px  no-repeat;
		padding: 0;
		margin: 0;
	}
	.bottom {
		width: 759px;
		height: 80px;
		background: url(<?=BASE_URL?>images/bg.jpg) 0 -435px  no-repeat;
		margin: 0;
	}
	.bottom p {
		font: 11px Arial;
		padding: 40px 20px 0 20px;
		color: #fff;
	}

	.content {
		margin: 0 0 0 70px;
		width: 455px;
		color: #666;
	}
	.content h1 {
		color: #84bb41;
		font: 24px Arial;
		font-weight: bold;
	}
	
	p {
		font-size: 12px;
	}
	
	label {
		font: 14px Arial;
		font-weight: bold;
		color: #84bb41;
		padding-bottom: 5px;
	}
	
	</style>
</head>
<div class="top">
</div>
<div class="middle">
	<div class="content">
		<h1>Get in touch with Zendesk</h1>
		<p><?=$display?></p>
		<p><a href="http://apps.facebook.com/<?=FB_APPNAME?>/dropbox/index.php">Go back</a></p>
		
		<div style="text-align:right; float:right;"><img src="<?=BASE_URL?>images/powered-by-zendesk.jpg" /></div>

	</div>
</div>
<div class="bottom">
	<div style="float:left"><p>Copyright © 2008 <a href="http://www.zendesk.com/?source=FBDB">Zendesk</a>. All Rights Reserved.</p></div>
</div>
</html>