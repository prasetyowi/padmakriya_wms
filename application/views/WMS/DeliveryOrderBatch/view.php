<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-VIEWBATCHSURATTUGASPENGIRIMAN">View Batch Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">
				<a href="<?= base_url('WMS/Distribusi/DeliveryOrderBatch/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
				<a href="<?= base_url('WMS/Distribusi/DeliveryOrderBatch/update/' . $deliveryOrderBatch['delivery_order_batch_id']) ?>" class="btn btn-warning"><i class="fa fa-edit"></i> <label name="CAPTION-UPDATE">Update</label></a>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<label for=""><?= (new M_DeliveryOrderBatch)->getAttributeLabels('delivery_order_batch_tanggal') ?></label>
							<div><?= date("d F Y", strtotime($deliveryOrderBatch['delivery_order_batch_tanggal'])) ?>
							</div>
						</div>
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('delivery_order_batch_kode') ?></label>
							<div><?= $deliveryOrderBatch['delivery_order_batch_kode'] ?></div>
						</div>
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('delivery_order_batch_tipe_layanan_id') ?></label>
							<div><?= isset($serviceType) ? $serviceType->tipe_layanan_nama : "" ?></div>
						</div>
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('tipe_delivery_order_id') ?></label>
							<div><?= isset($doType) && !empty($doType) ? $doType->tipe_delivery_order_alias : "" ?></div>
							<!-- <label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('delivery_order_batch_is_need_packing') ?></label>
							<div><?= $deliveryOrderBatch['delivery_order_batch_is_need_packing'] == '0' ? 'Tidak' : 'Ya' ?></div> -->
						</div>
					</div>
					<br />
					<div class="row">
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('area_id') ?></label>
							<div><?= $area->area_nama ?></div>
						</div>
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('delivery_order_batch_tanggal_kirim') ?></label>
							<div>
								<?= date('d F Y', strtotime($deliveryOrderBatch['delivery_order_batch_tanggal_kirim'])) ?>
							</div>
						</div>
						<div class="col-xs-3">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('tipe_ekspedisi_id') ?></label>
							<div><?= $expeditionType->tipe_ekspedisi_nama ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-dodraft">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas Pengiriman</h4>
						<div class="clearfix"></div>
					</div>
					<table class="table table-bordered table-striped" id="table-do-draft">
						<thead>
							<tr>
								<th name="CAPTION-PRIORITAS">Prioritas</th>
								<th name="CAPTION-TANGGALDO">Tanggal DO</th>
								<th><label name="CAPTION-NODODRAFT">No. DO Draft</label>
									<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No.
										DO</label>
								</th>
								<th name="CAPTION-NAMA">Nama</th>
								<th><label name="CAPTION-ALAMAT">Alamat</label>
									<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-TELP">Telp</label>
								</th>
								<th name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</th>
								<th name="CAPTION-TIPELAYANAN">Tipe Layanan</th>
								<th name="CAPTION-TIPE">Tipe</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($deliveryOrders)) : ?>
								<?php foreach ($deliveryOrders as $i => $item) : ?>
									<tr id="row-<?= $i ?>" class="row-do row-do-<?= $i ?>">
										<td>
											<span class="do-no-urut-rute-label"><?= $item['DeliveryOrder']['delivery_order_no_urut_rute'] ?></span>
										</td>
										<td>
											<span class="do-tgl-buat-do-label"><?= date("d-m-Y", strtotime($item['DeliveryOrder']['delivery_order_tgl_buat_do'])) ?></span>
										</td>
										<td>
											<span class="do-draft-kode-label"><?= $item['DeliveryOrder']['delivery_order_draft_kode'] ?></span><br />
											<span class="do-kode-label"><?= $item['DeliveryOrder']['delivery_order_kode'] ?></span>
										</td>
										<td>
											<span class="do-kirim-nama-label"><?= $item['DeliveryOrder']['delivery_order_kirim_nama'] ?></span>
										</td>
										<td>
											<span class="do-kirim-alamat-label"><?= $item['DeliveryOrder']['delivery_order_kirim_alamat'] ?></span><br />
											<span class="do-kirim-telp-label"><?= $item['DeliveryOrder']['delivery_order_kirim_telp'] ?></span>
										</td>
										<td>
											<span class="do-tipe-pembayaran-label"><?= $item['DeliveryOrder']['delivery_order_tipe_pembayaran'] ?></span>
										</td>
										<td>
											<span class="do-tipe-layanan-label"><?= $item['DeliveryOrder']['delivery_order_tipe_layanan'] ?></span>
										</td>
										<td>
											<span class="do-tipe-layanan-label"><?= $item['DeliveryOrder']['tipe_delivery_order_alias'] ?></span>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
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
					<div class="row">
						<div class="col-xs-12">
							<label name="CAPTION-LOKASIGUDANG">Lokasi Gudang:</label>
							<?= $depoDetail->depo_detail_nama ?>
							<input type="hidden" id="deliveryorderbatch-depo_detail_id" value="<?= $depoDetail->depo_detail_id ?>" />
							<button type="button" class="btn btn-info btn-small btn-check-stock" style="display: none;"><label name="CAPTION-CEKKETERSEDIAANSTOK">Cek Ketersediaan
									Stok</label></button>
						</div>
					</div>
					<br />
					<table class="table table-bordered table-striped" id="table-summary-sku">
						<thead>
							<tr>
								<th name="CAPTION-KODESKU">Kode SKU</th>
								<th name="CAPTION-NAMASKU">Nama SKU</th>
								<th name="CAPTION-KEMASAN">Kemasan</th>
								<th name="CAPTION-SATUAN">Satuan</th>
								<th name="CAPTION-REQEXPDATE">Req Exp Date?</th>
								<th name="CAPTION-QTYREQ">Qty Req</th>
								<th name="CAPTION-QTYAVAILABLE">Qty Available</th>
								<th></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<div id="do-detail-draft-container">
						<?php if (!empty($items)) : ?>
							<?php foreach ($items as $i => $item) : ?>
								<div id="rowdetail-<?= $i ?>" class="row-item row-do-<?= set_value('item[' . $i . '][DeliveryOrderDetail][index_do]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][index_do]') : (isset($item['DeliveryOrderDetail']['index_do']) ? $item['DeliveryOrderDetail']['index_do'] : "") ?>">
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][delivery_order_detail_id]" class="delivery-order-detail-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_detail_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_detail_id]') : (isset($item['DeliveryOrderDetail']['delivery_order_detail_id']) ? $item['DeliveryOrderDetail']['delivery_order_detail_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_id]" class="sku-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_id]') : (isset($item['DeliveryOrderDetail']['sku_id']) ? $item['DeliveryOrderDetail']['sku_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][delivery_order_id]" class="detail-do-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_id]') : (isset($item['DeliveryOrderDetail']['delivery_order_id']) ? $item['DeliveryOrderDetail']['delivery_order_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][delivery_order_batch_id]" class="detail-do-batch-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_batch_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][delivery_order_batch_id]') : (isset($item['DeliveryOrderDetail']['delivery_order_batch_id']) ? $item['DeliveryOrderDetail']['delivery_order_batch_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][depo_id]" class="detail-depo-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][depo_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][depo_id]') : (isset($item['DeliveryOrderDetail']['depo_id']) ? $item['DeliveryOrderDetail']['depo_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][depo_detail_id]" class="depo-detail-id" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][depo_detail_id]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][depo_detail_id]') : (isset($item['DeliveryOrderDetail']['depo_detail_id']) ? $item['DeliveryOrderDetail']['depo_detail_id'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_harga_satuan]" class="sku-harga-satuan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_harga_satuan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_harga_satuan]') : (isset($item['DeliveryOrderDetail']['sku_harga_satuan']) ? $item['DeliveryOrderDetail']['sku_harga_satuan'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_disc_percent]" class="sku-disc-percent" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_disc_percent]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_disc_percent]') : (isset($item['DeliveryOrderDetail']['sku_disc_percent']) ? $item['DeliveryOrderDetail']['sku_disc_percent'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_disc_rp]" class="sku-disc-rp" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_disc_rp]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_disc_rp]') : (isset($item['DeliveryOrderDetail']['sku_disc_rp']) ? $item['DeliveryOrderDetail']['sku_disc_rp'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_harga_nett]" class="sku-harga-nett" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_harga_nett]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_harga_nett]') : (isset($item['DeliveryOrderDetail']['sku_harga_nett']) ? $item['DeliveryOrderDetail']['sku_harga_nett'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_weight]" class="sku-weight" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_weight]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_weight]') : (isset($item['DeliveryOrderDetail']['sku_weight']) ? $item['DeliveryOrderDetail']['sku_weight'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_weight_unit]" class="sku-weight-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_weight_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_weight_unit]') : (isset($item['DeliveryOrderDetail']['sku_weight_unit']) ? $item['DeliveryOrderDetail']['sku_weight_unit'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_length]" class="sku-length" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_length]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_length]') : (isset($item['DeliveryOrderDetail']['sku_length']) ? $item['DeliveryOrderDetail']['sku_length'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_length_unit]" class="sku-length-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_length_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_length_unit]') : (isset($item['DeliveryOrderDetail']['sku_length_unit']) ? $item['DeliveryOrderDetail']['sku_length_unit'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_width]" class="sku-width" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_width]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_width]') : (isset($item['DeliveryOrderDetail']['sku_width']) ? $item['DeliveryOrderDetail']['sku_width'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_width_unit]" class="sku-width-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_width_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_width_unit]') : (isset($item['DeliveryOrderDetail']['sku_width_unit']) ? $item['DeliveryOrderDetail']['sku_width_unit'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_height]" class="sku-height" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_height]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_height]') : (isset($item['DeliveryOrderDetail']['sku_height']) ? $item['DeliveryOrderDetail']['sku_height'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_height_unit]" class="sku-height-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_height_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_height_unit]') : (isset($item['DeliveryOrderDetail']['sku_height_unit']) ? $item['DeliveryOrderDetail']['sku_height_unit'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_volume]" class="sku-volume" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_volume]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_volume]') : (isset($item['DeliveryOrderDetail']['sku_volume']) ? $item['DeliveryOrderDetail']['sku_volume'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_volume_unit]" class="sku-volume-unit" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_volume_unit]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_volume_unit]') : (isset($item['DeliveryOrderDetail']['sku_volume_unit']) ? $item['DeliveryOrderDetail']['sku_volume_unit'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_kode]" class="form-control sku-kode" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_kode]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_kode]') : (isset($item['DeliveryOrderDetail']['sku_kode']) ? $item['DeliveryOrderDetail']['sku_kode'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_nama_produk]" class="form-control sku-nama-produk" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_nama_produk]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_nama_produk]') : (isset($item['DeliveryOrderDetail']['sku_nama_produk']) ? $item['DeliveryOrderDetail']['sku_nama_produk'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_kemasan]" class="form-control sku-kemasan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_kemasan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_kemasan]') : (isset($item['DeliveryOrderDetail']['sku_kemasan']) ? $item['DeliveryOrderDetail']['sku_kemasan'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_satuan]" class="form-control sku-satuan" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_satuan]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_satuan]') : (isset($item['DeliveryOrderDetail']['sku_satuan']) ? $item['DeliveryOrderDetail']['sku_satuan'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_request_expdate]" class="form-control sku-request-expdate" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_request_expdate]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_request_expdate]') : (isset($item['DeliveryOrderDetail']['sku_request_expdate']) ? $item['DeliveryOrderDetail']['sku_request_expdate'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_qty]" class="form-control sku-qty" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_qty]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_qty]') : (isset($item['DeliveryOrderDetail']['sku_qty']) ? $item['DeliveryOrderDetail']['sku_qty'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][sku_qty_available]" class="form-control sku-qty-available" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][sku_qty_available]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][sku_qty_available]') : (isset($item['DeliveryOrderDetail']['sku_qty_available']) ? $item['DeliveryOrderDetail']['sku_qty_available'] : "") ?>" />
									<input type="hidden" name="item[<?= $i ?>][DeliveryOrderDetail][index_do]" class="form-control index-do" value="<?= set_value('item[' . $i . '][DeliveryOrderDetail][index_do]') != "" ? set_value('item[' . $i . '][DeliveryOrderDetail][index_do]') : (isset($item['DeliveryOrderDetail']['index_do']) ? $item['DeliveryOrderDetail']['index_do'] : "") ?>" />
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="panel-armada">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-PILIHARMADA">Pilih Armada</h4>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('kendaraan_id') ?></label>
							<div><?= $armada->kendaraan_nopol ?></div>
						</div>
						<div class="col-md-6">
							<label><?= (new M_DeliveryOrderBatch)->getAttributeLabels('karyawan_id') ?></label>
							<div><?= $employee[0]->karyawan_nama ?></div>
						</div>
					</div>
					<br />
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
								<td><?= $deliveryOrderBatch['kendaraan_berat_gr_max'] ?></td>
								<td><?= $deliveryOrderBatch['kendaraan_volume_cm3_max'] ?></td>
							</tr>
							<tr>
								<td name="CAPTION-KAPASITASTERPAKAI">Kapasitas Terpakai</td>
								<td><?= $deliveryOrderBatch['kendaraan_berat_gr_terpakai'] ?></td>
								<td><?= $deliveryOrderBatch['kendaraan_volume_cm3_terpakai'] ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-view-dodraft" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title"><label name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas
						Pengiriman</label></h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th name="CAPTION-PRIORITAS">Prioritas</th>
							<th name="CAPTION-TANGGALDO">Tanggal DO</th>
							<th><label name="CAPTION-NODODRAFT">No. DO Draft</label>
								<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No.
									DO</label>
							</th>
							<th name="CAPTION-NAMA">Nama</th>
							<th><label name="CAPTION-ALAMAT">Alamat</label>
								<hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-TELP">Telp</label>
							</th>
							<th name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</th>
							<th name="CAPTION-TIPELAYANAN">Tipe Layanan</th>
							<th name="CAPTION-TIPE">Tipe</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>

<div class="table-summary-sku-template-container" style="display: none">
	<table id="table-summary-sku-template">
		<tr id="rowsku-{skuNumber}" class="row-sku">
			<td><span class="sku-kode-label"></span></td>
			<td><span class="sku-nama-produk-label"></span></td>
			<td><span class="sku-kemasan-label"></span></td>
			<td><span class="sku-satuan-label"></span></td>
			<td><span class="sku-request-expdate-label"></span></td>
			<td><span class="sku-qty-label"></span></td>
			<td><span class="sku-qty-available-label"></span></td>
			<td><button class="btn btn-success btn-small btn-view-do" title="Lihat Detil DO" data-toggle="modal" data-target="#modal-view-dodraft"><i class="fa fa-eye"></i></button></td>
		</tr>
	</table>
</div>