<div class="modal fade" id="modalViewDetailBarang" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 60%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-VIEWDATAPICKINGVALIDATION">View Data Validasi Barang</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-KODESKU">Kode SKU</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="kodeSKUview" class="form-control" name="kodeSKUview" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-NAMASKU">Nama SKU</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="namaSKUview" class="form-control" name="namaSKUview" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-TOTALORDER">Total Order</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="totalOrder" class="form-control" name="totalOrder" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-TOTALAMBIL">Total Ambil</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="totalAmbil" class="form-control" name="totalAmbil" autocomplete="off" disabled>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-sm-12" style="padding: 5px">
            <div class="item form-group">
              <label class="col-form-label col-md-3 col-sm-3 label-align" name="CAPTION-TOTALSERAHTERIMA">Total Serah Terima</label>
              <div class="col-md-5 col-sm-5">
                <input type="text" id="totalSerahTerima" class="form-control" name="totalSerahTerima" autocomplete="off" disabled>
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive" style="margin-top: 10px;">
          <table class="table table-striped table-bordered" width="100%" id="initViewDataDetailBarang">
            <thead>
              <tr class="text-center bg-primary text-white">
                <td style="vertical-align:middle"><strong><label name="CAPTION-PRIORITAS">Prioritas</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-NODO">No. DO</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-NAMAOUTLET">Nama Outlet</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-ALAMATOUTLET">Alamat Outlet</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-SEGMENTLEVEL1">Segment 1</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-SEGMENTLEVEL2">Segment 2</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-SEGMENTLEVEL3">Segment 3</label></strong></td>
                <td style="vertical-align:middle"><strong><label name="CAPTION-JUMLAHORDER">Jumlah Order</label></strong></td>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-KEMBALI">Kembali</label></button>
      </div>
    </div>
  </div>
</div>