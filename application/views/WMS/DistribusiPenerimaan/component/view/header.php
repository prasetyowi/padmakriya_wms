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
                                    <label>No. Dokumen</label>
                                    <input type="text" class="form-control" name="no_doc_view" id="no_doc_view" placeholder="Auto" required readonly>
                                    <input type="hidden" class="form-control" name="global_id_view" id="global_id_view" value="<?= $id ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control input-date-start" name="tgl_view" id="tgl_view" placeholder="dd-mm-yyyy" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>No. Penerimaan</label>
                            <select class="form-control select2" name="no_penerimaan_view" id="no_penerimaan_view" disabled required></select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Surat Jalan</label>
                                    <input type="text" class="form-control" name="surat_jalan_view" id="surat_jalan_view" placeholder="No. Surat Jalan" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Surat Jalan Eksternal</label>
                                    <input type="text" class="form-control" name="surat_jalan_eksternal_view" id="surat_jalan_eksternal_view" placeholder="No. Surat Jalan Eksternal" required readonly />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipe Penerimaan</label>
                                    <input type="text" class="form-control" name="tipe_penerimaan_view" id="tipe_penerimaan_view" placeholder="Tipe Penerimaan" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal BTB</label>
                                    <input type="date" class="form-control" name="tgl_btb_view" id="tgl_btb_view" placeholder="dd-mm-yyyy" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Jasa Pengangkut</label>
                                    <input type="text" class="form-control" name="expedisi_view" id="expedisi_view" placeholder="Ekspedisi" readonly required />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>No. Kendaraan</label>
                                    <input type="text" class="form-control" name="no_kendaraan_view" id="no_kendaraan_view" placeholder="No kendaraan" readonly required />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Nama Pengemudi</label>
                                    <input type="text" class="form-control" name="nama_pengemudi_view" id="nama_pengemudi_view" placeholder="Nama Pengemudi" required readonly />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" name="status_view" id="status_view" placeholder="Status" required readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_view" id="keterangan_view" placeholder="Keterangan" readonly></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gudang Asal</label>
                                    <select class="form-control select2" name="gudang_asal_view" id="gudang_asal_view" required disabled></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Checker</label>
                                    <select class="form-control select2" name="checker_view" id="checker_view" required disabled></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>