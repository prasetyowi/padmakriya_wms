<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">
      <div class="text-center">
        <h3 style="font-weight: 700;">Check In / Out Driver</h3>
      </div>
      <div style="width:100%">
        <a href="<?= base_url('WMS/CheckInOutShipmentDriver/add') ?>" class="btn btn-sm btn-primary" style="float:right;margin-right:20px"><i class="fas fa-plus"></i> <label>Add Data</label></a>
      </div>

      <div style="margin-top: 10vh;">
        <div class="panel panel-default">
          <div class="panel-heading">Filter Data</div>
          <div class="panel-body">
            <div class="container">
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <label>Tgl Masuk</label>
                    <input type="text" id="filterTglMasuk" class="form-control input-sm datepicker" name="filterTglMasuk" autocomplete="off">
                  </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <label>Kode</label>
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
                      <td width="15%"><strong><label name="CAPTION-KODEKELAS">Kode</label></strong></td>
                      <td width="15%"><strong><label name="CAPTION-DRIVER">Driver</label></strong></td>
                      <td width="15%"><strong><label name="CAPTION-NOKENDARAAN">No. Kendaraan</label></strong></td>
                      <td width="20%"><strong><label name="CAPTION-TANGGALKELUAR">Tanggal keluar</label></strong></td>
                      <td width="20%"><strong><label name="CAPTION-TANGGALMASUK">Tanggal Masuk</label></strong></td>
                      <td width="20%"><strong><label name="CAPTION-AKSI">Aksi</label></strong></td>
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

<div class="modal fade" id="modalAddKmKembali" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Tambah KM Kembali</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="kmKembaliModal">Km Kembali</label>
          <input type="text" name="kmKembaliModal" id="kmKembaliModal" class="form-control numeric" placeholder="Km Kembali">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btnKonfirmasi" onclick="handlerKonfirmasiDriverShipment(event)"><i class="fas fa-check"></i> Konfirmasi</button>
        <button type="button" class="btn btn-dark" onclick="handlerCloseKonfirmasiDriverShipment(event)"><i class="fas fa-xmark"></i> kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- /page content -->
