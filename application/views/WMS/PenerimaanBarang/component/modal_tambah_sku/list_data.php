<div class="col-md-12 col-sm-12 col-xs-12">

	<div class="toggle-button">
		<input type="radio" name="sizeBy" value="0" id="showListDataSku" onchange="handleChangeListSKU(this.value)" />
		<label for="showListDataSku" name="CAPTION-LIHATDAFTARSKU"></label>
		<input type="radio" name="sizeBy" value="1" id="showbarcodeListSKu" onchange="handleChangeListSKU(this.value)" />
		<label for="showbarcodeListSKu" name="CAPTION-SCANBARCODESKU"></label>
	</div>

	<div class="x_panel" id="showTableListPilihSku" style="display: none;">
		<div class="container mt-2">
			<div class="row">
				<div class="x_content table-responsive">
					<table id="table_list_pilih_sku" width="100%" class="table table-striped">
						<thead>
							<tr>
								<td class="text-center" width="5%">
									<input type="checkbox" id="check-all-pilih-sku" style="transform: scale(1.5)" class="form-control" onchange="checkAllSKU(this, 'pallet')" />
								</td>
								<td width="7%"><strong><label name="CAPTION-SKUKODE">SKU Kode</strong></td>
								<td width="25%"><strong><label name="CAPTION-SKU">SKU</strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SKUKODE">Kemasan</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
								<td width="10%" class="text-center"><strong><label>Batch No</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SISA">Sisa</label></strong></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="x_panel" id="showScanbarcodeSKU" style="display: none;">
		<div class="container mt-2">
			<div class="row">
				<div class="head-switch">
					<div class="switch-holder">
						<div class="switch-toggle">
							<input type="checkbox" id="check_scan_sku" class="check_scan_sku" onchange="handleCheckScan('sku', event)">
							<label for="check_scan_sku"></label>
						</div>
						<div class="switch-label">
							<button type="button" class="btn btn-info" id="start_scan_sku" onclick="handleStartScan('sku')"><i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start Scan</label></button>
							<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_sku" onclick="handleStopScan('sku')"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>

							<button type="button" class="btn btn-warning" style="display:none" id="input_manual_sku" onclick="handleInputManual('sku')"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
							<button type="button" class="btn btn-danger" style="display:none" id="close_input_sku" onclick="handleStopInput('sku')"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>
						</div>

						<div class="row" style="margin-left: 10px;">
							<div class="col-md-5"><label name="CAPTION-HASILSCANSKU">Hasil Scan SKU</label></div>
							<div class="col-md-7">
								<input type="text" class="form-control" id="txtpreviewscan_sku" readonly />
							</div>
						</div>
					</div>
				</div>


				<div id="select_kamera_sku"></div>
				<div id="preview_sku" style="display: none;"></div>

				<div id="preview_input_manual_sku" style="display: none;margin-top:10px">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label name="CAPTION-KODESKU">Kode</label>
								<input type="text" class="form-control" autocomplete="off" onkeyup="handlerAutoCompleteScan(event, this.value, 'sku')" id="kode_barcode_sku" placeholder="kode sku">
								<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
									<table class="table table-striped table-sm table-hover" id="table-fixed-sku">
										<tbody id="konten-table-sku"></tbody>
									</table>
								</div>
							</div>
							<!-- <div class="form-group">
								<label name="CAPTION-KODESKU">Kode SKU</label>
								<input type="text" class="form-control" id="kode_barcode_sku" placeholder="" onkeypress="return handleChecKodeByEnter(event, this.value, 'sku')">
							</div> -->
						</div>
						<div class="col-md-6">
							<div style="margin-top: 23px;">
								<button type="button" class="btn btn-success" id="check_kode_sku" onclick="handleCheckKode('sku')"><i class="fas fa-search"></i> Check Kode</button>
								<span id="loading_cek_manual_sku" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
							</div>
						</div>
					</div>
				</div>

				<div class="x_content table-responsive" id="showTableListScanSku" style="display: none;">
					<table id="table_list_scan_sku" width="100%" class="table table-striped">
						<thead>
							<tr>
								<td class="text-center" width="5%">
									<input type="checkbox" id="check-all-pilih-sku" style="transform: scale(1.5)" class="form-control" onchange="checkAllSKU(this, 'sku')" />
								</td>
								<td width="7%"><strong><label name="CAPTION-SKUKODE">SKU Kode</strong></td>
								<td width="25%"><strong><label name="CAPTION-SKU">SKU</strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SKUKODE">Kemasan</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
								<td width="10%" class="text-center"><strong><label name="CAPTION-SISA">Sisa</label></strong></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>