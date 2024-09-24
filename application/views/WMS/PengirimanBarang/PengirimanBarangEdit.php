<style>
	table.display {
		margin: 0 auto;
		width: 100%;
		clear: both;
		border-collapse: collapse;
		table-layout: fixed;
		word-wrap: break-word;
	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}

	#tbody-listSKU tr,
	#tbody-listLokasiSKU tr {
		cursor: pointer;
	}

	#selectCameraPallet {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	input[name="tools"]:checked,
	input[name="tools"]:not(:checked) {
		position: absolute;
		left: -9999px;
		width: 0;
		height: 0;
		visibility: hidden;
	}

	#selectCameraPallet .checkbox-tools:checked+label,
	#selectCameraPallet .checkbox-tools:not(:checked)+label {
		position: relative;
		display: inline-block;
		padding: 20px;
		width: 50%;
		font-size: 14px;
		line-height: 20px;
		letter-spacing: 1px;
		margin: 0 auto;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 10px;
		text-align: center;
		border-radius: 4px;
		overflow: hidden;
		cursor: pointer;
		text-transform: uppercase;
		-webkit-transition: all 300ms linear;
		transition: all 300ms linear;
	}

	#selectCameraPallet .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#selectCameraPallet .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCameraPallet .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#selectCameraPallet .checkbox-tools:checked+label::before,
	#selectCameraPallet .checkbox-tools:not(:checked)+label::before {
		position: absolute;
		content: "";
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border-radius: 4px;
		background-image: linear-gradient(298deg, var(--red), var(--yellow));
		z-index: -1;
	}

	#selectCameraPallet .checkbox-tools:checked+label .uil,
	#selectCameraPallet .checkbox-tools:not(:checked)+label .uil {
		font-size: 24px;
		line-height: 24px;
		display: block;
		padding-bottom: 10px;
	}

	.head-switch {
		/* max-width: 1000px; */
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		/* justify-content: space-around; */
	}

	.switch-holder {
		display: flex;
		border-radius: 10px;
		justify-content: space-between;
		align-items: center;
	}

	.switch-label {
		width: 150px;
		text-align: end;
	}

	.switch-toggle input[type="checkbox"] {
		position: absolute;
		opacity: 0;
		z-index: -2;
	}

	.switch-toggle input[type="checkbox"]+label {
		position: relative;
		display: inline-block;
		width: 100px;
		height: 40px;
		border-radius: 20px;
		margin: 0;
		cursor: pointer;
		box-shadow: 1px 1px 4px 1px;
	}

	.switch-toggle input[type="checkbox"]+label::before {
		position: absolute;
		content: "Scan";
		font-size: 13px;
		text-align: center;
		line-height: 25px;
		top: 8px;
		left: 8px;
		width: 45px;
		height: 25px;
		color: #fff;
		border-radius: 20px;
		background-color: #5bc0de;
		box-shadow: -3px -3px 5px rgba(255, 255, 255, 0.5), 3px 3px 5px #5bc0de;
		transition: 0.3s ease-in-out;
	}

	.switch-toggle input[type="checkbox"]:checked+label::before {
		left: 50%;
		content: "Input";
		color: #fff;
		background-color: #f0ad4e;
		box-shadow: -3px -3px 5px rgba(255, 255, 255, 0.5), 3px 3px 5px #f0ad4e;
	}

	@media (max-width: 800px) {

		#select_kamera_by_one .checkbox-tools:checked+label,
		#select_kamera_by_one .checkbox-tools:not(:checked)+label {
			flex: 100%;
		}
	}
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-EDITPENGIRIMANBARANG">Pengiriman Barang Edit</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h5 name="CAPTION-EDITPENGIRIMANBARANG">Edit Pengiriman Barang</h5>
					</div>
					<div class="x_content">
						<form id="form-filter-pick-form" action="<?php echo site_url('PickList/PickingProgressForm') ?>" method="POST" class="form-horizontal form-label-left">
							<div class="row">
								<input type="hidden" id="last_update" value="<?= $stp->serah_terima_kirim_update_tgl ?>">
								<div class="col-lg-6">
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALPENGIRIMANBARANG">Tanggal Pengiriman
											Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="date" class="form-control input-date-start date" name="tgl" id="tgl" placeholder="dd-mm-yyyy" required="required" value="<?= $stp->tgl_create ?>" readonly>
										</div>

									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4  label-align" name="CAPTION-NOPENGIRIMANBARANG">No Pengiriman
											Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" name="no_picking" readonly value="<?= $stp->serah_terima_kirim_kode ?>">
											<input type="hidden" class="form-control" name="serah_terima_kirim_id" id="serah_terima_kirim_id" readonly value="<?= $stp->serah_terima_kirim_id ?>">
											<input type="hidden" class="form-control" name="delivery_order_batch_id" id="delivery_order_batch_id" readonly value="<?= $stp->delivery_order_batch_id ?>">

										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALAKHIR">No PPB
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control select2" name="ppb_id" id="ppb_id" required disabled>

												<option value=""><?= $stp->picking_order_kode ?></option>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align">No FDJR
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control select2" name="no_batch_do" id="no_batch_do" required onchange="" disabled>

												<option value=""><?= $stp->delivery_order_batch_kode ?></option>

											</select>
											<input type="hidden" class="form-control " name="tipe" id="tipe" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" value="<?= $stp->karyawan_nama ?>" name="driver" id="driver" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align">Status
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" value="<?= $stp->serah_terima_kirim_status ?>" name="status" id="status" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan
										</label>
										<div class="col-md-6 col-sm-6">
											<textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="3" style="width:100%" disabled>
											<?= $stp->serah_terima_kirim_keterangan ?>
											</textarea>
										</div>
									</div>



								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row ro-batch" id="do-table">
			<?php if ($stp_d1 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5 name="CAPTION-DETAILPENGIRIMANBARANGBULK">Detail Pengiriman Barang Picking per SKU</h5>
							<div class="head-switch">
								<div class="switch-holder">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan_bulk" class="check_scan_bulk" onchange="changeScanPalletHandler(event, 'bulk')">
										<label for="check_scan_bulk"></label>
									</div>

									<div class="switch-label" style="margin-left: -40px;">
										<button type="button" class="btn btn-info start_scan_pallet_bulk" id="start_scan_pallet_bulk" name="start_scan_pallet_bulk" onclick="startScanPalletHandler('bulk')"> <i class="fas fa-qrcode"></i>
											Scan</button>
										<button type="button" class="btn btn-warning input_pallet_bulk" id="input_pallet_bulk" name="input_pallet_bulk" style="display:none" onclick="inputScanPalletHandler('bulk')"> <i class="fas fa-keyboard"></i>
											Input</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="viewBulk" class="table-responsive" style="overflow-x:auto;">
							<!-- <div style="padding-bottom: 1rem;">
                            <label>Show/Hide Column :</label>
                            <select id="colvis-select" multiple class="form-control select2">
                            </select>
                        </div> -->
							<table id="TabelDetailBulk" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
										<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
										<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
										<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
										<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang
										</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil
										</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima
											(Bagus)</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima
											(Rusak)</th>
										<?php if ($stp->serah_terima_kirim_status != 'Closed') { ?>
											<th style="text-align: center;">Edit</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } ?>
			<?php if ($stp_d2 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5 name="CAPTION-DETAILPENGIRIMANBARANGSTANDAR">Detail Pengiriman Barang Picking per DO</h5>
							<div class="head-switch">
								<div class="switch-holder">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan_standar" class="check_scan_standar" onchange="changeScanPalletHandler(event, 'standar')">
										<label for="check_scan_standar"></label>
									</div>

									<div class="switch-label" style="margin-left: -40px;">
										<button type="button" class="btn btn-info start_scan_pallet_standar" id="start_scan_pallet_standar" name="start_scan_pallet_standar" onclick="startScanPalletHandler('standar')"> <i class="fas fa-qrcode"></i>
											Scan</button>
										<button type="button" class="btn btn-warning input_pallet_standar" id="input_pallet_standar" name="input_pallet_standar" style="display:none" onclick="inputScanPalletHandler('standar')"> <i class="fas fa-keyboard"></i>
											Input</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="viewStandar" class="table-responsive" style="overflow-x:auto;">
							<table id="TabelDetailStandar" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-DOKODE">DO Kode
										</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTNAME">Nama
										</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTADDRESS">
											Alamat</th>
										<!-- <th style="text-align: center;">Kemasan</th>
										<th style="text-align: center;">Satuan</th> -->
										<th style="text-align: center; vertical-align:middle;" hidden name="CAPTION-JUMLAHPAKET">Jumlah Paket</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-TOTALSERAHTERIMA">Jumlah Serah Terima</th>
										<?php if ($stp->serah_terima_kirim_status != 'Closed') { ?>
											<th style="text-align: center;">Edit</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if ($stp_d3 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5>Detail Pengiriman Barang DO Reschedule</h5>
							<div class="head-switch">
								<div class="switch-holder">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan_reschedule" class="check_scan_reschedule" onchange="changeScanPalletHandler(event, 'reschedule')">
										<label for="check_scan_reschedule"></label>
									</div>

									<div class="switch-label" style="margin-left: -40px;">
										<button type="button" class="btn btn-info start_scan_pallet_reschedule" id="start_scan_pallet_reschedule" name="start_scan_pallet_reschedule" onclick="startScanPalletHandler('reschedule')"> <i class="fas fa-qrcode"></i>
											Scan</button>
										<button type="button" class="btn btn-warning input_pallet_reschedule" id="input_pallet_reschedule" name="input_pallet_reschedule" style="display:none" onclick="inputScanPalletHandler('reschedule')"> <i class="fas fa-keyboard"></i>
											Input</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="viewReschedule" class="table-responsive" style="overflow-x:auto;">
							<!-- <div style="padding-bottom: 1rem;">
                            <label>Show/Hide Column :</label>
                            <select id="colvis-select" multiple class="form-control select2">
                            </select>
                        </div> -->
							<table id="TabelDetailReschedule" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
										<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
										<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
										<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
										<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang
										</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil
										</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima
											(Bagus)</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima
											(Rusak)</th>
										<?php if ($stp->serah_terima_kirim_status != 'Closed') { ?>
											<th style="text-align: center;">Edit</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } ?>
			<?php if ($stp_d4 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5>Detail Pengiriman Barang Canvas</h5>
							<div class="head-switch">
								<div class="switch-holder">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan_canvas" class="check_scan_canvas" onchange="changeScanPalletHandler(event, 'canvas')">
										<label for="check_scan_canvas"></label>
									</div>

									<div class="switch-label" style="margin-left: -40px;">
										<button type="button" class="btn btn-info start_scan_pallet_canvas" id="start_scan_pallet_canvas" name="start_scan_pallet_canvas" onclick="startScanPalletHandler('canvas')"> <i class="fas fa-qrcode"></i>
											Scan</button>
										<button type="button" class="btn btn-warning input_pallet_canvas" id="input_pallet_canvas" name="input_pallet_canvas" style="display:none" onclick="inputScanPalletHandler('canvas')"> <i class="fas fa-keyboard"></i>
											Input</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div id="viewCanvas" class="table-responsive" style="overflow-x:auto;">
							<!-- <div style="padding-bottom: 1rem;">
                            <label>Show/Hide Column :</label>
                            <select id="colvis-select" multiple class="form-control select2">
                            </select>
                        </div> -->
							<table id="TabelDetailCanvas" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
										<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
										<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
										<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
										<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang
										</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil
										</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima
											(Bagus)</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima
											(Rusak)</th>
										<?php if ($stp->serah_terima_kirim_status != 'Closed') { ?>
											<th style="text-align: center;">Edit</th>
										<?php } ?>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="row mt-2">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel" style="display: flex;align-items:center;justify-content:space-between">

					<div class="text-right">
						<!-- <a class="btn btn-primary btn-update-picklist-progress" id="saveData">Simpan</a> -->
						<a class="btn btn-danger" href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangMenu') ?>"><label name="CAPTION-BACK">KEMBALI</label></a>
						<?php if ($stp->serah_terima_kirim_status != 'Closed') { ?>
							<a class="btn btn-warning" id="saveEdit" onclick="saveEdit()"><label name="CAPTION-SAVE">Simpan</label></a>
							<a class="btn btn-info" id="saveConfirm" onclick="saveConfirm()"><label name="CAPTION-KONFIRMASISELESAI">Konfirmasi Selesai</label></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>

<div class="modal fade" id="modalScanPallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-SCANPALLET"></h4>
			</div>
			<div class="modal-body">
				<div id="selectCameraPallet"></div>
				<div id="previewScanPallet"></div>

				<div class="from-group" style="margin-top: 20px;">
					<label name="CAPTION-HASILSCANPALLET"></label>
					<input type="text" class="form-control" id="txtPreviewScanPallet" readonly />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark" onclick="closeScanPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN"></label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalInputPallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKPALLET"></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Kode SKU</label>
					<input type="text" class="form-control kodeSKu" autocomplete="off" id="kodeSKu" onkeyup="handlerAutoCompleteSKU(event, this.value)" placeholder="Kode SKU">
					<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
						<table class="table table-striped table-sm table-hover" id="table-fixed">
							<tbody id="konten-table"></tbody>
						</table>
					</div>
				</div>
				<!-- <div class="form-group">
					<label>Kode SKU</label>
					<input type="text" class="form-control kodeSKu" id="kodeSKu" placeholder="Kode SKU" onchange="changeInputPalletHandler(event)" />
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark" onclick="closeInputPalletHandler()"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE"></label></button>
			</div>
		</div>
	</div>
</div>