<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-101002013">Laporan Setoran Driver</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
							<div class="col-md-8 col-sm-8">
								<input type="text" class="form-control" id="filter_tanggal_kirim" name="filter_tanggal_kirim" value="" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PT">PT</label>
							<div class="col-md-8 col-sm-8">
								<select class="form-control select2" id="filter_perusahaan" name="filter_perusahaan" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($ClientWMS as $row) { ?>
										<option value="<?= $row['client_wms_id']; ?>"><?= $row['client_wms_nama']; ?>
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
								<select class="form-control select2" id="filter_principle" name="filter_principle" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Principle as $row) { ?>
										<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
							<div class="col-md-8 col-sm-8">
								<!-- <input type="text" class="form-control" id="filter_driver" name="filter_driver" value="" /> -->
								<select class="form-control select2" id="filter_driver" name="filter_driver" style="width:100%">
									<option value=""><label name="CAPTION-ALL">All</label></option>
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
									<tr style="background:#F0EBE3;">
										<th class="text-center" name="CAPTION-DRIVER">Driver</th>
										<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
										<th class="text-center" name="CAPTION-NOMINALTUNAI">Nominal Tunai</th>
										<th class="text-center" name="CAPTION-JUMLAHBAYAR">Jumlah Bayar</th>
										<th class="text-center" name="CAPTION-KURANGBAYAR">Kurang Bayar</th>
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