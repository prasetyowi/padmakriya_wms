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
									<label name="CAPTION-NOKOREKSIDRAFT">No Dokumen</label>
									<select class="form-control select2" name="noMutasiAntarUnit" id="noMutasiAntarUnit" required>
										<option value="">--<label name="CAPTION-PILIHNOKOREKSIDRAFT">Pilih No. Dokumen</label>--</option>
										<?php foreach ($no_koreksi as $no) : ?>
											<option value="<?= $no->id ?>"><?= $no->kode ?></option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" class="form-control" name="error" id="error" disabled>
									<input type="hidden" class="form-control" name="koreksi_draft_id" id="koreksi_draft_id" disabled>
									<input type="hidden" class="form-control" name="gudang_id" id="gudang_id" disabled>
									<input type="hidden" class="form-control" name="gudang_nama" id="gudang_nama" disabled>
									<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" disabled>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Asal</label>
									<input type="text" class="form-control" name="depo_asal" id="depo_asal" placeholder="Depo Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Gudang Asal</label>
									<input type="text" class="form-control" name="gudang_asal" id="gudang_asal" placeholder="Gudang Asal" required disabled />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Depo Tujuan</label>
									<input type="text" class="form-control" name="depo_tujuan" id="depo_tujuan" placeholder="Depo Tujuan" required disabled />
								</div>
							</div>
						</div>

						<div class="form-group">
							<label>Ekspedisi</label>
							<input type="text" class="form-control" name="ekspedisi" id="ekspedisi" placeholder="Ekspedisi" required disabled />
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">

						<div class="form-group">
							<label>Pengemudi</label>
							<input type="text" class="form-control" name="pengemudi" id="pengemudi" placeholder="Pengemudi" required disabled />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Kendaraan - Nopol</label>
									<input type="text" class="form-control" name="kendaraan" id="kendaraan" placeholder="kendaraan" required disabled />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" class="form-control" name="status" id="status" placeholder="Status" disabled required />
								</div>
							</div>
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label>Keterangan Persiapan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keteranganPersiapan" id="keteranganPersiapan" disabled></textarea>
						</div>
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 68px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>