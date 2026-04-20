<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PENERIMAANMUTASIANTARUNIT">Penerimaan Mutasi Antar Unit</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-FILTERDATA">Filter Data</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="col-form-label col-md-2 col-sm-2 label-align"
                                name="CAPTION-TANGGAL">Tanggal</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="filter_date" class="form-control" name="filter_date" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NODOKUMEN">No
                                Dokumen</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="filter_dokumen" id="filter_dokumen" class="form-control select2">
                                    <option value="">-- Pilih Semua --</option>
                                    <?php foreach ($dokumen as $data) { ?>
                                    <option value="<?= $data['tr_mutasi_depo_kode']  ?>">
                                        <?= $data['tr_mutasi_depo_kode'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-2 col-sm-2 label-align"></label>
                            <div class="col-md-6 col-sm-6">
                                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="searchData" class="btn btn-primary"><i
                                        class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default" style="display: none;" id="resultData">
            <div class="panel-heading"><label name="CAPTION-RESULTDATA">Result Data</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="table-responsive">
                        <table id="table_mutasiAntarUnit" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" name="CAPTION-NO">No</th>
                                    <th class="text-center" name="CAPTION-TANGGALKIRIM">Tgl Kirim</th>
                                    <th class="text-center" name="CAPTION-NODOKUMEN">No Dokumen</th>
                                    <th class="text-center" name="CAPTION-STATUS">Status</th>
                                    <th class="text-center" name="CAPTION-DEPOASAL">Depo Asal</th>
                                    <th class="text-center" name="CAPTION-DEPOTUJUAN">Depo Tujuan</th>
                                    <th class="text-center" name="CAPTION-ACTION">Action</th>
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

<div class="modal fade" id="modalViewKodeGen" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" name="CAPTION-DAFTARKODEMUTASISTOK">Daftar Kode Mutasi Stok</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="tableViewKodeGen" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode generate mutasi stok</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label
                        name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>