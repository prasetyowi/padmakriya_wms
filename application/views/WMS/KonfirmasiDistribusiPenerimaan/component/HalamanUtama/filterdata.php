<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

			<div class="x_content">

				<ul class="nav nav-pills nopadding" role="depolist" id="deponavigation">
					<li id="tab1" class="active"><a href="#filter" role="tab" data-toggle="pill" onclick="handleRemoveData()">
							<h4>Filter</h4>
						</a></li>
					<li id="tab1"><a href="#scan" role="tab" data-toggle="pill" onclick="handleRemoveData()">
							<h4>Scan</h4>
						</a></li>
				</ul>
				<hr>
				<div class="nopadding tab-content" id="depolist" style="margin-top: 15px;">
					<div class="tab-pane active" class="form-horizontal form-label-left" id="filter">
						<div class="container mt-2">
							<div class="row">
								<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
									<label name="CAPTION-NODRAFTMUTASI">No. Draft Mutasi</label>
									<select class="form-control select2" name="no_draft_mutasi" id="no_draft_mutasi" required>
										<option value="">--Pilih No. Draft Mutasi--</option>
										<?php foreach ($draft_mutasi as $no) : ?>
											<option value="<?= $no->id ?>">
												<?= $no->kode ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
									<label name="CAPTION-LOKASITUJUAN">Lokasi Tujuan</label>
									<select class="form-control select2" name="gudang_tujuan" id="gudang_tujuan" required>
										<option value="">-- <label name="CAPTION-PILIHLOKASITUJUAN">Pilih Lokasi Tujuan</label>--</option>
										<?php foreach ($gudangs as $gudang) : ?>
											<option value="<?= $gudang->id ?>">
												<?= $gudang->nama ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
									<label>Status</label>
									<select class="form-control select2" name="status" id="status" required>
										<option value="">--<label name="CAPTION-PILIHSTATUS">Pilih Status</label>--</option>
										<option value="In Progress"><label name="CAPTION-INPROGRESS">In Progress</label></option>
										<option value="Completed"><label name="CAPTION-COMPLETED">Completed</label></option>
									</select>
								</div>

								<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
									<div style="margin-top: 24px;">
										<button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
										<span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
									</div>
									<!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" class="form-horizontal form-label-left" id="scan">
						<div class="row">
							<div class="col-xl-7 col-lg-7 col-md-7 col-xs-12">
								<div class="head-switch-global">
									<div class="switch-holder">
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
										<div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
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
												<input type="text" class="form-control" id="kode_barcode" placeholder="masukkan kode setelah tanda '/' pertama">
											</div> -->
										</div>
										<div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
											<div style="margin-top: 23px;">
												<button type="button" class="btn btn-success" id="check_kode"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label></button>
												<span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
								<div class="from-group">
									<label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
									<input type="text" class="form-control" id="txtpreviewscan" readonly />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>