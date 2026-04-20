<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-LAPORANGAGALKIRIM">Laporan Gagal Kirim</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALFDJR">Tanggal FDJR</label>
							<div class="col-md-8 col-sm-8">
								<input type="date" class="form-control" id="filter_tanggal_fdjr" name="filter_tanggal_fdjr" value="<?= date('Y-m-d') ?>" />
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
							<div class="col-md-8 col-sm-8">
								<!-- <input type="text" class="form-control" id="filter_driver" name="filter_driver" value="" /> -->
								<select class="form-control select2" id="filter_driver" name="filter_driver" style="width:100%">
									<option value=""><label name="CAPTION-PILIH">Pilih</label></option>
									<?php foreach ($Driver as $row) { ?>
										<option value="<?= $row['karyawan_id']; ?>"><?= $row['karyawan_nama']; ?>
										</option>
									<?php } ?>
								</select>
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
									<tr class="bg-primary">
										<th class="text-center" style="color:white;" name="CAPTION-NO">NO</th>
										<th class="text-center" style="color:white;" name="CAPTION-NAMA DRIVER">Nama Driver</th>
										<th class="text-center" style="color:white;" name="CAPTION-TGLFDJR">Tgl FDJR</th>
										<th class="text-center" style="color:white;" name="CAPTION-KODEFDJR">Kode FDJR</th>
										<th class="text-center" style="color:white;" name="CAPTION-KODESO">Kode SO</th>
										<th class="text-center" style="color:white;" name="CAPTION-NOSOPO">No SO Eksternal</th>
										<th class="text-center" style="color:white;" name="CAPTION-KODEDO">Kode DO</th>
										<th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>
										<th class="text-center" style="color:white;" name="CAPTION-ALAMAT">Alamat</th>
										<th class="text-center" style="color:white;" name="CAPTION-KODESKU">Kode SKU</th>
										<th class="text-center" style="color:white;" name="CAPTION-SKU">SKU</th>
										<th class="text-center" style="color:white;" name="CAPTION-QTYORDER">QTY Order</th>
										<th class="text-center" style="color:white;" name="CAPTION-QTYTERKIRIM">QTY Terkirim</th>
										<th class="text-center" style="color:white;" name="CAPTION-QTYGAGAL">QTY Gagal</th>
										<th class="text-center" style="color:white;" name="CAPTION-REASON">Keterangan</th>
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

		<div class="modal fade" id="modalViewDetailBiaya" role="dialog" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h4 class="modal-title"><label name="CAPTION-DETAILBIAYA">Detail Biaya</label></h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12 table-responsive">
								<table class="table table-striped" id="table-detail-biaya">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;" name="CAPTION-TIPEBIAYA">Tipe Biaya</th>
											<th class="text-center" style="color:white;" name="CAPTION-NOMINAL">Nominal</th>
											<th class="text-center" style="color:white;" name="CAPTION-KETERANGAN">Keterangan</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
					</div>
				</div>
			</div>
		</div>
	</div>