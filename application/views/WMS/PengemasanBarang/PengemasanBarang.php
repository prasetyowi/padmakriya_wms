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
                <h3 name="CAPTION-MENUPENGEMASANBARANG">Menu Pengemasan Barang</h3>
            </div>
        </div>

        <div class="clearfix"></div>


        <!-- filter data -->
        <?php $this->load->view("WMS/PengemasanBarang/component/FilterData") ?>

        <!-- list data hasil dari filter -->
        <?php $this->load->view("WMS/PengemasanBarang/component/ListDataFilter") ?>

    </div>
</div>
<!-- /page content -->

<!-- Modal untuk detail data DO !-->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailDataDO") ?>

<!-- modal untuk packing do -->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_PackingDO/index") ?>

<!-- modal untuk cetak packing -->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_CetakPacking/index") ?>

<!-- modal untuk packing do detail -->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailForPackingCorfimed/index") ?>

<!-- modal untuk buat packaging do -->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_BuatPacking/index") ?>

<!-- modal untuk detail buat packaging do -->
<?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailBuatPacking/index") ?>