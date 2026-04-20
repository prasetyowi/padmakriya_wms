<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-VIEWDRAFTSURATTUGASPENGIRIMAN">View Draft Surat Tugas Pengiriman</h3>
			</div>
			<div style="float: right">
				<a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
				<?php if ($deliveryOrderDraft['delivery_order_draft_status'] == $this->M_DeliveryOrderDraft::STATUS_DRAFT) : ?>
					<a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/approve/' . $deliveryOrderDraft['delivery_order_draft_id']) ?>" class="btn-approve btn btn-danger"><i class="fa fa-thumbs-up"></i> <label name="CAPTION-APPROVE">Approve</label></a>
				<?php endif; ?>
				<?php if ($deliveryOrderDraft['delivery_order_draft_status'] == $this->M_DeliveryOrderDraft::STATUS_DRAFT) : ?>
					<a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/update/' . $deliveryOrderDraft['delivery_order_draft_id']) ?>" class="btn btn-warning"><i class="fa fa-edit"></i> <label name="CAPTION-UPDATE">Update</label></a>
				<?php endif; ?>
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
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_kode">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_kode"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_kode') ?></label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_kode'] ?></div>
							</div>
						</div>
						<div class="col-xs-3">
							<label class="control-label" for="deliveryorderdraft-delivery_order_draft_status"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('tipe_delivery_order_id') ?></label>
							<div><?= $tipeDeliveryOrder->tipe_delivery_order_alias ?></div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_status">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_status"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_status') ?></label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_status'] ?></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_buat_do">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_buat_do"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tgl_buat_do') ?></label>
								<div>
									<?= date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_buat_do'])) ?>
								</div>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_expired_do">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_expired_do"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tgl_expired_do') ?></label>
								<div>
									<?= date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_expired_do'])) ?>
								</div>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_surat_jalan">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tgl_surat_jalan') ?></label>
								<div>
									<?= date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_surat_jalan'])) ?>
								</div>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tgl_rencana_kirim') ?></label>
								<div>
									<?= date('d-m-Y', strtotime($deliveryOrderDraft['delivery_order_draft_tgl_rencana_kirim'])) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group field-deliveryorderdraft-client_wms_id">
								<label for="" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
								<div><?= $clientWms->client_wms_nama ?></div>
							</div>
							<div class="form-group field-deliveryorderdraft-client_wms_alamat">
								<label class="control-label" for="deliveryorderdraft-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
								<div><?= $clientWms->client_wms_alamat ?></div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_keterangan">
								<label class="control-label" for="deliveryorderdraft-delivery_order_draft_keterangan"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_keterangan') ?></label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_keterangan'] ?></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tipe_pembayaran">
								<label for="deliveryorderdraft-delivery_order_draft_tipe_pembayaran" class="control-label"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tipe_pembayaran') ?></label>
								<div>
									<?= $this->M_DeliveryOrderDraft->getPaymentTypeLabels($deliveryOrderDraft['delivery_order_draft_tipe_pembayaran']) ?>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group field-deliveryorderdraft-delivery_order_draft_tipe_layanan">
								<label for="deliveryorderdraft-delivery_order_draft_tipe_layanan" class="control-label"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_draft_tipe_layanan') ?></label>
								<div>
									<?= $this->M_DeliveryOrderDraft->getServiceTypeLabels($deliveryOrderDraft['delivery_order_draft_tipe_layanan']) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div <?= empty($deliveryOrderDraft['client_pt_id']) ? "style='display: none'" : "" ?> class="row" id="panel-customer">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
						<div class="clearfix"></div>
					</div>
					<div class="customer-info">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-NAMA">Nama:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_kirim_nama'] ?></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-ALAMAT">Alamat:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_kirim_alamat'] ?></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-AREA">Area:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_kirim_area'] ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div <?= empty($deliveryOrderDraft['pabrik_id']) ? "style='display: none'" : "" ?> class="row" id="panel-factory">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
						<div class="clearfix"></div>
					</div>
					<div class="factory-info">
						<div class="row">
							<div class="col-xs-4">
								<label name="CAPTION-NAMA">Nama:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_ambil_nama'] ?></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-ALAMAT">Alamat:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_ambil_alamat'] ?></div>
							</div>
							<div class="col-xs-4">
								<label name="CAPTION-AREA">Area:</label>
								<div><?= $deliveryOrderDraft['delivery_order_draft_ambil_area'] ?></div>
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
						<div class="clearfix"></div>
					</div>
					<table class="table table-bordered table-striped" id="table-sku-delivery-only">
						<thead>
							<tr>
								<th><?= (new M_SKU)->getAttributeLabels('sku_kode') ?></th>
								<th><?= (new M_SKU)->getAttributeLabels('sku_kode') ?> Pabrik</th>
								<th><?= (new M_SKU)->getAttributeLabels('sku_nama_produk') ?></th>
								<th><?= (new M_SKU)->getAttributeLabels('sku_kemasan') ?></th>
								<th><?= (new M_SKU)->getAttributeLabels('sku_satuan') ?></th>
								<th name="CAPTION-REQEXPDATE">Req Exp Date?</th>
								<th name="CAPTION-KETERANGAN">Keterangan</th>
								<th name="CAPTION-QTYREQ">Qty Req</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($items as $i => $item) : ?>
								<tr id="row-<?= $i ?>" class="row-item">
									<td>
										<span class="sku-kode-label"><?= $item['DeliveryOrderDetailDraft']['sku_kode'] ?></span>
									</td>
									<td></td>
									<td>
										<span class="sku-nama-produk-label"><?= $item['DeliveryOrderDetailDraft']['sku_nama_produk'] ?></span>
									</td>
									<td>
										<span class="sku-kemasan-label"><?= $item['DeliveryOrderDetailDraft']['sku_kemasan'] ?></span>
									</td>
									<td>
										<span class="sku-satuan-label"><?= $item['DeliveryOrderDetailDraft']['sku_satuan'] ?></span>
									</td>
									<td>
										<?= $item['DeliveryOrderDetailDraft']['sku_request_expdate'] === '0' ? 'Tidak' : 'Ya' ?>
									</td>
									<td><?= $item['DeliveryOrderDetailDraft']['sku_keterangan'] ?></td>
									<td><?= $item['DeliveryOrderDetailDraft']['sku_qty'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>