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
			<div class="title_left">
				<h3><span name="CAPTION-LAPORANAKTIVITASPENGGUNA">Laporan Aktivitas Pengguna</span></h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="form-group">
						<div class="row">
							<div class="col-xs-6">
								<label name="CAPTION-TANGGAL">Tanggal</label>
								<input type="text" id="filter_tanggal" name="filter_tanggal" class="form-control" value="" />
							</div>
							<div class="col-xs-6">
								<label name="CAPTION-DIVISI">Divisi</label>
								<select class="form-control select2" id="filter_divisi" name="filter_divisi" style="width:100%">
									<option value="">** <label name="CAPTION-PILIH">Pilih</label> **</option>
									<?php foreach ($Divisi as $row) { ?>
										<option value="<?= $row['karyawan_divisi_id']; ?>"><?= $row['karyawan_divisi_nama']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div><br>
						<div class="row">
							<div class="col-xs-12">
								<span id="loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
								<button type="button" id="btncariAll" class="btn btn-primary" onclick="handlerFilterData()"><i class="fa fa-refresh"></i> <span name="CAPTION-REFRESH">Refresh</span></button>
							</div>
						</div>
					</div>
				</div>
				<div class="x_panel">
					<div class="row">
						<div class="col-xs-12">
							<div class="x_content table-responsive">
								<table id="table_list_aktivitas_karyawan" width="100%" class="table table-bordered">
									<thead id="table-head">
										<!-- JS akan mengisi <tr><th>...</th></tr> di sini -->
									</thead>
									<tbody id="table-body">
										<!-- JS akan mengisi <tr><td>...</td></tr> di sini -->
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

<div class="modal fade" id="modal_detail_fdjr" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail FDJR</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
									<div class="col-md-4">
										<input type="text" id="karyawan_nama_fdjr" class="form-control" name="karyawan_nama_fdjr" autocomplete="off" value="" disabled>
									</div>

									<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
									<div class="col-md-4">
										<input type="text" id="divisi_fdjr" class="form-control" name="divisi_fdjr" autocomplete="off" value="" disabled>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_fdjr" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No FDJR</th>
											<th class="text-center" style="color:white;">Tanggal Kirim</th>
											<th class="text-center" style="color:white;">No DO</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Qty (Pcs)</th>
											<th class="text-center" style="color:white;">Qty (Krt)</th>
											<th class="text-center" style="color:white;">Qty Kirim (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Kirim (Krt)</th>
											<th class="text-center" style="color:white;">Qty Gagal Kirim (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Gagal Kirim (Krt)</th>
											<th class="text-center" style="color:white;">Persentasi (%)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_bkb" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Keluar Barang (BKB)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="form-horizontal">
								<div class="form-group">
									<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
									<div class="col-md-4">
										<input type="text" id="karyawan_nama_bkb" class="form-control" name="karyawan_nama_bkb" autocomplete="off" value="" disabled>
									</div>

									<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
									<div class="col-md-4">
										<input type="text" id="divisi_bkb" class="form-control" name="divisi_bkb" autocomplete="off" value="" disabled>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_bkb" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No BKB</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty Plan (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Plan (Krt)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_pb" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_pb" class="form-control" name="karyawan_nama_pb" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_pb" class="form-control" name="divisi_pb" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_pb" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No PB</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty Plan (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Plan (Krt)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_btb_retur" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_btb_retur" class="form-control" name="karyawan_nama_btb_retur" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_btb_retur" class="form-control" name="divisi_btb_retur" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_btb_retur" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No BTB</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty Plan (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Plan (Krt)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Ambil (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_stok_opnam" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_stok_opnam" class="form-control" name="karyawan_nama_stok_opnam" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_stok_opnam" class="form-control" name="divisi_stok_opnam" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_stok_opnam" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No Dokumen</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty Aktual (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Aktual (Krt)</th>
											<th class="text-center" style="color:white;">Qty Sistem (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Sistem (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_koreksi_stok" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_koreksi_stok" class="form-control" name="karyawan_nama_koreksi_stok" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_koreksi_stok" class="form-control" name="divisi_koreksi_stok" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_koreksi_stok" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No Dokumen</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty Plan (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Plan (Krt)</th>
											<th class="text-center" style="color:white;">Qty Aktual (Pcs)</th>
											<th class="text-center" style="color:white;">Qty Aktual (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_mutasi_stok" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_mutasi_stok" class="form-control" name="karyawan_nama_mutasi_stok" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_mutasi_stok" class="form-control" name="divisi_mutasi_stok" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_mutasi_stok" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No Dokumen</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty (Pcs)</th>
											<th class="text-center" style="color:white;">Qty (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detail_mutasi_pallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xlg" style="width: 95%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label>Detail Bukti Terima Barang Retur (BTB Retur)</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="col-md-2 control-label" name="CAPTION-KARYAWAN">Karyawan</label>
							<div class="col-md-4">
								<input type="text" id="karyawan_nama_mutasi_pallet" class="form-control" name="karyawan_nama_mutasi_pallet" autocomplete="off" value="" disabled>
							</div>

							<label class="col-md-2 control-label" name="CAPTION-DIVISI">Divisi</label>
							<div class="col-md-4">
								<input type="text" id="divisi_mutasi_pallet" class="form-control" name="divisi_mutasi_pallet" autocomplete="off" value="" disabled>
							</div>
						</div>
						<br>
						<div class="x_panel" style="width:100%;">
							<div class="x_content table-responsive">
								<table id="table_detail_mutasi_pallet" width="100%" class="table table-border table-bordered">
									<thead>
										<tr class="bg-primary">
											<th class="text-center" style="color:white;">No</th>
											<th class="text-center" style="color:white;">No Dokumen</th>
											<th class="text-center" style="color:white;">Tanggal</th>
											<th class="text-center" style="color:white;">Pallet</th>
											<th class="text-center" style="color:white;">SKU Kode</th>
											<th class="text-center" style="color:white;">SKU</th>
											<th class="text-center" style="color:white;">Tanggal Kadaluarsa</th>
											<th class="text-center" style="color:white;">Qty (Pcs)</th>
											<th class="text-center" style="color:white;">Qty (Krt)</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-sign-out"></i> Tutup</button>
			</div>
		</div>
	</div>
</div>