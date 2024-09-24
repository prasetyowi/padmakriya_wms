<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Persiapan Mutasi Antar Unit</h3>
			</div>
			<div style="float: right">
				<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/form?mode=create') ?>" target="_BLANK" class="btn-submit btn btn-primary"><i class="fa fa-plus"></i> <span style="color:white;">Tambah</span></a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
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
										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label name="CAPTION-TANGGAL">Tanggal</label>
												<input type="text" id="filterTanggalHomePage" class="form-control" name="filterTanggalHomePage" value="" />
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label>Ekspedisi</label>
												<select id="filterEkspedisiHomePage" class="form-control select2">
													<option value="all">--All--</option>
													<?php foreach ($ekspedisis as $ekspedisi) : ?>
														<option value="<?= $ekspedisi->ekspedisi_id ?>"><?= $ekspedisi->ekspedisi_kode ?> - <?= $ekspedisi->ekspedisi_nama ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label>Pengemudi</label>
												<select id="filterPengemudiHomePage" class="form-control select2">
													<option value="all">--All--</option>
													<?php foreach ($drivers as $driver) : ?>
														<option value="<?= $driver->karyawan_id ?>"><?= $driver->karyawan_nama ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label>Kendaraan - Nopol</label>
												<select id="filterKendaraanHomePage" class="form-control select2">
													<option value="all">--All--</option>
													<?php foreach ($vehicles as $vehicle) : ?>
														<option value="<?= $vehicle->kendaraan_id ?>"><?= $vehicle->kendaraan_model ?> - <?= $vehicle->kendaraan_nopol ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label name="CAPTION-STATUS">Status</label>
												<select name="status" id="filterStatusHomePage" class="form-control select2">
													<option value="all"><label name="CAPTION-ALL">All</label></option>
													<option value="Draft"><label>Draft</label></option>
													<option value="In Progress Approval"><label>In Progress Approval</label></option>
													<option value="Approved"><label>Approved</label></option>
												</select>
											</div>
										</div>

									</div>
									<div class="item form-group">
										<button class="btn btn-primary" id="btnsearchdata" onclick="handlerFilterData()"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
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
		<div class="row" id="list-data-form-search">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="container mt-2">
						<div class="row">
							<div class="x_content table-responsive">
								<table id="listDataFilter" width="100%" class="table table-striped">
									<thead>
										<tr>
											<!-- <th width="5%" class="text-center">#</th> -->
											<th width="7%" class="text-center"><label name="CAPTION-NO">NO</label></th>
											<th width="15%" class="text-center"><label>Kode Dokumen</label></th>
											<th width="15%" class="text-center"><label>Ekspedisi</label></th>
											<th width="15%" class="text-center"><label>Pengemudi</label></th>
											<th width="15%" class="text-center"><label>Kendaraan</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-STATUS">Status</label></th>
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