<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3 name="CAPTION-MENUHAKAKSESMENUDANSUBMENU">Menu Hak Akses Menu dan Sub Menu</h3>
	  </div>

	 
	</div>

	<div class="clearfix"></div>
	
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<div class="clearfix"></div>
			</div>
			<div class="form-label-left form-horizontal">
				<div class="form-group">
					<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3" name="CAPTION-PILIHAPLIKASI">Pilih Aplikasi</label>
					<div class="col-xs-7 col-sm-3 col-md-3 col-lg-3 col-xl-3">
						<select id="cbaplikasi" class="form-control"></select>
					</div>
					<label class="control-label col-xs-5 col-sm-3 col-md-3 col-lg-3 col-xl-3" name="CAPTION-PILIHMENUROOT">Pilih Menu Root</label>
					<div class="col-xs-7 col-sm-3 col-md-3 col-lg-3 col-xl-3">
						<select id="cbmenuroot" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: scroll; max-height: 380px; min-height: 380px;">
						<table class="table" id="tbmenustep">
						<thead>
							<tr>
								<td width="30%" name="CAPTION-NAMAMENU" align="center">Nama Menu</td>
								<td width="20%" name="CAPTION-STEP" align="center">Step</td>
								<td width="30%" name="CAPTION-SUBMENUDARI" align="center">Sub Menu Dari</td>
								<td width="20%" name="CAPTION-ICON" align="center">Icon</td>
							</tr>
						</thead>
						<tbody></tbody>
						</table>
					</div-->
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
						<div style="overflow-x: scroll; overflow-y: scroll; max-width: 2000px; max-height: 386px;">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divMenu" style="height: 2000px;"></div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><label name="CAPTION-STEP">Step</label> - <label style="cursor: pointer; color: blue;" onclick="PreviewStep()" name="CAPTION-TAMPILKANLEBIHLEBAR">Preview</label></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 300px; border: solid black 1px; overflow-x: scroll;">
							<div id="treeview">
								
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div class="hidden-xs hidden-sm col-md-6 col-lg-6 col-xl-6">
								<button type="button" class="btn btn-primary form-control" onclick="StepSetupMenu()"><i class="fas fa-cog"></i> <label name="CAPTION-ATURDETAILLANGKAH"></label></button>
							</div>							
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
								<button type="button" class="btn btn-success form-control" onclick="SaveStepLayout()"><i class="fa fa-save"></i> <label name="CAPTION-SAVESTEPLAYOUT"></label></button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal untuk menampilkan Update User !-->
			<div class="modal fade" id="previewwider" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label name="CAPTION-ATURDETAILLANGKAH">Atur Detail Langkah</label></h4>
						</div>
						<div class="modal-body form-label-left form-horizontal">	
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 200px; border: solid black 1px; overflow-x: scroll;">
									<div id="treeviewwider">
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-ms-12 col-md-4 col-lg-4 col-xl-4">
									<div class="form-group">
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9" style="padding: 0;">
											<select id="cbicons" class="form-control">
											<?php 
												$this->load->view('master/Global/S_FAIcon'); 
												
											?>
											</select>
										</div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0;">
											<button type="button" class="btn btn-primary" id="btnseticon"><i class="fas fa-gear"></i> <label name="CAPTION-SETICON">Set Icon</label></button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9" style="padding: 0;">
											<button type="button" class="btn btn-primary form-control btnmenu_step_detail" id="btnmenu_step_detail_keterangan_1"><i class="fas fa-edit"></i> <label name="CAPTION-KETERANGAN1"></label></button>
										</div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0;">										
											<button type="button" id="btnview_ket_1" class="btn btn-primary btnview_ket"><i class="fa fa-eye"></i></button>
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9" style="padding: 0;">
											<button type="button" class="btn btn-primary form-control btnmenu_step_detail" id="btnmenu_step_detail_keterangan_2"><i class="fas fa-edit"></i> <label name="CAPTION-KETERANGAN2"></label></button>
										
										</div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0;">				
											<button type="button" id="btnview_ket_2" class="btn btn-primary btnview_ket"><i class="fa fa-eye"></i></button>		
										</div>
									</div>
									<div class="form-group">
										<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9" style="padding: 0;">
											<button type="button" class="btn btn-primary form-control btnmenu_step_detail" id="btnmenu_step_detail_keterangan_3"><i class="fas fa-edit"></i> <label name="CAPTION-KETERANGAN3"></label></button>
									
										</div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0;">					
											<button type="button" id="btnview_ket_3" class="btn btn-primary btnview_ket"><i class="fa fa-eye"></i></button>		
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" style="height: 200px; overflow-y: scroll;">
									<input type="hidden" id="hdmenu_step_id" />
									<div class="form-group divket" id="divket1" style="display: none;">
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-KETERANGAN1"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<textarea id="txamenu_step_detail_keterangan_1" class="txamenu_step_detail_keterangan form-control" style="height: 120px; resize: none;" maxlength="8000"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-LINK1"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<input type="text" id="txtmenu_step_detail_link_1" class="txtmenu_step_detail_link form-control" />
											</div>
										</div>
									</div>
									<div class="form-group divket" id="divket2" style="display: none;">
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-KETERANGAN2"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<textarea id="txamenu_step_detail_keterangan_2" class="txamenu_step_detail_keterangan form-control" style="height: 120px; resize: none;" maxlength="8000"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-LINK2"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<input type="text" id="txtmenu_step_detail_link_2" class="txtmenu_step_detail_link form-control" />
											</div>
										</div>
									</div>
									<div class="form-group divket" id="divket3" style="display: none;">
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-KETERANGAN3"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<textarea id="txamenu_step_detail_keterangan_3" class="txamenu_step_detail_keterangan form-control" style="height: 120px; resize: none;" maxlength="8000"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3" name="CAPTION-LINK3"></label>
											<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
												<input type="text" id="txtmenu_step_detail_link_3" class="txtmenu_step_detail_link form-control" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
						</div>
					</div>
				</div>
				
			</div>	

			<div class="modal fade" id="previewconfirmtree" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-xlg">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title"><label name="CAPTION-CONFIRMSTEP">Konfirmasi Langkah</label></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<input type="hidden" id="hdselected_menu_kode" />
									<input type="hidden" id="hdselected_level" />
									<input type="hidden" id="hdselected_parent" />
									<input type="hidden" id="hdselected_menu_step_id" />
									
									<input type="hidden" id="hdtarget_menu_kode" />
									<input type="hidden" id="hdtarget_level" />
									<input type="hidden" id="hdtarget_parent" />
									<input type="hidden" id="hdtarget_menu_step_id" />
									
									<h4><label name="CAPTION-MENUYANGDIPILIH">Menu yang Dipilih</label> : <strong><label id="lbsource_menu_name"></label></strong>
									<h4><label name="CAPTION-MENUTUJUAN">Menu Tujuan</label> : <strong><label id="lbtarget_menu_name"></label></strong>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnturntonextstep"><label name="CAPTION-JADIKANLANGKAHSELANJUTNYA">Jadikan Langkah Selanjutnya</label></button>
							<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnswitchstep"><label name="CAPTION-TUKARLANGKAH">Tukar Langkah</label></button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnresettodefaultstep"><label name="CAPTION-ATURULANGKELANGKAHPERTAMA">Keluar ke Langkah Pertama</label></button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback"><label name="CAPTION-BATAL">Batal</label></button>
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