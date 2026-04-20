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
                <h3><span name="CAPTION-199100200">Komparasi Lokasi Stock Pallet Global</span></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- <div class="x_panel">
                    <div class="x_title">
                        <h4><label name="CAPTION-PENCARIANDATA">Pencarian Data</label></h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label" name="CAPTION-DEPODETAIL">Gudang</label>
                                <select class="form-control select2" id="filter_depo_detail_id"
                                    name="filter_depo_detail_id" style="width:100%;">
                                    <option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>
                                    <?php foreach ($Gudang as $value) { ?>
                                    <option value="<?= $value['depo_detail_id'] ?>"><?= $value['depo_detail_nama'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-6">
                                <label class="control-label" name="CAPTION-PALLETKODE">Pallet Kode</label>
                                <input type="text" class="form-control" id="filter_pallet_kode"
                                    name="filter_pallet_kode" autocomplete="off" value="">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-12">
                                <span id="loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    Loading...</span>
                                <button type="button" id="btn_search_komparasi_lokasi_stock_pallet"
                                    class="btn btn-success"><i class="fa fa-search"></i> <span
                                        name="CAPTION-CARI">Cari</span></button>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="x_panel">
                    <div class="x_title">
                        <div class="row">
                            <div class="col-xs-12">
                                <span id="loading" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    Loading...</span>
                                <button type="button" id="btn_search_komparasi_lokasi_stock_pallet"
                                    class="btn btn-success"><i class="fa fa-search"></i> <label
                                        name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_komparasi_lokasi_stock_pallet" width="100%"
                                    class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center" style="color:white;">#</th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-PALLETKODE">Pallet Kode</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUKODE">SKU Kode</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SKUNAMA">SKU Nama</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-LOKASI">Lokasi</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-SUMMARY">Summary</span></th>
                                            <th class="text-center" style="color:white;"><span
                                                    name="CAPTION-FLAG">Flag</span></th>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-199100200">Komparasi Lokasi Stock Pallet Global</label>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table id="table_komparasi_pallet" class="table table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pilih</th>
                                        <th class="text-center">Lokasi</th>
                                        <th class="text-center">Kode Pallet</th>
                                        <th class="text-center">Nama Rak</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingKomparasiLokasiStockPalletGlobaladd" style="display:none;"><i
                        class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" id="btn_update_pallet" class="btn btn-success"><i class="fa fa-save"></i> <span
                        name="CAPTION-SIMPAN">Simpan</span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger" onclick="ResetForm()"><i
                        class="fa fa-sign-out"></i> <span name="CAPTION-CLOSE">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>