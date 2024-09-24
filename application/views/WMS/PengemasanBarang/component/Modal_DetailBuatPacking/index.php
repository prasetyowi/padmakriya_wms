<div class="modal fade" id="modaldetailbuatpackaging" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-DETAILPEMBUATANPACKAGING">Detail Pembuatan Packaging</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <!-- informasi packing -->
                    <?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailBuatPacking/informasipacking") ?>

                    <!-- detail item packing -->
                    <?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailBuatPacking/detailpacking") ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btnclosemodaldetailbuatpackingdo"><i class="fas fa-arrow-left"></i> <label name="CAPTION-BACK">Kembali</label></button>
            </div>
        </div>
    </div>
</div>