<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-DRAFTSURATTUGASPENGIRIMAN"> Draft Surat Tugas Pengiriman</h3>
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
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_kode">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_kode" name="CAPTION-NODO">No DO</label>
								<input readonly="readonly" type="text" id="deliveryorderdraft-delivery_order_draft_kode" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_kode]" autocomplete="off" value="">
								<input readonly="readonly" type="hidden" id="deliveryorderdraft-sales_order_id" class="form-control input-sm" name="DeliveryOrderDraft[sales_order_id]" autocomplete="off" value="">
								<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_reff_id" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_reff_id]" autocomplete="off" value="">
								<input readonly="readonly" type="hidden" id="deliveryorderdraft-delivery_order_draft_reff_no" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_reff_no]" autocomplete="off" value="">
								<input type="hidden" id="cek_customer" value="0">
								<input type="hidden" id="cek_qty" value="0">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_kode">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_kode" name="CAPTION-TIPE">Tipe</label>
								<select name="DeliveryOrderDraft[tipe_delivery_order_id]" class="input-sm form-control" id="deliveryorderdraft-tipe_delivery_order_id">
									<option value="">** <label name="CAPTION-TIPEDO">Tipe DO</label> **</option>
									<?php foreach ($TipeDeliveryOrder as $type) : ?>
										<option value="<?= $type['tipe_delivery_order_id'] ?>">
											<?= $type['tipe_delivery_order_alias'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_status">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_status" name="CAPTION-STATUS">Status</label>
								<input readonly="readonly" type="text" id="deliveryorderdraft-delivery_order_draft_status" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_status]" autocomplete="off" value="Draft">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_buat_do">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_buat_do" name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
								<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_buat_do" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_buat_do]" autocomplete="off" value="<?= set_value('DeliveryOrderDraft[delivery_order_draft_tgl_buat_do]') != "" ? set_value('DeliveryOrderDraft[delivery_order_draft_tgl_buat_do]') : (isset($deliveryOrderDraft['delivery_order_draft_tgl_buat_do']) ? date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_buat_do'])) : date('d-m-Y')) ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_expired_do">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
								<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_expired_do" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_expired_do]" autocomplete="off" value="<?= set_value('DeliveryOrderDraft[delivery_order_draft_tgl_expired_do]') != "" ? set_value('DeliveryOrderDraft[delivery_order_draft_tgl_expired_do]') : (isset($deliveryOrderDraft['delivery_order_draft_tgl_expired_do']) ? date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_expired_do'])) : date('d-m-Y')) ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_surat_jalan">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
								<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_surat_jalan]" autocomplete="off" value="<?= set_value('DeliveryOrderDraft[delivery_order_draft_tgl_surat_jalan]') != "" ? set_value('DeliveryOrderDraft[delivery_order_draft_tgl_surat_jalan]') : (isset($deliveryOrderDraft['delivery_order_draft_tgl_surat_jalan']) ? date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_surat_jalan'])) : "") ?>">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
								<input type="text" id="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim" class="form-control input-sm datepicker" name="DeliveryOrderDraft[delivery_order_draft_tgl_rencana_kirim]" autocomplete="off" value="<?= set_value('DeliveryOrderDraft[delivery_order_draft_tgl_rencana_kirim]') != "" ? set_value('DeliveryOrderDraft[delivery_order_draft_tgl_rencana_kirim]') : (isset($deliveryOrderDraft['delivery_order_draft_tgl_rencana_kirim']) ? date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_rencana_kirim'])) : "") ?>">
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
										<option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group field-deliveryorderdraft-client_wms_alamat">
								<label class="control-label" for="deliveryorderdraft-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
								<textarea readonly="readonly" type="text" id="deliveryorderdraft-client_wms_alamat" class="form-control input-sm" name="DeliveryOrderDraft[client_wms_alamat]" autocomplete="off"></textarea>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_keterangan">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
								<textarea rows="5" type="text" id="deliveryorderdraft-delivery_order_draft_keterangan" class="form-control input-sm" name="DeliveryOrderDraft[delivery_order_draft_keterangan]" autocomplete="off"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tipe_pembayaran">
								<label for="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
								<fieldset>
									<label>
										<input class="radio-payment-type" type="radio" name="DeliveryOrderDraft[delivery_order_draft_tipe_pembayaran]" id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" value="0" onclick="reset_table_sku()" checked> <label name="CAPTION-TUNAI">Tunai</label>
									</label>
								</fieldset>
								<fieldset>
									<label>
										<input class="radio-payment-type" type="radio" name="DeliveryOrderDraft[delivery_order_draft_tipe_pembayaran]" id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" value="1" onclick="reset_table_sku()"> <label name="CAPTION-NONTUNAI">Non
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
											<input class="radio-service-type" type="radio" id="deliveryorderdraft-delivery_order_draft_tipe_layanan" name="DeliveryOrderDraft[delivery_order_draft_tipe_layanan]" value="<?= $row['tipe_layanan_nama'] ?>" onclick="getCustomer();" <?= $index_tipe == 0 ? 'Checked' : '' ?>> <?= $row['tipe_layanan_nama'] ?>
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
								<label class="control-label" for="" name="CAPTION-INPUTPEMBAYARANTUNAI">Input Pembayaran
									Tunai</label>
								<input type="number" id="input_tunai" class="form-control input-sm" name="input_tunai" autocomplete="off" placeholder="0">
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group">
								<label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
								<input type="file" class="form-control" name="file" id="file" placeholder="upload attachment" onchange="previewFile()" required accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
								<div class="row" id="show-file"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-customer" style="display:none;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
						<div class="pull-right"><button data-toggle="modal" data-target="#modal-customer" id="btn-choose-customer" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
						<div class="clearfix"></div>
					</div>
					<input type="hidden" name="DeliveryOrderDraft[client_pt_id]" id="deliveryorderdraft-client_pt_id" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_nama]" id="deliveryorderdraft-delivery_order_draft_kirim_nama" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_alamat]" id="deliveryorderdraft-delivery_order_draft_kirim_alamat" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_provinsi]" id="deliveryorderdraft-delivery_order_draft_kirim_provinsi" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kota]" id="deliveryorderdraft-delivery_order_draft_kirim_kota" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kecamatan]" id="deliveryorderdraft-delivery_order_draft_kirim_kecamatan" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kelurahan]" id="deliveryorderdraft-delivery_order_draft_kirim_kelurahan" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_kodepos]" id="deliveryorderdraft-delivery_order_draft_kirim_kodepos" value="" />
					<!-- <input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_area]" id="deliveryorderdraft-delivery_order_draft_kirim_area" value="" /> -->
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_kirim_telepon]" id="deliveryorderdraft-delivery_order_draft_kirim_telepon" value="" />
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
								<select id="deliveryorderdraft-delivery_order_draft_kirim_area" name="DeliveryOrderDraft[delivery_order_draft_kirim_area]" class="form-control input-sm select2" style="width:100%;">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Area as $area) : ?>
										<option value="<?= $area['area_nama'] ?>"><?= $area['area_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-factory" style="display:none;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
						<div class="pull-right"><button data-toggle="modal" data-target="#modal-factory" id="btn-choose-factory" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
						<div class="clearfix"></div>
					</div>
					<input type="hidden" name="DeliveryOrderDraft[pabrik_id]" id="deliveryorderdraft-pabrik_id" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_nama]" id="deliveryorderdraft-delivery_order_draft_ambil_nama" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_alamat]" id="deliveryorderdraft-delivery_order_draft_ambil_alamat" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_provinsi]" id="deliveryorderdraft-delivery_order_draft_ambil_provinsi" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kota]" id="deliveryorderdraft-delivery_order_draft_ambil_kota" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kecamatan]" id="deliveryorderdraft-delivery_order_draft_ambil_kecamatan" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kelurahan]" id="deliveryorderdraft-delivery_order_draft_ambil_kelurahan" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_kodepos]" id="deliveryorderdraft-delivery_order_draft_ambil_kodepos" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_telepon]" id="deliveryorderdraft-delivery_order_draft_ambil_telepon" value="" />
					<input type="hidden" name="DeliveryOrderDraft[delivery_order_draft_ambil_area]" id="deliveryorderdraft-delivery_order_draft_ambil_area" value="" />
					<div class="factory-info">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-NAMA">Nama:</label>
								<div class="factory-name" value=""></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-ALAMAT">Alamat:</label>
								<div class="factory-address" value=""></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-AREA">Area:</label>
								<div class="factory-area" value=""></div>
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
								<th class="text-center" style="color:white;" name="CAPTION-REQEXPDATE">Req Exp Date?
								</th>
								<th class="text-center" style="color:white;" name="CAPTION-FILTER">Filter</th>
								<th class="text-center" style="color:white;" name="CAPTION-BULAN">Bulan</th>
								<th class="text-center" style="color:white;" name="CAPTION-KETERANGAN">Keterangan</th>
								<th class="text-center" style="color:white;" name="CAPTION-QTYREQ">Qty Req</th>
								<th class="text-center" style="color:white;" name="CAPTION-ACTION">Action</th>
							</tr>
						</thead>
						<tbody>
							<!-- <?php foreach ($items as $i => $item) : ?>
								<tr id="row-<?= $i ?>" class="row-item">
									<td style="display: none">
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_id]" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_id]') : (isset($item['DeliveryOrderDetailDraft']['sku_id']) ? $item['DeliveryOrderDetailDraft']['sku_id'] : "") ?>" class="sku-id" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][gudang_id]" class="gudang-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][gudang_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][gudang_id]') : (isset($item['DeliveryOrderDetailDraft']['gudang_id']) ? $item['DeliveryOrderDetailDraft']['gudang_id'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][gudang_detail_id]" class="gudang-detail-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][gudang_detail_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][gudang_detail_id]') : (isset($item['DeliveryOrderDetailDraft']['gudang_detail_id']) ? $item['DeliveryOrderDetailDraft']['gudang_detail_id'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_harga_satuan]" class="sku-harga-satuan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_harga_satuan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_harga_satuan]') : (isset($item['DeliveryOrderDetailDraft']['sku_harga_satuan']) ? $item['DeliveryOrderDetailDraft']['sku_harga_satuan'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_disc_percent]" class="sku-disc-percent" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_disc_percent]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_disc_percent]') : (isset($item['DeliveryOrderDetailDraft']['sku_disc_percent']) ? $item['DeliveryOrderDetailDraft']['sku_disc_percent'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_disc_rp]" class="sku-disc-rp" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_disc_rp]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_disc_rp]') : (isset($item['DeliveryOrderDetailDraft']['sku_disc_rp']) ? $item['DeliveryOrderDetailDraft']['sku_disc_rp'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_harga_nett]" class="sku-harga-nett" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_harga_nett]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_harga_nett]') : (isset($item['DeliveryOrderDetailDraft']['sku_harga_nett']) ? $item['DeliveryOrderDetailDraft']['sku_harga_nett'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_weight]" class="sku-weight" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_weight]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_weight]') : (isset($item['DeliveryOrderDetailDraft']['sku_weight']) ? $item['DeliveryOrderDetailDraft']['sku_weight'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_weight_unit]" class="sku-weight-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_weight_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_weight_unit]') : (isset($item['DeliveryOrderDetailDraft']['sku_weight_unit']) ? $item['DeliveryOrderDetailDraft']['sku_weight_unit'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_length]" class="sku-length" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_length]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_length]') : (isset($item['DeliveryOrderDetailDraft']['sku_length']) ? $item['DeliveryOrderDetailDraft']['sku_length'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_length_unit]" class="sku-length-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_length_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_length_unit]') : (isset($item['DeliveryOrderDetailDraft']['sku_length_unit']) ? $item['DeliveryOrderDetailDraft']['sku_length_unit'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_width]" class="sku-width" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_width]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_width]') : (isset($item['DeliveryOrderDetailDraft']['sku_width']) ? $item['DeliveryOrderDetailDraft']['sku_width'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_width_unit]" class="sku-width-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_width_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_width_unit]') : (isset($item['DeliveryOrderDetailDraft']['sku_width_unit']) ? $item['DeliveryOrderDetailDraft']['sku_width_unit'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_height]" class="sku-height" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_height]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_height]') : (isset($item['DeliveryOrderDetailDraft']['sku_height']) ? $item['DeliveryOrderDetailDraft']['sku_height'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_height_unit]" class="sku-height-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_height_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_height_unit]') : (isset($item['DeliveryOrderDetailDraft']['sku_height_unit']) ? $item['DeliveryOrderDetailDraft']['sku_height_unit'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_volume]" class="sku-volume" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_volume]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_volume]') : (isset($item['DeliveryOrderDetailDraft']['sku_volume']) ? $item['DeliveryOrderDetailDraft']['sku_volume'] : "") ?>" />
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_volume_unit]" class="sku-volume-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_volume_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_volume_unit]') : (isset($item['DeliveryOrderDetailDraft']['sku_volume_unit']) ? $item['DeliveryOrderDetailDraft']['sku_volume_unit'] : "") ?>" />
									</td>
									<td>
										<span class="sku-kode-label"><?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kode]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kode]') : (isset($item['DeliveryOrderDetailDraft']['sku_kode']) ? $item['DeliveryOrderDetailDraft']['sku_kode'] : "") ?></span>
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_kode]" class="form-control sku-kode" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kode]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kode]') : (isset($item['DeliveryOrderDetailDraft']['sku_kode']) ? $item['DeliveryOrderDetailDraft']['sku_kode'] : "") ?>" />
									</td>
									<td></td>
									<td>
										<span class="sku-nama-produk-label"><?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_nama_produk]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_nama_produk]') : (isset($item['DeliveryOrderDetailDraft']['sku_nama_produk']) ? $item['DeliveryOrderDetailDraft']['sku_nama_produk'] : "") ?></span>
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_nama_produk]" class="form-control sku-nama-produk" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_nama_produk]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_nama_produk]') : (isset($item['DeliveryOrderDetailDraft']['sku_nama_produk']) ? $item['DeliveryOrderDetailDraft']['sku_nama_produk'] : "") ?>" />
									</td>
									<td>
										<span class="sku-kemasan-label"><?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kemasan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kemasan]') : (isset($item['DeliveryOrderDetailDraft']['sku_kemasan']) ? $item['DeliveryOrderDetailDraft']['sku_kemasan'] : "") ?></span>
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_kemasan]" class="form-control sku-kemasan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kemasan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_kemasan]') : (isset($item['DeliveryOrderDetailDraft']['sku_kemasan']) ? $item['DeliveryOrderDetailDraft']['sku_kemasan'] : "") ?>" />
									</td>
									<td>
										<span class="sku-satuan-label"><?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_satuan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_satuan]') : (isset($item['DeliveryOrderDetailDraft']['sku_satuan']) ? $item['DeliveryOrderDetailDraft']['sku_satuan'] : "") ?></span>
										<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_satuan]" class="form-control sku-satuan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_satuan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_satuan]') : (isset($item['DeliveryOrderDetailDraft']['sku_satuan']) ? $item['DeliveryOrderDetailDraft']['sku_satuan'] : "") ?>" />
									</td>
									<td>
										<select name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_request_expdate]" class="form-control sku-request-expdate">
											<option <?= set_select('item[' . $i . '][DeliveryOrderDetailDraft][sku_request_expdate]', '0') ?> value="0">Tidak</option>
											<option <?= set_select('item[' . $i . '][DeliveryOrderDetailDraft][sku_request_expdate]', '1') ?> value="1">Ya</option>
										</select>
									</td>
									<td><input type="text" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_keterangan]" class="form-control sku-keterangan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_keterangan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_keterangan]') : (isset($item['DeliveryOrderDetailDraft']['sku_keterangan']) ? $item['DeliveryOrderDetailDraft']['sku_keterangan'] : "") ?>" /></td>
									<td><input type="number" name="item[<?= $i ?>][DeliveryOrderDetailDraft][sku_qty]" class="form-control sku-qty" value="<?= set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_qty]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetailDraft][sku_qty]') : (isset($item['DeliveryOrderDetailDraft']['sku_qty']) ? $item['DeliveryOrderDetailDraft']['sku_qty'] : "") ?>" /></td>
									<td><button class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-trash"></i></button></td>
								</tr>
							<?php endforeach; ?> -->
						</tbody>
					</table>
				</div>
				<div style="float: right">
					<a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
					<button class="btn-submit btn btn-success" id="btnsavedodraft"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
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
								<label name="CAPTION-AREA">Area</label>
								<select id="filter-area" name="filter_area" class="form-control select2" style="width:100%;">
									<option value=""><label name="CAPTION-ALL">All</label></option>
									<?php foreach ($Area as $area) : ?>
										<option value="<?= $area['area_id'] ?>"><?= $area['area_nama'] ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-NAMA">Nama</label>
								<input type="text" id="filter-client-name" name="filter_client_name" class="form-control" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-ALAMAT">Alamat</label>
								<input type="text" id="filter-client-address" name="filter_client_address" class="form-control" />
							</div>
							<div class="col-xs-3">
								<label name="CAPTION-TELEPON">Telepon</label>
								<input type="text" id="filter-client-phone" name="filter_client_phone" class="form-control" />
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-right">
								<label>&nbsp;</label>
								<div>
									<span id="loadingcustomer" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
									<button type="button" id="btn-search-customer" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
								</div>
								<br>
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