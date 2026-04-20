<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PROSESDORETUR">Proses Delivery Order Retur</h3>
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
                            <div class="form-group field-deliveryorder-delivery_order_kode">
                                <label class="control-label" for="deliveryorder-delivery_order_kode" name="CAPTION-NODO">No DO</label>
                                <input readonly="readonly" type="text" id="deliveryorder-delivery_order_kode" class="form-control input-sm" name="deliveryorder[delivery_order_kode]" autocomplete="off" value="">
                                <input type="hidden" id="deliveryorder-delivery_order_batch_id" name="deliveryorder[delivery_order_batch_id]" autocomplete="off" value="<?= $delivery_order_batch_id; ?>">
                                <input type="hidden" id="deliveryorder-delivery_order_reff_id" name="deliveryorder[delivery_order_reff_id]" autocomplete="off" value="<?= $delivery_order_id; ?>">
                                <input type="hidden" id="deliveryorder-delivery_order_reff_no" name="deliveryorder[delivery_order_reff_no]" autocomplete="off" value="<?= $delivery_order_kode; ?>">
                                <input type="hidden" id="cek_customer" value="1">
                                <input type="hidden" id="cek_qty" value="0">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_kode">
                                <label class="control-label" for="deliveryorder-delivery_order_kode" name="CAPTION-TIPE">Tipe</label>
                                <select name="deliveryorder[tipe_delivery_order_id]" class="input-sm form-control" id="deliveryorder-tipe_delivery_order_id" disabled>
                                    <option value="C5BE83E2-01E8-4E24-B766-26BB4158F2CD">Retur</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_status">
                                <label class="control-label" for="deliveryorder-delivery_order_status" name="CAPTION-STATUS">Status</label>
                                <input readonly="readonly" type="text" id="deliveryorder-delivery_order_status" class="form-control input-sm" name="deliveryorder[delivery_order_status]" autocomplete="off" value="retur">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_tgl_buat_do">
                                <label class="control-label" for="deliveryorder-delivery_order_tgl_buat_do" name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
                                <input disabled type="text" id="deliveryorder-delivery_order_tgl_buat_do" class="form-control input-sm datepicker" name="deliveryorder[delivery_order_tgl_buat_do]" autocomplete="off" value="<?= set_value('deliveryorder[delivery_order_tgl_buat_do]') != "" ? set_value('deliveryorder[delivery_order_tgl_buat_do]') : (isset($deliveryorder['delivery_order_tgl_buat_do']) ? date('d-m-Y', strtotime($deliveryorder['delivery_order_tgl_buat_do'])) : date('d-m-Y')) ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_tgl_expired_do">
                                <label class="control-label" for="deliveryorder-delivery_order_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
                                <input disabled type="text" id="deliveryorder-delivery_order_tgl_expired_do" class="form-control input-sm datepicker" name="deliveryorder[delivery_order_tgl_expired_do]" autocomplete="off" value="<?= set_value('deliveryorder[delivery_order_tgl_expired_do]') != "" ? set_value('deliveryorder[delivery_order_tgl_expired_do]') : (isset($deliveryorder['delivery_order_tgl_expired_do']) ? date('d-m-Y', strtotime($deliveryorder['delivery_order_tgl_expired_do'])) : date('d-m-Y')) ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_tgl_surat_jalan">
                                <label class="control-label" for="deliveryorder-delivery_order_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
                                <input disabled type="text" id="deliveryorder-delivery_order_tgl_surat_jalan" class="form-control input-sm datepicker" name="deliveryorder[delivery_order_tgl_surat_jalan]" autocomplete="off" value="<?= set_value('deliveryorder[delivery_order_tgl_surat_jalan]') != "" ? set_value('deliveryorder[delivery_order_tgl_surat_jalan]') : (isset($deliveryorder['delivery_order_tgl_surat_jalan']) ? date('d-m-Y', strtotime($deliveryorder['delivery_order_tgl_surat_jalan'])) : "") ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group field-deliveryorder-delivery_order_tgl_rencana_kirim">
                                <label class="control-label" for="deliveryorder-delivery_order_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input disabled type="text" id="deliveryorder-delivery_order_tgl_rencana_kirim" class="form-control input-sm datepicker" name="deliveryorder[delivery_order_tgl_rencana_kirim]" autocomplete="off" value="<?= set_value('deliveryorder[delivery_order_tgl_rencana_kirim]') != "" ? set_value('deliveryorder[delivery_order_tgl_rencana_kirim]') : (isset($deliveryorder['delivery_order_tgl_rencana_kirim']) ? date('d-m-Y', strtotime($deliveryorder['delivery_order_tgl_rencana_kirim'])) : "") ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($Perusahaan as $row) : ?>
                            <div class="col-xs-6">
                                <div class="form-group field-deliveryorder-client_wms_id">
                                    <label for="deliveryorder-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                    <select id="deliveryorder-client_wms_id" class="input-sm form-control select2" name="deliveryorder[client_wms_id]" disabled>
                                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                                    </select>
                                </div>
                                <div class="form-group field-deliveryorder-client_wms_alamat">
                                    <label class="control-label" for="deliveryorder-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
                                    <textarea readonly="readonly" type="text" id="deliveryorder-client_wms_alamat" class="form-control input-sm" name="deliveryorder[client_wms_alamat]" autocomplete="off"><?= $row['client_wms_alamat'] ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-xs-6">
                            <div class="form-group field-deliveryorder-delivery_order_keterangan">
                                <label class="control-label" for="deliveryorder-delivery_order_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                <textarea rows="5" type="text" id="deliveryorder-delivery_order_keterangan" class="form-control input-sm" name="deliveryorder[delivery_order_keterangan]" autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group field-deliveryorder-delivery_order_tipe_pembayaran">
                                <label for="deliveryorder-delivery_order_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                <fieldset>
                                    <label>
                                        <input class="radio-payment-type" type="radio" name="deliveryorder[delivery_order_tipe_pembayaran]" id="deliveryorder-delivery_order_tipe_pembayaran" value="0" onclick="reset_table_sku()" checked> <label name="CAPTION-TUNAI">Tunai</label>
                                    </label>
                                </fieldset>
                                <fieldset>
                                    <label>
                                        <input class="radio-payment-type" type="radio" name="deliveryorder[delivery_order_tipe_pembayaran]" id="deliveryorder-delivery_order_tipe_pembayaran" value="1" onclick="reset_table_sku()"> <label name="CAPTION-NONTUNAI">Non
                                            Tunai</label>
                                    </label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-deliveryorder-delivery_order_tipe_layanan">
                                <label for="deliveryorder-delivery_order_tipe_layanan" class="control-label" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                                <?php
                                $index_tipe = 0;
                                foreach ($TipePelayanan as $row) :
                                ?>
                                    <fieldset>
                                        <label>
                                            <input class="radio-service-type" type="radio" id="deliveryorder-delivery_order_tipe_layanan" name="deliveryorder[delivery_order_tipe_layanan]" value="<?= $row['tipe_layanan_nama'] ?>" <?= $index_tipe == 0 ? 'Checked' : '' ?>> <?= $row['tipe_layanan_nama'] ?>
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
        <?php foreach ($Customer as $row) : ?>
            <div class="row" id="panel-customer">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
                            <!-- <div class="pull-right"><button data-toggle="modal" data-target="#modal-customer" id="btn-choose-customer" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div> -->
                            <div class="clearfix"></div>
                        </div>
                        <input type="hidden" name="deliveryorder[client_pt_id]" id="deliveryorder-client_pt_id" value="<?= $row['client_pt_id'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_nama]" id="deliveryorder-delivery_order_kirim_nama" value="<?= $row['client_pt_nama'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_alamat]" id="deliveryorder-delivery_order_kirim_alamat" value="<?= $row['client_pt_alamat'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_provinsi]" id="deliveryorder-delivery_order_kirim_provinsi" value="<?= $row['client_pt_propinsi'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_kota]" id="deliveryorder-delivery_order_kirim_kota" value="<?= $row['client_pt_kota'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_kecamatan]" id="deliveryorder-delivery_order_kirim_kecamatan" value="<?= $row['client_pt_kecamatan'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_kelurahan]" id="deliveryorder-delivery_order_kirim_kelurahan" value="<?= $row['client_pt_kelurahan'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_kodepos]" id="deliveryorder-delivery_order_kirim_kodepos" value="<?= $row['client_pt_kodepos'] ?>" />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_area]" id="deliveryorder-delivery_order_kirim_area" value="" <?= $row['area_nama'] ?> />
                        <input type="hidden" name="deliveryorder[delivery_order_kirim_telepon]" id="deliveryorder-delivery_order_kirim_telepon" value="<?= $row['client_pt_telepon'] ?>" />
                        <div style="text-align: center; display: none;" class="spinner"><img style="width: 30px;" src="<?= base_url() ?>/assets/images/spinner.gif" alt=""></div>
                        <div class="customer-info">
                            <div class="row">
                                <div class="col-xs-4">
                                    <label name="CAPTION-NAMA">Nama:</label>
                                    <div class="customer-name"><?= $row['client_pt_nama'] ?></div>
                                </div>
                                <div class="col-xs-4">
                                    <label name="CAPTION-ALAMAT">Alamat:</label>
                                    <div class="customer-address"><?= $row['client_pt_alamat'] ?></div>
                                </div>
                                <div class="col-xs-4">
                                    <label name="CAPTION-AREA">Area:</label>
                                    <div class="customer-area"><?= $row['area_nama'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row" id="panel-factory" style="display:none;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
                        <div class="pull-right"><button data-toggle="modal" data-target="#modal-factory" id="btn-choose-factory" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
                        <div class="clearfix"></div>
                    </div>
                    <input type="hidden" name="deliveryorder[principle_id]" id="deliveryorder-principle_id" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_nama]" id="deliveryorder-delivery_order_ambil_nama" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_alamat]" id="deliveryorder-delivery_order_ambil_alamat" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_provinsi]" id="deliveryorder-delivery_order_ambil_provinsi" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_kota]" id="deliveryorder-delivery_order_ambil_kota" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_kecamatan]" id="deliveryorder-delivery_order_ambil_kecamatan" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_kelurahan]" id="deliveryorder-delivery_order_ambil_kelurahan" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_kodepos]" id="deliveryorder-delivery_order_ambil_kodepos" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_telepon]" id="deliveryorder-delivery_order_ambil_telepon" value="" />
                    <input type="hidden" name="deliveryorder[delivery_order_ambil_area]" id="deliveryorder-delivery_order_ambil_area" value="" />
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
                                <th class="text-center" style="color:white;" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</th>
                                <th class="text-center" style="color:white;" name="CAPTION-SKUREQEXPDATE">Exp Date</th>
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
                    <a href="<?= base_url('WMS/Distribusi/deliveryorder/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
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