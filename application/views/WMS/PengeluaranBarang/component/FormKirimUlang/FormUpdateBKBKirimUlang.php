<!-- Modal untuk menampilkan form tambah tutup periode !-->
<div class="modal fade" id="previewupdateformbkbkirimulang" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="row">
            <div class="col-lg-12 col-sm-12 martop">
              <div style="float:left">
                <h4 class="modal-title"><label>Form Buat BKB Reschedule</label></h4>
              </div>
              <div style="float: right; padding: 5px; background-color: white; border-radius: 10px; color: black; display: flex; justify-content: space-between; align-items: center;">
                <div class="row" style="margin-left: 5px;margin-right: 0px;margin-top: 5px;">
                  <span id="loadingupdatebkbkirimulang" style="display: none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
                  <div style="display: flex; align-items: center;">
                    <!-- <button type="button" class="btn btn-success" id="btnsaveaddbkbkirimulang"><label name="CAPTION-SIMPAN"></label></button> -->
                    <button type="button" class="btn btn-danger" id="btnbackupdatebkbkirimulang"><label name="CAPTION-KEMBALI"></label></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-body form-horizontal form-label-left">
        <div class="row">
          <div class="col-xs-3">
            <label name="CAPTION-NOBKB"></label>
            <input type="text" id="txtupdatenobkbkirimulang" class="form-control" value="" readonly />
            <input type="hidden" id="txtupdateidbkbkirimulang" class="form-control" value="" readonly />
          </div>
          <div class="col-xs-3">
            <label name="CAPTION-NOPPB"></label>
            <input type="text" id="txtupdatenoppbkirimulang" class="form-control" value="" readonly />
            <input type="hidden" id="txtpickingorderid" class="form-control" value="" />
          </div>
          <div class="col-xs-3">
            <label name="CAPTION-CHECKER"></label>
            <select id="slcupdatecheckerbkbkirimulang" class="form-control select2" style="width: 100%;" readonly></select>
          </div>
        </div>
        <div class="row">
          <table id="tableupdatebkbkirimulang" width="100%" class="table table-striped">
            <thead>
              <tr>
                <th name="CAPTION-PRINCIPLE"></th>
                <th name="CAPTION-BRAND"></th>
                <th name="CAPTION-SKUKODE"></th>
                <th name="CAPTION-NAMABARANG"></th>
                <th name="CAPTION-KEMASAN"></th>
                <th name="CAPTION-SATUAN"></th>
                <th name="CAPTION-RENCANAQTYAMBIL"></th>
                <th name="CAPTION-SISAAMBIL"></th>
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
        <span id="loadingupdatebkbkirimulang" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING"></label></span>
        <!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbkirimulang">Simpan</button> -->
        <!-- <button type="button" class="btn btn-danger" id="btnbackupdatebkbkirimulang"><label name="CAPTION-KEMBALI"></label></button> -->
      </div>
    </div>
  </div>
</div>