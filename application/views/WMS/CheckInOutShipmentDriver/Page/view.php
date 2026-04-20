<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>
<?php $this->load->view("WMS/InboundSupplier/Component/Script/Style/index") ?>


<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">
      <div class="text-center">
        <h3 style="font-weight: 700;">View Data</h3>
      </div>

      <div class="container" style="padding: 20px;">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="nopol">NOPOL</label>
              <input type="text" name="nopol" id="nopol" class="form-control" placeholder="Nopol" value="<?= $dataInboundSupplier['header']->kendaraan_nopol ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="namaDriver">Nama Driver</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="namaDriver" id="namaDriver" class="form-control" placeholder="Nopol" value="<?= $dataInboundSupplier['header']->karyawan_nama ?>" disabled>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="noHandphone">No. HP</label>
              <input type="text" name="noHandphone" id="noHandphone" class="form-control numeric" placeholder="No. HP" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_no_hp : '' ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="tglTrukKeberangkatan">Tgl. Truk Keberangakatan</label>
              <input type="date" name="tglTrukKeberangkatan" id="tglTrukKeberangkatan" class="form-control" disabled placeholder="Tgl. Truk Masuk" value="<?= isset($dataInboundSupplier) ? date('Y-m-d', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : date('Y-m-d') ?>">
            </div>
          </div>
        </div>
        <div class="row">

          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="jamTrukKeberangkatan">Jam Truk Keberangkatan</label>
              <div class='input-group date datetimepicker'>
                <input type='text' class="form-control" disabled name="jamTrukKeberangkatan" id="jamTrukKeberangkatan" placeholder="Jam Truk Keberangkatan" value="<?= isset($dataInboundSupplier) ? ($dataInboundSupplier['header']->security_logbook_tgl_masuk !== null ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : '') : '' ?>" />
                <span class="input-group-addon" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="jamTrukKeluar">Jam Truk Kembali</label>
              <div class='input-group date datetimepicker'>
                <input type='text' class="form-control" disabled id='jamTrukKeluar' name="jamTrukKeluar" placeholder="Jam Truk Kembali" value="<?= isset($dataInboundSupplier) ? ($dataInboundSupplier['header']->security_logbook_tgl_keluar !== null ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_keluar)) : '') : '' ?>" />
                <span class="input-group-addon" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="kmKeberangkatan">Km Keberangkatan</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="kmKeberangkatan" id="kmKeberangkatan" class="form-control numeric" placeholder="Km Keberangkatan" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_km_berangkat : '' ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="kmKembali">Km Kembali</label>
              <input type="text" name="kmKembali" id="kmKembali" class="form-control numeric" placeholder="Km Kembali" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_km_kembali : '' ?>" disabled>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea name="keterangan" id="keterangan" cols="3" rows="3" class="form-control" disabled><?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_keterangan : '' ?></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div id="initListDeliveryOrderBatch">
            <?php if (isset($dataInboundSupplier)) { ?>
              <?php if (!empty($dataInboundSupplier['detail2'])) { ?>
                <h4 style="margin-left: 10px;">List Surat Tugas Pengiriman</h4>
                <?php foreach ($dataInboundSupplier['detail2'] as $key => $value) { ?>
                  <div class="col-xl-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="listDeliveryOrderBatch" data-id="<?= $value->id ?>" style="background: #1e293b;color:white;text-align: center;padding: 10px;border: 1px solid #2A3F54;border-radius: 10px 10px;margin-bottom: 10px;font-weight:700"><?= $value->kode ?></div>
                  </div>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </div>
        </div>

        <div class="row show-file" style="margin-top: 20px">
          <?php if (isset($dataInboundSupplier)) { ?>
            <?php if (!empty($dataInboundSupplier['detail'])) { ?>
              <h4 style="margin-left: 10px;">Dokumen</h4>
              <?php foreach ($dataInboundSupplier['detail'] as $key => $value) { ?>
                <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_<?= $key ?>" style="margin-bottom: 20px;">
                  <div class="img-view">
                    <img src="<?= base_url('assets/images/uploads/LogSecurity/' . $value->file) ?>" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>

        <div class="row" style="margin-top:20px;padding:10px">
          <button type="button" class="btn btn-dark btn-sm" onclick="handlerBackData()"><i class="fas fa-arrow-left"></i> Kembali</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- /page content -->