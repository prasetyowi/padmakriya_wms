<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MENULAPORANPENGIRIMAN">Menu Laporan Pengiriman</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
        <div class="panel-heading"><label name="CAPTION-PENCARIANLAPORANPENGIRIMAN">Pencarian Laporan Pengiriman</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGAL">Tanggal</label>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <input type="date" class="form-control" id="filter_pengiriman_tgl" name="filter_pengiriman_tgl" value="<?= date('Y-m-d') ?>" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <button type="button" style="height: 35px" id="btn_laporan_pengiriman_cari" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                                <span id="loadingview" style="display: none;" ><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default panel-tabel-pengiriman">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_laporan_pengiriman" width="100%" class="table table-striped text-center">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center" style="color: white;" name="CAPTION-TANGGAL">Tanggal</th>
                                <th class="text-center" style="color: white;" name="CAPTION-STATUS">Status</th>
                                <th class="text-center" style="color: white;" name="CAPTION-DOWNLOAD">Download</th>
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
                                <th class="text-center" name="CAPTION-DEPO">Depo</th>
                                <th class="text-center" name="CAPTION-GUDANG">Gudang</th>
                                <th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" name="CAPTION-IDPRODUK">ID Produk</th>

                                <th class="text-center" NAME="CAPTION-NAMAPRODUK">Nama Produk</th>
                                <th class="text-center" name="CAPTION-STOCKAWAL">Stock Awal</th>
                                <th class="text-center" name="CAPTION-STOCKMASUK">Stock Masuk</th>
                                <th class="text-center" name="CAPTION-STOCKALOKASIMASUK">Stock Alokasi Masuk</th>
                                <th class="text-center" name="CAPTION-STOCKALOKASIKELUAR">Stock Alokasi Keluar</th>
                                <th class="text-center" name="CAPTION-STOCKKELUAR">Stock Keluar</th>
                                <th class="text-center" name="CAPTION-STOCKAKHIR">Stock Akhir</th>
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