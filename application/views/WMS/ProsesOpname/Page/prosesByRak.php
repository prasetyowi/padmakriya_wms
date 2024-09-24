<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">

      <section id="list-detail-proses-section-perintah-kerja">
        <div style="margin-top: 5px; margin-bottom: 10px">
          <button class="btn btn-dark btn-sm" onclick="handlerkembaliDetailProses()"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
          <div style="float:right">
            <button type="button" class="btn btn-success btn-sm" onclick="handlerKonfirmasiProsesOpname()"><label name="CAPTION-KONFIRMASISELESAIOPNAME">Konfirmasi Selesai Opname</label></button>
          </div>
        </div>

        <input type="hidden" id="prosesId" value="<?= $_GET['prosesId'] ?>">
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
              </tbody>
            </table>
          </div>
        </div>

        <div class="text-center" style="margin-top: 15px; margin-bottom: 15px;">
          <h4><strong><label name="CAPTION-STATUSOPNAME">Status Opname</label> : <p style="display:inline-block" id="statusProsesOpnameDetail"></p></strong></h4>
        </div>

        <div class="card-list-opname-detail">
          <div class="row">
            <div class="col-md-6 col-sm-6">
              <h5 class="title-proses-data">List Opname Detail</h5>
            </div>
            <div class="col-md-6 col-sm-6">
              <div style="float: right">
                <input type="checkbox" onclick="chkAllBtnDel(this)" id="chkAllBtnDel" style="transform: scale(1.5);">
                <label for=""> Action</label>
              </div>
            </div>
          </div>
          <div class="parent-data-proses">
            <?php foreach ($detail as $key => $data) { ?>
              <div class="child-parent-data-proses table-responsive check" style="border: none" id="data-<?= $data['tr_opname_plan_detail_id'] ?>">
                <h6 class="number"><?= $key + 1 ?></h6>
                <h6><?= $data['tr_opname_plan_kode'] ?></h6>
                <h6><?= $data['rak_lajur_detail_nama'] ?></h6>
                <h6 class="statusOpnameDetail"><?= $data['status'] ?></h6>
                <button class="btn btn-danger btnDeleteOpnameDetail" data-status="<?= $data['status'] ?>" onclick="btnDeleteOpnameDetailRow('<?= $data['tr_opname_plan_detail_id'] ?>')" disabled id="btnDeleteOpnameDetail"><i class="fas fa-trash"></i></button>
              </div>
            <?php } ?>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-lg-3 col-xl-3 col-sm-3 col-xs-3" style="align-self: center;" name="Scan Lokasi">Scan Lokasi</label>
          <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7 col-xs-6">
            <input type="text" id="scanLokasiRak" class="form-control" onclick="handlerOpenModalScan('lokasi', null, null)" autocomplete="off">
          </div>
          <div class="col-md-2 col-lg-2 col-lg-2 col-sm-2 col-xs-3" id="statusScanLokasi"></div>
        </div>

        <!-- <div class="form-group row">
          <label class="col-md-3 col-lg-3 col-xl-3 col-sm-3 col-xs-3" name="CAPTION-SCANPALLET">Scan Pallet</label>
          <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7 col-xs-6">
            <input type="text" id="scanLokasiPallet" class="form-control" onclick="handlerOpenModalScan('pallet', null, null)" disabled autocomplete="off">
          </div>
          <div class="col-md-2 col-lg-2 col-lg-2 col-sm-2 col-xs-3" id="statusScanPallet">
          </div>
        </div> -->

        <div style="padding: 10px;border:1px solid gray">

          <button class="btn btn-info btn-sm" id="addNewPallet" onclick="handlerOpenModalScan('newPallet', null, null)"><i class="fas fa-plus"></i>&nbsp;<label name="CAPTION-TAMBAHPALLETBARU">Tambah Pallet Baru</label></button>

          <div class="table-responsive">
            <table class="table table-xs table-striped table-bordered" id="initDataPalletScan" style="font-size:11px;">
              <thead>
                <tr class="text-center">
                  <td width="5%"><strong><label name="CAPTION-NO">No.</label></strong></td>
                  <td width="15%"><strong><label name="CAPTION-PALLET">Pallet</label></strong></td>
                  <td width="7%"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                  <td width="10%"><strong><label name="CAPTION-STATUSOPNAME">Status Opname</label></strong></td>
                  <td width="10%"><strong>Total SKU</strong></td>
                  <td width="7%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>

          <!-- <button class="btn btn-info btn-sm" style="margin-top: 15px;" id="addNewSku" onclick="handlerAddNewSkuInPalletDetail()"><i class="fas fa-plus"></i>&nbsp;<label name="CAPTION-TAMBAHSKU">Tambah SKU</label></button> -->
          <button class="btn btn-info btn-sm" style="margin-top: 15px;" id="addNewSku" onclick="handlerOpenModalScan('addScanSku', null, null)"><i class="fas fa-plus"></i>&nbsp;<label name="CAPTION-TAMBAHSKU">Tambah SKU</label></button>
          <!-- <button class="btn btn-warning btn-sm" style="margin-top: 15px;" name="selectSplit" value="0" class="chkTypeScan" id="addScanSku" data-type="scanSku" onclick="handlerOpenModalScan('addScanSku', null, null)"><i class="fas fa-search"></i>&nbsp;<label name="CAPTION-SCANSKU">Scan SKU</label></button> -->

          <h5 id="palletKodeData"></h5>

          <div class="table-responsive" style="margin-top:15px">
            <table class="table table-xs table-striped table-bordered " width="100%" id="initDataPalletDetailScan" style="font-size:11px;">
              <thead>
                <tr class="text-center">
                  <td scope="col"><strong><label name="CAPTION-NO">No.</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-SKUNAMA">Nama SKU</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                  <td scope="col"><strong><label>Batch No</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-TANGGALEXPIRED">Expired Date</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-AKTUALQTY">Aktual Qty</label></strong></td>
                  <td scope="col"><strong><label>Barcode</label></strong></td>
                  <td scope="col"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </section>
      <section>
        <div style="float:right">
          <!-- <button type="button" style="display: none" class="btn btn-success btn-sm" id="btnKonfirmasiProsesOpnameByRak" onclick="handlerKonfirmasiProsesOpnameByRak(event)"><label name="CAPTION-SELESAI1LOKASI">Selesai 1 Lokasi</label></button> -->
          <?php
          $count = 1;
          foreach ($detail as $key => $data) {
            if ($data['status'] == "Completed") {
              $count = $count + 1;
            }
          }
          ?>
          <button type="button" style="display: none" class="btn btn-success btn-sm" id="btnKonfirmasiProsesOpnameByRak" onclick="handlerKonfirmasiProsesOpnameByRak(event)"><label>Selesai <?= $count == 1 ? '1' : strval($count) ?> Lokasi</label></button>
          <button type="button" class="btn btn-primary btn-sm" id="btnSimpanProsesOpnameByRak" onclick="handlerSimpanProsesOpnameByRak(event)"><label name="CAPTION-SIMPAN">Simpan</label></button>
        </div>
      </section>
    </div>
  </div>
</div>


<!-- load modal -->
<?php $this->load->view("WMS/ProsesOpname/Component/Modal/scan") ?>
<?php $this->load->view("WMS/ProsesOpname/Component/Modal/sku") ?>

<!-- /page content -->