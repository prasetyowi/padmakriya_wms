<?php $this->load->view("WMS/AssignmentPengembalianBarang/component/Style/index") ?>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-ASSIGNMENTBARANGADD">Menu Tambah Assignment Pengembalian Barang</h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- filter data tambah -->
        <?php $this->load->view("WMS/AssignmentPengembalianBarang/Page/Tambah/filter") ?>

        <!-- list data tambah -->
        <?php $this->load->view("WMS/AssignmentPengembalianBarang/Page/Tambah/list_filter") ?>

        <!-- modal pilih sku -->
        <?php $this->load->view("WMS/AssignmentPengembalianBarang/component/Modal/SKU/index") ?>

    </div>
</div>
<!-- /page content -->