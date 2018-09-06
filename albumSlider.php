<?php
		use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;
		use Facebook\FacebookRequest;
		use Facebook\FacebookResponse;
		use Facebook\FacebookSDKException;
		use Facebook\FacebookRequestException;
		use Facebook\FacebookAuthorizationException;
		use Facebook\GraphObject;
	
		include('siteConfig.php');
    	    if ( isset( $session ) )
		    {
    			$alb_id=$_POST['album_id'];
                $album_photo = datafromfacebook ( '/' . $alb_id . '/photos?fields=source&limit=2000&' );
    			if(!empty($album_photo))
    			{
                    $count=0;
					foreach($album_photo['data'] as $alb)
					{
						$alb=(array) $alb;

                        $response[$count]['src'] = $alb['source'];
                        $response[$count]['thumb'] = $alb['source'];
                        $count++;
					}
                    echo json_encode($response);
    			}
		    }
?>
