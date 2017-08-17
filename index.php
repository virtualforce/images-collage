<?php
include 'config/connection.php';
include 'config/functions.php';

//$sub_folder = '/newcollage';
$sub_folder = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Images Collage</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body style="background:url(collage.png) no-repeat center center; background-size: cover;">
  <div class="sectionWrapper sectionOne"></div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
var winheight = jQuery(window).height();
var winh = winheight + "px";
jQuery(".sectionOne").height(winh);
</script>
