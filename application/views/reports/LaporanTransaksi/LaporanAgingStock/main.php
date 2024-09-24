<style>
	.wrapper {
		display: inline-flex;
		margin-top: 10px;
		height: 40px;
		width: 30%;
		align-items: center;
		justify-content: space-evenly;
		border-radius: 5px;
	}

	.wrapper .option {
		background: #fff;
		height: 100%;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: space-evenly;
		margin: 0 10px;
		border-radius: 5px;
		cursor: pointer;
		padding: 0 10px;
		border: 2px solid lightgrey;
		transition: all 0.3s ease;
	}

	.wrapper .option .dot {
		height: 20px;
		width: 20px;
		background: #d9d9d9;
		border-radius: 50%;
		position: relative;
	}

	.wrapper .option .dot::before {
		position: absolute;
		content: "";
		top: 4px;
		left: 4px;
		width: 12px;
		height: 12px;
		background: #0069d9;
		border-radius: 50%;
		opacity: 0;
		transform: scale(1.5);
		transition: all 0.3s ease;
	}

	input[type="radio"] {
		display: none;
	}

	#option-1:checked:checked~.option-1,
	#option-2:checked:checked~.option-2 {
		border-color: #0069d9;
		background: #0069d9;
	}

	#option-1:checked:checked~.option-1 .dot,
	#option-2:checked:checked~.option-2 .dot {
		background: #fff;
	}

	#option-1:checked:checked~.option-1 .dot::before,
	#option-2:checked:checked~.option-2 .dot::before {
		opacity: 1;
		transform: scale(1);
	}

	.wrapper .option span {
		font-size: 16px;
		color: #808080;
	}

	#option-1:checked:checked~.option-1 span,
	#option-2:checked:checked~.option-2 span {
		color: #fff;
	}

	.DTFC_LeftBodyLiner {
		overflow-y: unset !important
	}

	.DTFC_RightBodyLiner {
		overflow-y: unset !important
	}

	/** End Styling Tabs pane */
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<h3 name="CAPTION-LAPORANAGINGSTOCK">Laporan Aging Stock</h3>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPE">Tipe</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_tipe" name="filter_tipe" style="width:100%" onchange="GetTipe()">
									<option value="tahun">Tahun</option>
									<option value="bulan">Bulan</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TAHUN">Tahun</label>
							<div class="col-md-8 col-sm-8">
								<div class="row">
									<div class="col-md-6">
										<input type="text" class="form-control" id="filter_tahun" name="filter_tahun" value="<?= date('Y') ?>" />
									</div>
									<div class=" col-md-6">
										<input type="text" class="form-control" id="filter_tahun2" name="filter_tahun2" value="<?= date('Y') ?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group" id="span_bulan" style="display: none;">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-BULAN">Bulan</label>
							<div class="col-md-8 col-sm-8">
								<div class="row">
									<div class="col-md-6">
										<select class="form-control select2" id="filter_bulan" name="filter_bulan" style="width:100%">
											<?php foreach ($ListBulan as $key => $row) { ?>
												<option value="<?= $key; ?>"><?= $row; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class=" col-md-6">
										<select class="form-control select2" id="filter_bulan2" name="filter_bulan2" style="width:100%">
											<?php foreach ($ListBulan as $key => $row) { ?>
												<option value="<?= $key; ?>"><?= $row; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="form-group">
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
						</div> -->
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_perusahaan" name="filter_perusahaan" style="width:100%" onchange="GetPrincipleByPerusahaan()">
									<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
									<?php foreach ($Perusahaan as $row) { ?>
										<option value="<?= $row['client_wms_id']; ?>"><?= $row['client_wms_nama']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_principle" name="filter_principle" style="width:100%">
									<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
									<!-- <?php foreach ($Principle as $row) { ?>
										<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
									<?php } ?> -->
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 text-right">
					<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Memuat Data...</label>...</span>
					<button type="button" id="btncariAll" class="btn btn-primary" onclick="handlerFilterData(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class=" panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-xs-12">
						<div class="x_content table-responsive">
							<table id="table_laporan_stock_aging" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th class="text-center" name="CAPTION-DEPO">Depo</th>
										<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
										<th class="text-center" name="CAPTION-LOKASIPENYIMPANAN">Lokasi Penyimpanan</th>
										<th class="text-center" name="CAPTION-KODESKU">Kode SKU</th>
										<th class="text-center" name="CAPTION-SKU">SKU</th>
										<th class="text-center" name="CAPTION-SKUSATUAN">SKU Satuan</th>
										<th class="text-center" name="CAPTION-SKUKEMASAN">SKU Kemasan</th>
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

	<div class="modal fade" id="modal-detailSKUKode" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-lg" style="width:80%;">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title inputTitle"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table id="table_stock_detail" width="100%" class="table table-striped text-center">
								<thead>
									<tr style="background:#F0EBE3;">
										<th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
										<th class="text-center" name="CAPTION-DEPO">Depo</th>
										<th class="text-center" name="CAPTION-GUDANG">Gudang</th>
										<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
										<th class="text-center" name="CAPTION-IDPRODUK">ID Produk</th>
										<th class="text-center" name="CAPTION-NAMAPRODUK">Nama Produk</th>
										<th class="text-center" name="CAPTION-IDDOKUMEN">ID Dokumen</th>
										<th class="text-center" name="CAPTION-DRIVER">Driver</th>
										<th class="text-center" name="CAPTION-KETERANGAN">Keterangan</th>
										<th class="text-center" name="CAPTION-TIPEMUTASISTOCK">Tipe Mutasi Stock</th>
										<th class="text-center" name="CAPTION-QTY">QTY</th>
										<th class="text-center" name="CAPTION-SISASTOCK">Sisa Stock</th>
										<!-- <th class="text-center" name="CAPTION-STOCKMASUK">Stock Masuk</th>
										<th class="text-center" name="CAPTION-STOCKKELUAR">Stock Keluar</th> -->
										<!-- <th class="text-center">Total</th> -->
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 d-flex justify-content-start">
							<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left; display: none" id="composite">
								<span class="badge badge-light" style="color: white" id="karton">0</span>
							</button>
							<!-- <button type="button" onclick="hitungQuantity()" class="btn btn-warning" style="font-weight: bold; float:left; display: none;">
							<i class="fas fa-calculator"></i> Hitung Quantity
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Karton <span class="badge badge-light" style="color: white" id="karton">0</span>
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Pack <span class="badge badge-light" style="color: white" id="pack">0</span>
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Pcs <span class="badge badge-light" style="color: white" id="pcs">0</span>
						</button> -->
						</div>
						<div class="col-md-6">
							<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>