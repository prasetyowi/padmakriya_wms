<section>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<div class="clearfix"></div>
				</div>
				<div class="container mt-2">
					<div class="row">
						<div class="col-xl-8 col-lg-8 col-md-8 col-xs-12">
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Kode Dokumen</label>
									<input type="text" class="form-control" name="kodeDokumen" id="kodeDokumen" placeholder="Auto generate" disabled>
									<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated">
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Expedisi</label>
									<select class="form-control select2" name="ekspedisi" id="ekspedisi" required>
										<option value="">--Pilih Ekspedisi--</option>
										<?php foreach ($ekspedisis as $ekspedisi) : ?>
											<option value="<?= $ekspedisi->ekspedisi_id ?>"><?= $ekspedisi->ekspedisi_kode ?> - <?= $ekspedisi->ekspedisi_nama ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Pengemudi</label>
									<select class="form-control select2" name="pengemudi" id="pengemudi" required>
										<option value="">--Pilih Pengemudi--</option>
										<?php foreach ($drivers as $driver) : ?>
											<option value="<?= $driver->karyawan_id ?>"><?= $driver->karyawan_nama ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Kendaraan - Nopol</label>
									<select class="form-control select2" name="kendaraan" id="kendaraan" required>
										<option value="">--Pilih Kendaraan--</option>
										<?php foreach ($vehicles as $vehicle) : ?>
											<option value="<?= $vehicle->kendaraan_id ?>"><?= $vehicle->kendaraan_model ?> - <?= $vehicle->kendaraan_nopol ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
								<div class="form-group">
									<label name="CAPTION-STATUSPENGAJUAN">Status</label>
									<div class="row">
										<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
											<input type="text" id="status" class="form-control" name="status" value="Draft" readonly />
										</div>
										<div class="col-xl-8 col-lg-8 col-md-8 col-xs-12">
											<input type="checkbox" name="chckStatus" id="chckStatus" style="margin-top:10px;transform: scale(1.5)" onchange="handlerViewCahngeStatus(event)">
											<span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
							<div class="form-group">
								<label>Keterangan</label>
								<textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>