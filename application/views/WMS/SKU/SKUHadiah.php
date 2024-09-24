<div class="right_col" role="main">
  <div class="">
	<div class="page-title">
	  <div class="title_left">
		<h3>Menu SKU Hadiah</h3>
	  </div>

	 
	</div>

	<div class="clearfix"></div>
	
	
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
			
				<div class="clearfix"></div>
			</div>
			
			<div id="MainMenu">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<button type="button" class="btn btn-primary" id="btntambahskuinduk"><i class="fa fa-plus"></i> Tambah SKU</button>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6"><h4>SKU Induk yang sudah Terdaftar</h4></div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" id="txtsearch" class="form-control" placeholder="Cari SKU Induk...">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="SKUIndukList">
					
				</div>
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
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">1. Isi Data SKU Induk</label> 
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12"><hr></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 form-horizontal form-label-left">
							<div id="step1">
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i style="color: red;">*</i> SKU Induk</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbskuinduk" class="form-control"></select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i class="fa fa-pencil"></i> Keterangan</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<textarea id="txaskuinduk_ket" class="form-control" style="width: 100%; height: 70px; resize: none;" maxlength="8000" placeholder="Input Keterangan SKU Induk..."></textarea>
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
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori1">
										<i style="color: red;">*</i> Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbKategori1" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="kat2" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori2">Sub Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbKategori2" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="kat3" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori3">Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbKategori3" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="kat4" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori4">Sub Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="cbKategori4" class="form-control"></select>
									</div>
								</div>
							</div>
							<div class="form-group" id="pri">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori9">
									<i style="color: red;">*</i> Principle </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="cbKategori9" class="form-control"></select>
								</div>
							</div>
							<div class="form-group" id="bra">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbKategori10">
									<i style="color: red;">*</i> Brand </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="cbKategori10" class="form-control"></select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="cbSKUInduk_Jenis_PPn">Jenis PPn</label>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<select id="cbSKUInduk_Jenis_PPn" class="form-control">
										<option value="1">Include</option>
										<option value="2">Exclude</option>
										<option value="3">None</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtSKUInduk_PPn_Persen">PPn (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="txtSKUInduk_PPn_Persen" class="form-control" min="0" max="100" value="0" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtSKUInduk_PPnBM_Persen">PPn Barang Mewah (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="txtSKUInduk_PPnBM_Persen" class="form-control" min="0" max="100" value="0" />
								</div>
							</div>
							
							<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
								<button type="button" id="btnback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
							</div>
							<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
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
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
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
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="divvarian">
									<label class="control-label">***Tidak ada data***</label>
								</div>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<button type="button" id="btnaddvarian" class="btn btn-primary"><i class="fa fa-plus"></i> Varian</button>
							</div>
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
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">2. Isi Data SKU (Opsional)</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Deskripsi">Keterangan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="txtS_SKU_Deskripsi" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Origin">Asal </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8"><input type="text" id="txtS_SKU_Origin" class="form-control" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="txtS_SKU_Kondisi">Kondisi</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="cbS_SKU_Kondisi" class="form-control">
												<option value="Baru">Baru</option>
												<option value="Bekas">Bekas</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_HargaJual">Harga Jual </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="txtS_SKU_HargaJual" min="0" class="form-control" value="0" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_SalesMinQty">Jml Min. Penjualan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="txtS_SKU_SalesMinQty" class="form-control" min="1" value="1" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="chS_SKU_IsActive">Aktif </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="chS_SKU_IsActive" checked /></div>
									</div>
									<!--div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="chS_SKU_IsJual">Dijual </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="chS_SKU_IsJual" checked /></div>
									</div-->
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Weight">Panjang </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_Length" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_LengthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Volume">Lebar </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_Width" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_WidthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Weight">Tinggi </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_Height" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_HeightUnit" class="form-control"></select>
										</div>
									
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Volume">Volume </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_Volume" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_VolumeUnit" class="form-control"></select>
										</div>
									</div>
									<?php
										/*echo '<div class="form-group">
											<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_WeightNetto">Berat Bersih</label>
											<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_WeightNetto" class="form-control" value="0" /></div>
											<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
												<select id="cbS_SKU_WeightNettoUnit" class="form-control"></select>
											</div>
											</div>';*/
									?>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_WeightProduct">Berat Barang</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_WeightProduct" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_WeightProductUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_Weight">Berat Packaging</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_WeightPackaging" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_WeightPackagingUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="txtS_SKU_WeightGift">Berat Hadiah</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="txtS_SKU_WeightGift" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="cbS_SKU_WeightGiftUnit" class="form-control"></select>
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
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">3. Cek / Ubah Data SKU tiap barisnya</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<button type="button" id="btntambahsku" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah SKU</button>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<table id="tablegeneratedstringsku" width="100%" class="table table-striped">
								<thead>
								<tr>
									<th>Hapus</th>									
									<th>Salin</th>									
									<!--th>Kode SKU</th-->
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
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">1. Isi Data SKU Induk</label> 
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12"><hr></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 form-horizontal form-label-left">
							<div id="updatestep1">
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4" for="updatecbskuinduk">
										<i style="color: red;">*</i> SKU Induk</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbskuinduk" class="form-control"></select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<i class="fa fa-pencil"></i> Keterangan</label> 
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<textarea id="updatetxaskuinduk_ket" class="form-control" style="width: 100%; height: 70px; resize: none;" maxlength="8000" placeholder="Input Keterangan SKU Induk..."></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"></label>
									<label class="control-label col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6" id="updatelbjumlahket">
										Sisa Karakter : <strong>8000</strong></label>
								</div>
							</div>

							<div id="updatekat1">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori1">
										<i style="color: red;">*</i> Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbKategori1" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="updatekat2" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori2">Sub Kategori</label>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbKategori2" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="updatekat3" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori3">Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbKategori3" class="form-control"></select>
									</div>
								</div>
							</div>
							<div id="updatekat4" style="display: none;">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori4">Sub Jenis</label>
									<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<select id="updatecbKategori4" class="form-control"></select>
									</div>
								</div>
							</div>
							<div class="form-group" id="updatepri">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori9">
									<i style="color: red;">*</i> Principle </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="updatecbKategori9" class="form-control"></select>
								</div>
							</div>
							<div class="form-group" id="updatebra">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori10">
									<i style="color: red;">*</i> Brand </label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="updatecbKategori10" class="form-control"></select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbSKUInduk_Jenis_PPn">Jenis PPn</label>
								<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<select id="updatecbSKUInduk_Jenis_PPn" class="form-control">
										<option value="1">Include</option>
										<option value="2">Exclude</option>
										<option value="3">None</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtSKUInduk_PPn_Persen">PPn (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="updatetxtSKUInduk_PPn_Persen" class="form-control" min="0" max="100" value="0" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtSKUInduk_PPnBM_Persen">PPn Barang Mewah (%)</label>
								<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
									<input type="number" id="updatetxtSKUInduk_PPnBM_Persen" class="form-control" min="0" max="100" value="0" />
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
							<div class="hidden-xs col-sm-3 col-md-3 col-lg-3 col-xl-3"></div>
							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
								<button type="button" id="updatebtnproses1" class="btn btn-primary form-control">Tambah SKU <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
							</div>	
							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
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
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
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
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="updatedivvarian">
									<label class="control-label">***Tidak ada data***</label>
								</div>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<button type="button" id="updatebtnaddvarian" class="btn btn-primary"><i class="fa fa-plus"></i> Varian</button>
							</div>
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
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">2. Isi Data SKU (Opsional)</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Deskripsi">Keterangan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtS_SKU_Deskripsi" class="form-control" value="" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Origin">Asal </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8"><input type="text" id="updatetxtS_SKU_Origin" class="form-control" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="updatecbS_SKU_Kondisi">Kondisi</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbS_SKU_Kondisi" class="form-control">
												<option value="Baru">Baru</option>
												<option value="Bekas">Bekas</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_HargaJual">Harga Jual </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxtS_SKU_HargaJual" min="0" class="form-control" value="0" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_SalesMinQty">Jml Min. Penjualan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxtS_SKU_SalesMinQty" class="form-control" min="1" value="1" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechS_SKU_IsActive">Aktif </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechS_SKU_IsActive" checked /></div>
									</div>
									<!--div class="form-group">
										<label class="control-label col-xs-10 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechS_SKU_IsJual">Dijual </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechS_SKU_IsJual" checked /></div>
									</div-->
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Panjang </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Length" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_LengthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Volume">Lebar </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Width" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WidthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Tinggi </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Height" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_HeightUnit" class="form-control"></select>
										</div>
									
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Volume">Volume </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Volume" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_VolumeUnit" class="form-control"></select>
										</div>
									</div>
									<?php
										/*echo '
											<div class="form-group">
												<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightNetto">Berat Bersih</label>
												<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightNetto" class="form-control" value="0" /></div>
												<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
													<select id="updatecbS_SKU_WeightNettoUnit" class="form-control"></select>
												</div>
											</div>
											';*/
									?>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightProduct">Berat Barang</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightProduct" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightProductUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Berat Packaging</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightPackaging" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightPackagingUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightGift">Berat Hadiah</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightGift" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightGiftUnit" class="form-control"></select>
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
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">3. Cek / Ubah Data SKU tiap barisnya</label>
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
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><strong>Data SKU baru.</strong></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<button type="button" id="updatebtntambahsku" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah SKU</button>
							</div>
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
			
			<!--div id="Ubah" style="display: none;">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-lg-12 nopadding" style="display: none;">
						<ul class="nav nav-pills nopadding" role="updateanylist" id="updatenavigation">
							<li id="updatetab1" class="active"><a href="#updateSKUInduk" role="tab" data-toggle="pill">Varian SKU</a></li>
							<li id="updatetab2"><a href="#updateKategori" role="tab" data-toggle="pill">Data SKU Induk</a></li>
							<li id="updatetab3"><a href="#updateDataSKU" role="tab" data-toggle="pill">Data SKU (Opsional)</a></li>
							<li id="updatetab4"><a href="#updateListSKU" role="tab" data-toggle="pill">List SKU</a></li>
						</ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="updateanylist">
					
					<div class="tab-pane active" id="updateSKUInduk">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatestep1">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">1. Pilih SKU Induk</label>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select id="updatecbskuinduk" class="form-control"></select>
								</div>
							</div>
						</div>
						
						<div id="updatestep2" class="form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">2. Varian (Wajib)</label>
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="updatedivvarianwajib">
									<label class="control-label"></label>
								</div>	
							</div>
						</div>
						
						<div id="updatestep3" class="form-horizontal form-label-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<div class="form-horizontal form-label-left" style="display: none;">
									
									
								</div>
							</div>
							<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">3. Varian (Optional)</label>
							<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6 col-xl-6">
								<div id="updatedivvarian">
									<label class="control-label">***Tidak ada data***</label>
								</div>
							</div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<button type="button" id="updatebtnaddvarian" class="btn btn-primary"><i class="fa fa-plus"></i> Varian</button>
							</div>
						</div>
						
						<div id="updatestep4">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							
							<div class="hidden-xs col-sm-9 col-md-9 col-lg-9 col-xl-9"></div>
							<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
								<button type="button" id="updatebtnproses1" class="btn btn-primary form-control">Proses <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="updateKategori">
						<div id="updatestep4">
							<div class="form-group form-horizontal form-label-left">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">4. Isi Kategori</label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left">
								<div id="updatedivcategory">
									<div id="updatekat1">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori1">Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbKategori1" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat2" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori2">Sub Kategori</label>
											<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbKategori2" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat3" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori3">Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbKategori3" class="form-control"></select>
											</div>
										</div>
									</div>
									<div id="updatekat4" style="display: none;">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori4">Sub Jenis</label>
											<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6 col-xl-6">
												<select id="updatecbKategori4" class="form-control"></select>
											</div>
										</div>
									</div>
									<div class="form-group" id="updatepri">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori9">Principle </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="updatecbKategori9" class="form-control"></select>
										</div>
									</div>
									<div class="form-group" id="updatebra">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbKategori10">Brand </label>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
											<select id="updatecbKategori10" class="form-control"></select>
										</div>
									</div>
								</div>	
							</div>
							
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left">
								<div class="form-group form-horizontal form-label-left">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">5. Isi PPn (%) untuk Produk</label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatecbSKUInduk_Jenis_PPn">PPn (%)</label>
										<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<select id="updatecbSKUInduk_Jenis_PPn" class="form-control">
												<option value="1">Include</option>
												<option value="2">Exclude</option>
												<option value="3">None</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtSKUInduk_PPn_Persen">PPn (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="updatetxtSKUInduk_PPn_Persen" class="form-control" min="0" max="100" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-4 col-sm-4 col-xs-12" for="updatetxtSKUInduk_PPnBM_Persen">PPn Barang Mewah (%)</label>
										<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
											<input type="number" id="updatetxtSKUInduk_PPnBM_Persen" class="form-control" min="0" max="100" />
										</div>
									</div>
									
									<div class="hidden-xs col-sm-9 col-md-9 col-lg-9 col-xl-9"></div>
									<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 pull-right">
										<button type="button" id="updatebtnproses2" class="btn btn-primary form-control">Proses <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
									</div>
								</div>										
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="updateDataSKU">
						<div id="step5">
							<div class="form-group">
								<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">6. Isi Data SKU (Opsional)</label>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Deskripsi">Keterangan </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7"><input type="text" id="updatetxtS_SKU_Deskripsi" class="form-control" value="" /></div>
									</div> 										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Origin">Asal </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-8"><input type="text" id="updatetxtS_SKU_Origin" class="form-control" /></div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="updatecbS_KemasanID">Kemasan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbS_KemasanID" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-lg-5 col-xl-5 col-md-5 col-sm-5 col-xs-12" for="updatetxtS_SKU_Kondisi">Kondisi</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
											<select id="updatecbS_SKU_Kondisi" class="form-control">
												<option value="Baik">Baik</option>
												<option value="Bekas">Bekas</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_HargaJual">Harga Jual </label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxtS_SKU_HargaJual" min="0" class="form-control" value="0" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_SalesMinQty">Jml Min. Penjualan</label>
										<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4 col-xl-4"><input type="number" id="updatetxtS_SKU_SalesMinQty" class="form-control" value="0" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-11 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechS_SKU_IsActive">Aktif </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechS_SKU_IsActive" /></div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-11 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatechS_SKU_IsJual">Dijual </label>
										<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><input type="checkbox" class="checkbox_form" id="updatechS_SKU_IsJual" /></div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 form-horizontal form-label-left">
									
									
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Panjang </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Length" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_LengthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Volume">Lebar </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Width" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WidthUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Tinggi </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Height" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_HeightUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Volume">Volume </label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_Volume" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_VolumeUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightNetto">Berat Bersih</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightNetto" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightNettoUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightProduct">Berat Barang</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightProduct" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightProductUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_Weight">Berat Packaging</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightPackaging" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightPackagingUnit" class="form-control"></select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5" for="updatetxtS_SKU_WeightGift">Berat Hadiah</label>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"><input type="number" id="updatetxtS_SKU_WeightGift" class="form-control" value="0" /></div>
										<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
											<select id="updatecbS_SKU_WeightGiftUnit" class="form-control"></select>
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
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="updateListSKU">
						<div class="form-group">
							<div class="form-horizontal form-label-left col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<label class="control-label col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">7. Cek / Ubah Data SKU tiap barisnya</label>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<table id="updatetablegeneratedstringsku" width="100%" class="table table-striped">
							<thead>
							<tr>
								<th>Paste</th>									
								<th>Nama SKU</th>
								<th>Deskripsi</th>
								<th>Kemasan</th>
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
							<div class="hidden-xs hidden-sm col-md-8 col-lg-8 col-xl-8"></div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
								<button type="button" class="btn btn-success form-control" id="updatebtnsimpandata">Simpan Data</button>
							</div>
						</div>
					</div>
				</div>	
			</div-->
			
			
			<div id="AturStock" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdaturstock_SKUInduk_ID" />
						<input type="text" class="form-control" id="txtaturstock_SKUInduk_Nama" disabled="disabled" />
					</div>
				</div>		
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Unit Mandiri</label>
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
			
			<div id="AturHadiah" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdaturhadiah_SKUInduk_ID" />
						<input type="text" class="form-control" id="txtaturhadiah_SKUInduk_Nama" disabled="disabled" />
					</div>
				</div>		
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="50%"><strong><center>Nama SKU Hadiah</center></strong></td>
							<td width="20%"><strong><center>Kemasan</center></strong></td>
							<td width="20%"><strong><center>Satuan</center></strong></td>
							<td width="10%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="overflow-x: hidden; height: 400px;">
						<table width="100%" class="table table-striped table-sm" id="tableaturhadiahheader">
						<tbody>
						
						</tbody>
						</table>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 nopadding">
					
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<?php
							echo '<button type="button" id="btntambahdetailaturhadiah" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Detail</button>';
						?>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divtablehead">
						<table width="100%" class="table table-striped">
						<thead>
						<tr>
							<td width="50%"><strong><center>SKU</center></strong></td>
							<td width="20%"><strong><center>Syarat Min. Beli</center></strong></td>
							<td width="20%"><strong><center>Qty Hadiah</center></strong></td>
							<td width="10%"><strong><center>Opsi</center></strong></td>
						</tr>
						</thead>
						</table>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divaturhadiah_detailsku" style="overflow-x: hidden; height: 360px;">
						
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><hr></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-lg-3 nopadding">
					<button type="button" id="btnaturhadiahback" class="btn btn-danger form-control"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Ke List SKU Induk</button>
				</div>
				<div class="hidden-xs col-sm-6 col-md-6 col-lg-6 col-xl-6"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<?php
						echo '<button type="button" class="form-control btn btn-success" id="btnaturhadiahsimpan"><i class="fa fa-floppy"></i> Simpan</button>';
					?>
				</div>
			</div>
			
			<div id="Konversi" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">SKU Induk</label>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
						<input type="hidden" id="hdkonversi_SKUInduk_ID" />
						<input type="text" class="form-control" id="txtkonversi_SKUInduk_Nama" disabled="disabled" />
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
				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-xl-9"></div>
				<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
					<button type="button" class="form-control btn btn-success" id="btnkonversisimpan"><i class="fa fa-floppy"></i> Simpan</button>
				</div>
			</div>
			
			<div id="Foto" style="display: none;">
				<div class="form-group form-label-left form-horizontal">
					<div class="modal-body form-horizontal form-label-left">
						<div class="form-group">
						
							<input type="hidden" id="hdID" />
							<input type="hidden" id="hdupdatephoto_SKUInduk_ID" />
							<input type="hidden" id="hdupdatephoto_SKUInduk_Nama" />
							
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdatephoto_SKUInduk_Nama">SKU Induk</label>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
								<input type="text" id="txtupdatephoto_up_SKUInduk_Nama" class="form-control" value="" disabled="disabled" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdate_UploadPhoto_Utama">Foto (Utama)</label>
							<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
								<input type="file" id="txtupdatephoto_UploadPhoto_Utama" name="txtupdate_UploadPhoto_Utama" class="form-control" />
							</div>
						</div>
						<div class="form-group scrolling-wrapper-flexbox" id="uploadedimages_Utama">
							<!--img id="imgtest" src="#" alt="your image" /-->
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 col-sm-4 col-xs-12" for="txtupdate_UploadPhoto">Foto </label>
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
					<h4 class="modal-title"><label>Tambah SKU</label></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<h4><label>Apakah Anda yakin untuk menambah SKU?</h4>
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
							<input type="hidden" id="txtaddvariandetail_varianid" disabled="disabled" />
							<input type="text" id="txtaddvariandetail_variannama" class="form-control" disabled="disabled" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Jenis</label>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
							<input type="hidden" id="txtaddvariandetail_rand" class="form-control" />
							<input type="text" id="txtaddvariandetail_variandetailnama" class="form-control" placeholder="Tambah Jenis Varian..." />
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3" id="isvarianberat">
							<select id="cbaddvariandetail_varianunit" class="form-control"></select>
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
							<input type="hidden" id="updatetxtaddvariandetail_varianid" disabled="disabled" />
							<input type="text" id="updatetxtaddvariandetail_variannama" class="form-control" disabled="disabled" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">Jenis</label>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
							<input type="hidden" id="updatetxtaddvariandetail_rand" class="form-control" />
							<input type="text" id="updatetxtaddvariandetail_variandetailnama" class="form-control" placeholder="Tambah Jenis Varian..." />
						</div>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3" id="updateisvarianberat">
							<select id="cbupdateaddvariandetail_varianunit" class="form-control"></select>
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
							<h4><label>Apakah Anda yakin untuk menyimpan Data Stocknya?</h4>
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
	<div class="modal fade" id="previewaturhadiahsimpan" role="dialog" data-keyboard="false" data-backdrop="static">
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
							<h4><label>Apakah Anda yakin untuk menyimpan Data Hadiahnya?</h4>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesaturhadiahsimpan">Iya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnoaturhadiahsimpan">Tidak</button>
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