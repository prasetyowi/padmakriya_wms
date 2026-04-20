<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3>
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <label name="CAPTION-NODRAFTMUTASI">No. Draft Mutasi</label>
                        <select class="form-control select2" name="no_draft_mutasi" id="no_draft_mutasi" required>
                            <option value="">--<label name="CAPTION-PILIHNODRAFTMUTASI">Pilih No. Draft Mutasi</label>--
                            </option>
                            <?php foreach ($draft_mutasi as $no) : ?>
                            <option value="<?= $no->id ?>">
                                <?= $no->kode ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TIPEPENERIMAAN">Tipe Penerimaan</label>
                            <input type="date" name="tgl" id="tgl" class="form-control">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div style="margin-top: 24px;">
                            <button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label
                                    name="CAPTION-FILTER">Filter</label></button>
                            <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label
                                    name="CAPTION-LOADING">Loading</label>...</span>
                        </div>
                        <!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
                    </div>
                </div>
      
      </div>
        </div>
    </div>
</div>