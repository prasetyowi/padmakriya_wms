<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3 name="CAPTION-CLOSINGPENERIMAAN">Closing Penerimaan</h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOMORFDJR">Nomor
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="no_fdjr" class="form-control" name="no_fdjr" autocomplete="off" readonly>
                                <input type="hidden" id="id_do_batch" class="form-control" name="id_do_batch" autocomplete="off" value="<?= $id ?>" readonly>
                                <input type="hidden" id="lastUpdateTgl" class="form-control" name="lastUpdateTgl" autocomplete="off" value="<?= $lastUpdate['tglUpd'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALFDJR">Tanggal
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="tgl_fdjr" class="form-control" name="tgl_fdjr" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPE">Tipe</label>
                            <div class="col-md-6 col-sm-6">
                                <select disabled id="tipe" name="tipe" class="form-control select2" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-AREA">Area</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea id="area" class="form-control" disabled><?php foreach ($GetArea as $data) {
                                                                                        echo $data['area_nama'] . ', ';
                                                                                    } ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSFDJR">Status
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <select disabled id="status_fdjr" name="status_fdjr" class="form-control select2" style="width:100%">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"
                                name="CAPTION-TIPEEKSPEDISI">Tipe
                                Ekspedisi</label>
                            <div class="col-md-6 col-sm-6">
                                <select disabled id="tipe_ekspedisi" name="tipe_ekspedisi" class="form-control select2"
                                    style="width:100%">
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-ARMADA">Armada</label>
                            <div class="col-md-6 col-sm-6">
                                <select disabled id="armada" name="armada" class="form-control select2" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-SALES">Sales</label>
                            <div class="col-md-6 col-sm-6">
                                <select disabled id="pengemudi" name="pengemudi" class="form-control select2" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-JUMLAHTERIMA">Jumlah
                                Terima</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="number" oninput="this.value = Math.abs(this.value)" value="0" id="viewJumlahTerima" class="form-control" name="viewJumlahTerima" autocomplete="off" disabled>
                                <input type="hidden" value="0" id="delivery_order_batch_nominal_invoice" class="form-control" name="delivery_order_batch_nominal_invoice" autocomplete="off" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="x_panel">
            <div class="x_title">
                <h4 name="CAPTION-LISTDO">List DO</h4>
                <div class="clearfix">
                </div>
            </div>
            <div class="form-horizontal form-label-left">
                <div class="row">
                    <table id="table_list_do" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" name="CAPTION-NO">No</th>
                                <th class="text-center" name="CAPTION-TANGGALDO">Tanggal DO</th>
                                <th class="text-center" name="CAPTION-NODO">No. DO</th>
                                <th class="text-center" name="CAPTION-CUSTOMER">Customer</th>
                                <th class="text-center" name="CAPTION-ALAMAT">Alamat</th>
                                <th class="text-center" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</th>
                                <th class="text-center" name="CAPTION-TOTALBAYAR">Total Bayar</th>
                                <!-- <th class="text-center" name="CAPTION-JUMLAHTERIMA">Jumlah Terima</th> -->
                                <!-- <th class="text-center" name="CAPTION-BIAYALAIN">Biaya Lain</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="x_panel">
            <div class="x_title">
                <h4 name="CAPTION-LISTPEMBAYARAN">List Pembayaran</h4>
                <div class="clearfix"></div>
            </div>
            <div class="form-horizontal form-label-left">
                <span id="loadingaddpembayaran" style="display:none;"><i class="fa fa-spinner fa-spin"></i><span name="CAPTION-LOADING">Loading</span>...</span>
                <!-- <button type="button" class="btn btn-success" id="btn_add_pembayaran"><i class="fa fa-plus"></i> <span
                        name="CAPTION-TAMBAHPEMBAYARAN">Tambah Pembayaran</span></button> -->
                <div class="row">
                    <table id="table_list_pembayaran" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" name="CAPTION-NO">No</th>
                                <th class="text-center" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</th>
                                <th class="text-center" name="CAPTION-REFERENSIDOKUMEN">Referensi Dokumen</th>
                                <th class="text-center" name="CAPTION-TGLJATUHTEMPO">Tanggal Jatuh Tempo</th>
                                <th class="text-center" name="CAPTION-NILAIBAYAR">Nilai Bayar</th>
                                <th class="text-center" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($listDOPembayaran) > 0) {
                                $no = 0;
                                foreach ($listDOPembayaran as $data) {
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no + 1 ?> <input type="hidden" id="do_batch_id" value="<?= $data['delivery_order_batch_id'] ?>"></td>
                                        <td class="text-center">
                                            <select id="DeliveryOrderPayment-tipe_pembayaran_id<?= $no ?>" name="DeliveryOrderPayment[tipe_pembayaran_id<?= $no ?>]" class="form-control" style="width:100%" disabled>
                                                <?php if (count($TipePembayaran) != 0) {
                                                    foreach ($TipePembayaran as $row) { ?>
                                                        <option value="<?= $row['tipe_pembayaran_id'] ?>" <?= $row['tipe_pembayaran_id'] == $data['tipe_pembayaran_id'] ? 'selected' : '' ?>>
                                                            <?= $row['tipe_pembayaran_nama'] ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control DeliveryOrderPayment-delivery_order_reff_no" id="DeliveryOrderPayment-delivery_order_reff_no<?= $no ?>" name="DeliveryOrderPayment[delivery_order_reff_no<?= $no ?>]" autocomplete="off" value="<?= $data['delivery_order_reff_no'] ?>" />
                                        </td>
                                        <td class="text-center">
                                            <input disabled type="date" class="form-control DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" id="DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo<?= $no ?>" name="DeliveryOrderPayment[delivery_order_payment_tgl_jatuh_tempo<?= $no ?>]" autocomplete="off" value="<?= $data['delivery_order_payment_tgl_jatuh_tempo'] ?>" />
                                        </td>
                                        <td class="text-center">
                                            <input type="text" class="form-control DeliveryOrderPayment-delivery_order_payment_value" onChange="appendPayment('<?= $no ?>')" id="DeliveryOrderPayment-delivery_order_payment_value<?= $no ?>" name="DeliveryOrderPayment[delivery_order_payment_value<?= $no ?>]" autocomplete="off" value="<?= $data['delivery_order_payment_value'] ?>" />
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary" onClick="ViewModalPembayaranDetail('<?= $no ?>')"><i class="fa fa-add"></i></button>
                                        </td>
                                    </tr>
                                <?php $no++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td class="text-center">
                                        <select id="DeliveryOrderPayment-tipe_pembayaran_id0" name="DeliveryOrderPayment[tipe_pembayaran_id0]" class="form-control" style="width:100%" onChange="handleOnChange('0', this)" disabled>
                                            <?php if (count($TipePembayaran) != 0) {
                                                foreach ($TipePembayaran as $row) { ?>
                                                    <option value="<?= $row['tipe_pembayaran_id'] ?>">
                                                        <?= $row['tipe_pembayaran_nama'] ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control DeliveryOrderPayment-delivery_order_reff_no" id="DeliveryOrderPayment-delivery_order_reff_no0" name="DeliveryOrderPayment[delivery_order_reff_no0]" autocomplete="off" value="" />
                                    </td>
                                    <td class="text-center">
                                        <input disabled type="date" class="form-control" id="DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo0" name="DeliveryOrderPayment[delivery_order_payment_tgl_jatuh_tempo0]" autocomplete="off" value="<?= date('d-m-Y') ?>" />
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control DeliveryOrderPayment-delivery_order_payment_value" id="DeliveryOrderPayment-delivery_order_payment_value0" name="DeliveryOrderPayment[delivery_order_payment_value0]" autocomplete="off" value="" onChange="appendPayment('0')" />
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" onClick="ViewModalPembayaranDetail('0')"><i class="fa fa-add"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div style="float: right;">
            <div class="">
                <span id="loadingsimpanpembayaran" style="display:none;"><i class="fa fa-spinner fa-spin"></i><span name="CAPTION-LOADING">Loading</span>...</span>
                <button type="button" class="btn btn-success" id="btn_simpan_all"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
                <a href="<?= base_url(); ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/KonfirmasiSetoranTunaiCanvasMenu" class="btn btn-info" id="btn_kembali"><i class="fa fa-reply"></i>
                    <label name="CAPTION-KEMBALI">Kembali</label></a>
            </div>
        </div>
    </div>



    <!-- Modal untuk menampilkan Tambah Biaya Lain !-->
    <div class="modal fade" id="modal_pembayaran_detail" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><span name="CAPTION-PEMBAYARANDETAIL">Pembayaran Detail</span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label name="CAPTION-REFERENSIDOKUMEN">Referensi Dokumen</label>
                                    <input type="text" id="filter_referensi_dokumen" class="form-control" readonly />
                                    <input type="hidden" id="filter_index" class="form-control" readonly />
                                    <input type="hidden" id="jml_do" class="form-control" value="0" readonly />
                                </div>
                                <div class="col-xs-6">
                                    <label name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                    <input type="text" id="filter_tipe_pembayaran" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label name="CAPTION-TGLJATUHTEMPO">Tanggal Jatuh Tempo</label>
                                    <input type="date" id="filter_tgl_jatuh_tempo" class="form-control" readonly />
                                </div>
                                <div class="col-xs-6">
                                    <label name="CAPTION-NILAIBAYAR">Nilai Bayar</label>
                                    <input type="text" id="filter_nilai_bayar" class="form-control" readonly />
                                </div>
                            </div>
                        </div>
                    </div><br><br>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped" id="table_list_pembayaran_detail">
                                <thead>
                                    <tr class="bg-primary">
                                        <th class="text-center" style="color:white;"><input type="checkbox" name="select-do" id="select-do" value="1" checked></th>
                                        <th class="text-center" style="color:white;"><span name="CAPTION-NODO">No.
                                                DO</span></th>
                                        <th class="text-center" style="color:white;"><span name="CAPTION-NOSO">No.
                                                SO</span></th>
                                        <th class="text-center" style="color:white;"><span name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</span></th>
                                        <th class="text-center" style="color:white;"><span name="CAPTION-OUTLET">Outlet</span></th>
                                        <th class="text-center" style="color:white;"><span name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <?php foreach ($ListDO as $key => $row) : ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="CheckboxDO" id="check-do-<?= $key ?>" value="<?= $row['delivery_order_draft_id'] ?>">
                                            </td>
                                            <td class="text-center"><?= $row['delivery_order_draft_kode'] ?></td>
                                        </tr>
                                    <?php endforeach; ?> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="loadingkonversi" style="display:none;"><i class="fa fa-spinner fa-spin"></i><label name="CAPTION-LOADING">Loading</label>...</span>
                    <button type="button" class="btn btn-info" id="btn_update_list_pembyaran_detail" onclick="UpdateListPembayaranDetail()"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN">Simpan</span></button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="btn_close_list_pembyaran_detail"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
                </div>
            </div>
        </div>
    </div>
</div>