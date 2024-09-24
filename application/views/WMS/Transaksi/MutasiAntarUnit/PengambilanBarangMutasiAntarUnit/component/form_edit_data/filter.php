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
									<label>No. Dokumen</label>
									<input type="text" class="form-control" name="no_koreksi_draft_edit" id="no_koreksi_draft_edit" placeholder="Gudang Asal" required disabled />
									<input type="hidden" class="form-control" name="error_edit" id="error_edit" disabled>
									<input type="hidden" class="form-control" name="global_id_edit" id="global_id_edit" value="<?= $id ?>" disabled>
									<input type="hidden" class="form-control" name="koreksi_draft_id_edit" id="koreksi_draft_id_edit" disabled>
									<input type="hidden" class="form-control" name="gudang_id_edit" id="gudang_id_edit" disabled>
									<input type="hidden" class="form-control" name="gudang_nama_edit" id="gudang_nama_edit" disabled>
									<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" disabled>
									<input type="hidden" class="form-control" name="lastUpdatedDraft" id="lastUpdatedDraft" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl_edit" id="tgl_edit" placeholder="dd-mm-yyyy" disabled>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Asal</label>
									<input type="text" class="form-control" name="depo_asal_edit" id="depo_asal_edit" placeholder="Depo Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Gudang Asal</label>
									<input type="text" class="form-control" name="gudang_asal_edit" id="gudang_asal_edit" placeholder="Gudang Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Tujuan</label>
									<input type="text" class="form-control" name="depo_tujuan_edit" id="depo_tujuan_edit" placeholder="Depo Tujuan" required disabled />
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Ekspedisi</label>
							<input type="text" class="form-control" name="ekspedisi_edit" id="ekspedisi_edit" placeholder="Ekspedisi" required disabled />
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label>Pengemudi</label>
							<input type="text" class="form-control" name="pengemudi_edit" id="pengemudi_edit" placeholder="Pengemudi" required disabled />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Kendaraan - Nopol</label>
									<input type="text" class="form-control" name="kendaraan_edit" id="kendaraan_edit" placeholder="kendaraan" required disabled />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" class="form-control" name="status_edit" id="status_edit" placeholder="Status" disabled required />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan Persiapan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keteranganPersiapan_edit" id="keteranganPersiapan_edit" disabled></textarea>
						</div>
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keterangan_edit" id="keterangan_edit" placeholder="Keterangan"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>