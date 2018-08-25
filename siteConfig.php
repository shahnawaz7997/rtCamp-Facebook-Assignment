<?php
ini_set('max_execution_time', 300);

$fb_app_id = 'XXXXXXXXXXXXXXX';
$fb_secret_id = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
$fb_login_url = 'http://rtcamp-fb-assignment.000webhostapp.com/facebookProfile.php';

require_once ('node_modules/lib/Facebook/autoload.php');
require_once ('node_modules/lib/google/vendor/autoload.php');

/**
 *
 * defines the namespace alies
 * for the use of facebook namespace
 * easy to use
 */

session_start();
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;

// setting application configuration and session

FacebookSession::setDefaultApplication($fb_app_id, $fb_secret_id);
$helper = new FacebookRedirectLoginHelper($fb_login_url);

if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
	$session = new FacebookSession($_SESSION['fb_token']);
	try {
		if (!$session -> validate()) {
			$session = null;
		}
	} catch ( Exception $e ) {
		$session = null;
	}
}
if (!isset($session) || $session === null) {
	try {
		$session = $helper -> getSessionFromRedirect();
	} catch( FacebookRequestException $ex ) {
		print_r($ex);
	} catch( Exception $ex ) {
		print_r($ex);
	}
}

//It will fetch the data from facebook according to specified query passes as an argument
function datafromfacebook($url){
       $session = new FacebookSession($_SESSION['fb_token']);
       $request = new FacebookRequest($session, 'GET', $url);
	$response = $request -> execute();
	$user = $response -> getGraphObject() -> asArray();

	return $user;
   }


//Google Configration
$google_redirect_url = "http://rtcamp-fb-assignment.000webhostapp.com/googleAuth.php";
$client = new Google_Client();
$client->setAuthConfigFile('credentials.json');
$client->setRedirectUri($google_redirect_url);
$client->addScope(Google_Service_Drive::DRIVE);

?>
