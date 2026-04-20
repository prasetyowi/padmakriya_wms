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
		content: 'Off';
		font-size: 13px;
		text-align: center;
		line-height: 25px;
		top: 8px;
		left: 8px;
		width: 45px;
		height: 25px;
		color: #fff;
		border-radius: 20px;
		background-color: red;
		transition: .3s ease-in-out;
	}

	.switch-toggle input[type="checkbox"]:checked+label::before {
		left: 50%;
		content: 'On';
		color: #fff;
		background-color: green;
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
				<h3 name="CAPTION-128001000">Monitor Minimum Stok</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4>Filter Data</h4>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-PRINCIPLE">Principle</label>
								<select id="filter-principle" name="filter_principle" class="form-control select2">
									<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
									<?php foreach ($Principle as $row) : ?>
										<option value="<?= $row['principle_id'] ?>"><?= $row['principle_kode'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-GUDANG">Gudang</label>
								<select id="filter-depo_detail_id" name="filter_depo_detail_id" class="form-control select2">
									<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
									<?php foreach ($Gudang as $row) : ?>
										<option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-MINIMUMSTOCK">Minimum Stock</label>
								<input type="text" id="filter-minimum-stock" class="form-control" name="filter_minimum_stock" value="">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-12">
								<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <span name="CAPTION-LOADING">Loading</span>...</span>
								<button type="button" id="btn-search-data" class="btn btn-primary"><i class="fa fa-search"></i> <span name="CAPTION-CARI">Cari</span></button>
								<button class="btn-submit btn btn-primary" id="btn-pembongkaran-barang" onclick="PembongkaranBarang()"><i class="fa fa-box"></i> <span name="CAPTION-PERSETUJUANPEMBONGKARANKEMASAN">Persetujuan Pembongkaran Kemasan</span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="row">
						<div class="col-xs-12">
							<div class="x_content table-responsive">
								<table id="table_list_data" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;"><input type="checkbox" name="select-all-sku" id="select-all-sku" value="1"></th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUQTY">SKU Qty</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-MINIMUMSTOCK">Minimum Stock</th>
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
</div>

<div class="modal fade" id="modal-pembongkaran-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-PERSETUJUANPEMBONGKARANKEMASAN">Persetujuan Pembongkaran Kemasan</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
										<label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-KODE">Kode</label>
										<input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_kode" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_kode]" autocomplete="off" value="">
										<input readonly="readonly" type="hidden" id="persetujuanpembongkaran-tr_konversi_sku_id" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_id]" autocomplete="off" value="<?= $tr_konversi_sku_id ?>">
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_tanggal">
										<label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_tanggal" name="CAPTION-TANGGAL">Tanggal</label>
										<input type="text" id="persetujuanpembongkaran-tr_konversi_sku_tanggal" class="form-control datepicker" name="persetujuanpembongkaran[tr_konversi_sku_tanggal]" autocomplete="off" value="<?= date('d-m-Y') ?>" disabled>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
										<label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-TIPE">Tipe</label>
										<select name="persetujuanpembongkaran[tipe_konversi_id]" class="form-control" id="persetujuanpembongkaran-tipe_konversi_id" disabled>
											<option value="">** <label name="CAPTION-TIPE">Tipe</label> **</option>
											<?php foreach ($TipeKonversi as $type) : ?>
												<option value="<?= $type['tipe_konversi_id'] ?>" <?= $type['tipe_konversi_id'] == 'B5B99B77-86D2-48B8-964F-D4B91CDD9B0C' ? 'selected' : '' ?>><?= $type['tipe_konversi_nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_status">
										<label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_status">Status</label>
										<input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_status" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_status]" autocomplete="off" value="In Progress Approval">
									</div>
									<div class="form-group field-persetujuanpembongkaran-konversi_is_need_approval">
										<input type="checkbox" id="persetujuanpembongkaran-konversi_is_need_approval" name="persetujuanpembongkaran[konversi_is_need_approval]" autocomplete="off" value="1" checked> <span name="CAPTION-PENGAJUAN">Pengajuan Approval</span>
									</div>
									<!-- <div class="form-group field-persetujuanpembongkaran-client_wms_id">
										<label for="persetujuanpembongkaran-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
										<select id="persetujuanpembongkaran-client_wms_id" class="form-control select2" name="persetujuanpembongkaran[client_wms_id]">
											<option value="">** <span name="CAPTION-PERUSAHAAN">Perusahaan</span> **</option>
											<?php foreach ($Perusahaan as $row) : ?>
												<option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="panel-sku">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<h4 class="pull-left" name="CAPTION-SKUYGDIPILIH">SKU Yang Dipilih</h4>
							<table class="table table-bordered" id="table-pembongkaran-sku">
								<thead>
									<tr class="bg-primary">
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PERUSAHAAN">Perusahaan</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-GUDANG">Gudang</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUQTY">SKU QTY</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info" id="btnsavekonversi"><i class="fa fa-save"></i> <span name="CAPTION-SAVE"></span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->