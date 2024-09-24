<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3>
        <div class="clearfix"></div>
      </div>
      <div class="container mt-2">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label name="CAPTION-TAHUN">Tahun</label>
                  <select class="form-control select2" name="tahun_filter" id="tahun_filter" required>
                    <option value="">--Pilih Tahun--</option>
                    <?php foreach ($rangeYear as $item) : ?>
                      <option value="<?php echo $item ?>" <?= ($item == date('Y') ? "selected" : "") ?>>
                        <?php echo $item ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label name="CAPTION-BULAN">Bulan</label>
                  <select id="bulan_filter" name="bulan_filter" class="form-control select2">
                    <?php foreach ($rangeMonth as $key => $item) : ?>
                      <option value="<?php echo $key ?>" <?= ($key == date('m') ? "selected" : "") ?>>
                        <?php echo $item ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
              <select class="form-control select2 perusahaan" name="perusahaan_filter" id="perusahaan_filter" required></select>
            </div>
            <div class="form-group">
              <label name="CAPTION-PRINCIPLE">Principle</label>
              <select class="form-control select2 principle" name="principle_filter" id="principle_filter" required></select>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
            <!-- <div class="form-group">
              <label>Tipe Penerimaan</label>
              <select class="form-control select2 tipe_penerimaan" name="tipe_penerimaan_filter" id="tipe_penerimaan_filter" required></select>
            </div> -->
            <div class="form-group">
              <label name="CAPTION-STATUS">Status</label>
              <select name="status_filter" id="status_filter" class="form-control select2">
                <option value=""><label name="CAPTION-ALL">All</label></option>
                <option value="NULL"><label name="CAPTION-OPEN">Open</label></option>
                <option value="1"><label name="CAPTION-CLOSE">Close</label></option>
              </select>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
            <button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
            <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
            <!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>