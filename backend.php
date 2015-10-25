<?php


/**
 * This file contains all xajax functions
 *
 * all functions 
 *
 *
 */

// declare local functions as xajax function:
$xajax->register(XAJAX_FUNCTION,"buttonPress");
$xajax->register(XAJAX_FUNCTION,"ListItemPress");
$xajax->register(XAJAX_FUNCTION,"refresh");
$xajax->register(XAJAX_FUNCTION,"devices");
$xajax->register(XAJAX_FUNCTION,"devicescan");
$xajax->register(XAJAX_FUNCTION,"devicesave");
$xajax->register(XAJAX_FUNCTION,"devicedel");
$xajax->register(XAJAX_FUNCTION,"devicesel");

/* setup credentials*/
require_once('fsapi/radio.php');
$radio = new radio();
$configs = config_read();
// determine the last active device
if(($configs[0] == true) && (count($configs[1]) > 0)){
	$i = 0;
	$act_device  = "";
	foreach($configs[1]  as $key => $config){
		if($i == 0){
			$act_device = $key;
		}elseif(isset($config['active']) && ($config['active'] == true)) {
			$act_device = $key;
		}
		$i ++;
	}
	// use this device as active device
	$radio->setpin($configs[1][$act_device]['pin']);
	$radio->sethost($configs[1][$act_device]['host']);
}


/**
 *	this function is called via xajax. It updates all values on the frontend
 *
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function refresh(){
	global $radio;
	$objResponse = new xajaxResponse();
	$objResponse->script("console.log('refresh');");
	// disabled self refering update, needs some blocking
	//        $objResponse->script("window.setTimeout(xajax_refresh(), 100000 );");
	
	// get all known values available via get
	$stats = $radio->system_status();
	
	// There was an error
	if($stats[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $stats[1]);
		return $objResponse;
	}

	// The resultset is empty
	if(count($stats[1]) < 1){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", 'Got an empty resultset from fsapi.');
		return $objResponse;
	}

	// Everything is okay
	if($stats[0] == 1){
		foreach($stats[1] as $key => $value){
			$objResponse->script("update_fields('".str_replace('.','_',$key)."','".$value."')");
		}
	}

	// define meta status for toggeling multiple buttons with one value
	if($stats[1]['netRemote.play.status'] == 'playing'){
		 $objResponse->script("update_fields('netRemote_play_status_play','on')");
	}elseif($stats[1]['netRemote.play.status'] == 'stopped'){
		 $objResponse->script("update_fields('netRemote_play_status_stop','on')");
	}elseif($stats[1]['netRemote.play.status'] == 'paused'){
		 $objResponse->script("update_fields('netRemote_play_status_pause','on')");
	}

	// get the list of available modes
	$response = $radio->validModes();
	if($response[0] == 1){
	$modes = array(-2 => '<a href="#" class="list-group-item disabled">Mode</a>');
	foreach($response[1] as $key => $value){
		if($value['selectable'] == 1){
			if($stats[1]['netRemote.sys.mode'] == $value['label']){
				$modes[$key] = '<a id="mode_'.$key.'" href="#" class="active list-group-item">'.$value['label'].'</a>';
			}else{
				$modes[$key] = '<a id="mode_'.$key.'" href="#" class="list-group-item">'.$value['label'].'</a>';
			}
		}
	}
	}
	$objResponse->script("update_fields('netRemote_sys_mode_list','".implode('',$modes)."')");

	// get the list of available eqs-presets
	$response = $radio->eqPresets();
	if($response[0] == 1){
		$eqs = array(-2 => '<a href="#" class="list-group-item disabled">EQS</a>');
		foreach($response[1] as $key => $value){
						if($stats[1]['netRemote.sys.audio.eqPreset'] == $value['label']){
								$eqs[$key] = '<a id="eqs_'.$key.'" href="#" class="active list-group-item">'.$value['label'].'</a>';
						}else{
								$eqs[$key] = '<a id="eqs_'.$key.'" href="#" class="list-group-item">'.$value['label'].'</a>';
						}
		}
		$objResponse->script("update_fields('netRemote_sys_caps_eqPresets_list','".implode('',$eqs)."')");
	}

	//  get the list of available favorite stations for the current mode
	$response = $radio->NavPresets();
	if($response[0] == 1){
			$favs = array(-2 => '<a href="#" class="list-group-item disabled">Favorites</a>');
			foreach($response[1] as $key => $value){	
					$favs[$key] = '<a id="favs_'.$key.'" href="#" class="list-group-item">'.$value['name'].'</a>';
			}
	$objResponse->script("update_fields('netRemote_nav_presets_list','".implode('',$favs)."')");
	}

	//  get the list of available navigation items
	$response = $radio->NavLists();
	if($response[0] == 1){
			$navs = array(-2 => '<a href="#" class="list-group-item disabled">Channels</a>');
			foreach($response[1] as $key => $value){
					if($value['type'] == 0){
						$navs[$key] = '<a id="navs_'.$key.'" href="#" class="list-group-item"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;&nbsp;'.$value['name'].'</a>';
					}elseif($value['type'] == 1){
						$navs[$key] = '<a id="navs_'.$key.'" href="#" class="list-group-item"><span class="glyphicon glyphicon-music" aria-hidden="true"></span>&nbsp;&nbsp;'.$value['name'].'</a>';
					}else{
						$navs[$key] = '<a href="#" class="list-group-item disabled"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>&nbsp;'.$value['name'].'('.$value['type'].'-'.$value['subtype'].')</a>';
					}
			}
		$objResponse->script("update_fields('netRemote_nav_list','".implode('',$navs)."')");
	}
	return $objResponse;
}


/**
 * this function is called via xajax. It is fired by the onclick-event of an list-item 
 *
 * 	@var string $id - the id of the list-item. Coinstantaneous the id is used to determine what to do
 *
 *	@return xajaxResponse Object to manipulate the dom
 *	
 */
