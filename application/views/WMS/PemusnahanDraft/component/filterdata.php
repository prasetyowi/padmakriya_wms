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
              <label name="CAPTION-NODRAFTPEMUSNAHANSTOCK">No Draft pemusnahan Stok</label>
              <select id="filter_no_pemusnahan_draft" name="filter_no_pemusnahan_draft" class="form-control select2">
                <option value="">--ALL--</option>
                <!-- <?php foreach ($pemusnahan_draft as $pemusnahan) { ?>
                  <option value="<?= $pemusnahan->id ?>"><?= $pemusnahan->kode ?></option>
                <?php } ?> -->
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-TANGGAL">Tanggal</label>
              <input type="date" class="form-control" name="filter_pemusnahan_draft_tgl_draft" id="filter_pemusnahan_draft_tgl_draft" value="<?= date('Y-m-d'); ?>" required>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-GUDANG">Gudang</label>
              <select id="filter_gudang_asal_pemusnahan_draft" name="filter_gudang_asal_pemusnahan_draft" class="form-control select2">
                <option value="">--ALL--</option>
                <?php foreach ($Gudang as $row) { ?>
                  <option value="<?= $row['depo_detail_id']; ?>"><?= $row['depo_detail_nama']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
              <select id="filter_pemusnahan_draft_tipe_transaksi" name="filter_pemusnahan_draft_tipe_transaksi" class="form-control select2">
                <option value="">--ALL--</option>
                <?php foreach ($TipeTransaksi as $row) { ?>
                  <option value="<?= $row['tipe_mutasi_id']; ?>"><?= $row['tipe_transaksi']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-PRINCIPLE">Principle</label>
              <select id="filter_pemusnahan_draft_principle" name="filter_pemusnahan_draft_principle" class="form-control select2">
                <option value="">--ALL--</option>
                <?php foreach ($Principle as $row) { ?>
                  <option value="<?= $row['principle_id']; ?>"><?= $row['principle_kode']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-CHECKER">Checker</label>
              <select id="filter_pemusnahan_draft_checker" name="filter_pemusnahan_draft_checker" class="form-control select2">
                <option value="">--ALL--</option>
                <?php foreach ($Checker as $row) { ?>
                  <option value="<?= $row['karyawan_nama']; ?>"><?= $row['karyawan_nama']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-STATUS">Status</label>
              <select name="filter_pemusnahan_draft_status" id="filter_pemusnahan_draft_status" class="form-control select2">
                <option value="">--ALL--</option>
                <option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
                <option value="In Progress Approval"><label name="CAPTION-INPROGRESSAPPROVAL">In Progress Approval</label></option>
                <option value="Approved"><label name="CAPTION-APPROVAL">Approved</label></option>
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