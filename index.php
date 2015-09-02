<?php
	require_once("xajax/xajax_core/xajax.inc.php");
	$xajax = new xajax();
	include('backend.php');
	$xajax->processRequest();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8"/>
	<title>FSAPI - Remote</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- css -->
	<link rel="stylesheet" href="bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="bootstrap/plugins/slider/css/slider.css">

	<!-- js -->
	<script src="jquery/1.11.2/jquery.min.js"></script>
	<script src="bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="bootstrap/plugins/slider/js/bootstrap-slider.js"></script>

	<!-- override css + own scripts-->
	<script src="js/script.js"></script>
	<link rel="stylesheet" href="css/style.css">
	
	<!-- start xajax-js-->
	<?php 
	$xajax->configure('javascript URI','/fsapi-remote/xajax/');
	$xajax->printJavascript(); 
	?>
	<!-- end xajax-js-->
	</head>
	<body>
		<?php
		if(isset($_REQUEST['setup']) && $_REQUEST['setup'] == true){
			include('content/setup.php');

		}else{
			include('content/home.php');

		}
		?>
	</body>
</html>
