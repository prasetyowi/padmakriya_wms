<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h4><strong><label name="CAPTION-FILTERSKU">Filter SKU</label></strong></h4>
            <div class="clearfix"></div>
        </div>
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label name="CAPTION-PRINCIPLE">Principle</label>
                        <select name="principle_sku" id="principle_sku" class="form-control select2" onchange="handlerGetPrincipleBrand(this.value)">
                            <option value="">--Pilih Principle--</option>
                            <?php foreach ($principles as $key => $value) { ?>
                                <option value="<?= $value->principle_id ?>"><?= $value->principle_nama ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
                    <div class="form-group">
                        <label name="CAPTION-BRAND">Brand</label>
                        <select name="brand_sku" id="brand_sku" class="form-control select2">
                            <option value="">--Pilih Brand--</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12 col-sm-12">
                    <div style="margin-top: 10px;">
                        <button class="btn btn-primary" id="search_filter_data_sku" onclick="handlerSearchFilterDataSku(event)"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
                        <span id="loadingsearchsku" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>