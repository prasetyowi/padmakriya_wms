<div class="modal fade" id="modalDropKoreksiDO" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width:90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title" name="CAPTION-DROPKOREKSIDO">Drop Koreksi DO</h4>
      </div>
      <div class="modal-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="container mt-2">
              <div class="row">
                <div class="x_content table-responsive">
                  <table id="initDataDropKoreksiDO" width="100%" class="table table-striped">
                    <thead>
                      <tr class="text-center">
                        <td class="text-center" width="5%">
                          <input type="checkbox" id="check-all-pilih-sku" style="transform: scale(1.5)" class="form-control input-sm" width="20" onchange="checkAllSKU(event)" />
                        </td>
                        <td width="10%"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                        <td width="7%"><strong><label name="CAPTION-SKUKODE">SKU Kode</label></strong></td>
                        <td width="15%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
                        <td class="text-center" width="10%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                        <td class="text-center" width="10%"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                        <td width="10%"><strong><label>Qty</label></strong></td>
                        <td width="10%"><strong><label>Qty Drop</label></strong></td>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn_pilih_sku"><i class="fas fa-check"></i> <label name="CAPTION-KONFIRMASI">Konfirmasi</label></button>
        <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-BACK">Kembali</label></button>
      </div>
    </div>
  </div>

</div>