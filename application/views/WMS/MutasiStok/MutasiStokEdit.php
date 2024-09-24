<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-120005000">Draft Mutasi Stok</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-FORM">Form</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NODRAFTMUTASI">No Draft Mutasi</label>
							<div class="col-md-6 col-sm-6">
								<input type="text" id="mutasi_no" class="form-control" name="mutasi_no" value="<?= $Header->tr_mutasi_stok_kode ?>" readonly />
								<input type="hidden" id="tr_mutasi_stok_id" class="form-control" name="tr_mutasi_stok_id" value="<?= $Header->tr_mutasi_stok_id ?>" readonly />
								<input type="hidden" id="tr_mutasi_stok_tgl_update" class="form-control" name="tr_mutasi_stok_tgl_update" value="<?= $Header->tr_mutasi_stok_tgl_update ?>" readonly />
								<input type="hidden" id="tr_mutasi_stokt_who_update" class="form-control" name="tr_mutasi_stokt_who_update" value="<?= $Header->tr_mutasi_stokt_who_update ?>" readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TGLDRAFTMUTASI">Tgl Draft Mutasi</label>
							<div class="col-md-6 col-sm-6">
								<input type="text" class="form-control datepicker" name="mutasi_tanggal" id="mutasi_tanggal" value="<?= $Header->tr_mutasi_stok_tanggal ?>" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_perusahaan" name="mutasi_perusahaan" class="form-control select2" style="width:100%" onchange="GetPrinciple(this.value)" disabled>
									<option value="">** <span name="CAPTION-PILIHPERUSAHAAN">Pilih Perusahaan</span> **</option>
									<?php foreach ($getClientWms as $row) { ?>
										<option value="<?= $row->client_wms_id ?>" <?= $row->client_wms_id == $Header->client_wms_id ? 'selected' : '' ?>><?= $row->client_wms_nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_principle" name="mutasi_principle" class="form-control select2" style="width:100%" onchange="GetCheckerPrinciple(this.value)" disabled>
									<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
									<?php foreach ($Principle as $row) { ?>
										<option value="<?= $row->id ?>" <?= $row->id == $Header->principle_id ? 'selected' : '' ?>><?= $row->kode ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGASAL">Gudang Asal</label>
							<div class="col-md-6 col-sm-6">
								<select id="gudang_asal_mutasi" name="gudang_asal_mutasi" class="form-control select2" style="width:100%" onchange="ViewPallet()" disabled>
									<option value="">** <label name="CAPTION-PILIHGUDANG">Pilih Gudang</label> **</option>
									<?php foreach ($Gudang as $row) { ?>
										<option value="<?= $row['depo_detail_id']; ?>" <?= $row['depo_detail_id'] == $Header->depo_detail_asal ? 'selected' : '' ?>><?= $row['depo_detail_nama']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKER">Checker</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_checker" name="mutasi_checker" class="form-control select2" style="width:100%" disabled>
									<option value="">** <label name="CAPTION-PILIHCHECKER">Pilih Checker</label> **</option>
									<?php foreach ($Checker as $row) { ?>
										<option value="<?= $row['karyawan_id'] . " || " . $row['karyawan_nama']; ?>" <?= $row['karyawan_nama'] == $Header->tr_mutasi_stok_nama_checker ? 'selected' : '' ?>><?= $row['karyawan_nama']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
							<div class="col-md-6 col-sm-6">
								<textarea class="form-control" id="mutasi_keterangan" name="mutasi_keterangan" disabled><?= $Header->tr_mutasi_stok_keterangan; ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS">Status</label>
							<div class="col-md-6 col-sm-6">
								<input type="text" name="mutasi_status" id="mutasi_status" class="form-control" value="<?= $Header->tr_mutasi_stok_status ?>" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align"></label>
							<div class="col-md-6 col-sm-6">
								<input type="checkbox" name="mutasi_approval" style="transform: scale(1.5);" id="mutasi_approval" value="In Progress Approval" <?= $Header->tr_mutasi_stok_status != 'Draft' ? 'checked' : '' ?> disabled> &nbsp;<label name="CAPTION-PENGAJUANAPPROVAL"> Pengajuan Approval</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<input type="hidden" id="txt_jml_sku" value="0">
					<table id="table_mutasi" width="100%" class="table table-bordered">
						<thead>
							<tr style="background:#F0EBE3;">
								<th class="text-center">#</th>
								<th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
								<th class="text-center" name="CAPTION-SKU">SKU</th>
								<th class="text-center" name="CAPTION-SKUSATUAN">SKU Satuan</th>
								<th class="text-center" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
								<th class="text-center" name="CAPTION-PALLETASAL">Pallet Asal</th>
								<th class="text-center" name="CAPTION-QTY">Qty</th>
								<th class="text-center" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="row pull-right">
					<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <span name="CAPTION-LOADING">Loading</span>...</span>
					<button class="btn btn-success" id="btn_konfirmasi_mutasi"><i class="fa fa-save"></i> <span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>
					<button class="btn btn-success" id="btn_update_mutasi"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
					<a href="<?= base_url(); ?>WMS/MutasiStok/MutasiStokMenu" class="btn btn-danger" id="btn_kembali"><i class="fa fa-reply"></i> <span name="CAPTION-MENUUTAMA">Menu Utama</span></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-PILIHSKU">Pilih SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
								<label name="CAPTION-GUDANGASAL">Gudang Asal</label>
							</div>
							<div class="col-lg-4 col-sm-4 col-xs-8">
								<input type="text" class="form-control" id="gudang_asal_sku" value="" readonly>
							</div>
						</div>
					</div>
				</div><br>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
								<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
							</div>
							<div class="col-lg-4 col-sm-4 col-xs-8">
								<input type="text" class="form-control" id="perusahaan_sku" value="" readonly>
							</div>
						</div>
					</div>
				</div><br>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
								<label name="CAPTION-PRINCIPLE">Principle</label>
							</div>
							<div class="col-lg-4 col-sm-4 col-xs-8">
								<input type="text" class="form-control" id="principle_sku" value="" readonly>
							</div>
						</div>
					</div>
				</div><br>
				<div class="row">
					<div class="col-xs-12">
						<table id="data-table-sku" width="100%" class="table table-bordered">
							<thead>
								<tr style="background:#F0EBE3;">
									<th class="text-center"><input type="checkbox" name="select-all-sku" id="select-all-sku" value="1"></th>
									<th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
									<th class="text-center" name="CAPTION-SKU">SKU</th>
									<th class="text-center" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
									<th class="text-center" name="CAPTION-SKUSTOCK">SKU Stock</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="btn-choose-sku-multi"><span name="CAPTION-PILIH">Pilih</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbackpallet"><span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-mutasi-stok-detail" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 90%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-PILIHSKU">Pilih SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-4">
										<label name="CAPTION-SKUKODE">SKU Kode</label>
										<input type="text" id="filter_mutasi_stok_detail_sku_kode" class="form-control" name="filter_mutasi_stok_detail_sku_kode" value="" disabled />
										<input type="hidden" id="filter_mutasi_stok_detail_sku_id" class="form-control" name="filter_mutasi_stok_detail_sku_id" value="" disabled />
										<input type="hidden" id="filter_mutasi_stok_detail_sku_stock_id" class="form-control" name="filter_mutasi_stok_detail_sku_stock_id" value="" disabled />
										<input type="hidden" id="filter_mutasi_stok_detail_tr_mutasi_stok_detail_id" class="form-control" name="filter_mutasi_stok_detail_tr_mutasi_stok_detail_id" value="" disabled />
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKU">SKU</label>
										<input type="text" id="filter_mutasi_stok_detail_sku" class="form-control" name="filter_mutasi_stok_detail_sku" value="" disabled />
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</label>
										<input type="text" id="filter_mutasi_stok_detail_sku_stock_expired_date" class="form-control" name="filter_mutasi_stok_detail_sku_stock_expired_date" value="" disabled>
									</div>
								</div><br>
								<div class="row">
									<div class="col-xs-4">
										<label name="CAPTION-SKUSATUAN">SKU Satuan</label>
										<input type="text" id="filter_mutasi_stok_detail_sku_satuan" class="form-control" name="filter_mutasi_stok_detail_sku_satuan" value="" disabled>
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-QTY">Qty</label>
										<input type="text" id="filter_mutasi_stok_detail_sku_stock_qty" class="form-control" name="filter_mutasi_stok_detail_sku_stock_qty" value="" disabled>
									</div>
									<div class="col-xs-4">
										<label name="CAPTION-PALLETASAL">Pallet Asal</label>
										<input type="text" id="filter_mutasi_stok_detail_pallet_asal" class="form-control" name="filter_mutasi_stok_detail_pallet_asal" value="" disabled>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="row">
								<div class="col-xs-12">
									<div class="x_content table-responsive">
										<button class="btn btn-primary" id="btn_tambah_tujuan_mutasi"><i class="fa fa-plus"></i> <span name="CAPTION-TAMBAHTUJUANMUTASI">Tambah Tujuan Mutasi</span></button>
										<table id="table_mutasi_stok_detail" width="100%" class="table table-striped table-bordered">
											<thead>
												<tr class="bg-primary">
													<th class="text-center" style="color:white;text-align: center; vertical-align: middle;">#</th>
													<th class="text-center" style="color:white;text-align: center; vertical-align: middle;"><span name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</span></th>
													<th class="text-center" style="color:white;text-align: center; vertical-align: middle;"><span name="CAPTION-PALLETTUJUAN">Pallet Tujuan</span></th>
													<th class="text-center" style="color:white;text-align: center; vertical-align: middle;"><span name="CAPTION-QTY">Qty</span></th>
													<th class="text-center" style="color:white;text-align: center; vertical-align: middle;"><span name="CAPTION-ACTION">Action</span></th>
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
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-save-mutasi-stok-detail"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btn-tutup-mutasi-stok-detail"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>