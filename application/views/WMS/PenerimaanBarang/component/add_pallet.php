<div class="row" id="show_add_pallet" style="margin-top: 10px;display:none">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="container mt-2">
				<div class="row">
					<div class="col-md-6">
						<fieldset>
							<legend>
								<label>Scan pallet</label>
							</legend>
							<div class="head-switch">
								<div class="switch-holder">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan" class="check_scan" onchange="handleCheckScan('pallet', event)">
										<label for="check_scan"></label>
									</div>
									<div class="switch-label">
										<button type="button" class="btn btn-info" id="start_scan_pallet" onclick="handleStartScan('pallet')"><i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start Scan</label></button>
										<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_pallet" onclick="handleStopScan('pallet')"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>

										<button type="button" class="btn btn-warning" style="display:none" id="input_manual_pallet" onclick="handleInputManual('pallet')"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
										<button type="button" class="btn btn-danger" style="display:none" id="close_input_pallet" onclick="handleStopInput('pallet')"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>
									</div>

									<div class="row" style="margin-left: 10px;">
										<div class="col-md-4"><label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label></div>
										<div class="col-md-8">
											<input type="text" class="form-control" id="txtpreviewscan_pallet" readonly />
										</div>
									</div>
								</div>
							</div>


							<div id="select_kamera_pallet"></div>
							<div id="preview_pallet" style="display: none;"></div>

							<div id="preview_input_manual_pallet" style="display: none;margin-top:10px">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label name="CAPTION-KODEPALLET">Kode Pallet</label>
											<!-- <input type="text" class="form-control" autocomplete="off" onkeyup="handlerAutoCompleteScan(event, this.value, 'pallet')" id="kode_barcode_pallet" placeholder="masukkan kode setelah tanda '/' pertama"> -->
											<input type="text" class="form-control" autocomplete="off" id="kode_barcode_pallet" placeholder="masukkan kode setelah tanda '/' pertama">
											<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
												<table class="table table-striped table-sm table-hover" id="table-fixed-pallet">
													<tbody id="konten-table-pallet"></tbody>
												</table>
											</div>
										</div>
										<!-- <div class="form-group">
											<label name="CAPTION-KODEPALLET">Kode Pallet</label>
											<input type="text" class="form-control" id="kode_barcode_pallet" onkeypress="return handleChecKodeByEnter(event, this.value, 'pallet')" placeholder="masukkan kode setelah tanda '/' pertama">
										</div> -->
									</div>
									<div class="col-md-4">
										<div style="margin-top: 23px;">
											<button type="button" class="btn btn-success" id="check_kode_pallet" onclick="handleCheckKode('pallet')"><i class="fas fa-search"></i> Check Kode</button>
											<span id="loading_cek_manual_pallet" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
										</div>
									</div>
								</div>
							</div>
						</fieldset>

					</div>
					<div class="col-md-6">
						<fieldset>
							<legend>
								<label name="CAPTION-PALLET">Pallet</label>
								<!-- <button type="button" class="btn btn-primary btn-xs" id="add-row"><i class="fas fa-plus"></i> <label name="CAPTION-ADDROW">Add Row</label></button> -->
							</legend>
							<div class="table-responsive">
								<table id="data_pallet" class="table table-striped" width="100%">
									<thead>
										<tr class="text-center">
											<td width="5%"><strong>#</strong></td>
											<td width="20%"><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
											<!-- <td width="15%"><strong><label name="CAPTION-JENISPALLET">Jenis Pallet</label></strong></td> -->
											<td width="15%"><strong><label name="CAPTION-LOKASITUJUAN">Lokasi Tujuan</label></strong></td>
											<td width="15%"><strong><label>Checker</label></strong></td>
											<td width="15%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<td colspan="5" class="text-center bg-success" id="total_pallet"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</fieldset>
					</div>
					<div class="col-md-12" id="show_isi_sku_pallet" style="display: none;">
						<fieldset>
							<legend>
								<label name="CAPTION-DETAILPALLET">Detail Pallet</label>
								<button type="button" class="btn btn-primary btn-xs" id="add-sku"><i class="fas fa-plus"></i> <label name="CAPTION-ADDSKU">Add SKU</label></button>

								<strong>[ <Span class="detail_pallet_ke"></Span> ]</strong>
							</legend>

							<div class="span-example alert alert-info" style="display: none;">
								<h4><i class="fa fa-info"></i> <label name="CAPTION-INFORMASI">Informasi</label>!</h4> <label name="CAPTION-MSG01_1">Jika melakukan perubahan pada Jumlah barang harap tekan enter setelah mengubahnya!</label> , <label name="CAPTION-MSG01_2"></label>
							</div>

							<div class="table-responsive">
								<table class="table table-striped" width="100%" id="list_detail_pallet">
									<thead>
										<tr class="text-center">
											<td><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
											<td><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
											<td><strong><label name="CAPTION-NAMABARANG">Nama Barang</label></strong></td>
											<td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
											<td><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
											<td><strong><label name="CAPTION-EXPIREDDATE">Exp Date</label></strong></td>
											<td><strong><label name="CAPTION-ACTUALEXPIREDDATE">Aktual Exp Date</label></strong></td>
											<td><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
											<td><strong><label name="CAPTION-TERIMA">Terima</label></strong></td>
											<td><strong><label name="CAPTION-SISA">Sisa</label></strong></td>
											<td width="6%"><strong><label name="CAPTION-AKTUALJUMLAHBARANG">Aktual Jumlah Barang</label></strong></td>
											<td><strong><label name="CAPTION-ACTION">Action</label></strong></td>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<td colspan="12" class="text-center bg-success" id="total_detail_pallet"></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>