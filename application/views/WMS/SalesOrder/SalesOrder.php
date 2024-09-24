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
				<h3 name="CAPTION-130002001">Menu Download SO FAS</h3>
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
						<div class="col-md-3 col-sm-12">
							<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<select class="form-control select2" id="filter_perusahaan" style="width:100%;">
								<?php foreach ($Perusahaan as $row) : ?>
									<option value="<?= $row['client_wms_kode'] ?>"><?= $row['client_wms_nama'] ?></option>
								<?php endforeach; ?>
								<!-- <option value="ATP">PT. AMARIS TIRTA PRATAMA</option> -->
							</select>
						</div>
						<div class="col-md-3 col-sm-12">
							<label name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
							<input style="height:40px;" type="date" id="datesofas" class="form-control" name="datesofas" value="<?= date('Y-m-d') ?>" />
						</div>
						<div class="col-md-3 col-sm-12">
							<label name="CAPTION-STATUS">Status</label>
							<select class="form-control select2" id="status_sync" style="width:100%;">
								<option value="">** <span name="CAPTION-SEMUA">Semua</span> **</option>
								<option value="1">Sync</option>
								<option value="0">Not Sync</option>
							</select>
						</div>
						<div class="col-md-3 col-sm-12">
							<label name="CAPTION-API">API</label>
							<input type="hidden" id="filter_switched" value="0">
							<div class="head-switch-global">
								<div class="switch-holder-global">
									<div class="switch-toggle">
										<input type="checkbox" id="check_scan" class="check_scan">
										<label for="check_scan"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-8 col-sm-12">
							<!-- <button type="button" id="btnviewsofas" class="btn btn-primary"><i class="fa fa-search"></i> View</button> -->
							<button type="button" id="btnsavesofas" class="btn btn-primary"><i class="fa fa-save"></i> Download SO</button>
							<button type="button" id="btnsavedowms" class="btn btn-primary"><i class="fa fa-save"></i> Generate DO</button>
							<button type="button" id="btnrefreshsofas" class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</button>
							<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
						</div>
					</div>
					<br>
					<div class="clearfix"></div>
					<div class="row">
						<div class="container">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#panel-in-do">In DO</a></li>
								<li><a data-toggle="tab" href="#panel-not-in-do">Not In DO</a></li>
							</ul>
							<div class="clearfix"></div><br>
							<div class="tab-content">
								<div id="panel-in-do" class="tab-pane fade in active">
									<table id="table-so-in-do" width="100%" class="table table-striped">
										<thead>
											<tr>
												<!-- <th><input type="checkbox" name="select-all-so-fas" id="select-all-so-fas" value="1"> PILIH</th> -->
												<th class="text-center" name="CAPTION-NODO">DO</th>
												<th class="text-center" name="CAPTION-NOSO">SO</th>
												<th class="text-center" name="CAPTION-TANGGALSO">Tanggal SO</th>
												<th class="text-center" name="CAPTION-TANGGALDO">Tanggal DO</th>
												<th class="text-center" name="CAPTION-OUTLET">Outlet</th>
												<th class="text-center" name="CAPTION-TIPE">Tipe</th>
												<th class="text-center" name="CAPTION-STATUS">Status</th>
											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
								<div id="panel-not-in-do" class="tab-pane fade">
									<table id="table-so-not-in-do" width="100%" class="table table-striped">
										<thead>
											<tr>
												<!-- <th><input type="checkbox" name="select-all-so-fas" id="select-all-so-fas" value="1"> PILIH</th> -->
												<!-- <th class="text-center" name="CAPTION-NODO">DO</th> -->
												<th class="text-center" name="CAPTION-NOSO">SO</th>
												<th class="text-center" name="CAPTION-TANGGALBUAT">Tanggal Buat</th>
												<th class="text-center" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</th>
												<th class="text-center" name="CAPTION-OUTLET">Outlet</th>
												<th class="text-center" name="CAPTION-TIPE">Tipe</th>
												<th class="text-center" name="CAPTION-STATUS">Status</th>
												<th class="text-center" name="CAPTION-STATUSOUTLET">Status Outlet</th>
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
</div>
<!-- /page content -->