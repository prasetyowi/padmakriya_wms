<style>
	.input-group-addon button {
		display: inline-block;
		padding: 5px 10px;
		text-decoration: none;
		background-color: #286090;
		/* Warna dasar tombol */
		color: #fff;
		/* Warna teks */
		border-radius: 4px;
		/* Membuat sudut membulat */
		transition: background-color 0.3s ease;
		/* Animasi untuk hover */
	}

	.input-group-addon button:hover {
		background-color: #204d74;
		/* Warna saat hover */
		color: #fff;
		/* Warna teks saat hover */
		text-decoration: none;
		/* Pastikan tidak ada garis bawah */
	}
</style>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title child-proses-opname">
			<div class="text-center">
				<h3 name="CAPTION-HAPUSPICKINGORDER">Hapus Picking Order</h3>
			</div>

			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 ">
					<div class="x_panel">
						<div class="panel panel-default">
							<div class="panel-heading"><label name="CAPTION-PENCARIANPERMINTAANPengeluaranBarang">PENCARIAN PERMINTAAN PENGELUARAN BARANG</label>
								<ul class="nav navbar-right panel_toolbox">
									<li style="margin-left: 35px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</ul>
								<div class="clearfix"></div>
							</div>
							<div class="x_content" style="display: block;">
								<br>
								<div class="panel-body form-horizontal form-label-left" style="padding:0px">
									<div class="row">
										<div class="col-lg-6 col-xs-12">
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-CARINOPPB">Cari No PPB</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<input type="hidden" class="form-control" name="txtpickinglistid" id="txtpickinglistid" value="">
													<input type="hidden" class="form-control" name="txtidppb" id="txtidppb" value="" required="required" readonly>
													<input type="hidden" class="form-control" name="txtjumlahbarang" id="txtjumlahbarang" value="0">
													<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" readonly>
													<input type="hidden" class="form-control" name="tipeBKBMix" id="tipeBKBMix" readonly>
													<div class="input-group">
														<input type="text" style="height: 52px;" class="form-control" autocomplete="off" name="txtnoppb" id="txtnoppb" value="" required="required">
														<span class="input-group-addon"><button id="search_kode_ppb"><label name="CAPTION-CARI">CARI</label></button></span>
													</div>
													<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
														<table class="table table-striped table-sm table-hover" id="table-fixed">
															<tbody id="konten-table"></tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-NOPPB">NO PPB</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<input type="text" style="height: 45px;" class="form-control" name="txtnoppb2" id="txtnoppb2" value="" required="required" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-TGLPPB">Tgl PPB</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<input type="date" class="form-control input-date-start date" name="dateppb" id="dateppb" placeholder="dd-mm-yyyy" required="required" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<textarea class="form-control" id="txtketerangan" name="txtketerangan"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-DRIVER">Driver</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<input type="text" class="form-control" name="driverppb" id="driverppb" placeholder="Driver" required="required" readonly>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-xs-12">
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-NOPB">No Pb</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<select id="slcnopb" class="form-control" readonly></select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-NOFDJR">No FDJR</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<input type="text" class="form-control" name="txtnofdjr" id="txtnofdjr" required="required" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-TIPEPB"> Tipe PB</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<select id="slctipepb" class="form-control" readonly></select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-STATUSPPB">Status PB</label>
												<div class="col-md-6 col-sm-4 col-xs-10">
													<select id="slcstatusppb" class="form-control" readonly></select>
												</div>
											</div>
											<div class="form-group fieldFormChecker">
												<label class="col-form-label col-md-2 col-sm-2 col-xs-2 label-align" name="CAPTION-CHECKER">Checker</label>
												<div class="col-md-8 col-sm-8 col-xs-10">
													<select id="slccheckerheaderpb" class="form-control" readonly></select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<input type="hidden" id="txtcheckerpb" class="form-control" value="" />
				<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
					&nbsp;<label name="CAPTION-LOADING"></label></span>

				<div class="x_panel" id="panelbkbbulk" style="display: none;">
					<div class="panel panel-default">
						<div class="panel-heading"><label name="CAPTION-DETAILPENGELUARANBARANGBULK">Detail Pengeluaran Bulk</label>
							<ul class="nav navbar-right panel_toolbox">
								<li style="margin-left: 35px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display: block;">
							<div class="panel-body form-horizontal form-label-left">
								<div class="row">
									<table id="tabledetailpbbulk" width="100%" class="table table-striped">
										<thead>
											<tr>
												<th name="CAPTION-NO">No</th>
												<th name="CAPTION-PRINCIPLE">Principle</th>
												<th name="CAPTION-SKUKODE">Sku Kode</th>
												<th name="CAPTION-NAMABARANG">Nama Barang</th>
												<th style="display: none;" name="CAPTION-KEMASAN">Kemasan</th>
												<th name="CAPTION-SATUAN">Satuan</th>
												<th name="CAPTION-PERMINTAANEDBARANG">Permintaan Ed Barang</th>
												<!-- <th>Lokasi Gudang Barang</th> -->
												<th name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
												<th name="CAPTION-JUMLAHBARANGDIAMBIL">Jumlah Barang Diambil</th>
												<!-- <th>Permintaan ED Barang</th> -->
												<!-- <th name="CAPTION-CHECKER"></th> -->
												<!-- <th name="CAPTION-DETAIL">Detail</th> -->
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<!-- <div class="row pull-right">
									<button type="button" onclick="showPrincipleBKB('bulk')" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-LIHATBKBBULK">Lihat Bkb Bulk</label></button>
									<button type="button" id="btncetakbkbbulkplan" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKBKBPLAN">Cetak BKB Plan</label></button>
									<button type="button" id="btncetakbkbbulk" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKBKBAKTUAL">Cetak BKB Aktual</label></button>
								</div> -->
							</div>
						</div>
					</div>
				</div>

				<div class="x_panel" id="panelbkbstandar" style="display: none;">
					<div class="panel panel-default">
						<div class="panel-heading"><label name="CAPTION-DETAILPENGELUARANBARANGSTANDAR">Detail Pengeluaran Barang Standar</label>
							<ul class="nav navbar-right panel_toolbox">
								<li style="margin-left: 35px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display: block;">
							<div class="panel-body form-horizontal form-label-left">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKERGUDANG">Checker</label>
											<div class="col-md-8 col-sm-8">
												<select id="slccheckerpb" class="form-control" style="width:100%;"></select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<table id="tabledetailpbstandar" width="100%" class="table table-striped">
										<thead>
											<tr>
												<th name="CAPTION-NO"></th>
												<th name="CAPTION-NODO"></th>
												<th name="CAPTION-SKUBARANGWMS"></th>
												<th name="CAPTION-NAMABARANG"></th>
												<th name="CAPTION-KEMASAN"></th>
												<th name="CAPTION-SATUAN"></th>
												<th name="CAPTION-JUMLAHBARANG"></th>
												<th name="CAPTION-PERMINTAANEDBARANG"></th>
												<th name="Lokasi Gudang Barang"></th>
												<th name="CAPTION-JUMLAHBARANGDIAMBIL"></th>
												<!-- <th>Permintaan ED Barang</th> -->
												<!-- <th name="CAPTION-DETAIL"></th> -->
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<!-- <div class="row pull-right">
									<button type="button" id="btnlihatbkbstandar" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-LIHATBKBSTANDAR">Lihat</label></button>
									<button type="button" id="btncetakbkbstandar" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKREKAP">Cetak Rekap</label></button>
									<button type="button" id="btncetaklabel" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKLABELKERANJANG">Cetak Label Keranjang</label></button>
								</div> -->
							</div>
						</div>
					</div>
				</div>

				<div class="x_panel" id="panelbkbkirimulang" style="display: none;">
					<div class="panel panel-default">
						<div class="panel-heading"><label>Detail Pengeluaran Barang DO Reschedule</label>
							<ul class="nav navbar-right panel_toolbox">
								<li style="margin-left: 35px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display: block;">
							<div class="panel-body form-horizontal form-label-left">
								<div class="row">
									<table id="tabledetailpbkirimulang" width="100%" class="table table-striped">
										<thead>
											<tr>
												<th name="CAPTION-NO">No</th>
												<th name="CAPTION-PRINCIPLE">Principle</th>
												<th name="CAPTION-SKUKODE">Sku Kode</th>
												<th name="CAPTION-NAMABARANG">Nama Barang</th>
												<th name="CAPTION-KEMASAN">Kemasan</th>
												<th name="CAPTION-SATUAN">Satuan</th>
												<th name="CAPTION-PERMINTAANEDBARANG">Permintaan Ed barang</th>
												<!-- <th>Lokasi Gudang Barang</th> -->
												<th name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
												<th name="CAPTION-JUMLAHBARANGDIAMBIL">Jumlah Barang Diambil</th>
												<!-- <th>Permintaan ED Barang</th> -->
												<!-- <th name="CAPTION-CHECKER"></th> -->
												<!-- <th name="CAPTION-DETAIL">Detail</th> -->
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div class="row pull-right">
									<button type="button" onclick="showPrincipleBKB('reschedule')" class="btn btn-primary"><i class="fa fa-search"></i> <span>Lihat BKB Reschedule</span></button>
									<button type="button" id="btncetakbkbKirimulangplan" class="btn btn-primary"><i class="fa fa-print"></i> <span name="CAPTION-CETAKBKBPLAN">Cetak BKB Plan</span></button>
									<button type="button" id="btncetakbkbkirimulang" class="btn btn-primary"><i class="fa fa-print"></i> <span name="CAPTION-CETAKBKBAKTUAL">Cetak BKB Aktual</span></button>
									<!-- <button type="button" id="btncetakbkbkirimulang" class="btn btn-primary"><i class="fa fa-print"></i> <label>Cetak BKB Per Principal</label></button> -->
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="x_panel" id="panelbkbcanvas" style="display: none;">
					<div class="panel panel-default">
						<div class="panel-heading"><label name="CAPTION-DETAILPENGELUARANBARANGCANVAS">Detail Pengeluaran Barang Canvas</label>
							<ul class="nav navbar-right panel_toolbox">
								<li style="margin-left: 35px;"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display: block;">
							<div class="panel-body form-horizontal form-label-left">
								<div class="row">
									<table id="tabledetailpbcanvas" width="100%" class="table table-striped">
										<thead>
											<tr>
												<th name="CAPTION-NO">No</th>
												<th name="CAPTION-PRINCIPLE">Princple</th>
												<th name="CAPTION-SKUKODE">Sku Kode</th>
												<th name="CAPTION-NAMABARANG">Nama Barang</th>
												<th name="CAPTION-KEMASAN">Kemasan</th>
												<th name="CAPTION-SATUAN">Satuan</th>
												<th name="CAPTION-PERMINTAANEDBARANG">Permintaan Ed Barang</th>
												<!-- <th>Lokasi Gudang Barang</th> -->
												<th name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
												<th name="CAPTION-JUMLAHBARANGDIAMBIL">Jumlah Barang Diambil</th>
												<!-- <th>Permintaan ED Barang</th> -->
												<!-- <th name="CAPTION-CHECKER"></th> -->
												<!-- <th name="CAPTION-DETAIL">Detail</th> -->
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<!-- <div class="row pull-right">
									<button type="button" onclick="showPrincipleBKB('canvas')" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-LIHATBKBCANVAS">Lihat Bkb Canvas</label></button>
									<button type="button" id="btncetakbkbcanvasplan" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKBKBPERPRINCIPLE">Cetak Bkb Plan</label></button>
									<button type="button" id="btncetakbkbcanvas" class="btn btn-primary"><i class="fa fa-print"></i> <label name="CAPTION-CETAKBKBPERPRINCIPLE">Cetak BKB Aktual</label></button>
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel">
				<div class="panel-body form-horizontal form-label-left">
					<div class="row pull-right">
						<button class="btn btn-danger" onclick="deletePickingOrder()"><label for="" name="CAPTION-HAPUS">Hapus</label></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>