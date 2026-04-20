<style>
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-x: auto;
        overflow-y: auto;
    }

    .error {
        border: 1px solid red !important;
    }

    .error .select2-selection {
        border: 1px solid red !important;
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
                <h3 name="CAPTION-VIEWPENERIMAANBARANG">View Penerimaan Barang </h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-dark" id="kembali_view"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
            </div>
        </div>

        <div class="clearfix"></div>


        <!-- filter data -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_view/header") ?>

        <!-- list data hasil dari filter -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_view/detail") ?>

        <!-- tabel add pallet -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_view/pallet") ?>

    </div>
</div>
<!-- /page content -->