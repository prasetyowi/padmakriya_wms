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
        <h3 name="CAPTION-DRAFTBERITAACARAPEMUSNAHANATAURETUR">Draft Berita Acara Pemusnahan / Retur</h3>
      </div>
      <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
    </div>

    <div class="clearfix"></div>

    <!-- filter data tambah -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/form_tambah_data/filter") ?>

    <!-- list data tambah -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/form_tambah_data/list_filter") ?>

    <!-- modal pilih sku -->
    <?php $this->load->view("WMS/PemusnahanDraft/component/modal_pilih_sku/index") ?>

  </div>
</div>
<!-- /page content -->