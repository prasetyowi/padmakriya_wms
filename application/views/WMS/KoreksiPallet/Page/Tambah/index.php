<?php $this->load->view("WMS/KoreksiPallet/component/Style/index") ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MENUTAMBAHKOREKSIPALLET">Menu Tambah Koreksi Pallet</h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- filter data tambah -->
        <?php $this->load->view("WMS/KoreksiPallet/Page/Tambah/filter") ?>

        <!-- list data tambah -->
        <?php $this->load->view("WMS/KoreksiPallet/Page/Tambah/list_filter") ?>

        <!-- modal pilih sku -->
        <?php $this->load->view("WMS/KoreksiPallet/component/Modal/SKU/index") ?>

    </div>
</div>
<!-- /page content -->