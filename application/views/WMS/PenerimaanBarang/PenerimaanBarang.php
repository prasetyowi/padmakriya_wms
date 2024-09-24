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

    .active-warehouse {
        background-color: green;
        color: #fff;
    }

    :root {
        --white: #ffffff;
        --light: #f0eff3;
        --black: #000000;
        --dark-blue: #1f2029;
        --dark-light: #353746;
        --grey: #4D4C4C;
        --yellow: #f8ab37;
        --green: #9ddf84;
    }

    #shape-placeholder {
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

    #shape-placeholder .checkbox-tools:checked+label,
    #shape-placeholder .checkbox-tools:not(:checked)+label {
        position: absolute;
        white-space: normal;
        word-break: break-all;
        padding: 10px;
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

    #shape-placeholder .checkbox-tools:not(:checked)+label {
        border: 1px solid var(--dark-light);
        background-color: var(--white);
        color: var(--dark-light);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #shape-placeholder .checkbox-tools[data-active=true]+label {
        border: 1px solid var(--dark-light);
        background-color: var(--light);
        color: var(--dark-light) !important;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #shape-placeholder .checkbox-tools[data-active=false]+label {
        border: 1px solid var(--dark-light);
        background-color: var(--grey);
        color: var(--light) !important;
        cursor: not-allowed !important;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #shape-placeholder .checkbox-tools:checked+label {
        background-color: var(--green);
        border: 1px solid var(--dark-light);
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #shape-placeholder .checkbox-tools:not(:checked)+label:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #select_kamera_pallet,
    #select_kamera_sku {
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

    #select_kamera_pallet .checkbox-tools:checked+label,
    #select_kamera_pallet .checkbox-tools:not(:checked)+label,
    #select_kamera_sku .checkbox-tools:checked+label,
    #select_kamera_sku .checkbox-tools:not(:checked)+label {
        position: relative;
        display: inline-block;
        padding: 20px;
        width: 50%;
        font-size: 14px;
        line-height: 20px;
        letter-spacing: 1px;
        margin: 0 auto;
        margin-left: 5px;
        margin-right: 5px;
        margin-bottom: 10px;
        text-align: center;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        text-transform: uppercase;
        -webkit-transition: all 300ms linear;
        transition: all 300ms linear;
    }

    #select_kamera_pallet .checkbox-tools:not(:checked)+label,
    #select_kamera_sku .checkbox-tools:not(:checked)+label {
        background-color: var(--dark-light);
        color: var(--white);
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }

    #select_kamera_pallet .checkbox-tools:checked+label,
    #select_kamera_sku .checkbox-tools:checked+label {
        background-color: transparent;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #select_kamera_pallet .checkbox-tools:not(:checked)+label:hover,
    #select_kamera_sku .checkbox-tools:not(:checked)+label:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    #select_kamera_pallet .checkbox-tools:checked+label::before,
    #select_kamera_pallet .checkbox-tools:not(:checked)+label::before,
    #select_kamera_sku .checkbox-tools:checked+label::before,
    #select_kamera_sku .checkbox-tools:not(:checked)+label::before {
        position: absolute;
        content: '';
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 4px;
        background-image: linear-gradient(298deg, var(--red), var(--yellow));
        z-index: -1;
    }

    #select_kamera_pallet .checkbox-tools:checked+label .uil,
    #select_kamera_pallet .checkbox-tools:not(:checked)+label .uil,
    #select_kamera_sku .checkbox-tools:checked+label .uil,
    #select_kamera_sku .checkbox-tools:not(:checked)+label .uil {
        font-size: 24px;
        line-height: 24px;
        display: block;
        padding-bottom: 10px;
    }

    .head-switch {
        /* max-width: 1000px; */
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .switch-holder {
        display: flex;
        border-radius: 10px;
        justify-content: space-between;
        align-items: center;
    }

    .switch-label {
        width: 150px;
        text-align: end;
    }

    .switch-toggle input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        z-index: -2;
    }

    .switch-toggle input[type="checkbox"]+label {
        position: relative;
        display: inline-block;
        width: 100px;
        height: 40px;
        border-radius: 20px;
        margin: 0;
        cursor: pointer;
        box-shadow: 1px 1px 4px 1px;

    }

    .switch-toggle input[type="checkbox"]+label::before {
        position: absolute;
        content: 'Scan';
        font-size: 13px;
        text-align: center;
        line-height: 25px;
        top: 8px;
        left: 8px;
        width: 45px;
        height: 25px;
        color: #fff;
        border-radius: 20px;
        background-color: #5bc0de;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #5bc0de;
        transition: .3s ease-in-out;
    }

    .switch-toggle input[type="checkbox"]:checked+label::before {
        left: 50%;
        content: 'Input';
        color: #fff;
        background-color: #f0ad4e;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #f0ad4e;
    }

    @media (max-width: 800px) {

        #select_kamera_pallet .checkbox-tools:checked+label,
        #select_kamera_pallet .checkbox-tools:not(:checked)+label,
        #select_kamera_sku .checkbox-tools:checked+label,
        #select_kamera_sku .checkbox-tools:not(:checked)+label {
            flex: 100%;
        }
    }

    .toggle-button {
        margin: 0 0 1.5rem;
        box-sizing: border-box;
        font-size: 0;
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        align-items: stretch;
    }

    .toggle-button input {
        width: 0;
        height: 0;
        position: absolute;
        left: -9999px;
    }

    .toggle-button input+label {
        margin: 0;
        padding: 0.75rem 2rem;
        box-sizing: border-box;
        position: relative;
        display: inline-block;
        border: solid 1px #DDD;
        background-color: #FFF;
        font-size: 1.5rem;
        line-height: 140%;
        font-weight: 600;
        text-align: center;
        box-shadow: 0 0 0 rgba(255, 255, 255, 0);
        transition: border-color 0.15s ease-out, color 0.25s ease-out, background-color 0.15s ease-out, box-shadow 0.15s ease-out;
        flex: 0 0 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .toggle-button input+label:first-of-type {
        border-radius: 6px 0 0 6px;
        border-right: none;
        cursor: pointer;
    }

    .toggle-button input+label:last-of-type {
        border-radius: 0 6px 6px 0;
        border-left: none;
        cursor: pointer;
    }

    .toggle-button input:hover+label {
        border-color: #213140;
    }

    .toggle-button input:checked+label {
        background-color: #4B9DEA;
        color: #FFF;
        box-shadow: 0 0 10px rgba(102, 179, 251, 0.5);
        border-color: #4B9DEA;
        z-index: 1;
        cursor: pointer;
    }

    .toggle-button input:focus+label {
        outline: dotted 1px #CCC;
        outline-offset: 0.45rem;
    }

    @media (max-width: 800px) {
        .toggle-button input+label {
            padding: 0.75rem 0.25rem;
            flex: 0 0 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-TAMBAHPALLETPENERIMAANBARANG">Tambah Pallet Penerimaan Barang </h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-primary" id="simpan-data" onclick="handleSaveAndback()"><i class="fas fa-save"></i> <label name="CAPTION-SIMPANDANKEMBALI">Simpan dan Kembali</label></button>
                <!-- <button type="button" class="btn btn-danger" id="batalkan"><i class="fas fa-xmark"></i> <label name="CAPTION-CANCEL">Batalkan</label></button> -->
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- filter data -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/FilterData") ?>

        <!-- list data hasil dari filter -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/ListDataFilter") ?>

        <!-- tabel add pallet -->
        <?php $this->load->view("WMS/Penerimaanbarang/component/add_pallet") ?>

        <!-- modal add sku -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_tambah_sku/index") ?>

        <!-- modal add sku -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_pilih_gudang/index") ?>

    </div>
</div>
<!-- /page content -->