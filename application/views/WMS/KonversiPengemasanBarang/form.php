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
#select_kamera {
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
#select_kamera .checkbox-tools:checked+label,
#select_kamera .checkbox-tools:not(:checked)+label {
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
#select_kamera .checkbox-tools:not(:checked)+label {
    background-color: var(--dark-light);
    color: var(--white);
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

#select_kamera .checkbox-tools:checked+label,
#select_kamera .checkbox-tools:checked+label {
    background-color: transparent;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

#select_kamera .checkbox-tools:not(:checked)+label:hover,
#select_kamera .checkbox-tools:not(:checked)+label:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

#select_kamera .checkbox-tools:checked+label::before,
#select_kamera .checkbox-tools:not(:checked)+label::before,
#select_kamera .checkbox-tools:checked+label::before,
#select_kamera .checkbox-tools:not(:checked)+label::before {
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
#select_kamera .checkbox-tools:checked+label .uil,
#select_kamera .checkbox-tools:not(:checked)+label .uil {
    font-size: 24px;
    line-height: 24px;
    display: block;
    padding-bottom: 10px;
}

@media (max-width: 800px) {

    #select_kamera .checkbox-tools:checked+label,
    #select_kamera .checkbox-tools:not(:checked)+label,
    #select_kamera .checkbox-tools:checked+label,
    #select_kamera .checkbox-tools:not(:checked)+label {
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
                <h3 name="CAPTION-128006000">Pembongkaran</h3>
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
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-tr_konversi_sku_id">
                                <label class="control-label" for="pembongkaran-tr_konversi_sku_id"
                                    name="CAPTION-NOPERSETUJUAN">No Persetujuan</label>
                                <select name="pembongkaran[tr_konversi_sku_id]" class="form-control select2"
                                    id="pembongkaran-tr_konversi_sku_id">
                                    <option value="">** <label name="CAPTION-PILIH">Pilih</label> **</option>
                                    <?php foreach ($KonversiSKU as $row) : ?>
                                    <option value="<?= $row['tr_konversi_sku_id'] ?>">
                                        <?= $row['tr_konversi_sku_kode'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input readonly="readonly" type="hidden" id="pembongkaran-tr_konversi_sku_kode"
                                class="form-control" name="pembongkaran[tr_konversi_sku_kode]" autocomplete="off"
                                value="">
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-tipe_konversi_id">
                                <label class="control-label" for="pembongkaran-tipe_konversi_id"
                                    name="CAPTION-TIPE">Tipe</label>
                                <select disabled name="pembongkaran[tipe_konversi_id]" class="form-control"
                                    id="pembongkaran-tipe_konversi_id"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-tr_konversi_sku_tanggal">
                                <label class="control-label" for="pembongkaran-tr_konversi_sku_tanggal"
                                    name="CAPTION-TANGGAL">Tanggal</label>
                                <input readonly="readonly" disabled type="text"
                                    id="pembongkaran-tr_konversi_sku_tanggal" class="form-control datepicker"
                                    name="pembongkaran[tr_konversi_sku_tanggal]" autocomplete="off"
                                    value="<?= date('d-m-Y') ?>">
                                <input readonly="readonly" disabled type="hidden"
                                    id="pembongkaran-tr_konversi_sku_tgl_update" class="form-control"
                                    name="pembongkaran[tr_konversi_sku_tgl_update]" autocomplete="off" value="">
                            </div>
                            <div class="form-group field-pembongkaran-tr_konversi_sku_status">
                                <label class="control-label" for="pembongkaran-tr_konversi_sku_status">Status</label>
                                <input readonly="readonly" type="text" id="pembongkaran-tr_konversi_sku_status"
                                    class="form-control" name="pembongkaran[tr_konversi_sku_status]" autocomplete="off"
                                    value="Draft">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-tr_konversi_sku_keterangan">
                                <label class="control-label" for="pembongkaran-tr_konversi_sku_keterangan"
                                    name="CAPTION-KETERANGAN">Keterangan</label>
                                <textarea rows="4" type="text" id="pembongkaran-tr_konversi_sku_keterangan"
                                    class="form-control" name="pembongkaran[tr_konversi_sku_keterangan]"
                                    autocomplete="off"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-client_wms_id">
                                <label for="pembongkaran-client_wms_id" class="control-label"
                                    name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                <select readonly="readonly" disabled id="pembongkaran-client_wms_id"
                                    class="form-control select2" name="pembongkaran-[client_wms_id]"></select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group field-pembongkaran-depo_detail_id">
                                <label for="pembongkaran-depo_detail_id" class="control-label"
                                    name="CAPTION-GUDANG">Gudang</label>
                                <select readonly="readonly" disabled id="pembongkaran-depo_detail_id"
                                    class="form-control select2" name="pembongkaran[depo_detail_id]"></select>
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
                        <h4 class="pull-left" name="CAPTION-SKUYGDIPILIH">SKU Yang Dipilih</h4>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-striped" id="table-sku-pembongkaran">
                        <thead>
                            <tr class="bg-primary">
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No
                                </th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SKUKODE">SKU Kode</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">
                                    SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-BRAND">Brand</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-KEMASAN">Kemasan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SATUAN">Satuan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-QTYPLAN">Qty Plan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div style="float: right">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>Loading...</span>
                    <button class="btn-submit btn btn-primary" id="btn_konfirmasi_konversi"><i class="fa fa-check"></i>
                        <label name="CAPTION-KONFIRMASI">Konfirmasi</label></button>
                    <button class="btn-submit btn btn-success" id="btn_save_konversi"><i class="fa fa-save"></i> <label
                            name="CAPTION-SIMPAN">Simpan</label></button>
                    <a href="<?= base_url('WMS/pembongkaranBarang/pembongkaranMenu') ?>" class="btn btn-danger"><i
                            class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-konversi" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-DAFTARLOKASISKU">Daftar Lokasi SKU</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-4">
                                <label name="CAPTION-SKUKODE">SKU Kode</label>
                                <input readonly="readonly" type="text" id="pembongkarandetail2-sku_kode"
                                    class="form-control" value="">
                                <input readonly="readonly" type="hidden" id="pembongkarandetail2-index"
                                    class="form-control" value="">
                                <input readonly="readonly" type="hidden" id="pembongkarandetail2-sku_stock_id"
                                    class="form-control" value="">
                            </div>
                            <div class="col-xs-4">
                                <label name="CAPTION-SKU">SKU</label>
                                <input readonly="readonly" type="text" id="pembongkarandetail2-sku_nama_produk"
                                    class="form-control" value="">
                            </div>
                            <div class="col-xs-4">
                                <label name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</label>
                                <input readonly="readonly" type="text" id="pembongkarandetail2-sku_stock_expired_date"
                                    class="form-control" value="">
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-SATUAN">Satuan</label>
                                <input readonly="readonly" type="text" id="pembongkarandetail2-sku_satuan"
                                    class="form-control" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-KEMASAN">Kemasan</label>
                                <input readonly="readonly" type="text" id="pembongkarandetail2-sku_kemasan"
                                    class="form-control" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-QTY">Qty</label>
                                <input readonly="readonly" type="text"
                                    id="pembongkarandetail2-tr_konversi_sku_detail_qty_plan" class="form-control"
                                    value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-QTYKONVERSI">Qty Konversi</label>
                                <input readonly="readonly" type="text"
                                    id="pembongkarandetail2-tr_konversi_sku_detail_qty_result" class="form-control"
                                    value="">
                                <input readonly="readonly" type="hidden"
                                    id="pembongkarandetail2-tr_konversi_sku_detail_qty_sisa" class="form-control"
                                    value="0">
                            </div>
                        </div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="row" style="margin-top:10px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
                        <div class="col-xl-10 col-lg-10 col-md-10 col-xs-12">
                            <div class="head-switch-global">
                                <div class="switch-holder-global">
                                    <div class="switch-toggle">
                                        <input type="checkbox" id="check_scan" class="check_scan">
                                        <label for="check_scan"></label>
                                    </div>
                                    <div class="switch-label-global">
                                        <button type="button" class="btn btn-info" id="start_scan"><i
                                                class="fas fa-qrcode"></i> <label name="CAPTION-STARTSCAN">Start
                                                Scan</label></button>
                                        <button type="button" class="btn btn-danger" style="display:none"
                                            id="stop_scan"><i class="fas fa-xmark"></i> <label
                                                name="CAPTION-STOPSCAN">Stop Scan</label></button>
                                        <button type="button" class="btn btn-warning" style="display:none"
                                            id="input_manual"><i class="fas fa-keyboard"></i> <label
                                                name="CAPTION-MANUALINPUT">Manual Input</label></button>
                                        <button type="button" class="btn btn-danger" style="display:none"
                                            id="close_input"><i class="fas fa-xmark"></i> <label
                                                name="CAPTION-CLOSEINPUT">Close Input</label></button>
                                    </div>
                                </div>
                            </div>

                            <div id="select_kamera"></div>
                            <div id="preview" style="display: none;"></div>

                            <div id="preview_input_manual" style="display: none;margin-top:10px">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-KODEPALLET">Kode Pallet</label>
                                            <input type="text" class="form-control" id="kode_barcode"
                                                placeholder="PAL/00000001">
                                            <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                                                <table class="table table-striped table-sm table-hover"
                                                    id="table-fixed">
                                                    <tbody id="konten-table"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="margin-top: 23px;">
                                            <button type="button" class="btn btn-success" id="check_kode"><i
                                                    class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check
                                                    Kode</label></button>
                                            <span id="loading_cek_manual" style="display:none;"><i
                                                    class="fa fa-spinner fa-spin"></i> <label
                                                    name="CAPTION-LOADING">Loading</label>...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2 col-xs-12">
                            <div class="from-group">
                                <label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
                                <input type="text" class="form-control" id="txtpreviewscan" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-konversi-detail">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-PILIH">Pilih</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-AREA">Area</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-LOKASIRAK">Lokasi Rak</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-LOKASIBIN">Lokasi Bin</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-NOPALLET">No. Pallet</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-LOCKED">Locked</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-QTY">Qty</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-QTYAMBIL">Qty Ambil</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-ACTION">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingkonversi" style="display:none;"><i class="fa fa-spinner fa-spin"></i><label
                        name="CAPTION-LOADING">Loading</label>...</span>
                <button type="button" class="btn btn-info" id="btn_save_konversi_detail"><label
                        name="CAPTION-SIMPAN">Simpan</label></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label
                        name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-konversi-detail" role="dialog" data-keyboard="false" data-backdrop="static">
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
                                <input type="hidden" id="filter_pallet_id" class="form-control" readonly />
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
                        <table class="table table-striped" id="table-konversi-by-sku">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-PILIH">Pilih</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-NILAI">Nilai</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-HASIL">Hasil</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-REQPALLETTUJUAN">Req Pallet Tujuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-PALLETTUJUAN">Pallet Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingkonversi" style="display:none;"><i class="fa fa-spinner fa-spin"></i><label
                        name="CAPTION-LOADING">Loading</label>...</span>
                <button type="button" class="btn btn-info" id="btn_save_konversi_detail2"><label
                        name="CAPTION-SIMPAN">Simpan</label></button>
                <button type="button" class="btn btn-danger" id="btn_close_konversi_detail"><label
                        name="CAPTION-TUTUP">Tutup</label></button>
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