<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3 name="CAPTION-MENUKARYAWAN">Menu Karyawan</h3>
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
			<button type="button" id="btnaddnewkaryawan" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> <label name="CAPTION-TAMBAHARYAWAN">Tambah Karyawan</label></button>
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablekaryawanmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th name="CAPTION-NAMA">Nama</th>
					<th name="CAPTION-KONTAK">Kontak</th>
					<th name="CAPTION-EMAIL">Email</th>
					<th name="CAPTION-OPSI">Opsi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				</table>
		  <!--/div-->
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablekaryawandetailmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th name="CAPTION-RESIPIEN">Resipien</th>
					<th name="CAPTION-ALAMAT">Alamat</th>
					<th name="CAPTION-KODEPOS">Kode Pos</th>
					<th name="CAPTION-KONTAK">Kontak</th>
					<th name="CAPTION-DEFAULT">Default</th>
					<th name="CAPTION-OPSI">Opsi</th>
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
		  <div class="modal fade" id="previewaddnewkaryawan" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label name="CAPTION-TAMBAHKARYAWAN">Tambah Karyawan</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<input type="hidden" id="txtselectKodePos_ID" />
									<input type="hidden" id="txtselectKelurahan_ID" />
									<input type="hidden" id="txtselectKelurahan_Nama" />
									<input type="hidden" id="txtselectKodePos" />
									
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_Name" name="CAPTION-NAMA">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtKaryawan_Name" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_No" name="CAPTION-NOKARYAWAN">No. Karyawan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtKaryawan_No" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="cbperusahaan" name="CAPTION-PERUSAHAAN">Perusahaan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="cbperusahaan" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_UserName" name="CAPTION-NAMAPENGGUNA">User Name </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtKaryawan_UserName" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_Pass" name="CAPTION-KATASANDI">Kata Sandi </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="password" id="txtKaryawan_Pass" class="form-control" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_Phone" name="CAPTION-KONTAK">Kontak </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtKaryawan_Phone" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_Email" name="CAPTION-EMAIL">Email </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="number" id="txtKaryawan_Email" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="txtKaryawan_Birthdate" name="CAPTION-TANGGALLAHIR">Tanggal Lahir</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtKaryawan_Birthdate" class="form-control" /></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="form-group">
									<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
										<button type="button" id="btnaddkaryawandetail" class="btn btn-primary"><i class="fa fa-plus"></i> <label name="CAPTION-TAMBAHDETAILKARYAWAN">Tambah Detail Karyawan</label></button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding divtablehead">
									<table width="100%">
									<tr>
										<td width="20%" align="center"><h5><strong><label name="CAPTION-TAMBAHKARYAWAN">Resipien</label></strong></h5></td>
										<td width="20%" align="center"><h5><strong><label name="CAPTION-KODEPOS">Kode Pos</label></strong></h5></td>
										<td width="20%" align="center"><h5><strong><label name="CAPTION-ALAMAT">Alamat</label></strong></h5></td>
										<td width="15%" align="center"><h5><strong><label name="CAPTION-KONTAK">Kontak</label></strong></h5></td>
										<td width="15%" align="center"><h5><strong><label name="CAPTION-DEFAULT">Default</label></strong></h5></td>
										<td width="10%" align="center"><h5><strong><label name="CAPTION-OPSI">Opsi</label></strong></h5></td>
									</tr>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
									<table id="tableaddkaryawandetail" class="table table-striped table-sm" width="100%">
									
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewkaryawan"><label name="CAPTION-SAVE">Save</label></button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback"><label name="CAPTION-BACK">Back</label></button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletekaryawan" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label name="CAPTION-HAPUSKARYAWAN">Hapus Karyawan</label></h4>
							<input type="hidden" id="hddeletekaryawanid" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Karyawan </label> <strong><label id="lbdeletekaryawan"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletekaryawan">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletekaryawan">No</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletekaryawan1" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Detail Karyawan</label></h4>
							<input type="hidden" id="hddeletekaryawandetail_karyawan_id" />
							<input type="hidden" id="hddeletekaryawandetail_karyawandetail_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Detail Karyawan </label> <strong><label id="lbdeletesutomerdetail"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletekaryawandetail">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletekaryawandetail">No</button>
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