function ListItemPress($id){
	global $radio;
	$objResponse = new xajaxResponse();
	list($type,$mode_id) = explode("_",$id);
	switch($type){
		case 'mode':
			// Change mode
			$response = $radio->mode($mode_id);
		break;
		case 'eqs':
			// Change eq-preset 
			$response = $radio->eq_preset($mode_id);
		break;
		case 'favs':
			// Select channel from favourites 
			$response = $radio->SelectFavorite($mode_id);
		break;
		case 'navs':
			// Select an navigation item
			$response = $radio->openNavItem($mode_id);
		break;
	}
	// check if everythink is ok
	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
		return $objResponse;
	}
	// Tell the browser it should fire xajax_refresh to get all new values
	$objResponse->script("xajax_refresh();");
	return $objResponse;

}


/**
 *	this function is called via xajax. It is fired by the onclick-event of an button
 *
 *	@var string $id - the id of the html-button. Coinstantaneous the id is used to determine what to do
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function buttonPress($id){
	global $radio;
	$objResponse = new xajaxResponse();
	$objResponse->script("console.log('".$id."');");
	switch($id){
		case 'volume-up':
                $response = $radio->volume('up');
			break;
		case 'volume-down':
					$response = $radio->volume('down');
			break;
		case 'volume-mute':
			$response = $radio->mute('toggle');
			if($response[0] == 1){
				if($response[1] == 'on'){
					$objResponse->script("$('#".$id."').removeClass( 'btn-default' )");
					$objResponse->script("$('#".$id."').addClass( 'btn-danger' )");
				}else{
					$objResponse->script("$('#".$id."').removeClass( 'btn-danger' )");
					$objResponse->script("$('#".$id."').addClass( 'btn-default' )");
				}
			}
			break;
		case 'power':
			$response = $radio->power('toggle');
			if($response[0] == 1){
				if($response[1] == 'on'){
					$objResponse->script("$('#".$id."').removeClass( 'btn-danger' )");
					$objResponse->script("$('#".$id."').addClass( 'btn-success' )");
					$objResponse->script("$('#".$id."').html('<span class=\"glyphicon glyphicon-off\" aria-label=\"Left Align\" aria-hidden=\"true\"></span> ON')");
				}else{
					$objResponse->script("$('#".$id."').removeClass( 'btn-success' )");
					$objResponse->script("$('#".$id."').addClass( 'btn-danger' )");
					$objResponse->script("$('#".$id."').html('<span class=\"glyphicon glyphicon-off\" aria-label=\"Left Align\" aria-hidden=\"true\"></span> OFF')");
				}
			}
			break;
		case 'play-step-backward':
			// not implemented yet
			break;
		case 'play-fast-backward':
			// not implemented yet
			break;
		case 'play-backward':
			// not implemented yet
			break;
		case 'play-start':
			$response = $radio->control('play');
			break;
		case 'play-pause':
			$response = $radio->control('pause');
			break;
		case 'play-stop':
			$response = $radio->control('stop');
				break;
		case 'play-forward':
			// not implemented yet
				break;
		case 'play-fast-forward':
			// not implemented yet
				break;
		case 'play-step-forward':
			// not implemented yet
				break;
		case 'play-random':
			$response = $radio->shuffle('toggle');
			break;
		case 'play-repeat':
			$response = $radio->repeat('toggle');
				break;
	}
	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
	}


	// Tell the browser it hast to call our refresh function to refresh all values after this request
	$objResponse->script("xajax_refresh();");
	return $objResponse;
}


/**
 *	this function is called via xajax. It does a device scan via ssdp
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function devicescan(){
	global $radio;
	$start = time();
	$objResponse = new xajaxResponse();
	$response = $radio->devicescan();

	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
		return $objResponse;
	}

	$add_html = "";
	$add_js = "";

	foreach($response[1] as $row => $dataset){
		if(isset($dataset['location'])){
			if(preg_match('([0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})', $dataset['details']['webfsapi'], $ip) == 1){

				$add_html ='
				  <li class="media">
				    <div class="media-left media-middle">
				      <a href="#">
				        <img class="media-object" src="img/devices/unknown.jpg" alt="device image">
				      </a>
				    </div>
				    <div class="media-body">
				      <h4 class="media-heading">'.$dataset['details']['friendlyName'].' <button type="button" id="add'.$row.'" class="btn btn-primary btn-xs">add </button></h4>
				      '.$dataset['details']['version'].'<br/>
				      '.$dataset['details']['webfsapi'].'
				    </div>
				  </li>';
				  $add_js = '
				  $( "#add'.$row.'" ).click(function() {


				  	  $("#index").val("N");
					  $("#host").val("'.$ip[0].'");
					  $("#friendlyname").val("'.$dataset['details']['friendlyName'].'");
					});

				  ';
			}
		}
	}
	if($add_html==""){
	$add_html = '	<li style="display: none;" id="scanning_devices">
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							searching for remote devices
						  </div>
						</div>
					</li>
					<li>No Devices Found, <button type="button" id="rescan" class="btn btn-primary btn-xs">try again </button> or add it manualy.</li>';
				  $add_js = '
				  $( "#rescan").click(function() {
					$( "#scan li" ).each(function( index ) {
						$( this ).hide();
					});
			  		$("#scanning_devices").show()
				  	xajax_devicescan();
					});

				  ';
	}

	$objResponse->assign("scan","innerHTML", $add_html);
	$objResponse->script($add_js);
	$end = time();
	$objResponse->script("console.log('finnished scan for network devices (".($end-$start) ."s)')");
	return $objResponse;
}


/**
 *	this function is called via xajax. It saves a recovered device into config
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function devicesave($index,$host,$pin,$friendlyname){
	global $radio;
	$objResponse = new xajaxResponse();
	$config = array('host' => $host,'pin' =>$pin, 'friendlyname' => $friendlyname);
	if($index == "N"){
		$response = config_add($config);
	}else{
		$response = config_replace($index,$config);
	}

	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
		return $objResponse;
	}

	$objResponse->script("xajax_devices();");
	return $objResponse;
	

}


/**
 *	this function is called via xajax. It deletes a recovered device from config
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function devicedel($index){
	global $radio;
	$objResponse = new xajaxResponse();
	$response = config_remove($index);
	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
		return $objResponse;
	}
	$objResponse->script("xajax_devices();");
	return $objResponse;
}


/**
 *	this function is called via xajax. It selects and activates a recovered device from config
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function devicesel($index = false){
	global $radio;
	$objResponse = new xajaxResponse();
	$config = config_read();
	if($config[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $config[1]);
		return $objResponse;
	}
	if(count($config[1]) < 1){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", 'No configured device found, please use setup to add new devices.');
		return $objResponse;
	}

	if($index == false){
		$objResponse->script("$('#alert-info').show();");
		$objResponse->script("setTimeout(\"$('#alert-info').hide()\", 5000);");
		$objResponse->assign("alert-info","innerHTML", 'No device selected, using first device in config as default.');
		
	}
	
	foreach($config[1] as $k => $v){
		if($index == $k){
			$config[1][$k]['active'] = true;
		}else{
			unset($config[1][$k]['active']);
		}
	}

	$response = config_write($config[1]);

	if($response[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $response[1]);
		return $objResponse;
	}
	
	$objResponse->script("$('#devicesel').val(".$index.");");
	$objResponse->script("xajax_refresh();");
	return $objResponse;
}


/**
 *	this function is called via xajax. It outputs all saved devices from config
 *	
 *	@return xajaxResponse Object to manipulate the dom
 *
 */
