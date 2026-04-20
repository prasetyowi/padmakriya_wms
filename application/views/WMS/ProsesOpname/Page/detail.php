<?php $this->load->view("WMS/ProsesOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main" id="parrent-opname">
  <div class="parrent-proses-opname hide-scrollbar">
    <div class="page-title child-proses-opname">

      <section id="list-detail-section-perintah-kerja">
        <div style="margin-top: 5px; margin-bottom: 7px">
          <button class="btn btn-dark btn-sm" onclick="handlerKembaliDetailData()"><i class="fas fa-arrow-left"></i> <span name="CAPTION-BACK">Kembali</span></button>
        </div>
        <h5 class="text-center">
          <strong id="title-type"></strong>
        </h5>
        <div id="showDataDetail"></div>
      </section>

    </div>
  </div>
</div>

<!-- /page content -->