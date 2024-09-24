<div class="modal fade" id="list_data_pilih_pallet" role="dialog" data-keyboard="false" data-backdrop="static" style="display:none;">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">
          <label name="CAPTION-DAFTARLOKASISKU">Daftar Lokasi SKU</label>
          <div style="float: right; padding: 7px;background-color:white;border-radius:10px;color:black">
            <div class="is-filled"></div>
          </div>
        </h4>

      </div>
      <div class="modal-body">
        <!-- filter data -->
        <?php $this->load->view("WMS/KoreksiStokBarang/component/modal_pilih_pallet/filter_data") ?>

        <!-- list data -->
        <?php $this->load->view("WMS/KoreksiStokBarang/component/modal_pilih_pallet/list_data") ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_pilih_pallet"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
        <button type="button" class="btn btn-danger btn_close_list_data_pilih_pallet"><i class="fas fa-xmark"></i> <label name="CAPTION-BATAL">Batal</label></button>
      </div>
    </div>
  </div>
</div>