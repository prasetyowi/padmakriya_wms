<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h3><strong><label name="CAPTION-DRAFTKOREKSISTOK">Draft Koreksi Stok</label></strong></h3>
				<div class="clearfix"></div>
			</div>
			<div class="container mt-2">
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NODRAFTKOREKSISTOK">No Draft Koreksi Stok</label>
									<input type="hidden" name="count_error_edit" id="count_error_edit">
									<input type="hidden" name="global_id_edit" id="global_id_edit" value="<?= $id ?>">
									<input type="hidden" name="depo_edit" id="depo_edit" data-id="<?= $depo->depo_id ?>" value="<?= $depo->depo_nama ?>">
									<input type="text" class="form-control" name="no_koreksi_draft_edit" id="no_koreksi_draft_edit" value="Auto" readonly required>
									<input type="hidden" name="last_updated" id="last_updated">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control" name="koreksi_draft_tgl_edit" id="koreksi_draft_tgl_edit" readonly required>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-GUDANG">Gudang</label>
							<select id="gudang_koreksi_draft_edit" name="gudang_koreksi_draft_edit" class="form-control select2">
								<option value="">--<label name="CAPTION-PILIHGUDANG">Pilih Gudang</label>--</option>
								<?php foreach ($warehouses as $warehouse) { ?>
									<option value="<?= $warehouse->id ?>"><?= $warehouse->nama ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label name="CAPTION-PRINCIPLE">Principle</label>
							<select id="koreksi_draft_principle_edit" name="koreksi_draft_principle_edit" class="form-control select2">
								<option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--
								</option>
								<?php foreach ($principles as $principle) { ?>
									<option value="<?= $principle->id ?>"><?= $principle->nama ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-CHECKER">Checker</label>
							<select id="koreksi_draft_checker_edit" name="koreksi_draft_checker_edit" class="form-control select2">
							</select>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
									<select id="koreksi_draft_tipe_transaksi_edit" name="koreksi_draft_tipe_transaksi_edit" class="form-control select2">
										<option value="">--<label name="CAPTION-PILIHTIPETRANSAKSI">Pilih Tipe
												Transaksi</label>--</option>
										<?php foreach ($type_transactions as $type_transaction) { ?>
											<option value="<?= $type_transaction->id ?>"><?= $type_transaction->nama ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" class="form-control" name="koreksi_draft_status_edit" id="koreksi_draft_status_edit" readonly required>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TIPEDOKUMEN">Tipe Dokumen</label>
									<select id="koreksi_draft_tipe_dokumen" name="koreksi_draft_tipe_dokumen" class="form-control select2" onchange="getReferensiDokumen(this.value)">
										<option value="">--<label name="CAPTION-PILIHTIPEDOKUMEN">Pilih Tipe
												Dokumen</label>--</option>
										<?php foreach ($type_document as $typeDocument) { ?>
											<option value="<?= $typeDocument->table_name ?>-<?= $typeDocument->table_name_kode ?>-<?= $typeDocument->tipe_dokumen_nama ?>"><?= $typeDocument->tipe_dokumen_nama ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NOREFERENSIDOKUMEN">No Referensi Dokumen</label>
									<input type="text" class="form-control" name="koreksi_draft_no_referensi_dokumen" id="koreksi_draft_no_referensi_dokumen" disabled required onkeyup="autoCompleteReferensiDokumen(this)">
									<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
										<table class="table table-striped table-sm table-hover" id="table-fixed">
											<tbody id="konten-table"></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!-- <div class="form-group">
							<input type="checkbox" style="margin-top: 30px;transform: scale(1.5)" name="koreksi_draft_approval_edit" id="koreksi_draft_approval_edit" value="In Progress Approval">
							<span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
						</div> -->
					</div>

					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="koreksi_draft_keterangan_edit" id="koreksi_draft_keterangan_edit" placeholder="Keterangan"></textarea>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
									<input type="file" class="form-control" name="file" id="file" placeholder="upload attachment" onchange="previewFile()" required accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
									<div class="row" id="show-file"></div>
									<div id="hide-file">

									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="checkbox" style="margin-top: 30px;transform: scale(1.5)" name="koreksi_draft_approval_edit" id="koreksi_draft_approval_edit" value="In Progress Approval">
									<span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>