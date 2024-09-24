<?php
// $segment01 = $Vrbl->row(0);
// $segment02 = $Vrbl->row(1);
// $segment03 = $Vrbl->row(2);
// $segment04 = $Vrbl->row(3);
// $segment05 = $Vrbl->row(4);
?>

<style>
.autocomplete-suggestions {
    position: fixed !important;
    background: #fff;
    cursor: default;
    overflow: auto;
    top: 82px !important;
    font-size: 8px;
}
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><span name="CAPTION-199100100">Komparasi Stock Pallet Global</span></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4><label name="CAPTION-PENCARIANDATA">Pencarian Data</label></h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-3">
                                <label class="control-label" name="CAPTION-DEPODETAIL">Gudang</label>
                                <select class="form-control select2" id="filter_depo_detail_id"
                                    name="filter_depo_detail_id" style="width:100%;">
                                    <option value=""><span name="CAPTION-PILIH">Semua Gudang</span></option>
                                    <?php foreach ($Gudang as $value) { ?>
                                    <option value="<?= $value['depo_detail_id'] ?>"><?= $value['depo_detail_nama'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label class="control-label" name="CAPTION-PRINCIPLE">Principle</label>
                                <select class="form-control select2" id="filter_principle" name="filter_principle"
                                    style="width:100%;">
                                    <option value=""><span name="CAPTION-PILIH">Semua Principle</span></option>
                                    <?php foreach ($Principle as $value) { ?>
                                    <option value="<?= $value['principle_id'] ?>"><?= $value['principle_kode'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label class="control-label" name="CAPTION-HASIL">Hasil</label>
                                <select class="form-control select2" id="filter_hasil" name="filter_hasil"
                                    style="width:100%;">
                                    <option value=""><span name="CAPTION-PILIH">-- Pilih Hasil --</span></option>
                                    <option value="sama">Sama</option>
                                    <option value="beda">Beda</option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label class="control-label" name="CAPTION-ALOKASI">Alokasi</label>
                                <select class="form-control select2" id="filter_alokasi" name="filter_alokasi"
                                    style="width:100%;">
                                    <option value=""><span name="CAPTION-PILIH">-- Pilih Alokasi --</span></option>
                                    <option value="ada alokasi">Ada alokasi</option>
                                    <option value="tidak ada alokasi">Tidak ada alokasi</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-12">
                                <span id="loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    Loading...</span>
                                <button type="button" id="btn_search_komparasi_lokasi_stock_pallet"
                                    class="btn btn-primary"><i class="fa fa-search"></i> <span
                                        name="CAPTION-CARI">Cari</span></button>
                                <button type="button" id="btn_generate_komparasi_lokasi_stock_pallet"
                                    class="btn btn-success"><i class="fa fa-save"></i> <span
                                        name="CAPTION-GENERATE">Generate</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x_panel">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_komparasi_lokasi_stock_pallet" width="100%"
                                    class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center" style="color:white;">#</th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-GUDANG">Gudang</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-PRINCIPLE">Principle</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUKODE">SKU Kode</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKU">SKU</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUSTOCKQTYALOKASI">SKU Stock Alokasi</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUSTOCKQTYSALDOALOKASI">SKU Stock Saldo
                                                    Alokasi</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-QTYPALLET">Qty Pallet</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-HASIL">Hasil</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-ACTION">Action</span></th>
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
        </div>
    </div>
</div>

<div class="modal fade" id="modal_edit_komparasi_lokasi_stock_pallet" role="dialog" data-keyboard="false"
    data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 95%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-199100100">Komparasi Stock Pallet Global</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-PRINCIPLE">Principle</label>
                                        <input type="text" id="KomparasiStockPalletGlobal-principle"
                                            class="form-control" name="KomparasiStockPalletGlobal[principle]"
                                            autocomplete="off" value="" disabled>
                                        <input type="hidden" id="KomparasiStockPalletGlobal-sku_stock_id"
                                            class="form-control" name="KomparasiStockPalletGlobal[sku_stock_id]"
                                            autocomplete="off" value="" disabled>
                                        <input type="hidden" id="KomparasiStockPalletGlobal-sku_id" class="form-control"
                                            name="KomparasiStockPalletGlobal[sku_id]" autocomplete="off" value=""
                                            disabled>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-SKUKODE">SKU Kode</label>
                                        <input type="text" id="KomparasiStockPalletGlobal-sku_kode" class="form-control"
                                            name="KomparasiStockPalletGlobal[sku_kode]" autocomplete="off" value=""
                                            disabled>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-SKU">SKU</label>
                                        <input type="text" id="KomparasiStockPalletGlobal-sku_nama_produk"
                                            class="form-control" name="KomparasiStockPalletGlobal[sku_nama_produk]"
                                            autocomplete="off" value="" disabled>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-CAPTION-SKUREQEXPDATE">Tgl
                                            Kadaluwarsa SKU</label>
                                        <input type="date" id="KomparasiStockPalletGlobal-sku_stock_expired_date"
                                            class="form-control"
                                            name="KomparasiStockPalletGlobal[sku_stock_expired_date]" autocomplete="off"
                                            value="" disabled>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</label>
                                        <input type="text" id="KomparasiStockPalletGlobal-sku_stock_qty"
                                            class="form-control text-right"
                                            name="KomparasiStockPalletGlobal[sku_stock_qty]" autocomplete="off" value=""
                                            disabled>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-QTYPALLET">Qty Pallet</label>
                                        <input type="text" id="KomparasiStockPalletGlobal-sku_stock_qty_pallet"
                                            class="form-control text-right"
                                            name="KomparasiStockPalletGlobal[sku_stock_qty_pallet]" autocomplete="off"
                                            value="" disabled>
                                    </div>
                                    <div class="col-xs-3">
                                        <label class="control-label" name="CAPTION-SKU">Hasil</label><br>
                                        <input type="text" id="KomparasiStockPalletGlobal-hasil" class="form-control"
                                            name="KomparasiStockPalletGlobal[hasil]" autocomplete="off" value=""
                                            disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="x_panel" style="width: 150%;">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="x_content table-responsive" style="overflow-x: scroll;">
                                        <table id="table_detail_komparasi_lokasi_stock_pallet_edit" width="100%"
                                            class="table table-border table-bordered">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-NO">No</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-LOKASI">Lokasi</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-STATUS">Status</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-PALLET">Pallet</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-QTYAMBIL">Qty Ambil</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-QTYSTOCKIN">Qty Stock In</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-QTYSTOCKOUT">Qty Stock Out</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-QTYTERIMA">Qty Terima</span></th>
                                                    <th class="text-center" style="color:white;"><span
                                                            name="CAPTION-QTYSUMMARY">Qty Summary</span></th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingKomparasiStockPalletGlobaladd" style="display:none;"><i
                        class="fa fa-spinner fa-spin"></i> Loading...</span>
                <!-- <button type="button" id="btn_update_pallet_detail" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button> -->
                <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="ResetForm()"><i
                        class="fa fa-sign-out"></i> <span name="CAPTION-CLOSE">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>