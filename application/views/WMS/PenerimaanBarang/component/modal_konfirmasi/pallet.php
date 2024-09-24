<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-8">
            <fieldset>
              <legend>
                <label name="CAPTION-PALLET">Pallet</label>
              </legend>
              <div class="table-responsive">
                <table id="data_pallet_konfirmasi" class="table table-striped" width="100%">
                  <thead>
                    <tr class="text-center">
                      <td width="15%"><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
                      <td width="15%"><strong><label name="CAPTION-JENISPALLET">Jenis Pallet</label></strong></td>
                      <td width="15%"><strong><label name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</label></strong></td>
                      <td width="10%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-center bg-success" id="total_pallet_konfirmasi"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </fieldset>

          </div>
          <div class="col-md-12" id="show_isi_sku_pallet_konfirmasi" style="display: none;">
            <fieldset>
              <legend>
                <label name="CAPTION-DETAILPALLET">Detail Pallet</label> <strong>[ <span class="kode_pallet_konfirmasi"></span> ]</strong>
              </legend>

              <div class="table-responsive">
                <table class="table table-striped" width="100%" id="list_detail_pallet_konfirmasi">
                  <thead>
                    <tr class="text-center">
                      <td width="10%"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                      <td width="10%"><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
                      <td width="27%"><strong><label name="CAPTION-NAMABARANG">Nama Barang</label></strong></td>
                      <td width="8%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                      <td width="8%"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                      <td width="10%"><strong><label name="CAPTION-EXPIREDDATE">Exp Date</label></strong></td>
                      <td width="10%"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
                    </tr>
                  </thead>
                  <tbody></tbody>
                  <tfoot>
                    <tr>
                      <td colspan="7" class="text-center bg-success" id="total_detail_pallet_konfirmasi"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </fieldset>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>