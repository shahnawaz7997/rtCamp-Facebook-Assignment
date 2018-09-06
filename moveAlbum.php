 <?php

ini_set('max_execution_time', 300);

include 'siteConfig.php';

function url_get_contents ($Url) {

    if (!function_exists('curl_init')){
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function moveToDrive($album_id,$folderId,$drive){

    $photos = datafromfacebook ( '/' . $album_id . '/photos?fields=source&limit=100' );
    $album = datafromfacebook ( '/' . $album_id .'?fields=id,name');
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $album['name'],
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($folderId)
    ));

    $file = $drive->files->create($fileMetadata, array('fields' => 'id'));
    $album_folder = $file->id;

    $offset=0;
    while(count($photos['data']) > 0)
    {
        foreach ($photos['data'] as $photo) {
            $photo = ( array ) $photo;
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => uniqid().'.jpg',
                'parents' => array($album_folder)
            ));
            $content = url_get_contents($photo['source'] );
            $file = $drive->files->create($fileMetadata, array(
                'data' => $content,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart',
                'fields' => 'id'
            ));
         }
         $offset+=100;
         $photos = datafromfacebook ( '/' . $album_id . '/photos?fields=source&limit=100&offset='.$offset );
    }
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

    $client->setAccessToken($_SESSION['access_token']);
    if ($client->isAccessTokenExpired()) {
        echo "Session Expired. Logout and Login Again to Google";
        echo '<script type="text/javascript">window.open("https://rtcamp-fb-assignment.000webhostapp.com/googleAuth.php", "Drive Access", width="700", height="380");</script>';
        exit;
    }
    $drive = new Google_Service_Drive($client);
    $user = datafromfacebook ( '/me?fields=first_name,last_name');
    $username = 'facebook_'.$user['first_name'].'_'.$user['last_name'].'_albums';
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $username,
        'mimeType' => 'application/vnd.google-apps.folder'));
    $file = $drive->files->create($fileMetadata, array('fields' => 'id'));
    $folderId = $file->id;

    if(isset($_POST['move_single'])) {
        $album_id = $_POST['album_id'];
        moveToDrive($album_id,$folderId,$drive);
        echo '<div class="text-center text-success">
                    <i class="fa fa-check-circle fa-3x"></i>
                    <br>
                    <h3 class="text-success">Success!</h3>
                </div>';
    }

    if(isset($_POST['move_selected'])) {
        $album_ids = explode ( "/", $_POST ['albums'] );
        foreach ( $album_ids as $album_id ) {
            $album_id = explode( ",", $album_id );
            moveToDrive($album_id [0],$folderId,$drive);
        }
        echo '<div class="text-center text-success">
                    <i class="fa fa-check-circle fa-3x"></i>
                    <br>
                    <h3 class="text-success">Success!</h3>
                </div>';
    }

    if(isset($_POST['move_all'])) {
        $albums=datafromfacebook ('/me/albums?fields=id,name');

        foreach ($albums['data'] as $album) {
            $album = (array) $album;
            moveToDrive($album['id'],$folderId,$drive);
        }
        echo '<div class="text-center text-success">
                    <i class="fa fa-check-circle fa-3x"></i>
                    <br>
                    <h3 class="text-success">Success!</h3>
                </div>';
    }

} else {
    echo "Please Login First";
    echo '<script type="text/javascript">window.open("https://rtcamp-fb-assignment.000webhostapp.com/googleAuth.php", "Drive Access", width="400", height="400");</script>';
}
