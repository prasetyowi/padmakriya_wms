<?php foreach ($FDJRHeader as $header) : ?>
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3><span name="CAPTION-PROSESBTB">Proses BTB</span></h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="panel panel-default">
                <div class="panel-heading"><span name="CAPTION-PROSESBTB">Proses BTB</span></div>

                <div class="panel-body form-horizontal form-label-left">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No
                                    FDJR</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="hidden" id="delivery_order_batch_id" class="form-control" name="delivery_order_batch_id" value="<?= $header['delivery_order_batch_id'] ?>" />
                                    <input type="text" class="form-control" id="filter_fdjr_no" name="filter_fdjr_no" value="<?= $header['delivery_order_batch_kode'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALFDJR">Tanggal FDJR</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_tgl" name="filter_fdjr_tgl" value="<?= $header['delivery_order_batch_tanggal'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_tgl_kirim" name="filter_fdjr_tgl_kirim" value="<?= $header['delivery_order_batch_tanggal_kirim'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPE">Tipe</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_tipe" name="filter_fdjr_tipe" value="<?= $header['tipe_delivery_order_alias'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-AREA">Area</label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea class="form-control" id="filter_fdjr_area" disabled><?php
                                                                                                    $area = "";
                                                                                                    foreach ($FDJRArea as $key => $val) {
                                                                                                        if ($key + 1 == count($FDJRArea)) {
                                                                                                            $area .= $val['area_nama'];
                                                                                                        } else {
                                                                                                            $area .= $val['area_nama'] . ",";
                                                                                                        }
                                                                                                    }

                                                                                                    echo preg_replace('/\s+/', '', $area);
                                                                                                    ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_ekspedisi" name="filter_fdjr_ekspedisi" value="<?= $header['tipe_ekspedisi_nama'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-ARMADA">Armada</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_armada" name="filter_fdjr_armada" value="<?= $header['kendaraan_nopol'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_driver" name="filter_fdjr_driver" value="<?= $header['karyawan_nama'] ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSFDJR">Status
                                    FDJR</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" id="filter_fdjr_status" name="filter_fdjr_status" value="<?= $header['delivery_order_batch_status'] ?>" readonly />
                                </div>
                            </div>
                            <!-- <div class="form-group">
								<label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KMAKHIR">KM Akhir</label>
								<div class="col-md-6 col-sm-6">
									<input type="text" class="form-control" id="filter_fdjr_km" name="filter_fdjr_km" value="<?= $header['kendaraan_km_akhir'] ?>" readonly />
								</div>
							</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-check text-right">
                        <span name="CAPTION-DAFTARDO" style="float: left;">Daftar DO</span>
                        <input type="checkbox" style="transform: scale(1.5); margin-right: 0.5rem;" class="form-check-input" id="showAll" checked>
                        <label class="form-check-label" for="showAll" style="vertical-align: middle">Tampilkan gagal
                            kirim</label>
                    </div>
                </div>
                <input type="hidden" id="txt_jumlah" name="txt_jumlah" value="0" />
                <div class="panel-body form-horizontal form-label-left">
                    <div class="row" style="display: block;overflow-x: auto;">
                        <table id="tabledomenu" width="100%" class="table table-bordered">
                            <thead>
                                <tr class="bg-info">
                                    <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                    <th class="text-center"><span name="CAPTION-TANGGALDO">Tgl DO</span></th>
                                    <th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
                                    <th class="text-center"><span name="CAPTION-TIPE">Tipe</span></th>
                                    <th class="text-center"><span name="CAPTION-CUSTOMER">Customer</span></th>
                                    <th class="text-center" style="width:20%"><span name="CAPTION-ALAMAT">Alamat
                                            Customer</span></th>
                                    <th class="text-center"><span name="CAPTION-TELP">No Telp</span></th>
                                    <th class="text-center"><span name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</span></th>
                                    <th class="text-center"><span name="CAPTION-NOURUTRUTE">No Urut Rute</span></th>
                                    <th class="text-center"><span name="CAPTION-TERKIRIM">Terkirim</span></th>
                                    <th class="text-center"><span name="CAPTION-TERKIRIMSEBAGIAN">Terkirim Sebagian</span>
                                    </th>
                                    <th class="text-center"><span name="CAPTION-GAGALKIRIM">Gagal Kirim</span></th>
                                    <th class="text-center"><span name="CAPTION-KIRIMULANG">Kirim Ulang</span></th>
                                    <th class="text-center" style="width:20%"><span name="CAPTION-ALASANGAGALKIRIM">Alasan
                                            Gagal Kirim</span>
                                    </th>
                                    <th class="text-center"><span name="CAPTION-TITIPAN">Ada Titipan</span></th>
                                    <th class="text-center"><span name="CAPTION-ACTION">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($FDJRDetail as $i => $detail) : ?>
                                    <tr id="row-<?= $i ?>" class="row-item">
                                        <td class="text-center"><?= $no ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_tgl_buat_do'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_kode'] ?></td>
                                        <td class="text-center"><?= $detail['tipe_delivery_order_alias'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_kirim_nama'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_kirim_alamat'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_kirim_telp'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_tipe_pembayaran'] ?></td>
                                        <td class="text-center"><?= $detail['delivery_order_no_urut_rute'] ?></td>
                                        <td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_<?= $i; ?>" value="<?= $detail['delivery_order_id'] ?>" <?= $detail['delivery_order_status'] == 'delivered' ? 'checked' : '' ?> disabled></td>
                                        <td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_<?= $i; ?>" value="<?= $detail['delivery_order_id'] ?>" <?= $detail['delivery_order_status'] == 'partially delivered' ? 'checked' : '' ?> disabled></td>
                                        <td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_<?= $i; ?>" value="<?= $detail['delivery_order_id'] ?>" <?= $detail['delivery_order_status'] == 'not delivered' ? 'checked' : '' ?> disabled></td>
                                        <td><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_<?= $i; ?>" value="<?= $detail['delivery_order_id'] ?>" <?= $detail['delivery_order_status'] == 'rescheduled' ? 'checked' : '' ?> disabled></td>
                                        <td class="text-center"><?= $detail['reason_keterangan'] ?></td>
                                        <td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_<?= $i; ?>" value="<?= $detail['delivery_order_id'] ?>" <?= $detail['ada_titipan'] == 1 ? 'checked' : '' ?> disabled></td>
                                        <td>
                                            <?php if ($detail['sudah_btb'] == "BELUM BTB") { ?>
                                                <!-- <a href="<?php echo base_url() ?>WMS/PenerimaanRetur/ProsesBTB/?delivery_order_batch_id=<?= $detail['delivery_order_batch_id'] ?>&delivery_order_id=<?= $detail['delivery_order_id'] ?>"
                                        type="button" class="btn btn-primary" id="btn_proses_do_retur_<?= $i ?>"><i
                                            class="fas fa-pencil-alt"></a> -->
                                            <?php } else if ($detail['sudah_btb'] == "SUDAH BTB") { ?>
                                                <a href="<?php echo base_url() ?>WMS/PenerimaanRetur/BTBDetail/?delivery_order_batch_id=<?= $detail['delivery_order_batch_id'] ?>&delivery_order_id=<?= $detail['delivery_order_id'] ?>" type="button" class="btn btn-primary" id="btn_view_do_retur_<?= $i ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="<?php echo base_url() ?>WMS/PenerimaanRetur/print_btb/?delivery_order_batch_id=<?= $detail['delivery_order_batch_id'] ?>&delivery_order_id=<?= $detail['delivery_order_id'] ?>&nama_toko=<?= $detail['delivery_order_kirim_nama'] ?>" type="button" target="_blank" class="btn btn-success" id="">
                                                    <i class="fas fa-print"></i>
                                                </a>

                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="pull-left">
                            <?php foreach ($is_cek as $data) {
                                if ($data == 'ADA KIRIMAN') { ?>
                                    <a type="button" id="btn_btb_kiriman" target="_blank" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == "completed" || $header['delivery_order_batch_status'] == "Closing Delivery Confirm" ? 'display:none' : '' ?>"><i class="fa fa-list-check"></i>
                                        <span name="CAPTION-BTBKIRIMAN">BTB Kiriman</span></a>
                                <?php } else if ($data == 'ADA RETUR') { ?>
                                    <a type="button" id="btn_btb_retur" class="btn btn-warning" target="_blank" style="<?= $header['delivery_order_batch_status'] == "completed" || $header['delivery_order_batch_status'] == "Closing Delivery Confirm" ? 'display:none' : '' ?>"><i class="fa fa-list-check"></i>
                                        <span name="CAPTION-BTBRETUR">BTB Retur</span></a>
                                <?php }
                                ?>
                            <?php } ?>
                        </div>

                        <div class="pull-right">
                            <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                Loading...</span>
                            <button type="button" id="btn_konfirmasi_pengiriman" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == "completed" || $header['delivery_order_batch_status'] == "Closing Delivery Confirm" ? 'display:none' : '' ?>"><i class="fa fa-check"></i> <span name="CAPTION-KONFIRMASI">Konfirmasi Pengiriman
                                    Selesai</span></button>
                            <!-- <button type="button" id="btn_simpan" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-SAVE">Simpan</span></button> -->
                            <a href="<?php echo base_url() ?>WMS/PenerimaanRetur/PenerimaanReturMenu" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>