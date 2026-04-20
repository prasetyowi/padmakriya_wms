<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu SKU</h3>
	  </div>

	 
	</div>

	<div class="clearfix"></div>
	
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
			
				<div class="clearfix"></div>
			</div>
			
			<div id="MainMenu" class="form-label-left form-horizontal">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<button type="button" class="btn btn-primary" id="btntambahskuinduk"><i class="fa fa-plus"></i> Tambah SKU</button>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"><h4>SKU Induk Yang Sudah Terdaftar</h4></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" id="txtsearch" class="form-control" placeholder="Cari SKU Induk...">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="SKUIndukList">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 nopadding"  style="overflow-x: auto;">
						<ul class="nav nav-pills nopadding" role="anylist_letter" id="navigation_letter">
						</ul>
					</div>
					
				</div>
				<div class="hidden-xs hidden-sm col-md-2 col-lg-2 col-xl-2"></div>
			</div>
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="anylist_letter">
			
			</div>
			<div id="Tambah" style="display: none;">
				
				<div class="row" style="display: none;">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 nopadding"  style="overflow-x: auto;">
						<ul class="nav nav-pills nopadding" role="anylist" id="navigation">
							<li id="tab1" class="active"><a href="#SKUInduk" role="tab" data-toggle="pill">Data SKU Induk</a></li>
							<li id="tab2"><a href="#VarianSKU" role="tab" data-toggle="pill">Varian SKU</a></li>
							<li id="tab3"><a href="#DataSKU" role="tab" data-toggle="pill">Data SKU (Opsional)</a></li>
							<li id="tab4"><a href="#ListSKU" role="tab" data-toggle="pill">List SKU</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="anylist">
					
					<div class="tab-pane active" class="form-horizontal form-label-left" id="SKUInduk">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="step1">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">1. Pilih SKU Induk</label> 
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12"><hr></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 form-horizontal form-label-left">
							<div id="step1">
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i style="color: red;">*</i> SKU Induk</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbsku_induk" class="form-control"></select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i class="fa fa-pencil"></i> Keterangan</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<textarea id="txasku_induk_ket" class="form-control" style="width: 100%; height: 70px; resize: none;" maxlength="8000" placeholder="Input Keterangan SKU Induk..." readonly></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
									<label class="control-label col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6" id="lbjumlahket">
										Sisa Karakter : <strong>8000</strong></label>
								</div>
							</div>

							<div id="kat1">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori1">
										<i style="color: red;">*</i> Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="cbkategori1" class="form-control" disabled="disabled"></select-->
										<input type="text" id="cbkategori1" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="kat2" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori2">Sub Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="cbkategori2" class="form-control" disabled="disabled"></select-->
										<input type="text" id="cbkategori2" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="kat3" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori3">Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="cbkategori3" class="form-control" disabled="disabled"></select-->
										<input type="text" id="cbkategori3" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="kat4" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbkategori4">Sub Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="cbkategori4" class="form-control" disabled="disabled"></select-->
										<input type="text" id="cbkategori4" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div class="form-group" id="pri">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbprinciple">
									<i style="color: red;">*</i> Principle </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="cbprinciple" class="form-control" disabled="disabled"></select>
								</div>
							</div>
							<div class="form-group" id="bra">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbprinciplebrand">
									<i style="color: red;">*</i> Brand </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="cbprinciplebrand" class="form-control" disabled="disabled"></select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbsku_induk_jenis_ppn">Jenis PPn</label>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<select id="cbsku_induk_jenis_ppn" class="form-control" readonly>
										<option value="1">Include</option>
										<option value="2">Exclude</option>
										<option value="3">None</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_ppn_persen">PPn (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="txtsku_induk_ppn_persen" class="form-control" min="0" max="100" value="0" readonly />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtsku_induk_ppnbm_persen">PPn Barang Mewah (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="txtsku_induk_ppnbm_persen" class="form-control" min="0" max="100" value="0" readonly />
								</div>
							</div>
							
							<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
								<button type="button" id="btnback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
							</div>
							<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
							<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
								<button type="button" id="btnproses1" class="btn btn-success form-control">Lanjut <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
							</div>									
						</div>
					</div>
					
					<div class="tab-pane" id="VarianSKU">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="step1">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">2. Lengkapi Varian</label> 
							</div>
						</div>
						<div id="step2" class="form-horizontal form-label-left" style="display: none;">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<i style="color: red;">*</i> Varian</label>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="divvarianwajib">
									<label class="control-label"></label>
								</div>	
							</div>
						</div>
						
						<div id="step3" class="form-horizontal form-label-left" style="display: none;">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">Varian (Optional)</label>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="divvarian">
									<label class="control-label">***Tidak ada data***</label>
								</div>
							</div>
							<!--div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<button type="button" id="btnaddvarian" class="btn btn-primary"><i class="fa fa-plus"></i> Varian</button>
							</div-->
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						
						<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
							<button type="button" id="btnkembalike1" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
						</div>
						
						<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
						
						<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
							<button type="button" id="btnproses2" class="btn btn-success form-control">Proses <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
					
					
					
					<div class="tab-pane" id="DataSKU">
						<div id="step4">
							<div class="form-group">
								<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">6. Isi Data SKU (Opsional)</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_deskripsi">Keterangan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txts_sku_deskripsi" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_origin">Asal </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8"><input type="text" id="txts_sku_origin" class="form-control" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="txts_sku_kondisi">Kondisi</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="cbs_sku_kondisi" class="form-control">
												<option value="Baru">Baru</option>
												<option value="Bekas">Bekas</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_harga_jual">Harga Jual </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="txts_sku_harga_jual" min="0" class="form-control" value="0" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_sales_min_qty">Jml Min. Penjualan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="txts_sku_sales_min_qty" class="form-control" min="1" value="1" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="chs_sku_is_aktif">Aktif </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="chs_sku_is_aktif" checked /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="chs_sku_is_jual">Dijual </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="chs_sku_is_jual" checked /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_length">Panjang </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_length" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_length_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_width">Lebar </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_width" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_width_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_height">Tinggi </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_height" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_height_unit" class="form-control"></select>
										</div>
									
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_volume">Volume </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_volume" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_volume_unit" class="form-control"></select>
										</div>
									</div>
									<?php
										/*echo '<div class="form-group">
											<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_WeightNetto">Berat Bersih</label>
											<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_WeightNetto" class="form-control" value="0" /></div>
											<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
												<select id="cbs_sku_WeightNetto_unit" class="form-control"></select>
											</div>
											</div>';*/
									?>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_weight_product">Berat Barang</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_weight_product" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_weight_product_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_weight_packaging">Berat Packaging</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_weight_packaging" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_weight_packaging_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txts_sku_weight_gift">Berat Hadiah</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txts_sku_weight_gift" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbs_sku_weight_gift_unit" class="form-control"></select>
										</div>
									</div>
									
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="btnhapussalinan" class="form-control btn btn-danger"><i class="fa fa-copy"></i> <i class="fa fa-times"></i> Hapus Copy</button>
										</div>
										<div class="hidden-xs hidden-sm hidden-md col-lg-2 col-xl-2"></div>
										<div class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-xl-2">
											<button type="button" id="btnsalin" class="form-control btn btn-primary"><i class="fa fa-copy"></i> Copy</button>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-5 col-xl-5">
											<button type="button" id="btnsalinsemua" class="form-control btn btn-success"><i class="fa fa-copy"></i> Copy ke Semua List SKU</button>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="btnkembalike2" class="form-control btn btn-danger"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
										</div>
										
										<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
										
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="btnproses3" class="form-control btn btn-success">Lanjut <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="ListSKU">
						<div id="step5">
							<div class="form-group">
								<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">7. Cek / Ubah Data SKU tiap barisnya</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table id="tablegeneratedstringsku" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th>Hapus</th>									
									<th>Salin</th>									
									<th>Nama SKU</th>
									<th>Bosnet ID</th>
									<th>Deskripsi</th>
									<th>Kemasan</th>
									<th>Satuan</th>
									<th>Kondisi</th>
									<th>Asal</th>
									<th>Harga Jual</th>
									<th>Jumlah Min. Penjualan</th>
									<th>Status Aktif</th>
									<th>Jual</th>
									<th>Berat Netto</th>	
									<th>Satuan</th>			
									<th>Berat Barang</th>
									<th>Satuan</th>	
									<th>Berat Packaging</th>
									<th>Satuan</th>	
									<th>Berat Hadiah</th>
									<th>Satuan</th>	
									<th>Panjang</th>
									<th>Satuan</th>
									<th>Lebar</th>
									<th>Satuan</th>
									<th>Tinggi</th>
									<th>Satuan</th>
									<th>Volume</th>
									<th>Satuan</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<button type="button" id="btnkembalike3" class="form-control btn btn-danger"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
								</div>
								
								<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
								
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<button type="button" class="btn btn-success form-control" id="btnsimpandata">Simpan Data</button>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			
			<div id="Ubah" style="display: none;">
				
				<div class="row" style="display: none;">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 nopadding"  style="overflow-x: auto;">
						<ul class="nav nav-pills nopadding" role="anylist" id="navigation">
							<li id="updatetab1" class="active"><a href="#updateSKUInduk" role="tab" data-toggle="pill">Data SKU Induk</a></li>
							<li id="updatetab2"><a href="#updateVarianSKU" role="tab" data-toggle="pill">Varian SKU</a></li>
							<li id="updatetab3"><a href="#updateDataSKU" role="tab" data-toggle="pill">Data SKU (Opsional)</a></li>
							<li id="updatetab4"><a href="#updateListSKU" role="tab" data-toggle="pill">List SKU</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="updateanylist">
					
					<div class="tab-pane active" class="form-horizontal form-label-left" id="updateSKUInduk">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatestep1">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">1. Ubah SKU yang sudah dibuat (Opsional)</label> 
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12"><hr></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 form-horizontal form-label-left">
							<div id="updatestep1">
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="updatecbsku_induk">
										<i style="color: red;">*</i> SKU Induk</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbsku_induk" class="form-control" disabled="disabled"></select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i class="fa fa-pencil"></i> Keterangan</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<textarea id="updatetxasku_induk_ket" class="form-control" disabled="disabled" style="width: 100%; height: 70px; resize: none;" maxlength="8000" placeholder="Input Keterangan SKU Induk..."></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
									<label class="control-label col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6" id="updatelbjumlahket" disabled="disabled">
										Sisa Karakter : <strong>8000</strong></label>
								</div>
							</div>

							<div id="updatekat1">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori1">
										<i style="color: red;">*</i> Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="updatecbkategori1" class="form-control" disabled="disabled"></select-->
										<input type="hidden" id="updatehdkategori1" />
										<input type="text" id="updatecbkategori1" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="updatekat2" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori2">Sub Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="updatecbkategori2" class="form-control" disabled="disabled"></select-->
										<input type="hidden" id="updatehdkategori2" />
										<input type="text" id="updatecbkategori2" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="updatekat3" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori3">Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="updatecbkategori3" class="form-control" disabled="disabled"></select-->
										<input type="hidden" id="updatehdkategori3" />
										<input type="text" id="updatecbkategori3" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div id="updatekat4" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbkategori4">Sub Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<!--select id="updatecbkategori4" class="form-control" disabled="disabled"></select-->
										<input type="hidden" id="updatehdkategori4" />
										<input type="text" id="updatecbkategori4" class="form-control" disabled="disabled" />
									</div>
								</div>
							</div>
							<div class="form-group" id="updatepri">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbprinciple">
									<i style="color: red;">*</i> Principle </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="updatecbprinciple" class="form-control" disabled="disabled"></select>
								</div>
							</div>
							<div class="form-group" id="updatebra">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbprinciplebrand">
									<i style="color: red;">*</i> Brand </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="updatecbprinciplebrand" class="form-control" disabled="disabled"></select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbsku_induk_jenis_ppn">Jenis PPn</label>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<select id="updatecbsku_induk_jenis_ppn" class="form-control" disabled="disabled">
										<option value="1">Include</option>
										<option value="2">Exclude</option>
										<option value="3">None</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtsku_induk_ppn_persen">PPn (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="updatetxtsku_induk_ppn_persen" disabled="disabled" class="form-control" min="0" max="100" value="0" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtsku_induk_ppnbm_persen">PPn Barang Mewah (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="updatetxtsku_induk_ppnbm_persen" disabled="disabled" class="form-control" min="0" max="100" value="0" />
								</div>
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table id="updatetabledatasku" width="100%" class="table table-striped">
								<thead>
								<tr>								
									<th>Nama SKU</th>
									<th>Bosnet ID</th>
									<th>Deskripsi</th>
									<th>Kemasan</th>
									<th>Satuan</th>
									<th>Kondisi</th>
									<th>Asal</th>
									<th>Harga Jual</th>
									<th>Jumlah Min. Penjualan</th>
									<th>Status Aktif</th>
									<th>Jual</th>
									<th>Berat Netto</th>
									<th>Satuan</th>	
									<th>Berat Barang</th>
									<th>Satuan</th>	
									<th>Berat Packaging</th>
									<th>Satuan</th>	
									<th>Berat Hadiah</th>
									<th>Satuan</th>	
									<th>Panjang</th>
									<th>Satuan</th>
									<th>Lebar</th>
									<th>Satuan</th>
									<th>Tinggi</th>
									<th>Satuan</th>
									<th>Volume</th>
									<th>Satuan</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
								<button type="button" id="updatebtnback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
							</div>
							<div class="hidden-xs col-sm-1 col-md-1 col-lg-1 col-xl-1"></div>
							<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 pull-right">
								<button type="button" id="updatebtnproses1" class="btn btn-success form-control">Tambah Varian Baru <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
							</div>	
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 pull-right">
								<button type="button" id="updatebtnsimpandatasku" class="btn btn-success form-control"><i class="fa fa-floppy-o"></i> Simpan SKU</button>
							</div>									
						</div>
						
					</div>
					
					<div class="tab-pane" id="updateVarianSKU">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">2. Lengkapi Varian</label> 
							</div>
						</div>
						<div id="updatestep2" class="form-horizontal form-label-left" style="display: none;">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<i style="color: red;">*</i> Varian</label>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="updatedivvarianwajib">
									<label class="control-label"></label>
								</div>	
							</div>
						</div>
						
						<div id="updatestep3" class="form-horizontal form-label-left" style="display: none;">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">Varian (Optional)</label>
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="updatedivvarian">
									<label class="control-label">***Tidak ada data***</label>
								</div>
							</div>
							<!--div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<button type="button" id="updatebtnaddvarian" class="btn btn-primary"><i class="fa fa-plus"></i> Varian</button>
							</div-->
						</div>
						
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						
						<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
							<button type="button" id="updatebtnkembalike1" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
						</div>
						
						<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
						
						<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
							<button type="button" id="updatebtnproses2" class="btn btn-success form-control">Proses <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
					
					
					
					<div class="tab-pane" id="updateDataSKU">
						<div id="updatestep4">
							<div class="form-group">
								<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">6. Isi Data SKU (Opsional)</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_deskripsi">Keterangan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxts_sku_deskripsi" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_origin">Asal </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8"><input type="text" id="updatetxts_sku_origin" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="updatecbs_sku_kondisi">Kondisi</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbs_sku_Kondisi" class="form-control">
												<option value="Baru">Baru</option>
												<option value="Bekas">Bekas</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_harga_jual">Harga Jual </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxts_sku_Harga_jual" min="0" class="form-control" value="0" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_sales_min_qty">Jml Min. Penjualan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxts_sku_sales_min_qty" class="form-control" min="1" value="1" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechs_sku_is_aktif">Aktif </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechs_sku_is_aktif" checked /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechs_sku_is_jual">Dijual </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechs_sku_is_jual" checked /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_length">Panjang </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_length" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_length_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_width">Lebar </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_width" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_width_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_height">Tinggi </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_height" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_height_unit" class="form-control"></select>
										</div>
									
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_volume">Volume </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_volume" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_volume_unit" class="form-control"></select>
										</div>
									</div>
									<?php
										/*echo '
											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_WeightNetto">Berat Bersih</label>
												<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_WeightNetto" class="form-control" value="0" /></div>
												<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
													<select id="updatecbs_sku_WeightNetto_unit" class="form-control"></select>
												</div>
											</div>
											';*/
									?>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_weight_product">Berat Barang</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_weight_product" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_weight_product_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_weight_packaging">Berat Packaging</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_weight_packaging" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_weight_packaging_unit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxts_sku_weight_gift">Berat Hadiah</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxts_sku_weight_gift" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbs_sku_weight_gift_unit" class="form-control"></select>
										</div>
									</div>
									
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="updatebtnhapussalinan" class="form-control btn btn-danger"><i class="fa fa-copy"></i> <i class="fa fa-times"></i> Hapus Copy</button>
										</div>
										<div class="hidden-xs hidden-sm hidden-md col-lg-2 col-xl-2"></div>
										<div class="col-xs-12 col-sm-2 col-md-3 col-lg-2 col-xl-2">
											<button type="button" id="updatebtnsalin" class="form-control btn btn-primary"><i class="fa fa-copy"></i> Copy</button>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-5 col-xl-5">
											<button type="button" id="updatebtnsalinsemua" class="form-control btn btn-success"><i class="fa fa-copy"></i> Copy ke Semua List SKU</button>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<div class="form-group">
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="updatebtnkembalike2" class="form-control btn btn-danger"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
										</div>
										
										<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
										
										<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
											<button type="button" id="updatebtnproses3" class="form-control btn btn-success">Lanjut <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="updateListSKU">
						<div id="updatestep5">
							<div class="form-group">
								<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">7. Cek / Ubah Data SKU tiap barisnya</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Data SKU yang sudah ada.</strong></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table id="updateubahtabledatasku" width="100%" class="table table-striped">
								<thead>
								<tr>								
									<th>Nama SKU</th>
									<th>Bosnet ID</th>
									<th>Status Aktif</th>
									<th>Jual</th>
									<th>Deskripsi</th>
									<th>Kemasan</th>
									<th>Satuan</th>
									<th>Kondisi</th>
									<th>Asal</th>
									<th>Harga Jual</th>
									<th>Jumlah Min. Penjualan</th>
									<th>Berat Netto</th>
									<th>Satuan</th>	
									<th>Berat Barang</th>
									<th>Satuan</th>	
									<th>Berat Packaging</th>
									<th>Satuan</th>	
									<th>Berat Hadiah</th>
									<th>Satuan</th>	
									<th>Panjang</th>
									<th>Satuan</th>
									<th>Lebar</th>
									<th>Satuan</th>
									<th>Tinggi</th>
									<th>Satuan</th>
									<th>Volume</th>
									<th>Satuan</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Data SKU hasil generate.</strong></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table id="updatetablegeneratedstringsku" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th>Hapus</th>									
									<th>Salin</th>									
									<th>Nama SKU</th>
									<th>Bosnet ID</th>
									<th>Deskripsi</th>
									<th>Kemasan</th>
									<th>Satuan</th>
									<th>Kondisi</th>
									<th>Asal</th>
									<th>Harga Jual</th>
									<th>Jumlah Min. Penjualan</th>
									<th>Status Aktif</th>
									<th>Jual</th>
									<th>Berat Netto</th>
									<th>Satuan</th>	
									<th>Berat Barang</th>
									<th>Satuan</th>	
									<th>Berat Packaging</th>
									<th>Satuan</th>	
									<th>Berat Hadiah</th>
									<th>Satuan</th>	
									<th>Panjang</th>
									<th>Satuan</th>
									<th>Lebar</th>
									<th>Satuan</th>
									<th>Tinggi</th>
									<th>Satuan</th>
									<th>Volume</th>
									<th>Satuan</th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<button type="button" id="updatebtnkembalike3" class="form-control btn btn-danger"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Kembali</button>
								</div>
								
								<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
								
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<button type="button" class="btn btn-success form-control" id="updatebtnsimpandataubah">Simpan Data</button>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>			
			
			<div id="AturStock" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdaturstock_sku_induk_id" />
						<input type="text" class="form-control" id="txtaturstock_sku_induk_nama" disabled="disabled" />
					</div>
				</div>		
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">_unit Mandiri</label>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">
						<select class="form-control" id="cbaturstock_unitmandiri">
						
						</select>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				
				
				<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5 nopadding">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="50%"><strong><center>Nama SKU</center></strong></td>
							<td width="20%"><strong><center>Total</center></strong></td>
							<td width="30%"><strong><center>Detail</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; height: 400px;">
						<table width="100%" class="table table-striped table-sm" id="tableaturstokheader">
						<tbody>
						
						</tbody>
						</table>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7 nopadding">
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<?php
							echo '<button type="button" id="btntambahdetailaturstock" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Detail</button>';
						?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="15%"><strong><center>Stok</center></strong></td>
							<td width="30%"><strong><center>Gudang</center></strong></td>
							<td width="40%"><strong><center>Tgl Expired</center></strong></td>
							<td width="15%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divaturstock_detailsku" style="overflow-x: hidden; height: 360px;">
						
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
					<button type="button" id="btnaturstockback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
				</div>
				<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<?php
						echo '<button type="button" class="form-control btn btn-success" id="btnaturstoksimpan"><i class="fa fa-floppy"></i> Simpan</button>';
					?>
				</div>
			</div>
			
			<div id="AturVarian" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdaturvarian_sku_induk_id" />
						<input type="text" class="form-control" id="txtaturvarian_sku_induk_nama" disabled="disabled" />
					</div>
				</div>		
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				
				
				<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5 nopadding">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="50%"><strong><center>Nama SKU</center></strong></td>
							<td width="30%"><strong><center>Total Varian</center></strong></td>
							<td width="20%"><strong><center>Detail</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; height: 400px;">
						<table width="100%" class="table table-striped table-sm" id="tableaturvarianheader">
						<tbody>
						
						</tbody>
						</table>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7 nopadding">
					
					<!--div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<?php
							echo '<button type="button" id="btntambahdetailaturstock" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Detail</button>';
						?>
					</div-->
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="15%"><strong><center>Stok</center></strong></td>
							<td width="30%"><strong><center>Gudang</center></strong></td>
							<td width="40%"><strong><center>Tgl Expired</center></strong></td>
							<td width="15%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divaturstock_detailsku" style="overflow-x: hidden; height: 360px;">
						
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
					<button type="button" id="btnaturstockback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
				</div>
				<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<?php
						echo '<button type="button" class="form-control btn btn-success" id="btnaturstoksimpan"><i class="fa fa-floppy"></i> Simpan</button>';
					?>
				</div>
			</div>
			
			<div id="Konversi" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdkonversi_sku_induk_id" />
						<input type="text" class="form-control" id="txtkonversi_sku_induk_nama" disabled="disabled" />
					</div>
				</div>		
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				
				<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5 nopadding">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="60%"><strong><center>Nama SKU</center></strong></td>
							<td width="20%"><strong><center>Kemasan</center></strong></td>
							<td width="20%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; height: 400px;">
						<table width="100%" class="table table-striped table-sm" id="tablekonversiheader">
						<tbody>
						
						</tbody>
						</table>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7 nopadding">
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<button type="button" id="btntambahdetailkonversi" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Konversi</button>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="50%"><strong><center>Nama SKU</center></strong></td>
							<td width="20%"><strong><center>Kemasan</center></strong></td>
							<td width="15%"><strong><center>Nilai</center></strong></td>
							<td width="15%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divkonversi_detailsku" style="overflow-x: hidden; height: 360px;">
						
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<button type="button" id="btnkback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<button type="button" class="form-control btn btn-success" id="btnkonversisimpan"><i class="fa fa-floppy"></i> Simpan</button>
				</div>
			</div>
			
			<div id="Foto" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<div class="modal-body form-horizontal form-label-left">
						<div class="form-group">
						
							<input type="hidden" id="hdID" />
							<input type="hidden" id="hdupdatephoto_sku_induk_id" />
							<input type="hidden" id="hdupdatephoto_sku_induk_nama" />
							
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatephoto_sku_induk_nama">SKU Induk</label>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
								<input type="text" id="txtupdatephoto_up_sku_induk_nama" class="form-control" value="" disabled="disabled" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdate_UploadPhoto">Upload Foto SKU </label>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
								<input type="file" id="txtupdatephoto_UploadPhoto" name="txtupdate_UploadPhoto" class="form-control" />
							</div>
						</div>
						<div class="form-group scrolling-wrapper-flexbox" id="uploadedimages">
							<!--img id="imgtest" src="#" alt="your image" /-->
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
					<button type="button" id="btnphotoback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
				</div>
				<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<button type="button" class="form-control btn btn-success" id="btnsaveuploadphoto"><i class="fa fa-floppy"></i> Simpan</button>
				</div>
			</div>
		</div>
	  </div>
	</div>

	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="updatepreviewkonfirmasiproses1" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Tambah Varian Baru</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk Menambah Varian Baru ?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyesproses1">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnoproses1">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="previewphotokembalikelistskuinduk" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Kembali ke List SKU Induk</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk Kembali ke List SKU Induk? Segala proses akan dibatalkan.</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesphotoback">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnophotoback">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="previewaddvariandetail" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Tambah Varian</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama Varian</label>
						<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
							<input type="hidden" id="txtaddvarian_detail_varian_id" disabled="disabled" />
							<input type="text" id="txtaddvarian_detail_varian_nama" class="form-control" disabled="disabled" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Jenis</label>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
							<input type="hidden" id="txtaddvarian_detail_rand" class="form-control" />
							<input type="text" id="txtaddvarian_detail_varian_detail_nama" class="form-control" placeholder="Tambah Jenis Varian..." />
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3" id="isvarianberat">
							<select id="cbaddvarian_detail_varian_unit" class="form-control"></select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btnsavevariandetail">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackvariandetail">Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="updatelihatpreviewaddbosnet" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Tampilan Bosnet ID</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama SKU</label>
							<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
								<input type="hidden" id="updatelihattxtRandBosnetID" class="form-control" disabled="disabled" />
								<input type="hidden" id="updatelihattxtBSKU_ID" class="form-control" disabled="disabled" />
								<input type="text" id="updatelihattxtBosnet_SKU_NamaProduk" class="form-control" disabled="disabled" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="100%"><center>Bosnet ID</center></th>
											<!--th width="20%">Opsi</th-->
										</tr>
									</thead>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div style="overflow-x: hidden; height: 300px;">
									<table class="table table-striped" id="updatelihattbskubosnet">
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatelihatbtnbackskubosnetid">OK</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="updatepreviewaddbosnet" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Atur Bosnet ID</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama SKU</label>
							<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
								<input type="hidden" id="updatetxtRandBosnetID" class="form-control" disabled="disabled" />
								<input type="hidden" id="updatetxtBSKU_ID" class="form-control" disabled="disabled" />
								<input type="text" id="updatetxtBosnet_SKU_NamaProduk" class="form-control" disabled="disabled" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
								<button type="button" id="updatebtntambahbosnet" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Tambah Bosnet ID</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="80%"><center>Bosnet ID</center></th>
											<th width="20%">Opsi</th>
										</tr>
									</thead>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div style="overflow-x: hidden; height: 300px;">
									<table class="table table-striped" id="updatetbskubosnet">
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="updatebtnsaveskubosnetid">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnbackskubosnetid">Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="previewaddbosnet" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Atur Bosnet ID</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama SKU</label>
							<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
								<input type="hidden" id="txtRandBosnetID" class="form-control" disabled="disabled" />
								<input type="text" id="txtBosnet_SKU_NamaProduk" class="form-control" disabled="disabled" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
								<button type="button" id="btntambahbosnet" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Tambah Bosnet ID</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="80%"><center>Bosnet ID</center></th>
											<th width="20%">Opsi</th>
										</tr>
									</thead>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div style="overflow-x: hidden; height: 300px;">
									<table class="table table-striped" id="tbskubosnet">
									<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btnsaveskubosnetid">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackskubosnetid">Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="updateubahpreviewaddbosnet" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Tambah Varian</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama SKU</label>
							<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
								<input type="hidden" id="updateubahtxtRandBosnetID" class="form-control" disabled="disabled" />
								<input type="text" id="updateubahtxtBosnet_SKU_NamaProduk" class="form-control" disabled="disabled" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
							<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
								<button type="button" id="updateubahbtntambahbosnet" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Tambah Bosnet ID</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table class="table table-striped">
									<thead>
										<tr>
											<th width="80%"><center>Bosnet ID</center></th>
											<th width="20%">Opsi</th>
										</tr>
									</thead>
								</table>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div style="overflow-x: hidden; height: 300px;">
									<table class="table table-striped" id="updateubahtbskubosnet">
									<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="updateubahbtnsaveskubosnetid">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updateubahbtnbackskubosnetid">Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan L !-->
	<div class="modal fade" id="updatepreviewaddvariandetail" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Tambah Varian</label></h4>
				</div>
				<div class="modal-body form-horizontal form-label-left">
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Nama Varian</label>
						<div class="col-xs-8 col-sm-5 col-md-5 col-lg-5 col-xl-5">
							<input type="hidden" id="updatetxtaddvarian_detail_varian_id" disabled="disabled" />
							<input type="text" id="updatetxtaddvarian_detail_varian_nama" class="form-control" disabled="disabled" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Jenis</label>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
							<input type="hidden" id="updatetxtaddvarian_detail_rand" class="form-control" />
							<input type="text" id="updatetxtaddvarian_detail_varian_detail_nama" class="form-control" placeholder="Tambah Jenis Varian..." />
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3" id="updateisvarianberat">
							<select id="cbupdateaddvarian_detail_varian_unit" class="form-control"></select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="updatebtnsavevariandetail">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnbackvariandetail">Batal</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Delete Photo !-->
	<div class="modal fade" id="previewdeletephoto" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-md">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Hapus Foto SKU</label></h4>
					<input type="hidden" id="hdphotodir" />
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Hapus Foto terkait? </label> <label style="color: red;"> Note : Foto akan dihapus secara permanen</label> </h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeletephoto">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeletephoto">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="previewsalinsemua" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Copy ke Semua List SKU</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Semua Data pada List SKU akan di-Paste. Lanjut?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyessalinsemua">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnosalinsemua">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="updatepreviewsalinsemua" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Copy ke Semua List SKU</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Semua Data pada List SKU akan di-Paste. Lanjut?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyessalinsemua">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnosalinsemua">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menyimpan data !-->
	<div class="modal fade" id="previewsimpandata" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Simpan Data</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk menyimpan data SKU?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyessimpandata">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnosimpandata">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menyimpan data !-->
	<div class="modal fade" id="updatepreviewsimpandata" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Simpan Data</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk menyimpan data SKU? </label>
								<label style="color: red;">Segala SKU beserta pengaturan Stock dan Konversi yang telah dibuat sebelumnya akan dinon-aktifkan</label></h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyessimpandata">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnosimpandata">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="previewubahsalinan" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Ubah Salinan</label></h4>
					<input type="hidden" id="hdIdx" />
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah yakin untuk mem-Paste pada baris tersebut?.</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesubahsalinan">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnoubahsalinan">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="updatepreviewubahsalinan" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Ubah Salinan</label></h4>
					<input type="hidden" id="updatehdIdx" />
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah yakin untuk mem-Paste pada baris tersebut?.</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyesubahsalinan">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnoubahsalinan">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menyimpan data !-->
	<div class="modal fade" id="previewaturstoksimpan" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Simpan Data</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk menyimpan Stocknya?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesaturstoksimpan">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnoaturstoksimpan">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menyimpan data !-->
	<div class="modal fade" id="previewkonversisimpan" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Simpan Data</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk menyimpan Konversinya?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyeskonversisimpan">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnokonversisimpan">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="previewkembalikelistskuinduk" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Kembali ke List SKU Induk</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk Kembali ke List SKU Induk? Segala proses akan dibatalkan.</h4>
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
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="updatepreviewkembalikelistskuinduk" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
					<h4 class="modal-title"><label>Kembali ke List SKU Induk</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk Kembali ke List SKU Induk? Segala proses akan dibatalkan.</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyesback">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnoback">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="previewhapusgeneratedsku" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title"><label>Hapus SKU</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<input type="hidden" id="hdhapusidx" />
							
							<h4><label>Apakah Anda yakin untuk menghapus SKU tersebut?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyeshapusgeneratedsku">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnohapusgeneratedsku">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal untuk menampilkan Salinan !-->
	<div class="modal fade" id="updatepreviewhapusgeneratedsku" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title"><label>Hapus SKU</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<input type="hidden" id="updatehdhapusidx" />
							
							<h4><label>Apakah Anda yakin untuk menghapus SKU tersebut?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="updatebtnyeshapusgeneratedsku">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="updatebtnnohapusgeneratedsku">Tidak</button>
				</div>
			</div>
		</div>
	</div>
	
  </div>
</div>
        <!-- /page content -->