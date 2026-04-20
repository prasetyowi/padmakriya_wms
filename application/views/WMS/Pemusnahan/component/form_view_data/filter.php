<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <!-- <h3><strong>Filter Data</strong></h3> -->
        <div class="clearfix"></div>
      </div>
      <div class="container mt-2">
        <div class="row">
          <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label name="CAPTION-NOBERITAACARA">No. Berita Acara</label>
                  <input type="text" class="form-control" name="no_koreksi_view" id="no_koreksi_view" placeholder="Auto" required readonly>
                  <input type="hidden" class="form-control" name="error_view" id="error_view" readonly>
                  <input type="hidden" class="form-control" name="global_id_view" id="global_id_view" value="<?= $id ?>" readonly>
                  <input type="hidden" class="form-control" name="koreksi_draft_id_view" id="koreksi_draft_id_view" readonly>
                  <input type="hidden" class="form-control" name="gudang_id_view" id="gudang_id_view" readonly>
                  <input type="hidden" class="form-control" name="gudang_nama_view" id="gudang_nama_view" readonly>
                  <input type="hidden" class="form-control" name="principle_id_view" id="principle_id_view" readonly>
                  <input type="hidden" class="form-control" name="tipe_id_view" id="tipe_id_view" readonly>
                  <input type="hidden" class="form-control" name="checker_id_view" id="checker_id_view" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label name="CAPTION-TANGGAL">Tanggal</label>
                  <input type="date" class="form-control input-date-start" name="tgl_view" id="tgl_view" placeholder="dd-mm-yyyy" readonly>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label name="CAPTION-NODRAFTBERITAACARA">No. Draft Berita Acara</label>
              <input type="text" class="form-control" name="no_koreksi_draft_view" id="no_koreksi_draft_view" placeholder="Gudang Asal" required readonly />
            </div>

            <div class="form-group">
              <label name="CAPTION-GUDANGASAL">Gudang Asal</label>
              <input type="text" class="form-control" name="gudang_asal_view" id="gudang_asal_view" placeholder="Gudang Asal" required readonly />
            </div>

          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-PRINCIPLE">Principle</label>
              <input type="text" class="form-control" name="principle_view" id="principle_view" placeholder="Principle" required readonly />
            </div>
            <div class="form-group">
              <label name="CAPTION-CHECKER">Checker</label>
              <input type="text" class="form-control" name="checker_view" id="checker_view" placeholder="Checker" required readonly>
            </div>
            <div class="form-group">
              <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
              <input type="text" class="form-control" name="tipe_transaksi_view" id="tipe_transaksi_view" placeholder="Tipe Transaksi" readonly required />
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label name="CAPTION-KETERANGAN">Keterangan</label>
              <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_view" id="keterangan_view" placeholder="Keterangan" readonly></textarea>
            </div>

            <div class="form-group">
              <label name="CAPTION-STATUS">Status</label>
              <input type="text" class="form-control" name="status_view" id="status_view" placeholder="Status" readonly required />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>