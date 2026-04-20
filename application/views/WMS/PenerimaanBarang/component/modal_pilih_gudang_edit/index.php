<div class="modal fade" id="modal_pilih_gudang_edit" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-PILIHGUDANG">Pilih Gudang</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12 col-xs-12 justify-content-center">
            <div class="form-group">
              <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
              <select name="perusahaan_filter_gudang_edit" id="perusahaan_filter_gudang_edit" class="form-control select2" disabled></select>
            </div>

            <div class="form-group">
              <label name="CAPTION-PRINCIPLE">Principle</label>
              <select name="principle_filter_gudang_edit" id="principle_filter_gudang_edit" class="form-control select2" disabled></select>
            </div>

            <div class="form-group">
              <label name="CAPTION-TIPESTOCK">Tipe Stock</label>
              <select name="tipe_filter_gudang_edit" id="tipe_filter_gudang_edit" class="form-control select2">
                <option value="">--<label name="CAPTION-PILIHTIPESTOCK">Pilih Tipe Stock</label>--</option>
                <option value="Good Stock"><label name="CAPTION-GOODSTOCK">Good Stock</label></option>
                <option value="Bad Stock"><label name="CAPTION-BADSTOCK">Bad Stock</label></option>
              </select>
            </div>
          </div>
          <div class="col-md-9 col-xl-9 col-lg-9 col-sm-12 col-xs-12 justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divDepoDetail_edit"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_pilih_gudang_edit"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
        <button type="button" class="btn btn-dark btn_close_list_pilih_gudang_edit"><i class="fas fa-xmark"></i> <label name="CAPTION-PILIH">Tutup</label></button>
      </div>
    </div>
  </div>
</div>