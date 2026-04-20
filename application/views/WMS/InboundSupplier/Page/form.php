<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>
<?php $this->load->view("WMS/InboundSupplier/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">
      <div class="text-center">
        <h3 style="font-weight: 700;"><?= empty($_GET['id']) ? 'Form Tambah Data' : (!empty($_GET['id']) ? 'Form Edit Data' : 'Form') ?></h3>
      </div>

      <div class="container" style="padding: 20px;">
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="noSuratJalan" name="CAPTION-NOSURATJALAN">No. Surat Jalan</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <!-- <input type="text" name="noSuratJalan" id="noSuratJalan" class="form-control" placeholder="No. Surat Jalan" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->penerimaan_surat_jalan_kode : '' ?>"> -->
              <select name="noSuratJalan" id="noSuratJalan" class="form-control select2">
                <option value="">--Pilih Surat Jalan--</option>
                <?php foreach ($suratJalan as $key => $value) { ?>
                  <option <?= isset($dataInboundSupplier) && $dataInboundSupplier['header']->penerimaan_surat_jalan_id == $value->penerimaan_surat_jalan_id ? 'selected' : '' ?> value="<?= $value->penerimaan_surat_jalan_id ?>"><?= $value->penerimaan_surat_jalan_kode ?> - <?= $value->client_wms_nama ?> [<?= $value->principle_kode ?>]</option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="principle" name="CAPTION-PRINCIPLE">Principle</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <select name="principle" id="principle" class="form-control select2" placeholder="Principle">
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
              <select name="ekspedisi" id="ekspedisi" class="form-control select2" placeholder="Principle">
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
              <input type="text" name="nopol" id="nopol" class="form-control" placeholder="Nopol" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_nopol : '' ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="namaDriver" name="CAPTION-DRIVER">Nama Driver</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="namaDriver" id="namaDriver" class="form-control" placeholder="Nama Driver" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_nama_driver : '' ?>">
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="noHandphone" name="CAPTION-NOTELEPON">No. HP</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="noHandphone" id="noHandphone" class="form-control numeric" placeholder="No. HP" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_no_hp : '' ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="tglTrukMasuk" name="CAPTION-TANGGALTRUKMASUK">Tgl. Truk Masuk</label>
              <input type="date" name="tglTrukMasuk" id="tglTrukMasuk" class="form-control" disabled placeholder="Tgl. Truk Masuk" value="<?= isset($dataInboundSupplier) ? date('Y-m-d', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : date('Y-m-d') ?>">
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="totalCatatanSJ" name="CAPTION-TOTALCATATANSJ">Total Catatan SJ</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <input type="text" name="totalCatatanSJ" id="totalCatatanSJ" class="form-control numeric" placeholder="Total Catatan SJ" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_total_ctn : '' ?>">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="jamTrukMasuk" name="CAPTION-JAMTRUKMASUK">Jam Truk Masuk</label>
              <div class='input-group date datetimepicker'>
                <input type='text' class="form-control" disabled id='jamTrukMasuk' name="jamTrukMasuk" placeholder="Jam Truk Masuk" value="<?= isset($dataInboundSupplier) ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_masuk)) : '' ?>" />
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
                <input type='text' class="form-control" disabled name="jamTrukKeluar" id="jamTrukKeluar" placeholder="Jam Truk Keluar" value="<?= isset($dataInboundSupplier) ? ($dataInboundSupplier['header']->security_logbook_tgl_keluar !== null ? date('Y-m-d H:i:s', strtotime($dataInboundSupplier['header']->security_logbook_tgl_keluar)) : '') : '' ?>" />
                <span class="input-group-addon" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="fileFoto" name="CAPTION-FILEFOTO">File Foto</label> <?= isset($dataInboundSupplier) ? '<span class="text-danger">*Abaikan jika tidak merubah</span>' : '' ?>
              <input type="file" name="fileFoto" id="fileFoto" class="form-control" onchange="previewFile(this)" accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF" multiple>
            </div>
          </div>
        </div>

        <div class="row show-file" style="margin-top: 20px">
          <?php if (isset($dataInboundSupplier)) { ?>
            <?php if (!empty($dataInboundSupplier['detail'])) { ?>
              <?php foreach ($dataInboundSupplier['detail'] as $key => $value) { ?>
                <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_<?= $key ?>" style="margin-bottom: 20px;">
                  <div class="img-view">
                    <div class="delete" onclick="handlerRemove('<?= $key ?>')" data-img="<?= $value->file ?>">X</div>
                    <img src="<?= base_url('assets/images/uploads/LogSecurity/' . $value->file) ?>" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">
                  </div>
                </div>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </div>

        <div class="row" style="margin-top:20px;padding:10px">
          <button type="button" class="btn btn-dark btn-sm" onclick="handlerBackData()"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
          <button type="button" class="btn btn-info btn-sm" onclick="handlerSaveData('<?= isset($dataInboundSupplier) ? 'edit' : 'add' ?>')"><i class="fas fa-save"></i> <label name="CAPTION-SAVE">Simpan</label></button>
          <?php if (isset($dataInboundSupplier)) { ?>
            <button type="button" class="btn btn-success btn-sm btn-konfirmasi" style="display: none;" onclick="handlerKonfirmasiData('<?= $dataInboundSupplier['header']->security_logbook_id ?>', 'edit')"><i class="fas fa-check"></i> <label name="CAPTION-KONFIRMASI">Konfirmasi</label></button>
          <?php } ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- /page content -->