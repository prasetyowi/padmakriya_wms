<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu SKU Induk</h3>
	  </div>

	 
	</div>

	<div class="clearfix"></div>
	
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			
			<div class="clearfix"></div>
		  </div>
		  
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<button type="button" id="btnaddnewsku_induk" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah SKU Induk</button>
			</div>
		  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		  <!--div class="x_content" style="overflow-x:auto"-->
				<table id="tablesku_indukmenu" width="100%" class="table table-striped">
				<thead>
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>Keterangan</th>
					<th>Judul</th>
					<th>Hadiah</th>
					<th>Opsi</th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
				</table>
		  <!--/div-->
		  </div>
		  
		  <!-- Modal untuk menampilkan Add SKU Induk !-->
		  <div class="modal fade" id="previewaddnewsku_induk" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Tambah SKU Induk</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_nama_judul">Title </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="txtsku_induk_nama_judul" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_kode">Kode </label>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4"><input type="text" id="txtsku_induk_kode" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_nama">Nama </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="txtsku_induk_nama" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_keterangan">Keterangan </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
											<textarea id="txtsku_induk_keterangan" style="height: 200px; resize: none;" class="form-control" maxlength="8000"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
										<label class="control-label col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8" id="lbjumlahket">
											Sisa Karakter : <strong>8000</strong></label>
									</div>
									<div id="kat1">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori1">
												<i style="color: red;">*</i> Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="cbkategori1" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="kat2" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori2">Sub Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="cbkategori2" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="kat3" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori3">Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="cbkategori3" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="kat4" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori4">Sub Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="cbkategori4" class="form-control"></select>
											</div>
										</div>
									</div>
									<div class="form-group" id="pri">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori9">
											<i style="color: red;">*</i> Principle </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="cbprinciple" class="form-control"></select>
										</div>
									</div>
									<div class="form-group" id="bra">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori10">
											<i style="color: red;">*</i> Brand </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="cbprinciplebrand" class="form-control"></select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbsku_induk_jenis_ppn">Jenis PPn</label>
										<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<select id="cbsku_induk_jenis_ppn" class="form-control">
												<option value="1">Include</option>
												<option value="2">Exclude</option>
												<option value="3">None</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_ppn_persen">PPn (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="txtsku_induk_ppn_persen" class="form-control" min="0" max="100" value="0" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_ppnbm_persen">PPn Barang Mewah (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="txtsku_induk_ppnbm_persen" class="form-control" min="0" max="100" value="0" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="chsku_induk_is_hadiah">Hadiah </label>
										<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1"><input type="checkbox" id="chsku_induk_is_hadiah" class="checkbox_form" /></div>
									</div>
									
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<h5><label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Varian Wajib</strong></label></h5>
									<table class="table table-striped table-sm" id="tablevarianwajib">
									<thead>
									</thead>
									<tbody>
									</tbody>
									</table>
									<!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div-->
									<h5><label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Varian (Opsional)</strong></label></h5>
									<table class="table table-sm table-striped" id="tablevariannonwajib">
									<thead>
									</thead>
									<tbody>
									</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewsku_induk">Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete SKU Induk !-->
			<div class="modal fade" id="previewdeletesku_induk" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus SKU Induk</label></h4>
							<input type="hidden" id="hddeletesku_induk_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus SKU Induk </label> <strong><label id="lbdeletesku_induk_nama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletesku_induk">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletesku_induk">Tidak</button>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Modal untuk menampilkan Delete SKU Induk !-->
			<div class="modal fade" id="previewbeforesave" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Konfirmasi Penyimpanan Data SKU Induk</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Tidak ada Varian Opsional yang dipilih. Lanjut simpan ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyessaveaddnewsku_induk">Iya</button>
							<button type="button" class="btn btn-danger" id="btnnosaveaddnewsku_induk">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Delete SKU Induk !-->
			<div class="modal fade" id="previewupdatebeforesave" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Konfirmasi Penyimpanan Data SKU Induk</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Tidak ada Varian Opsional yang dipilih. Lanjut simpan ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyessaveupdatesku_induk">Iya</button>
							<button type="button" class="btn btn-danger" id="btnnosaveupdatesku_induk">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Update SKU Induk !-->
			<div class="modal fade" id="previewupdatesku_induk" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah SKU Induk</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="row">
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatesku_induk_nama_judul">Title </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="txtupdatesku_induk_nama_judul" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatesku_induk_kode">Kode </label>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4"><input type="text" id="txtupdatesku_induk_kode" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatesku_induk_nama">Nama </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8"><input type="text" id="txtupdatesku_induk_nama" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatesku_induk_keterangan">Keterangan </label>
										<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
											<textarea id="txtupdatesku_induk_keterangan" style="height: 200px; resize: none;" onpaste="return false" class="form-control" maxlength="8000"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
										<label class="control-label col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8" id="updatelbjumlahket">
											Sisa Karakter : <strong>8000</strong></label>
									</div>
									<div id="updatekat1">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori1">
												<i style="color: red;">*</i> Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbkategori1" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat2" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori2">Sub Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbkategori2" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat3" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori3">Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbkategori3" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat4" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori4">Sub Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbkategori4" class="form-control"></select>
											</div>
										</div>
									</div>
									<div class="form-group" id="updatepri">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbprinciple">
											<i style="color: red;">*</i> Principle </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="updatecbprinciple" class="form-control"></select>
										</div>
									</div>
									<div class="form-group" id="updatebra">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbprinciplebrand">
											<i style="color: red;">*</i> Brand </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="updatecbprinciplebrand" class="form-control"></select>
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbsku_induk_jenis_ppn">Jenis PPn</label>
										<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<select id="updatecbsku_induk_jenis_ppn" class="form-control">
												<option value="1">Include</option>
												<option value="2">Exclude</option>
												<option value="3">None</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtsku_induk_ppn_persen">PPn (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="updatetxtsku_induk_ppn_persen" class="form-control" min="0" max="100" value="0" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtsku_induk_ppnbm_persen">PPn Barang Mewah (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="updatetxtsku_induk_ppnbm_persen" class="form-control" min="0" max="100" value="0" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatechsku_induk_ishadiah">Hadiah </label>
										<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1"><input type="checkbox" id="updatechsku_induk_is_hadiah" class="checkbox_form" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
									<h5><label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Varian Wajib</strong></label></h5>
									<table class="table table-striped table-sm" id="updatetablevarianwajib">
									<thead>
									</thead>
									<tbody>
									</tbody>
									</table>
									<!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div-->
									<h5><label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Varian (Opsional)</strong></label></h5>
									<table class="table table-sm table-striped" id="updatetablevariannonwajib">
									<thead>
									</thead>
									<tbody>
									</tbody>
									</table>
								</div>
							</div>
							<input type="hidden" id="txtupdatesku_induk_id" class="form-control" />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveupdatenewsku_induk">Ubah</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdatesku_induk">Kembali</button>
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