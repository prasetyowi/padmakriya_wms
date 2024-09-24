<style>
  #body-modal-split {
    height: 500px !important;
    overflow-x: hidden !important;
    overflow-y: hidden !important;
    overflow: hidden;
  }

  .wrapper {
    display: inline-flex;
    background: transparent;
    height: 100px;
    width: 100%;
    align-items: center;
    justify-content: space-evenly;
    border-radius: 5px;
    padding: 20px 15px;
    box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.2);
  }

  .wrapper .option {
    background: #fff;
    height: 100%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    margin: 0 10px;
    border-radius: 5px;
    cursor: pointer;
    padding: 0 10px;
    border: 2px solid lightgrey;
    transition: all 0.3s ease;
  }

  .wrapper .option .dot {
    height: 20px;
    width: 20px;
    background: #d9d9d9;
    border-radius: 50%;
    position: relative;
  }

  .wrapper .option .dot::before {
    position: absolute;
    content: "";
    top: 4px;
    left: 4px;
    width: 12px;
    height: 12px;
    background: #0069d9;
    border-radius: 50%;
    opacity: 0;
    transform: scale(1.5);
    transition: all 0.3s ease;
  }

  input[type="radio"] {
    display: none;
  }

  #option-1:checked:checked~.option-1,
  #option-2:checked:checked~.option-2 {
    border-color: #0069d9;
    background: #0069d9;
  }

  #option-1:checked:checked~.option-1 .dot,
  #option-2:checked:checked~.option-2 .dot {
    background: #fff;
  }

  #option-1:checked:checked~.option-1 .dot::before,
  #option-2:checked:checked~.option-2 .dot::before {
    opacity: 1;
    transform: scale(1);
  }

  .wrapper .option span {
    font-size: 20px;
    color: #808080;
  }

  #option-1:checked:checked~.option-1 span,
  #option-2:checked:checked~.option-2 span {
    color: #fff;
  }

  #table-fixed {
    width: 100%;
    border: 1px solid gray;
  }

  #konten-table {
    max-height: calc(60vh - 210px);
    overflow-y: auto;
    width: 100%;
  }

  #konten-table,
  #konten-table tr,
  #konten-table td {
    display: block;
  }

  #konten-table td {
    float: left;
  }
</style>

<div class="modal fade" id="modalSplitSuratJalan" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"><label name="CAPTION-SPLITSURATJALAN">Split Surat Jalan</label></h4>
      </div>
      <div class="modal-body" id="body-modal-split">
        <div class="wrapper">
          <input type="radio" name="selectSplit" id="option-1" value="0" class="chkTypeSuratJalan">
          <input type="radio" name="selectSplit" id="option-2" value="1" class="chkTypeSuratJalan">
          <label for="option-1" class="option option-1">
            <div class="dot"></div>
            <span name="CAPTION-TIDAK"></span>
          </label>
          <label for="option-2" class="option option-2">
            <div class="dot"></div>
            <span name="CAPTION-YA"></span>
          </label>
        </div>

        <div id="showInputNoEksternal" style="margin-top: 20px; width: 100%;position:relative;max-height:100%; display: none">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                <select class="form-control select2 perusahaan_split" name="perusahaan_split" id="perusahaan_split" required></select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label name="CAPTION-PENYALURATAUPRINCIPLE">Penyalur / Principle</label>
                <select class="form-control select2 principle_split" name="principle_split" id="principle_split" required></select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label name="CAPTION-NOSURATJALANEKSTERNAL">No. Surat Jalan Eksternal</label>
            <input type="text" class="form-control" autocomplete="off" id="noSuratJalanEksternal">
            <table class="table table-striped table-sm table-hover" id="table-fixed">
              <tbody id="konten-table"></tbody>
            </table>
          </div>


        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success handlePilihSplitSJ"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
        <button type="button" class="btn btn-dark handleTutupPilihSplitSJ"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
      </div>
    </div>
  </div>

</div>