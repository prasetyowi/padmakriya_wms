<div class="modal fade" id="form_batch_penerimaan" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-FORMBATCHPENERIMAAN">Form Batch Penerimaan</label></h4>
            </div>
            <div class="modal-body">
                <!-- form tambah penerimaan -->
                <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_tambah_penerimaan/form") ?>

                <!-- list sku for pilih sku di form tambah penerimaan -->
                <?php $this->load->view("WMS/PenerimaanSuratJalan/component/modal_tambah_penerimaan/list_sku") ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="simpan-penerimaan"><i class="fas fa-save"></i> <label name="CAPTION-SAVE">Simpan</label></button>
                <button type="button" class="btn btn-danger" id="batal-penerimaan"><i class="fas fa-xmark"></i> <label name="CAPTION-CANCEL">Batalkan</label></button>
            </div>
        </div>

    </div>
</div>