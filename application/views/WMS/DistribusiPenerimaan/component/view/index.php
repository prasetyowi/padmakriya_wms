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
                <h3>View Data Distribusi Penerimaan Barang</h3>
            </div>
            <div style="float: right">
                <button type="button" class="btn btn-dark" id="kembali_distribusi"><i class="fas fa-arrow-left"></i> Kembali</button>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- form data -->
        <?php $this->load->view("WMS/DistribusiPenerimaan/component/view/header") ?>

        <!-- list scan data -->
        <?php $this->load->view("WMS/DistribusiPenerimaan/component/view/detail") ?>

        <!-- list scan data -->
        <?php $this->load->view("WMS/DistribusiPenerimaan/component/view/modal_detail_pallet") ?>

    </div>
</div>
<!-- /page content -->