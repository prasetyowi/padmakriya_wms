<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-MENUPERMINTAANPENGELUARANBARANG"></h3>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-PENCARIANPERMINTAANPENGELUARANBARANG"></label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALBUATPBB"></label>
							<div class="col-md-6 col-sm-6">
								<!-- <input type="text" id="datecreateppb" class="datepicker" value="" /> -->
								<input type="text" id="datecreateppb" class="form-control input-sm datepicker" name="datecreateppb" autocomplete="off">
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOPPB"></label>
							<div class="col-md-6 col-sm-6">
								<input type="text" class="form-control" name="txtnoppb" id="txtnoppb" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR"></label>
							<div class="col-md-6 col-sm-6">
								<input type="text" class="form-control" name="txtnofdjr" id="txtnofdjr" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align"></label>
							<div class="col-md-6 col-sm-6">
								<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;<label name="CAPTION-LOADING"></label></span>
								<button type="button" id="btnviewbkb" class="btn btn-primary"><i class="fa fa-search"></i> &nbsp; <label name="CAPTION-CARI"></label></button>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOPB"></label>
							<div class="col-md-6 col-sm-6">
								<input type="text" class="form-control" name="txtnopb" id="txtnopb" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPEPB"></label>
							<div class="col-md-6 col-sm-6">
								<select id="slctipepb" class="form-control" style="width:100%"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS"></label>
							<div class="col-md-6 col-sm-6">
								<select id="slcstatuspb" class="form-control" style="width:100%">
									<option value="all">All</option>
									<option value="Completed">Completed</option>
									<option value="In Progress">In Progress</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-DAFTARPERMINTAANPENGELUARANBARANG"></label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> &nbsp; <label name="CAPTION-MENUUTAMA"></label></a>
					<a href="<?php echo base_url('WMS/Distribusi/PengeluaranBarang/FormPengeluaranBarangMenu'); ?>" class="btn btn-primary" target="_blank"><i class="fa fa-plus"></i>&nbsp;<label name="CAPTION-BUATBARU"></label></a>
				</div>
				<div class="row">
					<table id="tablebkbmenu" width="100%" class="table table-striped">
						<thead>
							<tr>
								<th class="text-center" name="CAPTION-NOPPB"></th>
								<th class="text-center" name="CAPTION-TGLPPB"></th>
								<th class="text-center" name="CAPTION-NOFDJR"></th>
								<th class="text-center" name="CAPTION-TGLFDJR"></th>
								<th class="text-center" name="CAPTION-DRIVER"></th>
								<th class="text-center" name="CAPTION-NOKENDARAAN"></th>
								<th class="text-center" name="CAPTION-NOPB"></th>
								<th class="text-center" name="CAPTION-TGLPB"></th>
								<th class="text-center" name="CAPTION-TIPEPB"></th>
								<th class="text-center" name="CAPTION-AREA"></th>
								<th class="text-center" name="CAPTION-STATUSPPB"></th>
								<th class="text-center" name="CAPTION-TINDAKAN"></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalViewArea" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title"><label name="CAPTION-DETAILAREA">Detail Area</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table class="table table-striped" id="table-area">
								<thead>
									<tr class="bg-primary">
										<th class="text-center" style="color:white;" name="CAPTION-NO">NO </th>
										<th class="text-center" style="color:white;" name="CAPTION-AREA">Area </th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
				</div>
			</div>
		</div>
	</div>
</div>