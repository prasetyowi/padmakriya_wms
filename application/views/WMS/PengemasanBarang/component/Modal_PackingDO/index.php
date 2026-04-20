<div class="modal fade" id="modalpackingdo" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label>Informasi Packing Delivery Order</label></h4>
            </div>
            <div class="modal-body">
                <!-- informasi delivery order -->
                <input type="hidden" name="check_konfirmasi_packing" class="form-control check_konfirmasi_packing" id="check_konfirmasi_packing">
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_PackingDO/InformasiDO") ?>

                <!-- detail item delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_PackingDO/DetailItemDO") ?>

                <!-- informasi pengemasan delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_PackingDO/Informasipengemasan") ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btnkonfirmasimodalpackingdo"><i class="fas fa-check"></i> Konfirmasi Packaging Selesai</button>
                <button type="button" class="btn btn-success btnsimpanmodalpackingdo"><i class="fas fa-floppy-disk"></i> Simpan</button>
                <button type="button" class="btn btn-dark btnclosemodalpackingdo"><i class="fas fa-xmark"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>