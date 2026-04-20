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
									<input type="text" class="form-control" name="no_koreksi_draft_view" id="no_koreksi_draft_view" placeholder="Gudang Asal" required disabled />
									<input type="hidden" class="form-control" name="error_view" id="error_view" disabled>
									<input type="hidden" class="form-control" name="global_id_view" id="global_id_view" value="<?= $id ?>" disabled>
									<input type="hidden" class="form-control" name="koreksi_draft_id_view" id="koreksi_draft_id_view" disabled>
									<input type="hidden" class="form-control" name="koreksi_stok_id_view" id="koreksi_stok_id_view" disabled>
									<input type="hidden" class="form-control" name="gudang_id_view" id="gudang_id_view" disabled>
									<input type="hidden" class="form-control" name="gudang_nama_view" id="gudang_nama_view" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl_view" id="tgl_view" placeholder="dd-mm-yyyy" disabled>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Asal</label>
									<input type="text" class="form-control" name="depo_asal_view" id="depo_asal_view" placeholder="Depo Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Gudang Asal</label>
									<input type="text" class="form-control" name="gudang_asal_view" id="gudang_asal_view" placeholder="Gudang Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Tujuan</label>
									<input type="text" class="form-control" name="depo_tujuan_view" id="depo_tujuan_view" placeholder="Depo Tujuan" required disabled />
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Ekspedisi</label>
							<input type="text" class="form-control" name="ekspedisi_view" id="ekspedisi_view" placeholder="Ekspedisi" required disabled />
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label>Pengemudi</label>
							<input type="text" class="form-control" name="pengemudi_view" id="pengemudi_view" placeholder="Pengemudi" required disabled />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Kendaraan - Nopol</label>
									<input type="text" class="form-control" name="kendaraan_view" id="kendaraan_view" placeholder="kendaraan" required disabled />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" class="form-control" name="status_view" id="status_view" placeholder="Status" disabled required />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label>Keterangan Persiapan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keteranganPersiapan_view" id="keteranganPersiapan_view" disabled></textarea>
						</div>
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keterangan_view" id="keterangan_view" disabled placeholder="Keterangan"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>