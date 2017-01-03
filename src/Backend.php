<?php
class Backend{
	protected $xajax;
	protected $radio;
	protected $Config;
	protected $layout;
	protected $activeDevice;
	
	
	
	/**
	 *	constructs the Backend Object
	 *
	 *	@var Xajax Object  - The Xajax Object to manipulate the dom via ajax
	 *
	 *	@var Config Object - Object to interact with the local configuration
	 *
	 */
	public function __construct($Xajax,&$Config){
		$this->xajax 	= $Xajax;
		$this->xajaxResponse = new xajaxResponse;
		$this->settings 	= $Config->read();
		$this->Config   = $Config;
		$this->layout 	= new Layout;
		try{
			$this->radio 	= $this->getActiveRadio($this->settings);
		}catch(Exception $e){
			$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
		}
		
		
		$this->xajax->register(XAJAX_FUNCTION,array($this, "devicesel"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "devicedel"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "devicesave"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "buttonPress"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "ListItemPress"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "refresh"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "devices"));
		$this->xajax->register(XAJAX_FUNCTION,array($this, "devicescan"));
	}
	
	
	
	/**
	 *	returns the radio object of the active radio read from config
	 *
	 *	@var array - Configuration Array
	 *	
	 *	@return Radio Object
	 *
	 */
	public function getActiveRadio($config){
		$act_device  = NULL;
		$i = 0;
		foreach($config  as $key => $value){
			if($i == 0){
				// Select first device as default
				$act_device = $key;
			}elseif(isset($value['active']) && ($value['active'] == true)) {
				// select active device if is set
				$act_device = $key;
			}
			$i ++;
		}
		if($act_device === NULL){
			$this->layout->xajaxShowAlert('warning','No configured device found, please use setup to add new devices.',$this->xajaxResponse);
			return false;
		}
		$this->activeDevice = $act_device;
		return new Radio($config[$act_device]['host'],$config[$act_device]['pin']);
	}
	
	
	
