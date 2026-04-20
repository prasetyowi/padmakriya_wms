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

[type="tools"]:checked,
[type="tools"]:not(:checked) {
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
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3 name="CAPTION-NONAKTIFPALLET">Non Aktif Pallet</h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-xs-12">
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
                                            <input type="text" class="form-control" autocomplete="off" id="kode_barcode"
                                                placeholder="masukkan kode setelah tanda '/' pertama">
                                            <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                                                <table class="table table-striped table-sm table-hover"
                                                    id="table-fixed">
                                                    <tbody id="konten-table"></tbody>
                                                </table>
                                            </div>
                                            <!-- <input type="text" class="form-control" id="kode_barcode"
                                                placeholder="PALLET/00000001"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div style="margin-top: 23px;">
                                            <button type="button" class="btn btn-success" id="search_kode_pallet"><i
                                                    class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check
                                                    Kode</label></button>
                                            <button type="button" class="btn btn-success" id="check_kode"
                                                style="display: none;"><i class="fas fa-search"></i> <label
                                                    name="CAPTION-CHECKKODE">Check
                                                    Kode</label></button>
                                            <span id="loading_cek_manual" style="display:none;"><i
                                                    class="fa fa-spinner fa-spin"></i> <label
                                                    name="CAPTION-LOADING">Loading</label>...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                            <div class="row">
                                <div class="from-group">
                                    <label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
                                    <input type="text" class="form-control" id="txtpreviewscan" readonly />
                                </div>
                            </div>
                            <br>
                            <!-- <div class="row" style="float: right;">
                                <button type="button" disabled class="btn btn-primary" id="release_pallet"><i
                                        class="fas fa-qrcode"></i> <label name="CAPTION-RELEASEPALLET">Release
                                        Pallet</label></button>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="panel-pallet">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-PALLET">Pallet</h4>
                        <div class="pull-right">
                            <button id="nonAktifPallet" class="btn btn-danger" type="button" disabled><i
                                    class="fa fa-link-slash"></i> Non Aktif Pallet</button>
                            <button data-toggle="modal" data-target="#modal-history-pallet" id="btn-history-pallet"
                                class="btn btn-success" type="button"><i class="fa fa-search"></i> History</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div style="text-align: center; display: none;" class="spinner"><img style="width: 30px;"
                            src="<?= base_url() ?>/assets/images/spinner.gif" alt=""></div>
                    <div class="customer-info">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-NOPALLET">No. Pallet</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-pallet_kode"></span>
                                <input type="hidden" id="pallet-pallet_id" value="">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-DEPO">Depo</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-depo_nama"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-Area">Area</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-depo_detail_nama"></span>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-LOKASIRAK">Lokasi Rak</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-rak_lajur_nama"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-LOKASIBIN">Lokasi Bin</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-rak_lajur_detail_nama"></span>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-ISLOCKED">Is Locked</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-is_lock"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label name="CAPTION-ISLOCKREASON">Is Lock Reason</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <span class="pallet-is_lock_reason"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="panel-pallet-sku">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-LISTSKU">List SKU</h4>
                        <button id="deletePalletDetail" class="btn btn-danger pull-right" type="button" disabled><i
                                class="fa fa-trash"></i> Hapus
                            <span id="totalSelected" style="color: white;">(0)</span></button>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-striped" id="table-pallet-detail">
                        <thead>
                            <tr class="bg-primary">
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-PILIH">
                                    Pilih</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SKUKODE">
                                    SKU Kode</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">
                                    SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-BRAND">
                                    Brand</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-KEMASAN">
                                    Kemasan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SATUAN">
                                    Satuan</th>
                                <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                <th style="text-align: center; vertical-align: middle;color:white;">Batch No</th>
                                <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTY">
                                    Qty</th>
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

<div class="modal fade" id="modal-history-pallet" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-HISTORYPALLET">History Pallet</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label name="CAPTION-NOPALLET">No. Pallet:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="pallethistory-pallet_kode"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label name="CAPTION-DEPO">Depo:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="pallethistory-depo_nama"></span>
                            </div>
                        </div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-history-pallet">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-AREA">Area</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-LOKASIRAK">Lokasi Rak</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-LOKASIBIN">Lokasi Bin</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;"
                                        name="CAPTION-TANGGAL">Tanggal</th>
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
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label
                        name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>