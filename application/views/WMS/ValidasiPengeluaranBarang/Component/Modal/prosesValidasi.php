<div class="modal fade" id="modalProsesValidasi" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 90%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-PROSESDATAVALIDASIBARANG">Proses Data Validasi Barang</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-KODESKU">Kode SKU</label>
              <div class="col-md-5 col-sm-5">
                <input type="hidden" id="depoDetailNama" class="form-control" name="depoDetailNama" autocomplete="off" disabled>
                <input type="hidden" id="skuId" class="form-control" name="skuId" autocomplete="off" disabled>
                <input type="hidden" id="skuStockId" class="form-control" name="skuStockId" autocomplete="off" disabled>
                <input type="hidden" id="skuExpDate" class="form-control" name="skuExpDate" autocomplete="off" disabled>
                <input type="text" id="kodeSKU" class="form-control" name="kodeSKU" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-NAMASKU">Nama SKU</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="namaSKU" class="form-control" name="namaSKU" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-KEMASAN">Kemasan</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="kemasanSKU" class="form-control" name="kemasanSKU" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-SATUAN">Satuan</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="satuanSKU" class="form-control" name="satuanSKU" autocomplete="off" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive" style="margin-top: 10px;">
          <table class="table table-striped table-bordered" width="100%" id="initDataDetailProsesValidasi">
            <thead>
              <tr class="text-center bg-primary text-white">
                <td rowspan="2" style="vertical-align:middle"><strong>Qty Order</strong></td>
                <td rowspan="2" style="vertical-align:middle"><strong><label name="CAPTION-QTYAMBIL">Qty Ambil</label> </strong></td>
                <td colspan="2" style="vertical-align:middle"><strong><label name="CAPTION-QTYTERIMA">Qty Terima</label> </strong></td>
                <td colspan="2" style="vertical-align:middle"><strong><label name="CAPTION-QTYVALIDASI">Qty Validasi</label> </strong></td>
              </tr>
              <tr class="text-center bg-primary text-white">
                <td style="vertical-align:middle"><strong><label name="CAPTION-BAGUS">Bagus</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-RUSAK">Rusak</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-BAGUS">Bagus</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-RUSAK">Rusak</label></strong></td>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <fieldset style="margin-top: 10px;display:none" id="initDetailDOValidasi">
          <legend>List DO</legend>
          <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%" id="initViewDataDetailBarang">
              <thead>
                <tr class="text-center bg-primary text-white">
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-PRIORITAS">Prioritas</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-NODO">No. DO</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-NAMAOUTLET">Nama Outlet</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-ALAMATOUTLET">Alamat Outlet</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-SEGMENTLEVEL1">Segment 1</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-SEGMENTLEVEL2">Segment 2</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-SEGMENTLEVEL3">Segment 3</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-JUMLAHORDER">Jumlah Order</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-JUMLAHKURANG">Jumlah Kurang</label> </strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong>Status</strong></td>
                  <td style="vertical-align:middle" rowspan="2"><strong><label name="CAPTION-221003002">Promo</label> </strong></td>
                  <td style="vertical-align:middle" colspan="2"><strong>Drop</strong></td>
                </tr>
                <tr class="text-center bg-primary text-white">
                  <td style="vertical-align:middle"><strong>Drop Per SKU</strong></td>
                  <td style="vertical-align:middle"><strong>Drop Per DO</strong></td>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="handlerProsesValidasi(event)"><i class="fas fa-save"></i> <label name="CAPTION-SAVE">Simpan</label></button>
        <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-KEMBALI">Kembali</label></button>
      </div>
    </div>
  </div>
</div>