function devices(){
	$start = time();
	global $radio;
	$configs = config_read();

	if($configs[0] == false){
		$objResponse->script("$('#alert-danger').show();");
		$objResponse->script("setTimeout(\"$('#alert-danger').hide()\", 5000);");
		$objResponse->assign("alert-danger","innerHTML", $configs[1]);
		return $objResponse;
	}


	$objResponse = new xajaxResponse();
	$add_html = '	<li style="display: none;" id="scanning_local">
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							loading known devices
						  </div>
						</div>
					</li>';
	$add_js = "";
	foreach($configs[1] as $row => $config){
		preg_match('([0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})', $config['host'], $ip);
		  $add_html .= '<li class="media">
		    <div class="media-left media-middle">
		      <a href="#">
		        <img class="media-object" src="img/devices/unknown.jpg" alt="device image">
		      </a>
		    </div>
		    <div class="media-body">
		      <h4 class="media-heading">'.$config['friendlyname'].' 
		      <button type="button" id="add'.$row.'" class="btn btn-primary btn-xs">edit</button>
		      <button type="button" id="del'.$row.'" class="btn btn-primary btn-xs">del</button>
		      </h4>
		      '.$config['host'].'
		    </div>
		  </li>';
		  $add_js .= '
		  $( "#add'.$row.'" ).click(function() {
		  	  $("#index").val("'.$row.'");
			  $("#host").val("'.$ip[0].'");
			  $("#pin").val("'.$config['pin'].'");
			  $("#friendlyname").val("'.$config['friendlyname'].'");
			});

		  $( "#del'.$row.'" ).click(function() {
		  		xajax_devicedel("'.$row.'");
			});

		  ';
	}
	$objResponse->assign("devices","innerHTML", $add_html);
	$objResponse->script($add_js);
	$end = time();
	$objResponse->script("console.log('finnished loading known devices (".($end-$start) ."s)')");
	return $objResponse;
}


