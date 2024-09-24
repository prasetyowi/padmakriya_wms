<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-136000100">Register Tanda Terima</h3>
            </div>
            <div style="float: right">
                <?php if ($Menu_Access["C"] == 1) : ?>
                    <a href="<?= base_url('WMS/Abacus/RegisterTandaTerima/create') ?>" class="btn btn-primary" target="_blank"><i class="fa fa-plus"></i> <span name="CAPTION-BARU">Baru</span></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4>Filter Data</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <label name="CAPTION-TANGGALABACUS">Tanggal Abacus</label>
                                <input type="text" id="filter_tanggal_abacus" class="form-control" name="filter_tanggal_abacus" value="" />
                            </div>
                            <div class="col-xs-4">
                                <label for="TrAbacus-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                <select id="filter_perusahaan" class="form-control select2" name="filter_perusahaan">
                                    <option value="">** <span name="CAPTION-SEMUA">Semua</span> **</option>
                                    <?php foreach ($Perusahaan as $row) : ?>
                                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <label for="TrAbacus-client_wms_id" class="control-label" name="CAPTION-STATUS">Status</label>
                                <select id="filter_status" class="form-control select2" name="filter_status">
                                    <option value="">** <span name="CAPTION-SEMUA">Semua</span> **</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Laksanakan">Laksanakan</option>
                                    <option value="Canceled">Canceled</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-12">
                                <span id="loadingviewdodraft" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <span name="CAPTION-LOADING">Loading</span>...</span>
                                <button type="button" id="btn_search_abacus" class="btn btn-primary" onclick="Get_tr_abacus_by_filter()"><i class="fa fa-search"></i> <span name="CAPTION-CARI">Cari</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_abacus" width="100%" class="table table-bordered table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;">#</th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-PERUSAHAAN">Perusahaan</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-TANGGALABACUS">Tanggal Abacus</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-KODEABACUS">Kode Abacus</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-REFFKODE">Reff Kode</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-GRANDTOTAL">Grand Total</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-STATUS">Status</span></th>
                                            <th class="text-center" style="text-align: center; vertical-align: middle;color:white;"><span name="CAPTION-ACTION">Action</span></th>
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