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
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<div class="clearfix"></div>
					</div>
					<div class="row" style="margin-bottom:15px;">
						<div class="form-group">
							<label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-NOFDJR">No FDJR </label>
							<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
								<input type="hidden" id="delivery_order_batch_id" class="form-control" value="<?php echo $delivery_order_batch_id; ?>" readonly />
								<input type="text" id="filter_fdjr_no" class="form-control" value="" readonly />
								<input type="hidden" id="fdjr_type" value="retur">
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom:25px;">
						<div class="form-group">
							<label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-DRIVER">Driver</label>
							<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
								<select id="filter_fdjr_driver" class="form-control" readonly></select>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom:25px;">
						<div class="form-group">
							<label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
								<select class="form-control select2" id="filter_perusahaan" name="filter_perusahaan" onchange="GetPrincipleByPerusahaan(this.value)">
									<option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
									<?php foreach ($Perusahaan as $row) : ?>
										<option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom:25px;">
						<div class="form-group">
							<label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
								<select class="form-control select2" id="filter_principle" name="filter_principle" onchange="GetCheckerByPrinciple(this.value)"></select>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom:25px;">
						<div class="form-group">
							<label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
							<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
								<select class="form-control select2" id="filter_checker" name="filter_checker"></select>
							</div>
						</div>
					</div>
					<div class="row">
						<ul class="nav nav-tabs">
							<li class="active">
								<a data-toggle="tab" href="#do-retur" onclick="ChangeTypeFDJR('retur')"><span name="CAPTION-TERIMABARANGDORETUR">Terima Barang DO Retur</span></a>
							</li>
							<li>
								<a data-toggle="tab" href="#terkirim-sebagian" onclick="ChangeTypeFDJR('terkirim sebagian')"><span name="CAPTION-TERIMABARANGTERKIRIMSEBAGIAN">Terima Barang Terkirim Sebagian</span></a>
							</li>
							<li>
								<a data-toggle="tab" href="#gagal-kirim" onclick="ChangeTypeFDJR('tidak terkirim')"><span name="CAPTION-TERIMABARANGAGALKIRIM">Terima Barang Gagal Kirim</span></a>
							</li>
							<li>
								<a data-toggle="tab" href="#barang-titipan" onclick="ChangeTypeFDJR('titipan')"><span name="CAPTION-TERIMABARANGTITIPAN">Terima Barang Titipan</span></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="do-retur" class="tab-pane fade in active">
								<div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="DO Retur" readonly />
										</div>
									</div>
								</div>
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<select id="filter_gudang_penerima_do_retur" class="form-control select2"></select>
										</div>
									</div>
								</div>
								<!-- <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                  <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                    <select class="form-control select2" id="filter_perusahaan_do_retur" name="filter_perusahaan_do_retur" onchange="GetPrincipleByPerusahaan(this.value,'do_retur')">
                      <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                      <?php foreach ($Perusahaan as $row) : ?>
                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_principle_do_retur" name="filter_principle_do_retur" onchange="GetCheckerByPrinciple(this.value,'do_retur')"></select>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_checker_do_retur" name="filter_checker_do_retur"></select>
                    </div>
                  </div>
                </div> -->
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<table id="tabledoretur" width="100%" class="table table-bordered">
										<thead>
											<tr class="bg-info">
												<th class="text-center"><span name="CAPTION-NO">No</span></th>
												<th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
												<th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
												<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
												<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
												<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
												<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
												<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
												<th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
												<th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div id="terkirim-sebagian" class="tab-pane fade">
								<div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_terkirim_sebagian" class="form-control" value="autogenerate" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="Terkirim Sebagian" readonly />
										</div>
									</div>
								</div>
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<select id="filter_gudang_penerima_terkirim_sebagian" class="form-control select2"></select>
										</div>
									</div>
								</div>
								<!-- <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                  <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                    <select class="form-control select2" id="filter_perusahaan_terkirim_sebagian" name="filter_perusahaan_terkirim_sebagian" onchange="GetPrincipleByPerusahaan(this.value,'terkirim_sebagian')">
                      <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                      <?php foreach ($Perusahaan as $row) : ?>
                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_principle_terkirim_sebagian" name="filter_principle_terkirim_sebagian" onchange="GetCheckerByPrinciple(this.value,'terkirim_sebagian')"></select>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_checker_terkirim_sebagian" name="filter_checker_terkirim_sebagian"></select>
                    </div>
                  </div>
                </div> -->
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<table id="tableterkirimsebagian" width="100%" class="table table-bordered">
										<thead>
											<tr class="bg-info">
												<th class="text-center"><span name="CAPTION-NO">No</span></th>
												<th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
												<th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
												<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
												<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
												<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
												<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
												<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
												<th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
												<th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div id="gagal-kirim" class="tab-pane fade">
								<div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="Gagal Kirim" readonly />
										</div>
									</div>
								</div>
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<select id="filter_gudang_penerima_gagal_kirim" class="form-control select2"></select>
										</div>
									</div>
								</div>
								<!-- <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                  <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                    <select class="form-control select2" id="filter_perusahaan_gagal_kirim" name="filter_perusahaan_gagal_kirim" onchange="GetPrincipleByPerusahaan(this.value,'gagal_kirim')">
                      <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                      <?php foreach ($Perusahaan as $row) : ?>
                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_principle_gagal_kirim" name="filter_principle_gagal_kirim" onchange="GetCheckerByPrinciple(this.value,'gagal_kirim')"></select>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_checker_gagal_kirim" name="filter_checker_gagal_kirim"></select>
                    </div>
                  </div>
                </div> -->
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<table id="tablegagalkirim" width="100%" class="table table-bordered">
										<thead>
											<tr class="bg-info">
												<th class="text-center"><span name="CAPTION-NO">No</span></th>
												<th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
												<th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
												<th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
												<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
												<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
												<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
												<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
												<th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
												<th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
							<div id="barang-titipan" class="tab-pane fade">
								<div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="Barang Titipan" readonly />
										</div>
									</div>
								</div>
								<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
										<div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
											<select id="filter_gudang_penerima_barang_titipan" class="form-control select2"></select>
										</div>
									</div>
								</div>
								<!-- <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                  <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                    <select class="form-control select2" id="filter_perusahaan_barang_titipan" name="filter_perusahaan_barang_titipan" onchange="GetPrincipleByPerusahaan(this.value,'barang_titipan')">
                      <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                      <?php foreach ($Perusahaan as $row) : ?>
                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_principle_barang_titipan" name="filter_principle_barang_titipan" onchange="GetCheckerByPrinciple(this.value,'barang_titipan')"></select>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                  <div class="form-group">
                    <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
                    <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                      <select class="form-control select2" id="filter_checker_barang_titipan" name="filter_checker_barang_titipan"></select>
                    </div>
                  </div>
                </div> -->
							</div>
						</div>
					</div>
					<div class="row" style="margin-top:10px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
							<button type="button" class="btn btn-success" id="btnpenerimaanperpalletdoretur"><span name="CAPTION-TAMBAHPENERIMAANPERPALLET">Tambah Penerimaan Per Pallet</span></button>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
							<div class="head-switch-global">
								<div class="switch-holder-global">
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
									<div class="col-md-6">
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
											<input type="text" class="form-control" id="kode_barcode" placeholder="PALLET/00000001">
										</div> -->
									</div>
									<div class="col-md-6">
										<div style="margin-top: 23px;">
											<button type="button" class="btn btn-success" id="check_kode"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label></button>
											<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
							<div class="from-group">
								<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
								<input type="text" class="form-control" id="txtpreviewscan" readonly />
							</div>
						</div>

					</div>
					<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
						<div class="panel panel-default col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="viewpalletdoretur">
							<div class="panel-heading" style="margin-left:-10px;margin-right:-10px;"><span name="CAPTION-PALLET">Pallet</span></div>
							<div class="panel-body form-horizontal form-label-left" style="margin-left:-20px;margin-right:-20px;">
								<table id="tablepalletdoretur" width="100%" class="table table-bordered">
									<thead>
										<tr class="bg-info">
											<th class="text-center"><span name="CAPTION-KODE">Kode</span></th>
											<th class="text-center" style="width: 50%;"><span name="CAPTION-TIPE">Jenis</span></th>
											<th class="text-center" style="width: 20%;"><span name="CAPTION-ACTION">Action</span></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
						<div class="panel panel-default col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="viewdetailpalletdoretur" style="display: none;">
							<div class="panel-heading" style="margin-left:-10px;margin-right:-10px;">Detail Pallet</div>
							<div class="panel-body form-horizontal form-label-left" style="margin-left:-15px;margin-right:-20px;">
								<input type="hidden" id="pallet_id" value="">
								<button type="button" class="btn btn-success" id="btn-tambah-sku-fdjr"><span name="CAPTION-TAMBAHSKUBYFDJR">Tambah SKU By FDJR</span></button>
								<button type="button" class="btn btn-success" id="btn-view-sku-fdjr" data-toggle="modal" data-target="#modal-sku"><span name="CAPTION-TAMBAHSKUTITIPAN">Tambah SKU Titipan</span></button>
								<div class="table-responsive">
									<table id="tabledetailpalletdoretur" width="100%" class="table table-bordered">
										<thead>
											<tr class="bg-info">
												<th class="text-center"><span name="CAPTION-PRINCIPLE">Principal</span></th>
												<th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
												<th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
												<th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
												<th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
												<th class="text-center"><span name="CAPTION-CAPTION-SKUREQEXPDATE">Exp Date</span></th>
												<th class="text-center"><span name="CAPTION-QTY">Jumlah</span></th>
												<th class="text-center"><span name="CAPTION-TIPE">Tipe</span></th>
												<th class="text-center"><span name="CAPTION-ACTION">Action</span></th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="row pull-right" style="margin-top:20px;">
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
						<button type="button" id="btnsavebtb" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-SAVE">Simpan</span> </button>
						<a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h4 class="modal-title"><label name="CAPTION-CARISKU">Cari SKU</label></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-4">
										<label name="CAPTION-SKUINDUK">SKU Induk</label>
										<input type="text" id="filter-sku-induk" name="filter_sku_induk" class="form-control input-sm" />
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKU">SKU</label>
										<input type="text" id="filter-sku-nama-produk" name="filter_sku_nama_produk" class="form-control input-sm" />
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-BRAND">Brand</label>
										<input type="text" id="filter-brand" name="filter_brand" class="form-control input-sm" />
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 text-right">
										<label>&nbsp;</label>
										<div>
											<button type="button" id="btn-search-sku" class="btn btn-success"><i class="fa fa-search"></i> Cari</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<table id="data-table-sku" width="100%" class="table table-bordered">
									<thead>
										<tr class="bg-primary">
											<th><input type="checkbox" name="select-sku" id="select-sku" value="1"></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-PRINCIPLE">Principle</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-BRAND">Brand</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-SKUINDUK">SKU Induk</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-SKUKODE">SKU Kode</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-SKUNAMA">Nama Barang</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
											<th class="text-center" style="color:white;"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-info" id="btn-choose-sku-multi"><span name="CAPTION-PILIH">Pilih</span></button>
						<button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbacksku"><span name="CAPTION-TUTUP">Tutup</span></button>
					</div>
				</div>
			</div>
		</div>
		<!-- /page content -->

		<div class="modal fade" id="modal_scan" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h4 class="modal-title" name="CAPTION-DETAILDATA">Detail Data</h4>
					</div>
					<div class="modal-body">
						<div id="select_kamera"></div>
						<div id="preview"></div>

						<div class="from-group" style="margin-top: 20px;">
							<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
							<input type="text" class="form-control" id="txtpreviewscan2" readonly />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-dark stop_scan" id="stop_scan"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>
					</div>
				</div>
			</div>
		</div>