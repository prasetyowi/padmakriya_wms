<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-DRAFTMUTASIPALLET">Draft Mutasi Pallet</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="panel panel-default">
			<div class="panel-heading"><label name="CAPTION-FORM">Form</label></div>
			<div class="panel-body form-horizontal form-label-left">
				<div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NODRAFTMUTASI">No
								Draft Mutasi</label>
							<div class="col-md-6 col-sm-6">
								<input type="text" id="mutasi_draft_no_draft" class="form-control" name="mutasi_draft_no_draft" value="" readonly />
								<input type="hidden" id="mutasi_pallet_draft_id" class="form-control" name="mutasi_pallet_draft_id" value="<?= $mutasi_pallet_draft_id; ?>" readonly />
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TGLDRAFTMUTASI">Tgl Draft Mutasi</label>
							<div class="col-md-6 col-sm-6">
								<input type="date" class="form-control" name="mutasi_draft_tanggal" id="mutasi_draft_tanggal" required value="<?= getLastTbgDepo() ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_draft_tipe_transaksi" name="mutasi_draft_tipe_transaksi" class="form-control select2" style="width:100%" onchange="GetGudangTujuan(this.value)" required>
									<option value="">** <label name="CAPTION-PILIHTIPE">Pilih Tipe</label> **</option>
									<!-- <option value="Mutasi Pallet Antar Gudang">Mutasi Pallet Antar Gudang</option> -->
									<?php foreach ($TipeTransaksi as $row) { ?>
										<option value="<?= $row['tipe_mutasi_id']; ?>"><?= $row['tipe_transaksi']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGASAL">Gudang
								Asal</label>
							<div class="col-md-6 col-sm-6">
								<select id="gudang_asal_mutasi_draft" name="gudang_asal_mutasi_draft" class="form-control select2" style="width:100%" onchange="ViewPallet()" required>
									<option value="">** <label name="CAPTION-PILIHGUDANG">Pilih Gudang</label> **
									</option>
									<?php foreach ($Gudang as $row) { ?>
										<option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</label>
							<div class="col-md-6 col-sm-6">
								<select id="gudang_tujuan_mutasi_draft" name="gudang_tujuan_mutasi_draft" class="form-control select2" style="width:100%" required>
									<option value="">** <label name="CAPTION-PILIHGUDANG">Pilih Gudang</label> **
									</option>
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
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_draft_perusahaan" name="mutasi_draft_perusahaan" class="form-control select2" style="width:100%" onchange="GetPrinciple(this.value)" required>
									<option value="">** <label name="CAPTION-PILIHPERUSAHAAN">Pilih Perusahaan</label> **
									</option>
									<?php foreach ($getClientWms as $row) { ?>
										<option value="<?= $row->client_wms_id ?>"><?= $row->client_wms_nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_draft_principle" name="mutasi_draft_principle" class="form-control select2" style="width:100%" onchange="GetCheckerPrinciple(this.value)" required></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKER">Checker</label>
							<div class="col-md-6 col-sm-6">
								<select id="mutasi_draft_checker" name="mutasi_draft_checker" class="form-control select2" style="width:100%" required>
									<option value="">** <label name="CAPTION-PILIHCHECKER">Pilih Checker</label> **
									</option>
									<!-- <?php foreach ($Checker as $row) { ?>
                              <option value="<?= $row['karyawan_id'] . " || " . $row['karyawan_nama']; ?>"><?= $row['karyawan_nama']; ?></option>
                           <?php } ?> -->
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
							<div class="col-md-6 col-sm-6">
								<textarea class="form-control" id="mutasi_draft_keterangan" name="mutasi_draft_keterangan" required></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS">Status</label>
							<div class="col-md-6 col-sm-6">
								<input type="text" name="mutasi_draft_status" id="mutasi_draft_status" class="form-control" value="Draft" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-form-label col-md-4 col-sm-4 label-align"></label>
							<div class="col-md-6 col-sm-6">
								<input type="checkbox" name="mutasi_draft_approval" style="transform: scale(1.5);" id="mutasi_draft_approval" value="In Progress Approval"> &nbsp;<label name="CAPTION-PENGAJUANAPPROVAL"> Pengajuan
									Approval</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row" style="margin-bottom: 10px;">
					<button class="btn btn-primary" id="btn_pilih_pallet_mutasi_draft"><i class="fa fa-plus"></i> <label name="CAPTION-PILIHPALLET">Pilih Pallet</label></button>
				</div>
				<div class="row">
					<input type="hidden" id="txt_jml_pallet" value="0">
					<table id="table_mutasi_draft" width="100%" class="table table-striped">
						<thead>
							<tr style="background:#F0EBE3;">
								<th class="text-center" name="CAPTION-NOPALLET">No Pallet</th>
								<th class="text-center" name="CAPTION-JENISPALLET">Jenis Pallet</th>
								<th class="text-center" name="CAPTION-LOKASIRAKASAL">Lokasi Rak Asal</th>
								<th class="text-center" name="CAPTION-LOKASIBINASAL">Lokasi Bin Asal</th>
								<th class="text-center" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="row">
					<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
					<button class="btn btn-success" id="btn_save_mutasi_draft"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
					<a href="<?= base_url(); ?>WMS/MutasiPalletDraft/MutasiPalletDraftMenu" class="btn btn-danger" id="btn_kembali"><i class="fa fa-reply"></i> <label name="CAPTION-MENUUTAMA">Menu
							Utama</label></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-pallet" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-PILIHPALLET">Pilih Pallet</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
								<label name="CAPTION-GUDANGASAL">Gudang Asal</label>
							</div>
							<div class="col-lg-4 col-sm-4 col-xs-8">
								<input type="text" class="form-control" id="gudang_asal_pallet" value="" readonly>
							</div>
						</div>
					</div>
				</div><br>
				<!-- <div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
								<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
							</div>
							<div class="col-lg-4 col-sm-4 col-xs-8">
								<input type="text" class="form-control" id="perusahaan_pallet" value="" readonly>
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
								<input type="text" class="form-control" id="principle_pallet" value="" readonly>
							</div>
						</div>
					</div>
				</div><br> -->
				<div class="row">
					<div class="col-xs-12">
						<table id="data-table-pallet" width="100%" class="table table-bordered">
							<thead>
								<tr style="background:#F0EBE3;">
									<th class="text-center"><input type="checkbox" name="select-pallet" id="select-pallet" value="1"></th>
									<th class="text-center" name="CAPTION-LOKASIRAK">Lokasi Rak</th>
									<th class="text-center" name="CAPTION-LOKASIBIN">Lokasi Bin</th>
									<th class="text-center" name="CAPTION-NOPALLET">No Pallet</th>
									<th class="text-center" name="CAPTION-JENISPALLET">Jenis Pallet</th>
									<th class="text-center" name="CAPTION-ACTION">Action</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-info" id="btn-choose-pallet-multi"><label name="CAPTION-PILIH">Pilih</label></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbackpallet"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-pallet-detail" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DETAILPALLET">Detail Pallet</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="container mt-2">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-PALLETKODE">Pallet Kode</label>
											</div>
											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_pallet_kode" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-JENISPALLET">Jenis Pallet</label>
											</div>
											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_jenis_pallet" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="x_content table-responsive">
										<table id="data-table-pallet-detail" width="100%" class="table table-striped">
											<thead>
												<tr>
													<td class="text-center" width="5%">#</td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
													<td width="30%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-EXPDATE">Exp Date</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
													<td width="5%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah
																Barang</label></strong></td>
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
				<button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-back-detail-pallet"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-pallet-detail-2" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DETAILPALLET">Detail Pallet</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="container mt-2">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-PALLETKODE">Pallet Kode</label>
											</div>

											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_pallet_kode2" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="form-group">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
												<label name="CAPTION-JENISPALLET">Jenis Pallet</label>
											</div>
											<div class="col-lg-4 col-sm-4 col-xs-8">
												<input type="text" class="form-control" id="pallet_detail_jenis_pallet2" value="" readonly>
											</div>
										</div>
									</div>
								</div><br>
								<div class="row">
									<div class="x_content table-responsive">
										<table id="data-table-pallet-detail" width="100%" class="table table-striped">
											<thead>
												<tr>
													<td class="text-center" width="5%">#</td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
													<td width="30%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-EXPDATE">Exp Date</label></strong></td>
													<td width="10%" class="text-center"><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
													<td width="5%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah
																Barang</label></strong></td>
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
				<button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-back-detail-pallet-2"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>