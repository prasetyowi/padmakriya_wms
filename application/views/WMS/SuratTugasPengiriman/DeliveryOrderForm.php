<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-SURATTUGASPENGIRIMAN">Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_kode">
								<label class="control-label" for="DeliveryOrder-delivery_order_kode" name="CAPTION-NODO">No DO</label>
								<input readonly="readonly" type="text" id="DeliveryOrder-delivery_order_kode" class="form-control input-sm" name="DeliveryOrder[delivery_order_kode]" autocomplete="off" value="">
								<input readonly="readonly" type="hidden" id="DeliveryOrder-delivery_order_batch_id" class="form-control input-sm" name="DeliveryOrder[delivery_order_batch_id]" autocomplete="off" value="<?= $delivery_order_batch_id ?>">
								<input type="hidden" id="cek_customer" value="0">
								<input type="hidden" id="cek_qty" value="0">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_kode">
								<label class="control-label" for="DeliveryOrder-delivery_order_kode" name="CAPTION-TIPE">Tipe</label>
								<select name="DeliveryOrder[tipe_delivery_order_id]" class="input-sm form-control" id="DeliveryOrder-tipe_delivery_order_id">
									<option value="">** <label name="CAPTION-TIPEDO">Tipe DO</label> **</option>
									<?php foreach ($TipeDeliveryOrder as $type) : ?>
										<option value="<?= $type['tipe_delivery_order_id'] ?>">
											<?= $type['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_status">
								<label class="control-label" for="DeliveryOrder-delivery_order_status" name="CAPTION-STATUS">Status</label>
								<input readonly="readonly" type="text" id="DeliveryOrder-delivery_order_status" class="form-control input-sm" name="DeliveryOrder[delivery_order_status]" autocomplete="off" value="delivered">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_tgl_buat_do">
								<label class="control-label" for="DeliveryOrder-delivery_order_tgl_buat_do" name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
								<input readonly type="text" id="DeliveryOrder-delivery_order_tgl_buat_do" class="form-control input-sm datepicker" name="DeliveryOrder[delivery_order_tgl_buat_do]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_tgl_expired_do">
								<label class="control-label" for="DeliveryOrder-delivery_order_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
								<input readonly type="text" id="DeliveryOrder-delivery_order_tgl_expired_do" class="form-control input-sm datepicker" name="DeliveryOrder[delivery_order_tgl_expired_do]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_tgl_surat_jalan">
								<label class="control-label" for="DeliveryOrder-delivery_order_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
								<input readonly type="text" id="DeliveryOrder-delivery_order_tgl_surat_jalan" class="form-control input-sm datepicker" name="DeliveryOrder[delivery_order_tgl_surat_jalan]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-DeliveryOrder-delivery_order_tgl_rencana_kirim">
								<label class="control-label" for="DeliveryOrder-delivery_order_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
								<input readonly type="text" id="DeliveryOrder-delivery_order_tgl_rencana_kirim" class="form-control input-sm datepicker" name="DeliveryOrder[delivery_order_tgl_rencana_kirim]" autocomplete="off" value="<?= date('d-m-Y') ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group field-DeliveryOrder-client_wms_id">
								<label for="DeliveryOrder-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
								<select id="DeliveryOrder-client_wms_id" class="input-sm form-control select2" name="DeliveryOrder[client_wms_id]" onchange="getCustomer()">
									<?php
									$alamat = "";
									foreach ($Perusahaan as $row) :
										$alamat = $row['client_wms_alamat'];
									?>
										<option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group field-DeliveryOrder-client_wms_alamat">
								<label class="control-label" for="DeliveryOrder-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
								<textarea readonly="readonly" type="text" id="DeliveryOrder-client_wms_alamat" class="form-control input-sm" name="DeliveryOrder[client_wms_alamat]" autocomplete="off"><?= $alamat; ?></textarea>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group field-DeliveryOrder-delivery_order_keterangan">
								<label class="control-label" for="DeliveryOrder-delivery_order_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
								<textarea rows="5" type="text" id="DeliveryOrder-delivery_order_keterangan" class="form-control input-sm" name="DeliveryOrder[delivery_order_keterangan]" autocomplete="off"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group field-DeliveryOrder-delivery_order_tipe_pembayaran">
								<label for="DeliveryOrder-delivery_order_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
								<fieldset>
									<label>
										<input class="radio-payment-type" type="radio" name="DeliveryOrder[delivery_order_tipe_pembayaran]" id="DeliveryOrder-delivery_order_tipe_pembayaran" value="0" onclick="reset_table_sku()" checked> <label name="CAPTION-TUNAI">Tunai</label>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input class="radio-payment-type" type="radio" name="DeliveryOrder[delivery_order_tipe_pembayaran]" id="DeliveryOrder-delivery_order_tipe_pembayaran" value="1" onclick="reset_table_sku()"> <label name="CAPTION-NONTUNAI">Non
											Tunai</label>
									</label>
								</fieldset>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group field-DeliveryOrder-delivery_order_tipe_layanan">
								<label for="DeliveryOrder-delivery_order_tipe_layanan" class="control-label" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
								<?php
								$index_tipe = 0;
								foreach ($TipePelayanan as $row) :
								?>
									<fieldset>
										<label>
											<input class="radio-service-type" type="radio" id="DeliveryOrder-delivery_order_tipe_layanan" name="DeliveryOrder[delivery_order_tipe_layanan]" value="<?= $row['tipe_layanan_nama'] ?>" onclick="getCustomer();" <?= $index_tipe == 0 ? 'Checked' : '' ?>> <?= $row['tipe_layanan_nama'] ?>
										</label>
									</fieldset>
								<?php
									$index_tipe++;
								endforeach;
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-customer">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
						<div class="pull-right">
							<button data-toggle="modal" data-target="#modal-customer" id="btn-choose-customer" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button>
						</div>
						<div class="clearfix"></div>
					</div>
					<input type="hidden" name="DeliveryOrder[client_pt_id]" id="DeliveryOrder-client_pt_id" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_nama]" id="DeliveryOrder-delivery_order_kirim_nama" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_alamat]" id="DeliveryOrder-delivery_order_kirim_alamat" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_provinsi]" id="DeliveryOrder-delivery_order_kirim_provinsi" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_kota]" id="DeliveryOrder-delivery_order_kirim_kota" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_kecamatan]" id="DeliveryOrder-delivery_order_kirim_kecamatan" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_kelurahan]" id="DeliveryOrder-delivery_order_kirim_kelurahan" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_kodepos]" id="DeliveryOrder-delivery_order_kirim_kodepos" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_area]" id="DeliveryOrder-delivery_order_kirim_area" value="" />
					<input type="hidden" name="DeliveryOrder[delivery_order_kirim_telepon]" id="DeliveryOrder-delivery_order_kirim_telepon" value="" />
					<div style="text-align: center; display: none;" class="spinner"><img style="width: 30px;" src="<?= base_url() ?>/assets/images/spinner.gif" alt=""></div>
					<div class="customer-info">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-NAMA">Nama:</label>
								<div class="customer-name"></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-ALAMAT">Alamat:</label>
								<div class="customer-address"></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-AREA">Area:</label>
								<div class="customer-area"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-sku">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-BARANGYANGDIKIRIM">Barang Yang Dikirim</h4>
						<div class="pull-right"><button data-toggle="modal" data-target="#modal-sku" id="btn-choose-prod-delivery" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
						<div class="clearfix"></div>
					</div>
					<table class="table table-striped" id="table-sku-delivery-only">
						<thead>
							<tr class="bg-primary">
								<th class="text-center" style="color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
								<!-- <th class="text-center" style="color:white;">SKU Kode Pabrik</th> -->
								<th class="text-center" style="color:white;" name="CAPTION-SKU">SKU</th>
								<th class="text-center" style="color:white;" name="CAPTION-KEMASAN">Kemasan</th>
								<th class="text-center" style="color:white;" name="CAPTION-SATUAN">Satuan</th>
								<th class="text-center" style="color:white;" name="CAPTION-TIPESTOCK">Tipe Stock</th>
								<th class="text-center" style="color:white;" name="CAPTION-REQEXPDATE">Req Exp Date?</th>
								<th class="text-center" style="color:white;" name="CAPTION-FILTER">Filter</th>
								<th class="text-center" style="color:white;" name="CAPTION-BULAN">Bulan</th>
								<th class="text-center" style="color:white;" name="CAPTION-KETERANGAN">Keterangan</th>
								<th class="text-center" style="color:white;" name="CAPTION-QTYREQ">Qty Req</th>
								<th class="text-center" style="color:white;" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
				<div style="float: right">
					<a href="<?= base_url() ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
					<button class="btn-submit btn btn-success" id="btnsavedo"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-customer" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-CARICUSTOMER">Cari Customer</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-NAMA">Nama</label>
								<input type="text" id="filter-client-name" name="filter_client_name" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-ALAMAT">Alamat</label>
								<input type="text" id="filter-client-address" name="filter_client_address" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TELEPON">Telepon</label>
								<input type="text" id="filter-client-phone" name="filter_client_phone" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-AREA">Area</label>
								<select id="filter-area" name="filter_area" class="form-control input-sm select2" style="width:100%;">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($Area as $area) : ?>
										<option value="<?= $area['area_id'] ?>"><?= $area['area_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-right">
								<label>&nbsp;</label>
								<div>
									<span id="loadingcustomer" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
									<button type="button" id="btn-search-customer" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-customer">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>
									<th class="text-center" style="color:white;" name="CAPTION-ALAMAT">Alamat</th>
									<th class="text-center" style="color:white;" name="CAPTION-TELEPON">Telepon</th>
									<th class="text-center" style="color:white;" name="CAPTION-AREA">Area</th>
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
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-factory" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-CARIPABRIK">Cari Pabrik</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-3">
								<label name="CAPTION-NAMA">Nama</label>
								<input type="text" id="filter-principle-name" name="principle_nama" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-ALAMAT">Alamat</label>
								<input type="text" id="filter-principle-address" name="principle_alamat" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TELEPON">Telepon</label>
								<input type="text" id="filter-principle-phone" name="principle_telepon" class="form-control input-sm" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-AREA">Area</label>
								<select id="filter-area-principle" name="filter_area_principle" class="form-control input-sm select2" style="width:100%;">
									<option value=""><label name="CAPTION-SEMUA">Semua</label></option>
									<?php foreach ($Area as $area) : ?>
										<option value="<?= $area['area_id'] ?>"><?= $area['area_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-right">
								<label>&nbsp;</label>
								<div>
									<span id="loadingfactory" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
									<button type="button" id="btn-search-factory" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="x_content table-responsive">
							<table id="table-factory" width="100%" class="table table-striped">
								<thead>
									<tr class="bg-primary">
										<th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>
										<th class="text-center" style="color:white;" name="CAPTION-ALAMAT">Alamat</th>
										<th class="text-center" style="color:white;" name="CAPTION-TELEPON">Telepon</th>
										<th class="text-center" style="color:white;" name="CAPTION-AREA">Area</th>
										<th class="text-center" style="color:white;" name="CAPTION-ACTION">Action</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-CARISKU">Cari SKU</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-striped" id="table-sku">
							<thead>
								<tr class="bg-primary">
									<th class="text-center" style="color:white;"><input type="checkbox" name="select-sku" id="select-sku" value="1"></th>
									<th class="text-center" style="color:white;" name="CAPTION-SKUINDUK">Sku Induk</th>
									<th class="text-center" style="color:white;" name="CAPTION-SKU">SKU</th>
									<th class="text-center" style="color:white;" name="CAPTION-KEMASAN">Kemasan</th>
									<th class="text-center" style="color:white;" name="CAPTION-SATUAN">Satuan</th>
									<th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle</th>
									<th class="text-center" style="color:white;" name="CAPTION-BRAND">Brand</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($detail != "0") {
									foreach ($detail as $i => $row) : ?>
										<tr>
											<td width="5%" class="text-center">
												<input type="checkbox" name="CheckboxSKU" id="check-sku-<?= $i ?>" value="<?= $row['sku_id'] ?>">
											</td>
											<td width="15%" class="text-center"><?= $row['sku_induk'] ?></td>
											<td width="25%" class="text-center"><?= $row['sku_nama_produk'] ?></td>
											<td width="8%" class="text-center"><?= $row['sku_kemasan'] ?></td>
											<td width="8%" class="text-center"><?= $row['sku_satuan'] ?></td>
											<td width="10%" class="text-center"><?= $row['principle'] ?></td>
											<td width="10%" class="text-center"><?= $row['brand'] ?></td>
										</tr>
								<?php endforeach;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-info btn-choose-sku-multi"><label name="CAPTION-PILIH">Pilih</label></button>
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>