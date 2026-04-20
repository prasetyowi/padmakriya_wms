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
                <h3 name="CAPTION-VIEWDATASURATJALAN">View Data Surat Jalan</h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-dark" id="kembali_view"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/form_view/form") ?>
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/form_view/list_sku") ?>

    </div>
</div>
<!-- /page content -->