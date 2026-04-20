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
              <label for="noSuratJalan" name="CAPTION-NOSURATJALAN">No. Surat Jalan</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="noSuratJalanView" id="noSuratJalanView" class="form-control" placeholder="No. Surat Jalan" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->penerimaan_surat_jalan_kode : '' ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="principle" name="CAPTION-PRINCIPLE">Principle</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <select name="principleView" id="principleView" class="form-control select2" placeholder="Principle" disabled>
                <option value="">-Pilih Principle--</option>
                <?php foreach ($principle as $key => $value) { ?>
                  <?php if (isset($dataInboundSupplier)) { ?>
                    <?php if ($dataInboundSupplier['header']->principle_id == $value->principle_id) { ?>
                      <option value="<?= $value->principle_id ?>" selected><?= $value->principle_kode ?></option>
                    <?php } else { ?>
                      <option value="<?= $value->principle_id ?>"><?= $value->principle_kode ?></option>
                    <?php } ?>
                  <?php } else { ?>
                    <option value="<?= $value->principle_id ?>"><?= $value->principle_kode ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="ekspedisi" name="Nama Ekspedisi">Nama Ekspedisi</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <select name="ekspedisiView" id="ekspedisiView" class="form-control select2" placeholder="Principle" disabled>
                <option value="">-Pilih Ekspedisi--</option>
                <?php foreach ($ekspedisi as $key => $value) { ?>
                  <?php if (isset($dataInboundSupplier)) { ?>
                    <?php if ($dataInboundSupplier['header']->ekspedisi_id == $value->ekspedisi_id) { ?>
                      <option value="<?= $value->ekspedisi_id ?>" selected><?= $value->ekspedisi_kode ?> - <?= $value->ekspedisi_nama ?></option>
                    <?php } else { ?>
                      <option value="<?= $value->ekspedisi_id ?>"><?= $value->ekspedisi_kode ?> - <?= $value->ekspedisi_nama ?></option>
                    <?php } ?>
                  <?php } else { ?>
                    <option value="<?= $value->ekspedisi_id ?>"><?= $value->ekspedisi_kode ?> - <?= $value->ekspedisi_nama ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="nopol" name="CAPTION-NOPOLISI">NOPOL</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="nopolView" id="nopolView" class="form-control" placeholder="Nopol" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_nopol : '' ?>" disabled>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="namaDriver" name="CAPTION-DRIVER">Nama Driver</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="namaDriverView" id="namaDriverView" class="form-control" placeholder="Nama Driver" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_nama_driver : '' ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="noHandphone" name="CAPTION-NOTELEPON">No. HP</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="noHandphoneView" id="noHandphoneView" class="form-control numeric" placeholder="No. HP" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_no_hp : '' ?>" disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="tglTrukMasuk" name="CAPTION-TANGGALTRUKMASUK">Tgl. Truk Masuk</label>
              <input type="date" name="tglTrukMasukView" id="tglTrukMasukView" class="form-control" disabled placeholder="Tgl. Truk Masuk" value="<?= isset($dataInboundSupplier) ? date('Y-m-d', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : date('Y-m-d') ?>" disabled>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="totalCatatanSJ" name="CAPTION-TOTALCATATANSJ">Total Catatan SJ</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="totalCatatanSJView" id="totalCatatanSJView" class="form-control numeric" placeholder="Total Catatan SJ" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_total_ctn : '' ?>" disabled>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="jamTrukMasuk" name="CAPTION-JAMTRUKMASUK">Jam Truk Masuk</label>
              <div class='input-group date datetimepicker'>
                <input type='text' class="form-control" disabled id='jamTrukMasukView' name="jamTrukMasukView" placeholder="Jam Truk Masuk" value="<?= isset($dataInboundSupplier) ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : '' ?>" disabled />
                <span class="input-group-addon" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="jamTrukKeluar" name="CAPTION-JAMTRUKKELUAR">Jam Truk Keluar</label>
              <div class='input-group date datetimepicker'>
                <input type='text' class="form-control" disabled name="jamTrukKeluarView" id="jamTrukKeluarView" placeholder="Jam Truk Keluar" value="<?= isset($dataInboundSupplier) ? ($dataInboundSupplier['header']->security_logbook_tgl_keluar !== null ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_keluar)) : '') : '' ?>" disabled />
                <span class="input-group-addon" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="fileFoto" name="CAPTION-FILEFOTO">File Foto</label>
              <div class="row show-file" style="margin-top: 20px">
                <?php if (!empty($dataInboundSupplier['detail'])) { ?>
                  <?php foreach ($dataInboundSupplier['detail'] as $key => $value) { ?>
                    <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_<?= $key ?>" style="margin-bottom: 20px;">
                      <div class="img-view">
                        <img src="<?= base_url('assets/images/uploads/LogSecurity/' . $value->file) ?>" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">
                      </div>
                    </div>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>



        <div class="row" style="margin-top:20px;padding:10px">
          <button type="button" class="btn btn-dark btn-sm" onclick="handlerBackData()"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- /page content -->