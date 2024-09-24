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
				<h3 name="CAPTION-132003000">Download Stock Movement Bosnet</h3>
			</div>


		</div>

		<div class="clearfix"></div>


		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">

						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-12">
							<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<select class="form-control select2" id="filter_perusahaan" style="width:100%;">
								<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
								<?php foreach ($Perusahaan as $row) : ?>
									<option value="<?= $row['client_wms_kode'] ?>"><?= $row['client_wms_nama'] ?></option>
								<?php endforeach; ?>
								<!-- <option value="ATP">PT. AMARIS TIRTA PRATAMA</option> -->
							</select>
						</div>
						<div class="col-md-4 col-sm-12">
							<label name="CAPTION-TANGGAL">Tanggal</label>
							<input style="height:40px;" type="date" id="filter_tanggal" class="form-control" name="filter_tanggal" value="<?= date('Y-m-d') ?>" />
						</div>
						<div class="col-md-4 col-sm-12">
							<label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
							<select class="form-control select2" id="filter_tipe_transaksi" style="width:100%;">
								<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
								<?php foreach ($TipeTransaksi as $row) : ?>
									<option value="<?= $row['tipe_transaksi_id'] ?>"><?= $row['tipe_transaksi_id'] . " - " . $row['tipe_transaksi'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-8 col-sm-12">
							<!-- <button type="button" id="btnviewsofas" class="btn btn-primary"><i class="fa fa-search"></i> View</button> -->
							<button type="button" id="btn_save_stock_movement" class="btn btn-primary"><i class="fa fa-save"></i> Download Stock Movement</button>
							<button type="button" id="btn_generate_retur_supplier" class="btn btn-primary"><i class="fa fa-save"></i> Generate Retur Supplier</button>
							<button type="button" id="btn_refresh_stock_movement" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</button>
							<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
						</div>
					</div>
					<br>
					<div class="clearfix"></div>
					<div class="row">
						<table id="table_stock_movement_wms" width="100%" class="table table-striped">
							<thead>
								<tr>
									<th class="text-center" name="CAPTION-TANGGALTRANSAKSI">Tanggal Transaksi</th>
									<th class="text-center" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</th>
									<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
									<th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
									<th class="text-center" name="CAPTION-SKU">SKU</th>
									<th class="text-center" name="CAPTION-QTY">Qty</th>
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
<!-- /page content -->