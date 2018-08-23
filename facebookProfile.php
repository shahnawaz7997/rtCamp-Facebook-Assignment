<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Facebook Album</title>
		<!-- for-mobile-apps -->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" /> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
		<!-- //for-mobile-apps -->
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="style.css" rel="stylesheet" type="text/css" media="all" />
		<!-- font-awesome icons -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
		<link href="css/font-awesome.css" rel="stylesheet"> 
		<script src="js/spin.min.js"></script>

		<!-- //font-awesome icons -->
		<link href="//fonts.googleapis.com/css?family=Gidugu" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
	</head>

<?php
	include_once('siteConfig.php');
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

   $google_session_token = "";
   try{
       if((isset($session))){
           $_SESSION['login_info'] = $session;
           $_SESSION['fb_token']=$session ->getToken();
           
           $user = datafromfacebook("/me?fields=id,name,gender,email,location");
           
           $_SESSION['user_id']= $user['id'];
           $_SESSION['username']=$user['name'];
       
			if ( isset( $_SESSION['google_session_token'] ) ) {
				$google_session_token = $_SESSION['google_session_token'];
			}
 ?>

<body>
	<div class="main" id="home" style="background-image: url(images/facebook-banner.jpg)">
	<!-- banner -->
		<div class="banner">
			<!--Slider-->
			<img src="https://graph.facebook.com/<?php echo $user['id']; ?>/picture" alt=" " class="img-responsive">
			<h2>Welcome, <?php echo $user['name']; ?></h2>
			<span>Email : <?php echo $user['email']; ?></span>	
		</div>
	<!-- //banner -->
	</div>
	<!-- about -->
	<div class="about" id="about">
		<div class="container">
			<h3 class="w3l_head">About <?php echo $user['name']; ?></h3>
			<p class="w3ls_head_para">A few information about <?php echo $user['name']; ?></p>
			<div class="w3l-grids-about">
				<div class="col-md-12 w3ls-agile-left">
					<div class="w3ls-agile-left-info">
						<h4>Name</h4>
						<p><?php echo $user['name']; ?></p>
					</div>
					<div class="w3ls-agile-left-info">	
						<h4>Sex</h4>
						<p><?php echo $user['gender']; ?></p>
					</div>
					<div class="w3ls-agile-left-info">
						<h4>Email Address</h4>
						<p><?php echo $user['email']; ?></p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //about-bottom -->
	<div class="w3_navigation">
		<div class="container">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>

				<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
					<nav class="cl-effect-1" id="cl-effect-1">
						<ul class="nav navbar-nav">
							<li><a href="" id="download-all-albums" class="scroll">Download All</a></li>
							<li><a href="" id="download-selected-albums" class="scroll hvr-bounce-to-bottom">Download Selected Album</a></li>
							<li><a href="" id="move_all" class="scroll hvr-bounce-to-bottom">Move All</a></li>
							<li><a href="" id="move_selected" class="scroll hvr-bounce-to-bottom">Move Selected</a></li>
							<li><a href="logout.php" id="">Logout</a></li>
						</ul>
					</nav>
				</div>
				<!-- /.navbar-collapse -->
			</nav>
		</div>
	</div>

	<!-- Album download report window -->   
    <div class="modal fade" id="download-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Albums Report</h4>
				</div>
				<div class="modal-body" id="display-response">
				<!-- Response will displayed over here -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
    </div>

    <!-- /gallery-->
	<div class="container">
		<div class="row">
        	<div class="col-md-12 text-center">
				<h3 class="w3l_head"><?php echo $user['name']; ?>'s Facebook Albums</h3>
			</div>
		</div>
		<div class="row">
			<div id ="centerDiv" style="position:absolute;top:1500px;left:50%"></div>
			<?php
			$alb_count=0;
			$albums = datafromfacebook( "/me/albums" );

			if (! empty ( $albums )) {
				$totalAlbum=0;
				$totalImages=0;

				foreach ( $albums ['data'] as $album ) {
					$totalAlbum++;
					$album = ( array ) $album;
					$album_photo = datafromfacebook( '/' . $album ['id'] . '/photos?fields=source' );
					if (! empty ( $album_photo )) {
						foreach ( $album_photo ['data'] as $alb ) {
							$alb = ( array ) $alb;
							$totalImages++;
						}
					?>
			<div class="col-md-4 col-sm-6 col-xs-12">
                <div class="album">
                    <a class="dynamic" data-id="<?php echo $album['id']; ?>">                       
                        <a href="albumSlider.php?ida=<?php echo $album['id']; ?>">
                        	<div class="album_img" style="background-image: url(<?php  echo $alb['source'];?>)"></div>
                        </a>
                    </a>
                    <a class="dynamic"><h3><?php echo $album['name']; ?></h3></a>
                    <div class="content">
                    	<div class="action">
                    		<div class="input-group">
								<span class="input-group-addon input-sm">
	                            	<input class="select-album" type="checkbox" value="<?php echo $album['id'].','.$album['name'];?>" />
								</span>
								<a>
									<button rel="<?php echo $album['id'].','.$album['name'];?>" class="single-download btn btn-primary btn-round">Download This Album</button>
								</a>
                            	<button class="btn btn-primary btn-round move_album" data-id="<?php echo $album['id']; ?>">Move</button>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
       <?php
   			}
        }
    }?>
</div>
</div>
<!-- footer -->
<div class="w3l_footer">
	<div class="container">
		<div class="w3ls_footer_grids">
			<div class="w3ls_footer_grid">
				<div class="col-md-6 w3ls_footer_grid_left">
					<div class="w3ls_footer_grid_leftl">
						<i class="fas fa-address-book" aria-hidden="true"></i>
					</div>
					<div class="w3ls_footer_grid_leftr">
						<h4>Total Albums</h4>
						<h4>(<?php echo $totalAlbum?>)</h4>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="col-md-6 w3ls_footer_grid_left">
					<div class="w3ls_footer_grid_leftl">
						<i class="fas fa-images" aria-hidden="true"></i>
					</div>
					<div class="w3ls_footer_grid_leftr">
						<h4>Total Images</h4>
						<h4>(<?php echo $totalImages ?>)</h4>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
</div>
<!-- //footer -->

<?php }
    }catch ( Exception $ex ) {
			echo $ex;
}?>
<script src="js/jquery-2.2.3.min.js"></script> 
<!-- js -->
<script src="js/jquery.mobile.custom.min.js"></script>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
<!-- //js -->
<script src="js/bootstrap.js"></script>
<!-- //for bootstrap working -->
<!-- here stars scrolling icon -->
<script type="text/javascript">
	$(document).ready(function() {						
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>
<!-- //here ends scrolling icon -->
<script type="text/javascript">
	var opts = {

lines: 20 // The number of lines to draw
, length: 200 // The length of each line
, width: 33 // The line thickness
, radius: 42 // The radius of the inner circle
, scale: 100 // Scales overall size of the spinner
, corners: 1 // Corner roundness (0..1)
, color: '#3c5a99' // #rgb or #rrggbb or array of colors
, opacity: 0.25 // Opacity of the lines
, rotate: 0 // The rotation offset
, direction: 1 // 1: clockwise, -1: counterclockwise
, speed: 1 // Rounds per second
, trail: 60 // Afterglow percentage
, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
, zIndex: 2e9 // The z-index (defaults to 2000000000)
, className: 'spinner' // The CSS class to assign to the spinner
, top: '70%' // Top position relative to parent
, left: '50%' // Left position relative to parent
, shadow: false // Whether to render a shadow
, hwaccel: false // Whether to use hardware acceleration
, position: 'absolute' // Element positioning Element positioning 
// Left position relative to parent
};

var target = document.getElementById('centerDiv');	
function append_download_link(url) {
	var spinner = new Spinner(opts).spin(target);
	$.ajax({
		url:url,
		success:function(result){
			$("#display-response").html(result);
			spinner.stop();
			$("#download-modal").modal({
				show: true
			});
		}
	});
}

//download all albums
$("#download-all-albums").on("click", function() {
        append_download_link("downloadAlbum.php?zip=1&all_albums=all_albums");
});

//single album download
$(".single-download").on("click", function() {        
	var rel = $(this).attr("rel");
	var album = rel.split(",");
	append_download_link("downloadAlbum.php?zip=1&single_album="+album[0]+","+album[1]);
});

//get selected checkboxes    
function get_all_selected_albums() {
	var selected_albums;
	var i = 0;
	$(".select-album").each(function () {
		if ($(this).is(":checked")){
			if (!selected_albums) {
				selected_albums = $(this).val();
			} else {
				selected_albums = selected_albums + "/" + $(this).val();
			}
		}
	});
	return selected_albums;
}

//download selected album
$("#download-selected-albums").on("click", function() {
    var selected_albums = get_all_selected_albums();
	append_download_link("downloadAlbum.php?zip=1&selected_albums="+selected_albums);
});

//move all albums to google drive
$('#move_all').click(function () {
	var spinner = new Spinner(opts).spin(target);
	$.ajax({
		type:'POST',
		url: 'moveAlbum.php',
		data:{
			move_all:''
		},
		success:function(res){
			$("#display-response").html(res);
            spinner.stop();
            $("#download-modal").modal({
				show:true
            });
        }
    });
});

//move selected albums to google drive
$('#move_selected').click(function () {
	var selected_albums = get_all_selected_albums();
	var spinner = new Spinner(opts).spin(target);

	$.ajax({
		type:'POST',
		url: 'moveAlbum.php',
		data:{
			albums:selected_albums,
			move_selected:''
		},
		success:function(res){
			$("#display-response").html(res);
			spinner.stop();
			$("#download-modal").modal({
                show:true
            });
        }
    });
});

//move single albums to google drive
$('.move_album').click(function () {
	var album_id = $(this).data('id');
	var spinner = new Spinner(opts).spin(target);

	$.ajax({
		type:'POST',
		url: 'moveAlbum.php',
		data:{
			album_id:album_id,
			move_single:''
        },
		success:function(res){
			$("#display-response").html(res);
			spinner.stop();
            $("#download-modal").modal({
				show:true
			});
		}
    });
});

</script>
</body>
</html>