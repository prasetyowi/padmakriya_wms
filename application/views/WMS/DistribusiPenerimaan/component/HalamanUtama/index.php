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
        <h3>Menu Distribusi Penerimaan Barang</h3>
      </div>
      <div style="float: right">
        <a href="<?= base_url('WMS/DistribusiPenerimaan/tambah') ?>" class="btn btn-primary" id="tambah_distribusi_penerimaan"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan</a>
      </div>
    </div>

    <div class="clearfix"></div>

    <!-- filter data -->
    <?php $this->load->view("WMS/DistribusiPenerimaan/component/HalamanUtama/filterdata") ?>

    <!-- list data -->
    <?php $this->load->view("WMS/DistribusiPenerimaan/component/HalamanUtama/listdata") ?>

  </div>
</div>
<!-- /page content -->