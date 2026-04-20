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
                  <label>No. Dokumen</label>
                  <input type="text" class="form-control" name="no_doc" id="no_doc" placeholder="Auto" required readonly>
                  <input type="hidden" class="form-control" name="error" id="error" readonly>
                  <input type="hidden" class="form-control" name="principle" id="principle" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" value="<?php echo Date('Y-m-d') ?>" readonly>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>No. Penerimaan</label>
              <select class="form-control select2" name="no_penerimaan" id="no_penerimaan" required>
                <option value="">--Pilih No. Penerimaan--</option>
                <?php foreach ($no_penerimaan as $no) : ?>
                  <option value="<?= $no->id ?>">
                    <?= $no->kode ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>No. Surat Jalan</label>
                  <input type="text" class="form-control" name="surat_jalan" id="surat_jalan" placeholder="No. Surat Jalan" required readonly />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>No. Surat Jalan Eksternal</label>
                  <input type="text" class="form-control" name="surat_jalan_eksternal" id="surat_jalan_eksternal" placeholder="No. Surat Jalan Eksternal" required readonly />
                </div>
              </div>
            </div>

          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tipe Penerimaan</label>
                  <input type="text" class="form-control" name="tipe_penerimaan" id="tipe_penerimaan" placeholder="Tipe Penerimaan" required readonly />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal BTB</label>
                  <input type="date" class="form-control" name="tgl_btb" id="tgl_btb" placeholder="dd-mm-yyyy" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <label>Jasa Pengangkut</label>
                  <input type="text" class="form-control" name="expedisi" id="expedisi" placeholder="Ekspedisi" readonly required />
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>No. Kendaraan</label>
                  <input type="text" class="form-control" name="no_kendaraan" id="no_kendaraan" placeholder="No kendaraan" readonly required />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-7">
                <div class="form-group">
                  <label>Nama Pengemudi</label>
                  <input type="text" class="form-control" name="nama_pengemudi" id="nama_pengemudi" placeholder="Nama Pengemudi" required readonly />
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Status</label>
                  <input type="text" class="form-control" name="status" id="status" placeholder="Status" value="Open" required readonly />
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label>Keterangan</label>
              <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"></textarea>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Gudang Asal</label>
                  <select class="form-control select2" name="gudang_asal" id="gudang_asal" required disabled></select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Checker</label>
                  <select class="form-control select2" name="checker" id="checker" required disabled></select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>