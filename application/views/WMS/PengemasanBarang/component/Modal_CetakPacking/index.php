<div class="modal fade" id="modalcetakdo" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-MENUCETAKLABELRESI">Menu Cetak Label Resi</label></h4>
            </div>
            <div class="modal-body">

                <!-- detail item delivery order -->
                <?php $this->load->view("WMS/PengemasanBarang/component/Modal_CetakPacking/ListData") ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary cetaklabel"><i class="fas fa-print"></i> <label name="CAPTION-CETAKLABELRESI">Cetak Label Resi</label></button>
                <button type="button" class="btn btn-dark btnclosemodalcetakpackingdo"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP"></label></button>
            </div>
        </div>
    </div>
</div>