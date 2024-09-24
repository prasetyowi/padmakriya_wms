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
                                    <input type="hidden" name="depo" id="depo" data-id="<?= $depo->depo_id ?>"
                                        value="<?= $depo->depo_nama ?>">
                                    <input type="hidden" name="depo_detail_id" id="depo_detail_id">
                                    <input type="hidden" name="pallet_id" id="pallet_id">
                                    <input type="hidden" name="client_wms_id" id="client_wms_id">
                                    <input type="hidden" name="principle_id" id="principle_id">
                                    <input type="text" class="form-control" name="koreksi_pallet_no"
                                        id="koreksi_pallet_no" value="Auto" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGALKOREKSI">Tanggal Koreksi</label>
                                    <input type="date" class="form-control" name="koreksi_pallet_tanggal"
                                        id="koreksi_pallet_tanggal" value="<?= date('Y-m-d') ?>" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-GUDANG">Gudang</label>
                                    <input type="text" name="koreksi_pallet_gudang" id="koreksi_pallet_gudang"
                                        class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-PALLETKODE">Kode Pallet</label>
                                    <input type="text" name="koreksi_pallet_kode_pallet" id="koreksi_pallet_kode_pallet"
                                        class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                            <select id="koreksi_pallet_tipe_transaksi" name="koreksi_pallet_tipe_transaksi"
                                class="form-control select2">
                                <option value="">--<label name="CAPTION-PILIHTIPETRANSAKSI">Pilih
                                        TipeTransaksi</label>--</option>
                                <?php foreach ($typeTransactions as $typeTransaction) { ?>
                                <option value="<?= $typeTransaction->id ?>" selected><?= $typeTransaction->nama ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGALBUATPALLET">Tgl Buat pallet</label>
                                    <input type="text" name="koreksi_pallet_tgl_buat_pallet"
                                        id="koreksi_pallet_tgl_buat_pallet" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-PEMBUATPALLET">Pembuat Pallet</label>
                                    <input type="text" name="koreksi_pallet_pembuat_pallet"
                                        id="koreksi_pallet_pembuat_pallet" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control"
                                name="koreksi_pallet_keterangan" id="koreksi_pallet_keterangan"
                                placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>