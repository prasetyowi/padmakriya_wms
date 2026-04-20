<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="container mt-2">
			<div class="row">
				<div class="col-xl-5 col-lg-5 col-md-5 col-xs-12">
					<div class="head-switch-global">
						<div class="switch-holder">
							<div class="switch-toggle">
								<input type="checkbox" id="check_scan_edit" class="check_scan_edit">
								<label for="check_scan_edit"></label>
							</div>
							<div class="switch-label-global">
								<button type="button" class="btn btn-info" id="start_scan_edit"><i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start Scan</label></button>
								<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_edit"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>
								<button type="button" class="btn btn-warning" style="display:none" id="input_manual_edit"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
								<button type="button" class="btn btn-danger" style="display:none" id="close_input_edit"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>
							</div>
						</div>
					</div>
					<div id="select_kamera_edit"></div>
					<div id="preview_edit" style="display: none;"></div>

					<div id="preview_input_manual_edit" style="display: none;margin-top:10px">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-KODEKELAS">Kode</label>
									<input type="text" class="form-control" autocomplete="off" id="kode_barcode_edit" placeholder="nomor setelah tanda '/' terakhir">
									<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
										<table class="table table-striped table-sm table-hover" id="table-fixed">
											<tbody id="konten-table"></tbody>
										</table>
									</div>
								</div>
								<!-- <div class="form-group">
									<label name="CAPTION-KODEPALLET">Kode Pallet</label>
									<input type="text" class="form-control" id="kode_barcode_edit" placeholder="nomor setelah tanda '/' terakhir">
								</div> -->
							</div>
							<div class="col-md-6">
								<button type="button" class="btn btn-success" id="check_kode_edit"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label></button>
								<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-UPLOADBUKTICEKFISIK">Upload bukti cek fisik</label>
							<input id="myFileInput_edit" type="file" class="form-control" accept="image/*;capture=camera" onchange="previewFile()">
						</div>

						<div id="show-file_edit" style="margin-top: 7px;"></div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
					<div class="from-group">
						<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
						<input type="text" class="form-control" id="txtpreviewscan_edit" readonly />
					</div>
				</div>
			</div>
			<hr noshade="1">

			<div class="row" style="margin-top: 12px;">
				<div class="x_content table-responsive">
					<table id="table_list_data_pilih_pallet_edit" width="100%" class="table table-striped">
						<thead>
							<tr class="text-center">
								<td><strong></strong></td>
								<td><strong><label name="CAPTION-LOKASIRAK">Lokasi Rak</label></strong></td>
								<td><strong><label name="CAPTION-LOKASIBIN">Lokasi Bin</label></strong></td>
								<td><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
								<td><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
								<td><strong><label name="CAPTION-QTY">Qty</label></strong></td>
								<td><strong><label name="CAPTION-QTYPLANPEMUSNAHAN">Qty Plan Pemusnahan</label></strong></td>
								<td><strong><label name="CAPTION-QTYAKTUALPEMUSNAHAN">Qty Aktual Pemusnahan</label></strong></td>
								<td><strong><label name="CAPTION-ACTION">Action</label></strong></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>