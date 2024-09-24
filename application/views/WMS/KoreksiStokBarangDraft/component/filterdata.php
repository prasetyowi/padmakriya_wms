<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3>
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-NODRAFTKOREKSISTOK">No Draft Koreksi Stok</label>
                            <select id="filter_no_koreksi_draft" name="filter_no_koreksi_draft" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHNODRAFTKOREKSISTOK">Pilih No Draft Koreksi
                                        Stok</label>--</option>
                                <?php foreach ($data_corrections_draft as $correction) { ?>
                                    <option value="<?= $correction->id ?>"><?= $correction->kode ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TANGGAL">Tanggal</label>
                            <input type="text" id="filter_koreksi_draft_tgl_draft" class="form-control input-sm datepicker" name="filter_koreksi_draft_tgl_draft" autocomplete="off">
                            <!-- <input type="date" class="form-control" name="filter_koreksi_draft_tgl_draft" id="filter_koreksi_draft_tgl_draft" required> -->
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-GUDANG">Gudang</label>
                            <select id="filter_gudang_asal_koreksi_draft" name="filter_gudang_asal_koreksi_draft" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHGUDANG">Pilih Gudang</label>--</option>
                                <?php foreach ($warehouses as $warehouse) { ?>
                                    <option value="<?= $warehouse->id ?>"><?= $warehouse->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                            <select id="filter_koreksi_draft_tipe_transaksi" name="filter_koreksi_draft_tipe_transaksi" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHTIPETRANSAKSI">Pilih Tipe
                                        Transaksi</label>--</option>
                                <?php foreach ($type_transactions as $type_transaction) { ?>
                                    <option value="<?= $type_transaction->id ?>"><?= $type_transaction->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-PRINCIPLE">Principle</label>
                            <select id="filter_koreksi_draft_principle" name="filter_koreksi_draft_principle" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--
                                </option>
                                <?php foreach ($principles as $principle) { ?>
                                    <option value="<?= $principle->id ?>"><?= $principle->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-CHECKER">Checker</label>
                            <select id="filter_koreksi_draft_checker" name="filter_koreksi_draft_checker" class="form-control select2"></select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-STATUS">Status</label>
                            <select name="filter_koreksi_draft_status" id="filter_koreksi_draft_status" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHSTATUS">Pilih Status</label>--</option>
                                <option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
                                <option value="In Progress Approval"><label name="CAPTION-INPROGRESSAPPROVAL">In
                                        Progress Approval</label></option>
                                <option value="Approved"><label name="CAPTION-APPROVED">Approved</label></option>
                                <option value="Rejected"><label name="CAPTION-REJECTED">Rejected</label></option>
                                <option value="Completed"><label name="CAPTION-COMPLETED">Completed</label></option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div style="margin-top: 24px;">
                            <button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
                            <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>