<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-EDITBATCHSURATTUGASPENGIRIMAN">Edit Batch Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">

			</div>
		</div>
		<input type="hidden" name="do_batch_id" id="do_batch_id" value="<?= $id ?>">
		<input type="hidden" name="do_temp_id" id="do_temp_id" value="<?= $temp_id ?>">
		<input type="hidden" id="vrbl_location" class="form-control" name="vrbl_location" autocomplete="off" value="<?= $vrblLocation['vrbl_kode'] ?>">
		<?php foreach ($FDJR as $header) : ?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="clearfix"></div>
						</div>
						<input type="hidden" id="deliveryorderbatch-delivery_order_batch_id" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_id]" autocomplete="off" value="<?= $header['delivery_order_batch_id'] ?>">
						<input type="hidden" id="deliveryorderbatch-delivery_order_batch_status" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_status]" autocomplete="off" value="<?= $header['delivery_order_batch_status'] ?>">
						<input type="hidden" id="deliveryorderbatch-depo_id" class="form-control" name="DeliveryOrderBatch[depo_id]" autocomplete="off" value="<?= $header['depo_id'] ?>">
						<input type="hidden" id="deliveryorderbatch-unit_mandiri_id" class="form-control" name="DeliveryOrderBatch[unit_mandiri_id]" autocomplete="off" value="<?= $header['unit_mandiri_id'] ?>">
						<input type="hidden" id="area_id" class="form-control" name="area_id" autocomplete="off" data-area="<?= $FdjrArea ?>">
						<input type="hidden" id="tglLastUpdate" name="tglLastUpdate" value="<?= $header['delivery_order_batch_update_tgl'] == null ? '' : $header['delivery_order_batch_update_tgl'] ?>">
						<input type="hidden" id="ritasiID" class="form-control" name="ritasiID" autocomplete="off" value="<?= $header['delivery_order_batch_h_id'] ?>">
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal">
									<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal" name="CAPTION-TANGGALBATCH">Tanggal Batch</label>
									<input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_tanggal" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_tanggal]" autocomplete="off" value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal'])) ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-delivery_order_batch_kode">
									<label class="control-label" for="deliveryorderbatch-delivery_order_batch_kode" name="CAPTION-NOBATCH">No Batch</label>
									<input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_kode" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_kode]" autocomplete="off" value="<?= $header['delivery_order_batch_kode'] ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
									<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tipe_layanan_id" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
									<select name="DeliveryOrderBatch[delivery_order_batch_tipe_layanan_id]" class="form-control" id="deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($TipePelayanan as $row) : ?>
											<option value="<?= $row['tipe_layanan_id'] ?> || <?= $row['tipe_layanan_kode'] ?> || <?= $row['tipe_layanan_nama'] ?>" <?= $header['delivery_order_batch_tipe_layanan_id'] == $row['tipe_layanan_id'] ? 'selected' : '' ?>>
												<?= $row['tipe_layanan_nama'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-tipe_delivery_order_id">
									<label class="control-label" for="deliveryorderbatch-tipe_delivery_order_id" name="CAPTION-TIPE">Tipe</label>
									<select name="DeliveryOrderBatch[tipe_delivery_order_id]" class="form-control" id="deliveryorderbatch-tipe_delivery_order_id">
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($TipeDeliveryOrder as $row) : ?>
											<option value="<?= $row['tipe_delivery_order_id'] ?>" <?= $header['tipe_delivery_order_id'] == $row['tipe_delivery_order_id'] ? 'selected' : '' ?>>
												<?= $row['tipe_delivery_order_alias'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal_kirim">
									<label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal_kirim" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
									<input type="text" id="deliveryorderbatch-delivery_order_batch_tanggal_kirim" class="form-control datepicker" disabled name="DeliveryOrderBatch[delivery_order_batch_tanggal_kirim]" autocomplete="off" value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal_kirim'])) ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-area_id">
									<label class="control-label" for="deliveryorderbatch-area_id" name="CAPTION-AREA">Area</label>
									<select id="deliveryorderbatch-area_id" class="selectpicker form-control" name="DeliveryOrderBatch[area_id]" data-live-search="true" style="color:#000000" multiple required data-actions-box="true" title="Select System">
										<?php foreach ($Area as $row) : ?>
											<?php if (in_array($row['area_id'], explode(',', $FdjrArea))) { ?>
												<option value="<?= $row['area_id'] ?>" selected><?= $row['area_nama'] ?></option>
											<?php } ?>
										<?php endforeach; ?>
									</select>
									<!-- <select name="DeliveryOrderBatch[area_id]" class="form-control select2" id="deliveryorderbatch-area_id" multiple>
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($Area as $row) : ?>
											<?php if (in_array($row['area_id'], explode(',', $FdjrArea))) { ?>
												<option value="<?= $row['area_id'] ?>" selected><?= $row['area_nama'] ?></option>
											<?php } ?>
										<?php endforeach; ?>
									</select> -->
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderbatch-tipe_ekspedisi_id">
									<label class="control-label" for="deliveryorderbatch-tipe_ekspedisi_id" name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi</label>
									<select name="DeliveryOrderBatch[tipe_ekspedisi_id]" class="form-control" id="deliveryorderbatch-tipe_ekspedisi_id">
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($TipeEkspedisi as $row) : ?>
											<option value="<?= $row['tipe_ekspedisi_id'] ?>" <?= $header['tipe_ekspedisi_id'] == $row['tipe_ekspedisi_id'] ? 'selected' : '' ?>>
												<?= $row['tipe_ekspedisi_nama'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<br>
									<button class="btn btn-primary" onclick="simulasiArea()"><i class="fa-solid fa-chart-area"></i> <label name="CAPTION-SIMULASIAREA">Simulasi Area</label></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="row" id="panel-armada">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php foreach ($FDJR as $header) : ?>
					<div class="x_panel">
						<div class="x_title">
							<h4 class="pull-left" name="CAPTION-PILIHARMADA">Pilih Armada</h4>
							<div class="clearfix"></div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group field-deliveryorderbatch-kendaraan_id">
									<label class="control-label" for="deliveryorderbatch-kendaraan_id" name="CAPTION-KENDARAAN">Kendaraan</label>
									<select name="DeliveryOrderBatch[kendaraan_id]" disabled class="form-control select2" id="deliveryorderbatch-kendaraan_id" onchange="GetKendaraan(this.value)">
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($Kendaraan as $row) : ?>
											<option value="<?= $row['kendaraan_id'] ?>" <?= $header['kendaraanID'] == $row['kendaraan_id'] ? 'selected' : '' ?>>
												<?= $row['kendaraan_nopol'] ?> - <?= $row['kendaraan_model'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group field-deliveryorderbatch-karyawan_id">
									<label class="control-label" for="deliveryorderbatch-karyawan_id" name="CAPTION-DRIVER">Driver</label>
									<select name="DeliveryOrderBatch[karyawan_id]" disabled class="form-control select2" id="deliveryorderbatch-karyawan_id">
										<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
										<?php foreach ($Driver as $row) : ?>
											<option value="<?= $row['karyawan_id'] ?>" <?= $header['karyawanID'] == $row['karyawan_id'] ? 'selected' : '' ?>>
												<?= $row['karyawan_nama'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<table class="table table-bordered table-striped" id="table-armada-summary">
							<thead>
								<tr>
									<th name="CAPTION-PROFILMUATAN">Profil Muatan</th>
									<th class="weight-label" name="CAPTION-BERATGRAM">Berat (gram)</th>
									<th class="volume-label"><label name="CAPTION-VOLUME">Volume</label> (cm<sup>3</sup>)
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="max-capacity-label" name="CAPTION-KAPASITASMAKSIMAL">Kapasitas Maksimal</td>
									<td>
										<input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_max]" id="deliveryorderbatch-kendaraan_berat_gr_max" value="<?= $header['beratMax'] ?>">
									</td>
									<td>
										<input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_max]" id="deliveryorderbatch-kendaraan_volume_cm3_max" value="<?= $header['volumeMax'] ?>">
										<input type="hidden" name="DeliveryOrderBatch[kendaraan_km_awal]" id="deliveryorderbatch-kendaraan_km_awal" value="<?= $header['kendaraan_km_awal'] ?>">
										<input type="hidden" name="DeliveryOrderBatch[kendaraan_km_akhir]" id="deliveryorderbatch-kendaraan_km_akhir" value="<?= $header['kendaraan_km_akhir'] ?>">
									</td>
								</tr>
								<tr>
									<td name="CAPTION-KAPASITASTERPAKAI">Kapasitas Terpakai</td>
									<td>
										<?php if ($header['beratTerpakai'] > $header['beratMax']) { ?>
											<div class="input-group simulasiPenguranganBerat">
												<input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>">
												<span class="input-group-addon" id="pengurangan_terpakai_berat" style="font-weight: bold;"></span>
											</div>
											<!-- <input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>"> -->
										<?php } else { ?>
											<div class="input-group simulasiPenguranganBerat">
												<input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>">
												<span class="input-group-addon" id="pengurangan_terpakai_berat" style="font-weight: bold;"></span>
											</div>
											<!-- <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>"> -->
										<?php } ?>
										<input type="hidden" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_sisa]" id="deliveryorderbatch-kendaraan_berat_gr_sisa" value="<?= $header['beratMax'] ?>">
									</td>
									<td>
										<?php if ($header['volumeTerpakai'] > $header['volumeMax']) { ?>
											<div class="input-group simulasiPenguranganVolume">
												<input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>">
												<span class="input-group-addon" id="pengurangan_terpakai_volume" style="font-weight: bold;"></span>
											</div>
											<!-- <input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>"> -->
										<?php } else { ?>
											<div class="input-group simulasiPenguranganVolume">
												<input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>">
												<span class="input-group-addon" id="pengurangan_terpakai_volume" style="font-weight: bold;"></span>
											</div>
											<!-- <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>"> -->
										<?php } ?>
										<input type="hidden" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_sisa]" id="deliveryorderbatch-kendaraan_volume_cm3_sisa" value="<?= $header['volumeMax'] ?>">
									</td>
								</tr>
								<?php
								$presentaseBeratTerpakai = intval($header['beratMax']) != 0 ? number_format(intval($header['beratTerpakai']) / intval($header['beratMax']) * 100, 2, '.', '') : 0;
								$presentaseVolumeTerpakai = intval($header['volumeMax']) != 0 ? number_format(intval($header['volumeTerpakai']) / intval($header['volumeMax']) * 100, 2, '.', '') : 0;

								?>
								<tr>
									<td name="CAPTION-PRESENTASEKAPASITASTERPAKAI">Presentase Kapasitas Terpakai</td>
									<td>
										<div class="input-group simulasiPenguranganPresentaseBerat">
											<input <?= intval($presentaseBeratTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai" value="<?= $presentaseBeratTerpakai ?>%">
											<span class="input-group-addon" id="pengurangan_presentase_berat" style="font-weight: bold;"></span>
										</div>
										<!-- <input <?= intval($presentaseBeratTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai" value="<?= $presentaseBeratTerpakai ?>%"> -->
									</td>
									<td>
										<div class="input-group simulasiPenguranganPresentaseVolume">
											<input <?= intval($presentaseVolumeTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai" value="<?= $presentaseVolumeTerpakai ?>%">
											<span class="input-group-addon" id="pengurangan_presentase_volume" style="font-weight: bold;"></span>
										</div>
										<!-- <input <?= intval($presentaseVolumeTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai" value="<?= $presentaseVolumeTerpakai ?>%"> -->
									</td>
								</tr>
								<tr>
									<td class="max-capacity-label" name="CAPTION-SELISIHKAPASITAS">Selisih Kapasitas</td>
									<td>
										<div class="input-group simulasiPenguranganSelisihBerat">
											<input type="text" readonly="readonly" class="form-control" name="selisihKapasitasBerat" id="selisihKapasitasBerat" value="<?= $header['beratMax'] - $header['beratTerpakai'] ?>">
											<span class="input-group-addon" id="pengurangan_selisih_berat" style="font-weight: bold;"></span>
										</div>
										<!-- <input type="text" readonly="readonly" class="form-control" name="selisihKapasitasBerat" id="selisihKapasitasBerat" value="<?= $header['beratMax'] - $header['beratTerpakai'] ?>"> -->
									</td>
									<td>
										<div class="input-group simulasiPenguranganSelisihVolume">
											<input type="text" readonly="readonly" class="form-control" name="selisihKapasitasVolume" id="selisihKapasitasVolume" value="<?= $header['volumeMax'] - $header['volumeTerpakai'] ?>">
											<span class="input-group-addon" id="pengurangan_selisih_volume" style="font-weight: bold;"></span>
										</div>
										<!-- <input type="text" readonly="readonly" class="form-control" name="selisihKapasitasVolume" id="selisihKapasitasVolume" value="<?= $header['volumeMax'] - $header['volumeTerpakai'] ?>"> -->
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="row" id="panel-dodraft">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas Pengiriman</h4>
						<div class="pull-right"><button data-toggle="modal" data-target="#modal-dodraft" id="btn-choose-dodraft" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
						<div class="pull-right">
							<?php if (round($vrbl['vrbl_flg_value']) == 1) { ?>
								<button class="btn btn-primary" type="button" id="btn-optimasi-rute"><i class="fa-solid fa-route"></i> <label name="CAPTION-OPTIMASIRUTE">Optimasi
										Rute</label></button>
							<?php } else { ?>
								<button class="btn btn-primary" disabled type="button" id="btn-optimasi-rute"><i class="fa-solid fa-route"></i> <label name="">Optimasi
										Rute</label></button>
							<?php } ?>
						</div>
						<div class="pull-right">
							<button class="btn btn-dark" type="button" id="btn-check-muatan" onclick="checkMuatan()"><i class="fa-solid fa-arrows-rotate"></i> <label name="CAPTION-CEKMUATAN">Cek
									Muatan</label></button>
						</div>
						<?php
						if ($UseMap[0]['vrbl_kode'] == 'TRUE') {
						?>
							<div class="pull-right">
								<button class="btn btn-primary" type="button" id="btn-check-muatan" onclick="LihatPeta()"><i class="fa-solid fa-globe"></i> <label name="CAPTION-LIHATPETA">Lihat Peta</label>
								</button>
							</div>
						<?php
						}
						?>
						<div class="pull-right">
							<button class="btn btn-danger" type="button" id="btn-hapus-outlet" onclick="hapusOutlet()"><i class="fa-solid fa-trash"></i> <label name="CAPTION-HAPUSOUTLET">Hapus Outlet</label> <span style="color:white"><strong id="jml_outlet">(0)</strong></span>
							</button>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<em><label name="CAPTION-DRAGANDDROPDOUNTUKMENGUBAHPRIORITAS">Drag and drop DO untuk
									mengubah prioritas</label></em>
						</div>
					</div>
					<table class="table table-bordered table-striped" id="table-do-draft">
						<thead>
							<tr>
								<th class="text-center" name="CAPTION-PRIORITAS">Prioritas</th>
								<th class="text-center resetPrioritas" name="CAPTION-NAMA">Nama</th>
								<th class="text-center resetPrioritas"><label name="CAPTION-ALAMAT">Alamat</label></th>
								<th class="text-center resetPrioritas"><label name="CAPTION-TELP">Telp</label></th>
								<th class="text-center resetPrioritas">Jumlah DO</th>
								<th class="text-center resetPrioritas">Kecamatan</th>
								<th class="text-center resetPrioritas">Area</th>
								<th class="text-center resetPrioritas"><label name="CAPTION-LATITUDE">Latitude</label></th>
								<th class="text-center resetPrioritas"><label name="CAPTION-LONGITUDE">Longitude</label></th>
								<th class="text-center resetPrioritas"><label name="CAPTION-BERATGRAM">Berat(gram)</label></th>
								<th class="text-center resetPrioritas"><label name="CAPTION-VOLUMECM">Volume(cm<sup>3</sup>)</label></th>
								<th class="text-center resetPrioritas">Composite</th>
								<th class="text-center" hidden></th>
								<th class="text-center" name="CAPTION-ACTION">Action</th>
								<th class="text-center">Pilih Semua &nbsp; <input type="checkbox" style="transform: scale(1.5)" name="allDelOutlet" id="allDelOutlet" onchange="allDelOutlet(this.checked)" value="1"></th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($DO)) {
								$no = 1;
								$no2 = 1;
								$totalBeratDO = 0;
								$totalVolumeDO = 0; ?>
								<?php foreach ($DO as $i => $detail) :
									$totalBeratDO = $totalBeratDO + intval($detail['sku_weight']);
									$totalVolumeDO = $totalVolumeDO + intval($detail['sku_volume']);
									if ($totalBeratDO > intval($rowFDJR['kendaraan_berat_gr_max']) || $totalVolumeDO > intval($rowFDJR['kendaraan_volume_cm3_max'])) {
								?>
										<tr id="row-<?= $i ?>" class="ui-sortable-handle" style="background-color: #FF9999; color:black">
											<td class="text-center"><span id="urutan-prioritas-<?= $i ?>"><?= $no++ ?></span></td>
											<input type="hidden" class="latitudelongitude" data-nama="<?= $detail['delivery_order_draft_kirim_nama'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-lat="<?= $detail['client_pt_latitude'] ?>" data-long="<?= $detail['client_pt_longitude'] ?>" data-prioritas="<?= $no2++ ?>" />
											<td class="text-center  "><?= $detail['delivery_order_draft_kirim_nama'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_alamat'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_telp'] ?></td>
											<td class="text-center"><?= $detail['jumlah_do'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_kecamatan'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_area'] ?></td>
											<td class="text-center dblclkRow" data-client-pt-id="<?= $detail['client_pt_id'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-mode="client_pt_latitude" data-mode-do="delivery_order_kirim_latitude">
												<?= $detail['client_pt_latitude'] ?>
											</td>
											<td class="text-center dblclkRow" data-client-pt-id="<?= $detail['client_pt_id'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-mode="client_pt_longitude" data-mode-do="delivery_order_kirim_longitude">
												<?= $detail['client_pt_longitude'] ?></td>
											<td class="text-center"><?= intval($detail['sku_weight']) ?></td>
											<td class="text-center"><?= intval($detail['sku_volume']) ?></td>
											<td class="text-center"><?= $detail['composite'] ?></td>
											<td style="display: none"><?= $detail['delivery_order_temp_id'] ?></td>
											<td class="text-center">
												<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('<?= $detail['delivery_order_temp_id'] ?>','<?= $detail['delivery_order_draft_kirim_nama'] ?>','<?= $detail['delivery_order_draft_kirim_alamat'] ?>')"><i class="fa fa-eye"></i></button>
												<!-- <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,<?= $i ?>,'<?= $detail['delivery_order_temp_id'] ?>', '<?= $detail['delivery_order_draft_kirim_alamat'] ?>', '<?= $detail['delivery_order_batch_id'] ?>')"><i class="fa fa-trash"></i></button> -->
												<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('<?= $detail['delivery_order_draft_kirim_alamat'] ?>')"><i class="fa-sharp fa-solid fa-location-dot"></i></button>
											</td>
											<td class="text-center"><input type="checkbox" data-berat="<?= intval($detail['sku_weight']) ?>" data-volume="<?= intval($detail['sku_volume']) ?>" style="transform: scale(1.5)" class="ChkboxDelOutlet" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="<?= $detail['delivery_order_temp_id'] ?>_<?= $detail['delivery_order_draft_kirim_alamat'] ?>_<?= $detail['delivery_order_draft_kirim_nama'] ?>"></td>
										</tr>
									<?php } else {
									?>
										<tr id="row-<?= $i ?>" class="ui-sortable-handle">
											<td class="text-center"><span id="urutan-prioritas-<?= $i ?>"><?= $no++ ?></span></td>
											<input type="hidden" class="latitudelongitude" data-nama="<?= $detail['delivery_order_draft_kirim_nama'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-lat="<?= $detail['client_pt_latitude'] ?>" data-long="<?= $detail['client_pt_longitude'] ?>" data-prioritas="<?= $no2++ ?>" />
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_nama'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_alamat'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_telp'] ?></td>
											<td class="text-center"><?= $detail['jumlah_do'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_kecamatan'] ?></td>
											<td class="text-center"><?= $detail['delivery_order_draft_kirim_area'] ?></td>
											<td class="text-center dblclkRow" data-client-pt-id="<?= $detail['client_pt_id'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-mode="client_pt_latitude" data-mode-do="delivery_order_kirim_latitude">
												<?= $detail['client_pt_latitude'] ?>
											</td>
											<td class="text-center dblclkRow" data-client-pt-id="<?= $detail['client_pt_id'] ?>" data-alamat="<?= $detail['delivery_order_draft_kirim_alamat'] ?>" data-mode="client_pt_longitude" data-mode-do="delivery_order_kirim_longitude">
												<?= $detail['client_pt_longitude'] ?></td>
											<td class="text-center"><?= intval($detail['sku_weight']) ?></td>
											<td class="text-center"><?= intval($detail['sku_volume']) ?></td>
											<td class="text-center"><?= $detail['composite'] ?></td>
											<td style="display: none"><?= $detail['delivery_order_temp_id'] ?></td>
											<td class="text-center">
												<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('<?= $detail['delivery_order_temp_id'] ?>','<?= $detail['delivery_order_draft_kirim_nama'] ?>','<?= $detail['delivery_order_draft_kirim_alamat'] ?>')"><i class="fa fa-eye"></i>
												</button>
												<!-- <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,<?= $i ?>,'<?= $detail['delivery_order_temp_id'] ?>', '<?= $detail['delivery_order_draft_kirim_alamat'] ?>', '<?= $detail['delivery_order_batch_id'] ?>')"><i class="fa fa-trash"></i></button> -->
												<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('<?= $detail['delivery_order_draft_kirim_alamat'] ?>')"><i class="fa-sharp fa-solid fa-location-dot"></i></button>
											</td>
											<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="<?= intval($detail['sku_weight']) ?>" data-volume="<?= intval($detail['sku_volume']) ?>" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="<?= $detail['delivery_order_temp_id'] ?>_<?= $detail['delivery_order_draft_kirim_alamat'] ?>_<?= $detail['delivery_order_draft_kirim_nama'] ?>"></td>
										</tr>
								<?php }
								endforeach; ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row" id="panel-maps" style="display: none;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left"><strong><label name="CAPTION-KODEAREAKIRIM">Kode Area Kirim</label></strong></h4>

						<div class="pull-right"><button id="close-panel-maps" onclick="handlerClosePanelMaps()" class="btn btn-dark" type="button"><label name="CAPTION-TUTUPMAPS">Tutup Maps</label></button></div>
						<div class="clearfix"></div>
					</div>

					<!-- <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5"></div> -->

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 20px;"></div>

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0;" id="init-maps-container">
						<!-- <div id="datamapdobatch" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 70vh;"></div> -->
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-sku">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-DAFTARSKUSUMMARY">Daftar SKU Summary</h4>
						<div class="clearfix"></div>
					</div>
					<!-- <div class="row">
						<div class="col-xs-1">
							Lokasi Gudang:
						</div>
						<div class="col-xs-4">
							<select name="DeliveryOrderBatch[depo_detail_id]" id="deliveryorderbatch-depo_detail_id" class="form-control">
								<option value="">**Pilih**</option>
								<?php foreach ($Gudang as $row) : ?>
									<option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-xs-7">
							<button type="button" class="btn btn-info btn-small btn-check-stock">Cek Ketersediaan Stok</button>
						</div>
					</div> -->
					<!-- <br /> -->
					<button type="button" class="btn btn-secondary" style="font-weight: bold;" id="totalComposite">
						<?= $totalComposite ?>
					</button>

					<table class="table table-bordered table-striped" id="table-summary-sku">
						<thead>
							<tr>
								<th name="CAPTION-PRINCIPLE">Principle</th>
								<th name="CAPTION-KODESKU">Kode SKU</th>
								<!-- <th>Kode SKU Pabrik</th> -->
								<th name="CAPTION-NAMASKU">Nama SKU</th>
								<th name="CAPTION-KEMASAN">Kemasan</th>
								<th name="CAPTION-SATUAN">Satuan</th>
								<th name="CAPTION-QTYREQ">Qty Req</th>
								<th name="CAPTION-TIPESTOCK">Tipe Stock</th>
								<th name="CAPTION-REQEXPDATE">Req Exp Date?</th>
								<th name="CAPTION-REQEXPFILTER">Req Exp Filter</th>
								<th name="CAPTION-REQEXPFILTERBULAN">Req Exp Filter Bulan</th>
								<!-- <th>Qty Available</th> -->
								<th name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if ($DO_SKU != 0) { ?>
								<?php foreach ($DO_SKU as $i => $sku) : ?>
									<tr id="row-<?= $i ?>" class="ui-sortable-handle">
										<td class="text-center"><?= $sku['principle_kode'] ?></td>
										<td class="text-center">
											<?= $sku['sku_kode'] ?>
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_id" value="<?= $i ?>" />
										</td>
										<td class="text-center"><?= $sku['sku_nama_produk'] ?></td>
										<td class="text-center"><?= $sku['sku_kemasan'] ?></td>
										<td class="text-center"><?= $sku['sku_satuan'] ?></td>
										<td class="text-center"><?= $sku['sku_qty'] ?></td>
										<td class="text-center"><?= $sku['tipe_stock_nama'] ?></td>
										<td class="text-center"><?= $sku['sku_request_expdate'] ?></td>
										<td class="text-center"><?= $sku['sku_filter_expdate'] ?></td>
										<td class="text-center"><?= $sku['sku_filter_expdatebulan'] ?></td>
										<td class="text-center">
											<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKUDO('<?= $sku['sku_id'] ?>', '<?= $sku['sku_kode'] ?>', '<?= $sku['sku_nama_produk'] ?>')"><i class="fa fa-eye"></i></button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div style="float: right">
					<button onclick="ResetTemp()" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></button>
					<button class="btn-submit btn btn-danger" id="btnbatalkandobatch"><i class="fa fa-close"></i> <label name="CAPTION-BATALKAN">Batalkan</label></button>
					<button class="btn-submit btn btn-warning" id="btnconfirmdobatch"><i class="fa fa-check"></i> <label name="CAPTION-LAKSANAKAN">Laksanakan</label></button>
					<button class="btn-submit btn btn-success" id="btnupdatedobatch"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-simulasiarea" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width:80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-SIMULASIAREA">Simulasi Area</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<label name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
						<input type="text" id="simulasiTanggalKirim" class="form-control datepicker" name="simulasiTanggalKirim" autocomplete="off" disabled value="<?= date('d-m-Y') ?>">
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label" for="simulasiTipeLayananID" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
							<select name="simulasiTipeLayananID" class="form-control select2" id="simulasiTipeLayananID" disabled>
								<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
								<?php foreach ($TipePelayanan as $row) : ?>
									<option <?= $row['tipe_layanan_nama'] == 'Delivery Only' ? 'selected' : '' ?> value="<?= $row['tipe_layanan_id'] ?> || <?= $row['tipe_layanan_kode'] ?> || <?= $row['tipe_layanan_nama'] ?>">
										<?= $row['tipe_layanan_nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label" for="simulasiTipeDOID" name="CAPTION-TIPE">Tipe</label>
							<select name="simulasiTipeDOID" class="form-control select2" id="simulasiTipeDOID" disabled>
								<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
								<?php foreach ($TipeDeliveryOrder as $row) : ?>
									<option <?= $row['tipe_delivery_order_alias'] == 'Mix' ? 'selected' : '' ?> value="<?= $row['tipe_delivery_order_id'] ?>">
										<?= $row['tipe_delivery_order_alias'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label" for="segmentasi1" name="CAPTION-SEGMENTASI">Segmentasi</label> <strong>1</strong>
							<select name="segmentasi1" class="form-control selectpickersegment1" data-live-search="true" style="color:#000000" multiple required data-actions-box="true" title="Pilih Segmentasi 1" id="segmentasi1" onchange="changeSegmentasi(1)">
								<?php foreach ($Segmentasi1 as $row) : ?>
									<option value="<?= $row['client_pt_segmen_id'] ?>">
										<?= $row['client_pt_segmen_nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label" for="segmentasi2" name="CAPTION-SEGMENTASI">Segmentasi</label> <strong>2</strong>
							<select name="segmentasi2" class="form-control selectpickersegment2" data-live-search="true" style="color:#000000" multiple required data-actions-box="true" title="Pilih Segmentasi 2" id="segmentasi2" onchange="changeSegmentasi(2)">
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label" for="segmentasi3" name="CAPTION-SEGMENTASI">Segmentasi</label> <strong>3</strong>
							<select name="segmentasi3" class="form-control selectpickersegment3" data-live-search="true" style="color:#000000" multiple required data-actions-box="true" title="Pilih Segmentasi 3" id="segmentasi3">
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<button style="float: right; margin-right: 1rem;" type="button" onclick="searchAreaSimulasi()" class="btn btn-success"><label name="CAPTION-SEARCH">Search</label></button>
				</div>
				<br>
				<div class="row showKendaraan" hidden>
					<div class="col-md-4">
						<label name="CAPTION-KENDARAAN">Kendaraan</label>
						<select name="simulasiKendaraanID" class="form-control select2" id="simulasiKendaraanID" onchange="getSimulasiMuatan(this.value)">
							<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
							<?php foreach ($Kendaraan as $row) : ?>
								<option value="<?= $row['kendaraan_id'] ?>"><?= $row['kendaraan_nopol'] ?> -
									<?= $row['kendaraan_model'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-8" style="margin-top: 2.5rem;">
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-MAKSIMALBERAT">Maksimal Berat (gram)</label></b></span>
									<input type="text" id="simulasiMaxBerat" class="form-control" name="simulasiMaxBerat" readonly autocomplete="off" value="<?= $header['beratMax'] ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-MAKSIMALVOLUME">Maksimal Volume (cm<sup>3</sup>)</label></b></span>
									<input type="text" id="simulasiMaxVolume" class="form-control" name="simulasiMaxVolume" readonly autocomplete="off" value="<?= $header['volumeMax'] ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-BERATPENGIRIMAN">Berat Pengiriman</label> (gram)</b></span>
									<input type="text" id="simulasiBeratTerpakai" class="form-control" name="simulasiBeratTerpakai" readonly autocomplete="off" value="0">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-VOLUMEPENGIRIMAN">Volume Pengiriman</label> (cm<sup>3</sup>)</b></span>
									<input type="text" id="simulasiVolumeTerpakai" class="form-control" name="simulasiVolumeTerpakai" readonly autocomplete="off" value="0">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-BERATRETUR">Berat Retur</label> (gram)</b></span>
									<input type="text" id="simulasiBeratRetur" class="form-control" name="simulasiBeratRetur" readonly autocomplete="off" value="0">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-group">
									<span class="input-group-addon"><b><label name="CAPTION-VOLUMERETUR">Volume Retur</label> (cm<sup>3</sup>)</b></span>
									<input type="text" id="simulasiVolumeRetur" class="form-control" name="simulasiVolumeRetur" readonly autocomplete="off" value="0">
								</div>
							</div>
						</div>
					</div>
				</div>
				<br><br>
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<table id="data-table-simulasi-area" width="100%" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th class="text-center" width="5%"><input type="checkbox" style="transform: scale(1.5)" name="select-all-simulasi-area" id="select-all-simulasi-area" onchange="allCheckedArea(this)" value="1"></th>
									<th class="text-center" name="CAPTION-PILIH">Pilih</th>
									<th class="text-center" name="CAPTION-AREAWILAYAH">Area Wilayah</th>
									<th class="text-center" name="CAPTION-AREA">Area</th>
									<th class="text-center" name="CAPTION-JUMLAHOUTLET">Jumlah Outlet</th>
									<th class="text-center" name="CAPTION-JUMLAHDO">Jumlah DO</th>
									<th class="text-center" name="CAPTION-BERATGRAM">Berat (gram)</th>
									<th class="text-center"><label name="CAPTION-VOLUME">Volume</label> (cm<sup>3</sup>)</th>
									<th class="text-center" name="CAPTION-TIPE">Tipe</th>
									<!-- <th class="text-center" style="color:white;" name="CAPTION-JUMLAHINCTN">Jumlah In CTN</th> -->
									</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot></tfoot>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-6" style="float: right;">
						<button type="button" class="btn btn-info" id="btnPilihSimulasiArea" onclick="btnPilihSimulasiArea()"><label name="CAPTION-PILIH">Pilih</label></button>
						<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-dodraft" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width:80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<div class="col-lg-6 col-md-6">
					<h4 class="modal-title"><label name="CAPTION-CARIDRAFTSURATTUGASPENGIRIMAN">Cari Draft Surat Tugas
							Pengiriman</label></h4>
				</div>
				<div class="col-lg-6 col-md-6">
					<div style="float: right;">
						<input type="checkbox" style="transform: scale(1);" name="chk_kelas_jalan" id="chk_kelas_jalan" value=""> <b>Filter Berdasarkan Kelas Jalan</b>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-3">
						<label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
						<div class="label-service-type"></div>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-TIPE">Tipe</label>
						<div class="label-do-type"></div>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-AREA">Area</label>
						<div class="label-area"></div>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
						<div class="label-delivery-date"></div>
					</div>
				</div><br><br>
				<div class="row">
					<div class="col-xs-12">
						<table id="data-table-dodraft" width="100%" class="table table-striped">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;"><input type="checkbox" name="select-sku" id="select-dodraft" value="1"></th>
									<th class="text-center" style="color:white;" name="CAPTION-NO">No</th>
									<th class="text-center" style="color:white;" name="CAPTION-NOSOEKSTERNAL">No SO Eksternal</th>
									<th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle</th>
									<th class="text-center" style="color:white;" name="CAPTION-AREA">Area</th>
									<th class="text-center" style="color:white;" name="CAPTION-CUSTOMER">Customer</th>
									<th class="text-center" style="color:white;" name="CAPTION-ALAMATKIRIM">Alamat Kirim</th>
									<th class="text-center" style="color:white;" name="CAPTION-KECAMATAN">Kecamatan</th>
									<th class="text-center" style="color:white;" name="CAPTION-KELASJALAN">Kelas Jalan</th>
									<th class="text-center" style="color:white;" name="CAPTION-BERAT">Berat</th>
									<th class="text-center" style="color:white;" name="CAPTION-VOLUME">Volume</th>
									<th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>
									<th class="text-center" style="color:white;" name="CAPTION-COMPOSITE">Composite</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-6 d-flex justify-content-start">
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left; display: none" id="composite">
							<span class="badge badge-light" style="color: white" id="karton">0</span>
						</button>
						<!-- <button type="button" onclick="hitungQuantity()" class="btn btn-warning" style="font-weight: bold; float:left; display: none;">
							<i class="fas fa-calculator"></i> Hitung Quantity
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Karton <span class="badge badge-light" style="color: white" id="karton">0</span>
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Pack <span class="badge badge-light" style="color: white" id="pack">0</span>
						</button>
						<button type="button" class="btn btn-secondary" style="font-weight: bold; float:left">
							Pcs <span class="badge badge-light" style="color: white" id="pcs">0</span>
						</button> -->
					</div>
					<div class="col-md-6">
						<button type="button" data-dismiss="modal" class="btn btn-info btn-choose-do-draft-multi"><label name="CAPTION-PILIH">Pilih</label></button>
						<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-do-draft-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width:80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas
						Pengiriman</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-2">
								<label name="CAPTION-KODESKU">Kode SKU</label>
							</div>
							<div class="col-xs-1">
								<label>:</label>
							</div>
							<div class="col-xs-9">
								<label id="kode-sku"></label>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-2">
								<label name="CAPTION-SKU">SKU</label>
							</div>
							<div class="col-xs-1">
								<label>:</label>
							</div>
							<div class="col-xs-9">
								<label id="sku-nama-produk"></label>
							</div>
						</div>
					</div>
				</div><br><br>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-summary-sku-detail">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;" name="CAPTION-PRIORITAS">Prioritas</th>
									<th class="text-center" style="color:white;" name="CAPTION-TANGGALDO">Tanggal DO
									</th>
									<th class="text-center" style="color:white;"><label name="CAPTION-NODODRAFT">No. DO
											Draft</label>
										<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No. DO</label>
									</th>
									<th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>



















									<th class="text-center" style="color:white;"><label name="CAPTION-ALAMAT">Alamat</label>
										<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-TELP">Telp</label>
									</th>

									<th class="text-center" style="color:white;" name="CAPTION-TIPEPEMBAYARAN">Tipe
										Pembayaran</th>








									<th class="text-center" style="color:white;" name="CAPTION-TIPELAYANAN">Tipe Layanan
									</th>
									<th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>
									<th class="text-center" style="color:white;" name="CAPTION-QTY">Qty</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-do-draft-by-alamat" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width:80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas
						Pengiriman</label></h4>
			</div>


			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-2">
								<label name="CAPTION-KODESKU">Nama</label>
							</div>
							<div class="col-xs-1">
								<label>:</label>
							</div>
							<div class="col-xs-9">
								<label id="nama_do"></label>
							</div>


						</div>
						<div class="row">
							<div class="col-xs-2">
								<label name="CAPTION-SKU">Alamat</label>
							</div>
							<div class="col-xs-1">
								<label>:</label>
							</div>
							<div class="col-xs-9">
								<label id="alamat_do"></label>
							</div>
						</div>
					</div>
				</div><br><br>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-do-draft-by-alamat">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;"><input type="checkbox" style="transform: scale(1.5)" name="select-all-detail-do" id="select-all-detail-do" onchange="allCheckedDetailDO(this)"></th>
									<th class="text-center" style="color:white;" name="CAPTION-TANGGALDO">Tanggal DO</th>
									<th class="text-center" style="color:white;" name="CAPTION-TGLKIRIM">Tanggal Kirim </th>
									<th class="text-center" style="color:white;" name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</th>
									<th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle</th>
									<th class="text-center" style="color:white;"><label name="CAPTION-NODODRAFT">No. DO Draft</label>
										<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No. DO</label>
									</th>
									<th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>
									<th class="text-center" style="color:white;" name="CAPTION-KIRIMULANG">Kirim Ulang </th>
									<th class="text-center" style="color:white;" name="CAPTION-QTY">Qty </th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="DeleteDODraftByTemp()" class="btn btn-success"><label name="CAPTION-HAPUS">Hapus</label></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>

	</div>
</div>