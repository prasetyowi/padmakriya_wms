<div class="modal fade" id="modalScan" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-SCANPALLET"></h4>
			</div>
			<div class="modal-body">
				<div id="selectCamera"></div>
				<div id="previewScan"></div>

				<div class="from-group" style="margin-top: 20px;">
					<label name="CAPTION-HASILSCANPALLET"></label>
					<input type="text" class="form-control" id="txtpreviewScan" readonly />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark" onclick="closeScanPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN"></label></button>
			</div>
		</div>
	</div>
</div>