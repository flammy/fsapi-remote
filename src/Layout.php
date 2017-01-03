<?php
Class Layout{
	
	
	/**
	 *	toggles the button classes based on the current value
	 *
	 *  @var string $index - id of the button
	 *  
	 *	@var string $value - current value of the node
	 *
	 *	@var xajaxResponse object - to manipulate the dom
	 *
	 */
	public function xajaxToggleButton($id,$value,&$xajaxResponse){
		if($value == 'on'){
			$xajaxResponse->script("$('#".$id."').removeClass( 'btn-default' )");
			$xajaxResponse->script("$('#".$id."').addClass( 'btn-danger' )");
		}else{
			$xajaxResponse->script("$('#".$id."').removeClass( 'btn-danger' )");
			$xajaxResponse->script("$('#".$id."').addClass( 'btn-default' )");
		}
	}
	
	
	/**
	 *	shows an alert-message
	 *
	 *  @var string $type - type of the alert (danger,warning,info,success)
	 *  
	 *	@var string $message - message to display in the alert
	 *
	 *	@var xajaxResponse $xajaxResponse - to manipulate the dom
	 *
	 *	@var int $timeout - timeout to hide the alert after microseconds
	 *
	 */
	public function xajaxShowAlert($type,$message,&$xajaxResponse,$timeout = NULL){
			$xajaxResponse->script("$('#alert-".$type."').show();");
			if($timeout != FALSE){
				if($timeout == NULL){
					$timeout = 5000;
				}
				$xajaxResponse->script("setTimeout(\"$('#alert-".$type."').hide()\", ".$timeout.");");
			}
			
			$xajaxResponse->assign("alert-".$type."","innerHTML", $message);
	}
	
	/**
	 *	generates a single line fir the generateList function
	 *  
	 *	@var string $id - The unique id of the list
	 *
	 *	@var string $key - the id of the current row
	 *
	 *	@var string $value - the name of the current entry
	 *
	 *	@var array $class - an array of class-names to add to the current entry
	 *
	 *	@var string $glyphicon an string to add an icon the the entry
	 *	
	 *	@return string if the html representation of the current list-entry
	 */
	public function generateListEntry($id,$key,$value,$class = array(),$glyphicon = ''){
		return  '<a id="'.$id.'_'.$key.'" href="#" class="'.implode(" ",$class).' list-group-item">'.$glyphicon.'&nbsp;'.$value.'</a>';
	}
	
	
	
	
	/**
	 *	generates a list 
	 *
	 *  @var string $name - The title of the list
	 *  
	 *	@var string $id - The unique id of the list
	 *
	 *	@var array $list - An Array with the list-entries
	 *
	 *	@var array $config - an configuration array (tree = true for tree folder view, active = true to highlight the active entry)
	 *
	 *	@return array with the html representation of the list
	 */
	public function generateList($name,$id,$list,$config = array()){
		$result = array();
		$result_top = array();
		$result_top[-2] = $this->generateListEntry($id,-2,$name,array('disabled' => 'disabled'));

		foreach($list as $key => $value){
			$entry_name = '-';
			if(isset($value['name'])){
				$entry_name = $value['name'];
			}
			if(isset($value['label'])){
				$entry_name = $value['label'];
			}
			if(trim($entry_name) == ""){
				continue;
			}
	
			
			
			$class = array();
			$glyphicon = "";
			if(isset($config['tree'])){
				$glyphicon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
				$class['disabled'] = 'disabled';
				if(isset($value['type']) && $value['type'] == 0){
					$glyphicon = '<span class="glyphicon glyphicon-folder-close" aria-hidden="true">';
					unset($class['disabled']);
				}
				if(isset($value['type']) && $value['type'] == 1){
					$glyphicon = '<span class="glyphicon glyphicon-music" aria-hidden="true">';
					unset($class['disabled']);
				}
			}
			if(isset($value['selectable']) && $value['selectable'] == 0){
				$class['disabled'] = 'disabled';
			}
			if(isset($config['active']) && ($key == $config['active'])){
				$class['active'] = 'active';
			}
			
			
			
			$result[$key] =  $this->generateListEntry($id,$key,$entry_name,$class,$glyphicon);
		}
	
		
		if(count($result) < 1){
			$result[0] = $this->generateListEntry($id,'null','List is empty',array('disabled' => 'disabled'));
		}else{
			if(isset($config['tree'])){
				$result_top[-1] = $this->generateListEntry($id,-1,"..",array(),'<span class="glyphicon glyphicon-folder-open" aria-hidden="true">');
			}
		}
		return array_merge($result_top, $result);
	}
	
	
	
	/**
	 *	generates a list of the discovered devices
	 *
	 *  @var array $response - The list of the discovered devices
	 *
	 *  @var xajaxResponse object - to manipulate the dom
	 */
	public function xajaxGenerateDiscoveredDeviceList($response,&$xajaxResponse){
		foreach($response as $row => $dataset){
			if(isset($dataset['location'])){
				if(preg_match('([0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})', $dataset['details']['webfsapi'], $ip) == 1){
					$device_list_html[] ='
					  <li class="media">
						<div class="media-left media-middle">
						  <a href="#">
							<img class="media-object" src="img/devices/unknown.jpg" alt="device image">
						  </a>
						</div>
						<div class="media-body">
						  <h4 class="media-heading">'.$dataset['details']['friendlyName'].' <button type="button" id="add_discovered'.$row.'" class="btn btn-primary btn-xs">add </button></h4>
						  '.$dataset['details']['version'].'<br/>
						  '.$dataset['details']['webfsapi'].'
						</div>
					  </li>';
					  $device_list_js[] = '
					  $( "#add_discovered'.$row.'" ).click(function() {
						  $("#index").val("N");
						  $("#host").val("'.$ip[0].'");
						  $("#friendlyname").val("'.$dataset['details']['friendlyName'].'");
						});
					  ';
				}
			}
		}
		if(count($device_list_html) < 1){
			$device_list_html[] = '
					<li style="display: none;" id="scanning_devices">
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							searching for remote devices
						  </div>
						</div>
					</li>
					<li>No Devices Found, <button type="button" id="rescan" class="btn btn-primary btn-xs">try again </button> or add it manualy.</li>';
				  $device_list_js[] = '
				  $( "#rescan").click(function() {
					$( "#scan li" ).each(function( index ) {
						$( this ).hide();
					});
			  		$("#scanning_devices").show()
				  	xajax_devicescan();
					});
				  ';
		}
		
		
		
		$xajaxResponse->assign("scan","innerHTML", implode('',$device_list_html));
		$xajaxResponse->script(implode($device_list_js));

		
	}
	
	
	/**
	 *	generates a list of the allready stored devices
	 *
	 *  @var array $response - The list of the known devices
	 *
	 *  @var xajaxResponse object - to manipulate the dom
	 */
	public function xajaxGenerateKnownDeviceList($response,&$xajaxResponse){
		$device_list_html = array();
		$device_list_js = array();
		$device_list_html[] = '
						<li style="display: none;" id="scanning_local">
							<div class="progress">
							  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								loading known devices
							  </div>
							</div>
						</li>';
		foreach($response as $row => $config){
			preg_match('([0-9]{1,3}\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3})', $config['host'], $ip);
			  $device_list_html[] = '
			  <li class="media">
				<div class="media-left media-middle">
				  <a href="#">
					<img class="media-object" src="img/devices/unknown.jpg" alt="device image">
				  </a>
				</div>
				<div class="media-body">
				  <h4 class="media-heading">'.$config['friendlyname'].' 
				  <button type="button" id="add_known'.$row.'" class="btn btn-primary btn-xs">edit</button>
				  <button type="button" id="del'.$row.'" class="btn btn-primary btn-xs">del</button>
				  </h4>
				  '.$config['host'].'
				</div>
			  </li>';
			  $device_list_js[] = '
			  $( "#add_known'.$row.'" ).click(function() {
				  $("#index").val("'.$row.'");
				  $("#host").val("'.$ip[0].'");
				  $("#pin").val("'.$config['pin'].'");
				  $("#friendlyname").val("'.$config['friendlyname'].'");
				});
	
			  $( "#del'.$row.'" ).click(function() {
					if($("#index").val() == "'.$row.'"){
						$("#index").val("N");
					}
					xajax_devicedel("'.$row.'");
					
				});
	
			  ';
		}
		$xajaxResponse->assign("devices","innerHTML", implode('',$device_list_html));
		$xajaxResponse->script(implode($device_list_js));
	}
	
	
}


?>