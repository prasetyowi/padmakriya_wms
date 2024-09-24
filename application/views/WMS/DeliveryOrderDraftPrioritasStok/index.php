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
				<h3 name="CAPTION-DOPRIORITASSTOK">Delivery Order Prioritas Stok</h3>
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
							<div class="col-lg-4 col-xs-12">
								<label name="CAPTION-TANGGAL">Tanggal</label>
								<input type="date" id="filter_tanggal" class="form-control" name="filter_tanggal" value="<?= date('Y-m-d') ?>" />
							</div>
							<div class="col-lg-4 col-xs-12">
								<label name="CAPTION-TIPEDO">Tipe Delivery Order</label>
								<select id="filter_tipedo" class="form-control select2" name="filter_tipedo">
									<option value="">-- Pilih Tipe DO --</option>
									<?php foreach ($TipeDeliveryOrder as $type) : ?>
										<option value="<?= $type['tipe_delivery_order_id'] ?>" data-text="<?= $type['tipe_delivery_order_alias'] ?>">
											<?= $type['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-lg-4 col-xs-12">
								<label name="CAPTION-TIPEPRIORITAS">Tipe Prioritas</label>
								<select id="filter_tipedo" class="form-control select2" name="filter_tipeprioritas">
									<option value="">-- All --</option>
									<option value="2">-- Cukup --</option>
									<option value="1">-- Tidak Cukup --</option>
								</select>
							</div>
							<!-- <div class="col-xs-4">
								<label name="CAPTION-MINIMUMSTOCK">Minimum Stock</label>
								<input type="text" id="filter-minimum-stock" class="form-control" name="filter_minimum_stock" value="">
							</div> -->
						</div>
						<br>
						<div class="row">
							<div class="col-xs-12">
								<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <span name="CAPTION-LOADING">Loading</span>...</span>
								<button type="button" id="btn-search-data" class="btn btn-primary"><i class="fa fa-search"></i> <span name="CAPTION-CARI">Cari</span></button>
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

											<!-- <th class="text-center" style="color:white;"><input type="checkbox" name="select-all-sku" id="select-all-sku" value="1"></th> -->
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-AREA">Area</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-TIPESKU">Tipe SKU</th>
											<!-- <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th> -->
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUQTYDRAFT">SKU Qty Draft</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUSTOCK">SKU Stock</th>
											<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-DETAIL">Detail</th>
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

<div class="modal fade" id="modal-detail-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width:90%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-LISTDOBYSKU">List DO By SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row" id="panel-sku">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-4">
										<label name="CAPTION-SKUKODE">SKU Kode</label>
										<input type="text" id="DetailSKUHeader-sku_kode" class="form-control" name="DetailSKUHeader-sku_kode" value="" disabled>
										<input type="hidden" id="DetailSKUHeader-sku_id" class="form-control" name="DetailSKUHeader-sku_id" value="" disabled>
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKU">SKU</label>
										<input type="text" id="DetailSKUHeader-sku_nama_produk" class="form-control" name="DetailSKUHeader-sku_nama_produk" value="" disabled>
									</div>
								</div><br>
								<div class="row">
									<div class="col-xs-4">
										<label name="CAPTION-SKUKEMASAN">SKU Kemasan</label>
										<input type="text" id="DetailSKUHeader-sku_kemasan" class="form-control" name="DetailSKUHeader-sku_kemasan" value="" disabled>
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKUSATUAN">SKU Satuan</label>
										<input type="text" id="DetailSKUHeader-sku_satuan" class="form-control" name="DetailSKUHeader-sku_satuan" value="" disabled>
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</label>
										<input type="text" id="DetailSKUHeader-sku_stock_qty" class="form-control" name="DetailSKUHeader-sku_stock_qty" value="" disabled>
									</div>
								</div><br>
							</div>
						</div>
						<div class="x_panel">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-12">
										<button class="btn btn-success" onclick="handlerPrioritas()">Prioritas</button>
										<button class="btn btn-info" onclick="handlerProrate()">Prorate</button>
										<button class="btn btn-warning" onclick="handlerReset()">Reset</button>
									</div>
								</div>
							</div>
						</div>
						<div class="x_panel">
							<table class="table table-bordered" id="table-detail-sku">
								<thead>
									<tr class="bg-primary">
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOMORSOEKSTERNAL">Nomor SO Eksternal</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOMORDO">Nomor DO</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NAMA">Nama</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ALAMAT">Alamat</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SEGMENT1">Segment1</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SEGMENT2">Segment2</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SEGMENT3">Segment3</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRIORITAS">Prioritas</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYORDER">Qty Order</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYEDIT">Qty Edit</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ABAIKAN">Abaikan</th>
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
				<button type="button" class="btn btn-success" id="btn_simpan_do_prioritas_stok"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->