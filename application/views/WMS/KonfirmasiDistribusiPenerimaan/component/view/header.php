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
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label name="CAPTION-NOMUTASI">No. Mutasi</label>
                                    <input type="hidden" class="form-control" id="global_id_view" value="<?= $id ?>" />
                                    <input type="text" class="form-control" name="no_mutasi_view" id="no_mutasi_view" placeholder="Auto" required readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control input-date-start" name="tgl_view" id="tgl_view" placeholder="dd-mm-yyyy" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-NODRAFTMUTASI">No. Draft Mutasi</label>
                            <input type="text" class="form-control" name="no_draft_mutasi_view" id="no_draft_mutasi_view" placeholder="Auto" required readonly>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-NOPENERIMAAN">No. Penerimaan</label>
                            <input type="text" class="form-control" name="no_penerimaan_view" id="no_penerimaan_view" placeholder="No. Penerimaan" required readonly />
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <!-- <div class="form-group">
                            <label>No. Surat Jalan</label>
                            <input type="text" class="form-control" name="no_sj_view" id="no_sj_view" placeholder="No. Surat Jalan" required readonly />
                        </div> -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-LOKASIASAL">Lokasi Asal</label>
                                    <input type="text" class="form-control" name="gudang_asal_view" id="gudang_asal_view" placeholder="Auto" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-LOKASITUJUAN">Lokasi Tujuan</label>
                                    <input type="text" class="form-control" name="gudang_tujuan_view" id="gudang_tujuan_view" placeholder="Auto" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label name="CAPTION-CHECKER">Checker</label>
                                    <input type="text" class="form-control" name="checker_view" id="checker_view" placeholder="Checker" readonly required />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                                    <input type="text" class="form-control" name="tipe_transaksi_view" id="tipe_transaksi_view" placeholder="Auto" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-STATUS">Status</label>
                            <input type="text" class="form-control" name="status_view" id="status_view" placeholder="Status" readonly>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_view" id="keterangan_view" placeholder="Keterangan" disabled></textarea>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>