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
			<div class="title_left" id="pp1">
				<h3 id="nm_page" name="CAPTION-LAPORANTRANSAKSISTOCKMOVEMENTBATCHEDBARANG">Laporan Stock Movement Batch ED Barang</h3>
			</div>
			<div class="title_left" id="pp2" style="display: none;">
				<h3 id="nm_page" name="CAPTION-LAPORANTRANSAKSISTOCKMOVEMENTBARANG">Laporan Stock Movement Barang</h3>
			</div>
		</div>

		<div class="clearfix"></div>
		<div class="wrapper">
			<input type="radio" name="selectSplit" id="option-1" value="0" class="chkradio" onchange="chkradio(event)" checked>
			<input type="radio" name="selectSplit" id="option-2" value="1" class="chkradio" onchange="chkradio(event)">
			<label for="option-1" class="option option-1">
				<span>ED Batch Barang</span>
			</label>
			<label for="option-2" class="option option-2">
				<span>Barang</span>
			</label>
		</div>
		<hr>
		<div id="page1">
			<div class="panel panel-default">
				<div class="panel-body form-horizontal form-label-left">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal</label>
								<div class="col-md-8 col-sm-8">
									<div class="row">
										<div class="col-md-4">
											<input type="date" disabled class="form-control" id="filter_stock_tanggal" name="filter_stock_tanggal" value="<?= getLastTbgDepo() ?>" />
										</div>
										<div class="col-md-8">
											<select onchange="chgDate(this.value)" class="form-control col=lg-4 col-md-4 col-sm-4" name="mode" id="mode">
												<option value="realtime">Realtime</option>
												<option value="histori">Histori</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="form-group">
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
							</div> -->
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
						<button type="button" id="btncariAll" class="btn btn-primary" onclick="handlerFilterData(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class=" panel-body form-horizontal form-label-left">
					<div class="row">
						<div class="col-xs-12">
							<div class="x_content table-responsive">
								<table id="table_detail" width="100%" class="table table-striped text-center">
									<thead>
										<tr style="background:#F0EBE3;">
											<th class="text-center" name="CAPTION-DEPO">Depo</th>
											<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
											<th class="text-center" name="CAPTION-LOKASIPENYIMPANAN">Lokasi Penyimpanan</th>
											<th class="text-center" name="CAPTION-KODEBARANG">Kode Barang</th>
											<th class="text-center" name="CAPTION-NAMABARANG">Nama Barang</th>
											<th class="text-center" name="CAPTION-BATCHNO">Batch No</th>
											<th class="text-center" name="CAPTION-ED">ED</th>
											<th class="text-center" name="CAPTION-KEMASAN">Kemasan</th>
											<th class="text-center" name="CAPTION-STOCKAWAL">Stock Awal</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-OPNAMEIN">Opname In</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PENERIMAANSUPPLIER">Penerimaan Supplier</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PENERIMAANRETUROUTLET">Penerimaan Retur Outlet</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-MUTASIININTERNANTARGUDANG">Mutasi In Intern Antar Gudang</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-MUTASIININTERNANTARCABANG">Mutasi In Extern Antar Cabang</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-KOREKSIADJUSTMENIN">Koreksi Adjustment In</th>
											<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PEMBONGKARANIN">Pembongkaran In</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Penjualan</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-OPNAMEOUT">Opname Out</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Retur Supplier</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Pemusnahan</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-MUTASIOUTINTERNANTARGUDANG">Mutasi Out Intern Antar Gudang</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-MUTASIOUTEXTERNANTARCABANG">Mutasi Out Extern Antar Cabang</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-KOREKSIADJUSTMENOUT">Koreksi Adjustment Out</th>
											<th class="text-center" style="background:#FF8F8F;" name="CAPTION-PEMBONGKARANOUT">Pembongkaran Out</th>
											<th class="text-center" name="CAPTION-STOCKAKHIR">Stock Akhir</th>
											<!-- <th class="text-center" name="CAPTION-SALDOAKHIRPERHITUNGANMUTASI">Saldo Akhir Perhitungan Mutasi</th> -->
										</tr>
									</thead>
									<tbody id="konten-tablelaporanstocksku">

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="page2" style="display:none;">
			<div class="panel panel-default" id='pp'>
				<div class="panel-body form-horizontal form-label-left">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal</label>
								<div class="col-md-8 col-sm-8">
									<div class="row">
										<div class="col-md-4">
											<input disabled type="date" class="form-control" id="filter_stock_tanggal1" name="filter_stock_tanggal" value="<?= getLastTbgDepo() ?>" />
										</div>
										<div class="col-md-8">
											<select onchange="chgDate1(this.value)" class="form-control col=lg-4 col-md-4 col-sm-4" name="mode1" id="mode1">
												<option value="realtime">Realtime</option>
												<option value="histori">Histori</option>
											</select>
										</div>
									</div>
									<!-- <input type="text" class="form-control" id="filter_stock_tanggal1" name="filter_stock_tanggal" value="" /> -->
								</div>
							</div>
							<!-- <div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PT">PT</label>
								<div class="col-md-8 col-sm-8">
									<select class="form-control select2" id="filter_stock_pt1" name="filter_stock_pt" style="width:100%">
										<option value=""><label name="CAPTION-ALL">All</label></option>
										<?php foreach ($ClientWMS as $row) { ?>
											<option value="<?= $row['client_wms_id']; ?>"><?= $row['client_wms_nama']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div> -->
							<div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANG">Gudang</label>
								<div class="col-md-8 col-sm-8">
									<select class="form-control select2" id="filter_stock_gudang1" name="filter_stock_gudang" style="width:100%">
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
									<select class="form-control select2" id="filter_stock_principle1" name="filter_stock_principle" style="width:100%">
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
											<input type="text" class="form-control col=lg-2 col-md-2 col-sm-2" id="filter_stock_sku_kode1" name="filter_stock_sku_kode" placeholder="Kode" value="" />
										</div>
										<div class="col-md-8">
											<input type="text" class="form-control col=lg-4 col-md-4 col-sm-4" id="filter_stock_sku_nama1" name="filter_stock_sku_nama" placeholder="SKU" value="" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 text-right">
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Memuat Data...</label>...</span>
						<button type="button" id="btncariAll2" class="btn btn-primary" onclick="handlerFilterData2(event)"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body form-horizontal form-label-left">
					<div class="panel-body form-horizontal form-label-left">
						<div class="row">
							<div class="col-xs-12">
								<div class="x_content table-responsive">
									<table id="table_detail2" width="100%" class="table table-striped text-center">
										<thead>
											<tr style="background:#F0EBE3;">
												<th class="text-center" name="CAPTION-DEPO">Depo</th>
												<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
												<th class="text-center" name="CAPTION-LOKASIPENYIMPANAN">Lokasi Penyimpanan</th>
												<th class="text-center" name="CAPTION-KODEBARANG">Kode Barang</th>
												<th class="text-center" name="CAPTION-NAMABARANG">Nama Barang</th>
												<th class="text-center" name="CAPTION-KEMASAN">Kemasan</th>
												<th class="text-center" name="CAPTION-STOCKAWAL">Stock Awal</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-OPNAMEIN">Opname In</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PENERIMAANSUPPLIER">Penerimaan Supplier</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PENERIMAANRETUROUTLET">Penerimaan Retur Outlet</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-MUTASIININTERNANTARGUDANG">Mutasi In Intern Antar Gudang</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-MUTASIININTERNANTARCABANG">Mutasi In Extern Antar Cabang</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-KOREKSIADJUSTMENIN">Koreksi Adjustment In</th>
												<th class="text-center" style="background:#C3E2C2;" name="CAPTION-PEMBONGKARANIN">Pembongkaran In</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Penjualan</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-OPNAMEOUT">Opname Out</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Retur Supplier</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-BATCHNO">Pemusnahan</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-MUTASIOUTINTERNANTARGUDANG">Mutasi Out Intern Antar Gudang</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-MUTASIOUTEXTERNANTARCABANG">Mutasi Out Extern Antar Cabang</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-KOREKSIADJUSTMENOUT">Koreksi Adjustment Out</th>
												<th class="text-center" style="background:#FF8F8F;" name="CAPTION-PEMBONGKARANOUT">Pembongkaran Out</th>
												<th class="text-center" name="CAPTION-STOCKAKHIR">Stock Akhir</th>
												<!-- <th class="text-center" name="CAPTION-SALDOAKHIRPERHITUNGANMUTASI">Saldo Akhir Perhitungan Mutasi</th> -->
											</tr>
										</thead>
										<tbody id="konten-tablelaporanstockskupage2">

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