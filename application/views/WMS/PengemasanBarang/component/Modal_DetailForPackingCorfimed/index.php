<div class="modal fade" id="modalpackingdodetail" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-INFORMASIPACKINGDELIVERYORDER">Informasi Packing Delivery Order</label></h4>
            </div>
            <div class="modal-body">
                <!-- informasi delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailForPackingCorfimed/InformasiDO") ?>

                <!-- detail item delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailForPackingCorfimed/DetailItemDO") ?>

                <!-- informasi pengemasan delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_DetailForPackingCorfimed/Informasipengemasan") ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btnclosemodalpackingdodetail"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP"></label></button>
            </div>
        </div>
    </div>
</div>