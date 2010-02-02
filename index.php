<?

// Load in the Config File with Constants
require_once 'config.php';
// Include the Facebook Library
include_once 'php/facebook.php';

// Load in the Facebook Class (loaded from 'php/facebook.php')
$facebook = new Facebook(FB_APIKEY, FB_SECRET);

// Start the Frame, and ensure there's a logged in Facebook User
$facebook->require_frame();
$user = $facebook->require_login();

$prefix = ($_REQUEST['fb_sig_user']) ? 'fb_sig' : $api_key;

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
	a, a:visited {
		color: #fff;
	}
	
	a:hover {
		color: #fff;
		text-decoration: none;
	}
	
</style>
</head>
<div class="top">
</div>
<div class="middle">
	<div class="content">
		<h1>Get in touch with Zendesk</h1>
		<form method="post" action="submit.php">
		<p>Feel free to ask us anything. Submit this form and a ticket will be created for our support staff to attend to. You'll be notified just as if you had created a ticket by sending an email to our support address or submitted a ticket form on our support site.</p>
		<TEXTAREA NAME="contents" COLS=40 ROWS=6 style="width: 400px"></TEXTAREA><br><br>
		<label>Subject</label><br>
		<input type="text" name="subject" value="" style="width: 400px" /><br><br>
		<label>Your Email</label><br>
		<input type="text" name="email" value="" style="width: 400px" /><br><br>
		<div style="text-align:left; float:left"><input type="image" src="<?=BASE_URL?>images/submit_bu.jpg" name="submit" border="0" alt="Submit" width="99" height="27" value="submit" /></div><div style="text-align:right; float:right;"><img src="<?=BASE_URL?>images/powered-by-zendesk.jpg" /></div>
		</form>
	</div>
</div>
<div class="bottom">
	<div style="float:left"><p>Copyright © 2008 <a href="http://www.zendesk.com/?source=FBDB">Zendesk</a>. All Rights Reserved.</p></div>
</div>
</html>