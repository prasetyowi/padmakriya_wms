<?php $this->load->view("WMS/ValidasiPengeluaranBarang/Component/Script/Style/index") ?>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-135012000">Validasi Pengeluaran Barang</h3>
			</div>
			<div style="float: right">
				<!-- <a href="<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/form') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHDATA">Tambah Data</label></a> -->
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-CARIVALIDASIPENGELUARANBARANG">Pencarian Validasi Pengeluaran</label></div>
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
										<div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label name="CAPTION-TANGGAL">Tanggal</label>
														<input type="text" id="vfiltertqanggalrange" class="form-control input-sm datepicker" name="vfiltertqanggalrange" autocomplete="off">
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label name="CAPTION-KODEPICKINGVALIDASI">Kode Picking Validation</label>
														<select id="vkodePickingValidation" name="vkodePickingValidation" class="form-control select2">
															<option value="">--Pilih Kode Picking Validation--</option>
															<?php foreach ($dataValidation as $key => $value) : ?>
																<option value="<?= $value->id ?>"><?= $value->kode ?> - <?= $value->driver ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>

												<div class="col-md-4">
													<div class="form-group">
														<label name="CAPTION-STATUS">Status</label>
														<select id="vstatus" name="vstatus" class="form-control select2">
															<option value="all">--All--</option>
															<option value="Draft">Draft</option>
															<option value="In Progress Confirmation">In Progress Confirmation</option>
															<option value="Confirmation Done">Confirmation Done</option>
														</select>
													</div>
												</div>
											</div>

										</div>

										<div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
											<button class="btn btn-primary" onclick="handlerFilterDataPickingValidation()"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
											<span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="divListDataValidasiBarang">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="container mt-2">
						<div class="row">
							<div class="x_content table-responsive">
								<table id="listDataValidasiBarang" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr>
											<!-- <th width="5%" class="text-center">#</th> -->
											<th width="7%" class="text-center"><label name="CAPTION-NO">NO</label></th>
											<th width="10%" class="text-center"><label name="CAPTION-TANGGAL">Tanggal</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-KODE">Kode</label></th>
											<th width="15%" class="text-center"><label name="CAPTION-KODEFDJR">Kode FDJR</label></th>
											<th width="10%" class="text-center"><label name="CAPTION-KODEPPB">Kode PPB</label></th>
											<th width="10%" class="text-center"><label name="CAPTION-KODESERAHTERIMA">Kode Serah Terima</label></th>
											<th width="13%" class="text-center"><label>Status</label></th>
											<th width="10%" class="text-center"><label>Status Sync</label></th>
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

<!-- /page content -->