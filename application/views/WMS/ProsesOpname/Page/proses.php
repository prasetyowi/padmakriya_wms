<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">

      <section id="list-detail-section-perintah-kerja">
        <div style="margin-top: 5px; margin-bottom: 7px">
          <button class="btn btn-dark btn-sm" onclick="handlerKembaliDetailData()"><i class="fas fa-arrow-left"></i> <span name="CAPTION-BACK">Kembali</span></button>
          <button type="button" class="btn btn-success btn-sm"><i class="fas fa-check"></i> <span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>
        </div>
        <div id="showProsesData">
          <div class="card-detail-opname">
            <table width="100%">
              <tbody>
                <tr>
                  <td width="39%"><label name="CAPTION-KODEDOKUMEN">Kode Dokumen</label> </td>
                  <td width="2%" class="text-center">:&nbsp;</td>
                  <td width="59%"><?= $header->kode ?></td>
                </tr>
                <tr>
                  <td><label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label> </td>
                  <td class="text-center">:&nbsp;</td>
                  <td><?= $header->tipe_stock ?></td>
                </tr>
                <tr>
                  <td><label name="CAPTION-PT">Perusahaan</label></td>
                  <td class="text-center">:&nbsp;</td>
                  <td><?= $header->perusahaan ?></td>
                </tr>
                <tr>
                  <td><label name="CAPTION-PRINCIPLE">principle</label></td>
                  <td class="text-center">:&nbsp;</td>
                  <td><?= $header->principle ?></td>
                </tr>
                <tr>
                  <td><label name="CAPTION-JENISSTOCK">Jenis Stock</label></td>
                  <td class="text-center">:&nbsp;</td>
                  <td><?= $header->jenis_stok ?></td>
                </tr>
                <tr>
                  <td><label name="CAPTION-STATUS">Status</label></td>
                  <td class="text-center">:&nbsp;</td>
                  <td><?= $header->status ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <h5 class="text-center">
          <strong><span name="CAPTION-AREAOPNAME">Area Opname</span> : <?= $header->area ?></strong>
        </h5>

        <h6 class="text-center" name="CAPTION-AREAOPNAMEDETAIL">
          Detail Area Opname
        </h6>

        <div style="margin-top: 15px;">
          <?php foreach ($detail as $key => $data) { ?>
            <h5 class="title-proses-data"><?= $key ?></h5>
            <?php foreach ($data['data'] as $value) { ?>
              <div class="parent-data-proses">
                <div class="child-parent-data-proses">
                  <h6><?= $value['rak_lajur_detail_nama'] ?></h6>
                  <h6 class="statusOpnameDetail"><?= $value['status'] ?></h6>
                  <?php if ($value['status'] == "In Progress") { ?>
                    <button class="btn btn-primary btn-sm" onclick="handlerProsesOpnameByRak('<?= $_GET['prosesId'] ?>','<?= $value['id'] ?>', '<?= $value['rak_lajur_detail_nama'] ?>', '<?= $value['status'] ?>', '<?= $header->depo_detail_id ?>')"><label name="CAPTION-PROSES">Proses</label></button>
                  <?php } else { ?>
                    <button class="btn btn-primary btn-sm" disabled><label name="CAPTION-PROSES">Proses</label></button>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          <?php } ?>

        </div>
      </section>
    </div>
  </div>
</div>

<!-- /page content -->