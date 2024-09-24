<style>
	table.display {
		margin: 0 auto;
		width: 100%;
		clear: both;
		border-collapse: collapse;
		table-layout: fixed;
		word-wrap: break-word;
	}

	#overlay {
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height: 100%;
		display: none;
		background: rgba(0, 0, 0, 0.6);
	}

	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}

	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}

	.is-hide {
		display: none;
	}

	#tbody-listSKU tr,
	#tbody-listLokasiSKU tr {
		cursor: pointer;
	}
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-PENGIRIMANBARANGDETAIL">Pengiriman Barang Detail</h3>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<div class="row">
							<div class="col-md-6">
								<h5 name="CAPTION-PENGIRIMANBARANGDETAIL">Detail Pengiriman Barang</h5>
							</div>
							<div class="col-md-6">
								<a href="<?= base_url('WMS/Distribusi/PengirimanBarang/print?id=') . $stp->serah_terima_kirim_id ?>" style="float: right;" type="button" class="btn btn-danger" target="_blank"><i class="fa fa-print"></i>
									<label>Cetak</label></a>
							</div>
						</div>

					</div>
					<div class="x_content">
						<form id="form-filter-pick-form" action="<?php echo site_url('PickList/PickingProgressForm') ?>" method="POST" class="form-horizontal form-label-left">
							<div class="row">
								<div class="col-lg-6">
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALPENGIRIMANBARANG">Tanggal Pengiriman
											Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="date" class="form-control input-date-start date" name="tgl" id="tgl" placeholder="dd-mm-yyyy" required="required" value="<?= $stp->tgl_create ?>" readonly>
										</div>

									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4  label-align" name="CAPTION-NOPENGIRIMANBARANG">No Pengiriman
											Barang
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" name="no_picking" readonly value="<?= $stp->serah_terima_kirim_kode ?>">

										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALAKHIR">No PPB
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control select2" name="ppb_id" id="ppb_id" required disabled>

												<option value=""><?= $stp->picking_order_kode ?></option>
											</select>
										</div>
									</div>
									<div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align">No FDJR
										</label>
										<div class="col-md-6 col-sm-6">
											<select class="form-control select2" name="no_batch_do" id="no_batch_do" required onchange="" disabled>

												<option value=""><?= $stp->delivery_order_batch_kode ?></option>

											</select>
											<input type="hidden" class="form-control " name="tipe" id="tipe" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" value="<?= $stp->karyawan_nama ?>" name="driver" id="driver" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align">Status
										</label>
										<div class="col-md-6 col-sm-6">
											<input type="text" class="form-control" value="<?= $stp->serah_terima_kirim_status ?>" name="status" id="status" readonly>
										</div>
									</div>
									<div class="item form-group">

										<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan
										</label>
										<div class="col-md-6 col-sm-6">
											<textarea class="form-control" name="keterangan" id="keterangan" cols="10" rows="3" style="width:100%" disabled>
											<?= $stp->serah_terima_kirim_keterangan ?>
											</textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row ro-batch" id="do-table">
			<?php if ($stp_d1 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5 name="CAPTION-DETAILPENGIRIMANBARANGBULK">Detail Pengiriman Barang Picking per SKU</h5>
							<div class="clearfix"></div>
						</div>
						<div id="viewBulk" class="table-responsive" style="overflow-x:auto;">
							<table id="TabelDetailBulk" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
										<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
										<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
										<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
										<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
										<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
										<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
										<!-- <th style="text-align: center;">Checked</th> -->
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									foreach ($stp_d1 as $key => $value) {
									?>
										<tr>
											<td style="text-align: center;"><?= $no ?></td>
											<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
											<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
											<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
											<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
											<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
											<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
											<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
											<td style="text-align: center;"><?= $value['jumlah_serah_terima'] ?></td>
											<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] ?></td>
											<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
										</tr>

									<?php $no++;
									} ?>
								</tbody>
							</table>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } ?>
			<?php if ($stp_d2 != NULL) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h5 name="CAPTION-DETAILPENGIRIMANBARANGSTANDAR">Detail Pengiriman Barang Picking per DO</h5>
							<div class="clearfix"></div>
						</div>
						<!-- </div> -->
						<div id="viewStandar" class="table-responsive" style="overflow-x:auto;">
							<table id="TabelDetailStandar" width="100%" class="table">
								<thead>
									<tr>
										<!-- <th>Action</th> -->
										<th style="text-align: center;" name="CAPTION-NO">No</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-DOKODE">DO Kode</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTNAME">Nama</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-CUSTADDRESS">Alamat</th>
										<!-- <th style="text-align: center;">Kemasan</th>
										<th style="text-align: center;">Satuan</th> -->
										<th style="text-align: center; vertical-align:middle;" hidden name="CAPTION-JUMLAHPAKET">Jumlah Paket</th>
										<th style="text-align: center; vertical-align:middle;" name="CAPTION-TOTALSERAHTERIMA">Jumlah Serah Terima</th>
										<!-- <th style="text-align: center;">Checked</th> -->
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									foreach ($stp_d2 as $key => $value) {
									?>
										<tr>
											<td style="text-align: center;"><?= $no ?></td>
											<td style="text-align: center;"><?= $value['delivery_order_kode'] ?></td>
											<!-- <td style="text-align: center;"><?= $value['tipe_delivery_order_alias'] ?></td> -->
											<!-- <td style="text-align: center;"><?= $value['delivery_order_status'] ?></td> -->
											<td style="text-align: center;"><?= $value['delivery_order_kirim_nama'] ?></td>
											<td style="text-align: center;"><?= $value['delivery_order_kirim_alamat'] ?></td>
											<!-- <td style="text-align: center;"><?= $value['delivery_order_kirim_telp'] ?></td> -->
											<td style="text-align: center;" hidden><?= $value['jumlah_paket'] ?></td>
											<td style="text-align: center;"><?= $value['jumlah_serah_terima'] ?></td>
											<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
										</tr>

									<?php $no++;
									} ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	<?php } ?>
	<?php if ($stp_d3 != NULL) { ?>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h5>Detail Pengiriman Barang DO Reschedule</h5>
					<div class="clearfix"></div>
				</div>
				<div id="viewReschedule" class="table-responsive" style="overflow-x:auto;">
					<table id="TabelDetailReschedule" width="100%" class="table">
						<thead>
							<tr>
								<!-- <th>Action</th> -->
								<th style="text-align: center;" name="CAPTION-NO">No</th>
								<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
								<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
								<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
								<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
								<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
								<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
								<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
								<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
								<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
								<!-- <th style="text-align: center;">Checked</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($stp_d3 as $key => $value) {
							?>
								<tr>
									<td style="text-align: center;"><?= $no ?></td>
									<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
									<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
									<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
									<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
									<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
									<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
									<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
									<td style="text-align: center;"><?= $value['jumlah_serah_terima'] ?></td>
									<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] ?></td>
									<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
								</tr>

							<?php $no++;
							} ?>
						</tbody>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	<?php } ?>
	<?php if ($stp_d4 != NULL) { ?>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h5>Detail Pengiriman Barang Canvas</h5>
					<div class="clearfix"></div>
				</div>
				<div id="viewCanvas" class="table-responsive" style="overflow-x:auto;">
					<table id="TabelDetailCanvas" width="100%" class="table">
						<thead>
							<tr>
								<!-- <th>Action</th> -->
								<th style="text-align: center;" name="CAPTION-NO">No</th>
								<th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
								<th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
								<th style="text-align: center;" name="CAPTION-SKUNAMA">Nama Barang</th>
								<th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
								<th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
								<th style="text-align: center;" hidden name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
								<th style="text-align: center;" hidden name="CAPTION-JUMLAHDIAMBIL">Jumlah Diambil</th>
								<th style="text-align: center;" name="CAPTION-JUMLAHTERIMABAGUS">Jumlah Terima (Bagus)</th>
								<th style="text-align: center;" name="CAPTION-JUMLAHTERIMARUSAK">Jumlah Terima (Rusak)</th>
								<!-- <th style="text-align: center;">Checked</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($stp_d4 as $key => $value) {
							?>
								<tr>
									<td style="text-align: center;"><?= $no ?></td>
									<td style="text-align: center;"><?= $value['principle_kode'] ?></td>
									<td style="text-align: center;"><?= $value['sku_kode'] ?></td>
									<td style="text-align: center;"><?= $value['sku_nama_produk'] ?></td>
									<td style="text-align: center;"><?= $value['sku_kemasan'] ?></td>
									<td style="text-align: center;"><?= $value['sku_satuan'] ?></td>
									<td style="text-align: center;" hidden><?= $value['jumlah_ambil_plan'] ?></td>
									<td style="text-align: center;" hidden><?= $value['jumlah_ambil_aktual'] ?></td>
									<td style="text-align: center;"><?= $value['jumlah_serah_terima'] ?></td>
									<td style="text-align: center;"><?= $value['jumlah_serah_terima_rusak'] ?></td>
									<!-- <td style="text-align: center;"><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0" disabled></td> -->
								</tr>

							<?php $no++;
							} ?>
						</tbody>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	<?php } ?>
	</div>
	<div class="row mt-2">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel" style="display: flex;align-items:center;justify-content:space-between">

				<div class="text-right">
					<!-- <a class="btn btn-primary btn-update-picklist-progress" id="saveData">Simpan</a> -->
					<a class="btn btn-warning" href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangMenu') ?>"><label name="CAPTION-BACK">Kembali</label></a>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>