<div class="modal fade" id="modal_view_detail" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-DETAILDATA">Detail Data</h4>
      </div>
      <div class="modal-body">
        <!-- list data -->
        <?php $this->load->view("WMS/KonfirmasiDistribusiPenerimaan/component/modal_detail/list_data") ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark btn_close_detail"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>
  </div>
</div>