	/**
	 *	returns the index of the active radio
	 *	
	 *	@return int index of the active radio
	 *
	 */
	public function getActiveDevice(){
		return $this->activeDevice;
	}
	
	
	
	
	/**
	 *	this function is called via xajax. It deletes a recovered device from config
	 *	
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	function devicedel($index){
		try{
			$this->Config->remove($index);
		}catch(Exception $e){
			$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
		}
		$this->xajaxResponse->script("xajax_devices();");
		return $this->xajaxResponse;
	}
	
	
	/**
	 *	this function is called via xajax. It saves a recovered device into config
	 *	@var mixed $index index of the config-entry or N if it is a new value
	 *	@var string $host ip-adress or hostname of the device
	 *	@var int $pin pin of the device
	 *	@var string $friendlyname name of the device
	 *
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	function devicesave($index,$host,$pin,$friendlyname){
		$error = 0;
		$config = array('host' => $host,'pin' =>$pin, 'friendlyname' => $friendlyname);
		if($index == "N"){
			// new Device
			try{
				$this->Config->add($config);
			}catch(Exception $e){
				$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				$error++;
			}
		}else{
			// existing device
			try{
				$this->Config->replace($index,$config);
			}catch(Exception $e){
				$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				$error++;
			}
		}
		if($error == 0){
			$this->xajaxResponse->script("\$('form#entries').trigger('reset');");
		}
		$this->xajaxResponse->script("xajax_devices();");
		return $this->xajaxResponse;
	}
	
	
	
	/**
	 *	this function is called via xajax. It selects and activates a recovered device from config
	 *
	 *	@var int $index index of the device which should be activated
	 *	
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	function devicesel($index = false){
		if(count($this->settings) < 1){
			$this->layout->xajaxShowAlert('warning','No configured device found, please use setup to add new devices.',$this->xajaxResponse);
		}
		try{
			$this->Config->activate($index);
		}catch(Exception $e){
			$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
		}
		
		$this->xajaxResponse->script("$('#devicesel').val(".$index.");");
		$this->xajaxResponse->script("xajax_refresh();");
		return $this->xajaxResponse;
	}
	
	
	/**
	 *	this function is called via xajax. It is fired by the onclick-event of an button
	 *
	 *	@var string $id - the id of the html-button. Coinstantaneous the id is used to determine what to do
	 *	
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	public function buttonPress($id){
		$this->xajaxResponse->script("console.log('".$id."');");
		switch($id){
			case 'volume-up':
				try{
					$response = $this->radio->volume('up');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
			case 'volume-down':
				try{
					$response = $this->radio->volume('down');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
			case 'volume-mute':
				try{
					$response = $this->radio->mute('toggle');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$this->layout->xajaxToggleButton($id,$response,$this->xajaxResponse);
				break;
			case 'power':
				try{
					$response = $this->radio->power('toggle');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$this->layout->xajaxToggleButton($id,$response,$this->xajaxResponse);
				break;
			case 'play-step-backward':
				$response = $this->radio->control('previous');
				break;
			case 'play-start':
				try{
					$response = $this->radio->control('play');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				
				break;
			case 'play-pause':
				try{
					$response = $this->radio->control('pause');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
			case 'play-stop':
				try{
					$response = $this->radio->control('stop');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
			case 'play-step-forward':
					$response = $this->radio->control('next');
					break;
			case 'play-random':
				try{
					$response = $this->radio->shuffle('toggle');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
			case 'play-repeat':
				try{
					$response = $this->radio->repeat('toggle');
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				break;
		}
		$this->refresh(array('button' => $id));
		return $this->xajaxResponse;
	}
	
	
	/**
	 *	this function is called via xajax. It updates all values on the frontend
	 *
	 *	@var array $keys - array containing the information what is pressed an what have to be updated
	 *
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	public function refresh($keys = array()){
		// unserialize if called via ajax
		if(!is_array($keys) && $keys != ""){
			$keys = unserialize($keys);
		}

		// get all known values available via get
		$stats = array();
		$path = false;
		if(count($keys) < 1){
			$path = null;
		}
		// determine what to refresh if buttons where pressed
		if(isset($keys['button'])){
			switch($keys['button']){
				case 'power':
					// the power button has to refresh all values
					$path = null;
				break;
				case 'volume-down':
				case 'volume-up':
				case 'volume-mute':	
					// the power button has to refresh all values
					$path = 'netRemote.sys.audio';
				break;
			
			
				default:
					// other buttons affects only the play section
					$path = 'netRemote.play';
				break;
				
			}
		}
		if((isset($keys['list'])) && (in_array('mode',$keys['list']) || in_array('favs',$keys['list']) || in_array('navs',$keys['list']))){
			// mode, facs and navs affect the play section 
			$path = 'netRemote.play';
		}
		
		if((isset($keys['list'])) && (in_array('eqs',$keys['list']))){
			// eqs affects the audio section
			$path = 'netRemote.sys.audio';
		}
		if($path !== FALSE){
			try{
				$stats = $this->radio->systemStatus($path);
			} catch (Exception $e) {
				$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				return $this->xajaxResponse;
			}
			// The resultset is empty
			if(count($stats) < 1){
				$this->layout->xajaxShowAlert('danger','Got an empty resultset from fsapi.',$this->xajaxResponse);
				return $this->xajaxResponse;
			}
		}

	
		// define meta status for toggeling multiple buttons with one value
		if(isset($stats['netRemote.play.status'])){
			if($stats['netRemote.play.status'] == 'playing'){
				 $this->xajaxResponse->script("update_fields('netRemote_play_status_play','on')");
			}elseif($stats['netRemote.play.status'] == 'stopped'){
				 $this->xajaxResponse->script("update_fields('netRemote_play_status_stop','on')");
			}elseif($stats['netRemote.play.status'] == 'paused'){
				 $this->xajaxResponse->script("update_fields('netRemote_play_status_pause','on')");
			}
		}
		
		// get the list of available modes
		if(count($keys) < 1 || (isset($keys['list']) && in_array('mode',$keys['list']))){
			$response = array();
			try{
				$response = $this->refresh_list('mode',$stats);
			}catch(Exception $e){
				$this->layout->xajaxShowAlert('danger',"mode ".$e->getMessage(),$this->xajaxResponse);
			}
			$generated_list = $this->layout->generateList('Mode','mode',$response,array('active' => $stats['netRemote.sys.mode']));
			$this->xajaxResponse->script("update_fields('netRemote_sys_mode_list','".implode('',$generated_list)."')");
		}
		
		// get the list of available eqs-presets
		if(count($keys) < 1 || (isset($keys['list']) && in_array('eqs',$keys['list']))){
			$response = array();
			try{
				$response = $this->refresh_list('eqs',$stats);
			}catch(Exception $e){
				$this->layout->xajaxShowAlert('danger',"eqs: ".$e->getMessage(),$this->xajaxResponse);
			}
			$generated_list = $this->layout->generateList('EQS','eqs',$response,array('active' => $stats['netRemote.sys.audio.eqPreset']));
			$this->xajaxResponse->script("update_fields('netRemote_sys_caps_eqPresets_list','".implode('',$generated_list)."')");
		}
		
		
		//  get the list of available favorite stations for the current mode
		if(count($keys) < 1 || (isset($keys['list']) && in_array('favs',$keys['list']))){
			$response = array();
			try{
				$response = $this->refresh_list('favs',$stats);
			}catch(Exception $e){
				//$this->layout->xajaxShowAlert('danger',"favs: ".$e->getMessage(),$this->xajaxResponse);
			}
			$generated_list = $this->layout->generateList('Favorites','favs',$response,array('active' => $stats['netRemote.play.info.name']));
			$this->xajaxResponse->script("update_fields('netRemote_nav_presets_list','".implode('',$generated_list)."')");
		}
		
		// activate navigation
		if(count($keys) < 1 || (isset($keys['list']) && in_array('navs',$keys['list']))){
			$response = array();
			try{
				$response = $this->refresh_list('navs',$stats);
			}catch(Exception $e){
				//$this->layout->xajaxShowAlert('danger',"navs: ".$e->getMessage(),$this->xajaxResponse);
			}
			
			$generated_list = $this->layout->generateList('Menu','navs',$response,array('tree' => true));
			$this->xajaxResponse->script("update_fields('netRemote_nav_list','".implode('',$generated_list)."')");
		}
		
		
		// update collected fields
		foreach($stats as $key => $value){
			$this->xajaxResponse->script("update_fields('".str_replace('.','_',$key)."','".str_replace("'","\'",$value)."')");
		}
		$this->xajaxResponse->script("setTimeout(function() {xajax_refresh('".serialize(array('button' => 'timer'))."');}, 5000);");
		return $this->xajaxResponse;
	}
	
	/**
	 * this function is called via xajax. It is fired by the onclick-event of an list-item 
	 *
	 * 	@var string $id - the id of the list-item. Coinstantaneous the id is used to determine what to do
	 *
	 *	@return xajaxResponse Object to manipulate the dom
	 *	
	 */
	public function ListItemPress($id){
		list($type,$mode_id) = explode("_",$id);
		$update = array();
		switch($type){
			case 'mode':
				// Change mode
				try{
					$response = $this->radio->selectMode($mode_id);
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$update = array('mode','favs','navs');
			break;
			case 'eqs':
				// Change eq-preset
				try{
					$response = $this->radio->selectEq($mode_id);
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$update = array('eqs');
			break;
			case 'favs':
				// Select channel from favourites
				try{
					$response = $this->radio->selectFav($mode_id);
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$update = array('favs');
			break;
			case 'navs':
				// Select an navigation item
				try{
					$response = $this->radio->selectNav($mode_id);
				}catch(Exception $e){
					$this->layout->xajaxShowAlert('danger',$e->getMessage(),$this->xajaxResponse);
				}
				$update = array('navs');
			break;
		}

		// Tell the browser it should fire xajax_refresh to get all new values
		$this->refresh(array('list' => $update));
		return $this->xajaxResponse;
	}
	
		
	/**
	 *	this function is called via xajax. It outputs all saved devices from config
	 *	
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	public function devices(){
		$this->layout->xajaxGenerateKnownDeviceList($this->settings,$this->xajaxResponse);
		return $this->xajaxResponse;
	}
	
	/**
	 *	this function is called via xajax. It does a device scan via ssdp
	 *	
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	public function devicescan(){
		$Scanner = new Scanner();
		$ssdp = new SSDP($Scanner);
		$response = $ssdp->doScan('urn:schemas-frontier-silicon-com:fs_reference:fsapi:1');
		$this->layout->xajaxGenerateDiscoveredDeviceList($response,$this->xajaxResponse);
		return $this->xajaxResponse;

	}
	
	/**
	 *	this function is called via xajax. It updates a defined list
	 *
	 *	@param string list - the name of the list to update
	 *
	 *	@param array stats - the stats array with the collected values from the radio
	 *
	 *	@throws BackendException
	 *
	 *	@return xajaxResponse Object to manipulate the dom
	 *
	 */
	function refresh_list($list,&$stats){
		$error = "";
		$response = array();
		switch($list){
			case 'favs':
				// unblock navigation node
				try{
					$response = $this->radio->GetSet('netRemote.nav.state',1);
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
				// sleep 1 second until node gets unblocked
				sleep(1);
				try{
					$response = $this->radio->GetSetList('netRemote.nav.presets');
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
				if(!isset($stats['netRemote.play.info.name'])){
					try{
						$stats['netRemote.play.info.name'] = $this->radio->GetSet('netRemote.play.info.name');
					} catch (Exception $e) {
						$error .= $e->getMessage();
					}
				}
			break;
			case 'eqs':
				try{
					$response = $this->radio->GetSetList('netRemote.sys.caps.eqPresets');
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
				if(!isset($stats['netRemote.sys.audio.eqPreset'])){
					try{
						$stats['netRemote.sys.audio.eqPreset'] = $this->radio->GetSet('netRemote.sys.audio.eqPreset');
					} catch (Exception $e) {
						$error .= $e->getMessage();
					}
				}
			break;
			case 'mode':
				try{
					$response = $this->radio->GetSetList('netRemote.sys.caps.validModes');
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
				if(!isset($stats['netRemote.sys.mode'])){
					try{
						$stats['netRemote.sys.mode'] = $this->radio->GetSet('netRemote.sys.mode');
					} catch (Exception $e) {
						$error .= $e->getMessage();
					}
				}
			break;
			case 'navs':
				// unblock navigation node
				try{
					$response = $this->radio->GetSet('netRemote.nav.state',1);
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
				// sleep 1 second until node gets unblocked
				sleep(1);
				//  get the list of available navigation items
				try{
					$response = $this->radio->GetSetList('netRemote.nav.list');
				} catch (Exception $e) {
					$error .= $e->getMessage();
				}
			break;
			default:
				throw new BackendException(sprintf("List %s not found",$list));
		}
		if($error != ""){
			throw new BackendException(sprintf($error));
		}
		return $response;
	}
	
	
	
}