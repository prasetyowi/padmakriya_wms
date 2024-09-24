<div class="right_col" role="main">
   <div class="">
      <div class="page-title">
         <div class="title_left">
            <h3 name="CAPTION-DETAILSETTLEMENT">Detail Settlement</h3>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="panel panel-default">
         <!-- <div class="panel-heading">Closing Pengiriman</div> -->
         <div class="panel-body form-horizontal form-label-left">
            <div class="row">
               <table id="tableskuheader" width="100%" class="table table-borderless">
                  <tbody>
                     <tr>
                        <td name="CAPTION-KODESKU">Kode SKU</td>
                        <td colspan="3">
                           <input type="hidden" id="delivery_order_batch_id" class="form-control" name="delivery_order_batch_id" value="<?php echo $delivery_order_batch_id; ?>" />
                           <input type="hidden" id="sku_id" class="form-control" name="sku_id" value="<?php echo $sku_id; ?>" />
                           <input type="text" id="filter_sku_kode" class="form-control" name="filter_sku_kode" value="<?php echo $sku_kode; ?>" readonly />
                        </td>
                        <td name="CAPTION-QTYDO">Qty DO</td>
                        <td>
                           <input type="text" id="filter_qty_do" name="filter_qty_do" class="form-control" value="<?php echo $qty_do; ?>" readonly />
                        </td>
                        <td name="CAPTION-ACTUALQTYINOUT">Aktual Qty In Out</td>
                        <td>
                           <input type="text" id="filter_qty_aktual" name="filter_qty_aktual" class="form-control" value="<?php echo $qty_aktual; ?>" readonly />
                        </td>
                        <td name="CAPTION-SELISIH">Selisih</td>
                        <td>
                           <input type="text" id="filter_qty_selisih" name="filter_qty_selisih" class="form-control" value="<?php echo $qty_aktual - $qty_do; ?>" readonly />
                        </td>
                     </tr>
                     <tr>
                        <td name="CAPTION-NAMASKU">Nama SKU</td>
                        <td colspan="5">
                           <input type="text" id="filter_sku_nama" name="filter_sku_nama" class="form-control" value="<?php echo $sku_nama; ?>" readonly />
                        </td>
                        <td style="width:10%;"><input type="text" id="filter_sku_kemasan" name="filter_sku_kemasan" class="form-control" value="<?php echo $sku_kemasan; ?>" readonly /></td>
                        <td style="width:10%;"><input type="text" id="filter_sku_satuan" name="filter_sku_satuan" class="form-control" value="<?php echo $sku_satuan; ?>" readonly /></td>
                        <td name="CAPTION-STATUS">Status</td>
                        <td>
                           <input type="text" id="filter_status" name="filter_status" class="form-control" value="<?php echo $status; ?>" readonly />
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="panel panel-default">
         <!-- <div class="panel-heading">Daftar DO</div> -->
         <input type="hidden" id="txt_jumlah" name="txt_jumlah" value="0" />
         <div class="panel-body form-horizontal form-label-left">
            <div class="row">
               <table id="tableskumenu" width="100%" class="table table-striped">
                  <thead>
                     <tr>
                        <th class="text-center" name="CAPTION-NO">No</th>
                        <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                        <th class="text-center" name="CAPTION-DOKUMEN">Dokumen</th>
                        <th class="text-center" name="CAPTION-JENISDOKUMEN">Jenis Dokumen</th>
                        <th class="text-center" name="CAPTION-STATUSDOKUMEN">Status Dokumen</th>
                        <th class="text-center" name="CAPTION-JUMLAH">Jumlah</th>
                        <th class="text-center" name="CAPTION-TOTAL">Total</th>
                        <th class="text-center" name="CAPTION-SUGGESTIONSYSTEM">Suggestion System</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
            <br><br>
            <div class="row pull-right">
               <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
               <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/DeliveryOrderReturForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary"><span name="CAPTION-PROSESDORETUR">Proses DO Retur</span></a>
               <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=<?php echo $delivery_order_batch_id; ?>" type="button" id="btn_back" class="btn btn-primary" style="display: ;"><span name="CAPTION-BACK">Kembali</span></a>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="previewdetailbkb" role="dialog" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <h4 class="modal-title"><label name="CAPTION-DETAILBKB">Detail BKB</label></h4>
         </div>
         <div class="modal-body form-horizontal form-label-left">
            <div class="row">
               <div class="col-lg-6">
                  <div class="form-group">
                     <div class="col-4 col-sm-2">
                        <label name="CAPTION-NOBKB">NO BKB</label>
                     </div>
                     <div class="col-8 col-sm-10">
                        <input type="text" id="picking_order_aktual_kode" class="form-control" value="" readonly />
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-4 col-sm-2">
                        <label name="CAPTION-NOPPB">NO PPB</label>
                     </div>
                     <div class="col-8 col-sm-10">
                        <input type="text" id="picking_order_kode" class="form-control" value="" readonly />
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="form-group">
                     <div class="col-4 col-sm-2">
                        <label name="CAPTION-CHECKER">Checker</label>
                     </div>
                     <div class="col-8 col-sm-10">
                        <input type="text" id="karyawan_nama" class="form-control" value="" readonly />
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <table id="tabledetailbkb" width="100%" class="table table-striped">
                  <thead>
                     <tr>
                        <th name="CAPTION-NODO">No DO</th>
                        <th name="CAPTION-KODESKU">Kode SKU</th>
                        <th name="CAPTION-NAMABARANG">Nama Barang</th>
                        <th name="CAPTION-KEMASAN">Kemasan</th>
                        <th name="CAPTION-SATUAN">Satuan</th>
                        <th name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
                        <th name="CAPTION-PERMINTAANEDBARANG">Permintaan ED Barang</th>
                        <th name="CAPTION-ACTUALQTYAMBIL">Aktual Qty Ambil</th>
                        <th name="CAPTION-EXPIREDDATE">Expired Date</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="modal-footer">
            <span id="loadingbkb" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
            <!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbstandar">Simpan</button> -->
            <button type="button" class="btn btn-danger" id="btnback" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="previewdetaildo" role="dialog" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <h4 class="modal-title"><label>Detail DO</label></h4>
         </div>
         <div class="modal-body form-horizontal form-label-left">
            <div class="row">
               <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                     <div class="x_title">
                        <div class="clearfix"></div>
                     </div>
                     <div class="row">
                        <div class="col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_kode">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_kode">DO No</label>
                              <div id="delivery_order_kode"></div>
                           </div>
                        </div>
                        <div class="col-xs-3">
                           <label class="control-label" for="deliveryorderdraft-delivery_order_status">Tipe</label>
                           <div id="tipe_delivery_order_nama"></div>
                        </div>
                        <div class=" col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_status">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_status">Status</label>
                              <div id="delivery_order_status"></div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tgl_buat_do">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_buat_do">Tanggal Entry DO</label>
                              <div id="delivery_order_tgl_buat_do"></div>
                           </div>
                        </div>
                        <div class="col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tgl_expired_do">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_expired_do">Tanggal Expired</label>
                              <div id="delivery_order_tgl_expired_do"></div>
                           </div>
                        </div>
                        <div class=" col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tgl_surat_jalan">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_surat_jalan">Tanggal Surat Jalan</label>
                              <div id="delivery_order_tgl_surat_jalan"></div>
                           </div>
                        </div>
                        <div class=" col-xs-3">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tgl_rencana_kirim">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_rencana_kirim">Tanggal Rencana Kirim</label>
                              <div id="delivery_order_tgl_rencana_kirim"></div>
                           </div>
                        </div>
                     </div>
                     <div class=" row">
                        <div class="col-xs-6">
                           <div class="form-group field-deliveryorderdraft-client_wms_id">
                              <label for="" class="control-label">Perusahaan</label>
                              <div id="client_wms_nama"></div>
                           </div>
                           <div class=" form-group field-deliveryorderdraft-client_wms_alamat">
                              <label class="control-label" for="deliveryorderdraft-client_wms_alamat">Alamat Perusahaan</label>
                              <div id="client_wms_alamat"></div>
                           </div>
                        </div>
                        <div class=" col-xs-6">
                           <div class="form-group field-deliveryorderdraft-delivery_order_keterangan">
                              <label class="control-label" for="deliveryorderdraft-delivery_order_keterangan">Keterangan</label>
                              <div id="delivery_order_keterangan"></div>
                           </div>
                        </div>
                     </div>
                     <div class=" row">
                        <div class="col-xs-6">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tipe_pembayaran">
                              <label for="deliveryorderdraft-delivery_order_tipe_pembayaran" class="control-label">Tipe Pembayaran</label>
                              <div id="delivery_order_tipe_pembayaran"></div>
                           </div>
                        </div>
                        <div class=" col-xs-6">
                           <div class="form-group field-deliveryorderdraft-delivery_order_tipe_layanan">
                              <label for="deliveryorderdraft-delivery_order_tipe_layanan" class="control-label">Tipe Pelayanan</label>
                              <div id="delivery_order_tipe_layanan"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div <?= empty($deliveryOrderDraft['client_pt_id']) ? "style='display: none'" : "" ?> class=" row" id="panel-customer">
               <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                     <div class="x_title">
                        <h4 class="pull-left">Customer</h4>
                        <div class="clearfix"></div>
                     </div>
                     <div class="customer-info">
                        <div class="row">
                           <div class="col-xs-4">
                              <label>Nama:</label>
                              <div id="delivery_order_kirim_nama"></div>
                           </div>
                           <div class="col-xs-4">
                              <label>Alamat:</label>
                              <div id="delivery_order_kirim_alamat"></div>
                           </div>
                           <div class=" col-xs-4">
                              <label>Area:</label>
                              <div id="delivery_order_kirim_area"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row" id="panel-factory">
               <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                     <div class="x_title">
                        <h4 class="pull-left">Pabrik</h4>
                        <div class="clearfix"></div>
                     </div>
                     <div class="factory-info">
                        <div class="row">
                           <div class="col-xs-4">
                              <label>Nama:</label>
                              <div id="delivery_order_ambil_nama"></div>
                           </div>
                           <div class="col-xs-4">
                              <label>Alamat:</label>
                              <div id="delivery_order_ambil_alamat"></div>
                           </div>
                           <div class="col-xs-4">
                              <label>Area:</label>
                              <div id="delivery_order_ambil_area"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class=" row" id="panel-sku">
               <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                     <div class="x_title">
                        <h4 class="pull-left">Barang Yang Dikirim</h4>
                        <div class="clearfix"></div>
                     </div>
                     <table class="table table-bordered table-striped" id="tabledodetail">
                        <thead>
                           <tr>
                              <th>Kode SKU</th>
                              <th>Kode SKU Pabrik</th>
                              <th>SKU</th>
                              <th>Kemasan</th>
                              <th>Satuan</th>
                              <th>Req Exp Date?</th>
                              <th>Keterangan</th>
                              <th>Qty Req</th>
                              <th>Qty Kirim</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <span id="loadingbkb" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
            <!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbstandar">Simpan</button> -->
            <button type="button" class="btn btn-danger" id="btnback" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>