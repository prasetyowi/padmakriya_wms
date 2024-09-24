<style>
  :root {
    --white: #ffffff;
    --light: #f0eff3;
    --black: #000000;
    --dark-blue: #1f2029;
    --dark-light: #353746;
    --red: #da2c4d;
    --yellow: #f8ab37;
    --grey: #ecedf3;
  }

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
        <h3 name="CAPTION-MENUEDITKOREKSISTOCKBARANG">Menu Edit Koreksi Stok Barang</h3>
      </div>
      <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
    </div>

    <div class="clearfix"></div>

    <!-- filter data edit -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/form_edit_data/filter") ?>

    <!-- list data edit -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/form_edit_data/list_filter") ?>

    <!-- modal pilih sku -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/modal_pilih_sku_edit/index") ?>

  </div>
</div>
<!-- /page content -->