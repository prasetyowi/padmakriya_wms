<?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/style") ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MENUKONFIRMASIDISTRIBUSIPENERIMAANBARANG">Menu Konfirmasi Distribusi Penerimaan Barang</h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/filter") ?>

        <!-- list scan data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/list_filter") ?>

        <!-- scan by one pallet -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/modal_scan_by_one/index") ?>

        <!-- input by one pallet -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/modal_input_manual/index") ?>

        <!-- detail pallet -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/modal_detail/index") ?>

    </div>
</div>
<!-- /page content -->