<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
                                    <input type="hidden" name="id" id="id" value="<?= $id ?>">
                                    <input type="hidden" name="principle_id_view" id="principle_id_view" value="<?= $data->principle_id ?>">
                                    <input type="hidden" name="reason_detail_id" id="reason_detail_id" value="<?= $data->reason_surat_jalan_detail_id ?>">
                                    <input type="hidden" name="count_error" id="count_error_reason" class="form-control">
                                    <input type="text" class="form-control" name="doc_batch_view" id="doc_batch_view" placeholder="auto generate" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control input-date-start" name="tgl_view" id="tgl_view" placeholder="dd-mm-yyyy" value="<?php echo Date('Y-m-d') ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                            <select class="form-control select2" name="perusahaan_view" id="perusahaan_view" required></select>
                        </div>
                        <div class="form-group">
                            <label name="CAPTION-PENYALURATAUPRINCIPLE">Penyalur / Principle</label>
                            <select class="form-control select2" name="principle_view" id="principle_view" required></select>
                        </div>
                        <input type="text" class="form-control" name="sub_principle_view" id="sub_principle_view" placeholder="Nama Principle" required readonly />
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TIPEPENERIMAAN">Tipe Penerimaan</label>
                                    <select class="form-control select2 tipe_penerimaan_view" name="tipe_penerimaan_view" id="tipe_penerimaan_view" required disabled></select>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Tempo Pembayaran</label>
                                    <input type="text" class="form-control" name="tempo_pembayaran_view" id="tempo_pembayaran_view" placeholder="30 Hari" required readonly />
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-STATUS">Status</label>
                                    <input class="form-control" type="text" name="status_view" id="status_view" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label name="CAPTION-FILEATTACHMENT">File Attachment</label>
                            <div class="row" id="show-file-view" style="margin-top: 5px;"></div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
                            <input type="text" class="form-control" name="no_surat_jalan_view" id="no_surat_jalan_view" placeholder="No. Surat Jalan Eksternal" required readonly />
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-REASON">Reason</label>
                            <select name="reason" id="reason" class="form-control select2"></select>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea name="keterangan_view" id="keterangan_view" cols="30" rows="4" class="form-control keterangan" placeholder="keterangan" style="width: 100%;" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
                            <input type="text" class="form-control" name="no_kendaraan_view" id="no_kendaraan_view" placeholder="No. Kendaraan" required readonly />
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-REASONDETAIL">Reason Detail</label>
                            <select name="reason_detail" id="reason_detail" class="form-control select2"></select>
                        </div>
                    </div>
                    <!-- <div style="float:right">
                        <button type="button" class="btn btn-primary" id="pilih-sku-edit"><i class="fas fa-box"></i> Pilih SKU</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>