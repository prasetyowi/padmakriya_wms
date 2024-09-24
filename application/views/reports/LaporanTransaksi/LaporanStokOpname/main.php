<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-LAPORANTRANSAKSISTOKOPNAME">Laporan Transaksi Stok Opname</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal</label>
							<div class="col-md-8 col-sm-8">
								<input type="text" class="form-control" id="filter_stock_tanggal" name="filter_stock_tanggal" value="" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PT">PT</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_stock_pt" name="filter_stock_pt" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($ClientWMS as $row) { ?>
										<option value="<?= $row['client_wms_id']; ?>"><?= $row['client_wms_nama']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANG">Gudang</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_stock_gudang" name="filter_stock_gudang" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Gudang as $row) { ?>
										<option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_stock_principle" name="filter_stock_principle" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Principle as $row) { ?>
										<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-SKU">SKU</label>
							<div class="col-md-8 col-sm-8">
								<div class="row">
									<div class="col-md-4">
										<input type="text" class="form-control col=lg-2 col-md-2 col-sm-2" id="filter_stock_sku_kode" name="filter_stock_sku_kode" placeholder="Kode" value="" />
									</div>
									<div class="col-md-8">
										<input type="text" class="form-control col=lg-4 col-md-4 col-sm-4" id="filter_stock_sku_nama" name="filter_stock_sku_nama" placeholder="SKU" value="" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 text-right">
					<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Memuat Data...</label>...</span>
					<button type="button" id="btncariAll" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-stock-rekap">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-12">
						<div class="x_content table-responsive">
							<table id="table_detail" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th class="text-center" name="CAPTION-DEPO">Depo</th>
										<th class="text-center" name="CAPTION-TAHUN">Tahun</th>
										<th class="text-center" name="CAPTION-BULAN">Bulan</th>
										<th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
										<th class="text-center" name="CAPTION-PERUSAHAAN">Perusahaan</th>
										<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
										<th class="text-center" name="CAPTION-STATUSOPNAME">Status Opname</th>
										<th class="text-center" name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</th>
										<th class="text-center" name="CAPTION-JENISSTOCK">Jenis Stock</th>
										<th class="text-center" name="CAPTION-PENANGGUNGJAWAB">Penanggung Jawab</th>
										<th class="text-center" name="CAPTION-KODEDOKUMEN">Kode Dokumen</th>
										<th class="text-center" name="CAPTION-AREAOPNAME">Area Opname</th>
										<th class="text-center" name="CAPTION-LOKASI">Lokasi</th>
										<th class="text-center" name="CAPTION-NOPALLET">No. Pallet</th>
										<th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
										<th class="text-center" name="CAPTION-SKUNAMA">SKU Nama</th>
										<th class="text-center" name="CAPTION-SKUBARCODE">SKU Barcode</th>
										<th class="text-center" name="CAPTION-KEMASAN">Kemasan</th>
										<th class="text-center" name="CAPTION-SATUAN">Satuan</th>
										<th class="text-center" name="CAPTION-NOBATCH">SKU Barcode</th>
										<th class="text-center" name="CAPTION-ED">ED</th>
										<th class="text-center" name="CAPTION-QTY">QTY</th>
										<th class="text-center" name="CAPTION-STATUSOPNAMEPALLETDETAIL">Status Opname Pallet Detail</th>
										<!-- <th class="text-center">Total</th> -->
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>