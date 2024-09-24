<div class="modal fade" id="modal_konfirmasi" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-KONFIRMASIPENERIMAAN">Konfirmasi Penerimaan</h4>
      </div>
      <div class="modal-body">

        <!-- header data -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_konfirmasi/header") ?>

        <!-- detail data -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_konfirmasi/detail") ?>

        <!-- detail data -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_konfirmasi/pallet") ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_konfirmasi"><i class="fas fa-check"></i> <label name="CAPTION-KONFIRMASI">Konfirmasi</label></button>
        <button type="button" class="btn btn-dark btn_close_list_konfirmasi"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>
  </div>
</div>