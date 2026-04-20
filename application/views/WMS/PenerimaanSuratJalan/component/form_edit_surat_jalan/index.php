<style>
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
                <h3 name="CAPTION-MENUEDITSURATJALAN">Menu Edit Data Surat Jalan</h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-success" id="edit-data"><i class="fas fa-save"></i> <label name="CAPTION-EDITDATA">Edit Data</label></button>
                <button type="button" class="btn btn-danger" id="kembali"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/form_edit_surat_jalan/form") ?>
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/form_edit_surat_jalan/list_sku") ?>

        <!-- modal list pilih sku edit -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_pilih_sku_edit/index") ?>

    </div>
</div>
<!-- /page content -->