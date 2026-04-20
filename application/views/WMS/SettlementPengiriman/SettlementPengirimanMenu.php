<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-SETTLEMENTPENGIRIMAN">Settlement Pengiriman</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-PENCARIANSURATTUGASPENGIRIMAN">Pencarian Surat Tugas
					Pengiriman</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<label class="col-form-label label-align" name="CAPTION-TANGGALFDJR">Tanggal FDJR</label>
							<input type="date" id="filter_fdjr_date" class="form-control" name="filter_fdjr_date" value="<?= date('Y-m-d') ?>" />
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<label class="col-form-label label-align" name="CAPTION-NOFDJR">No FDJR</label>
							<input type="text" class="form-control" name="filter_fdjr_no" id="filter_fdjr_no" required>
						</div>
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<label class="col-form-label" name="CAPTION-DRIVER">Driver</label>
							<select id="filter_fdjr_driver" class="form-control select2" style="width:100%">
								<option value="">** <span name="CAPTION-DRIVER">Driver</span> **</option>
								<?php foreach ($Driver as $i => $value) : ?>
									<option value="<?= $value['karyawan_id'] ?>"><?= $value['karyawan_nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
							<button type="button" id="btnviewfdjr" class="btn btn-primary"><i class="fa fa-search"></i>
								<span name="CAPTION-CARI">Cari</span></button>
							<!-- <button type="button" id="btnsettlement" class="btn btn-success"><i class="fa fa-save"></i>
								Settlement</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body form-horizontal form-label-left">
			<div class="row">
				<div class="col-lg-12">
					<div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<button type="button" id="btnsettlement" class="btn btn-success"><i class="fa fa-save"></i>
							Settlement</button>
					</div>
				</div>
			</div>
			<br>
			<div class="hr"></div>
			<!-- <div class="table-responsive"> -->
			<div class="row">
				<div class="col-xs-12">
					<div class="x_content table-responsive">
						<table id="tablefdjrmenu" width="100%" class="table table-striped">
							<thead>
								<tr>
									<th><input type="checkbox" id="select-fdjr" value="1"></th>
									<th name="CAPTION-TANGGALKIRIM" class="text-center">Tgl Kirim</th>
									<th name="CAPTION-DRIVER" class="text-center">Driver</th>
									<th name="CAPTION-STATUSFDJR" class="text-center">Status FDJR</th>
									<th name="CAPTION-TIPEFDJR" class="text-center">Tipe FDJR</th>
									<th name="CAPTION-NOFDJR" class="text-center">No FDJR</th>
									<th name="CAPTION-NOPB" class="text-center">No PB</th>
									<th name="CAPTION-NOPPB" class="text-center">No PPB</th>
									<th name="CAPTION-NOSERAHTERIMA" class="text-center">No Serah Terima</th>
									<th name="CAPTION-STATUSSETTLEMENTBARANG" class="text-center">Status Settlement Barang
									</th>
									<th name="CAPTION-STATUSSETTLEMENTTUNAI" class="text-center">Status Settlement Tunai
									</th>
									<th name="CAPTION-STATUSSETTLEMENT" class="text-center">Status Settlement</th>
									<th name="CAPTION-ACTION" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- </div> -->
			<div class="row">
				<a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <span name="CAPTION-MENUUTAMA">Menu Utama</span></a>
			</div>
		</div>
	</div>
</div>
</div>