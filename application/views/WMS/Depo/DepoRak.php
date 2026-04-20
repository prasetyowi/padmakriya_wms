<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu Pengaturan Tata Letak Gudang</h3>
	  </div>

	 
	</div>

	<div class="clearfix"></div>
	
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			
			<div class="clearfix"></div>
		  </div>
		  <div style="display: none;">
			<button type="button" id="btnaddnewdeporak" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Rak Depo</button>
		  </div>
			<div class="form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3">Unit</label>
							<div class="col-xs-7 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
								<select id="cbDepo" class="form-control"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3">Unit Detail</label>
							<div class="col-xs-7 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
								<select id="cbDepoDetail" class="form-control"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3" for="txtrak_kode">Kode Rak</label>
							<div class="col-xs-7 col-sm-3 col-md-3 col-lg-3 col-xl-3 nopadding">
								<input type="text" id="txtrak_kode" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3" for="txtrak_nama">Nama Rak</label>
							<div class="col-xs-7 col-sm-5 col-md-5 col-lg-5 col-xl-5 nopadding">
								<input type="text" id="txtrak_nama" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3" for="chrak_is_aktif">Aktif</label>
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 nopadding">
								<input type="checkbox" id="chrak_is_aktif" class="checkbox_form" />
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3">Nama Lajur</label>
							<div class="col-xs-7 col-sm-3 col-md-3 col-lg-3 col-xl-3 nopadding">
								<input type="text" id="txtrak_lajur_nama" class="form-control" />
							</div>
							<label class="control-label col-xs-5 col-sm-1 col-md-1 col-lg-1 col-xl-1">Prefix</label>
							<div class="col-xs-7 col-sm-1 col-md-1 col-lg-1 col-xl-1 nopadding">
								<input type="text" id="txtrak_lajur_prefix" class="form-control" maxlength="3" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3">Panjang</label>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 nopadding">
								<input type="number" id="txtrak_lajur_width" class="form-control" value="70" />
							</div>
							<label class="control-label col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1">pixel/inch</label>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3">Lebar</label>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 nopadding">
								<input type="number" id="txtrak_lajur_length" class="form-control" value="70" />
							</div>
							<label class="control-label col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1">pixel/inch</label>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3"></label>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 nopadding">
								<button type="button" id="btngeneraterak" class="form-control btn btn-primary">Buat Rak</button>
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						<div class="form-group" id="divket">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<label id="lbMejaLokasi_Nama"></label> <label id="lbMejaLokasi_Luas_m_X"></label> <label id="lbMejaLokasi_Luas_m_Y"></label>
							</div>
						</div>
						
						<div class="form-group">
							<div id="Depo_Luas">
								<div id="shape-placeholder">
								
								</div>
							</div>
							
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						<div class="col-xs-6 col-sm-6 col-md-8 col-lg-8 col-xl-8"></div>
						<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
							<button type="button" id="btnsavelayout" class="btn btn-success form-control">
								<i class="fa fa-floppy-o"></i> Simpan Layout Rak
							</button>
						</div>
					</div>					
				</div>
			</div>
		  
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
			</div>
		  
		  <!-- Modal untuk menampilkan Add Template !-->
		  <div class="modal fade" id="previewaddnewdeporak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Tambah Rak Depo</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 nopadding">
									<ul class="nav nav-pills nopadding" role="anylist" id="navigation">
										<li id="tab1" class="active"><a href="#depolist" role="tab" data-toggle="pill">Unit</a></li>
										<li id="tab2"><a href="#detailrakdepolist" role="tab" data-toggle="pill">Detail Unit Rak</a></li>
									</ul>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="anylist">
								<div class="tab-pane active" id="depolist">
									<div class="form-group">
										<input type="hidden" id="hdrandID" />
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
											<div class="form-group">
												<label class="control-label col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" for="vcbDepo">Unit</label>
												<div class="col-xs-12 col-xs-7 col-sm-7 col-md-5 col-lg-5 col-xl-5">
													<select id="vcbDepo" class="form-control" disabled="disabled"></select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" for="vcbDepoDetail">Unit Detail</label>
												<div class="col-xs-12 col-xs-7 col-sm-7 col-md-5 col-lg-5 col-xl-5">
													<select id="vcbDepoDetail" class="form-control" disabled="disabled"></select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" for="vtxtrak_lajur_nama">Nama Lajur</label>
												<div class="col-xs-12 col-xs-7 col-sm-7 col-md-4 col-lg-4 col-xl-4">
													<input type="text" id="vtxtrak_lajur_nama" class="form-control" value="" disabled="disabled" />
												</div>
												<label class="control-label col-xs-5 col-sm-5 col-md-1 col-lg-1 col-xl-1" for="vtxtrak_lajur_prefix">Prefix</label>
												<div class="col-xs-12 col-xs-7 col-sm-7 col-md-1 col-lg-1 col-xl-1">
													<input type="text" id="vtxtrak_lajur_prefix" class="form-control" value="" disabled="disabled" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" for="chrak_is_aktif">Aktif </label>
												<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1">
													<input type="checkbox" id="chrak_is_aktif" class="checkbox_form" checked="checked" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="detailrakdepolist">
									<div class="form-group">
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3"></div>
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="btnaddbarisrak" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Tambah Level Rak</button>
										</div>
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="btnaddkolomrak" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Tambah Kolom Rak</button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-label-left form-horizontal" id="divRak">
											<table width="180px" id="tbrak" class="table table-striped" width="200px">
												
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="btnsaveaddnewdeporak">Simpan</button>
							<button type="button" class="btn btn-danger" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
			
		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewchangedefaultaddress" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Alamat Default</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<input type="hidden" id="cda_CustomerDetail_ID" />
								<input type="hidden" id="cda_Customer_ID" />
								
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda yakin untuk mengubah Alamat Default Resipien </label><strong><label id="cda_lbnama"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyeschangedefaultaddress">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewsaveaddnewgudangrak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Simpan Rak Depo</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda yakin untuk menyimpan Rak Depo ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyessaveaddnewgudangrak">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnosaveaddnewgudangrak">Tidak</button>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal fade" id="updatepreviewsaveaddnewgudangrak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Simpan Rak Depo</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda yakin untuk menyimpan Rak Depo ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyessaveaddnewgudangrak">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnosaveaddnewgudangrak">Tidak</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="updatepreviewaddnewgudangrak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Ubah Rak Depo</label></h4>
						</div>
						<div class="modal-body form-horizontal form-label-left">
							<div class="form-group">
								<input type="hidden" id="updatehdCustomer_ID" />
								
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_Name">Nama </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtCustomer_Name" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_UserName">User Name </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtCustomer_UserName" class="form-control" disabled="disabled" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_Pass">Kata Sandi </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="password" id="updatetxtCustomer_Pass" class="form-control" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_Phone">Kontak </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtCustomer_Phone" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_Email">Email </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtCustomer_Email" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatetxtCustomer_Birthdate">Tanggal Lahir</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtCustomer_Birthdate" class="form-control" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatecbSegment">Segment </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbSegment" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatecbLokasiKerja">Lokasi Kerja </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbLokasiKerja" class="form-control"></select>
										</div>
									</div>
									<!--div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatecbUnitMandiri">Unit Mandiri </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbUnitMandiri" class="form-control"></select>
										</div>
									</div-->
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatecbIsDefault">Alamat Default </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbIsDefault" class="form-control" disabled="disabled"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-5 col-sm-5 col-xs-12" for="updatechrak_is_aktif">Aktif </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<input type="checkbox" id="updatechrak_is_aktif" class="checkbox_form" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
							</div>
							<div class="form-group">
								<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
									<button type="button" id="updatebtnaddgudangrakdetail" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Detail Rak Depo</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="updatedivtabCustomerDetail">
									
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-label-left form-horizontal" id="updatedivCustomerDetail">
									
								</div>
							</div>
							
							<!--div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding divtablehead">
									<table width="100%">
									<tr>
										<td width="20%" align="center"><h5><strong>Resipien</strong></h5></td>
										<td width="20%" align="center"><h5><strong>Kode Pos</strong></h5></td>
										<td width="20%" align="center"><h5><strong>Alamat</strong></h5></td>
										<td width="15%" align="center"><h5><strong>Kontak</strong></h5></td>
										<td width="15%" align="center"><h5><strong>Default</strong></h5></td>
										<td width="10%" align="center"><h5><strong>Opsi</strong></h5></td>
									</tr>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding pre-scrollable" style="height: 400px;">
									<table id="tableaddgudangrakdetail" class="table table-striped table-sm" width="100%">
									
									</table>
								</div>
							</div-->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" id="updatebtnsaveaddnewgudangrak">Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnback">Kembali</button>
						</div>
					</div>
				</div>
			</div>
			
		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewconfirmbackdeporak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Kembali Ke Menu</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah Anda yakin untuk Batal Menyimpan Rak yang sudah dibuat?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesback">Iya</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnoback">Tidak</button>
						</div>
					</div>
				</div>
			</div>

		  <!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletegudangrak" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Rak Depo</label></h4>
							<input type="hidden" id="hddeletegudangrakid" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk Menonaktifkan Customer </label> <strong><label id="lbdeletegudangrak"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletegudangrak">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletegudangrak">No</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Delete Template !-->
			<div class="modal fade" id="previewdeletegudangrak1" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label>Hapus Detail Rak Depo</label></h4>
							<input type="hidden" id="hddeletegudangrakdetail_gudangrak_id" />
							<input type="hidden" id="hddeletegudangrakdetail_gudangrakdetail_id" />
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<h4><label>Apakah yakin untuk menghapus Detail Rak Depo </label> <strong><label id="lbdeletesutomerdetail"></label></strong> ?</h4>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletegudangrakdetail">Yes</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletegudangrakdetail">No</button>
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