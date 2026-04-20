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

    :root {
        --white: #ffffff;
        --light: #f0eff3;
        --black: #000000;
        --dark-blue: #1f2029;
        --dark-light: #353746;
        --red: #da2c4d;
        --yellow: #f8ab37;
        --green: #9ddf84;
    }

    #shape-placeholder_edit {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    input[name="tools"]:checked,
    input[name="tools"]:not(:checked) {
        position: absolute;
        left: -9999px;
        width: 0;
        height: 0;
        visibility: hidden;
    }

    #shape-placeholder_edit .checkbox-tools:checked+label,
    #shape-placeholder_edit .checkbox-tools:not(:checked)+label {
        /* padding: 10px; */
        position: absolute;
        white-space: normal;
        word-break: break-all;
        /* position: relative; */
        /* display: inline-block; */
        padding: 10px;
        /* width: 50%; */
        /* font-size: 14px; */
        /* line-height: 20px; */
        /* letter-spacing: 1px; */
        margin: 0 auto;
        margin-top: 5px;
        margin-left: 5px;
        margin-right: 5px;
        margin-bottom: 5px;
        text-align: center;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        text-transform: uppercase;
        -webkit-transition: all 300ms linear;
        transition: all 300ms linear;
    }

    #shape-placeholder_edit .checkbox-tools:not(:checked)+label {
        border: 1px solid var(--dark-light);
        background-color: var(--white);
        color: var(--dark-light);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #shape-placeholder_edit .checkbox-tools:checked+label {
        background-color: var(--green);
        border: 1px solid var(--dark-light);
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #shape-placeholder_edit .checkbox-tools:not(:checked)+label:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-EDITPENERIMAANBARANG">Edit Penerimaan Barang </h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-success" id="edit-data"><i class="fas fa-save"></i> <label name="CAPTION-EDITDATA">Edit Data</label></button>
                <button type="button" class="btn btn-dark" id="kembali-edit"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
            </div>
        </div>

        <div class="clearfix"></div>


        <!-- filter data -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_edit/header") ?>

        <!-- list data hasil dari filter -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_edit/detail") ?>

        <!-- tabel add pallet -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/form_edit/pallet") ?>

        <!-- modal add sku -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_tambah_sku_edit/index") ?>

        <!-- modal add sku -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_pilih_gudang_edit/index") ?>

    </div>
</div>
<!-- /page content -->