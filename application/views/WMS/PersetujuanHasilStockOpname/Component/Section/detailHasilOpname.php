<section id="sectionDataDetailOpname" style="display: none;">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <div class="clearfix"></div>
        </div>
        <div class="container mt-2">
          <div class="page-title">
            <div class="title_left">
              <div class="form-group">
                <input type="checkbox" class="check-item" name="tampilkan_selisih" id="tampilkan_selisih" onchange="handlerShowDataDetailOpnameSelisih(event)">
                <span class="check-item-span" name="CAPTION-HANYATAMPILKANYANGSELISIH">Hanya Tampilkan yang Selisih</span>
              </div>
            </div>

            <div style="float: right">
              <button type="button" class="btn btn-info btn-sm" onclick="handlerGetDataDetailOpnameBySKU()"><i class="fas fa-check"></i> <span name="CAPTION-LIHATBERDASARKANSKU">Lihat Berdasarkan SKU</span></button>
            </div>
          </div>

          <div id="initDetailOpname"></div>
        </div>
      </div>
    </div>
  </div>
</section>