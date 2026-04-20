<div class="modal fade" id="previewupdateformbkbstandar" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"><label name="CAPTION-FORMBUATBKBSTANDAR"></label></h4>
      </div>
      <div class="modal-body form-horizontal form-label-left">
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <div class="col-4 col-sm-2">
                <label name="CAPTION-NOBKB"></label>
              </div>
              <div class="col-8 col-sm-10">
                <input type="text" id="txtupdatenobkbstandar" class="form-control" value="" readonly />
                <input type="hidden" id="txtupdateidbkbstandar" class="form-control" value="" readonly />
              </div>
            </div>
            <div class="form-group">
              <div class="PB-4 col-sm-2">
                <label name="CAPTION-NOPPB"></label>
              </div>
              <div class="col-8 col-sm-10">
                <input type="text" id="txtupdatenoppbstandar" class="form-control" value="" readonly />
                <input type="hidden" id="txtpickingorderid" class="form-control" value="" readonly />
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <div class="col-4 col-sm-2">
                <label name="CAPTION-CHECKER"></label>
              </div>
              <div class="col-8 col-sm-10">
                <select id="slcupdatecheckerbkbstandar" class="form-control" style="width: 100%" readonly></select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-4 col-sm-2">
                <label name="CAPTION-NODO"></label>
              </div>
              <div class="col-8 col-sm-10">
                <input type="text" id="txtupdatenodostandar" class="form-control" value="" readonly />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <table id="tableupdatebkbstandar" width="100%" class="table table-striped">
            <thead>
              <tr>
                <th name="CAPTION-DICETAKOLEH"></th>
                <th name="CAPTION-SKUKODE"></th>
                <th name="CAPTION-NAMABARANG"></th>
                <th name="CAPTION-KEMASAN"></th>
                <th name="CAPTION-SATUAN"></th>
                <th name="CAPTION-JUMLAHBARANG"></th>
                <th name="CAPTION-SISAAMBIL"></th>
                <th name="CAPTION-PERMINTAANEDBARANG"></th>
                <th name="CAPTION-AKTUALQTYAMBIL"></th>
                <th name="CAPTION-TGLEXP"></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <span id="loadingbkbstandar" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
        <!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbstandar">Simpan</button> -->
        <button type="button" class="btn btn-danger" id="btnbackupdatebkbstandar"><label name="CAPTION-KEMBALI"></label></button>
      </div>
    </div>
  </div>
</div>