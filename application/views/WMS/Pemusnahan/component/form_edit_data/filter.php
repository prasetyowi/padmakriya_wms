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
									<label name="CAPTION-NOBERITAACARA">No. Berita Acara</label>
									<input type="text" class="form-control" name="no_koreksi_edit" id="no_koreksi_edit" placeholder="Auto" required readonly>
									<input type="hidden" class="form-control" name="error_edit" id="error_edit" readonly>
									<input type="hidden" class="form-control" name="global_id_edit" id="global_id_edit" value="<?= $id ?>" readonly>
									<input type="hidden" class="form-control" name="koreksi_draft_id_edit" id="koreksi_draft_id_edit" readonly>
									<input type="hidden" class="form-control" name="gudang_id_edit" id="gudang_id_edit" readonly>
									<input type="hidden" class="form-control" name="gudang_nama_edit" id="gudang_nama_edit" readonly>
									<input type="hidden" class="form-control" name="principle_id_edit" id="principle_id_edit" readonly>
									<input type="hidden" class="form-control" name="tipe_id_edit" id="tipe_id_edit" readonly>
									<input type="hidden" class="form-control" name="checker_id_edit" id="checker_id_edit" readonly>
									<input type="hidden" name="lastUpdated" id="lastUpdated">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl_edit" id="tgl_edit" placeholder="dd-mm-yyyy" readonly>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-NODRAFTBERITAACARA">No. Draft Berita Acara</label>
							<input type="text" class="form-control" name="no_koreksi_draft_edit" id="no_koreksi_draft_edit" placeholder="Gudang Asal" required readonly />
						</div>

						<div class="form-group">
							<label name="CAPTION-GUDANGASAL">Gudang Asal</label>
							<input type="text" class="form-control" name="gudang_asal_edit" id="gudang_asal_edit" placeholder="Gudang Asal" required readonly />
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-PRINCIPLE">Principle</label>
							<input type="text" class="form-control" name="principle_edit" id="principle_edit" placeholder="Principle" required readonly />
						</div>
						<div class="form-group">
							<label name="CAPTION-CHECKER">Checker</label>
							<input type="text" class="form-control" name="checker_edit" id="checker_edit" placeholder="Checker" required readonly>
						</div>
						<div class="form-group">
							<label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
							<input type="text" class="form-control" name="tipe_transaksi_edit" id="tipe_transaksi_edit" placeholder="Tipe Transaksi" readonly required />
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_edit" id="keterangan_edit" placeholder="Keterangan"></textarea>
						</div>

						<div class="form-group">
							<label name="CAPTION-STATUS">Status</label>
							<input type="text" class="form-control" name="status_edit" id="status_edit" placeholder="Status" readonly required />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>