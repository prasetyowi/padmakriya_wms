<style>
	#table_list_data_rekalkulasi_processing {
		height: 60px !important;
	}
</style>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-128010000">Rekalkulasi Stok</h3>
			</div>
			<div style="float: right">
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4>Filter Data</h4>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-TANGGALSKUSTOCK">Tanggal SKU Stock</label>
								<input type="text" id="filter_tgl_rekalkulasi" class="form-control" name="filter-tgl-rekalkulasi" value="" />
								<input type="hidden" id="jml_rekalkulasi" class="form-control" name="jml_rekalkulasi" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
								<select id="filter_perusahaan" class="form-control select2">
									<option value="">** <span name="CAPTION-PERUSAHAAN">Perusahaan</span> **</option>
									<?php foreach ($Perusahaan as $row) : ?>
										<option value="<?= $row['client_wms_nama'] ?>"><?= $row['client_wms_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-GUDANG">Lokasi Penyimpanan</label>
								<select id="filter_gudang" class="form-control select2">
									<option value="">** <span name="CAPTION-GUDANG">GUDANG</span> **</option>
									<?php foreach ($Gudang as $row) : ?>
										<option value="<?= $row['depo_detail_nama'] ?>"><?= $row['depo_detail_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-PRINCIPLE">Principle</label>
								<select id="filter_principle" class="form-control select2">
									<option value="">** <span name="CAPTION-PRINCIPLE">Principle</span> **</option>
									<?php foreach ($Principle as $row) : ?>
										<option value="<?= $row['principle_kode'] ?>"><?= $row['principle_kode'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-SKUKODE">SKU Kode</label>
								<input type="text" id="filter_sku_kode" class="form-control" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-SKU">SKU</label>
								<input type="text" id="filter_sku_nama_produk" class="form-control" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-STATUS">Status</label>
								<select id="filter_status" class="form-control select2">
									<option value="">** <span name="CAPTION-STATUS">Status</span> **</option>
									<option value="sama">Sama</option>
									<option value="beda">Beda</option>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-12">
								<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btn_search_rekalkulasi" class="btn btn-primary"><i class="fa fa-search"></i> <span name="CAPTION-CARI">Cari</span></button>
								<button type="button" id="btn_proses_rekalkulasi" class="btn btn-primary"><i class="fa fa-refresh"></i> <span name="CAPTION-Rekalkulasi">Rekalkulasi</span></button>
								<button type="button" id="btn_simpan_rekalkulasi" class="btn btn-primary"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4>Table Data</h4>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="x_content table-responsive">
								<table id="table_list_data_rekalkulasi" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF">No</th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-TANGGALSKUSTOCK">Tanggal SKU Stock</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-PERUSAHAAN">Perusahaan</span></th>
											<!-- <th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-DEPO">Depo</span></th> -->
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-GUDANG">Gudang</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-PRINCIPLE">Pricniple</span></th>
											<!-- <th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUINDUK">SKU Induk</span></th> -->
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUKODE">SKU Kode</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKU">SKU</span></th>
											<!-- <th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUKEMASAN">SKU Kemasan</span></th> -->
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUSATUAN">SKU Satuan</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-TANGGALEXPIRED">Tanggal Expired</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-BATCHNO">Batch No</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUSTOCKAWAL">SKU Stock Awal</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUSTOCKMASUK">SKU Stock Masuk</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUSTOCKKELUAR">SKU Stock Keluar</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-SKUSTOCKAKHIR">SKU Stock Akhir</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#4942E4"><span name="CAPTION-REKALKULASISKUSTOCKAWAL">Rekalkulasi SKU Stock Awal</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#4942E4"><span name="CAPTION-REKALKULASISKUSTOCKMASUK">Rekalkulasi SKU Stock Masuk</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#4942E4"><span name="CAPTION-REKALKULASISKUSTOCKKELUAR">Rekalkulasi SKU Stock Keluar</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#4942E4"><span name="CAPTION-REKALKULASISKUSTOCKAKHIR">Rekalkulasi SKU Stock Akhir</span></th>
											<th class="text-center" style="vertical-align: middle;color:white;background:#30A2FF"><span name="CAPTION-STATUS">Status</span></th>
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
</div>