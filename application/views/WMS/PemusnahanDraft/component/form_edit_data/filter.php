<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h3><strong><label name="CAPTION-FORMPEMUSNAHANATAURETUR">Form Pemusnahan / Retur</label></strong></h3>
				<div class="clearfix"></div>
			</div>
			<div class="container mt-2">
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-NODRAFTBERITAACARA">No Draft Berita Acara</label>
									<input type="text" name="" id="tempNopol" style="display:none">
									<input type="hidden" name="count_error_edit" id="count_error_edit">
									<input type="hidden" name="global_id_edit" id="global_id_edit" value="<?= $id ?>">
									<input type="hidden" name="depo_edit" id="depo_edit" data-id="<?= $depo->depo_id ?>" value="<?= $depo->depo_nama ?>">
									<input type="text" class="form-control" name="no_pemusnahan_draft_edit" id="no_pemusnahan_draft_edit" value="Auto" readonly required>
									<input type="hidden" name="lastUpdated" id="lastUpdated">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-TANGGAL">Tanggal</label>
									<input type="date" class="form-control" name="pemusnahan_draft_tgl_edit" id="pemusnahan_draft_tgl_edit" value="<?= date('Y-m-d') ?>" readonly required>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label name="CAPTION-GUDANG">Gudang</label>
							<select id="gudang_pemusnahan_draft_edit" name="gudang_pemusnahan_draft_edit" class="form-control select2">
								<option value="">--Pilih Gudang--</option>
								<?php foreach ($Gudang as $row) { ?>
									<option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label name="CAPTION-PRINCIPLE">Principle</label>
							<select id="pemusnahan_draft_principle_edit" name="pemusnahan_draft_principle_edit" class="form-control select2">
								<option value="">--Pilih Principle--</option>
								<?php foreach ($Principle as $row) { ?>
									<option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label name="CAPTION-CHECKER">Checker</label>
							<select id="pemusnahan_draft_checker_edit" name="pemusnahan_draft_checker_edit" class="form-control select2">
								<option value="">--Pilih Checker--</option>
								<?php foreach ($Checker as $row) { ?>
									<option value="<?= $row['karyawan_id']; ?>"><?= $row['karyawan_nama']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="form-group">
							<label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
							<select id="pemusnahan_draft_tipe_transaksi_edit" name="pemusnahan_draft_tipe_transaksi_edit" class="form-control select2">
								<option value="">--Pilih Tipe Transaksi--</option>
								<?php foreach ($TipeTransaksi as $row) { ?>
									<option value="<?= $row['tipe_mutasi_id']; ?>"><?= $row['tipe_transaksi']; ?></option>
								<?php } ?>
							</select>
						</div>

						<div id="divhideshow" style="display:none">
							<div class="form-group" id="divpemusnahan_draft_ekspedisi">
								<label name="CAPTION-EKSPEDISI">Ekspedisi</label>
								<select id="pemusnahan_draft_ekspedisi_edit" name="pemusnahan_draft_ekspedisi_edit" class="form-control select2">
									<option value="">--Pilih Ekspedisi--</option>
									<?php foreach ($Ekspedisi as $row) { ?>
										<option value="<?= $row['ekspedisi_id']; ?>"><?= $row['ekspedisi_nama']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group" id="divpemusnahan_draft_driver">
								<label name="CAPTION-DRIVER">Pengemudi</label>
								<select id="pemusnahan_draft_driver_edit" name="pemusnahan_draft_driver_edit" class="form-control select2">
									<option value="">--Pilih Driver--</option>
									<?php foreach ($Driver as $row) { ?>
										<option value="<?= $row['karyawan_nama']; ?>"><?= $row['karyawan_nama']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group" id="divpemusnahan_draft_kendaraan">
								<label name="CAPTION-KENDARAAN">Kendaraan</label>
								<select id="pemusnahan_draft_kendaraan_edit" name="pemusnahan_draft_kendaraan_edit" class="form-control select2">
									<option value="">--Pilih Kendaraan--</option>
									<?php foreach ($Kendaraan as $row) { ?>
										<option value="<?= $row['kendaraan_model']; ?>"><?= $row['kendaraan_model']; ?></option>
									<?php } ?>
								</select>
							</div>

							<div class="form-group" id="divpemusnahan_draft_nopol">
								<label name="CAPTION-NOPOLISI">No Polisi</label>
								<select id="pemusnahan_draft_nopol_edit" name="pemusnahan_draft_nopol_edit" class="form-control select2">
								</select>
							</div>

							<div class="form-group" id="divpemusnahan_draft_driver2" style="display: none;">
								<label name="CAPTION-DRIVER">Pengemudi</label>
								<input type="text" class="form-control" name="pemusnahan_draft_driver2_edit" id="pemusnahan_draft_driver2_edit">
							</div>
							<div class="form-group" id="divdivpemusnahan_draft_kendaraan2" style="display: none;">
								<label name="CAPTION-KENDARAAN">Kendaraan</label>
								<input type="text" class="form-control" name="divpemusnahan_draft_kendaraan2_edit" id="divpemusnahan_draft_kendaraan2_edit">
							</div>
							<div class="form-group" id="divpemusnahan_draft_nopol2" style="display: none;">
								<label name="CAPTION-NOPOLISI">No Polisi</label>
								<input type="text" class="form-control" name="pemusnahan_draft_nopol2_edit" id="pemusnahan_draft_nopol2_edit">
							</div>
						</div>

					</div>

					<div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<label name="CAPTION-STATUS">Status</label>
									<input type="text" class="form-control" name="pemusnahan_draft_status_edit" value="Draft" id="pemusnahan_draft_status_edit" readonly required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label name="CAPTION-KETERANGAN">Keterangan</label>
							<textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="pemusnahan_draft_keterangan_edit" id="pemusnahan_draft_keterangan_edit" placeholder="Keterangan"></textarea>
						</div>
						<div class="form-group">
							<input type="checkbox" style="margin-top: 30px;transform: scale(1.0)" name="pemusnahan_draft_approval_edit" id="pemusnahan_draft_approval_edit" value="In Progress Approval">
							<span style="margin-left: 10px;font-size:15px;font-weight:700">Pengajuan Approval</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>