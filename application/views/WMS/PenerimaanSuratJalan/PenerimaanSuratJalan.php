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
                <h3 name="CAPTION-MENUPENERIMAANSURATJALAN">Menu Penerimaan Surat Jalan</h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-primary" id="viewSL"><i class="fas fa-layer-group"></i> <label
                        name="CAPTION-VIEWSECURITYLOGBOOK">View Security Logbook</label></button>
                <button type="button" class="btn btn-primary" id="tambah-penerimaan"><i class="fas fa-plus"></i> <label
                        name="CAPTION-TAMBAHPENERIMAAN">Tambah Penerimaan</label></button>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- filter data -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/filterdata") ?>

        <!-- list data -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/listsuratjalan") ?>

        <!-- list data detail-->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/detaillistsuratjalan") ?>

        <!-- modal tambah penerimaan -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_tambah_penerimaan/index") ?>

        <!-- modal list pilih sku -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_pilih_sku/index") ?>

        <!-- modal list pilih sku -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_reason_cancel_sj/index") ?>

        <!-- modal split surat jalan -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_split/index") ?>

        <!-- modal split surat jalan -->
        <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_view_logbook_security/index") ?>

    </div>
</div>
<!-- /page content -->