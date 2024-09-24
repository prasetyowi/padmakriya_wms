<style>
table {
    display: block;
    overflow-x: scroll;
}
</style>
<br>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><span name="CAPTION-PROSESKONFIRMASIPENGIRIMAN">Proses Konfirmasi Pengiriman</span></h3>
            </div>
            <div style="float: right">
                <a href="<?= base_url() ?>WMS/SuratTugasPengiriman/print/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>"
                    class="btn btn-primary"><i class="fa fa-print"></i> <span name="CAPTION-CETAK">Cetak</span></a>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><span name="CAPTION-PROSESKONFIRMASIPENGIRIMAN">Proses Konfirmasi
                    Pengiriman</span></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="hidden" id="delivery_order_batch_id" class="form-control"
                                    name="delivery_order_batch_id" value="<?php echo $delivery_order_batch_id; ?>" />
                                <input type="text" id="filter_fdjr_no" class="form-control" name="filter_fdjr_no"
                                    value="" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-TANGGALFDJR">Tanggal FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="filter_fdjr_date" class="form-control" name="filter_fdjr_date"
                                    value="" disabled />
                                <input type="hidden" id="filter_update_fdjr_date" class="form-control"
                                    name="filter_update_fdjr_date" value="" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPE">Tipe</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_type" class="form-control" style="width:100%" disabled></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-AREA">Area</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea id="filter_fdjr_area" class="form-control" disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSFDJR">Status
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_status" class="form-control" style="width:100%"
                                    disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_tipe_ekspedisi" class="form-control" style="width:100%"
                                    disabled></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-ARMADA">Armada</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_armada" class="form-control" style="width:100%"
                                    disabled></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-DRIVER">Driver</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_driver" class="form-control" style="width:100%"
                                    disabled></select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KMAKHIR">KM
                                Akhir</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="filter_fdjr_km" class="form-control" name="filter_fdjr_km" value="0" />
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><span name="CAPTION-DAFTARDO">Daftar DO</span></div>
            <input type="hidden" id="txt_jumlah" name="txt_jumlah" value="0" />
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="tabledomenu" width="100%" class="table table-bordered">
                        <thead>
                            <tr class="bg-info">
                                <th><span name="CAPTION-NO">No</span></th>
                                <th><span name="CAPTION-TANGGALDO">Tgl DO</span></th>
                                <th><span name="CAPTION-NODO">No DO</span></th>
                                <th><span name="CAPTION-NOSO">No SO</span></th>
                                <th><span name="CAPTION-NOSOEKSTERNAL">No SO Eksternal</span></th>
                                <th><span name="CAPTION-TIPE">Tipe</span></th>
                                <th><span name="CAPTION-CUSTOMER">Customer</span></th>
                                <th style="width:20%"><span name="CAPTION-ALAMAT">Alamat Customer</span></th>
                                <th><span name="CAPTION-TELP">No Telp</span></th>
                                <th><span name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</span></th>
                                <th><span name="CAPTION-TOTALTAGIHAN">Total Tagihan</span></th>
                                <th style="width:15%;"><span name="CAPTION-TOTALBAYAR">Total Bayar</span></th>
                                <th><span name="CAPTION-BIAYALAIN">Biaya Lain</span></th>
                                <th><span name="CAPTION-NOURUTRUTE">No Urut Rute</span></th>
                                <th><input type="checkbox" name="select-terkirim" id="select-terkirim" value="1"> <span
                                        name="CAPTION-TERKIRIM">Terkirim</span></th>
                                <!-- <th><input type="checkbox" name="select-terkirim-sebagian" id="select-terkirim-sebagian" value="1"> Terkirim Sebagian</th> -->
                                <th><span name="CAPTION-TERKIRIMSEBAGIAN">Terkirim Sebagian</span></th>
                                <th><input type="checkbox" name="select-gagal" id="select-gagal" value="1"> <span
                                        name="CAPTION-GAGALKIRIM">Gagal Kirim</span></th>
                                <!-- <th><span name="CAPTION-KIRIMULANG">Kirim Ulang</span></th> -->
                                <th style="width:20%"><span name="CAPTION-ALASANGAGALKIRIM">Alasan Gagal Kirim</span>
                                </th>
                                <!-- <th><input type="checkbox" name="select-titipan" id="select-titipan" value="1"> <span name="CAPTION-TITIPAN">Ada Titipan</span></th> -->
                                <th><span name="CAPTION-TITIPAN">Ada Titipan</span></th>
                                <th><span name="CAPTION-ACTION">Action</span></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr style="background-color:#8F43EE;font-weight: bold;color:white;">
                                <td class="text-center" colspan="10">Grand Total</td>
                                <td class="text-center"><span id="total_tagihan">0</span></td>
                                <td class="text-center"><span id="total_bayar">0</span></td>
                                <td class="text-center" colspan="10"></td>
                            <tr>
                        </tfoot>
                    </table>
                </div>
                <br><br>
                <div class="row">
                    <div class="pull-right">
                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                            Loading...</span>
                        <button type="button" id="btn_konfirmasi_pengiriman" class="btn btn-primary"
                            style="display: none;"><i class="fa fa-check"></i> <span
                                name="CAPTION-KONFIRMASI">Konfirmasi Pengiriman
                                Selesai</span></button>
                        <button type="button" id="btn_simpan" class="btn btn-success"><i class="fa fa-save"></i> <span
                                name="CAPTION-SAVE">Simpan</span></button>
                        <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/SuratTugasPengirimanMenu"
                            type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span
                                name="CAPTION-BACK">Kembali</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="previewformdoterkirimsebagian" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="width: 90%;">
        <!-- Modal content-->
        <form class="terkirim_sebagian_form" method="post" action="#">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><label>Form Delivery Order</label></h4>
                </div>
                <div class="modal-body form-horizontal form-label-left">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="col-4 col-sm-2">
                                    <label name="CAPTION-NODO">NO DO</label>
                                </div>
                                <div class="col-8 col-sm-10">
                                    <input type="text" id="delivery_order_kode" class="form-control" value=""
                                        readonly />
                                    <input type="hidden" id="delivery_order_id" class="form-control" value=""
                                        readonly />
                                    <input type="hidden" id="txt_jumlah_do" name="txt_jumlah_do" value="0" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="tabledetaildo" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center"><span name="CAPTION-PRINCIPLE">Principal</span></th>
                                    <th class="text-center"><span name="CAPTION-BRAND">Brand</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                    <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                    <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                    <th class="text-center"><span name="CAPTION-EXPDATE">Exp Date</span></th>
                                    <th class="text-center"><span name="CAPTION-QTY">Qty</span></th>
                                    <th class="text-center"><span name="CAPTION-QTYTERKIRIM">Qty Kirim</span></th>
                                    <th class="text-center"><span name="CAPTION-REASON">Reason</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                    <button type="submit" class="btn btn-success" id="btnsaveterkirimsebagian"><span
                            name="CAPTION-SAVE">Simpan</span></button>
                    <button type="button" class="btn btn-danger" id="btnback"><span
                            name="CAPTION-BACK">Kembali</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk menampilkan Tambah Biaya Lain !-->
<div class="modal fade" id="modalAddBiaya" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-TAMBAHBIAYALAIN">Tambah Biaya Lain</label></h4>
            </div>
            <div class="modal-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <button type="button" id="btnaddbiayadetail" class="btn btn-primary"><i
                                        class="fa fa-plus"></i> <span name="CAPTION-TAMBAHBIAYALAIN">Tambah Biaya
                                        Lain</span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 nopadding ">
                                <table id="tableaddbiaya" width="100%" class="table table-striped">
                                    <thead>
                                        <tr class="bg-info">
                                            <th class="text-center"><span name="CAPTION-NAMABIAYA">Nama Biaya</span>
                                            </th>
                                            <th class="text-center"><span name="CAPTION-KETERANGAN">Keterangan</span>
                                            </th>
                                            <th class="text-center"><span name="CAPTION-NOMINALBIAYA">Kode SKU</span>
                                            </th>
                                            <th class="text-center" id="opsibiaya"><span name="CAPTION-OPSI">Nama
                                                    Barang</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnsimpanaddbiaya"><label
                        name="CAPTION-SIMPAN">Simpan</label></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback"><label
                        name="CAPTION-KEMBALI">Kembali</label></button>
            </div>
        </div>
    </div>
</div>