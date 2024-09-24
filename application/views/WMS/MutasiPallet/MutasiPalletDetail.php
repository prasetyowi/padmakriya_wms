<style>
	:root {
		--white: #ffffff;
		--light: #f0eff3;
		--black: #000000;
		--dark-blue: #1f2029;
		--dark-light: #353746;
		--red: #da2c4d;
		--yellow: #f8ab37;
		--grey: #ecedf3;
	}

	.modal-body {
		max-height: calc(100vh - 210px);
		overflow-x: auto;
		overflow-y: auto;
	}

	.error {
		border: 1px solid red;
	}

	.alert-header {
		display: flex;
		flex-direction: row;
	}

	.alert-header .alert-icon {
		margin-right: 10px;
	}

	.span-example .alert-header .alert-icon {
		align-self: center;
	}

	#select_kamera,
	#select_kamera_by_one {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	[type="radio"]:checked,
	[type="radio"]:not(:checked) {
		position: absolute;
		left: -9999px;
		width: 0;
		height: 0;
		visibility: hidden;
	}

	#select_kamera .checkbox-tools:checked+label,
	#select_kamera .checkbox-tools:not(:checked)+label,
	#select_kamera_by_one .checkbox-tools:checked+label,
	#select_kamera_by_one .checkbox-tools:not(:checked)+label {
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

	#select_kamera .checkbox-tools:not(:checked)+label,
	#select_kamera_by_one .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#select_kamera .checkbox-tools:checked+label,
	#select_kamera_by_one .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:not(:checked)+label:hover,
	#select_kamera_by_one .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:checked+label::before,
	#select_kamera .checkbox-tools:not(:checked)+label::before,
	#select_kamera_by_one .checkbox-tools:checked+label::before,
	#select_kamera_by_one .checkbox-tools:not(:checked)+label::before {
		position: absolute;
		content: '';
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border-radius: 4px;
		background-image: linear-gradient(298deg, var(--red), var(--yellow));
		z-index: -1;
	}

	#select_kamera .checkbox-tools:checked+label .uil,
	#select_kamera .checkbox-tools:not(:checked)+label .uil,
	#select_kamera_by_one .checkbox-tools:checked+label .uil,
	#select_kamera_by_one .checkbox-tools:not(:checked)+label .uil {
		font-size: 24px;
		line-height: 24px;
		display: block;
		padding-bottom: 10px;
	}

	@media (max-width: 800px) {

		#select_kamera .checkbox-tools:checked+label,
		#select_kamera .checkbox-tools:not(:checked)+label,
		#select_kamera_by_one .checkbox-tools:checked+label,
		#select_kamera_by_one .checkbox-tools:not(:checked)+label {
			flex: 100%;
		}
	}

	.head-switch {
		/* max-width: 1000px; */
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
	}

	.switch-holder {
		display: flex;
		border-radius: 10px;
		justify-content: space-between;
		align-items: center;
	}

	.switch-label {
		width: 120px;
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
		content: 'Scan';
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
		box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
			3px 3px 5px #5bc0de;
		transition: .3s ease-in-out;
	}

	.switch-toggle input[type="checkbox"]:checked+label::before {
		left: 50%;
		content: 'Input';
		color: #fff;
		background-color: #f0ad4e;
		box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
			3px 3px 5px #f0ad4e;
	}

	.head-switch-global {
		/* max-width: 1000px; */
		width: 100%;
		display: flex;
		flex-wrap: wrap;
	}

	.switch-label-global {
		width: 150px;
		text-align: end;
	}

	.switch-holder-global {
		display: flex;
		border-radius: 10px;
		justify-content: space-between;
		align-items: center;
	}
