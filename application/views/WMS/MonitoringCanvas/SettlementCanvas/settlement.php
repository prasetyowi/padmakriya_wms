<?php foreach ($SettlementHeader as $i => $header) : ?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-SETTLEMENTCANVAS">Settlement Canvas</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="Settlement">Settlement</label></div>
            <div class=" panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="hidden" class="form-control" name="delivery_order_batch_id"
                                    id="delivery_order_batch_id" value="<?php echo $delivery_order_batch_id ?>"
                                    required>
                                <input type="hidden" class="form-control" name="txt_jumlah" id="txt_jumlah" value="0"
                                    readonly>
                                <input type="text" class="form-control" name="filter_fdjr_no" id="filter_fdjr_no"
                                    value="<?= $header['delivery_order_batch_kode'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-SALES">Sales</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" class="form-control" name="txt_driver_fdjr" id="txt_driver_fdjr"
                                    value="<?= $header['karyawan_nama'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" class="form-control" name="txt_driver_fdjr" id="txt_driver_fdjr"
                                    value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal_kirim'])) ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-AREA">Area</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea id="fdjr_area" class="form-control" disabled><?php foreach ($getPengirimanArea as $data) {
                                                                                                echo $data['area_nama'] . ', ';
                                                                                            } ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="x-content" style="padding-top: 0.5rem;">
            <ul class="nav nav-tabs bar_tabs nav-pills" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link" id="slcBarang" data-toggle="tab" href="#home" role="tab"
                        aria-controls="penerimaanBarang" aria-selected="true" name="CAPTION-PENERIMAANBARANG">Penerimaan
                        Barang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="slcKomparasi" data-toggle="tab" href="#home" role="tab"
                        aria-controls="penerimaanBarang" aria-selected="true"
                        name="CAPTION-KOMPARASIINVOICETUNAIVSPENERIMAAN">Komparasi Invoice Tunai vs Penerimaan
                        Tunai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="slcTunai" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="penerimaanTunai" aria-selected="false" name="CAPTION-PENERIMAANTUNAI">Penerimaan
                        Tunai</a>
                    <!-- </li>
                <li class="nav-item">
                    <a class="nav-link" id="slcBG" data-toggle="tab" href="#contact" role="tab"
                        aria-controls="penerimaanBG" aria-selected="false" name="CAPTION-PENERIMAANBG">Penerimaan BG</a>
                </li> -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div id="penerimaanBarang" role="tabpanel" aria-labelledby="penerimaanBarang-tab">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label name="CAPTION-126006000">Penerimaan Barang</label></div>
                        <div class="panel-body form-horizontal form-label-left">
                            <div class="row">
                                <table id="tablesettlementbarang" width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th name="CAPTION-NO" class="text-center">No</th>
                                            <th name="CAPTION-KODESKU" class="text-center">Kode SKU</th>
                                            <th name="CAPTION-PRINCIPLE" class="text-center">Principle</th>
                                            <th name="CAPTION-BRAND" class="text-center">Brand</th>
                                            <th name="CAPTION-NAMABARANG" class="text-center">Nama Barang</th>
                                            <th name="CAPTION-KEMASAN" class="text-center">Kemasan</th>
                                            <th name="CAPTION-SATUAN" class="text-center">Satuan</th>
                                            <th name="CAPTION-QTYCANVAS" class="text-center">Qty Canvas</th>
                                            <th name="CAPTION-QTYTERJUAL" class="text-center">Qty Terjual</th>
                                            <th name="CAPTION-QTYSISA" class="text-center">Qty Sisa</th>
                                            <th name="CAPTION-QTYBARANGKEMBALI" class="text-center">Qty Barang Kembali
                                            </th>
                                            <th name="CAPTION-STATUS" class="text-center">Status</th>
                                            <!-- <th name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="KomparasiTunai" role="tabpanel" style="display: none;" aria-labelledby="KomparasiTunai-tab">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label name="CAPTION-KOMPARASIINVOICETUNAIVSPENERIMAAN">Komparasi
                                Invoice Tunai vs Penerimaan
                                Tunai</label></div>
                        <div class="panel-body form-horizontal form-label-left">
                            <div class="row">
                                <table id="tablekomparasi" width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th name="CAPTION-NO" class="text-center">No</th>
                                            <th name="CAPTION-GROUP" class="text-center">Group</th>
                                            <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                            <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                            <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                            <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                            <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                            <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                            <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                            <!-- <th name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                            <!-- <div class="row">
                                    <div class="col-lg-6">
                                        <button type="button" id="btn_proses_selisih" class="btn btn-primary">Proses Barang Selisih</button>
                                        <button type="button" id="btn_sproses_titipan" class="btn btn-primary">Proses Barang Titipan</button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                            <label name="CAPTION-LOADING">Loading</label>...</span>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" id="btn_simpan_settlement" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/SettlementPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                                    </div>
                                </div> -->
                        </div>
                    </div>
                </div>
                <div id="penerimaanTunai" role="tabpanel" style="display: none;" aria-labelledby="penerimaanTunai-tab">
                    <div class="panel panel-default">
                        <div class="panel-heading"><label name="CAPTION-PENERIMAANTUNAI">Penerimaan Tunai</label></div>
                        <div class="panel-body form-horizontal form-label-left">
                            <div class="row">
                                <table id="tablesettlementtunai" width="100%" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th name="CAPTION-NO" class="text-center">No</th>
                                            <th name="CAPTION-GROUP" class="text-center">Group</th>
                                            <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                            <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                            <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                            <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                            <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                            <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                            <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                            <!-- <th name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <br><br>
                            <!-- <div class="row">
                                    <div class="col-lg-6">
                                        <button type="button" id="btn_proses_selisih" class="btn btn-primary">Proses Barang Selisih</button>
                                        <button type="button" id="btn_sproses_titipan" class="btn btn-primary">Proses Barang Titipan</button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                            <label name="CAPTION-LOADING">Loading</label>...</span>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" id="btn_simpan_settlement" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/SettlementPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                                    </div>
                                </div> -->
                        </div>
                    </div>
                </div>
                <!-- <div id="penerimaanBG" role="tabpanel" style="display: none;" aria-labelledby="penerimaanBG-tab">
                        <div class="panel panel-default">
                            <div class="panel-heading"><label name="CAPTION-PENERIMAANBG">Penerimaan BG</label></div>
                            <div class="panel-body form-horizontal form-label-left">
                                <div class="row">
                                    <table id="tablesettlementtunai" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th name="CAPTION-NO" class="text-center">No</th>
                                                <th name="CAPTION-GROUP" class="text-center">Group</th>
                                                <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                                <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                                <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                                <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                                <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                                <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                                <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($PenerimaanBG) > 0) {
                                                foreach ($PenerimaanBG as $i => $value) : ?>
                                                    <tr>
                                                        <td class="text-center"><?= $value['idx'] ?></td>
                                                        <td class="text-center"><?= $value['groupurut'] ?></td>
                                                        <td class="text-center"><?= $value['tgl'] ?></td>
                                                        <td class="text-center">
                                                            <button type="button" id="btn_document_no<?= $i ?>" class="btn btn-link" onclick="ViewDetailDO('<?= $value['documentno']; ?>')"><?= $value['documentno']; ?></button>
                                                        </td>
                                                        <td class="text-center"><?= $value['namaoutlet'] ?></td>
                                                        <td class="text-center"><?= $value['alamatoutlet'] ?></td>
                                                        <td class="text-center"><?= $value['keterangan'] ?></td>
                                                        <td class="text-center"><?= round($value['nominal']) ?></td>
                                                        <td class="text-center">
                                                            <input type="hidden" id="status_settlement_tunai_<?= $i ?>" value="<?= $value['nominalkumulatif'] ?>">
                                                            <?= round($value['nominalkumulatif']) ?>
                                                        </td>
                                                    </tr>
                                            <?php endforeach;
                                            } else {
                                                echo '<tr><td colspan="9" class="text-center text-danger">Data kosong</td></tr>';
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br><br>

                            </div>
                        </div>
                    </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>"
                    class="btn btn-primary"
                    style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label
                        name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                    <label name="CAPTION-LOADING">Loading</label>...</span>
            </div>
            <div class="pull-right">
                <button type="button" id="btn_simpan_settlement" class="btn btn-success"
                    style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i
                        class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                <a href="<?php echo base_url() ?>WMS/MonitoringCanvas/SettlementCanvas/SettlementCanvasMenu"
                    class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                <!-- <button type="button" id="btn_back_settlement" class="btn btn-danger">Kembali</button> -->
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="previewdetaildo" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label>Detail DO</label></h4>
            </div>
            <div class="modal-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_kode">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_kode"
                                            name="CAPTION-NODO">DO No</label>
                                        <div id="delivery_order_kode"></div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label class="control-label" for="deliveryorderdraft-delivery_order_status"
                                        name="CAPTION-TIPE">Tipe</label>
                                    <div id="tipe_delivery_order_nama"></div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_status">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_status"
                                            name="CAPTION-STATUS">Status</label>
                                        <div id="delivery_order_status"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_buat_do">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_buat_do"
                                            name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
                                        <div id="delivery_order_tgl_buat_do"></div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_expired_do">
                                        <label class="control-label"
                                            for="deliveryorderdraft-delivery_order_tgl_expired_do"
                                            name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
                                        <div id="delivery_order_tgl_expired_do"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_surat_jalan">
                                        <label class="control-label"
                                            for="deliveryorderdraft-delivery_order_tgl_surat_jalan"
                                            name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
                                        <div id="delivery_order_tgl_surat_jalan"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_rencana_kirim">
                                        <label class="control-label"
                                            for="deliveryorderdraft-delivery_order_tgl_rencana_kirim"
                                            name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                        <div id="delivery_order_tgl_rencana_kirim"></div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-client_wms_id">
                                        <label for="" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                        <div id="client_wms_nama"></div>
                                    </div>
                                    <div class=" form-group field-deliveryorderdraft-client_wms_alamat">
                                        <label class="control-label" for="deliveryorderdraft-client_wms_alamat"
                                            name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
                                        <div id="client_wms_alamat"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_keterangan">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_keterangan"
                                            name="CAPTION-KETERANGAN">Keterangan</label>
                                        <div id="delivery_order_keterangan"></div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tipe_pembayaran">
                                        <label for="deliveryorderdraft-delivery_order_tipe_pembayaran"
                                            class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                        <div id="delivery_order_tipe_pembayaran"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tipe_layanan">
                                        <label for="deliveryorderdraft-delivery_order_tipe_layanan"
                                            class="control-label" name="CAPTION-TIPEPELAYANAN">Tipe Pelayanan</label>
                                        <div id="delivery_order_tipe_layanan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div <?= empty($deliveryOrderDraft['client_pt_id']) ? "style='display: none'" : "" ?> class=" row"
                    id="panel-customer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="customer-info">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTNAME">Nama</label>
                                        <div id="delivery_order_kirim_nama"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-ALAMAT">Alamat</label>
                                        <div id="delivery_order_kirim_alamat"></div>
                                    </div>
                                    <div class=" col-xs-4">
                                        <label name="CAPTION-CUSTAREA">Area</label>
                                        <div id="delivery_order_kirim_area"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="panel-factory">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="factory-info">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTNAME">Nama</label>
                                        <div id="delivery_order_ambil_nama"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-ALAMAT">Alamat</label>
                                        <div id="delivery_order_ambil_alamat"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTAREA">Area</label>
                                        <div id="delivery_order_ambil_area"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row" id="panel-sku">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-BARANGYANGDIKIRIM">Barang Yang Dikirim</h4>
                                <div class="clearfix"></div>
                            </div>
                            <table class="table table-bordered table-striped" id="tabledodetail">
                                <thead>
                                    <tr>
                                        <th name="CAPTION-SKUKODE">Kode SKU</th>
                                        <th name="CAPTION-KODESKUPABRIK">Kode SKU Pabrik</th>
                                        <th name="CAPTION-SKU">SKU</th>
                                        <th name="CAPTION-SKUKEMASAN">Kemasan</th>
                                        <th name="CAPTION-SATUAN">Satuan</th>
                                        <th name="CAPTION-REQEXPDATEE">Req Exp Date?</th>
                                        <th name="CAPTION-KETERANGAN">Keterangan</th>
                                        <th name="CAPTION-QTYREQ">Qty Req</th>
                                        <th name="CAPTION-QTYKIRIM">Qty Kirim</th>
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
                <span id="loadingbkb" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-danger" id="btnbackdo" data-dismiss="modal"><label
                        name="CAPTION-KEMBALI">Kembali</label></button>
            </div>
        </div>
    </div>
</div>

<span name="CAPTION-ALERT-SETTLEMENTBARANGTIDAKCOCOK" style="display: none;">SETTLEMENT BARANG TIDAK COCOK</span>
<span name="CAPTION-ALERT-SETTLEMENTTUNAITIDAKCOCOK" style="display: none;">SETTLEMENT TUNAI TIDAK COCOK</span>