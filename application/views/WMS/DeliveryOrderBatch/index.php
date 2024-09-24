<style>
	.vertical-align-middle {
		vertical-align: middle !important;
	}
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-BATCHSURATTUGASPENGIRIMAN">batch Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">
				<?php if ($Menu_Access["C"] == 1) : ?>
					<!-- <a href="<?= base_url('WMS/Distribusi/DeliveryOrderBatch/create') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <span name="CAPTION-BUATBARU">Buat Baru</span></a> -->
					<button onclick="modalRitasi()" class="btn btn-primary"><i class="fa fa-plus"></i> <span name="CAPTION-BUATBARU">Buat Baru</span></button>
				<?php endif; ?>
				<button type="button" id="btn-modal-fdjr-draft" class="btn btn-primary"><i class="fa fa-sign-out"></i> <span name="CAPTION-PINDAHSURATTUGASPENGIRIMAN">Pindah Surat Tugas Pengiriman</span></button>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-TANGGALBATCH">Tanggal Batch</label>
								<input type="text" id="filter-do-batch-date" class="form-control" name="filter_do_batch_date" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
								<input type="text" id="filter-do-pengiriman-date" class="form-control" name="filter_do_pengiriman_date" value="" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-NOBATCH">No. Batch</label>
								<input type="text" id="filter-do-batch-number" class="form-control" name="filter_do_batch_number" value="">
							</div>
							<!-- <div class="col-xs-3">
                                <label name="CAPTION-TIPEPENGIRIMAN">Tipe Pengiriman</label>
                                <select id="filter-tipe-pengiriman" name="filter_tipe_pengiriman" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($TipePengiriman as $row) : ?>
                                        <option value="<?= $row['tipe_pengiriman_id'] ?>">
                                            <?= $row['tipe_pengiriman_nama_tipe'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> -->
							<div class="col-xs-3">
								<label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
								<select id="filter-tipe-layanan" name="filter_tipe_layanan" class="form-control select2">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($TipePelayanan as $row) : ?>
										<option value="<?= $row['tipe_layanan_id'] ?>"><?= $row['tipe_layanan_nama'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<!-- <div class="col-xs-3">
								<label>Pengemasan</label>
								<select id="filter-pengemasan" name="filter_pengemasan" class="form-control select2">
									<option value="">Semua</option>
									<option value="Ya">Ya</option>
									<option value="Tidak">Tidak</option>
								</select>
							</div> -->
							<div class="col-xs-3">
								<label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label>
								<select id="filter-tipe-delivery" name="filter_tipe_delivery_order" class="form-control select2">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($TipeDeliveryOrder as $row) : ?>
										<option value="<?= $row['tipe_delivery_order_id'] ?>"><?= $row['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
								<input type="hidden" id="count_fdjr" value="0">
								<input type="hidden" id="count_do" value="0">
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-AREA">Area</label>
								<select id="filter-area" name="filter_area" class="form-control select2">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($Area as $row) : ?>
										<option value="<?= $row['area_id'] ?>"><?= $row['area_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-STATUS">Status</label>
								<select id="filter-status" name="filter_status" class="form-control select2">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
									<?php foreach ($Status as $row) : ?>
										<option value="<?= $row['status_progress_nama'] ?>">
											<?= $row['status_progress_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<br />
						<div class="row">
							<div class="col-xs-12 text-right">
								<span id="loadingviewdobatch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
								<button type="button" id="btn-search-data-do-batch" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
							</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-12">
							<div class="x_content table-responsive">
								<table id="table_list_data_do_batch" width="100%" class="table table-striped table-bordered">
									<thead>
										<tr class="bg-primary">
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALBATCH">Tanggal Batch</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-DRIVER">Driver</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-KENDARAAN">Kendaraan</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-RITASI">Ritasi</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-BERATMAX">Berat (gram) Max</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-VOLUMEMAX">Volume (cm<sup>3</sup>) Max</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-BERATTERPAKAI">Berat (gram) Terpakai</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-VOLUMETERPAKAI">Volume (cm<sup>3</sup>) Terpakai</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-PRESENTASEBERATTERPAKAI">Presentase Berat (gram) Terpakai</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-PRESENTASEVOLUMETERPAKAI">Presentase Volume (cm<sup>3</sup>) Terpakai</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-NOFDJR">No. FDJR</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-TOTALOUTLET">Total Outlet</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
											<td class="text-center" style="color:white;"><strong><label name="CAPTION-ASSIGMENT-DRIVER">Assigment Driver</label></strong></td>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-fdjr-draft" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 90%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><span name="CAPTION-PINDAHSURATTUGASPENGIRIMAN">Pindah Surat Tugas Pengiriman</span></h4>
			</div>
			<div class="modal-body">
				<div class="row" id="panel-do-draft">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<input type="text" id="filter-delivery_order_batch_tanggal_kirim" class="form-control" name="filter-delivery_order_batch_tanggal_kirim" value="" />
									</div>
									<div class="col-xs-6">
										<button type="button" id="btn-search-data-do-batch-draft" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
									</div>
								</div>
							</div>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h4 class="pull-left" name="CAPTION-PILIHDELIVERYORDERYANGINGINDIPINDAH">Pilih delivery order yang ingin dipindah</h4>
								<div class="clearfix"></div>
							</div>
							<table class="table table-bordered" id="table_list_data_do_draft">
								<thead>
									<tr class="bg-primary">
										<th style="text-align: center; vertical-align: middle;color:white;"><input type="checkbox" id="select-all-do-draft" name="select-all-do-draft"></th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-TGLKIRIM">Tanggal Kirim</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOBATCH">No Batch</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NODO">No DO</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOSO">No SO</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOSOEKSTERNAL">No SO Eksternal</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-OUTLET">Outlet</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ALAMAT">Alamat</th>
										<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-TIPE">Tipe</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
						<div class="x_panel">
							<div class="x_title">
								<h4 class="pull-left" name="CAPTION-PILIHDELIVERYORDERYANGINGINDIPINDAH">Pilih surat tugas yang dituju</h4>
								<div class="clearfix"></div>
							</div>
							<table class="table table-bordered" id="table_list_data_do_batch_draft">
								<thead>
									<tr class="bg-primary">
										<th style="text-align: center; vertical-align: middle;color:white;">#</th>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALBATCH">Tanggal Batch</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-NOBATCH">No. Batch</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-DRIVER">Driver</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPELAYANAN">Tipe Layanan</label></strong></td>
										<!-- <td class="text-center" style="color:white;"><strong>Pengemasan</strong></td> -->
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
										<td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn-submit btn btn-primary" id="btn_simpan_fdjr_pindah"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalViewArea" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DETAILAREA">Detail Area</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 table-responsive">
						<table class="table table-striped" id="table-area">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;" name="CAPTION-NO">NO</th>
									<th class="text-center" style="color:white;" name="CAPTION-AREA">Area</th>
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

<div class="modal fade" id="modalRitasi" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg" style="width: 80%;">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-CHECKRITASI">Check Ritasi</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-3">
						<label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
						<input type="date" id="filter-do-pengiriman-date-ritasi" class="form-control" name="filter-do-pengiriman-date-ritasi" value="<?= date("Y-m-d") ?>" />
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-KENDARAAN">Kendaraan</label>
						<select name="kendaraanRitasi" class="form-control select2" id="kendaraanRitasi">
							<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
							<?php foreach ($Kendaraan as $row) : ?>
								<option value="<?= $row['kendaraan_id'] ?>"><?= $row['kendaraan_nopol'] ?> -
									<?= $row['kendaraan_model'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-Driver">Driver</label>
						<select name="driverRitasi" class="form-control select2" id="driverRitasi">
							<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
							<?php foreach ($Driver as $row) : ?>
								<option value="<?= $row['karyawan_id'] ?>"><?= $row['karyawan_nama'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-xs-3">
						<label name="CAPTION-RITASI">Ritasi</label>
						<input type="text" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="ritasi" name="ritasi" value="" />
					</div>
				</div>
				<br>
				<div class="row">
					<button type="button" style="float:right; margin-right:10px" id="btn-search-data-ritasi" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
				</div>
				<br>
				<div class="row">
					<div class="table-responsive">
						<table class="table table-striped" id="table-ritasi">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;" name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</th>
									<th class="text-center" style="color:white;" name="CAPTION-KENDARAAN">Kendaraan</th>
									<th class="text-center" style="color:white;" name="CAPTION-DRIVER">Driver</th>
									<th class="text-center" style="color:white;" name="CAPTION-RITASI">Ritasi</th>
									<th class="text-center" style="color:white;"><label for="" name="CAPTION-SISAKAPASITASBERATGRAM">Sisa Kapasitas Berat</label> (gram)</th>
									<th class="text-center" style="color:white;"><label for="" name="CAPTION-SISAKAPASITASVOLUME">Sisa Kapasitas Volume</label> (cm<sup>3</sup>)</th>
									<th class="text-center" style="color:white;" name="CAPTION-ACTION">Action</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button style="display:none;" class="btn-submit btn btn-primary" id="btn_buat_baru_ritasi"><i class="fa fa-plus"></i> <span name="CAPTION-BUATBARU">Buat Baru</span></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>