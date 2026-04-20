<div class="modal fade" id="manual_input_rak" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKLOKASIBINTUJUAN">Manual Input Check Lokasi Bin Tujuan</h4>
			</div>
			<div class="modal-body">
				<div class="row">

					<div class="form-group">
						<div class="col-xs-6">

							<label name="CAPTION-NAMARAK">Nama Rak</label>
							<input type="text" class="form-control nama_rak" autocomplete="off" id="kode_barcode_one" onkeyup="" placeholder="1A-2-8">

						</div>
						<div class="col-xs-6">
							<br>
							<button type="button" class="btn btn-success" id="check_kode_rak" onclick="handlerScanInputManual(event, event, 'one')">
								<i class="fas fa-search"></i> Check Kode
							</button>

						</div>

					</div>
				</div>

				<div class="row">
					<div class="form-group" style="padding: 20px;">

						<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
							<table class="table table-striped table-sm table-hover" id="table-fixed-one">
								<tbody id="konten-table-one"></tbody>
							</table>
						</div>
					</div>
				</div>

				<!-- <div class="form-group">
					<label name="CAPTION-NAMARAK">Nama Rak</label>
					<input type="text" class="form-control nama_rak" id="nama_rak" placeholder="1A-2-8" />
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark tutup_input_manual"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE">Close</label></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="manual_input_pallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKLOKASIBPALLETTUJUAN">Manual Input Check Lokasi Pallet Tujuan</h4>
			</div>
			<div class="modal-body">
				<div class="row ">
					<div class="col-lg-12 col-sm-12 ">
						<div class="head-switch-global">
							<div class="switch-holder">
								<div class="switch-toggle">
									<input type="checkbox" id="check_scan_pallet" class="check_scan_pallet">
									<label for="check_scan_pallet"></label>
								</div>
								<div class="switch-label-global">
									<button type="button" class="btn btn-info" id="start_scan_pallet">
										<i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Mulai Scan</label>
									</button>
									<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_pallet">
										<i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Hentikan Scan</label>
									</button>
									<button type="button" class="btn btn-warning" style="display:none" id="input_manual_pallet">
										<i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Input Manual</label>
									</button>
									<button type="button" class="btn btn-danger" style="display:none" id="close_input">
										<i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Tutup Input</label>
									</button>
								</div>
							</div>
						</div>

						<div id="select_kamera_pallet"></div>
						<div id="preview_pallet" style="display: none;"></div>

						<div id="preview_input_manual_pallet" style="display: none;margin-top:10px">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label name="CAPTION-KODEPALLET">Kode Pallet</label>
										<input type="text" class="form-control" autocomplete="off" id="kode_barcode_notone_pallet" placeholder="masukkan kode setelah tanda '/' pertama">
										<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
											<table class="table table-striped table-sm table-hover" id="table-fixed-notone-pallet">
												<tbody id="konten-table-notone-pallet"></tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div style="margin-top: 23px;">
										<button type="button" class="btn btn-success" id="check_kode_pallet">
											<i class="fas fa-search"></i> Check Kode
										</button>
										<span id="loading_cek_manual" style="display:none;">
											<i class="fa fa-spinner fa-spin"></i> Loading...
										</span>
									</div>
								</div>
							</div>

							<div id="show-file" style="margin-top: 7px;"></div>
						</div>
					</div>
					<div style="margin:5px;"></div>
					<div class="col-lg-4" style="display: none;">
						<div class="form-group">
							<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
							<input type="text" class="form-control" id="txtpreviewscan_pallet" readonly="">
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>