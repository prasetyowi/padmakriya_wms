<div class="right_col" role="main">
   <div class="">
      <div class="page-title">
         <div class="title_left">
            <h3 name="CAPTION-SURATTUGASPENGIRIMAN">Surat Tugas Pengiriman</h3>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="panel panel-default">
         <div class="panel-heading"><label name="Settlement">Settlement</label></div>
         <div class=" panel-body form-horizontal form-label-left">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No FDJR</label>
                     <div class="col-md-6 col-sm-6">
                        <input type="hidden" class="form-control" name="delivery_order_batch_id" id="delivery_order_batch_id" value="<?php echo $delivery_order_batch_id ?>" required>
                        <input type="hidden" class="form-control" name="txt_jumlah" id="txt_jumlah" value="0" readonly>
                        <input type="text" class="form-control" name="filter_fdjr_no" id="filter_fdjr_no" readonly>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
                     <div class="col-md-6 col-sm-6">
                        <input type="text" class="form-control" name="txt_driver_fdjr" id="txt_driver_fdjr" readonly>
                     </div>
                  </div>
                  <!-- <div class="form-group">
					<label class="col-form-label col-md-4 col-sm-4 label-align"></label>
					<div class="col-md-6 col-sm-6">
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
						<button type="button" id="btn_sproses_settlement" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
					</div>
				  </div> -->
               </div>
               <!-- <div class="col-lg-6">
				  <div class="form-group">
					<label class="col-form-label col-md-4 col-sm-4 label-align">Status</label>
					<div id="settlement_status"><b>Cocok</b></div>
				  </div>
               </div> -->
            </div>
         </div>
      </div>
      <div class="panel panel-default">
         <div class="panel-body form-horizontal form-label-left">
            <div class="row">
               <table id="tablesettlementmenu" width="100%" class="table table-striped">
                  <thead>
                     <tr>
                        <th name="CAPTION-NO">No</th>
                        <th name="CAPTION-PRINCIPLE">Principle</th>
                        <th name="CAPTION-BRAND">Brand</th>
                        <th name="CAPTION-KODESKU">Kode SKU</th>
                        <th name="CAPTION-NAMABARANG">Nama Barang</th>
                        <th name="CAPTION-KEMASAN">Kemasan</th>
                        <th name="CAPTION-SATUAN">Satuan</th>
                        <th name="CAPTION-QTYDO">Qty DO</th>
                        <th name="CAPTION-QTYTERKIRIM">Qty Terkirim</th>
                        <th name="CAPTION-ACTUALQTYBARANG">Aktual Qty Barang</th>
                        <th name="CAPTION-DEVIASI">Deviasi</th>
                        <th name="CAPTION-STATUS">Status</th>
                        <th name="CAPTION-DETAILTRANSAKSI">Detail Transaksi</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
            <br><br>
            <div class="row">
               <div class="col-lg-6">
                  <!-- <button type="button" id="btn_proses_selisih" class="btn btn-primary">Proses Barang Selisih</button> -->
                  <!-- <button type="button" id="btn_sproses_titipan" class="btn btn-primary">Proses Barang Titipan</button> -->
                  <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                  <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
               </div>
               <div class="pull-right">
                  <button type="button" id="btn_simpan_settlement" class="btn btn-success"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                  <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/SuratTugasPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                  <!-- <button type="button" id="btn_back_settlement" class="btn btn-danger">Kembali</button> -->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>