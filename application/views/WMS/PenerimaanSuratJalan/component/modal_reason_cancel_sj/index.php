<div class="modal fade" id="list_reason_surat_jalan" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-REASONCANCELSURATJALAN">Reason Cancel Surat Jalan</h4>
      </div>
      <div class="modal-body">
        <!-- list data -->
        <div class="container">
          <div class="row">
            <div class="form-group">
              <label name="CAPTION-REASON">Reason</label>
              <textarea name="reason_cancel" id="reason_cancel" class="form-control" cols="10" rows="5"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success save_cancel_surat_jalan" onclick="save_cancel_surat_jalan(event)"><i class="fas fa-save"></i> <label name="CAPTION-SAVE">Simpan</label></button>
        <button type="button" class="btn btn-dark" onclick="close_show_cancel_surat_jalan()"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>

  </div>
</div>