<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Facebook Album</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="">
        <link rel="apple-touch-icon" href="">
        <link rel="stylesheet" type="text/css" href="node_modules/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="node_modules/css/font-awesome.min.css">
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>

<?php include_once('siteConfig.php'); ?>

    <body>
        <div class="container" style="position:absolute;top:160px;left:7%">
            <div class="text-center">
                <img src="images/fb-logo.png" style="border-radius: 10%;height: 250px;width: 250px;">
            </div>
            <br />
            <br />
            <div class="text-center">
                <a href="<?php echo $helper->getLoginUrl (array("user_photos","public_profile"));?>">
                    <button  type="button" class="btn btn-primary btn-lg">Login to Facebook</button>
                </a>
            </div>
        </div>
    </body>
</html> 
