		<div class="container">
			<div class="row">
			  <div class="col-md-4">
			  <div class="well">
				<h2>search device</h2>
				<ul class="media-list" id="scan">
					<li>
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							searching for devices
						  </div>
						</div>
					</li>
				</ul>
				<script type="text/javascript">
				$( document ).ready(function() {
					xajax_devicescan();
					});
				</script>
				</div>
			  </div>
			  <div class="col-md-4">
			  <div class="well">
			  <h2>add device</h2>
				<form>
				  <div class="form-group">
				    <label for="host">IP / Hostname</label>
				    <input type="text" class="form-control" name="host" id="host" placeholder="192.168.0.1">
				  </div>
				  <div class="form-group">
				    <label for="pin">Pin</label>
				    <input type="text" class="form-control" name="pin" id="pin" placeholder="1234">
				  </div>
				  <button type="submit" class="btn btn-default">save</button>
				</form>
				</div>
			  </div>
			  <div class="col-md-4">
			  <div class="well">
			  	<h2>edit device</h2>
				<ul class="media-list">
				  <li class="media">
				    <div class="media-left media-middle">
				      <a href="#">
				        <img class="media-object" src="img/index.svg" alt="device image">
				      </a>
				    </div>
				    <div class="media-body">
				      <h4 class="media-heading">Device 2 <button type="button" id="edit1" class="btn btn-primary btn-xs"></span> edit</button></h4>
				      Device ID<br/>
				      API URL
				    </div>
				  </li>
				</ul>
				<script type="text/javascript">
				$( document ).ready(function() {
					$( "#edit1" ).click(function() {
					  $("#host").val('192.168.0.100');
					  $("#pin").val('0815');
					});
					});
				</script>
				</div>
			  </div>
			</div>
		</div>