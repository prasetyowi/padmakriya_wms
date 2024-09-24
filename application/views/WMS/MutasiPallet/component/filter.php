<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <!-- <h3><strong>Filter Data</strong></h3> -->
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-NOMUTASI">No. Mutasi</label>
                                    <input type="hidden" class="form-control" id="global_id" value="<?= $id ?>" />
                                    <input type="hidden" class="form-control" id="principle" />
                                    <input type="text" class="form-control" name="no_mutasi" id="no_mutasi"
                                        placeholder="Auto" required readonly>
                                    <input type="hidden" class="form-control" name="error" id="error" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control input-date-start" name="tgl" id="tgl"
                                        placeholder="dd-mm-yyyy" value="<?php echo Date('Y-m-d') ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-NODRAFTMUTASI">No. Draft Mutasi</label>
                            <select class="form-control select2" name="no_draft_mutasi" id="no_draft_mutasi" required
                                disabled></select>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-NOPENERIMAAN">No. Penerimaan</label>
                            <input type="text" class="form-control" name="no_penerimaan" id="no_penerimaan"
                                placeholder="No. Penerimaan" required readonly />
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
                            <input type="text" class="form-control" name="no_sj" id="no_sj"
                                placeholder="No. Surat Jalan" required readonly />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-GUDANGASAL">Gudang Asal</label>
                                    <select class="form-control select2" name="gudang_asal" id="gudang_asal" required
                                        disabled></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</label>
                                    <select class="form-control select2" name="gudang_tujuan" id="gudang_tujuan"
                                        required disabled></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-CHECKER">Checker</label>
                                    <input type="text" class="form-control" name="checker" id="checker"
                                        placeholder="Checker" readonly required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                                    <input type="text" class="form-control" name="tipe_transaksi" id="tipe_transaksi"
                                        placeholder="Tipe Transaksi" readonly required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan"
                                id="keterangan" placeholder="Keterangan"></textarea>
                        </div>


                        <div class="form-group">
                            <label name="CAPTION-STATUS">Status</label>
                            <input type="text" class="form-control" name="status" id="status" placeholder="Status"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>