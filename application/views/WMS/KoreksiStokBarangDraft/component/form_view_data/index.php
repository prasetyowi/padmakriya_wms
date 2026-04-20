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
                <h3 name="CAPTION-MENUVIEWKOREKSISTOKBARANG">Menu view Koreksi Stok Barang</h3>
            </div>
            <!-- <div style="float: right">
                <button type="button" class="btn btn-primary" id="tambah"><i class="fas fa-plus"></i> Tambah Distribusi Penerimaan barang</button>
            </div> -->
        </div>

        <div class="clearfix"></div>

        <!-- filter data view -->
        <?php $this->load->view("WMS/KoreksiStokBarangDraft/component/form_view_data/filter") ?>

        <!-- list data view -->
        <?php $this->load->view("WMS/KoreksiStokBarangDraft/component/form_view_data/list_filter") ?>


    </div>
</div>
<!-- /page content -->
