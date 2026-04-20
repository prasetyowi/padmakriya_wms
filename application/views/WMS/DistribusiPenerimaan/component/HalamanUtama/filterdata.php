<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h3><strong>Filter Data</strong></h3>
        <div class="clearfix"></div>
      </div>
      <div class="container mt-2">
        <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
            <div class="form-group">
              <label>Tahun</label>
              <select class="form-control select2" name="tahun_filter" id="tahun_filter" required>
                <option value="">--Pilih Tahun--</option>
                <?php foreach ($rangeYear as $item) : ?>
                  <option value="<?php echo $item ?>" <?= ($item == date('Y') ? "selected" : "") ?>>
                    <?php echo $item ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
            <div class="form-group">
              <label>Bulan</label>
              <select id="bulan_filter" name="bulan_filter" class="form-control select2">
                <?php foreach ($rangeMonth as $key => $item) : ?>
                  <option value="<?php echo $key ?>" <?= ($key == date('m') ? "selected" : "") ?>>
                    <?php echo $item ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
            <div class="form-group">
              <label>Kode Distribusi Penerimaan</label>
              <select id="kode" name="kode" class="form-control select2">
                <option value="">--Pilih Kode Distribusi Penerimaan--</option>
                <?php foreach ($distriPenerimaan as $item) : ?>
                  <option value="<?= $item->id ?>"><?= $item->kode ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
            <div class="form-group">
              <label>Status</label>
              <select id="status" name="status" class="form-control select2">
                <option value="">--Pilih Status--</option>
                <option value="Open">Open</option>
                <option value="Close">Close</option>
              </select>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
            <div style="margin-top: 24px;">
              <button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> Filter</button>
              <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
            </div>
            <!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>