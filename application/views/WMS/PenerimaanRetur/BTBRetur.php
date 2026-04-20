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

    .head-switch-master {
        /* max-width: 1000px; */
        width: 100%;
    }

    .switch-holder-master {
        display: flex;
        border-radius: 10px;
    }

    .switch-label-master {
        width: 100%;
    }

    .switch-toggle-master input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        z-index: -2;
    }

    .switch-toggle-master input[type="checkbox"]+label {
        position: relative;
        display: inline-block;
        width: 200px;
        height: 50px;
        border-radius: 100px;
        margin: 0;
        cursor: pointer;
        box-shadow: 1px 1px 4px 1px;

    }

    .switch-toggle-master input[type="checkbox"]+label::before {
        position: absolute;
        content: 'Pallet Baru';
        font-size: 13px;
        text-align: center;
        line-height: 25px;
        top: 8px;
        left: 8px;
        width: 100px;
        height: 30px;
        color: #fff;
        border-radius: 20px;
        background-color: #5bc0de;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #5bc0de;
        transition: .3s ease-in-out;
    }

    .switch-toggle-master input[type="checkbox"]:checked+label::before {
        left: 50%;
        content: 'Pallet Lama';
        color: #fff;
        background-color: #f0ad4e;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #f0ad4e;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><span name="CAPTION-BTB">Bukti Terima Barang</span></h3>
            </div>
            <div style="float: right">
            </div>
        </div>
        <?php foreach ($DOHeader as $header) : ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-delivery_order_batch_kode">
                                    <label class="control-label" for="penerimaanpenjualan-delivery_order_batch_kode" name="CAPTION-NOFDJR">No FDJR</label>
                                    <input readonly="readonly" type="text" id="penerimaanpenjualan-delivery_order_batch_kode" class="form-control" name="penerimaanpenjualan[delivery_order_batch_kode]" autocomplete="off" value="<?= $header['delivery_order_batch_kode'] ?>">
                                    <input readonly="readonly" type="hidden" id="penerimaanpenjualan-delivery_order_batch_id" class="form-control" name="penerimaanpenjualan[delivery_order_batch_id]" autocomplete="off" value="<?= $header['delivery_order_batch_id'] ?>">
                                    <?php if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') { ?>
                                        <input readonly="readonly" type="hidden" id="penerimaanpenjualan-delivery_order_update_tgl" class="form-control" name="penerimaanpenjualan[delivery_order_update_tgl]" autocomplete="off" value="<?= $last_upd ?>">
                                    <?php } else { ?>
                                        <input readonly="readonly" type="hidden" id="penerimaanpenjualan-delivery_order_update_tgl" class="form-control" name="penerimaanpenjualan[delivery_order_update_tgl]" autocomplete="off" value="<?= $header['delivery_order_update_tgl'] ?>">
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-delivery_order_kode">
                                    <label class="control-label" for="penerimaanpenjualan-delivery_order_kode" name="CAPTION-NODO">No DO</label>
                                    <!-- <input readonly="readonly" type="text" id="penerimaanpenjualan-delivery_order_kode" class="form-control" name="penerimaanpenjualan[delivery_order_kode]" autocomplete="off" value="<?= $header['delivery_order_kode'] ?>"> -->
                                    <textarea name="penerimaanpenjualan-delivery_order_kode" id="penerimaanpenjualan-delivery_order_kode" class="form-control" disabled><?= $do_kode ?></textarea>
                                    <input readonly="readonly" type="hidden" id="penerimaanpenjualan-delivery_order_id" class="form-control" name="penerimaanpenjualan[delivery_order_id]" autocomplete="off" value="<?= $do_id ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-client_wms_id">
                                    <label for="penerimaanpenjualan-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                    <select id="penerimaanpenjualan-client_wms_id" class="form-control select2" name="penerimaanpenjualan[client_wms_id]" disabled>
                                        <option value="<?= $header['client_wms_id'] ?>"><?= $header['client_wms_nama'] ?>
                                        </option>
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-xs-4">
                <div class="form-group field-penerimaanpenjualan-principle_id">
                  <label for="penerimaanpenjualan-principle_id" class="control-label" name="CAPTION-PRINCIPLE">Principle</label>
                  <select id="penerimaanpenjualan-principle_id" class="form-control select2" name="penerimaanpenjualan[principle_id]" onchange="GetCheckerByPrinciple(this.value)">
                    <option value="">** <label name="CAPTION-PILIH">Pilih</label> **</option>
                    <?php foreach ($Principle as $row) : ?>
                      <option value="<?= $row['principle_id'] ?>"><?= $row['principle_kode'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div> -->
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-delivery_order_kode">
                                    <label class="control-label" for="penerimaanpenjualan-sales_order_no_po" name="CAPTION-NOSOEKSTERNAL">No SO External</label>
                                    <!-- <input readonly="readonly" type="text" id="penerimaanpenjualan-sales_order_no_po" class="form-control" name="penerimaanpenjualan[sales_order_no_po]" autocomplete="off" value="<?= $header['sales_order_no_po'] ?>"> -->
                                    <textarea name="penerimaanpenjualan-sales_order_no_po" id="penerimaanpenjualan-sales_order_no_po" class="form-control" disabled><?= $no_so ?></textarea>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-karyawan_id">
                                    <label for="penerimaanpenjualan-karyawan_id" class="control-label" name="CAPTION-CHECKER">Checker</label>
                                    <select id="penerimaanpenjualan-karyawan_id" class="form-control select2" name="penerimaanpenjualan[karyawan_id]" <?= $header['karyawan_id'] != '' ? 'disabled' : ''; ?>>
                                        <?php foreach ($Checker as $row) : ?>
                                            <option value="<?= $row['karyawan_id'] ?>" <?= $header['karyawan_id'] == $row['karyawan_id'] ? 'selected' : ''; ?>>
                                                <?= $row['karyawan_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-penerimaan_penjualan_kode">
                                    <label for="penerimaanpenjualan-penerimaan_penjualan_kode" class="control-label" name="CAPTION-NOBTB">No BTB</label>
                                    <input readonly="readonly" type="text" id="penerimaanpenjualan-penerimaan_penjualan_kode" class="form-control" name="penerimaanpenjualan[penerimaan_penjualan_kode]" autocomplete="off" placeholder="autogenerate" value="">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-penerimaan_tipe">
                                    <label class="control-label" for="penerimaanpenjualan-penerimaan_tipe" name="CAPTION-TIPE">Tipe BTB</label>
                                    <textarea name="penerimaanpenjualan-penerimaan_tipe" id="penerimaanpenjualan-penerimaan_tipe" name="penerimaanpenjualan[penerimaan_tipe]" class="form-control" disabled><?= $do_status ?></textarea>
                                    <input readonly="readonly" type="text" id="penerimaanpenjualan-tipe_delivery_order" class="form-control" name="penerimaanpenjualan[penerimaan_tipe]" autocomplete="off" value="<?= $do_tipe_nama ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-client_wms_id">
                                    <label for="penerimaanpenjualan-client_wms_id" class="control-label" name="CAPTION-TGLBTB">Tanggal BTB</label>
                                    <input type="text" id="penerimaanpenjualan-penerimaan_penjualan_tgl" class="form-control datepicker" name="penerimaanpenjualan[penerimaan_penjualan_tgl]" autocomplete="off" value="<?= $header['penerimaan_penjualan_tgl'] ?>" readonly>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group field-penerimaanpenjualan-depo_detail_id">
                                    <label for="penerimaanpenjualan-depo_detail_id" class="control-label" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                    <select id="penerimaanpenjualan-depo_detail_id" class="form-control select2" name="penerimaanpenjualan[depo_detail_id]">
                                        <!-- <option value="">** <label name="CAPTION-PILIH">Pilih</label> **</option> -->
                                        <?php foreach ($Gudang as $row) : ?>
                                            <option value="<?= $row['depo_detail_id'] ?>" <?= $header['depo_detail_id'] == $row['depo_detail_id'] ? 'selected' : ''; ?>>
                                                <?= $row['depo_detail_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="form-check text-right" style="display: block;">
                    <span style="margin-right: 2rem;"><b>Terapkan Semua :</b></span>
                    <input type="checkbox" style="transform: scale(1.5); margin-right: 0.5rem;" class="form-check-input terapkan" id="chkBagus" data-kondisi="Bagus">
                    <label class="form-check-label" for="chkBagus" style="vertical-align: middle">Bagus</label><br>
                    <input type="checkbox" style="transform: scale(1.5); margin-right: 0.5rem;" class="form-check-input terapkan" id="chkRusak" data-kondisi="Rusak">
                    <label class="form-check-label" for="chkRusak" style="vertical-align: middle">Rusak</label>
                </div>
            </div>
            <div class="row" id="panel-sku">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel-body form-horizontal form-label-left">
                        <table id="tabledoretur" width="100%" class="table table-bordered">
                            <thead>
                                <tr class="bg-info">
                                    <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                    <th class="text-center"><span name="CAPTION-NODO">No. DO</span></th>
                                    <th class="text-center"><span name="CAPTION-CUSTOMER">Customer</span></th>
                                    <th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                    <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                    <th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
                                    <th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUREQEXPDATE">Exp Date</span></th>
                                    <th class="text-center" style="width: 10%;"><span>Kondisi</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($DODetail != "0") {
                                    $no = 1;
                                    foreach ($DODetail as $detail) : ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $no ?>
                                                <input type="hidden" class="doid" value="<?= $detail['delivery_order_id'] ?>">
                                                <input type="hidden" class="skuid" value="<?= $detail['sku_id'] ?>">
                                                <input type="hidden" class="principleid" value="<?= $detail['principle_id'] ?>">
                                            </td>
                                            <td class="text-center"><?= $detail['delivery_order_kode'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_nama'] ?></td>
                                            <td class="text-center"><?= $detail['principle'] ?></td>
                                            <td class="text-center"><?= $detail['sku_kode'] ?></td>
                                            <td class="text-center"><?= $detail['sku_nama_produk'] ?></td>
                                            <td class="text-center"><?= $detail['sku_kemasan'] ?></td>
                                            <td class="text-center"><?= $detail['sku_satuan'] ?></td>
                                            <td class="text-center"><?= $detail['sku_qty'] ?></td>
                                            <td class="text-center"><?= $detail['sisa_jumlah_terima'] ?></td>
                                            <td class="text-center"><?= $detail['sku_expdate'] ?></td>
                                            <td class="text-center">
                                                <select name="kondisiBarang" id="kondisiBarang-<?= $no ?>" class="form-control">
                                                    <option value="">--Pilih Kondisi Barang--</option>
                                                    <?php if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') { ?>
                                                        <option <?= $detail['kondisi_barang'] != null && $detail['kondisi_barang'] == 'Bagus' ? 'selected' : '' ?> value="Bagus">
                                                            Bagus</option>
                                                        <option <?= $detail['kondisi_barang'] != null && $detail['kondisi_barang'] == 'Rusak' ? 'selected' : '' ?> value="Rusak">
                                                            Rusak</option>
                                                    <?php } else { ?>
                                                        <option <?= $detail['kondisi_barang'] != null && $detail['kondisi_barang'] == 'Bagus' ? 'selected' : '' ?> value="Bagus,<?= $detail['delivery_order_id'] ?>,<?= $detail['sku_id'] ?>">
                                                            Bagus</option>
                                                        <option <?= $detail['kondisi_barang'] != null && $detail['kondisi_barang'] == 'Rusak' ? 'selected' : '' ?> value="Rusak,<?= $detail['delivery_order_id'] ?>,<?= $detail['sku_id'] ?>">
                                                            Rusak</option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="text-center" hidden><?= $detail['client_wms_id'] ?></td>
                                        </tr>
                                <?php $no++;
                                    endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:10px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                <div class="head-switch-master">
                    <div class="switch-holder-master">
                        <div class="switch-toggle-master">
                            <input type="checkbox" id="check_master" class="check_master">
                            <label for="check_master"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12" id="switch-button-pallet">
                <button type="button" class="btn btn-success" id="btnpenerimaanperpallet"><span name="CAPTION-TAMBAHPENERIMAANPERPALLET">Tambah Penerimaan Per Pallet</span></button>
            </div>

            <div id="switch-scan-pallet" style="display: none;">
                <div class="col-xl-10 col-lg-10 col-md-10 col-xs-12">
                    <div class="head-switch-global">
                        <div class="switch-holder-global">
                            <div class="switch-toggle">
                                <input type="checkbox" id="check_scan" class="check_scan">
                                <label for="check_scan"></label>
                            </div>
                            <div class="switch-label-global">
                                <button type="button" class="btn btn-info" id="start_scan"><i class="fas fa-qrcode"></i>
                                    <label name="CAPTION-STARTSCAN">Start Scan</label></button>
                                <button type="button" class="btn btn-danger" style="display:none" id="stop_scan"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop
                                        Scan</label></button>
                                <button type="button" class="btn btn-warning" style="display:none" id="input_manual"><i class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual
                                        Input</label></button>
                                <button type="button" class="btn btn-danger" style="display:none" id="close_input"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close
                                        Input</label></button>
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
                                    <input type="text" class="form-control" autocomplete="off" id="kode_barcode_notone" onkeyup="handlerScanInputManual(event, this.value, 'notone')" placeholder="masukkan kode setelah tanda '/' pertama">
                                    <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                                        <table class="table table-striped table-sm table-hover" id="table-fixed-notone">
                                            <tbody id="konten-table-notone"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
								<label name="CAPTION-KODEPALLET">Kode Pallet</label>
								<input type="text" class="form-control" id="kode_barcode" placeholder="PALLET/00000001">
							</div> -->
                            </div>
                            <div class="col-md-6">
                                <div style="margin-top: 23px;">
                                    <button type="button" class="btn btn-success" id="check_kode"><i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check
                                            Kode</label></button>
                                    <span id="loading_cek_manual" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
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

        </div>
        <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
            <div class="panel panel-default col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" id="viewpalletdoretur">
                <div class="panel-heading" style="margin-left:-10px;margin-right:-10px;"><span name="CAPTION-PALLET">Pallet</span></div>
                <div class="panel-body form-horizontal form-label-left" style="margin-left:-20px;margin-right:-20px;">
                    <table id="tablepallet" width="100%" class="table table-bordered">
                        <thead>
                            <tr class="bg-info">
                                <th class="text-center" style="width: 40%;" colspan="2" name="CAPTION-LOKASIBINTUJUAN">
                                    Lokasi Bin Tujuan</th>
                                <th class="text-center"><span name="CAPTION-KODE">Kode</span></th>
                                <th class="text-center"><span name="CAPTION-TIPE">Jenis</span></th>
                                <th class="text-center" style="width: 20%;"><span name="CAPTION-ACTION">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
            <div class="panel panel-default col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="viewdetailpallet" style="display: none;">
                <div class="panel-heading" style="margin-left:-10px;margin-right:-10px;">Detail Pallet</div>
                <div class="panel-body form-horizontal form-label-left" style="margin-left:-15px;margin-right:-20px;">
                    <input type="hidden" id="penerimaanpenjualandetail-pallet_id" value="">
                    <!-- <button type="button" class="btn btn-success" id="btn-tambah-sku-fdjr"><span
                    name="CAPTION-TAMBAHSKUBYFDJR">Tambah SKU By FDJR</span></button> -->
                    <div class="table-responsive">
                        <table id="tablepalletdetail" width="100%" class="table table-bordered">
                            <thead>
                                <tr class="bg-info">
                                    <th class="text-center"><span name="CAPTION-PRINCIPLE">Principal</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                    <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUREQEXPDATE">Exp Date</span></th>
                                    <th class="text-center"><span name="CAPTION-QTY">Jumlah</span></th>
                                    <th class="text-center"><span name="CAPTION-BATCHNO">Batch No</span></th>
                                    <th class="text-center"><span name="CAPTION-ACTION">Action</span></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row pull-right" style="margin-top:20px;">
                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
                <button type="button" id="btnsavebtb" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-SAVE">Simpan</span> </button>
                <a href="<?php echo base_url() ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_scan" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" name="CAPTION-DETAILDATA">Detail Data</h4>
            </div>
            <div class="modal-body">
                <div id="select_kamera"></div>
                <div id="preview"></div>

                <div class="from-group" style="margin-top: 20px;">
                    <label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
                    <input type="text" class="form-control" id="txtpreviewscan2" readonly />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark stop_scan" id="stop_scan"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_scan_rak" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" name="CAPTION-DETAILDATA">Detail Data</h4>
            </div>
            <div class="modal-body">
                <div id="select_kamera_by_one"></div>
                <div id="preview_by_one"></div>

                <div class="from-group" style="margin-top: 20px;">
                    <label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
                    <input type="text" class="form-control" id="txtpreviewscan2" readonly />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark stop_scan_by_one"><i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="manual_input_rak" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" name="CAPTION-MANUALINPUTCHECKLOKASIBINTUJUAN">Manual Input Check Lokasi Bin
                    Tujuan</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label name="CAPTION-NAMARAK">Nama Rak</label>
                    <input type="text" class="form-control" id="nama_rak" autocomplete="off" id="kode_barcode_one" onkeyup="handlerScanInputManual(event, this.value, 'one')" placeholder="1A-2-8">
                    <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                        <table class="table table-striped table-sm table-hover" id="table-fixed-one">
                            <tbody id="konten-table-one"></tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="form-group">
					<label name="CAPTION-NAMARAK">Nama Rak</label>
					<input type="text" class="form-control nama_rak" id="nama_rak" placeholder="1A-2-8" />
				</div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark tutup_input_manual"><i class="fas fa-xmark"></i> <label name="CAPTION-CLOSE">Close</label></button>
            </div>
        </div>
    </div>
</div>