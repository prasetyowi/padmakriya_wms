<div class="modal fade" id="modalScan" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" id="body-modal-scan">
				<div class="wrapper">
					<input type="hidden" id="tempValForScan">
					<input type="hidden" id="SkuIdValForScan">
					<input type="hidden" id="indexValForScan">
					<input type="hidden" id="checkCameraIsRunning" value="0">
					<input type="radio" name="selectSplit" id="option-1" value="0" class="chkTypeScan" onclick="handleTypeScan(event)">
					<input type="radio" name="selectSplit" id="option-2" value="1" class="chkTypeScan" onclick="handleTypeScan(event)">
					<label for="option-1" class="option option-1">
						<div class="dot"></div>
						<span name="CAPTION-SCAN">Scan</span>
					</label>
					<label for="option-2" class="option option-2">
						<div class="dot"></div>
						<span name="CAPTION-MANUALINPUT">Input Manual</span>
					</label>
				</div>

				<div id="select_kamera"></div>
				<div id="preview" style="display: none;"></div>

				<div id="preview_input_manual" style="display: none;margin-top:10px">
					<div class="row">
						<div class="col-md-12 col-xl-12 col-lg-12 col-sm-12 col-xs-12" id="principleSKU">
							<div class="form-group">
								<label for="filterPrincipleSKU" name="CAPTION-PRINCIPLE">Principle</label>
								<select name="filterPrincipleSKU" id="filterPrincipleSKU" onchange="handlerFilterSKU(event)" class="form-control select2">
									<!-- <option value="">--Pilih Principle--</option>
									<?php foreach ($principle as $key => $value) { ?>
										<option class="checkSelected" value="<?= $value->principle_id ?>"><?= $value->principle_nama ?></option>
									<?php } ?> -->
								</select>
							</div>
						</div>
						<div class="col-md-12 col-xl-12 col-lg-12 col-sm-12 col-xs-12">
							<div class="form-group" id="scanWithAutoComplete" style="display: none;">
								<label name="CAPTION-KODEKELAS">Kode</label>
								<input type="text" class="form-control" autocomplete="off" id="kode_barcode_auto">
								<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
									<table class="table table-striped table-sm table-hover" id="table-fixed">
										<tbody id="konten-table"></tbody>
									</table>
								</div>
							</div>
							<div class="form-group" id="scanWithoutAutoComplete" style="display: none;">
								<label name="CAPTION-KODEKELAS">Kode</label>
								<input type="text" class="form-control" id="kode_barcode" onkeypress="return handleChecKodeByEnter(event, this.value)" autocomplete="off">
							</div>
						</div>
						<div class="col-md-12 col-xl-12 col-lg-12 col-sm-12 col-xs-12">
							<button type="button" style="float: right; " class="btn btn-success" id="check_kode" onclick="handleCheckKode()"><i class="fas fa-search"></i> <span name="CAPTION-CHECKKODE">Cek Kode</span></button>
						</div>
						<!-- <div class="col-md-4 col-xl-4 col-lg-4 col-sm-6 col-xs-6">
							<div style="margin-top: 23px;">
								<button type="button" class="btn btn-success" id="check_kode" onclick="handleCheckKode()"><i class="fas fa-search"></i> <span name="CAPTION-CHECKKODE">Cek Kode</span></button>
								<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
							</div>
						</div> -->
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-success handlePilihSplitSJ"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button> -->
				<button type="button" class="btn btn-dark" id="closeModal-1" onclick="handlerCloseModalScan()"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
				<!-- <button type="button" style="display: none" class="btn btn-dark" id="closeModal-2" onclick="handlerCloseModalScan2()"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button> -->
			</div>
		</div>
	</div>

</div>