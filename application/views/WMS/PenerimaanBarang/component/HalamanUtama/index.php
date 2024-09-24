<style>
  .modal-body {
    max-height: calc(100vh - 210px);
    overflow-x: auto;
    overflow-y: auto;
  }

  .error {
    border: 1px solid red;
  }

  .alert-header {
    display: flex;
    flex-direction: row;
  }

  .alert-header .alert-icon {
    margin-right: 10px;
  }

  .span-example .alert-header .alert-icon {
    align-self: center;
  }
</style>
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3 name="CAPTION-MENUPENERIMAANBARANG">Menu Penerimaan Barang</h3>
      </div>
      <div style="float: right">
        <button type="button" class="btn btn-primary" id="tambah_penerimaan"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHPENERIMAAN">Tambah Penerimaan</label></button>
      </div>
    </div>

    <div class="clearfix"></div>

    <!-- filter data -->
    <?php $this->load->view("WMS/PenerimaanBarang/component/HalamanUtama/filterdata") ?>

    <!-- list data -->
    <?php $this->load->view("WMS/PenerimaanBarang/component/HalamanUtama/listdata") ?>

    <!-- modal pilih sj -->
    <?php $this->load->view("WMS/PenerimaanBarang/component/modal_pilih_sj/index") ?>

    <!-- modal add sku -->
    <?php $this->load->view("WMS/PenerimaanBarang/component/modal_konfirmasi/index") ?>

  </div>
</div>
<!-- /page content -->