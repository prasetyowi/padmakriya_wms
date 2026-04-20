<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-120005000">Mutasi Stok</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOMUTASI">No Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_no_mutasi" name="filter_no_mutasi" class="form-control select2" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TGLDRAFTMUTASI">Tgl Draft Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <!-- <input type="text" class="form-control datepicker" name="filter_mutasi_tgl" id="filter_mutasi_tgl" required> -->
                                <input type="text" id="filter_mutasi_tgl" class="form-control input-sm datepicker" name="filter_mutasi_tgl" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGASAL">Gudang Asal</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_gudang_asal_mutasi" name="filter_gudang_asal_mutasi" class="form-control select2" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($Gudang as $row) { ?>
                                        <option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"></label>
                            <div class="col-md-6 col-sm-6">
                                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btn_pencarian_mutasi_pallet" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_mutasi_perusahaan" name="filter_mutasi_perusahaan" onchange="GetPrincipleHome(this.value)" class="form-control select2" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($getClientWms as $row) { ?>
                                        <option value="<?= $row->client_wms_id; ?>"><?= $row->client_wms_nama; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_mutasi_principle" name="filter_mutasi_principle" class="form-control select2" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKER">Checker</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_mutasi_checker" name="filter_mutasi_checker" class="form-control select2" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($Checker as $row) { ?>
                                        <option value="<?= $row['karyawan_nama']; ?>"><?= $row['karyawan_nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS">Status</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="filter_mutasi_status" id="filter_mutasi_status" class="form-control select2" style="width:100%">
                                    <option value=""><label name="CAPTION-ALL">All</label></option>
                                    <?php foreach ($Status as $row) { ?>
                                        <option value="<?= $row['status_mutasi']; ?>"><?= $row['status_mutasi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_pencarian_mutasi" width="100%" class="table table-bordered">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center">#</th>
                                <th class="text-center" name="CAPTION-NOMUTASI">No Mutasi</th>
                                <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                                <th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" name="CAPTION-CHECKER">Checker</th>
                                <th class="text-center" name="CAPTION-GUDANGASAL">Gudang Asal</th>
                                <th class="text-center" name="CAPTION-STATUS">Status</th>
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