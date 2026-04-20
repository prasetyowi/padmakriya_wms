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
                                    <input readonly="readonly" type="hidden" id="persetujuanpembongkaran-tr_konversi_sku_tgl_update" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_tgl_update]" autocomplete="off" value="<?= $header['tr_konversi_sku_tgl_update'] ?>">
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
                                            <option value="<?= $type['tipe_konversi_id'] ?>" <?= $header['tipe_konversi_id'] == $type['tipe_konversi_id'] ? 'selected' : '' ?>>
                                                <?= $type['tipe_konversi_nama'] ?></option>
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
                                            <option value="<?= $row['client_wms_id'] ?>" <?= $header['client_wms_id'] == $row['client_wms_id'] ? 'selected' : '' ?>>
                                                <?= $row['client_wms_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_keterangan">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                    <textarea rows="5" type="text" id="persetujuanpembongkaran-tr_konversi_sku_keterangan" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_keterangan]" autocomplete="off"><?= $header['tr_konversi_sku_keterangan'] ?></textarea>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-persetujuanpembongkaran-depo_detail_id">
                                    <label for="persetujuanpembongkaran-depo_detail_id" class="control-label" name="CAPTION-GUDANG">Gudang</label>
                                    <select disabled id="persetujuanpembongkaran-depo_detail_id" class="form-control select2" name="persetujuanpembongkaran[depo_detail_id]">
                                        <option value="">** <span name="CAPTION-GUDANG">Gudang</span> **</option>
                                        <?php foreach ($Gudang as $row) : ?>
                                            <option value="<?= $row['depo_detail_id'] ?>" <?= $header['depo_detail_id'] == $row['depo_detail_id'] ? 'selected' : '' ?>>
                                                <?= $row['depo_detail_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_status">
                                    <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_status">Status</label>
                                    <input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_status" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_status]" autocomplete="off" value="<?= $header['tr_konversi_sku_status'] ?>">
                                </div>
                                <div class="form-group field-persetujuanpembongkaran-konversi_is_need_approval">
                                    <input type="checkbox" id="persetujuanpembongkaran-konversi_is_need_approval" name="persetujuanpembongkaran[konversi_is_need_approval]" autocomplete="off" value="1" <?= $header['tr_konversi_sku_status'] == 'Draft' ? '' : 'checked' ?>>
                                    <span name="CAPTION-PENGAJUAN">Pengajuan Approval</span>
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
                        <div class="pull-right"><button id="btn-choose-prod-delivery" class="btn btn-success" type="button"><i class="fa fa-search"></i> <label name="CAPTION-PILIH">Pilih</label></button></div>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-striped" id="table-sku-pembongkaran">
                        <thead>
                            <tr class="bg-primary">
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">
                                    SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYPLAN">Qty</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($KonversiDetail as $i => $detail) : ?>
                                <tr id="row-<?= $i ?>">
                                    <td style="display: none">
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_id" value="<?= $detail['sku_id'] ?>" />
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_stock_expired_date" value="<?= $detail['sku_stock_expired_date'] ?>" />
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_stock_id" value="<?= $detail['sku_stock_id'] ?>" />
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-principle" value="<?= $detail['principle'] ?>" />
                                        <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_konversi_hasil" value="<?= $detail['sku_konversi_hasil'] ?>" />
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
                                    <td style="text-align: center; vertical-align: middle;">
                                        <?= $detail['sku_stock_expired_date'] ?></td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_kemasan'] ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_satuan'] ?>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;width:15%;">
                                        <input type="number" id="item-<?= $i ?>-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" class="form-control" value="<?= $detail['tr_konversi_sku_detail_qty_plan'] ?>" />
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <!-- <button class="btn btn-primary btn-small" onclick="GetSKUKonversi('<?= $detail['sku_id'] ?>','<?= $detail['sku_stock_expired_date'] ?>',<?= $i ?>,'<?= $detail['sku_nama_produk'] ?>','<?= $detail['sku_kemasan'] ?>','<?= $detail['sku_satuan'] ?>')"><i class="fa fa-angle-down"></i></button> -->
                                        <button class="btn btn-danger btn-small" onclick="DeleteSKU(this,<?= $i ?>)"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div style="float: right">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>Loading...</span>
                    <a href="<?= base_url('WMS/PersetujuanPembongkaranBarang/PersetujuanPembongkaranMenu') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                    <button class="btn-submit btn btn-success" id="btnupdatekonversi"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-CARISKU">Cari SKU</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-PRINCIPLE">Principle</label>
                                <select id="filter-principle" class="form-control select2" name="filter_principle"></select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SKUKODE">SKU Kode</label>
                                <input type="text" id="filter-sku-kode" name="filter_sku_kode" class="form-control" />
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SKU">SKU</label>
                                <input type="text" id="filter-sku-nama-produk" name="filter_sku_nama_produk" class="form-control" />
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SATUAN">Satuan</label>
                                <input type="text" id="filter-sku-satuan" name="filter_sku_satuan" class="form-control" />
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
                                    <th style="text-align: center; vertical-align: middle;color:white;"><input type="checkbox" name="select-sku" id="select-sku" value="1"></th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUINDUK">Sku Induk</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">Sku Kode</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;">Batch No</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUSTOCK">SKU Stock</th>
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
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PILIH">Pilih</th>
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
                <button type="button" class="btn btn-info" id="btn_save_konversi"><label name="CAPTION-SIMPAN">Simpan</label></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>


<span name="CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH" style="display: none;">Perusahaan Tidak Dipilih</span>
<span name="CAPTION-ALERT-DATABERHASILDISIMPAN" style="display: none;">Data Berhasil Disimpan</span>
<span name="CAPTION-ALERT-DATAGAGALDISIMPAN" style="display: none;">Data Gagal Disimpan</span>
<span name="CAPTION-ALERT-NILAIKONVERSIMELEBIHIQTYPLAN" style="display: none;">Nilai Konversi Melebehi Qty Plan</span>
<span name="CAPTION-ALERT-NILAIBELUMTERKONVERSISEMUA" style="display: none;">Nilai Belum TerKonversi Semua</span>
<span name="CAPTION-ALERT-SKUKONVERSIFAKTOR0" style="display: none;">Nilai SKU Konversi Faktor 0</span>
<span name="CAPTION-ALERT-PILIHTIPEKONVERSI" style="display: none;">Pilih Tipe Konversi</span>
<span name="CAPTION-ALERT-PILIHSKUKONVERSI" style="display: none;">Pilih SKU Konversi</span>
<span name="CAPTION-ALERT-SKUQTYTIDAKBOLEH0" style="display: none;">SKU Qty Tidak Boleh 0</span>
<span name="CAPTION-ALERT-CHECKQTYPLANSKUDENGANFORMKONVERSI" style="display: none;">Check Qty Plan SKU Dengan Form
    Konversi</span>