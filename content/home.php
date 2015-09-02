
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
		      <a class="navbar-brand" href="#">FSAPI-Remote</a>
		    </div>
			<!-- end navbar-header -->
			<!-- start navbar-collapse -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <!-- start left navbar -->
			  <ul class="nav navbar-nav navbar-left">
		        <li>
					<button type="button" id="volume-down" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-volume-down" aria-label="Left Align" aria-hidden="true"></span></button>
					<input type="text"  id="volume"  class="slider netRemote_sys_audio_volume" value="1" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="1" data-slider-orientation="horizontal" data-slider-selection="after" data-slider-tooltip="hide">
					<button type="button" id="volume-up" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-volume-up" aria-label="Left Align" aria-hidden="true"></span></button>
					<button type="button" id="volume-mute" class="btn btn-sm btn-default netRemote_sys_audio_mute"><span class="glyphicon glyphicon-volume-off" aria-label="Left Align" aria-hidden="true"></span></button>
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
		<!-- start player-->
		<div class="container">
			<div class="row">
			  <div class="col-md-12">
				<div class="media">
				  <div class="media-left media-middle">
				    <a href="#">
				      <img class="media-object netRemote_play_info_graphicUri" src="logo.png" alt="wdr2">
				    </a>
				  </div>
				  <div class="media-body">
				    <h4 class="txt netRemote_play_info_name media-heading">-</h4>
				    <h5 class="txt netRemote_play_info_text">-</h5>
				  </div>
			  	</div>
			  </div>
			</div>
			<div class="row">
			  <div class="col-md-12">
					<button type="button" id="play-step-backwar" class="disabled btn btn-default"><span class="glyphicon glyphicon-step-backward" aria-label="Left Align" aria-hidden="true"></span></button>

					<button type="button" id="play-start" class="disabled btn btn-default netRemote_play_status_play"><span class="glyphicon glyphicon-play" aria-label="Left Align" aria-hidden="true"></span></button>

					<button type="button" id="play-pause" class="disabled btn btn-default"><span class="glyphicon glyphicon-pause netRemote_play_status_pause" aria-label="Left Align" aria-hidden="true"></span></button>

					<button type="button" id="play-stop" class="disabled btn btn-default"><span class="glyphicon glyphicon-stop netRemote_play_status_stop" aria-label="Left Align" aria-hidden="true"></span></button>

					<button type="button" id="play-step-forward" class="disabled btn btn-default"><span class="glyphicon glyphicon-step-forward" aria-label="Left Align" aria-hidden="true"></span></button>

					<button type="button" id="play-random" class="disabled btn btn-default netRemote_play_shuffle"><span class="glyphicon glyphicon-random" aria-label="Shuffle / Random" aria-hidden="true"></span></button>
					<button type="button" id="play-repeat" class="disabled btn btn-default netRemote_play_repeat"><span class="glyphicon glyphicon-retweet" aria-label="Repeat" aria-hidden="true"></span></button>
			  </div>
			</div>
		</div>
		<!-- end player-->	
		<br/>
		<!-- start select-lists -->
		<div class="container">
			<div class="row">
			  <div class="col-md-4">
					<div class="list-group netRemote_sys_mode_list">
					  <a href="#" class="list-group-item disabled">
					    Loading modes 
					  </a>
					</div>
			  </div>
			  <div class="col-md-4">
					<div class="list-group netRemote_nav_presets_list">
					  <a href="#" class="list-group-item disabled">
					    Loading Favorites
					  </a>
					</div>
			  </div>
			  <div class="col-md-4">
					<div class="list-group netRemote_nav_list">
					  <a href="#" class="list-group-item disabled">
					    Loading Channels
					  </a>
					</div>
			  </div>
			</div>
		<br/>
			<div class="row">
			  <div class="col-md-4">
					<div class="list-group netRemote_sys_caps_eqPresets_list">
					  <a href="#" class="list-group-item disabled">
					    Loading EQS
					  </a>
					</div>
			  </div>
			  <div class="col-md-4">
				<div class="panel panel-default">
				  <div class="panel-heading">Custom EQS</div>
				  <div class="panel-body">
						<input type="text" id="sl1" class="slider netRemote_sys_audio_eqCustom_param0" value="" data-slider-min="-20" data-slider-max="20" data-slider-step="1" data-slider-value="-14" data-slider-orientation="horizontal" data-slider-selection="after"data-slider-tooltip="hide">
						<input type="text"  id="sl2" class="slider netRemote_sys_audio_eqCustom_param1" value="" data-slider-min="-20" data-slider-max="20" data-slider-step="1" data-slider-value="-14" data-slider-orientation="horizontal" data-slider-selection="after"data-slider-tooltip="hide">
				  </div>
				</div>

			  	</div>
			  	<div class="col-md-4">
				</div>
			</div>
		</div>
		<!-- end select-lists -->
		<br/>
		<!-- start parameter lists -->
		<div class="container">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		  
		  <!-- start system-panel -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingOne">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		          System
		        </a>
		      </h4>
		    </div>
		    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
					<table class="table table-striped">
					  <tr>
					  	<td>
					  		netRemote.sys.info.version
					  	</td>
					  	<td class="txt netRemote_sys_info_version">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.info.radioId
					  	</td>
					  	<td class="txt netRemote_sys_info_radioId">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.info.friendlyName
					  	</td>
					  	<td class="txt netRemote_sys_info_friendlyName">
					  		
					  	</td>
					  </tr>
					</table>
		      </div>
		    </div>
		  </div>
		  <!-- end system-panel -->
		  <!-- start network-panel -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingTwo">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		          Network
		        </a>
		      </h4>
		    </div>
		    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		      <div class="panel-body">
					<table class="table table-striped">
					  <tr>
					  	<td>
					  		netRemote.sys.net.wired.interfaceEnable
					  	</td>
					  	<td class="txt netRemote_sys_net_wired_interfaceEnable">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wired.macAddress
					  	</td>
					  	<td class="txt netRemote_sys_net_wired_macAddress">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wlan.interfaceEnable
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_interfaceEnable">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wlan.macAddress
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_macAddress">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
							netRemote.sys.net.wlan.connectedSSID
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_connectedSSID">
					  		1
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wlan.setEncType
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_setEncType">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wlan.setAuthType
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_setAuthType">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.wlan.rssi
					  	</td>
					  	<td class="netRemote_sys_net_wlan_rssi">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.dhcp
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_dhcp">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.address
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_address">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
							netRemote.sys.net.wlan.connectedSSID
					  	</td>
					  	<td class="txt netRemote_sys_net_wlan_connectedSSID">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.subnetMask
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_subnetMask">

					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.gateway
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_gateway">

					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.dnsPrimary
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_dnsPrimary">
					  	</td>
					  	<td>
					  		
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.sys.net.ipConfig.dnsSecondary
					  	</td>
					  	<td class="txt netRemote_sys_net_ipConfig_dnsSecondary">
					  		
					  	</td>

					  	<td>
					  		
					  	</td>
					  </tr>
					</table>
		      </div>
		    </div>
		  </div>
		  <!-- end network-panel -->
		  <!-- start play-panel -->
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="headingThree">
		      <h4 class="panel-title">
		        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePlay" aria-expanded="false" aria-controls="collapsePlay">
		          Play
		        </a>
		      </h4>
		    </div>
		    <div id="collapsePlay" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		      <div class="panel-body">
					<table class="table table-striped">
					  <tr>
					  	<td>
					  		netRemote.play.frequency
					  	</td>
					  	<td class="txt netRemote_play_frequency">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.serviceIds.fmRdsPi
					  	</td>
					  	<td class="txt netRemote_play_serviceIds_fmRdsPi">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.scrobble
					  	</td>
					  	<td class="txt netRemote_play_scrobble">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.serviceIds.ecc
					  	</td>
					  	<td class="txt netRemote_play_serviceIds_ecc">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.repeat
					  	</td>
					  	<td class="txt netRemote_play_repeat">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.info.name
					  	</td>
					  	<td class="txt netRemote_play_info_name">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.status
					  	</td>
					  	<td class="txt netRemote_play_status">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.caps
					  	</td>
					  	<td class="txt netRemote_play_caps">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.shuffle
					  	</td>
					  	<td class="txt netRemote_play_shuffle">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.control
					  	</td>
					  	<td class="txt netRemote_play_control">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.info.album
					  	</td>
					  	<td class="txt netRemote_play_info_album">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.info.artist
					  	</td>
					  	<td class="txt netRemote_play_info_artist">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.info.graphicUri
					  	</td>
					  	<td class="txt netRemote_play_info_graphicUri">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.position
					  	</td>
					  	<td class="txt netRemote_play_position">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.info.duration
					  	</td>
					  	<td class="txt netRemote_play_info_duration">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.rate
					  	</td>
					  	<td class="txt netRemote_play_rate">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					  <tr>
					  	<td>
					  		netRemote.play.signalStrength
					  	</td>
					  	<td class="txt netRemote_play_signalStrength">
					  		
					  	</td>
					  	<td>
					  		
					  	</td>
					  </tr>
					</table>
				</div>
			</div>
		</div>
		<!-- end play-panel -->
		<!-- start caps-panel -->
		<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingCaps">
				  <h4 class="panel-title">
					<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseCaps" aria-expanded="false" aria-controls="collapseCaps">
					  Caps
					</a>
				  </h4>
				</div>
				<div id="collapseCaps" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingCaps">
				  <div class="panel-body">
									<table class="table table-striped">
									  <tr>
											<td>
													netRemote.sys.caps.volumeSteps
											</td>
											<td class="txt netRemote_sys_caps_volumeSteps">

											</td>
											<td>

											</td>
									  </tr>
									  <tr>
											<td>
													netRemote.sys.caps.fmFreqRange.lower
											</td>
											<td class="txt netRemote_sys_caps_fmFreqRange_lower">

											</td>
											<td>

											</td>
									  </tr>
									  <tr>
											<td>
													netRemote.sys.caps.fmFreqRange.upper
											</td>
											<td class="txt netRemote_sys_caps_fmFreqRange_upper">

											</td>
											<td>

											</td>
									  </tr>
									  <tr>
											<td>
													netRemote.sys.caps.fmFreqRange.stepSize
											</td>
											<td class="txt netRemote_sys_caps_fmFreqRange_stepSize">

											</td>
											<td>

											</td>
									  </tr>
									  
									</table>
					</div>
				</div>
			  </div>
			<!-- end caps-panel -->
			<!-- start nav-panel -->
			<div class="panel panel-default">
			  <div class="panel-heading" role="tab" id="headingNav">
				<h4 class="panel-title">
				  <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseNav" aria-expanded="false" aria-controls="collapseNav">
					Nav
				  </a>
				</h4>
			  </div>
			  <div id="collapseNav" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNav">
				<div class="panel-body">
								  <table class="table table-striped">
									<tr>
										  <td>
												  netRemote.nav.action.dabScan
										  </td>
										  <td class="txt netRemote_nav_action_dabScan">

										  </td>
										  <td>

										  </td>
									</tr>
									<tr>
										  <td>
												 netRemote.nav.status
										  </td>
										  <td class="txt netRemote_nav_status">

										  </td>
										  <td>

										  </td>
									</tr>
									<tr>
										  <td>
												  netRemote.nav.action.selectItem
										  </td>
										  <td class="txt netRemote_nav_action_selectItem">

										  </td>
										  <td>

										  </td>
									</tr>
									<tr>
										  <td>
												  netRemote.nav.action.navigate
										  </td>
										  <td class="txt netRemote_nav_action_navigate">

										  </td>
										  <td>

										  </td>
									</tr>
									<tr>
										  <td>
												  netRemote.nav.caps
										  </td>
										  <td class="txt netRemote_nav_caps">

										  </td>
										  <td>

										  </td>
									</tr>
									<tr>
										  <td>
												  netRemote.nav.state
										  </td>
										  <td class="txt netRemote_nav_state">

										  </td>
										  <td>

										  </td>
									</tr>
								  </table>
							  </div>
						  </div>
					  </div>
					<!-- end nav-panel -->
					<!-- start audio-panel -->
					<div class="panel panel-default">
					  <div class="panel-heading" role="tab" id="headingThree">
						<h4 class="panel-title">
						  <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
							Audio
						  </a>
						</h4>
					  </div>
					  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
						<div class="panel-body">
							  <table class="table table-striped">
								<tr>
								  <td>
									  netRemote.sys.audio.volume
								  </td>
								  <td class="txt netRemote_sys_audio_volume">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
								<tr>
								  <td>
									  netRemote.sys.audio.mute
								  </td>
								  <td class="txt netRemote_sys_audio_mute">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
								<tr>
								  <td>
									  netRemote.sys.audio.eqPreset
								  </td>
								  <td class="txt netRemote_sys_audio_eqPreset">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
								<tr>
								  <td>
									  netRemote.sys.audio.eqLoudness
								  </td>
								  <td class="txt netRemote_sys_audio_eqLoudness">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
		  
		  
								<tr>
								  <td>
									  netRemote.sys.audio.eqCustom.param0
								  </td>
								  <td class="txt netRemote_sys_audio_eqCustom_param0">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
								<tr>
								  <td>
									  netRemote.sys.audio.eqCustom.param1
								  </td>
								  <td class="txt netRemote_sys_audio_eqCustom_param1">
									  
								  </td>
								  <td>
									  
								  </td>
								</tr>
							  </table>
						</div>
					  </div>
					</div>
					<!-- start audio-panel -->
				</div>
				<!-- end parameter lists -->

			</div>
			<script type="text/javascript">
			/*
			 *	function for updating events on elements in dom
			 *	its important to call this function because new created objects have no bound functions
			 *	and we are heavy manipulating the dom
			 */
			function addEvents(){
				/*
				 * click functions for buttons
				 */
				$('button').each( function(index) {
					$(this).unbind( "click" );
					$(this).click( function() {
						xajax_buttonPress(this.id);
					});
				})
				/*
				 * click functions for list-items
				 */
				$('.list-group-item').each( function(index) {
					$(this).unbind( "click" );
					$(this).click( function() {
						xajax_ListItemPress(this.id,$(this).parent().id);
					});
				})
	
			}
	
			$( document ).ready(function() {
				/*
				 *	Get all values via xajax
				 */
				xajax_refresh();
				/*	not ready yet		
				 *	$('.slider').slider();
				 */
			});
			// new events for new objects
			addEvents();
		</script>