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
                <h3 name="CAPTION-VIEWDATAKONFIRMASIDISTRIBUSIPENERIMAANBARANG">View Data Konfirmasi Distribusi Penerimaan Barang</h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/view/header") ?>

        <!-- list scan data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/view/detail") ?>

        <!-- list scan data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/view/modal_detail_pallet") ?>

    </div>
</div>
<!-- /page content -->