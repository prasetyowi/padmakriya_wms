<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MENUSTOCKMOVEMENT">Menu Stock Movement</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal</label>
                            <div class="col-md-8 col-sm-8">
                                <input type="text" class="form-control" id="filter_stock_tanggal" name="filter_stock_tanggal" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PT">PT</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="form-control select2" id="filter_stock_pt" name="filter_stock_pt" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($ClientWMS as $row) { ?>
                                        <option value="<?= $row['client_wms_id']; ?>"><?= $row['client_wms_nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                     <label class="col-form-label col-md-4 col-sm-4 label-align">Depo</label>
                     <div class="col-md-8 col-sm-8">
                        <select class="form-control select2" id="filter_stock_depo" name="filter_stock_depo" style="width:100%">
                           <option value="">All</option>
                           <?php foreach ($Depo as $row) { ?>
                              <option value="<?= $row['depo_id']; ?>"><?= $row['depo_nama']; ?></option>
                           <?php } ?>
                        </select>
                     </div>
                  </div> -->
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANG">Gudang</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="form-control select2" id="filter_stock_gudang" name="filter_stock_gudang" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($Gudang as $row) { ?>
                                        <option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="form-control select2" id="filter_stock_tipe_transaksi" name="filter_stock_tipe_transaksi" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($TipeTransaksi as $row) { ?>
                                        <option value="<?= $row['tipe_transaksi_id']; ?>">
                                            <?= $row['tipe_transaksi_nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"></label>
                            <div class="col-md-8 col-sm-8">
                                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btn_stock_pencarian" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-OPSI">Opsi</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="form-control select2" name="filter_stock_opsi" id="filter_stock_opsi" style="width:100%">
                                    <option value="rekap"><label name="CAPTION-REKAP">Rekap</label></option>
                                    <option value="detail"><label name="CAPTION-DETAIL">Detail</label></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-md-8 col-sm-8">
                                <select class="form-control select2" id="filter_stock_principle" name="filter_stock_principle" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($Principle as $row) { ?>
                                        <option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-SKU">SKU</label>
                            <div class="col-md-8 col-sm-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control col=lg-2 col-md-2 col-sm-2" id="filter_stock_sku_kode" name="filter_stock_sku_kode" placeholder="Kode" value="" />
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control col=lg-4 col-md-4 col-sm-4" id="filter_stock_sku_nama" name="filter_stock_sku_nama" placeholder="SKU" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-stock-rekap" style="display:none;">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_stock_rekap" width="100%" class="table table-striped text-center">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center" name="CAPTION-DEPO">Depo</th>
                                <th class="text-center" name="CAPTION-GUDANG">Gudang</th>
                                <th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</th>
                                <th class="text-center" name="CAPTION-STOCKMASUK">Stock Masuk</th>
                                <th class="text-center" name="CAPTION-STOCKKELUAR">Stock Keluar</th>
                                <!-- <th class="text-center">Total</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-stock-detail" style="display:none;">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_stock_detail" width="100%" class="table table-striped text-center">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                                <th class="text-center" name="CAPTION-DEPO">Depo</th>
                                <th class="text-center" name="CAPTION-GUDANG">Gudang</th>
                                <th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" name="CAPTION-IDPRODUK">ID Produk</th>
                                <th class="text-center" name="CAPTION-NAMAPRODUK">Nama Produk</th>
                                <th class="text-center" name="CAPTION-IDDOKUMEN">ID Dokumen</th>
                                <th class="text-center" name="CAPTION-DRIVER">Driver</th>
                                <th class="text-center" name="CAPTION-KETERANGAN">Keterangan</th>
                                <th class="text-center" name="CAPTION-STOCKMASUK">Stock Masuk</th>
                                <th class="text-center" name="CAPTION-STOCKKELUAR">Stock Keluar</th>
                                <!-- <th class="text-center">Total</th> -->
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