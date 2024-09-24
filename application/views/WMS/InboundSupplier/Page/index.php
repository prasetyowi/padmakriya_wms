<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">
      <div class="text-center">
        <h3 style="font-weight: 700;" name="CAPTION-138003000">Inbound Supplier</h3>
      </div>
      <div style="width:100%">
        <a href="<?= base_url('WMS/SecurityLogBook/InboundSupplier/add') ?>" class="btn btn-sm btn-primary" style="float:right;margin-right:20px"><i class="fas fa-plus"></i> <label>Add Data</label></a>
      </div>

      <div style="margin-top: 10vh;">
        <div class="panel panel-default">
          <div class="panel-heading" name="Filter Data">Filter Data</div>
          <div class="panel-body">
            <div class="container">
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <label name="CAPTION-TANGGALMASUK">Tgl Masuk</label>
                    <input type="text" id="filterTglMasuk" class="form-control input-sm datepicker" name="filterTglMasuk" autocomplete="off">
                  </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <label name="CAPTION-KODEKELAS">Kode</label>
                    <select name="kodeLogSecurity" id="kodeLogSecurity" class="form-control select2">
                      <option value="">--Pilih Kode Inbound Supplier--</option>
                      <?php foreach ($kodeInboundSup as $value) { ?>
                        <option value="<?= $value->kode ?>"><?= $value->kode ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button type="button" class="btn btn-info btn-md" onclick="handlerFilterData()"><i class="fas fa-search"></i> Filter</button>
                  <span id="loadingfilter" style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;<label name="CAPTION-LOADING">Loading</label>...</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-body">
            <div class="container">
              <div class="table-responsive">
                <table class="table table-striped" id="dataInboundSupplier">
                  <thead class="bg-primary">
                    <tr class="text-center">
                      <td width="7%"><strong>#</strong></td>
                      <td width="15%"><strong><label name="CAPTION-KODEKELAS">Kode</label> </strong></td>
                      <td width="20%"><strong><label name="CAPTION-PRINCIPLE">Principle</label> </strong></td>
                      <td width="20%"><strong><label name="CAPTION-TANGGALMASUK">Tanggal Masuk</label> </strong></td>
                      <td width="20%"><strong><label name="CAPTION-TANGGALKELUAR">Tanggal keluar</label> </strong></td>
                      <td width="13%"><strong>Action</strong></td>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- /page content -->