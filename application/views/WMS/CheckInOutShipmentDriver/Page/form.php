<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>
<?php $this->load->view("WMS/CheckInOutShipmentDriver/Component/Script/Style/index") ?>

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
              <label for="nopol">NOPOL</label>
              <input type="hidden" name="isConfirmDeparture" id="isConfirmDeparture" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->is_confirm_departure : '' ?>">
              <select name="nopol" id="nopol" class="form-control select2" placeholder="Nopol" onchange="handlerNopolSelected(this.value)">
                <option value="">-Pilih Nopol--</option>
                <?php foreach ($getNopols as $key => $value) { ?>
                  <?php if (isset($dataInboundSupplier)) { ?>
                    <?php if ($dataInboundSupplier['header']->kendaraan_id == $value->kendaraan_id) { ?>
                      <option value="<?= $value->kendaraan_id ?>,<?= $dataInboundSupplier['header']->karyawan_id ?>" selected><?= $value->kendaraan_nopol ?></option>
                    <?php } else { ?>
                      <option value="<?= $value->kendaraan_id ?>,<?= $dataInboundSupplier['header']->karyawan_id ?>"><?= $value->kendaraan_nopol ?></option>
                    <?php } ?>
                  <?php } else { ?>
                    <option value="<?= $value->kendaraan_id ?>,null"><?= $value->kendaraan_nopol ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="namaDriver">Nama Driver</label> <span class="text-danger" style="font-size: 20px;">*</span>
              <select name="namaDriver" id="namaDriver" class="form-control select2" placeholder="driver" onchange="handlerDriverSelected(this.value)">
                <option value="">-Pilih Driver--</option>
              </select>
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
              <input type="text" name="kmKeberangkatan" id="kmKeberangkatan" class="form-control numeric" placeholder="Km Keberangkatan" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_km_berangkat : '' ?>">
            </div>
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
              <label for="kmKembali">Km Kembali</label>
              <input type="text" name="kmKembali" id="kmKembali" class="form-control numeric" placeholder="Km Kembali" value="<?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_km_kembali : '' ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="keterangan">Keterangan</label>
              <textarea name="keterangan" id="keterangan" cols="3" rows="3" class="form-control"><?= isset($dataInboundSupplier) ? $dataInboundSupplier['header']->security_logbook_keterangan : '' ?></textarea>
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

        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
              <label for="fileFoto">File Foto</label> <?= isset($dataInboundSupplier) ? '<span class="text-danger">*Abaikan jika tidak merubah</span>' : '' ?>
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
          <button type="button" class="btn btn-dark btn-sm" onclick="handlerBackData()"><i class="fas fa-arrow-left"></i> Kembali</button>
          <button type="button" class="btn btn-info btn-sm" onclick="handlerSaveData('<?= isset($dataInboundSupplier) ? 'edit' : 'add' ?>')"><i class="fas fa-save"></i> Simpan</button>
          <?php if (isset($dataInboundSupplier)) { ?>
            <?php if ($dataInboundSupplier['header']->is_confirm_departure != 1) { ?>
              <button type="button" class="btn btn-primary btn-sm btn-konfirmasiKeberangkatan" onclick="handlerKonfirmasiBerangkat('<?= $dataInboundSupplier['header']->security_logbook_id ?>', 'edit')"><i class="fa-solid fa-truck-fast"></i> Konfirmasi Keberangkatan</button>
              <!-- <button type="button" class="btn btn-success btn-sm btn-konfirmasi" onclick="handlerKonfirmasiData('<?= $dataInboundSupplier['header']->security_logbook_id ?>', 'edit')"><i class="fas fa-check"></i> Konfirmasi</button> -->
            <?php } ?>
          <?php } ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- /page content -->