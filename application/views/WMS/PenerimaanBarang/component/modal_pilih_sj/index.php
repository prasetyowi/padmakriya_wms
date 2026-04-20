<div class="modal fade" id="modal_pilih_sj" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-TAMBAHPENERIMAAN">Tambah Penerimaan</h4>
      </div>
      <div class="modal-body">

        <!-- filter data -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_pilih_sj/filter_data") ?>

        <!-- list data -->
        <?php $this->load->view("WMS/PenerimaanBarang/component/modal_pilih_sj/list_data") ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_pilih_sj"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
        <button type="button" class="btn btn-dark btn_close_list_pilih_sj"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>
  </div>
</div>