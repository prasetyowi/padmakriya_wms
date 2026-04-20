<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu Depo</h3>
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
			<button type="button" id="btnaddnewdepo" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Depo</button>
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tabledepomenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>Alamat</th>
					<th>Koordinat</th>
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
				<table id="tabledepodetailmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Detail Depo</th>
					<th>Catatan</th>
					<th>Depo Penjualan</th>
					<th>Depo Penerima</th>
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
		  <div class="modal fade" id="previewaddnewdepo" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Tambah Depo</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_kode">Kode </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtdepo_kode" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_nama">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtdepo_nama" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_alamat">Alamat </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtdepo_alamat" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_status">Aktif </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<input type="checkbox" id="txtdepo_status" class="checkbox_form" value="" />
										</div>
									</div>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_latitude">Koordinat X </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtdepo_latitude" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtdepo_longitude">Koordinat Y </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="number" id="txtdepo_longitude" class="form-control" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
											<button type="button" id="btnadddepo_detail" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail Depo</button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding">
											<table width="100%">
											<tr>
												<td width="20%" align="center"><h5><strong>Detail Depo</strong></h5></td>
												<td width="20%" align="center"><h5><strong>Catatan</strong></h5></td>
												<td width="20%" align="center"><h5><strong>Perusahaan</strong></h5></td>
												<td width="15%" align="center"><h5><strong>Penjualan</strong></h5></td>
												<td width="15%" align="center"><h5><strong>Penerima</strong></h5></td>
												<td width="10%" align="center"><h5><strong>Opsi</strong></h5></td>
											</tr>
											</table>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
											<table id="tableadddepodetailmenu" class="table table-striped table-sm" width="100%">
											
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewdepo">Save</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Back</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletedepo" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Depo</label></h4>
							<input type="hidden" id="hddeletedepo_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Depo </label> <strong><label id="lbdeletedepo"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletedepo">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletedepo">No</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletedepo_detail" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Detail Depo</label></h4>
							<input type="hidden" id="hddeletedepo_detail_depo_id" />
							<input type="hidden" id="hddeletedepo_detail_depo_detail_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Detail Depo </label> <strong><label id="lbdeletedepo_detail"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletedepo_detail">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletedepo_detail">No</button>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Modal untuk menampilkan Update Template !-->
			<div class="modal fade" id="previewupdatedepo" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Depo</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatekode_depo">Kode </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatekode_depo" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatenama_depo">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatenama_depo" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatedepo_alamat">Alamat </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatedepo_alamat" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="chupdatedepo_status">Aktif </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<input type="checkbox" id="chupdatedepo_status" class="checkbox_form" value="" />
										</div>
									</div>
									<input type="hidden" id="txtupdatedepo_id" />
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatedepo_lat">Koordinat X </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtupdatedepo_lat" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtupdatedepo_lon">Koordinat Y </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="number" id="txtupdatedepo_lon" class="form-control" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
											<button type="button" id="btnaddupdatedepo_detail" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail Depo</button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding">
											<table width="100%">
											<tr>
												<td width="20%" align="center"><h5><strong>Detail Depo</strong></h5></td>
												<td width="20%" align="center"><h5><strong>Catatan</strong></h5></td>
												<td width="20%" align="center"><h5><strong>Perusahaan</strong></h5></td>
												<td width="15%" align="center"><h5><strong>Penjualan</strong></h5></td>
												<td width="15%" align="center"><h5><strong>Penerima</strong></h5></td>
												<td width="10%" align="center"><h5><strong>Opsi</strong></h5></td>
											</tr>
											</table>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
											<table id="updatetableadddepodetailmenu" class="table table-striped table-sm" width="100%">
											
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveupdatedepo">Update</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdatedepo">Back</button>
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