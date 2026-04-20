<div class="modal fade" id="modal_pilih_gudang" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-PILIHGUDANG">Pilih Gudang</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12 col-xs-12 justify-content-center">
            <div class="form-group">
              <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
              <select name="perusahaan_filter_gudang" id="perusahaan_filter_gudang" class="form-control select2" disabled></select>
            </div>

            <div class="form-group">
              <label name="CAPTION-PRINCIPLE">Principle</label>
              <select name="principle_filter_gudang" id="principle_filter_gudang" class="form-control select2" disabled></select>
            </div>

            <div class="form-group">
              <label name="CAPTION-TIPESTOCK">Tipe Stock</label>
              <select name="tipe_filter_gudang" id="tipe_filter_gudang" class="form-control select2">
                <option value="">--<label name="CAPTION-PERUSAHAAN">Pilih Tipe Stock</label>--</option>
                <option value="Good Stock"><label name="CAPTION-PERUSAHAAN">Good Stock</label></option>
                <option value="Bad Stock"><label name="CAPTION-BADSTOCK">Bad Stock</label></option>
              </select>
            </div>
          </div>
          <div class="col-md-7 col-xl-7 col-lg-7 col-sm-12 col-xs-12 justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="divDepoDetail"></div>
          </div>

          <div class="col-md-3 col-xl-3 col-lg-3 col-sm-12 col-xs-12 justify-content-center">
            <table class="table table-striped" id="table_data_detail_lokasi_gudang">
              <tr>
                <td width="48%"><strong><label name="CAPTION-LOKASI">Lokasi</label></strong></td>
                <td width="2%">:</td>
                <td width="48%" id="lokasi_gudang"></td>
              </tr>
              <!-- <tr>
                <td><strong><label name="CAPTION-PERUSAHAAN">PT</label></strong></td>
                <td>:</td>
                <td id="pt_gudang"></td>
              </tr>
              <tr>
                <td><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                <td>:</td>
                <td id="principle_gudang"></td>
              </tr> -->
              <tr>
                <td><strong><label name="CAPTION-TIPESTOCK">Tipe Stock</label></strong></td>
                <td>:</td>
                <td id="tipe_stock_gudang"></td>
              </tr>
              <tr>
                <td><strong><label name="CAPTION-JUMLAHRAK">Jumlah Rak</label></strong></td>
                <td>:</td>
                <td id="jumlah_rak_gudang"></td>
              </tr>
              <tr>
                <td><strong><label name="CAPTION-JUMLAHRAKTERISI">Jumlah Rak Terisi</label></strong>
                </td>
                <td>:</td>
                <td id="jumlah_rak_terisi_gudang"></td>
              </tr>
              <tr>
                <td><strong><label name="CAPTION-JUMLAHRAKKOSONG">Jumlah Rak Kosong</label></strong>
                </td>
                <td>:</td>
                <td id="jumlah_rak_kosong_gudang"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_pilih_gudang"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
        <button type="button" class="btn btn-dark btn_close_list_pilih_gudang"><i class="fas fa-xmark"></i>
          <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>
  </div>
</div>