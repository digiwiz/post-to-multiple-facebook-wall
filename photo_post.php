<?php
       $app_id = "544782395574012";
	   $app_secret    = "800d3e20387241a325d33a8441da0550";
       $post_login_url = "http://localhost/post-to-multiple-facebook-wall/photo_post.php";
    
       $code = $_REQUEST["code"];

       //Obtain the access_token with publish_stream permission 
       if(empty($code)){ 
          $dialog_url= "http://www.facebook.com/dialog/oauth?"
           . "client_id=" .  $app_id 
           . "&redirect_uri=" . urlencode( $post_login_url)
           .  "&scope=publish_stream";
          echo("<script>top.location.href='" . $dialog_url 
          . "'</script>");
         }
        else {
          $token_url="https://graph.facebook.com/oauth/access_token?"
           . "client_id=" . $app_id 
		   . "&redirect_uri=" . urlencode( $post_login_url)
           . "&client_secret=" . $app_secret
           . "&code=" . $code;
          $response = file_get_contents($token_url);
          $params = null;
          parse_str($response, $params);
          $access_token = $params['access_token'];

         // Show photo upload form to user and post to the Graph URL
         $graph_url= "https://graph.facebook.com/me/photos?"
         . "access_token=" .$access_token;

         echo '<html><body>';
         echo '<form enctype="multipart/form-data" action="'
         .$graph_url .' "method="POST">';
         echo 'Please choose a photo: ';
         echo '<input name="source" type="file"><br/><br/>';
         echo 'Say something about this photo: ';
         echo '<input name="message" 
             type="text" value=""><br/><br/>';
         echo '<input type="submit" value="Upload"/><br/>';
         echo '</form>';
         echo '</body></html>';
      }
?>