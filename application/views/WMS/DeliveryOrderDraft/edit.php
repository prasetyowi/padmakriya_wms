<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-DRAFTSURATTUGASPENGIRIMAN"> Draft Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">

			</div>
		</div>
		<?php foreach ($DOHeader as $header) : ?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="clearfix"></div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_kode">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_kode" name="CAPTION-NODO">No DO</label>
									<input readonly="readonly" type="text" id="deliveryorderdraft-delivery_order_draft_kode" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_kode]" autocomplete="off" value="<?= $header['delivery_order_draft_kode']; ?>">
									<input readonly="readonly" type="hidden" id="deliveryorderdraft-sales_order_id" class="form-control input-sm" name="DeliveryOrderDraft[sales_order_id]" autocomplete="off" value="<?= $header['sales_order_id']; ?>">
									<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_id" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_id]" autocomplete="off" value="<?= $header['delivery_order_draft_id']; ?>">
									<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_reff_id" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_reff_id]" autocomplete="off" value="<?= $header['delivery_order_draft_reff_id']; ?>">
									<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_reff_no" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_reff_no]" autocomplete="off" value="<?= $header['delivery_order_draft_reff_no']; ?>">
									<input type="hidden" id="cek_customer" value="1">
									<input type="hidden" id="cek_qty" value="0">
									<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_update_tgl" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_update_tgl]" autocomplete="off" value="<?= $header['delivery_order_draft_update_tgl']; ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_kode">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_kode" name="CAPTION-TIPE">Tipe</label>
									<select name="DeliveryOrderDraft[tipe_delivery_order_id]" class="input-sm form-control" id="deliveryorderdraft-tipe_delivery_order_id">
										<option value="">** <label name="CAPTION-TIPEDO">Tipe DO</label> **</option>
										<?php foreach ($TipeDeliveryOrder as $type) : ?>
											<option value="<?= $type['tipe_delivery_order_id'] ?>" <?= $type['tipe_delivery_order_id'] == $header['tipe_delivery_order_id'] ? 'selected' : '' ?>>
												<?= $type['tipe_delivery_order_alias'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_status">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_status" name="CAPTION-STATUS">Status</label>
									<input readonly="readonly" type="text" id="deliveryorderdraft-delivery_order_draft_status" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_status]" autocomplete="off" value="<?= $header['delivery_order_draft_status']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_buat_do">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_buat_do" name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_buat_do" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_buat_do]" autocomplete="off" value="<?= $header['delivery_order_draft_tgl_buat_do']; ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_expired_do">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_expired_do" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_expired_do]" autocomplete="off" value="<?= $header['delivery_order_draft_tgl_expired_do']; ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_surat_jalan">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_surat_jalan]" autocomplete="off" value="<?= $header['delivery_order_draft_tgl_surat_jalan']; ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
									<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_rencana_kirim]" autocomplete="off" value="<?= $header['delivery_order_draft_tgl_rencana_kirim']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group field-deliveryorderdraft-client_wms_id">
									<label for="deliveryorderdraft-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
									<select id="deliveryorderdraft-client_wms_id" class="input-sm form-control select2" name="DeliveryOrderDraft[client_wms_id]" onchange="getCustomer()">
										<option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
										<?php foreach ($Perusahaan as $row) : ?>
											<option value="<?= $row['client_wms_id'] ?>" <?= $row['client_wms_id'] == $header['client_wms_id'] ? 'selected' : '' ?>>
												<?= $row['client_wms_nama'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-group field-deliveryorderdraft-client_wms_alamat">
									<label class="control-label" for="deliveryorderdraft-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
									<textarea readonly="readonly" type="text" id="deliveryorderdraft-client_wms_alamat" class="form-control input-sm" name="DeliveryOrderDraft[client_wms_alamat]" autocomplete="off"><?= $header['client_wms_alamat'] ?></textarea>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_keterangan">
									<label class="control-label" for="deliveryorderdraft-delivery_order_draft_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
									<textarea rows="5" type="text" id="deliveryorderdraft-delivery_order_draft_keterangan" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_keterangan]" autocomplete="off"><?= $header['delivery_order_draft_keterangan'] ?></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tipe_pembayaran">
									<label for="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
									<fieldset>
										<label>
											<input class="radio-payment-type" type="radio" name="DeliveryOrderDraft[delivery_order_draft_tipe_pembayaran]" id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" value="0" <?= (int)$header['delivery_order_draft_tipe_pembayaran'] == 0 ? 'checked' : '' ?> onclick="reset_table_sku()"> <label name="CAPTION-TUNAI">Tunai</label>
										</label>
									</fieldset>
									<fieldset>
										<label>
											<input class="radio-payment-type" type="radio" name="DeliveryOrderDraft[delivery_order_draft_tipe_pembayaran]" id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" value="1" <?= $header['delivery_order_draft_tipe_pembayaran'] == 1 ? 'checked' : '' ?> onclick="reset_table_sku()"> <label name="CAPTION-NONTUNAI">Non
												Tunai</label>
										</label>
									</fieldset>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group field-deliveryorderdraft-delivery_order_draft_tipe_layanan">
									<label for="deliveryorderdraft-delivery_order_draft_tipe_layanan" class="control-label" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
									<?php
									$index_tipe = 0;
									foreach ($TipePelayanan as $row) :
									?>
										<fieldset>
											<label>
												<input class="radio-service-type" type="radio" id="deliveryorderdraft-delivery_order_draft_tipe_layanan" name="DeliveryOrderDraft[delivery_order_draft_tipe_layanan]" value="<?= $row['tipe_layanan_nama'] ?>" onclick="getCustomer();" <?= $header['delivery_order_draft_tipe_layanan'] == $row['tipe_layanan_nama'] ? 'checked' : '' ?>>
												<?= $row['tipe_layanan_nama'] ?>
											</label>
										</fieldset>
									<?php
										$index_tipe++;
									endforeach;
									?>
								</div>
							</div>
							<div class="col-xs-3" id="tunai">
								<div class="form-group">
									<label class="control-label" for="" name="CAPTION-INPUTPEMBAYARANTUNAI">Input Pembayaran Tunai</label>
									<input type="number" id="input_tunai" class="form-control input-sm" name="input_tunai" autocomplete="off" placeholder="0" value="<?= $header['delivery_order_draft_nominal_tunai'] ?>">
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
									<input type="file" class="form-control" name="file" id="file" placeholder="upload attachment" onchange="previewFile()" required accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
									<div class="row" id="show-file"></div>
									<div id="hide-file">
										<a href="<?= base_url('assets/images/uploads/Invoice/') . $header['delivery_order_draft_attachment'] ?>" target="_blank"><?= $header['delivery_order_draft_attachment'] ?></a>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="panel-customer" style="<?= $header['client_pt_id'] == '' ? 'display:none;' : '' ?>">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
							<div class="pull-right"><button data-toggle="modal" data-target="#modal-customer" id="btn-choose-customer" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
							<div class="clearfix"></div>
						</div>
						<input type="hidden" name="DeliveryOrderDraft[client_pt_id]" id="deliveryorderdraft-client_pt_id" value="<?= $header['client_pt_id']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_nama]" id="deliveryorderdraft-delivery_order_draft_kirim_nama" value="<?= $header['delivery_order_draft_kirim_nama']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_alamat]" id="deliveryorderdraft-delivery_order_draft_kirim_alamat" value="<?= $header['delivery_order_draft_kirim_alamat']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_provinsi]" id="deliveryorderdraft-delivery_order_draft_kirim_provinsi" value="<?= $header['delivery_order_draft_kirim_provinsi']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kota]" id="deliveryorderdraft-delivery_order_draft_kirim_kota" value="<?= $header['delivery_order_draft_kirim_kota']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kecamatan]" id="deliveryorderdraft-delivery_order_draft_kirim_kecamatan" value="<?= $header['delivery_order_draft_kirim_kecamatan']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kelurahan]" id="deliveryorderdraft-delivery_order_draft_kirim_kelurahan" value="<?= $header['delivery_order_draft_kirim_kelurahan']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kodepos]" id="deliveryorderdraft-delivery_order_draft_kirim_kodepos" value="<?= $header['delivery_order_draft_kirim_kodepos']; ?>" />
						<!-- <input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_area]" id="deliveryorderdraft-delivery_order_draft_kirim_area" value="<?= $header['delivery_order_draft_kirim_area']; ?>" /> -->
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_telepon]" id="deliveryorderdraft-delivery_order_draft_kirim_telepon" value="<?= $header['delivery_order_draft_kirim_telp']; ?>" />
						<div style="text-align: center; display: none;" class="spinner"><img style="width: 30px;" src="<?= base_url() ?>/assets/images/spinner.gif" alt=""></div>
						<div class="customer-info">
							<div class="row">
								<div class="col-xs-4">
									<label name="CAPTION-NAMA">Nama:</label>
									<div class="customer-name"><?= $header['delivery_order_draft_kirim_nama'] ?></div>
								</div>
								<div class="col-xs-4">
									<label name="CAPTION-ALAMAT">Alamat:</label>
									<div class="customer-address"><?= $header['delivery_order_draft_kirim_alamat'] ?></div>
								</div>
								<div class="col-xs-4">
									<label name="CAPTION-AREA">Area:</label>
									<select id="deliveryorderdraft-delivery_order_draft_kirim_area" name="DeliveryOrderDraft[delivery_order_draft_kirim_area]" class="form-control input-sm select2" style="width:100%;">
										<option value=""><label name="CAPTION-All">All</label></option>
										<?php foreach ($Area as $area) : ?>
											<option value="<?= $area['area_nama'] ?>" <?= $area['area_nama'] == $header['delivery_order_draft_kirim_area'] ? 'selected' : '' ?>>
												<?= $area['area_nama'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="panel-factory" style="<?= $header['pabrik_id'] == '' ? 'display:none;' : '' ?>">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
							<div class="pull-right"><button data-toggle="modal" data-target="#modal-factory" id="btn-choose-factory" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
							<div class="clearfix"></div>
						</div>
						<input type="hidden" name="DeliveryOrderDraft[pabrik_id]" id="deliveryorderdraft-pabrik_id" value="<?= $header['pabrik_id']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_nama]" id="deliveryorderdraft-delivery_order_draft_ambil_nama" value="<?= $header['delivery_order_draft_ambil_nama']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_alamat]" id="deliveryorderdraft-delivery_order_draft_ambil_alamat" value="<?= $header['delivery_order_draft_ambil_alamat']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_provinsi]" id="deliveryorderdraft-delivery_order_draft_ambil_provinsi" value="<?= $header['delivery_order_draft_ambil_provinsi']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kota]" id="deliveryorderdraft-delivery_order_draft_ambil_kota" value="<?= $header['delivery_order_draft_ambil_kota']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kecamatan]" id="deliveryorderdraft-delivery_order_draft_ambil_kecamatan" value="<?= $header['delivery_order_draft_ambil_kecamatan']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kelurahan]" id="deliveryorderdraft-delivery_order_draft_ambil_kelurahan" value="<?= $header['delivery_order_draft_ambil_kelurahan']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kodepos]" id="deliveryorderdraft-delivery_order_draft_ambil_kodepos" value="<?= $header['delivery_order_draft_ambil_kodepos']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_telepon]" id="deliveryorderdraft-delivery_order_draft_ambil_telepon" value="<?= $header['delivery_order_draft_ambil_telp']; ?>" />
						<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_area]" id="deliveryorderdraft-delivery_order_draft_ambil_area" value="<?= $header['delivery_order_draft_ambil_area']; ?>" />
						<div class="factory-info">
							<div class="row">
								<div class="col-xs-4">
									<label name="CAPTION-NAMA">Nama:</label>
									<div class="factory-name" value=""><?= $header['delivery_order_draft_ambil_nama'] ?>
									</div>
								</div>
								<div class="col-xs-4">
									<label name="CAPTION-ALAMAT">Alamat:</label>
									<div class="factory-address" value="">
										<?= $header['delivery_order_draft_ambil_alamat'] ?></div>
								</div>
								<div class="col-xs-4">
									<label name="CAPTION-AREA">Area:</label>
									<div class="factory-area" value=""><?= $header['delivery_order_draft_ambil_area'] ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
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
								<th class="text-center" style="color:white;" name="CAPTION-REQEXPDATE">Req Exp Date?
								</th>
								<th class="text-center" style="color:white;" name="CAPTION-FILTER">Filter</th>
								<th class="text-center" style="color:white;" name="CAPTION-BULAN">Bulan</th>
								<th class="text-center" style="color:white;" name="CAPTION-KETERANGAN">Keterangan</th>
								<th class="text-center" style="color:white;" name="CAPTION-QTYREQ">Qty Req</th>
								<th class="text-center" style="color:white;" name="CAPTION-FLAG">Flag</th>
								<th class="text-center" style="color:white;" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $arr_sku = array();
							if (!empty($DODetail)) { ?>
								<?php
								$arr_sku = array();
								foreach ($DODetail as $i => $detail) :
									array_push($arr_sku, $detail['sku_id']);
								?>
									<tr id="row-<?= $i ?>" class="row-item">
										<td style="display: none">
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_id" value="<?= $detail['sku_id']; ?>" class="sku-id" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-gudang_id" class="gudang-id" value="<?= $detail['gudang_id']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-gudang_detail_id" class="gudang-detail-id" value="<?= $detail['gudang_detail_id']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_harga_satuan" class="sku-harga-satuan" value="<?= $detail['sku_harga_satuan']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_disc_percent" class="sku-disc-percent" value="0" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_disc_rp" class="sku-disc-rp" value="0" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_harga_nett" class="sku-harga-nett" value="<?= $detail['sku_harga_nett']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_weight" class="sku-weight" value="<?= $detail['sku_weight']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_weight_unit" class="sku-weight-unit" value="<?= $detail['sku_weight_unit']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_length" class="sku-length" value="<?= $detail['sku_length']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_length_unit" class="sku-length-unit" value="<?= $detail['sku_length_unit']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_width" class="sku-width" value="<?= $detail['sku_width']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_width_unit" class="sku-width-unit" value="<?= $detail['sku_width_unit']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_height" class="sku-height" value="<?= $detail['sku_height']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_height_unit" class="sku-height-unit" value="<?= $detail['sku_height_unit']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_volume" class="sku-volume" value="<?= $detail['sku_volume']; ?>" />
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_volume_unit" class="sku-volume-unit" value="<?= $detail['sku_volume_unit']; ?>" />
										</td>
										<td class="text-center">
											<span class="sku-kode-label"><?= $detail['sku_kode']; ?></span>
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_kode" class="form-control sku-kode" value="<?= $detail['sku_kode']; ?>" />
										</td>
										<td class="text-center" style="display:none;"></td>
										<td class="text-center">
											<span class="sku-nama-produk-label"><?= $detail['sku_nama_produk']; ?></span>
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_nama_produk" class="form-control sku-nama-produk" value="<?= $detail['sku_nama_produk']; ?>" />
										</td>
										<td class="text-center">
											<span class="sku-kemasan-label"><?= $detail['sku_kemasan']; ?></span>
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_kemasan" class="form-control sku-kemasan" value="<?= $detail['sku_kemasan']; ?>" />
										</td>
										<td class="text-center">
											<span class="sku-satuan-label"><?= $detail['sku_satuan']; ?></span>
											<input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_satuan" class="form-control sku-satuan" value="<?= $detail['sku_satuan']; ?>" />
										</td>
										<td class="text-center" style="width:10%">
											<select id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_tipe_stock" class="form-control sku-tipe-stock">
												<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
												<?php for ($x = 0; $x < count($Lokasi); $x++) { ?>
													<?php if ($detail['tipe_stock'] == $Lokasi[$x]->nama) { ?>
														<option value="<?= $Lokasi[$x]->nama ?>" selected><?= $Lokasi[$x]->nama ?>
														</option>
													<?php } else { ?>
														<option value="<?= $Lokasi[$x]->nama ?>"><?= $Lokasi[$x]->nama ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</td>
										<td class="text-center" style="width:10%">
											<select id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_request_expdate" class="form-control sku-request-expdate" onchange="reqFilter(this.value,'<?= $i ?>')">
												<option value="0" <?= $detail['sku_request_expdate'] == '0' ? 'selected' : '' ?>><label name="CAPTION-TIDAK">Tidak</label></option>
												<option value="1" <?= $detail['sku_request_expdate'] == '1' ? 'selected' : '' ?>><label name="CAPTION-YA">Ya</label></option>
											</select>
										</td>
										<td class="text-center" style="width:10%">
											<select id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_filter_expdate" class="form-control sku-filter-expdate" disabled>
												<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
												<option value=">=" <?= $detail['sku_filter_expdate'] == '>=' ? 'selected' : '' ?>>=></option>
											</select>
										</td>
										<td class="text-center" style="width:10%">
											<select id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_filter_expdatebulan" class="form-control sku-filter-expdatebulan" <?= $detail['sku_request_expdate'] == '0' ? 'disabled' : '' ?>>
												<option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
												<?php for ($x = 0; $x < count($Bulan); $x++) { ?>
													<option value="<?= $Bulan[$x] ?>" <?= $detail['sku_filter_expdatebulan'] == $Bulan[$x] ? 'selected' : '' ?>>
														<?= $Bulan[$x] ?></option>
												<?php } ?>
											</select>
										</td>
										<td class="text-center"><input type="text" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_keterangan" class="form-control sku-keterangan" value="<?= $detail['sku_keterangan']; ?>" />
										</td>
										<td class="text-center"><input min="0" type="number" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_qty" class="form-control sku-qty" value="<?= $detail['sku_qty']; ?>" onchange="UpdateTotalHarga('<?= $i ?>')" /></td>
										<td class="text-center">
											<?php
											if ($detail['sku_harga_satuan'] == 0 && $detail['sku_harga_nett'] == 0) {
												echo "Bonus";
											} else {
												echo "Jual";
											}
											?>
										</td>
										<td class="text-center">
											<?php if ($header['tipe_delivery_order_nama'] != "Retur") { ?>
												<!-- <button class="btn btn-primary btn-small" onclick="ViewSKUStock('<?= $detail['sku_id']; ?>','<?= $detail['sku_kode']; ?>','<?= $detail['sku_nama_produk']; ?>','<?= round($detail['sku_qty']); ?>','<?= round($detail['sku_konversi_faktor']); ?>')"><i class="fa fa-plus"></i></button> -->

												<button class="btn btn-danger btn-small btn-delete-sku" onclick="DeleteSKU(this,<?= $i ?>)"><i class="fa fa-trash"></i></button>
											<?php } ?>
										</td>
										<td style="display: none;">
											<input type="hidden" id="cek_checkbox" name="cek_checkbox" class="form-control sku-satuan" value="<?= $detail['sku_id']; ?>" />
										</td>
									</tr>
								<?php endforeach; ?>
							<?php } else { ?>
								<tr>
									<td class="text-center" colspan="13">Data Kosong</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<input type="hidden" id="txt_arr_sku" value="<?= implode(',', $arr_sku); ?>" />
				<div style="float: right">
					<a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
					<button class="btn-submit btn btn-danger" id="btnrejectdodraft"><i class="fa fa-times"></i> <label name="CAPTION-BATAL">Batal</label></button>
					<!-- <button class="btn-submit btn btn-warning" id="btnconfirmdodraft"><i class="fa fa-check"></i> <label name="CAPTION-APPROV">Approv</label></button> -->
					<button class="btn-submit btn btn-success" id="btnupdatedodraft"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
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
						<table class="table table-striped" style="width: 100%" id="table-customer">

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
						<div class="row">
							<div class="col-xs-2">
								<label name="CAPTION-SKUINDUK">SKU Induk</label>
								<input type="text" id="filter-sku-induk" name="filter_sku_induk" class="form-control input-sm" />
							</div>
							<div class="col-xs-2">
								<label name="CAPTION-SKU">SKU</label>
								<input type="text" id="filter-sku-nama-produk" name="filter_sku_nama_produk" class="form-control input-sm" />
							</div>
							<div class="col-xs-2">
								<label name="CAPTION-KEMASAN">Kemasan</label>
								<input type="text" id="filter-sku-kemasan" name="filter_sku_kemasan" class="form-control input-sm" />
							</div>
							<div class="col-xs-2">
								<label name="CAPTION-SATUAN">Satuan</label>
								<input type="text" id="filter-sku-satuan" name="filter_sku_satuan" class="form-control input-sm" />
							</div>
							<div class="col-xs-2">
								<label name="CAPTION-PRINCIPLE">Principle</label>
								<input type="text" id="filter-principle" name="filter_principle" class="form-control input-sm" />
							</div>
							<div class="col-xs-2">
								<label name="CAPTION-BRAND">Brand</label>
								<input type="text" id="filter-brand" name="filter_brand" class="form-control input-sm" />
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-right">
								<label>&nbsp;</label>
								<div>
									<span id="loadingsku" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
										<label name="CAPTION-LOADING">Loading</label>...</span>
									<button type="button" id="btn-search-sku" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
								</div>
							</div>
						</div>
					</div>
				</div>
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

<div class="modal fade" id="modal-do-detail2" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-SKUSTOCK">SKU Stock</label></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<input type="hidden" id="filter-sku_id-detail2" value="">
						<input type="hidden" id="filter-sku_kode-detail2" value="">
						<input type="hidden" id="filter-sku_nama_produk-detail2" value="">
						<input type="hidden" id="filter-sku_qty-detail2" value="">
						<input type="hidden" id="filter-sku_konversi_faktor-detail2" value="">

						<button class="btn-submit btn btn-primary" id="btnaddskustock"><i class="fa fa-plus"></i> <span name="CAPTION-TAMBAHSKUSTOCK">Tambah SKU Stock</span></button>
						<table class="table table-striped" id="table-sku-do-detail2">
							<thead>
								<tr class="bg-primary">
									<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">Sku Kode</th>
									<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
									<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
									<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUSTOCK">SKU Stock</th>
									<th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ACTION">Action</th>
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