<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-NOKOREKSIPALLET">No Koreksi Pallet</label>
                                    <input type="text" class="form-control" name="koreksi_pallet_no" id="koreksi_pallet_no" value="<?= $header->kode ?>" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGALKOREKSI">Tanggal Koreksi</label>
                                    <input type="date" class="form-control" name="koreksi_pallet_tanggal" id="koreksi_pallet_tanggal" value="<?= $header->tgl ?>" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-GUDANG">Gudang</label>
                                    <input type="text" name="koreksi_pallet_gudang" id="koreksi_pallet_gudang" class="form-control" readonly value="<?= $header->depo_detail_nama ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-PALLETKODE">Kode Pallet</label>
                                    <input type="text" name="koreksi_pallet_kode_pallet" id="koreksi_pallet_kode_pallet" class="form-control" readonly value="<?= $header->pallet_kode ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                            <input type="text" class="form-control" readonly value="<?= $header->tipe ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGALBUATPALLET">Tgl Buat pallet</label>
                                    <input type="text" name="koreksi_pallet_tgl_buat_pallet" id="koreksi_pallet_tgl_buat_pallet" class="form-control" readonly value="<?= $header->tgl_pallet ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-PEMBUATPALLET">Pembuat Pallet</label>
                                    <input type="text" name="koreksi_pallet_pembuat_pallet" id="koreksi_pallet_pembuat_pallet" class="form-control" readonly value="<?= $header->pallet_who_create ?>">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="koreksi_pallet_keterangan" id="koreksi_pallet_keterangan" readonly><?= $header->keterangan ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>