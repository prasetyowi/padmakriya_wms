<style>
    :root {
        --white: #ffffff;
        --light: #f0eff3;
        --black: #000000;
        --dark-blue: #1f2029;
        --dark-light: #353746;
        --red: #da2c4d;
        --yellow: #f8ab37;
        --grey: #ecedf3;
    }

    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-x: auto;
        overflow-y: auto;
    }

    .error {
        border: 1px solid red;
    }

    .alert-header {
        display: flex;
        flex-direction: row;
    }

    .alert-header .alert-icon {
        margin-right: 10px;
    }

    .span-example .alert-header .alert-icon {
        align-self: center;
    }

    #select_kamera,
    #select_kamera_by_one {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: absolute;
        left: -9999px;
        width: 0;
        height: 0;
        visibility: hidden;
    }

    #select_kamera .checkbox-tools:checked+label,
    #select_kamera .checkbox-tools:not(:checked)+label,
    #select_kamera_by_one .checkbox-tools:checked+label,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label {
        position: relative;
        display: inline-block;
        padding: 20px;
        width: 50%;
        font-size: 14px;
        line-height: 20px;
        letter-spacing: 1px;
        margin: 0 auto;
        margin-left: 5px;
        margin-right: 5px;
        margin-bottom: 10px;
        text-align: center;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        text-transform: uppercase;
        -webkit-transition: all 300ms linear;
        transition: all 300ms linear;
    }

    #select_kamera .checkbox-tools:not(:checked)+label,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label {
        background-color: var(--dark-light);
        color: var(--white);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #select_kamera .checkbox-tools:checked+label,
    #select_kamera_by_one .checkbox-tools:checked+label {
        background-color: transparent;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #select_kamera .checkbox-tools:not(:checked)+label:hover,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #select_kamera .checkbox-tools:checked+label::before,
    #select_kamera .checkbox-tools:not(:checked)+label::before,
    #select_kamera_by_one .checkbox-tools:checked+label::before,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label::before {
        position: absolute;
        content: '';
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 4px;
        background-image: linear-gradient(298deg, var(--red), var(--yellow));
        z-index: -1;
    }

    #select_kamera .checkbox-tools:checked+label .uil,
    #select_kamera .checkbox-tools:not(:checked)+label .uil,
    #select_kamera_by_one .checkbox-tools:checked+label .uil,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label .uil {
        font-size: 24px;
        line-height: 24px;
        display: block;
        padding-bottom: 10px;
    }

    @media (max-width: 800px) {

        #select_kamera .checkbox-tools:checked+label,
        #select_kamera .checkbox-tools:not(:checked)+label,
        #select_kamera_by_one .checkbox-tools:checked+label,
        #select_kamera_by_one .checkbox-tools:not(:checked)+label {
            flex: 100%;
        }
    }

    .head-switch {
        /* max-width: 1000px; */
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .switch-holder {
        display: flex;
        border-radius: 10px;
        justify-content: space-between;
        align-items: center;
    }

    .switch-label {
        width: 120px;
        text-align: end;
    }

    .switch-toggle input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        z-index: -2;
    }

    .switch-toggle input[type="checkbox"]+label {
        position: relative;
        display: inline-block;
        width: 100px;
        height: 40px;
        border-radius: 20px;
        margin: 0;
        cursor: pointer;
        box-shadow: 1px 1px 4px 1px;

    }

    .switch-toggle input[type="checkbox"]+label::before {
        position: absolute;
        content: 'Scan';
        font-size: 13px;
        text-align: center;
        line-height: 25px;
        top: 8px;
        left: 8px;
        width: 45px;
        height: 25px;
        color: #fff;
        border-radius: 20px;
        background-color: #5bc0de;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #5bc0de;
        transition: .3s ease-in-out;
    }

    .switch-toggle input[type="checkbox"]:checked+label::before {
        left: 50%;
        content: 'Input';
        color: #fff;
        background-color: #f0ad4e;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #f0ad4e;
    }

    .head-switch-global {
        /* max-width: 1000px; */
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }

    .switch-label-global {
        width: 150px;
        text-align: end;
    }

    .switch-holder-global {
        display: flex;
        border-radius: 10px;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PENGEMASANBARANG">Pengemasan Barang</h3>
            </div>
            <div style="float: right">

            </div>
        </div>
        <?php foreach ($KonversiHeader as $i => $header) : ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-pembongkaran-tr_konversi_sku_id">
                                    <label class="control-label" for="pembongkaran-tr_konversi_sku_id" name="CAPTION-NOPERSETUJUAN">No Persetujuan</label>
                                    <select name="pembongkaran[tr_konversi_sku_id]" class="form-control" id="pembongkaran-tr_konversi_sku_id" disabled>
                                        <option value="<?= $header['tr_konversi_sku_id'] ?>">
                                            <?= $header['tr_konversi_sku_kode'] ?></option>
                                    </select>
                                    <input readonly="readonly" type="hidden" id="pembongkaran-tr_konversi_sku_kode" class="form-control" name="pembongkaran[tr_konversi_sku_kode]" autocomplete="off" value="<?= $header['tr_konversi_sku_kode'] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-pembongkaran-tipe_konversi_id">
                                    <label class="control-label" for="pembongkaran-tipe_konversi_id" name="CAPTION-TIPE">Tipe</label>
                                    <select disabled name="pembongkaran[tipe_konversi_id]" class="form-control" id="pembongkaran-tipe_konversi_id">
                                        <option value="<?= $header['tipe_konversi_id'] ?>">
                                            <?= $header['tipe_konversi_nama'] ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-pembongkaran-tr_konversi_sku_tanggal">
                                    <label class="control-label" for="pembongkaran-tr_konversi_sku_tanggal" name="CAPTION-TANGGAL">Tanggal</label>
                                    <input readonly="readonly" disabled type="text" id="pembongkaran-tr_konversi_sku_tanggal" class="form-control datepicker" name="pembongkaran[tr_konversi_sku_tanggal]" autocomplete="off" value="<?= $header['tr_konversi_sku_tanggal'] ?>">
                                    <input readonly="readonly" disabled type="hidden" id="pembongkaran-tr_konversi_sku_tgl_update" class="form-control" name="pembongkaran[tr_konversi_sku_tgl_update]" autocomplete="off" value="<?= $header['tr_konversi_sku_tgl_update'] ?>">
                                </div>
                                <div class="form-group field-pembongkaran-tr_konversi_sku_status">
                                    <label class="control-label" for="pembongkaran-tr_konversi_sku_status">Status</label>
                                    <input readonly="readonly" type="text" id="pembongkaran-tr_konversi_sku_status" class="form-control" name="pembongkaran[tr_konversi_sku_status]" autocomplete="off" value="<?= $header['tr_konversi_sku_status'] ?>">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-pembongkaran-client_wms_id">
                                    <label for="pembongkaran-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                    <select readonly="readonly" disabled id="pembongkaran-client_wms_id" class="form-control" name="pembongkaran-[client_wms_id]">
                                        <option value="<?= $header['client_wms_id'] ?>"><?= $header['client_wms_nama'] ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group field-pembongkaran-depo_detail_id">
                                    <label for="pembongkaran-depo_detail_id" class="control-label" name="CAPTION-GUDANG">Gudang</label>
                                    <select readonly="readonly" disabled id="pembongkaran-depo_detail_id" class="form-control" name="pembongkaran[depo_detail_id]">
                                        <option value="<?= $header['depo_detail_id'] ?>"><?= $header['depo_detail_nama'] ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-pembongkaran-tr_konversi_sku_keterangan">
                                    <label class="control-label" for="pembongkaran-tr_konversi_sku_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                    <textarea rows="1" type="text" id="pembongkaran-tr_konversi_sku_keterangan" class="form-control" name="pembongkaran[tr_konversi_sku_keterangan]" autocomplete="off" disabled><?= $header['tr_konversi_sku_keterangan'] ?></textarea>
                                </div>
                            </div>

                            <?php foreach ($PalletTujuan as $pallet) : ?>
                                <div class="col-xs-6">
                                    <div class="form-group field-pembongkaran-tr_konversi_sku_keterangan">
                                        <label class="control-label" for="pembongkaran-tr_konversi_sku_keterangan" name="CAPTION-PALLETTUJUAN">Pallet Tujuan</label>
                                        <input type="text" class="form-control" id="txtpreviewscan" value="<?= $pallet['pallet_kode'] ?>" readonly />
                                    </div>
                                </div>
                            <?php endforeach; ?>
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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-sku-pengemasan">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No
                                    </th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">
                                        SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <!-- <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYPLAN">Qty Plan</th> -->
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYAMBIL">Qty Ambil</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-ACTION">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($KonversiDetail as $i => $detail) : ?>
                                    <tr id="row-<?= $i ?>">
                                        <td style="display: none">
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-tr_konversi_sku_detail_id" value="<?= $detail['tr_konversi_sku_detail_id'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_id" value="<?= $detail['sku_id'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_kode" value="<?= $detail['sku_kode'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_nama_produk" value="<?= $detail['sku_nama_produk'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-brand" value="<?= $detail['brand'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_kemasan" value="<?= $detail['sku_kemasan'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_satuan" value="<?= $detail['sku_satuan'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_stock_expired_date" value="<?= $detail['sku_stock_expired_date'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" value="<?= $detail['tr_konversi_sku_detail_qty_plan'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-tr_konversi_sku_detail2_qty_result" value="<?= $detail['tr_konversi_sku_detail2_qty_result'] ?>" />
                                            <input type="hidden" id="item-<?= $i ?>-persetujuanpembongkarandetail-sku_stock_id" value="<?= $detail['sku_stock_id'] ?>" />
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $i + 1 ?></td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <span class="sku-kode-label"><?= $detail['sku_kode'] ?></span>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <span class="sku-nama-produk-label"><?= $detail['sku_nama_produk'] ?></span>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $detail['brand'] ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_kemasan'] ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_satuan'] ?></td>
                                        <td style="text-align: center; vertical-align: middle;"><?= $detail['sku_stock_expired_date'] ?></td>
                                        <!-- <td style="text-align: center; vertical-align: middle;">
                                            <?= $detail['tr_konversi_sku_detail_qty_plan'] ?>
                                        </td> -->
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $detail['tr_konversi_sku_detail2_qty'] ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <button class="btn btn-primary btn-sm" id="button_modal_pallet_asal" onclick="Get_pallet_by_sku_stock_id('<?= $detail['tr_konversi_sku_detail_id'] ?>','<?= $detail['sku_stock_id'] ?>','<?= $detail['sku_id'] ?>','<?= $detail['sku_kode'] ?>','<?= $detail['sku_nama_produk'] ?>','<?= $detail['sku_kemasan'] ?>','<?= $detail['sku_satuan'] ?>','<?= $detail['tr_konversi_sku_detail_qty_plan'] ?>')"><i class="fa fa-search"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-TUJUANKONVERSIPENGEMASAN">Tujuan Konversi Pengemasan</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table-sku-pengemasan-konversi">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUGROUP">SKU Group</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU"> SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYPLAN">Qty Plan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYKONVERSI">Qty Konversi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($HasilKonversiPengemasan as $key => $value) : ?>
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;"><?= $key + 1 ?></td>
                                        <td style="text-align: left; vertical-align: middle;width:15%;"><?= $value['sku_konversi_group'] ?></td>
                                        <td style="text-align: left; vertical-align: middle;width:15%;"><?= $value['sku_satuan'] ?></td>
                                        <td style="text-align: left; vertical-align: middle;"><?= $value['sku_kode'] ?></td>
                                        <td style="text-align: left; vertical-align: middle;width:15%;"><?= $value['sku_nama_produk'] ?></td>
                                        <td style="text-align: left; vertical-align: middle;"><?= $value['sku_stock_expired_date'] ?></td>
                                        <td style="text-align: right; vertical-align: middle;width:10%;"><?= $value['sku_qty_konversi_plan'] ?></td>
                                        <td style="text-align: right; vertical-align: middle;width:10%;"><?= $value['sku_qty_hasil_konversi'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="float: right">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>Loading...</span>
                    <a href="<?= base_url('WMS/KonversiPengemasanBarang/KonversiPengemasanBarangMenu') ?>" class="btn btn-danger"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pallet-asal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-PALLETASAL">Pallet Asal</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-6">
                                <label name="CAPTION-SKUKODE">SKU Kode</label>
                                <input type="text" id="filter_pallet_asal_sku_kode" class="form-control" readonly />
                                <input type="hidden" id="filter_pallet_asal_tr_konversi_sku_detail_id" class="form-control" readonly />
                                <input type="hidden" id="filter_pallet_asal_sku_id" class="form-control" readonly />
                                <input type="hidden" id="filter_pallet_asal_sku_stock_id" class="form-control" readonly />
                            </div>
                            <div class="col-xs-6">
                                <label name="CAPTION-SKU">SKU</label>
                                <input type="text" id="filter_pallet_asal_sku" class="form-control" readonly />
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label name="CAPTION-SATUAN">Satuan</label>
                                <input type="text" id="filter_pallet_asal_sku_satuan" class="form-control" readonly />
                            </div>
                            <div class="col-xs-6">
                                <label name="CAPTION-KEMASAN">Kemasan</label>
                                <input type="text" id="filter_pallet_asal_sku_kemasan" class="form-control" readonly />
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label name="CAPTION-QTYPLAN">Qty Plan</label>
                                <input type="text" id="filter_pallet_asal_qty_plan" class="form-control" readonly />
                            </div>
                        </div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-pallet-asal">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PILIH">#</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PALLET">Pallet</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYAMBIL">Qty Ambil</th>
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
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btn-tutup-mutasi-stok-detail"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
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
<span name="MESSAGE-200010" style="display: none;">Jumlah barang melebihi sisa, sisa jumlah barang dalam SKU</span>