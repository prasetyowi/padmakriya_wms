<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu Group Kategori</h3>
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
			<button type="button" id="btnaddnewkategori" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Group Kategori</button>
		  </div>
			<div class="row">
				<div class="form-group form-label-left form-horizontal">
					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
							<label class="control-label col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">Cari</label>
						</div>
						<div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 col-xl-4">
							<input type="text" id="txtcari" class="form-control" placeholder="Cari Kategori..." />
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
			</div>
			
			<div class="row" id="kat">
				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collkat" aria-expanded="false" aria-controls="collkat">Kategori</button>
							<div id="collkat" class="collapse col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; min-height: 200px; max-height: 200px;">
								<table id="tablekategori1menu" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="40%">Nama</th>
									<th width="40%">Opsi</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collkat2" aria-expanded="false" aria-controls="collkat2">Sub Kategori</button>
							<div id="collkat2" class="collapse col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; min-height: 200px; max-height: 200px;">
								<table id="tablekategori2menu" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="40%">Nama</th>
									<th width="40%">Opsi</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collkat3" aria-expanded="false" aria-controls="collkat3">Jenis</button>
							<div id="collkat3" class="collapse col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; min-height: 200px; max-height: 200px;">
								<table id="tablekategori3menu" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="40%">Nama</th>
									<th width="40%">Opsi</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collkat4" aria-expanded="false" aria-controls="collkat4">Sub Kategori</button>
							<div id="collkat4" class="collapse col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; min-height: 200px; max-height: 200px;">
								<table id="tablekategori4menu" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th width="20%">Kode</th>
									<th width="40%">Nama</th>
									<th width="40%">Opsi</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
					<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">SKU ( <strong id="lbketsku"></strong> )</label>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; min-height: 200px; max-height: 400px;">
						<table id="tableskumenu" width="100%" class="table table-striped">
						<thead>
						<tr>
							<th>Kode</th>
							<th>Nama</th>
						</tr>
						</thead>
						<tbody>
						
						</tbody>
						</table>
					</div>
				</div>
			</div>
		  
		  <!-- Modal untuk menampilkan Add Kategori 1 !-->
		  <div class="modal fade" id="previewaddnewkategori" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Tambah Group Kategori</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtkategori_kode">Kode </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4"><input type="text" id="txtkategori_kode" class="form-control" value="" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtkategori_nama">Nama </label>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="txtkategori_nama" class="form-control" /></div>
							</div>						
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori_level">Kategori Level</label>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<select id="cbkategori_level" class="form-control">
										
									</select>
								</div>
								<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5" id="subkategori">
									
								</div>
							</div>			
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkelas_id" id="lbkelas_id">Kelas </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<select id="cbkelas_id" class="form-control"></select>
								</div>
								<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" id="divwarna"></div>
							</div>				
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbisactive">Aktif </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<input type="checkbox" id="chkategori_is_aktif" class="checkbox_form" />
								</div>
							</div>	
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewkategori">Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Kategori 1 !-->
			<div class="modal fade" id="previewdeletekategori" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Group Kategori</label></h4>
							<input type="hidden" id="hddeletekategori_id" />
							<input type="hidden" id="hddeletekategori_level" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk Menghapus Group Kategori </label> <strong style="color: red;"><label id="lbdeletekategori_nama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletekategori">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletekategori">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Update Kategori 1 !-->
			<div class="modal fade" id="previewupdatekategori" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-lg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Group Kategori</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtkategori_kode">Kode </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4"><input type="text" id="updatetxtkategori_kode" class="form-control" value="" /></div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtkategori_nama">Nama </label>
								<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="updatetxtkategori_nama" class="form-control" /></div>
							</div>
							<!--div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbupdategroupkategori">Grup Kategori </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<select id="cbupdategroupkategori" class="form-control">
									
									</select>
								</div>
							</div-->
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori_level">Kategori Level</label>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<select id="updatecbkategori_level" class="form-control" disabled="disabled" >
									
									</select>
								</div>
								<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5" id="updatesubkategori">

								</div>
							</div>		
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkelas_id" id="updatelbkelas_id">Kelas </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<select id="updatecbkelas_id" class="form-control" disabled="disabled"></select>
								</div>
								<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" id="updatedivwarna" style="min-width: 50px; min-height: 30px;">
								</div>
							</div>			
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatechkategori_is_aktif">Aktif </label>
								<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<input type="checkbox" id="updatechkategori_is_aktif" class="checkbox_form" />
								</div>
							</div>	
							<input type="hidden" id="updatetxtkategori_id" class="form-control" />
						
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveupdatenewkategori">Ubah</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdatekategori">Kembali</button>
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