/**
 *	this function initiates the main config file
 *	
 *	@return string filename for configfile
 *
 */
function config_init(){
	$file = 'config.txt';
	if(!file_exists($file )){
		file_put_contents($file,serialize(array()));
	}
	return array(true,$file);
}


/**
 *	this function reads the main config file
 *	
 *	@return array config
 *
 */
function config_read($file = null){
	if($file == null){
		$file = config_init();
		if($file[0] == true){
			$file = $file[1];
		}else{
			return $file;
		}
	}
	$config = file_get_contents($file);
	if($config === false){
		return array(false,'could not read file '.$file);

	}
	
	if($config != "" && $config != serialize(false)){
		$config = unserialize($config);
		if($config === false){
			return array(false,'could not unserialize string');
		}
	}else{
		$config = array();
	}
	return array(true,$config);
}



/**
 *	this function reads the main config file
 *
 *  @var string $config - the new config
 *
 *  @var string $file - name of the configfile
 *	
 *	@return array with success-state
 *
 */
function config_write($config,$file = null){
	if($file == null){
		$file = config_init();
		if($file[0] == true){
			$file = $file[1];
		}else{
			return $file;
		}
	}
	$res = file_put_contents($file,serialize($config));
	if($res === false){
		return array(false,'could not save file'.$file);
	}
	return array(true);
}



/**
 *	this function adds a config line
 *
 *  @var string $config - the new config
 *
 *  @var string $file - name of the configfile
 *	
 *	@return array with success-state
 *
 */
function config_add($config,$file=null){
	if($file == null){
		$file = config_init();
		if($file[0] == true){
			$file = $file[1];
		}else{
			return $file;
		}
	}
	$configs = config_read();
	if($configs[0] == false){
		return $configs;
	}
	$configs[1][] = $config;
	return config_write($configs[1]);
}


/**
 *	this function deletes a config line
 *
 *  @var string $index - index of the configline
 *
 *  @var string $file - name of the configfile
 *	
 *	@return array with success-state
 *
 */
function config_remove($index,$file=null){
	if($file == null){
		$file = config_init();
		if($file[0] == true){
			$file = $file[1];
		}else{
			return $file;
		}
	}
	$configs = config_read();
	if($configs[0] == false){
		return $configs;
	}
	if(isset($configs[1][$index])){
		unset($configs[1][$index]);
	}else{
		return array(false,'index '.$index.' not found');

	}
	return config_write($configs[1]);
}


/**
 *	this function replaces a config line
 *
 *  @var string $index - index of the old configline
 *
 *  @var string $config - the new configline
 *
 *  @var string $file - name of the configfile
 *	
 *	@return array with success-state
 *
 */
function config_replace($index,$config,$file = null){
	if($file == null){
		$file = config_init();
		if($file[0] == true){
			$file = $file[1];
		}else{
			return $file;
		}
	}
	$configs = config_read($file);
	if($configs[0] == false){
		return $configs;
	}
	if(isset($configs[1][$index])){
		$configs[1][$index] = $config;
		return config_write($configs[1]);
	}
	return array(false,'index '.$index.' not found');
}




?>
