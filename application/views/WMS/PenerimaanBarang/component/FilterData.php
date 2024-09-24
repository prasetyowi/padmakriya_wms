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
									<label name="CAPTION-NODOKUMENPOREC">No. Dokumen PO Rec</label>
									<input type="hidden" class="form-control" id="error_save_data" />
									<input type="hidden" class="form-control" id="global_pallet_id" />
									<input type="hidden" class="form-control" id="global_id" />
									<input type="hidden" class="form-control" id="security_log_book_id" value="<?= $rowData != null ? 'notnull' : 'null' ?>" />
									<input type="hidden" class="form-control" id="session_depo" value="<?= $this->session->userdata('depo_id') ?>" />
									<input type="hidden" class="form-control" id="levelKaryawanLogin" value="<?= $dataKaryawanLogin->karyawan_level_id ?>" />
									<input type="hidden" class="form-control" id="KaryawanId" value="<?= $dataKaryawanLogin->karyawan_id ?>" />
									<input type="hidden" class="form-control" id="lastUpdated" value="<?= $header->penerimaan_pembelian_tgl_update ?>" />
									<input type="text" class="form-control" name="doc_po" id="doc_po" placeholder="Auto" value="<?= $header->kode ?>" required readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" value="<?= $header->tgl ?>" readonly>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-7">
								<div class="form-group">
									<label name="CAPTION-JASAPENGANGKUT">Jasa Pengangkut</label>
									<select class="form-control select2" name="expedisi" id="expedisi" required onchange="handleUpdateService(this.value)">
										<option value="">-- <label name="CAPTION-PILIHJASAPENGANGKUT">Pilih Jasa Pengangkut</label> --</option>
										<?php foreach ($ekspedisis as $ekspedisi) : ?>
											<?php if ($header->e_id != null) { ?>
												<?php if ($header->e_id == $ekspedisi->ekspedisi_id) { ?>
													<option value="<?php echo $ekspedisi->ekspedisi_id ?>" selected><?= $ekspedisi->ekspedisi_kode ?> - <?= $ekspedisi->ekspedisi_nama ?></option>
												<?php } else { ?>
													<option value="<?php echo $ekspedisi->ekspedisi_id ?>"><?= $ekspedisi->ekspedisi_kode ?> - <?= $ekspedisi->ekspedisi_nama ?></option>
												<?php } ?>
											<?php } else { ?>
												<option <?= $rowData != null && $rowData->ekspedisi_id == $ekspedisi->ekspedisi_id ? 'selected' : '' ?> value="<?php echo $ekspedisi->ekspedisi_id ?>"><?= $ekspedisi->ekspedisi_kode ?> - <?= $ekspedisi->ekspedisi_nama ?></option>
											<?php } ?>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
									<?php if ($rowData != null) { ?>
										<input type="text" class="form-control" name="no_kendaraan" id="no_kendaraan" placeholder="No kendaraan" value="<?= $header->nopol != null ? $header->nopol : $rowData->security_logbook_nopol ?>" required onchange="handleUpdateVehicle(this.value)" />
									<?php } else { ?>
										<input type="text" class="form-control" name="no_kendaraan" id="no_kendaraan" placeholder="No kendaraan" value="<?= $header->nopol != null ? $header->nopol : "" ?>" required onchange="handleUpdateVehicle(this.value)" />
									<?php } ?>
								</div>
							</div>
						</div>

					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">

						<div class="form-group">
							<label name="CAPTION-NAMAPENGEMUDI">Nama Pengemudi</label>
							<?php if ($rowData != null) { ?>
								<input type="text" class="form-control" name="nama_pengemudi" id="nama_pengemudi" placeholder="Nama Pengemudi" value="<?= $header->pengemudi != null ? $header->pengemudi : $rowData->security_logbook_nama_driver ?>" required onchange="handleUpdateDriver(this.value)" />
							<?php } else { ?>
								<input type="text" class="form-control" name="nama_pengemudi" id="nama_pengemudi" placeholder="Nama Pengemudi" value="<?= $header->pengemudi != null ? $header->pengemudi : "" ?>" required onchange="handleUpdateDriver(this.value)" />
							<?php } ?>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-LOKASIPENERIMA">Lokasi Penerima</label>
									<select class="form-control select2" name="gudang_penerima" id="gudang_penerima" required disabled>
										<option value="<?php echo $gudangs->depo_detail_id ?>" selected><?= $gudangs->depo_detail_nama ?></option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Checker</label>
									<select class="form-control select2" name="checker" id="checker" <?= $dataKaryawanLogin->karyawan_level_id == '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41' ? 'disabled' : '' ?> required multiple onchange="handleUpdateChecker(event)">
										<option value="">--<label name="CAPTION-PILIHCHECKER">Pilih Checker</label>--</option>
										<?php foreach ($checker as $check) : ?>
											<?php if (!empty($dataChecker)) { ?>
												<?php if (in_array($check->id, $dataChecker)) { ?>
													<option value="<?= $check->id ?>" selected><?= $check->nama ?> | <?= $check->divisi ?> | <?= $check->level ?></option>
												<?php } else { ?>
													<option value="<?= $check->id ?>"><?= $check->nama ?> | <?= $check->divisi ?> | <?= $check->level ?></option>
												<?php } ?>
											<?php } else { ?>
												<option value="<?= $check->id ?>"><?= $check->nama ?> | <?= $check->divisi ?> | <?= $check->level ?></option>
											<?php } ?>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" onchange="handleUpdateKeterangan(this.value)"><?= $header->keterangan != "" ? $header->keterangan : "" ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>