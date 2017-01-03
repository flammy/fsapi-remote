<?php
	//require_once("vendor/autoload.php");
	require_once('src/autoload.php');
	$Config = new Config;
	$config = $Config->read();
	$folder = str_replace(basename($_SERVER['SCRIPT_NAME']),'',$_SERVER['SCRIPT_NAME']);
	
	$Xajax   = new xajax();
	//include('backend.php');
	
	$Backend = new Backend($Xajax,$Config);
	
	//$Xajax->register(XAJAX_FUNCTION, new xajaxUserFunction('refresh', $Backend, 'refresh'));
	$Xajax->processRequest();
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8"/>
	<title>FSAPI - Remote</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- css -->
	<link rel="stylesheet" href="vendor/components/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/components/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="css/style.css">

	<!-- js -->
	<script src="vendor/components/jquery/jquery.min.js"></script>
	<script src="vendor/components/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/script.js"></script>
	
	
	<!-- start xajax-js-->
	<?php
	
	

	//die($_SERVER["REQUEST_URI"].'vendor/xajax/');
	$Xajax->configure('javascript URI',$folder .'vendor/xajax/xajax/');
	$Xajax->printJavascript(); 
	?>
	<!-- end xajax-js-->
	</head>
	<body>

		<!-- start content/home.php -->
		<nav class="navbar navbar-default">
		  <!--start container-fluid -->
		  <div class="container-fluid">
			<!-- start navbar-header -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <?php
		      echo '<a class="navbar-brand" href="'.((isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] != "") ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'" class="btn btn-default">FSAPI-Remote</a>';
		      ?>
		
		    </div>
			<!-- end navbar-header -->
			<!-- start navbar-collapse -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <!-- start left navbar -->
			  <ul class="nav navbar-nav navbar-left">
			  	<li>
			  		<?php
						if(!isset($_REQUEST['setup']) || $_REQUEST['setup'] != true){
			  		?>
			  		<form class="form-inline" style="margin-top: 7px;">
					<select class="form-control" id="devicesel">
						<?php
						foreach($config as $index => $config){
							$selected = '';
							if($Backend->getActiveDevice() == $index){
								$selected = 'selected="selected"';
							}
							echo  '<option value="'.$index.'" '.$selected.' >'.$config['friendlyname'].'</option>';
						}
						?>
					</select>
					<?php
						echo '<a href="'.((isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] != "") ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?setup=true" class="btn btn-default">setup</a>';
					?>
					</form>
					<?php
						}
					?>
			  	</li>
		      </ul>
			  <!-- end left navbar -->
			  <!-- end right navbar -->
		      <ul class="nav navbar-nav navbar-right">
		        <li>
				  	<button type="button" id="power" class="btn btn-sm btn-default netRemote_sys_power"><span class="glyphicon glyphicon-off" aria-label="Left Align" aria-hidden="true"></span> ON</button>
		        </li>
		      </ul>
			  <!-- end right navbar -->
		    </div>
			<!-- end navbar-collapse -->
		  </div>
		 <!--end container-fluid -->
		</nav>
			<div id="alert-success" class="alert alert-success" role="alert" style="display: none;"></div>
			<div id="alert-info" class="alert alert-info" role="alert" style="display: none;"></div>
			<div id="alert-warning" class="alert alert-warning" role="alert" style="display: none;"></div>
			<div id="alert-danger" class="alert alert-danger" role="alert" style="display: none;"></div>
		<?php
		if(isset($_REQUEST['setup']) && $_REQUEST['setup'] == true){
			include('content/setup.php');

		}else{
			include('content/home.php');

		}
		?>
		<script type="text/javascript">
				$('#devicesel').change( function() {
						xajax_devicesel($(this).val());
				});
		</script>
	</body>
</html>
