<?php
//Application Configurations
$app_id        = "544782395574012";
$app_secret    = "800d3e20387241a325d33a8441da0550";
$site_url      = "http://localhost/post-to-multiple-facebook-wall/";






try{
	include_once "src/facebook.php";
}catch(Exception $e){
	error_log($e);
}
// Create our application instance
$facebook = new Facebook(array(
	'appId'		=> $app_id,
	'secret'	=> $app_secret,
	));

// Get User ID
$user = $facebook->getUser();
// We may or may not have this data based 
// on whether the user is logged in.
// If we have a $user id here, it means we know 
// the user is logged into
// Facebook, but we don’t know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if($user){
//==================== Single query method ======================================
	try{
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
		//Get user pages details using Facebook Query Language (FQL)
//		$fql_query = 'SELECT page_id, name, page_url FROM page WHERE page_id IN (SELECT page_id FROM page_admin WHERE uid='.$fbuser.')';
//		$postResults = $facebook->api(array( 'method' => 'fql.query', 'query' => $fql_query ));
		
		
		
	}catch(FacebookApiException $e){
		error_log($e);
		$user = NULL;
	}
//==================== Single query method ends =================================
}

if($user){
	// Get logout URL
	$logoutUrl = $facebook->getLogoutUrl();
}else{
	// Get login URL
	$loginUrl = $facebook->getLoginUrl(array(
 		'scope'			=> 'publish_stream user_groups manage_pages read_insights',		
		
		'redirect_uri'	=> $site_url,
		));
}

if($user){
	// Proceed knowing you have a logged in user who has a valid session.

//========= Batch requests over the Facebook Graph API using the PHP-SDK ========
	// Save your method calls into an array
	$queries = array(
		array('method' => 'GET', 'relative_url' => '/'.$user),
		array('method' => 'GET', 'relative_url' => '/'.$user.'/friends'),
		array('method' => 'GET', 'relative_url' => '/'.$user.'/groups'),
//		array('method' => 'GET', 'relative_url' => '/'.$user.'/likes'),
		array('method' => 'GET', 'relative_url' => '/'.$user.'/accounts?fields=location,link,name,likes,description'),
		);

	// POST your queries to the batch endpoint on the graph.
	try{
		$batchResponse = $facebook->api('?batch='.json_encode($queries), 'POST');
	}catch(Exception $o){
		error_log($o);
	}

	//Return values are indexed in order of the original array, content is in ['body'] as a JSON
	//string. Decode for use as a PHP array.
	$user_info		= json_decode($batchResponse[0]['body'], TRUE);
	$friends_list	= json_decode($batchResponse[1]['body'], TRUE);
	$groups			= json_decode($batchResponse[2]['body'], TRUE);
	$pages			= json_decode($batchResponse[3]['body'], TRUE);		
	
	
	
//========= Batch requests over the Facebook Graph API using the PHP-SDK ends =====



	if(isset($_POST['submit_x'])){
		if($_POST['message'] || $_POST['link'] || $_POST['picture']) {

			$batchPost=array();

			$i=1;
			$flag=1;
			foreach($_POST as $key => $value) {
				if(strpos($key,"id_") === 0) {
				
					$page_info = $facebook->api("/$value?fields=access_token");
					$body = array(
						'access_token'  			=> $page_info['access_token'],
						'message'					=> $_POST['message'],
						'link'						=> $_POST['link'],
						'picture'					=> $_POST['picture'],
						'name'						=> $_POST['name'],
						'caption'					=> $_POST['caption'],
						'description'				=> $_POST['description'],
//						'source' => $_POST['description'],,
//						'scheduled_publish_time'	=> $_POST['scheduled'],
//						'published' => 'false', //Keep photo unpublished//
						
					);

				
					$batchPost[] = array('method' => 'POST', 'relative_url' => "/$value/feed", 'body' => http_build_query($body) );
//					$batchPost[] = array('method' => 'POST', 'relative_url' => "/$value/photos", 'body' => http_build_query($body) );

					if($i++ == 50) {
						try{
							$multiPostResponse = $facebook->api('?batch='.urlencode(json_encode($batchPost)), 'POST');							
						}catch(FacebookApiException $e){
							error_log($e);
							echo("Batch Post Failed");
						}
						$flag=0;
						unset($batchPost);
						$i=1;
					}
				}
			}
			if(isset($batchPost) && count($batchPost) > 0 ) {
				try{
					$multiPostResponse = $facebook->api('?batch='.urlencode(json_encode($batchPost)), 'POST');
				}catch(FacebookApiException $e){
					error_log($e);
					echo("Batch Post Failed");
				}
				$flag=0;
			}

		}
		else {
			$flag=2;
		}
	}
}
?>