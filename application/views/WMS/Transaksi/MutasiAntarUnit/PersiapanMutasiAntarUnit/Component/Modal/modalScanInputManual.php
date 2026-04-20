<div class="modal fade" id="modalInputManual" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKPALLET"></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="label-input">
						<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>
					</div>
					<input type="text" class="form-control kodePallet" autocomplete="off" id="kodePallet" placeholder="/PALLET/NoKode" onkeyup="handlerGetKodePallet(event, this.value)">
					<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
						<table class="table table-striped table-sm table-hover" id="table-fixed" width="100%">
							<tbody id="konten-table"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark" onclick="closeInputPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE"></label></button>
				</div>
			</div>
		</div>
	</div>