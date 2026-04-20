<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-120004000">Draft Mutasi Stok</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-FORM">Form</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NODRAFTMUTASI">No
                                Draft Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="mutasi_draft_no_draft" class="form-control" name="mutasi_draft_no_draft" value="" readonly />
                                <input type="hidden" id="mutasi_pallet_draft_id" class="form-control" name="mutasi_pallet_draft_id" value="<?= $mutasi_pallet_draft_id; ?>" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" class="form-control datepicker" name="mutasi_draft_tanggal_rencana_kirim" id="mutasi_draft_tanggal_rencana_kirim" onchange="Get_list_sku_bonus_tidak_ada_di_gudang_bonus()" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPEDO">Tipe Delivery Order</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_tipe_do" class="form-control select2" name="mutasi_draft_tipe_do" onchange="Get_list_sku_bonus_tidak_ada_di_gudang_bonus()">
                                    <option value="">-- Pilih Tipe DO --</option>
                                    <?php foreach ($TipeDeliveryOrder as $type) : ?>
                                        <option value="<?= $type['tipe_delivery_order_id'] ?>" data-text="<?= $type['tipe_delivery_order_alias'] ?>">
                                            <?= $type['tipe_delivery_order_alias'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TGLDRAFTMUTASI">Tgl Draft Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="date" class="form-control" name="mutasi_draft_tanggal" id="mutasi_draft_tanggal" value="<?= getLastTbgDepo() ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_perusahaan" name="mutasi_draft_perusahaan" class="form-control select2" style="width:100%" onchange="GetPrinciple(this.value)" required>
                                    <option value="">** <label name="CAPTION-PILIHPERUSAHAAN">Pilih Perusahaan</label> **
                                    </option>
                                    <?php foreach ($getClientWms as $row) { ?>
                                        <option value="<?= $row->client_wms_id ?>"><?= $row->client_wms_nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_principle" name="mutasi_draft_principle" class="form-control select2" style="width:100%" onchange="GetCheckerPrinciple(this.value)" required>
                                    <option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>
                                    <?php foreach ($Principle as $row) { ?>
                                        <option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGASAL">Gudang Asal</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="gudang_asal_mutasi_draft" name="gudang_asal_mutasi_draft" class="form-control select2" style="width:100%" onchange="ViewPallet()" required>
                                    <option value="">** <label name="CAPTION-PILIHGUDANG">Pilih Gudang</label> **</option>
                                    <?php foreach ($Gudang as $row) { ?>
                                        <option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKER">Checker</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_checker" name="mutasi_draft_checker" class="form-control select2" style="width:100%" required>
                                    <option value="">** <label name="CAPTION-PILIHCHECKER">Pilih Checker</label> **
                                    </option>
                                    <!-- <?php foreach ($Checker as $row) { ?>
                              <option value="<?= $row['karyawan_id'] . " || " . $row['karyawan_nama']; ?>"><?= $row['karyawan_nama']; ?></option>
                           <?php } ?> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" id="mutasi_draft_keterangan" name="mutasi_draft_keterangan" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS">Status</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="mutasi_draft_status" id="mutasi_draft_status" class="form-control" value="Draft" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="checkbox" name="mutasi_draft_approval" style="transform: scale(1.5);" id="mutasi_draft_approval" value="In Progress Approval"> &nbsp;<label name="CAPTION-PENGAJUANAPPROVAL"> Pengajuan
                                    Approval</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-INFOSKUBONUSYANGKURANG">Info SKU Bonus Yang Kurang</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_list_sku_bonus_tidak_ada_di_gudang_bonus" width="100%" class="table table-bordered">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center">#</th>
                                <th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
                                <th class="text-center" name="CAPTION-SKU">SKU</th>
                                <th class="text-center" name="CAPTION-SKUSATUAN">SKU Satuan</th>
                                <th class="text-center" name="CAPTION-GUDANGGOODSTOCK">Gudang Good Stock</th>
                                <th class="text-center" name="CAPTION-QTYORDER">Qty Order</th>
                                <th class="text-center" name="CAPTION-STOCKQTYGUDANGBONUS">Stock Qty Gudang Bonus</th>
                                <th class="text-center" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row" style="margin-bottom: 10px;">
                    <button class="btn btn-primary" id="btn_pilih_mutasi_sku_draft"><i class="fa fa-plus"></i> <span name="CAPTION-PILIHSKU">Pilih SKU</span></button>
                </div>
                <div class="row">
                    <input type="hidden" id="txt_jml_sku" value="0">
                    <table id="table_mutasi_draft" width="100%" class="table table-bordered">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center">#</th>
                                <th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
                                <th class="text-center" name="CAPTION-SKU">SKU</th>
                                <th class="text-center" name="CAPTION-SKUSATUAN">SKU Satuan</th>
                                <th class="text-center" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                <th class="text-center" name="CAPTION-PALLETASAL">Pallet Asal</th>
                                <th class="text-center" name="CAPTION-QTY">Qty</th>
                                <th class="text-center" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <span name="CAPTION-LOADING">Loading</span>...</span>
                    <button class="btn btn-success" id="btn_save_mutasi_draft"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
                    <a href="<?= base_url(); ?>WMS/MutasiStokDraft/MutasiStokDraftMenu" class="btn btn-danger" id="btn_kembali"><i class="fa fa-reply"></i> <span name="CAPTION-MENUUTAMA">Menu Utama</span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-sku" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-PILIHSKU">Pilih SKU</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-GUDANGASAL">Gudang Asal</label>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-8">
                                <input type="text" class="form-control" id="gudang_asal_sku" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-8">
                                <input type="text" class="form-control" id="perusahaan_sku" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-PRINCIPLE">Principle</label>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-8">
                                <input type="text" class="form-control" id="principle_sku" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="data-table-sku" width="100%" class="table table-bordered">
                            <thead>
                                <tr style="background:#F0EBE3;">
                                    <th class="text-center"><input type="checkbox" name="select-all-sku" id="select-all-sku" value="1"></th>
                                    <th class="text-center" name="CAPTION-SKUKODE">SKU Kode</th>
                                    <th class="text-center" name="CAPTION-SKU">SKU</th>
                                    <th class="text-center" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <th class="text-center" name="CAPTION-SKUSTOCK">SKU Stock</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-info" id="btn-choose-sku-multi"><span name="CAPTION-PILIH">Pilih</span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbackpallet"><span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-detail-sku-bonus" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><span name="CAPTION-135002000">Delivery Order</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKUKODE">SKU Kode</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="SKUBonus-sku_kode" value="" readonly>
                                <input type="hidden" class="form-control" id="SKUBonus-sku_id" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKU">SKU</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="SKUBonus-sku_nama_produk" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-QTYDRAFT">Qty Draft</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="SKUBonus-sku_qty" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="SKUBonus-sku_stock_qty" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="table_detail_sku_bonus_tidak_ada_di_gudang_bonus" width="100%" class="table table-bordered">
                            <thead>
                                <tr style="background:#F0EBE3;">
                                    <th class="text-center">#</th>
                                    <th class="text-center" name="CAPTION-NODO">No DO</th>
                                    <th class="text-center" name="CAPTION-NOSO">No SO</th>
                                    <th class="text-center" name="CAPTION-NOSOEKSTERNAL">No SO Eksternal</th>
                                    <th class="text-center" name="CAPTION-TANGGALDO">Tanggal DO</th>
                                    <th class="text-center" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</th>
                                    <th class="text-center" name="CAPTION-OUTLET">Outlet</th>
                                    <th class="text-center" name="CAPTION-QTYORDER">Qty Order</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbackpallet"><span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-detail-gudang-asal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><span name="CAPTION-GUDANGGOODSTOCK">Gudang Good Stock</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKUKODE">SKU Kode</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="GudangAsal-sku_kode" value="" readonly>
                                <input type="hidden" class="form-control" id="GudangAsal-sku_id" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKU">SKU</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="GudangAsal-sku_nama_produk" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-QTYDRAFT">Qty Draft</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="GudangAsal-sku_qty" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                <label name="CAPTION-SKUSTOCKQTY">SKU Stock Qty</label>
                            </div>
                            <div class="col-lg-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control" id="GudangAsal-sku_stock_qty" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="table_detail_gudang_asal" width="100%" class="table table-bordered">
                            <thead>
                                <tr style="background:#F0EBE3;">
                                    <th class="text-center">#</th>
                                    <th class="text-center" name="CAPTION-GUDANG">Gudang</th>
                                    <th class="text-center" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <th class="text-center" name="CAPTION-QTY">Qty</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" data-dismiss="modal" id="btnbackpallet"><span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>