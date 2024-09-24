<div class="modal fade" id="modalOpnameBySKU" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 90%;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title">Data Opname By SKU</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div>
            <input type="checkbox" id="checkSelisih" class="check-item" onchange="handlerShowDataDetailOpnameSelisihBySKU(event)">
            <span class="check-item-span" name="CAPTION-HANYATAMPILKANYANGSELISIH">Hanya Tampilkan yang Selisih</span>
            <button style="float: right;" onclick="handlerExportListSKU()" class="btn btn-sm btn-success"><i class="fa-solid fa-file-excel"></i> <label name="CAPTION-EXPORT">Export</label></button>
            <button style="float: right;" onclick="handlerCetakListSKU()" class="btn btn-sm btn-warning"><i class="fas fa-print"></i> <label name="CAPTION-CETAK">Cetak</label></button>
          </div> <br>
          <div id="initDataOpnameDetailBySku"></div>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-success handlePilihSplitSJ"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button> -->
          <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
        </div>
      </div>
    </div>
  </div>