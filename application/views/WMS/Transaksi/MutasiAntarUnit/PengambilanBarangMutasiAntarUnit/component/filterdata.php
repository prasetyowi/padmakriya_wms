<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3>
				<div class="clearfix"></div>
			</div>
			<div class="container mt-2">
				<div class="row">
					<div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-TANGGAL">Tanggal</label>
							<input type="text" id="filter_koreksi_tgl" class="form-control input-sm datepicker" name="filter_koreksi_tgl" autocomplete="off">
						</div>
					</div>

					<div class="col-md-2 col-lg-2 col-xl-2 col-xs-12">
						<div class="form-group">
							<label>No. Dokumen</label>
							<select id="filter_no_koreksi" name="filter_no_koreksi" class="form-control select2">
								<option value="all"><label name="CAPTION-ALL">All</label></option>
								<?php foreach ($stock_corrections as $stock_correction) { ?>
									<option value="<?= $stock_correction->id ?>"><?= $stock_correction->kode ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
						<div class="form-group">
							<label>Ekspedisi</label>
							<select id="filterEkspedisiHomePage" class="form-control select2">
								<option value="all"><label name="CAPTION-ALL">All</label></option>
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
								<option value="all"><label name="CAPTION-ALL">All</label></option>
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
								<option value="all"><label name="CAPTION-ALL">All</label></option>
								<?php foreach ($vehicles as $vehicle) : ?>
									<option value="<?= $vehicle->kendaraan_id ?>"><?= $vehicle->kendaraan_model ?> - <?= $vehicle->kendaraan_nopol ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-STATUS">Status</label>
							<select name="status" id="filter_koreksi_status" class="form-control select2">
								<option value="all"><label name="CAPTION-ALL">All</label></option>
								<option value="Draft"><label>Draft</label></option>
								<option value="In Progress Approval"><label>In Progress Approval</label></option>
								<option value="In Progress Picking"><label>In Progress Picking</label></option>
								<option value="Completed"><label>Completed</label></option>
							</select>
						</div>
					</div>

					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div style="margin-top: 24px;">
							<button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
							<span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>