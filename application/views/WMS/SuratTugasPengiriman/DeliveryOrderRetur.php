<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Surat Tugas Pengiriman</h3>
            </div>
            <div style="float: right">
                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
                <a href="<?= base_url() ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>" class="btn btn-info"><i class="fa fa-reply"></i> Kembali</a>
                <button type="button" class="btn-submit btn btn-success" id="btnsavedoretur"><i class="fa fa-save"></i> Save</button>
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
                            <div class="form-group">
                                <label class="control-label" for="delivery_order_kode" name="CAPTION-NODO">No DO</label>
                                <input readonly="readonly" type="text" id="delivery_order_kode" class="form-control input-sm" name="delivery_order_kode" autocomplete="off" value="" readonly>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label class="control-label" for="tipe_delivery_order_id" name="CAPTION-TIPE">Tipe</label>
                                <select name="tipe_delivery_order_id" class="input-sm form-control" id="tipe_delivery_order_id" readonly>
                                    <option value="C5BE83E2-01E8-4E24-B766-26BB4158F2CD">Retur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label class="control-label" for="tipe_delivery_order_id" name="CAPTION-NOFDJR">No FDJR</label>
                                <select name="delivery_order_batch_id" class="input-sm form-control" id="delivery_order_batch_id" readonly>
                                    <option value="<?= $delivery_order_batch_id; ?>"><?= $delivery_order_batch_kode ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group field-delivery_order_tgl_buat_do">
                                <label class="control-label" for="delivery_order_tgl_buat_do" name="CAPTION-TGLDO">Tanggal Entry DO</label>
                                <input type="text" id="delivery_order_tgl_buat_do" class="form-control input-sm datepicker" name="delivery_order_tgl_buat_do" autocomplete="off" value="<?= date('d-m-Y') ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group delivery_order_tgl_expired_do">
                                <label class="control-label" for="delivery_order_tgl_expired_do" name="CAPTION-TGLEXP">Tanggal Expired</label>
                                <input type="text" id="delivery_order_tgl_expired_do" class="form-control input-sm datepicker" name="delivery_order_tgl_expired_do" autocomplete="off" value="<?= date('d-m-Y') ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-delivery_order_tgl_surat_jalan">
                                <label class="control-label" for="delivery_order_tgl_surat_jalan" name="CAPTION-TGLSJ">Tanggal Surat Jalan</label>
                                <input type="text" id="delivery_order_tgl_surat_jalan" class="form-control input-sm datepicker" name="delivery_order_tgl_surat_jalan" autocomplete="off" value="<?= date('d-m-Y') ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-delivery_order_tgl_rencana_kirim">
                                <label class="control-label" for="delivery_order_tgl_rencana_kirim" name="CAPTION-TGLRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input type="text" id="delivery_order_tgl_rencana_kirim" class="form-control input-sm datepicker" name="delivery_order_tgl_rencana_kirim" autocomplete="off" value="<?= date('d-m-Y') ?>">
                            </div>
                        </div>
                        <!--
                            <div class="col-xs-3">
                                <div class="form-group field-delivery_order_status">
                                    <label class="control-label" for="delivery_order_status"><?= (new M_DeliveryOrderDraft)->getAttributeLabels('delivery_order_status') ?></label>
                                    <select id="delivery_order_status" class="input-sm form-control" name="DeliveryOrderDraft[delivery_order_status]">
									<?php foreach ($statuses as $status) : ?>
										<option value="<?= $status->status_progress_nama ?>"><?= $status->status_progress_nama ?></option>
									<?php endforeach; ?>
                                    </select>
                                </div>
								
                            </div>
							-->
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group field-client_wms_id">
                                <label for="client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                <select id="client_wms_id" class="input-sm form-control" name="client_wms_id" onchange="getDataPerusahaan(this.value)"></select>
                            </div>
                            <div class="form-group field-client_wms_alamat">
                                <label class="control-label" for="client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
                                <textarea readonly="readonly" type="text" id="client_wms_alamat" class="form-control input-sm" name="client_wms_alamat" autocomplete="off"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-delivery_order_keterangan">
                                <label class="control-label" for="delivery_order_keterangan" name="CAPTION-KETERANGAN">Catatan</label>
                                <textarea rows="5" type="text" id="delivery_order_keterangan" class="form-control input-sm" name="delivery_order_keterangan" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group field-delivery_order_tipe_pembayaran">
                                <label for="delivery_order_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                <div class="delivery_order_tipe_pembayaran">
                                    <?php foreach ((new M_DeliveryOrderDraft)->getPaymentTypeLabels() as $key => $label) : ?>
                                        <fieldset>
                                            <label>
                                                <input class="radio-payment-type" type="radio" id="delivery_order_tipe_pembayaran" name="delivery_order_tipe_pembayaran" value="<?= $key ?>"> <?= $label ?>
                                            </label>
                                        </fieldset>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-delivery_order_tipe_layanan">
                                <label for="delivery_order_tipe_layanan" class="control-label" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                                <?php foreach ((new M_DeliveryOrderDraft)->getServiceTypeLabels() as $key => $label) : ?>
                                    <fieldset>
                                        <label>
                                            <input class="radio-service-type" type="radio" id="delivery_order_tipe_layanan" name="delivery_order_tipe_layanan" <?= set_radio('delivery_order_tipe_layanan', $key) != "" ? set_radio('delivery_order_tipe_layanan', $key) : (isset($deliveryOrderDraft['delivery_order_tipe_layanan']) && $deliveryOrderDraft['delivery_order_tipe_layanan'] == $key ? "checked" : ""); ?> value="<?= $key ?>"> <?= $label ?>
                                        </label>
                                    </fieldset>
                                <?php endforeach; ?>
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
                        <div class="pull-right"><button data-toggle="modal" data-target="#modal-customer" id="btn-choose-customer" class="btn btn-success" type="button"><i class="fa fa-search"></i> Pilih</button></div>
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="client_pt_id" id="client_pt_id" value="" />
                    <input type="hidden" name="delivery_order_kirim_nama" id="delivery_order_kirim_nama" value="" />
                    <input type="hidden" name="delivery_order_kirim_alamat" id="delivery_order_kirim_alamat" value="" />
                    <input type="hidden" name="delivery_order_kirim_provinsi" id="delivery_order_kirim_provinsi" value="" />
                    <input type="hidden" name="delivery_order_kirim_kota" id="delivery_order_kirim_kota" value="" />
                    <input type="hidden" name="delivery_order_kirim_kecamatan" id="delivery_order_kirim_kecamatan" value="" />
                    <input type="hidden" name="delivery_order_kirim_kelurahan" id="delivery_order_kirim_kelurahan" value="" />
                    <input type="hidden" name="delivery_order_kirim_kodepose" id="delivery_order_kirim_kodepose" value="" />
                    <input type="hidden" name="delivery_order_kirim_area" id="delivery_order_kirim_area" value="" />
                    <input type="hidden" name="delivery_order_kirim_latitude" id="delivery_order_kirim_latitude" value="" />
                    <input type="hidden" name="delivery_order_kirim_longitude" id="delivery_order_kirim_longitude" value="" />
                    <span id="loadingviewcustomer" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
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
                        <h4 class="pull-left">Barang Yang Dikirim</h4>
                        <div class="pull-right"><button data-toggle="modal" data-target="#modal-sku" id="btn-choose-prod-delivery" class="btn btn-success" type="button"><i class="fa fa-search"></i> Pilih</button></div>
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="jml_sku" id="jml_sku" value="0" />
                    <table class="table table-bordered table-striped" id="table-sku-delivery">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKUKODE">Kode SKU Pabrik</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKU">SKU</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-SKUREQEXPDATE">Req Exp Date?</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-KETERANGAN">Keterangan</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-QTYREQ">Qty Req</span></th>
                                <th class="text-center" style="color:white;"><span name="CAPTION-ACTION">Action</span></th>
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
                                <select id="filter-area" name="filter_area" class="form-control input-sm">
                                    <option value="">Semua</option>
                                    <?php foreach ($areas as $area) : ?>
                                        <option value="<?= $area->area_id ?>"><?= $area->area_nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="button" id="btn-search-customer" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="data-table-customer" width="100%" class="table table-bordered">
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
                <button type="button" data-dismiss="modal" class="btn btn-danger">Tutup</button>
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
                                    <button type="button" id="btn-search-sku" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="data-table-sku" width="100%" class="table table-bordered">
                            <thead>
                                <tr class="bg-primary">
                                    <th><input type="checkbox" name="select-sku" id="select-sku" value="1"></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-BRAND">Brand</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-SKUINDUK">SKU Induk</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-SKUKODE">SKU Kode</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-SKUNAMA">Nama Barang</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="btn-choose-sku-multi"><span name="CAPTION-PILIH">Pilih</span></button>
                <button type=" button" data-dismiss="modal" class="btn btn-danger"><span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>