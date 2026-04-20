<style>
	#tabledetailareaopname.hide2 tr>*:nth-child(2),
	#tabledetailareaopname.hide2 tr>*:nth-child(1) {
		display: none;
	}

	.modal-body {
		max-height: calc(100vh - 210px);
		overflow-x: auto;
		overflow-y: auto;
	}

	.error {
		border: 1px solid red !important;
	}

	.error .select2-selection {
		border: 1px solid red !important;
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

	.active-warehouse {
		background-color: green;
		color: #fff;
	}

	:root {
		--white: #ffffff;
		--light: #f0eff3;
		--black: #000000;
		--dark-blue: #1f2029;
		--dark-light: #353746;
		--grey: #4D4C4C;
		--yellow: #f8ab37;
		--green: #9ddf84;
	}

	#shape-placeholder {
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}

	#shape-placeholder2 {
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

	#shape-placeholder .checkbox-tools:checked+label,
	#shape-placeholder .checkbox-tools:not(:checked)+label {
		position: absolute;
		white-space: normal;
		word-break: break-all;
		padding: 10px;
		margin: 0 auto;
		margin-top: 5px;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 5px;
		text-align: center;
		border-radius: 4px;
		overflow: hidden;
		cursor: pointer;
		text-transform: uppercase;
		-webkit-transition: all 300ms linear;
		transition: all 300ms linear;
	}

	#shape-placeholder .checkbox-tools:not(:checked)+label {
		border: 1px solid var(--dark-light);
		background-color: var(--white);
		color: var(--dark-light);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder .checkbox-tools[data-active=true]+label {
		border: 1px solid var(--dark-light);
		background-color: var(--light);
		color: var(--dark-light) !important;
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder .checkbox-tools[data-active=false]+label {
		border: 1px solid var(--dark-light);
		background-color: var(--grey);
		color: var(--light) !important;
		cursor: not-allowed !important;
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder .checkbox-tools:checked+label {
		background-color: var(--green);
		border: 1px solid var(--dark-light);
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#shape-placeholder .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	/* new edit */
	#shape-placeholder2 .checkbox-tools:checked+label,
	#shape-placeholder2 .checkbox-tools:not(:checked)+label {
		position: absolute;
		white-space: normal;
		word-break: break-all;
		padding: 10px;
		margin: 0 auto;
		margin-top: 5px;
		margin-left: 5px;
		margin-right: 5px;
		margin-bottom: 5px;
		text-align: center;
		border-radius: 4px;
		overflow: hidden;
		cursor: pointer;
		text-transform: uppercase;
		-webkit-transition: all 300ms linear;
		transition: all 300ms linear;
	}

	#shape-placeholder2 .checkbox-tools:not(:checked)+label {
		border: 1px solid var(--dark-light);
		background-color: var(--white);
		color: var(--dark-light);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder2 .checkbox-tools[data-active=true]+label {
		border: 1px solid var(--dark-light);
		background-color: var(--light);
		color: var(--dark-light) !important;
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder2 .checkbox-tools[data-active=false]+label {
		border: 1px solid var(--dark-light);
		background-color: var(--grey);
		color: var(--light) !important;
		cursor: not-allowed !important;
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#shape-placeholder2 .checkbox-tools:checked+label {
		background-color: var(--green);
		border: 1px solid var(--dark-light);
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#shape-placeholder2 .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}


	#select_kamera_pallet,
	#select_kamera_sku {
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

	#select_kamera_pallet .checkbox-tools:checked+label,
	#select_kamera_pallet .checkbox-tools:not(:checked)+label,
	#select_kamera_sku .checkbox-tools:checked+label,
	#select_kamera_sku .checkbox-tools:not(:checked)+label {
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

	#select_kamera_pallet .checkbox-tools:not(:checked)+label,
	#select_kamera_sku .checkbox-tools:not(:checked)+label {
		background-color: var(--dark-light);
		color: var(--white);
		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
	}

	#select_kamera_pallet .checkbox-tools:checked+label,
	#select_kamera_sku .checkbox-tools:checked+label {
		background-color: transparent;
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera_pallet .checkbox-tools:not(:checked)+label:hover,
	#select_kamera_sku .checkbox-tools:not(:checked)+label:hover {
		box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
	}

	#select_kamera_pallet .checkbox-tools:checked+label::before,
	#select_kamera_pallet .checkbox-tools:not(:checked)+label::before,
	#select_kamera_sku .checkbox-tools:checked+label::before,
	#select_kamera_sku .checkbox-tools:not(:checked)+label::before {
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

	#select_kamera_pallet .checkbox-tools:checked+label .uil,
	#select_kamera_pallet .checkbox-tools:not(:checked)+label .uil,
	#select_kamera_sku .checkbox-tools:checked+label .uil,
	#select_kamera_sku .checkbox-tools:not(:checked)+label .uil {
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
		justify-content: space-between;
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

	@media (max-width: 800px) {

		#select_kamera_pallet .checkbox-tools:checked+label,
		#select_kamera_pallet .checkbox-tools:not(:checked)+label,
		#select_kamera_sku .checkbox-tools:checked+label,
		#select_kamera_sku .checkbox-tools:not(:checked)+label {
			flex: 100%;
		}
	}

	.toggle-button {
		margin: 0 0 1.5rem;
		box-sizing: border-box;
		font-size: 0;
		display: flex;
		flex-flow: row nowrap;
		justify-content: flex-start;
		align-items: stretch;
	}

	.toggle-button input {
		width: 0;
		height: 0;
		position: absolute;
		left: -9999px;
	}

	.toggle-button input+label {
		margin: 0;
		padding: 0.75rem 2rem;
		box-sizing: border-box;
		position: relative;
		display: inline-block;
		border: solid 1px #DDD;
		background-color: #FFF;
		font-size: 1.5rem;
		line-height: 140%;
		font-weight: 600;
		text-align: center;
		box-shadow: 0 0 0 rgba(255, 255, 255, 0);
		transition: border-color 0.15s ease-out, color 0.25s ease-out, background-color 0.15s ease-out, box-shadow 0.15s ease-out;
		flex: 0 0 50%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.toggle-button input+label:first-of-type {
		border-radius: 6px 0 0 6px;
		border-right: none;
		cursor: pointer;
	}

	.toggle-button input+label:last-of-type {
		border-radius: 0 6px 6px 0;
		border-left: none;
		cursor: pointer;
	}

	.toggle-button input:hover+label {
		border-color: #213140;
	}

	.toggle-button input:checked+label {
		background-color: #4B9DEA;
		color: #FFF;
		box-shadow: 0 0 10px rgba(102, 179, 251, 0.5);
		border-color: #4B9DEA;
		z-index: 1;
		cursor: pointer;
	}

	.toggle-button input:focus+label {
		outline: dotted 1px #CCC;
		outline-offset: 0.45rem;
	}

	@media (max-width: 800px) {
		.toggle-button input+label {
			padding: 0.75rem 0.25rem;
			flex: 0 0 50%;
			display: flex;
			justify-content: center;
			align-items: center;
		}
	}

	.tableFixHead {
		overflow: auto;
		height: 100px;
	}

	.tableFixHead thead th {
		position: sticky;
		top: 0;
		z-index: 1;
	}

	/* Just common table stuff. Really. */
	table {
		border-collapse: collapse;
		width: 100%;
	}

	th,
	td {
		padding: 8px 16px;
	}

	th {
		background: #eee;
	}
</style>
<div class="right_col" role="main" id="divUtama">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-PERSIAPANOPNAME">Persiapan Opname</h3>
			</div>
			<div style="float: right">
				<a class="btn btn-primary" id="btnshowtambahdata"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHPERSIAPANOPNAME">Tambah Persiapan Opname</label></a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-PENCARIANPERSIAPANOPNAME">Pencarian Persiapan Opname</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="x_title">
									<h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3>
									<div class="clearfix"></div>
								</div>
								<div class="container mt-2">
									<div class="row">
										<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label name="CAPTION-TAHUN">Tahun</label>
														<select class="form-control select2" name="vfiltertahun" id="vfiltertahun" required>
															<option value="">--Pilih Tahun--</option>
															<?php foreach ($rangeYear as $item) : ?>
																<option value="<?php echo $item ?>" <?= ($item == date('Y') ? "selected" : "") ?>>
																	<?php echo $item ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label name="CAPTION-BULAN">Bulan</label>
														<select id="vfilterbulan" name="vfilterbulan" class="form-control select2">
															<?php foreach ($rangeMonth as $key => $item) : ?>
																<option value="<?php echo $key ?>" <?= ($key == date('m') ? "selected" : "") ?>>
																	<?php echo $item ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
												<div class="form-group">
													<label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock</label>
													<select name="vfiltertipestock" id="vfiltertipestock" class="form-control select2">
														<option value=""><label name="CAPTION-ALL">All</label></option>
														<!-- <option value="Good Stock"><label name="CAPTION-GOODSTOCK">Good Stock</label></option>
                                                        <option value="Bad Stock"><label name="CAPTION-BADSTOCK">Bad Stock</label></option> -->
													</select>
												</div>
											</div>
											<div id="vshowFiltered" style="display: none">
												<div class="form-group">
													<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
													<select class="form-control select2 perusahaan" name="vfilterperusahaan" id="vfilterperusahaan" required></select>
												</div>
												<div class="form-group">
													<label name="CAPTION-PRINCIPLE">Principle</label>
													<select class="form-control select2 principle" name="vfilterprinciple" id="vfilterprinciple" required></select>
												</div>
											</div>
										</div>



										<div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
											<button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
											<span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
											<!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="list-data-form-search">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="container mt-2">
						<div class="row">
							<div class="x_content table-responsive">
								<table id="listpersiapanopname" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr>
											<!-- <th width="5%" class="text-center">#</th> -->
											<th width="7%" class="text-center"><label name="CAPTION-NO">NO</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-PERUSAHAAN">Perusahaan</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-PRINCIPLE">Principle</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-TIPEOPNAMEPLAN">Tipe Opname Plan</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-KODEDOKUMEN">Kode Dokumen</label></th>
											<th width="18%" class="text-center"><label name="CAPTION-TANGGALOPNAME">Nama Depo Detail</label></th>
											<th width="18%" class="text-center"><label name="CAPTION-STATUSOPNAME">Status Opname</label></th>
											<th width="6%" class="text-center"><label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock</label></th>
											<th width="10%" class="text-center"><label name="CAPTION-ACTION">Action</label></th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- form Tambah -->
<div class="right_col" role="main" id="divTambah" style="display:none ;">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-TAMBAHPERSIAPANOPNAME">Tambah Persiapan Opname</h3>
			</div>
			<!-- <div style="float: right">
                <a class="btn btn-primary" id="tambah-data"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHPERSIAPANOPNAME">Tambah Persiapan Opname</label></a>
            </div> -->
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-PENCARIANPERSIAPANOPNAME">Pencarian Persiapan Opname</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<!-- <div class="x_title">
      <div class="clearfix"></div>
    </div> -->
						<div class="container mt-2">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KODEDOKUMEN">Kode Dokumen</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" id="txtkddokumen" class="form-control" name="txtkddokumen" value="" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PENANGGUNGJAWAB">Penanggung Jawab</label>
										<div class="col-md-6 col-sm-6">
											<select id="filterpenanggungjawab" class="form-control select2" style="width:100%"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal </label>
										<div class="col-md-6 col-sm-6">
											<input type="date" id="filter_date" class="form-control" name="filter_date" value="<?= date('Y-m-d') ?>" min="<?= getLastTbgDepo() != null ? getLastTbgDepo() : '' ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-JENISGUDANG">Jenis Gudang</label>
										<div class="col-md-6 col-sm-6">
											<select id="filterjenisstock" class="form-control select2 jenisstock" style="width:100%">


											</select>

										</div>
									</div>
									<div id="showFiltered" style="display: none">
										<div class="form-group">
											<label class="col-form-label col-md-4 col-sm-4 label-align " name="CAPTION-PERUSAHAAN">Perusahaan</label>
											<div class="col-md-6 col-sm-6">
												<select id="filterperusahaan" class="form-control select2 perusahaan" style="width:100%"></select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
											<div class="col-md-6 col-sm-6">
												<select class="form-control select2 principle" name="filterprinciple" id="filterprinciple" required></select>
											</div>
										</div>
									</div>

								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align " name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label>
										<div class="col-md-6 col-sm-6">
											<select id="filtertipestockopname" class="form-control select2 tipestock" style="width:100%">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-UNITCABANG">Unit Cabang</label>
										<div class="col-md-6 col-sm-6">
											<select id="filterunitcabang" class="form-control select2 depo" style="width:100%" disabled></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
										<div class="col-md-6 col-sm-6">
											<textarea name="txtketerangan" id="txtketerangan" cols="30" class="form-control keterangan" placeholder="keterangan" style="width: 100%;height: 165px"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSPENGAJUAN">Status </label>
										<div class="col-md-6 col-sm-6">
											<input type="text" id="txtstatus" class="form-control" name="txtstatus" value="Draft" readonly />
											<input type="checkbox" name="" style="margin-top:10px;transform: scale(1.5)" id="cbstatus" value="In Progress Approval">
											<span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
											<!-- <div class="form-group">
                            <input type="checkbox" style="margin-top: 30px;transform: scale(1.5)" name="koreksi_draft_approval" id="koreksi_draft_approval" value="In Progress Approval">
                            <span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
                        </div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="x_panel">
			<div class="form-horizontal form-label-left">

				<div class="row">
					<div class="col-lg-5">
						<div class="panel-heading"><label name="CAPTION-AREAOPNAME">Area Opname</label></div>
						<!-- <div class="col-md-7 col-xl-7 col-lg-7 col-sm-12 col-xs-12 justify-content-center"> -->
						<!-- <div class="col-md-7 col-xl-7 col-lg-7 col-sm-12 col-xs-12 justify-content-center"> -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divDepoDetail"></div>
						<!-- </div> -->
					</div>

					<!-- </div> -->
					<div class="col-lg-7">
						<div class="panel-heading"><label name="CAPTION-TABLEDETAILAREAOPNAME">Table Detail Area Opname</label></div>
						<div class="container">
							<div align="center">
								<input type="text" name="search" id="search" class="form-control" placeholder="search" />
							</div>
							<div class="x_content table-responsive" style="height: 500px;">
								<table id="tabledetailareaopname" class="table table-striped tableFixHead">
									<thead>
										<tr>
											<th class="text-center" width="5%">
												<input checked disabled type="checkbox" id="check-all-pilih-sj" class="cball-filled" style="transform: scale(1.5)" onchange="checkAll(this,'filled')" />
											</th>
											<th name="CAPTION-PILIH">Pilih</th>
											<th name="CAPTION-PERUSAHAAN">Perusahaan</th>
											<th name="CAPTION-AREA">Area</th>
											<th name="CAPTION-KODELOKASI">Kode Lokasi</th>
											<th name="CAPTION-PRINCIPLE">Principle</th>
											<th name="CAPTION-STATUSTERISI">Status Terisi</th>
											<th name="CAPTION-JENISSTOCK">Jenis Stock</th>
											<th name="CAPTION-JUMLAHPALETTE">Jumlah Pallete</th>

										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div style="float: right;padding:10px">
						<a class="btn btn-primary" id="tambah-data"><i class="fas fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></a>
					</div>
					<div style="float: right;padding:10px">
						<a class="btn btn-dark" id="kembali"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">KEMBALI</label></a>
					</div>
				</div>
				<br>
				<!-- <div class="row">
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <label name="CAPTION-MENUUTAMA">Menu Utama</label></a>
                </div> -->
			</div>
		</div>

	</div>
</div>

<!-- form edit -->
<div class="right_col" role="main" id="divEdit" style="display:none ;">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-VIEWPERSIAPANOPNAME">Lihat Persiapan Opname</h3>
			</div>
			<!-- <div style="float: right">
                <a class="btn btn-primary" id="tambah-data"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHPERSIAPANOPNAME">Tambah Persiapan Opname</label></a>
            </div> -->
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-VIEWPERSIAPANOPNAME">Lihat Persiapan Opname</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<!-- <div class="x_title">
      <div class="clearfix"></div>
    </div> -->
						<div class="container mt-2">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KODEDOKUMEN">Kode Dokumen</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" id="etxtkddokumen" class="form-control" name="etxtkddokumen" value="" readonly disabled />
											<input type="hidden" id="lastUpdateTgl" class="form-control" name="lastUpdateTgl" value="" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PENANGGUNGJAWAB">Penanggung Jawab</label>
										<div class="col-md-6 col-sm-6">
											<select id="efilterpenanggungjawab" class="form-control select2" style="width:100%" disabled></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal </label>
										<div class="col-md-6 col-sm-6">
											<input type="date" id="efilter_date" class="form-control" name="efilter_date" value="" min="<?= getLastTbgDepo() != null ? getLastTbgDepo() : '' ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-JENISSTOCK">Jenis Stock</label>
										<div class="col-md-6 col-sm-6">
											<select id="efilterjenisstock" class="form-control select2 jenisstock2" style="width:100%" disabled>

											</select>
										</div>
									</div>
									<div id="eshowFiltered" style="display: none">
										<div class="form-group">
											<label class="col-form-label col-md-4 col-sm-4 label-align " name="CAPTION-PERUSAHAAN">Perusahaan</label>
											<div class="col-md-6 col-sm-6">
												<select id="efilterperusahaan" class="form-control select2 perusahaan2" style="width:100%" disabled></select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
											<div class="col-md-6 col-sm-6">
												<select class="form-control select2 principle2" name="efilterprinciple" id="efilterprinciple" required disabled></select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align " name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label>
										<div class="col-md-6 col-sm-6">
											<select id="efiltertipestockopname" class="form-control select2 tipestock2" style="width:100%" disabled readonly>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-UNITCABANG">Unit Cabang</label>
										<div class="col-md-6 col-sm-6">
											<select id="efilterunitcabang" class="form-control select2 depo" style="width:100%" disabled></select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
										<div class="col-md-6 col-sm-6">
											<textarea name="etxtketerangan" id="etxtketerangan" cols="30" class="form-control keterangan" placeholder="keterangan" style="width: 100%;height: 165px" disabled></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSPENGAJUAN">Status </label>
										<div class="col-md-6 col-sm-6">
											<input type="text" id="etxtstatus" class="form-control" name="etxtstatus" value="Draft" readonly />
											<input type="checkbox" name="" style="margin-top:10px;transform: scale(1.5)" id="ecbstatus" value="In Progress Approval">
											<span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="x_panel" id="x_panel">
			<div class="form-horizontal form-label-left">

				<div class="row">

					<div class="col-lg-5">
						<div class="panel-heading"><label name="CAPTION-AREAOPNAME">Area Opname</label></div>
						<!-- <div class="col-md-7 col-xl-7 col-lg-7 col-sm-12 col-xs-12 justify-content-center"> -->
						<!-- <div class="col-md-7 col-xl-7 col-lg-7 col-sm-12 col-xs-12 justify-content-center"> -->
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divDepoDetail2"></div>
						<!-- </div> -->
					</div>

					<!-- </div> -->
					<div class="col-lg-7">
						<div class="container">
							<div class="panel-heading"><label name="CAPTION-TABLEDETAILAREAOPNAME">Table Detail Area Opname</label></div>
							<div align="center">
								<input type="text" name="search" id="search" class="form-control" placeholder="search" />
							</div>
							<div class="x_content table-responsive" style="height: 500px;">
								<table id="edittabledetailareaopname" class="table table-striped tableFixHead">
									<thead>
										<tr>
											<th class="text-center" width="5%">
												<input checked disabled type="checkbox" id="check-all-pilih-sj" class="cball-filled" style="transform: scale(1.5)" onchange="checkAll(this,'filled')" />
											</th>
											<th name="CAPTION-PILIH">Pilih</th>
											<th name="CAPTION-PERUSAHAAN">Perusahaan</th>
											<th name="CAPTION-AREA">Area</th>
											<th name="CAPTION-KODELOKASI">Kode Lokasi</th>
											<th name="CAPTION-PRINCIPLE">Principle</th>
											<th name="CAPTION-STATUSTERISI">Status Terisi</th>
											<th name="CAPTION-JENISSTOCK">Jenis Stock</th>
											<th name="CAPTION-JUMLAHPALETTE">Jumlah Pallete</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div style="float: right;padding:10px">
						<a class="btn btn-primary" id="edit_data"><i class="fas fa-plus"></i> <label name="CAPTION-EDIT">EDIT</label></a>
					</div>
					<div style="float: right;padding:10px">
						<a class="btn btn-dark" id="kembali"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">KEMBALI</label></a>
					</div>
				</div>
				<br>
				<!-- <div class="row">


                    <a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <label name="CAPTION-MENUUTAMA">Menu Utama</label></a>
                </div> -->
			</div>
		</div>

		<div class="x_panel-view" id="x_panel-view" style="display: none;">
			<div class="form-horizontal form-label-left">

				<div class="row">


					<!-- </div> -->

					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="container mt-2">
								<div class="row" style="margin-top: 10px;">
									<div class="x_content table-responsive">
										<table id="viewtabledetailareaopname" width="100%" class="table table-striped">
											<thead>
												<th name="CAPTION-NO">No</th>
												<th name="CAPTION-NAMADEPO">NAMA DEPO</th>
												<th name="CAPTION-NAMAAREADEPO">NAMA AREA DEPO</th>
												<th name="CAPTION-NAMARAK">NAMA RAK</th>
												<th name="CAPTION-NAMADETAILRAK">NAMA DETAIL RAK</th>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- <div style="float: right;padding:10px">
                        <a class="btn btn-primary" id="edit_data"><i class="fas fa-plus"></i> <label name="CAPTION-EDIT">EDIT</label></a>
                    </div> -->
					<div style="float: right;padding:10px">
						<a class="btn btn-dark" id="kembali"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">KEMBALI</label></a>
					</div>
				</div>
				<br>
				<!-- <div class="row">


                    <a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <label name="CAPTION-MENUUTAMA">Menu Utama</label></a>
                </div> -->
			</div>
		</div>

	</div>
</div>