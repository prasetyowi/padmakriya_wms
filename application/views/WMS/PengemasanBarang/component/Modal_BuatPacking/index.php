<div class="modal fade" id="modalbuatpackaging" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-PEMBUATANPACKAGING">Pembuatan Packaging</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <!-- informasi packing -->
                    <?php $this->load->view("WMS/PengemasanBarang/component/Modal_BuatPacking/informasipacking") ?>

                    <div class="span-example" style="width:50%">
                        <div class="alert alert-info alert-header">
                            <i class="alert-icon fas fa-info"></i>
                            <span name="CAPTION-IFCHANGEQTYPACKEDCLICKENTER">Jika melakukan perubahan pada Qty Dikemas harap tekan enter setelah mengubahnya!</span>
                        </div>
                    </div>

                    <!-- detail item packing -->
                    <?php $this->load->view("WMS/PengemasanBarang/component/Modal_BuatPacking/detailpacking") ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btnsavebuatpackingdo"><i class="fas fa-floppy-disk"></i> <label name="CAPTION-SAVE">Simpan</label></button>
                <button type="button" class="btn btn-dark btnclosemodalbuatpackingdo" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>