</style>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-MUTASIPALLET">Mutasi Pallet</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-FORM">Form</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<?php foreach ($MutasiPallet as $header) { ?>
					<div class="row">
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-NOMUTASI">No Mutasi</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" id="mutasi_kode" class="form-control" name="mutasi_kode" value="<?= $header['tr_mutasi_pallet_kode']; ?>" readonly />
									<input type="hidden" id="mutasi_no" class="form-control" name="mutasi_no" value="<?= $mutasi_pallet_id; ?>" readonly />
									<input type="hidden" id="global_id" class="form-control" name="global_id" value="<?= $header['tr_mutasi_pallet_draft_id']; ?>" readonly />
									<input type="hidden" id="lastUpdated" name="lastUpdated" value="<?= $header['tr_mutasi_pallet_tgl_update']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-TGLMUTASI">Tgl Mutasi</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control datepicker" name="mutasi_tanggal" id="mutasi_tanggal" value="<?= $header['tr_mutasi_pallet_tanggal']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-NODRAFTMUTASI">No Draft Mutasi</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" id="mutasi_draft_kode" class="form-control" name="mutasi_draft_kode" value="<?= $header['tr_mutasi_pallet_draft_kode']; ?>" readonly />
									<input type="hidden" id="mutasi_draft_no" class="form-control" name="mutasi_draft_no" value="<?= $header['tr_mutasi_pallet_draft_id']; ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-GUDANGASAL">Gudang Asal</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" id="gudang_asal_mutasi_nama" name="gudang_asal_mutasi_nama" value="<?= $header['gudang_asal']; ?>" readonly />
									<input type="hidden" class="form-control" id="gudang_asal_mutasi" name="gudang_asal_mutasi" value="<?= $header['depo_detail_id_asal']; ?>" readonly />
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" id="gudang_tujuan_mutasi_nama" name="gudang_tujuan_mutasi_nama" value="<?= $header['gudang_tujuan']; ?>" readonly />
									<!-- <input type="hidden" class="form-control" id="gudang_tujuan_mutasi" name="gudang_tujuan_mutasi" value="<?= $header['depo_detail_id_tujuan']; ?>" readonly /> -->
									<select id="gudang_tujuan_mutasi" name="gudang_tujuan_mutasi" style="display: none;" class="form-control" style="width:100%" readonly>
										<option selected value="<?= $header['depo_detail_id_tujuan']; ?>"><?= $header['gudang_tujuan']; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="mutasi_perusahaan" id="mutasi_perusahaan" value="<?= $header['client_wms_nama']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-PRINCIPLE">Principle</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="mutasi_principle" id="mutasi_principle" value="<?= $header['principle_nama']; ?>" readonly>
								</div>
							</div>

						</div>
						<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-CHECKER">Checker</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="mutasi_checker" id="mutasi_checker" value="<?= $header['tr_mutasi_pallet_nama_checker']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="mutasi_tipe_transaksi" id="mutasi_tipe_transaksi" value="<?= $header['tr_mutasi_pallet_tipe']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<textarea class="form-control" id="mutasi_keterangan" name="mutasi_keterangan" <?= $header['tr_mutasi_pallet_status'] == 'Completed' ? 'readonly' : ''; ?>><?= $header['tr_mutasi_pallet_keterangan']; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-form-label col-lg-4 col-md-12 col-sm-12 col-xs-12 label-align" name="CAPTION-STATUS">Status</label>
								<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
									<input type="text" class="form-control" name="mutasi_status" id="mutasi_status" value="<?= $header['tr_mutasi_pallet_status']; ?>" readonly>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">

				<?php if ($header['tr_mutasi_pallet_status'] != "Completed") { ?>
					<div class="row" style="margin-bottom: 10px;">
						<div class="col-xl-5 col-lg-5 col-md-5 col-xs-12">
							<div class="head-switch-global">
								<div class="switch-holder-global">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan" class="check_scan">
										<label for="check_scan"></label>
									</div>
									<div class="switch-label-global">
										<button type="button" class="btn btn-info" id="start_scan"><i class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start
												Scan</label></button>
										<button type="button" class="btn btn-danger" style="display:none" id="stop_scan"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop
												Scan</label></button>

										<button type="button" class="btn btn-warning" style="display:none" id="input_manual"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
										<button type="button" class="btn btn-danger" style="display:none" id="close_input"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>
									</div>
								</div>
							</div>

							<div id="select_kamera"></div>
							<div id="preview" style="display: none;"></div>

							<div id="preview_input_manual" style="display: none;margin-top:10px">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label name="CAPTION-KODEPALLET">Kode Pallet</label>
											<input type="text" class="form-control" id="kode_barcode" placeholder="nomor setelah tanda '/' terakhir">
										</div>
									</div>
									<div class="col-md-6">
										<div style="margin-top: 23px;">
											<button type="button" class="btn btn-success" id="check_kode"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check
													Kode</label></button>
											<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label name="CAPTION-UPLOADBUKTICEKFISIK">Upload bukti cek fisik</label>
									<input id="myFileInput" type="file" class="form-control" accept="image/*;capture=camera" onchange="previewFile()">
								</div>

								<div id="show-file" style="margin-top: 7px;"></div>
							</div>
						</div>

						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
							<div class="from-group">
								<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
								<input type="text" class="form-control" id="txtpreviewscan" readonly />
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<input type="hidden" id="txt_jml_pallet" value="<?= $MutasiPalletDetail != null ? count($MutasiPalletDetail) : '0'; ?>">
					<table id="table_mutasi_pallet" width="100%" class="table table-striped">
						<thead>
							<tr style="background:#F0EBE3;">
								<th class="text-center">#</th>
								<th class="text-center" name="CAPTION-NOPALLET">No Pallet</th>
								<th class="text-center" name="CAPTION-JENISPALLET">Jenis Pallet</th>
								<th class="text-center" name="CAPTION-STATUS">Status</th>
								<th class="text-center" name="CAPTION-LOKASIRAKASAL">Lokasi Rak Asal</th>
								<th class="text-center" name="CAPTION-LOKASIBINASAL">Lokasi Bin Asal</th>
								<th class="text-center" colspan="2" name="CAPTION-LOKASIBINTUJUAN">Lokasi Bin Tujuan
								</th>
								<th class="text-center" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							$rak = "";
							$status = "";
							foreach ($MutasiPalletDetail as $key => $detail) {
								if ($detail['rak_lajur_nama'] == "null") {
									$rak = '<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" disabled placeholder="Lokasi Bin Tujuan"/>';
								} else {
									$rak = '<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" value="' . $detail['rak_lajur_nama'] . '" disabled/>';
								}

								if ($detail['is_valid'] == 0) {
									$status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Tidak Valid</span>";
								} else if ($detail['is_valid'] == 1) {
									$status = "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
								} else {
									$status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
								}
							?>
								<tr>
									<td class="text-center"><?= $no ?> <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="<?= $detail['tr_mutasi_pallet_detail_id'] ?>" /></td>
									<td class="text-center"><?= $detail['pallet_kode'] ?></td>
									<td class="text-center"><?= $detail['pallet_jenis_nama'] ?></td>
									<td class="text-center"><?= $status; ?></td>
									<td class="text-center"><?= $rak; ?></td>
									<td class="text-center"><?= $detail['rak_lajur_detail_nama'] ?></td>
									<?php if ($header['tr_mutasi_pallet_status'] != "Completed") { ?>
										<td class="text-center"><?= $detail['rak_lajur_detail_tujuan_nama'] ?></td>
										<td class="text-center">
											<div class="row">
												<div class="head-switch">
													<div class="switch-holder">
														<div class="switch-toggle">
															<input type="checkbox" id="check_scan_<?= $key ?>" class="check_scan">
															<label for="check_scan_<?= $key ?>"></label>
														</div>
														<div class="switch-label">
															<button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-id="<?= $detail['pallet_id'] ?>" data-index="<?= $key ?>" data-detail-id="<?= $detail['tr_mutasi_pallet_detail_id'] ?>"> <i class="fas fa-qrcode"></i> <label name="CAPTION-SCAN">Scan</label></button>
															<button type="button" class="btn btn-warning input_rak" name="input_rak" data-id="<?= $detail['pallet_id'] ?>" data-index="<?= $key ?>" data-detail-id="<?= $detail['tr_mutasi_pallet_detail_id'] ?>" style="display:none"> <i class="fas fa-keyboard"></i> <label name="CAPTION-INPUT">Input</label></button>
														</div>
													</div>
												</div>
											</div>
										</td>
									<?php } else { ?>
										<td class="text-center" colspan="2"><?= $detail['rak_lajur_detail_tujuan_nama'] ?></td>
									<?php } ?>
									<td class="text-center">
										<button type="button" data-toggle="tooltip" data-placement="top" data-id="<?= $detail['tr_mutasi_pallet_detail_id'] ?>" title="detail pallet" class="btn btn-primary detail_pallet" onclick="ViewDetailPallet('<?= $detail['pallet_id'] ?>')"><i class="fas fa-eye"></i> <label name="CAPTION-DETAIL">Detail</label></button>
									</td>
								</tr>
							<?php $no++;
							} ?>
						</tbody>
					</table>
				</div>
				<div class="row">
					<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
					<button class="btn btn-primary" id="btn_konfirmasi_mutasi" <?= $header['tr_mutasi_pallet_status'] == 'Completed' ? 'style="display: none;"' : ''; ?>><i class="fa fa-check"></i> <label name="CAPTION-KONFIRMASI">Konfirmasi</label></button>
					<button class="btn btn-success" id="btn_update_mutasi" <?= $header['tr_mutasi_pallet_status'] == 'Completed' ? 'style="display: none;"' : ''; ?>><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
					<a href="<?= base_url(); ?>WMS/MutasiPallet/MutasiPalletMenu" class="btn btn-danger" id="btn_kembali"><i class="fa fa-reply"></i> <label name="CAPTION-MENUUTAMA">Menu
							Utama</label></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-pallet-detail" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DETAILPALLET">Detail Pallet</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="container mt-2">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-PALLETKODE">Pallet Kode</label>
											</div>
											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_pallet_kode" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-JENISPALLET">Jenis Pallet</label>
											</div>
											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_jenis_pallet" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="x_content table-responsive">
										<table id="data-table-pallet-detail" width="100%" class="table table-striped">
											<thead>
												<tr>
													<td class="text-center" width="5%">#</td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
													<td width="30%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-EXPDATE">Exp Date</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
													<td width="5%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah
																Barang</label></strong></td>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-back-detail-pallet"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_scan" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-DETAILDATA">Detail Data</h4>
			</div>
			<div class="modal-body">
				<div id="select_kamera_by_one"></div>
				<div id="preview_by_one"></div>

				<div class="from-group" style="margin-top: 20px;">
					<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
					<input type="text" class="form-control" id="txtpreviewscan2" readonly />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark stop_scan_by_one"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="manual_input_rak" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKLOKASIBINTUJUAN">Manual Input Check Lokasi Bin
					Tujuan</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<!-- <label name="CAPTION-NAMARAK">Nama Rak</label>
					<input type="text" class="form-control nama_rak" id="nama_rak" placeholder="1A-2-8" /> -->
					<label name="CAPTION-NAMARAK">Nama Rak</label>
					<input type="text" class="form-control nama_rak" autocomplete="off" id="kode_barcode_one" onkeyup="handlerScanInputManual(event, this.value, 'one')" placeholder="1A-2-8">
					<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
						<table class="table table-striped table-sm table-hover" id="table-fixed-one">
							<tbody id="konten-table-one"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-dark tutup_input_manual"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE">Close</label></button>
			</div>
		</div>
	</div>
</div>