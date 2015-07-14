<?php

/*this file contains all xajax functions*/



/* declare functions as xajax function*/
$xajax->register(XAJAX_FUNCTION,"buttonPress");
$xajax->register(XAJAX_FUNCTION,"ListItemPress");
$xajax->register(XAJAX_FUNCTION,"refresh");

/*including fsapi*/
require_once('fsapi/radio.php');

/* setup credentials*/

$radio = new radio();
$radio->setpin('1337');
$radio->sethost('192.168.0.56');




/**
 *	this function is called via xajax updates values on the website
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
	ob_start();
	$response = $radio->system_status();
	ob_end_clean();
	if($response[0] == 1){
		foreach($response[1] as $key => $value){
			$objResponse->script("update_fields('".str_replace('.','_',$key)."','".$value."')");

		}
	}

	$stats= $response;



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
	$modes = array(-1 => '<a href="#" class="list-group-item disabled">Mode</a>');
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
	$eqs = array(-1 => '<a href="#" class="list-group-item disabled">EQS</a>');


	foreach($response[1] as $key => $value){
					if($stats[1]['netRemote.sys.audio.eqPreset'] == $value['label']){
							$eqs[$key] = '<a id="eqs_'.$key.'" href="#" class="active list-group-item">'.$value['label'].'</a>';
					}else{
							$eqs[$key] = '<a id="eqs_'.$key.'" href="#" class="list-group-item">'.$value['label'].'</a>';
					}
	}
	}

	$objResponse->script("update_fields('netRemote_sys_caps_eqPresets_list','".implode('',$eqs)."')");


	return $objResponse;
}


/**
 * this function is called via xajax- It is fired by onclick of an list-item 
 *
 *	@return xajaxResponse Object to manipulate the dom
 *	
 */


function ListItemPress($id){
	global $radio;
	$objResponse = new xajaxResponse();
	$objResponse->script("console.log('".$id."');");
	list($type,$mode_id) = explode("_",$id);

	switch($type){
		case 'mode':
			$response = $radio->mode($mode_id);
		break;
		case 'eqs':
			$response = $radio->eq_preset($mode_id);
		break;
	}
	$objResponse->script("xajax_refresh();");
	return $objResponse;

}

/**
 *	this function is called via xajax- It is fired by onclick of an button
 *
 *	@var string $id - the id of the html-button. coinstantaneous the id is used to determine what to do
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
			ob_start();
                $response = $radio->volume('up');
			ob_end_clean();
			break;
		case 'volume-down':
					ob_start();
					$response = $radio->volume('down');
					ob_end_clean();
			break;
		case 'volume-mute':
			ob_start();
			$response = $radio->mute('toggle');
			ob_end_clean();
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
			ob_start();
			$response = $radio->power('toggle');
			ob_end_clean();
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
			//
				break;
		case 'play-fast-backward':
			//
				break;
		case 'play-backward':
			//
				break;
		case 'play-start':
			$response = $radio->control('play');
			//
				break;
		case 'play-pause':
			$response = $radio->control('pause');
				print_r($response);
			break;
		case 'play-stop':
			$response = $radio->control('stop');
	
			//
				break;
		case 'play-forward':
			//
				break;
		case 'play-fast-forward':
			//
				break;
		case 'play-step-forward':
			//
				break;
		case 'play-random':
			//
				break;
		case 'play-repeat':
			//
				break;
	}
	// Tell the browser it hast to refresh all values after this request
	$objResponse->script("xajax_refresh();");
	return $objResponse;
}
?>
