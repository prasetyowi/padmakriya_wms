<?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/style") ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3 name="CAPTION-MENUKONFIRMASIDISTRIBUSIPENERIMAANBARANG">Menu Konfirmasi Distribusi Penerimaan Barang</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <!-- filter data -->
    <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/HalamanUtama/filterdata") ?>

    <!-- list data -->
    <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/HalamanUtama/listdata") ?>

  </div>
</div>
<!-- /page content -->