<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PERSETUJUANPEMBONGKARANKEMASAN">Persetujuan Pembongkaran Kemasan</h3>
            </div>
            <div style="float: right">
            </div>
        </div>
        <?php foreach ($KonversiHeader as $header) : ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-KODE">Kode</label>
                                    <input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_kode" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_kode]" autocomplete="off" value="<?= $header['tr_konversi_sku_kode'] ?>">
                                    <input readonly="readonly" type="hidden" id="persetujuanpembongkaran-tr_konversi_sku_id" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_id]" autocomplete="off" value="<?= $header['tr_konversi_sku_id'] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_tanggal">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_tanggal" name="CAPTION-TANGGAL">Tanggal</label>
                                    <input readonly="readonly" disabled type="text" id="persetujuanpembongkaran-tr_konversi_sku_tanggal" class="form-control datepicker" name="persetujuanpembongkaran[tr_konversi_sku_tanggal]" autocomplete="off" value="<?= $header['tr_konversi_sku_tanggal'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-TIPE">Tipe</label>
                                    <select disabled name="persetujuanpembongkaran[tipe_konversi_id]" class="form-control" id="persetujuanpembongkaran-tipe_konversi_id">
                                        <option value="">** <label name="CAPTION-TIPE">Tipe</label> **</option>
                                        <?php foreach ($TipeKonversi as $type) : ?>
                                            <option value="<?= $type['tipe_konversi_id'] ?>" <?= $header['tipe_konversi_id'] == $type['tipe_konversi_id'] ? 'selected' : '' ?>><?= $type['tipe_konversi_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-client_wms_id">
                                    <label for="persetujuanpembongkaran-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                    <select disabled id="persetujuanpembongkaran-client_wms_id" class="form-control select2" name="persetujuanpembongkaran-[client_wms_id]">
                                        <option value="">** <span name="CAPTION-PERUSAHAAN">Perusahaan</span> **</option>
                                        <?php foreach ($Perusahaan as $row) : ?>
                                            <option value="<?= $row['client_wms_id'] ?>" <?= $header['client_wms_id'] == $row['client_wms_id'] ? 'selected' : '' ?>><?= $row['client_wms_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_keterangan">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                    <textarea readonly="readonly" rows="5" type="text" id="persetujuanpembongkaran-tr_konversi_sku_keterangan" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_keterangan]" autocomplete="off"><?= $header['tr_konversi_sku_keterangan'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-depo_detail_id">
                                    <label for="persetujuanpembongkaran-depo_detail_id" class="control-label" name="CAPTION-GUDANG">Gudang</label>
                                    <select disabled id="persetujuanpembongkaran-depo_detail_id" class="form-control select2" name="persetujuanpembongkaran[depo_detail_id]">
                                        <option value="">** <span name="CAPTION-GUDANG">Gudang</span> **</option>
                                        <?php foreach ($Gudang as $row) : ?>
                                            <option value="<?= $row['depo_detail_id'] ?>" <?= $header['depo_detail_id'] == $row['depo_detail_id'] ? 'selected' : '' ?>><?= $row['depo_detail_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_status">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_status">Status</label>
                                    <input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_status" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_status]" autocomplete="off" value="<?= $header['tr_konversi_sku_status'] ?>">
                                </div>
                                <div class="form-group field-persetujuanpembongkaran-konversi_is_need_approval">
                                    <input disabled type="checkbox" id="persetujuanpembongkaran-konversi_is_need_approval" name="persetujuanpembongkaran[konversi_is_need_approval]" autocomplete="off" value="1" <?= $header['tr_konversi_sku_status'] == 'Draft' ? '' : 'checked' ?>> <span name="CAPTION-PENGAJUAN">Pengajuan Approval</span>
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
                        <h4 class="pull-left" name="CAPTION-SKUYGDIPILIH">SKU Yang Dipilih</h4>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-striped" id="table-sku-pembongkaran">
                        <thead>
                            <tr class="bg-primary">
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYPLAN">Qty</th>
                                <!-- <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ACTION">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($KonversiDetail as $i => $detail) : ?>
                                <tr id="row-<?= $i ?>">
                                    <td style="display: none">
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_stock_id" value="<?= $detail['sku_stock_id'] ?>" class="sku-id" />
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="sku-kode-label"><?= $detail['sku_kode'] ?></span>
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_kode" class="form-control sku-kode" value="<?= $detail['sku_kode'] ?>" />
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <span class="sku-nama-produk-label"><?= $detail['sku_nama_produk'] ?></span>
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_nama_produk" class="form-control sku-nama-produk" value="<?= $detail['sku_nama_produk'] ?>" />
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['brand'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_stock_expired_date'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_kemasan'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_satuan'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;width:15%;">
                                        <input readonly type="number" id="item-<?= $i ?>-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" class="form-control" value="<?= $detail['tr_konversi_sku_detail_qty_plan'] ?>" />
                                    </td>
                                    <!-- <td style="text-align: center; vertical-align: middle;">
                                        <button class="btn btn-primary btn-small" onclick="GetSKUKonversi('<?= $detail['sku_stock_id'] ?>',<?= $i ?>,'<?= $detail['sku_nama_produk'] ?>','<?= $detail['sku_kemasan'] ?>','<?= $detail['sku_satuan'] ?>','<?= $detail['tr_konversi_sku_detail_id'] ?>')"><i class="fa fa-angle-down"></i></button>
                                    </td> -->
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div style="float: right">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>Loading...</span>
                    <a href="<?= base_url('WMS/PersetujuanPembongkaranBarang/PersetujuanPembongkaranMenu') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-konversi" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-KONVERSI">Konversi</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12">
                                <label name="CAPTION-SKU">SKU</label>
                                <input type="text" id="filter_konversi_sku" class="form-control" readonly />
                                <input type="hidden" id="filter_konversi_sku_stock_id" class="form-control" readonly />
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label name="CAPTION-QTYPLAN">Qty Plan</label>
                                <input type="text" id="filter_konversi_qty_plan" class="form-control" readonly />
                            </div>
                            <div class="col-xs-6">
                                <label name="CAPTION-QTYSISA">Qty Sisa</label>
                                <input type="text" id="filter_konversi_qty_sisa" class="form-control" readonly />
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label name="CAPTION-SATUAN">Satuan</label>
                                <input type="text" id="filter_konversi_satuan" class="form-control" readonly />
                            </div>
                            <div class="col-xs-6">
                                <label name="CAPTION-KEMASAN">Kemasan</label>
                                <input type="text" id="filter_konversi_kemasan" class="form-control" readonly />
                            </div>
                        </div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-konversi">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NILAI">Nilai</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-HASIL">Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingkonversi" style="display:none;"><i class="fa fa-spinner fa-spin"></i><label name="CAPTION-LOADING">Loading</label>...</span>
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>