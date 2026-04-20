<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">
      <div id="title-user-login">
        <p><span name="CAPTION-HELLO">Hai</span>, <strong><?= $userLogin->karyawan_nama ?></strong></p>
        <p><span name="CAPTION-ANDATERDAFTARSEBAGAI">Anda terdaftar sebagai</span> <strong><?= $userLogin->karyawan_level_nama ?></strong>, <span name="CAPTION-LOKASIUNITKERJA">Lokasi unit kerja</span> <strong><?= $userLogin->depo_nama ?></strong></p>
      </div>

      <!-- list table surat kerja -->
      <?php $this->load->view("WMS/ProsesOpname/Component/Section/listTableSuratKerja") ?>

      <!-- list table surat kerja -->
      <?php $this->load->view("WMS/ProsesOpname/Component/Section/listDataSuratKerja") ?>

    </div>
  </div>
</div>

<!-- /page content -->