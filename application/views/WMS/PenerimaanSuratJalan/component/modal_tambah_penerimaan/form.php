<div class="row" id="form-tambah-penerimaan-modal">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
                                <input type="hidden" name="count_error" id="count_error" class="form-control">
                                <input type="text" class="form-control" name="doc_batch" id="doc_batch" placeholder="auto generate" required readonly />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label name="CAPTION-TANGGAL">Tanggal</label>
                                <input type="date" class="form-control input-date-start" name="tgl" id="tgl" placeholder="dd-mm-yyyy" value="<?php echo Date('Y-m-d') ?>" readonly>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                        <select class="form-control select2 perusahaan" name="perusahaan" id="perusahaan" required></select>
                    </div>
                    <div class="form-group">
                        <label name="CAPTION-PENYALURATAUPRINCIPLE">Penyalur / Principle</label>
                        <select class="form-control select2 principle" name="principle" id="principle" required></select>
                    </div>
                    <input type="text" class="form-control" name="sub_principle" id="sub_principle" placeholder="Nama Principle" required readonly />
                </div>
                <div class="col-md-4">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label name="CAPTION-TIPEPENERIMAAN">Tipe Penerimaan</label>
                                <select class="form-control select2 tipe_penerimaan" name="tipe_penerimaan" id="tipe_penerimaan" required></select>
                            </div>
                            <!-- <div class="form-group">
                                    <label>Tempo Pembayaran</label>
                                    <input type="text" class="form-control" name="tempo_pembayaran" id="tempo_pembayaran" placeholder="30 Hari" required readonly />
                                </div> -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label name="CAPTION-STATUS">Status</label>
                                <select class="form-control select2" name="status" id="status" required disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label name="CAPTION-FILEATTACHMENT">File Attachment</label>
                        <input type="file" class="form-control upload up" name="file" id="file" placeholder="upload attachment" onchange="previewFile()" required accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />

                        <div class="row" id="show-file" style="margin-top: 5px;"></div>
                    </div>

                    <div class="form-group">
                        <label name="CAPTION-NOSURATJALANEKSTERNAL">No. Surat Jalan Eksternal</label>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="no_surat_jalan" id="no_surat_jalan" placeholder="No. Surat Jalan Eksternal" required />
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-center" value="-" readonly>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="no_surat_jalan_counter" id="no_surat_jalan_counter" readonly required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label name="CAPTION-KETERANGAN">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control keterangan" placeholder="keterangan" style="width: 100%"></textarea>
                    </div>
                    <div class="form-group">
                        <label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
                        <div class="row">
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="no_kendaraan" id="no_kendaraan" placeholder="No. Kendaraan" required />
                            </div>
                            <div style="float:right; margin-right: 10px; margin-bottom: 5px;">
                                <button type="button" class="btn btn-primary" id="pilih-sku"><i class="fas fa-box"></i> <label name="CAPTION-PILIHSKU">Pilih SKU</label></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>