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
                <h3 name="CAPTION-MENUKOREKSISTOKBARANG">Menu Koreksi Stok Barang</h3>
            </div>
            <div style="float: right">
                <a href="<?= base_url('WMS/KoreksiStokBarangDraft/add') ?>" class="btn btn-primary" id="tambah-data"><i
                        class="fas fa-plus"></i> <label name="CAPTION-TAMBAHDRAFTKOREKSISTOKDRAFT">Tambah Draft Koreksi
                        Stok Draft</label></a>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- filter data -->
        <?php $this->load->view("WMS/KoreksiStokBarangDraft/component/filterdata") ?>

        <!-- list data -->
        <?php $this->load->view("WMS/KoreksiStokBarangDraft/component/listdata") ?>

    </div>

</div>
<!-- /page content -->