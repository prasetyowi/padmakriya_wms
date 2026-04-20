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

#select_kamera,
#select_kamera_by_one {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

[type="radio"]:checked,
[type="radio"]:not(:checked) {
    position: absolute;
    left: -9999px;
    width: 0;
    height: 0;
    visibility: hidden;
}

#select_kamera .checkbox-tools:checked+label,
#select_kamera .checkbox-tools:not(:checked)+label,
#select_kamera_by_one .checkbox-tools:checked+label,
#select_kamera_by_one .checkbox-tools:not(:checked)+label {
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

#select_kamera .checkbox-tools:not(:checked)+label,
#select_kamera_by_one .checkbox-tools:not(:checked)+label {
    background-color: var(--dark-light);
    color: var(--white);
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

#select_kamera .checkbox-tools:checked+label,
#select_kamera_by_one .checkbox-tools:checked+label {
    background-color: transparent;
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

#select_kamera .checkbox-tools:not(:checked)+label:hover,
#select_kamera_by_one .checkbox-tools:not(:checked)+label:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

#select_kamera .checkbox-tools:checked+label::before,
#select_kamera .checkbox-tools:not(:checked)+label::before,
#select_kamera_by_one .checkbox-tools:checked+label::before,
#select_kamera_by_one .checkbox-tools:not(:checked)+label::before {
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

#select_kamera .checkbox-tools:checked+label .uil,
#select_kamera .checkbox-tools:not(:checked)+label .uil,
#select_kamera_by_one .checkbox-tools:checked+label .uil,
#select_kamera_by_one .checkbox-tools:not(:checked)+label .uil {
    font-size: 24px;
    line-height: 24px;
    display: block;
    padding-bottom: 10px;
}

@media (max-width: 800px) {

    #select_kamera .checkbox-tools:checked+label,
    #select_kamera .checkbox-tools:not(:checked)+label,
    #select_kamera_by_one .checkbox-tools:checked+label,
    #select_kamera_by_one .checkbox-tools:not(:checked)+label {
        flex: 100%;
    }
}
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MENUKONFIRMASIDISTRIBUSIPENERIMAANBARANG">Menu Konfirmasi Distribusi Penerimaan Barang
                </h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("master/KonfirmasiDistribusiPenerimaan/component/filter") ?>

        <!-- list scan data -->
        <?php $this->load->view("master/KonfirmasiDistribusiPenerimaan/component/list_filter") ?>

        <!-- scan by one pallet -->
        <?php $this->load->view("master/KonfirmasiDistribusiPenerimaan/component/modal_scan_by_one/index") ?>

    </div>
</div>

<!-- /page content -->