<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu Kelas</h3>
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
			<button type="button" id="btnaddnewkelas" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Add Kelas</button>
		  </div>
		  <div class="row">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablekelasmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Kode Kelas</th>
					<th>Nama Kelas</th>
					<th>Jenis</th>
					<th>Warna</th>
					<th>Opsi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				</table>
		  <!--/div-->
		  </div>
		  
		  <!-- Modal untuk menampilkan Add Package Type !-->
		  <div class="modal fade" id="previewaddnewkelas" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Add Kelas</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="txtkelas_kode">Kode Kelas </label>
								<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-xl-4"><input type="text" id="txtkelas_kode" class="form-control" value="" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="txtkelas_nama">Nama Kelas </label>
								<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-xl-6"><input type="text" id="txtkelas_nama" class="form-control" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="cbkelas_jenis">Jenis </label>
								<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-xl-4">
									<!--input type="text" id="txtjenis" class="form-control" /-->
									<select id="cbkelas_jenis" class="form-control">
										<option value="F">Makanan</option>
										<option value="B">Minuman</option>
										<option value="O">Lain-Lain</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="txtkelas_warna">Warna</label>
								<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-xl-4">
									<input type="color" id="txtkelas_warna" class="form-control" value="" />
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewkelas">Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Package Type !-->
			<div class="modal fade" id="previewdeletekelas" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i><label> Hapus Kelas</label></h4>
							<input type="hidden" id="hddeletekelas_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda Yakin untuk menghapus Kelas </label> <strong><label id="lbdeletekelas_nama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletekelas">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletekelas">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Update Package Type !-->
			<div class="modal fade" id="previewupdatekelas" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Kelas</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtkelas_kode">Kode Kelas </label>
								<div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-xl-4"><input type="text" id="updatetxtkelas_kode" class="form-control" value="" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtkelas_nama">Nama Kelas </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"><input type="text" id="updatetxtkelas_nama" class="form-control" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkelas_jenis">Jenis </label>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
									<!--input type="text" id="txtjenis" class="form-control" /-->
									<select id="updatecbkelas_jenis" class="form-control">
										<option value="F">Makanan</option>
										<option value="B">Minuman</option>
										<option value="O">Lain-Lain</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="updatetxtkelas_warna">Warna Awal</label>
								<div class="col-xs-7 col-sm-8 col-md-4 col-lg-4 col-xl-4">
									<input type="color" id="updatetxtkelas_warna" class="form-control" value="" />
								</div>
							</div>
							<input type="hidden" id="updatehdkelas_id" />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveupdatenewkelas">Ubah</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdatekelas">Kembali</button>
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