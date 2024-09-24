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
                            <label name="CAPTION-NOKOREKSIPALLET">No Koreksi Pallet</label>
                            <select id="filterNoKoreksiPallet" name="filterNoKoreksiPallet" class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHNOKOREKSIPALLET">Pilih No Koreksi Pallet</label>--</option>
                                <?php foreach ($dataKoreksiPallet as $key => $value) { ?>
                                    <option value="<?= $value->id ?>"><?= $value->kode ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TANGGAL">Tanggal</label>
                            <input type="text" id="filterKoreksiPalletTanggal" class="form-control input-sm datepicker" name="filterKoreksiPalletTanggal" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <button class="btn btn-primary" onclick="handleSearchFilterData()"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
                        <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>