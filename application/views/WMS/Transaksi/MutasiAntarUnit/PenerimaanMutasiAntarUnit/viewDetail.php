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
                <h3><span name="CAPTION-PROSESTERIMAMUTASI">Proses Terima Mutasi</span></h3>
            </div>
            <div style="float: right">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="mutasi_depo_kode" name="CAPTION-NODOKUMEN">No
                                    Dokumen</label>
                                <input readonly="readonly" type="text" id="mutasi_depo_kode" class="form-control" name="mutasi_depo_kode" autocomplete="off" value="<?= $header['tr_mutasi_depo_kode'] ?>">
                                <input readonly="readonly" type="hidden" id="mutasi_depo_id" class="form-control" name="mutasi_depo_id" autocomplete="off" value="<?= $header['tr_mutasi_depo_id'] ?>">
                                <input readonly="readonly" type="hidden" id="mutasi_depo_tgl_upd" class="form-control" name="mutasi_depo_tgl_upd" autocomplete="off" value="<?= $header['tr_mutasi_depo_tgl_update'] ?>">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="mutasi_depo_ekspedisi_id" name="CAPTION-EKSPEDISI">Ekspedisi</label>
                                <input readonly="readonly" type="text" id="mutasi_depo_ekspedisi_id" class="form-control" name="mutasi_depo_ekspedisi_id" autocomplete="off" value="<?= $header['ekspedisi_nama'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="mutasi_depo_depo_asal" name="CAPTION-DEPOASAL">Depo
                                    Asal</label>
                                <input readonly="readonly" type="text" id="mutasi_depo_depo_asal" class="form-control" name="mutasi_depo_depo_asal" autocomplete="off" value="<?= $header['depo_asal'] ?>">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="mutasi_depo_depo_tujuan" name="CAPTION-DEPOTUJUAN">Depo Tujuan</label>
                                <input readonly="readonly" type="text" id="mutasi_depo_depo_tujuan" class="form-control" name="mutasi_depo_depo_tujuan" autocomplete="off" value="<?= $header['depo_tujuan'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label class="control-label" for="mutasi_depo_driver" name="CAPTION-DRIVER">Pengemudi</label>
                                <input type="text" id="mutasi_depo_driver" name="mutasi_depo_driver" class="form-control" value="<?= $header['karyawan_nama'] ?>" readonly />
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="mutasi_depo_kendaraan" class="control-label" name="CAPTION-KENDARAAN">Kendaraan</label>
                                <input readonly="readonly" type="text" id="mutasi_depo_kendaraan" class="form-control" name="mutasi_depo_kendaraan" autocomplete="off" value="<?= $header['kendaraan_nopol'] ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="mutasi_depo_tgl" class="control-label" name="CAPTION-TANGGAL">Tanggal</label>
                                <input type="date" id="mutasi_depo_tgl" class="form-control" name="mutasi_depo_tgl" autocomplete="off" value="<?= date('Y-m-d', strtotime($header['tr_mutasi_depo_tgl_create'])) ?>" readonly>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="mutasi_depo_gudang" class="control-label" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                <select id="mutasi_depo_gudang" class="form-control select2" name="mutasi_depo_gudang" disabled>
                                    <!-- <option value="">-- Pilih Gudang --</option> -->
                                    <?php foreach ($gudang as $row) : ?>
                                        <option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
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
                                <th class="text-center"><span name="CAPTION-KODE">Kode</span></th>
                                <th class="text-center"><span name="CAPTION-JENIS">Jenis</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td><?= $pallet['pallet_kode'] ?></td>
                                <td><?= $pallet['pallet_jenis_nama'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="row" id="panel-sku">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel-body form-horizontal form-label-left">
                        <table id="table_detail_mutasi" width="100%" class="table table-bordered">
                            <thead>
                                <tr class="bg-info">
                                    <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                    <th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                    <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUREQEXPDATE">Exp Date</span></th>
                                    <th class="text-center"><span name="CAPTION-GUDANG">Gudang</span></th>
                                    <th class="text-center"><span name="CAPTION-QTYPLAN">QTY Plan</span></th>
                                    <th class="text-center"><span name="CAPTION-QTYAMBIL">QTY Ambil</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detail as $key => $data) { ?>
                                    <tr class="text-center">
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $data['principle_kode'] ?></td>
                                        <td><?= $data['sku_kode'] ?></td>
                                        <td><?= $data['sku_nama_produk'] ?></td>
                                        <td><?= $data['sku_kemasan'] ?></td>
                                        <td><?= $data['sku_satuan'] ?></td>
                                        <td><?= $data['sku_stock_expired_date'] ?></td>
                                        <td><?= $data['depo_detail_nama'] ?></td>
                                        <td><?= $data['qty_plan'] ?></td>
                                        <td><?= $data['qty_ambil'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row pull-right" style="margin-top:20px;">
                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
                <!-- <button type="button" id="btnSaveTerimaMutasi" class="btn btn-success"><i class="fa fa-save"></i> <span
                        name="CAPTION-SAVE">Simpan</span> </button> -->
                <a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/PenerimaanMutasiAntarUnitMenu') ?>" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
            </div>
        </div>
    </div>
</div>