<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu Varian</h3>
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
			<button type="button" id="btnaddnewvarian" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Varian</button>
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablevarianmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>Status</th>
					<th>Opsi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				</table>
		  <!--/div-->
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablevariandetailmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Nama</th>
					<th>Satuan Unit</th>
					<th>Opsi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				</table>
		  <!--/div-->
		  </div>
		  
		  <div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
		  </div>
		  
		  <!-- Modal untuk menampilkan Add Template !-->
		  <div class="modal fade" id="previewaddnewvarian" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Tambah Varian</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtvarian_kode">Kode </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtvarian_kode" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtvarian_nama">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtvarian_nama" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="chvarian_is_wajib">Wajib </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<input type="checkbox" id="chvarian_is_wajib" class="checkbox_form" value="" />
										</div>
									</div>
								</div>
								
								<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
									<div class="form-group">
										<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
											<button type="button" id="btnaddvariandetail" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail Varian</button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding">
											<table width="100%">
											<tr>
												<td width="60%" align="center"><h5><strong>Nama Varian Detail</strong></h5></td>
												<td width="30%" align="center"><h5><strong>Satuan Unit</strong></h5></td>
												<td width="10%" align="center"><h5><strong>Opsi</strong></h5></td>
											</tr>
											</table>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
											<table id="tableaddvariandetail" class="table table-striped table-sm" width="100%">
											
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewvarian">Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletevarian" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Varian</label></h4>
							<input type="hidden" id="hddeletevarian_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda yakin untuk menghapus Varian </label> <strong><label id="lbdeletevarian_nama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletevarian">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletevarian">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletevariandetail" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Detail Varian</label></h4>
							<input type="hidden" id="hddeletevariandetail_varian_id" />
							<input type="hidden" id="hddeletevariandetail_varian_detail_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Detail Varian </label> <strong><label id="lbdeletevariandetail_varian_detail_nama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletevariandetail">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletevariandetail">Tidak</button>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Modal untuk menampilkan Update Template !-->
			<div class="modal fade" id="previewupdatevarian" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Varian</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatevarian_kode">Kode </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatevarian_kode" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatevarian_nama">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatevarian_nama" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="chupdatevarian_is_wajib">Wajib </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<input type="checkbox" id="chupdatevarian_is_wajib" class="checkbox_form" value="" />
										</div>
									</div>
									<input type="hidden" id="txtupdatevarian_id" />
								</div>
								
								<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
									<div class="form-group">
										<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
											<button type="button" id="btnaddupdatevariandetail" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail Varian</button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding">
											<table width="100%">
											<tr>
												<td width="60%" align="center"><h5><strong>Nama Varian Detail</strong></h5></td>
												<td width="30%" align="center"><h5><strong>Satuan Unit</strong></h5></td>
												<td width="10%" align="center"><h5><strong>Opsi</strong></h5></td>
											</tr>
											</table>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
											<table id="tableupdatevariandetail" class="table table-striped table-sm" width="100%">
											
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveupdatevarian">Ubah</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdatevarian">Kembali</button>
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