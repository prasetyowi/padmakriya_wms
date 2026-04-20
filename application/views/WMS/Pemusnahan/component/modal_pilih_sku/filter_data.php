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
            <label name="CAPTION-DEPO">Depo</label>
            <input type="text" name="depo_sku" id="depo_sku" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label name="CAPTION-GUDANG">Gudang</label>
            <input type="text" name="gudang_sku" id="gudang_sku" class="form-control" readonly>
          </div>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
          <div class="form-group">
            <label name="CAPTION-PRINCIPLE">Principle</label>
            <input type="text" name="principle_sku" id="principle_sku" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label name="CAPTION-BRAND">Brand</label>
            <select name="brand_sku" id="brand_sku" class="form-control select2"></select>
          </div>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
          <div class="form-group">
            <label name="CAPTION-SKUINDUK">SKU Induk</label>
            <select name="sku_induk_sku" id="sku_induk_sku" class="form-control select2"></select>
          </div>

          <div class="form-group">
            <label name="CAPTION-NAMASKU">Nama SKU</label>
            <input type="text" name="nama_sku" id="nama_sku" placeholder="Nama SKU" class="form-control">
          </div>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3 col-xs-12 col-sm-12">
          <div class="form-group">
            <label name="CAPTION-KODESKUWMS">Kode SKU WMS</label>
            <input type="text" name="kode_wms_sku" id="kode_wms_sku" placeholder="Kode SKU WMS" class="form-control">
          </div>

          <div class="form-group">
            <label name="CAPTION-KODESKUPABRIK">Kode SKU Pabrik</label>
            <input type="text" name="kode_pabrik_sku" id="kode_pabrik_sku" placeholder="Kode SKU Pabrik" class="form-control">
          </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12 col-sm-12">
          <div style="margin-top: 10px;">
            <button class="btn btn-primary" id="search_filter_data_sku"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
            <span id="loadingsearchsku" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>