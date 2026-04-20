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
	#select_kamera {
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
	#select_kamera .checkbox-tools:checked+label,
	#select_kamera .checkbox-tools:not(:checked)+label {
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
	#select_kamera .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#select_kamera .checkbox-tools:checked+label,
	#select_kamera .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:not(:checked)+label:hover,
	#select_kamera .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera .checkbox-tools:checked+label::before,
	#select_kamera .checkbox-tools:not(:checked)+label::before,
	#select_kamera .checkbox-tools:checked+label::before,
	#select_kamera .checkbox-tools:not(:checked)+label::before {
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
	#select_kamera .checkbox-tools:checked+label .uil,
	#select_kamera .checkbox-tools:not(:checked)+label .uil {
		font-size: 24px;
		line-height: 24px;
		display: block;
		padding-bottom: 10px;
	}

	@media (max-width: 800px) {

		#select_kamera .checkbox-tools:checked+label,
		#select_kamera .checkbox-tools:not(:checked)+label,
		#select_kamera .checkbox-tools:checked+label,
		#select_kamera .checkbox-tools:not(:checked)+label {
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
				<h3><span name="CAPTION-BTB">Bukti Terima Barang</span></h3>
			</div>
			<div style="float: right">
			</div>
		</div>
		<?php foreach ($DOHeader as $header) : ?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="clearfix"></div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group field-konfirmasipengirimancanvas-delivery_order_batch_kode">
									<label class="control-label" for="konfirmasipengirimancanvas-delivery_order_batch_kode" name="CAPTION-NOFDJR">No FDJR</label>
									<input readonly="readonly" type="text" id="konfirmasipengirimancanvas-delivery_order_batch_kode" class="form-control" name="konfirmasipengirimancanvas[delivery_order_batch_kode]" autocomplete="off" value="<?= $header['delivery_order_batch_kode'] ?>">
									<input readonly="readonly" type="hidden" id="konfirmasipengirimancanvas-delivery_order_batch_id" class="form-control" name="konfirmasipengirimancanvas[delivery_order_batch_id]" autocomplete="off" value="<?= $header['delivery_order_batch_id'] ?>">
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group field-konfirmasipengirimancanvas-delivery_order_kode">
									<label class="control-label" for="konfirmasipengirimancanvas-delivery_order_kode" name="CAPTION-NODO">No DO</label>
									<input readonly="readonly" type="text" id="konfirmasipengirimancanvas-delivery_order_kode" class="form-control" name="konfirmasipengirimancanvas[delivery_order_kode]" autocomplete="off" value="<?= $header['delivery_order_kode'] ?>">
									<input readonly="readonly" type="hidden" id="konfirmasipengirimancanvas-delivery_order_id" class="form-control" name="konfirmasipengirimancanvas[delivery_order_id]" autocomplete="off" value="<?= $header['delivery_order_id'] ?>">
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group field-konfirmasipengirimancanvas-delivery_order_kode">
									<label class="control-label" for="konfirmasipengirimancanvas-sales_order_no_po" name="CAPTION-NOSOEKSTERNAL">No SO External</label>
									<input readonly="readonly" type="text" id="konfirmasipengirimancanvas-sales_order_no_po" class="form-control" name="konfirmasipengirimancanvas[sales_order_no_po]" autocomplete="off" value="<?= $header['sales_order_no_po'] ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group field-konfirmasipengirimancanvas-client_wms_id">
									<label for="konfirmasipengirimancanvas-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
									<select id="konfirmasipengirimancanvas-client_wms_id" class="form-control select2" name="konfirmasipengirimancanvas[client_wms_id]" disabled>
										<option value="<?= $header['client_wms_id'] ?>"><?= $header['client_wms_nama'] ?></option>
									</select>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group field-konfirmasipengirimancanvas-karyawan_id">
									<label for="konfirmasipengirimancanvas-karyawan_id" class="control-label" name="CAPTION-CHECKER">Checker</label>
									<select id="konfirmasipengirimancanvas-karyawan_id" class="form-control select2" name="konfirmasipengirimancanvas[karyawan_id]" disabled>
										<option value="<?= $header['karyawan_id'] ?>"><?= $header['karyawan_nama'] ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group field-konfirmasipengirimancanvas-penerimaan_penjualan_kode">
									<label for="konfirmasipengirimancanvas-penerimaan_penjualan_kode" class="control-label" name="CAPTION-NOBTB">No BTB</label>
									<input readonly="readonly" type="text" id="konfirmasipengirimancanvas-penerimaan_penjualan_kode" class="form-control" name="konfirmasipengirimancanvas[penerimaan_penjualan_kode]" autocomplete="off" placeholder="autogenerate" value="<?= $header['penerimaan_penjualan_kode'] ?>">
									<input type="hidden" id="konfirmasipengirimancanvas-penerimaan_penjualan_id" class="form-control" name="konfirmasipengirimancanvas[penerimaan_penjualan_id]" autocomplete="off" placeholder="autogenerate" value="<?= $header['penerimaan_penjualan_id'] ?>">
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group field-konfirmasipengirimancanvas-client_wms_id">
									<label for="konfirmasipengirimancanvas-client_wms_id" class="control-label" name="CAPTION-TGLBTB">Tanggal BTB</label>
									<input readonly="readonly" type="text" id="konfirmasipengirimancanvas-penerimaan_penjualan_tgl" class="form-control datepicker" name="konfirmasipengirimancanvas[penerimaan_penjualan_tgl]" autocomplete="off" value="<?= $header['penerimaan_penjualan_tgl'] ?>">
								</div>
							</div>
						</div>
						<div class="row">

							<div class="col-xs-6">
								<div class="form-group field-konfirmasipengirimancanvas-depo_detail_id">
									<label for="konfirmasipengirimancanvas-depo_detail_id" class="control-label" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
									<select id="konfirmasipengirimancanvas-depo_detail_id" class="form-control select2" name="konfirmasipengirimancanvas[depo_detail_id]" disabled>
										<option value="<?= $header['depo_detail_id'] ?>"><?= $header['depo_detail_nama'] ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="row" id="panel-sku">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<table id="tabledoretur" width="100%" class="table table-bordered">
						<thead>
							<tr class="bg-info">
								<th class="text-center"><span name="CAPTION-NO">No</span></th>
								<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
								<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
								<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
								<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
								<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
								<th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
								<th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($DODetail != "0") {
								$no = 1;
								foreach ($DODetail as $detail) : ?>
									<tr>
										<td class="text-center"><?= $no ?></td>
										<td class="text-center"><?= $detail['principle'] ?></td>
										<td class="text-center"><?= $detail['sku_kode'] ?></td>
										<td class="text-center"><?= $detail['sku_nama_produk'] ?></td>
										<td class="text-center"><?= $detail['sku_kemasan'] ?></td>
										<td class="text-center"><?= $detail['sku_satuan'] ?></td>
										<td class="text-center"><?= $detail['sku_qty'] ?></td>
										<td class="text-center"><?= $detail['sisa_jumlah_terima'] ?></td>
									</tr>
							<?php $no++;
								endforeach;
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
			<div class="panel panel-default col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="viewpalletdoretur">
				<div class="panel-heading" style="margin-left:-10px;margin-right:-10px;"><span name="CAPTION-PALLET">Pallet</span></div>
				<div class="panel-body form-horizontal form-label-left" style="margin-left:-20px;margin-right:-20px;">
					<table id="tablepallet" width="100%" class="table table-bordered">
						<thead>
							<tr class="bg-info">
								<th class="text-center"><span name="CAPTION-KODE">Kode</span></th>
								<th class="text-center" style="width: 50%;"><span name="CAPTION-TIPE">Jenis</span></th>
								<th class="text-center" style="width: 20%;"><span name="CAPTION-ACTION">Action</span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($Pallet as $detail) : ?>
								<tr>
									<td class="text-center"><?= $detail['pallet_kode'] ?></td>
									<td class="text-center"><?= $detail['pallet_jenis_nama'] ?></td>
									<td class="text-center">
										<button type="button" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPallet2('<?= $detail['pallet_id'] ?>')"><i class="fa fa-search text-primary"></i></button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
			<div class="panel panel-default col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="viewdetailpallet" style="display: none;">
				<div class="panel-heading" style="margin-left:-10px;margin-right:-10px;">Detail Pallet</div>
				<div class="panel-body form-horizontal form-label-left" style="margin-left:-15px;margin-right:-20px;">
					<div class="table-responsive">
						<table id="tablepalletdetail" width="100%" class="table table-bordered">
							<thead>
								<tr class="bg-info">
									<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
									<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
									<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
									<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
									<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
									<th class="text-center"><span name="CAPTION-CAPTION-SKUREQEXPDATE">Exp Date</span></th>
									<th class="text-center"><span name="CAPTION-QTY">Jumlah</span></th>
									<th class="text-center"><span name="CAPTION-BATCHNO">Batch No</span></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row pull-right" style="margin-top:20px;">
				<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
				<a href="<?php echo base_url() ?>WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/KonfirmasiPengirimanCanvasMenu" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
			</div>
		</div>
	</div>
</div>