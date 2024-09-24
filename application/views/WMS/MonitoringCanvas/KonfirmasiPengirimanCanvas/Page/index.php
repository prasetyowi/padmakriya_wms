<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Konfirmasi Pengiriman Canvas</h3>
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
												<input type="text" id="filter_tgl_request" class="form-control" name="filter_tgl_request" value="" />
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label>Kode FDJR</label>
												<input type="text" class="form-control" autocomplete="off" id="kodeFdjr" onkeyup="handlerGetKodeFDJR(event)" placeholder="All" data-fdjrId="all">
												<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
													<table class="table table-striped table-sm table-hover" id="table-fixed" width="100%">
														<tbody id="konten-table"></tbody>
													</table>
												</div>
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label name="CAPTION-SALES">Sales</label>
												<select id="sales" class="form-control select2">
													<option value="all">--<label name="CAPTION-SALES">Sales</label> --</option>
													<?php foreach ($sales as $key => $sale) { ?>
														<option value="<?= $sale->client_pt_id ?>"><?= $sale->client_pt_nama ?></option>
													<?php } ?>
												</select>
											</div>
										</div>

										<div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
											<div class="form-group">
												<label name="CAPTION-STATUS">Status</label>
												<select name="status" id="status" class="form-control select2">
													<option value="all"><label name="CAPTION-ALL">All</label></option>
													<!-- <option value="Draft"><label>Draft</label></option>
													<option value="In Progress Approval"><label>In Progress Approval</label></option>
													<option value="Approved"><label>Approved</label></option> -->
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
											<th widtd="7%" class="text-center"><label name="CAPTION-NO">NO</label></th>
											<th widtd="15%" class="text-center"><label>Tanggal FDJR</label></th>
											<th widtd="15%" class="text-center"><label name="CAPTION-SALES">Sales</label></th>
											<th widtd="15%" class="text-center"><label>Tipe FDJR</label></th>
											<th widtd="15%" class="text-center"><label>Status FDJR</label></th>
											<th widtd="15%" class="text-center"><label>Kode FDJR</label></th>
											<th widtd="15%" class="text-center"><label>Kode PB</label></th>
											<th widtd="15%" class="text-center"><label>Kode PPB</label></th>
											<th widtd="15%" class="text-center"><label>Kode Serah Terima</label></th>
											<th widtd="15%" class="text-center"><label name="CAPTION-STATUS">Status</label></th>
											<th widtd="10%" class="text-center"><label name="CAPTION-ACTION">Action</label></th>
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