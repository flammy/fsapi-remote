		<!-- start content/setup.php -->
		<div class="container">
			<div class="row">
			  <div class="col-md-4">
			  <div class="well">
				<h2>search device</h2>
				<ul class="media-list" id="scan">
					<li>
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							searching for remote devices
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
				<input type="hidden" class="form-control" autocomplete="off" value="N" name="index" id="index">
				  <div class="form-group">
				    <label for="host">IP / Hostname</label>
				    <input type="text" class="form-control" name="host" id="host" autocomplete="off" required="required" placeholder="192.168.0.1">
				  </div>
				  <div class="form-group">
				    <label for="pin">Pin</label>
				    <input type="text" class="form-control" name="pin" id="pin" autocomplete="off" required="required" placeholder="1234">
				  </div>
				  <div class="form-group">
				    <label for="friendlyname">Name</label>
				    <input type="text" class="form-control" name="friendlyname" id="friendlyname" autocomplete="off" placeholder="Name">
				  </div>
				  <button type="submit" id="save" class="btn btn-default">save</button>
				</form>
				<script type="text/javascript">
				$( document ).ready(function() {
					$( "#save" ).click(function() {
						if($("form")[0].checkValidity()) {
							xajax_devicesave($("#index").val(),$("#host").val(),$("#pin").val(),$("#friendlyname").val());
							return false;
						}
					});
					});
				</script>
				</div>
			  </div>
			  <div class="col-md-4">
			  <div class="well">
			  	<h2>edit device</h2>
				<ul class="media-list" id="devices">
					<li>
						<div class="progress">
						  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							loading local devices
						  </div>
						</div>
					</li>

				</ul>
				<script type="text/javascript">
				$( document ).ready(function() {
					xajax_devices();
					});
				</script>
				</div>
			  </div>
			</div>
		</div>
		<!-- end content/setup.php -->
