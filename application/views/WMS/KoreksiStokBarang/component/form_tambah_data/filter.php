<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<!-- <h3><strong>Filter Data</strong></h3> -->
				<div class="clearfix"></div>
			</div>
			<div class="container mt-2">
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NOKOREKSISTOCK">No. Koreksi Stok</label>
									<input type="text" class="form-control" name="no_koreksi" id="no_koreksi" placeholder="Auto" required readonly>
									<input type="hidden" class="form-control" name="error" id="error" readonly>
									<input type="hidden" class="form-control" name="koreksi_draft_id" id="koreksi_draft_id" readonly>
									<input type="hidden" class="form-control" name="gudang_id" id="gudang_id" readonly>
									<input type="hidden" class="form-control" name="gudang_nama" id="gudang_nama" readonly>
									<input type="hidden" class="form-control" name="principle_id" id="principle_id" readonly>
									<input type="hidden" class="form-control" name="tipe_id" id="tipe_id" readonly>
									<input type="hidden" class="form-control" name="checker_id" id="checker_id" readonly>
									<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" value="<?= Date('Y-m-d') ?>" readonly>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-NOKOREKSIDRAFT">No. Koreksi Draft</label>
							<select class="form-control select2" name="no_koreksi_draft" id="no_koreksi_draft" required>
								<option value="">--<label name="CAPTION-PILIHNOKOREKSIDRAFT">Pilih No. Koreksi Draft</label>--</option>
								<?php foreach ($no_koreksi as $no) : ?>
									<option value="<?= $no->id ?>"><?= $no->kode ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="form-group">
							<label name="CAPTION-GUDANGASAL">Gudang Asal</label>
							<input type="text" class="form-control" name="gudang_asal" id="gudang_asal" placeholder="Gudang Asal" required readonly />
						</div>

						<div class="form-group">
							<label name="CAPTION-TIPEDOKUMEN">Tipe Dokumen</label>
							<input type="text" class="form-control" name="tipe_dokumen" id="tipe_dokumen" required readonly />
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-PRINCIPLE">Principle</label>
							<input type="text" class="form-control" name="principle" id="principle" placeholder="Principle" required readonly />
						</div>
						<div class="form-group">
							<label name="CAPTION-CHECKER">Checker</label>
							<input type="text" class="form-control" name="checker" id="checker" placeholder="Checker" required readonly>
						</div>
						<div class="form-group">
							<label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
							<input type="text" class="form-control" name="tipe_transaksi" id="tipe_transaksi" placeholder="Tipe Transaksi" readonly required />
						</div>
						<div class="form-group">
							<label name="CAPTION-NOREFERENSIDOKUMEN">No Referensi Dokumen</label>
							<input type="text" class="form-control" name="no_referensi_dokumen" id="no_referensi_dokumen" required readonly />
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
						</div>

						<div class="form-group">
							<label name="CAPTION-STATUS">Status</label>
							<input type="text" class="form-control" name="status" id="status" placeholder="Status" readonly required />
						</div>

						<div id="showHideFile">
							<div class="form-group">
								<label name="CAPTION-ATTACHMENT">File Attachment</label>
								<input type="text" class="form-control" name="file" id="file" required readonly />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>