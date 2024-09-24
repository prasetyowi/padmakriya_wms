<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="container mt-2">
        <div class="row" style="margin-top: 10px;">
          <div class="x_content table-responsive">
            <table id="list_data_tambah_pemusnahan_stok_view" width="100%" class="table table-striped">
              <thead>
                <tr>
                  <td><strong><label name="CAPTION-KODESKU">SKU Kode</label></strong></td>
                  <td><strong><label name="CAPTION-NAMASKU">SKU Nama</label></strong></td>
                  <td><strong><label name="CAPTION-BRAND">Brand</label></strong></td>
                  <td><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                  <td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                  <td><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
                  <td><strong><label name="CAPTION-QTYAVAILABLE">Qty Available</label></strong></td>
                  <td><strong><label name="CAPTION-QTYPLANPEMUSNAHAN">Qty Plan pemusnahan</label></strong></td>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
                <tr>
                  <td colspan="9" class="text-center bg-success" id="total_detail_sku_view"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <hr noshade="1">
        <div style="float: right">
          <a href="<?= base_url('WMS/PemusnahanDraft/PemusnahanDraftMenu') ?>" class="btn btn-dark" id="kembali_pemusnahan_draft_view"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
        </div>
      </div>
    </div>
  </div>
</div>