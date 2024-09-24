<div class="row">
	<div class="col-xl-5 col-lg-5 col-md-5 col-xs-12">
		<div class="head-switch-global">
			<div class="switch-holder">
				<div class="switch-toggle">
					<input type="checkbox" id="check_scan" class="check_scan">
					<label for="check_scan"></label>
				</div>
				<div class="switch-label-global">
					<button type="button" class="btn btn-info" id="start_scan"><i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start Scan</label></button>
					<button type="button" class="btn btn-danger" style="display:none" id="stop_scan"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>

					<button type="button" class="btn btn-warning" style="display:none" id="input_manual"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
					<button type="button" class="btn btn-danger" style="display:none" id="close_input"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>
				</div>
			</div>
		</div>


		<div id="select_kamera"></div>
		<div id="preview" style="display: none;"></div>

		<div id="preview_input_manual" style="display: none;margin-top:10px">
			<div class="row">
				<div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
					<div class="form-group">
						<label name="CAPTION-KODEPALLET">Kode Pallet</label>
						<input type="text" class="form-control" autocomplete="off" id="kode_barcode" placeholder="masukkan kode setelah tanda '/' pertama">
						<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
							<table class="table table-striped table-sm table-hover" id="table-fixed">
								<tbody id="konten-table"></tbody>
							</table>
						</div>
					</div>
					<!-- <div class="form-group">
						<label name="CAPTION-KODEPALLET">Kode Pallet</label>
						<input type="text" class="form-control" id="kode_barcode" placeholder="masukkan kode setelah tanda '/' pertama">
					</div> -->
				</div>
				<div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
					<div style="margin-top: 23px;">
						<button type="button" class="btn btn-success" id="check_kode"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label></button>
						<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
		<div class="from-group">
			<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
			<input type="text" class="form-control" id="txtpreviewscan" readonly />
		</div>
	</div>

	<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
		<div style="float: right">
			<button type="button" class="btn btn-info" onclick="handleOpenPilihSKU(event)" id="pilih_sku"><i class="fas fa-save"></i> <label name="CAPTION-PILIHSKU">Pilih SKU</label></button>
		</div>
	</div>
</div>