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
                                    <label name="CAPTION-NODOKUMENPOREC">No. Dokumen PO Rec</label>
                                    <input type="hidden" name="global_id_view" id="global_id_view" class="global_id_view" value="<?= $id ?>">
                                    <input type="text" class="form-control" name="doc_po_view" id="doc_po_view" value="<?= $header->kode ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control" name="tgl_view" id="tgl_view" value="<?= $header->tgl ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label name="CAPTION-JASAPENGANGKUT">Jasa Pengangkut</label>
                                    <input type="text" class="form-control" name="expedisi_view" id="expedisi_view" value="<?= $header->e_kode . " - " . $header->e_nama ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
                                    <input type="text" class="form-control" name="no_kendaraan_view" id="no_kendaraan_view" required readonly value="<?= $header->nopol ?>" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-NAMAPENGEMUDI">Nama Pengemudi</label>
                            <input type="text" class="form-control" name="nama_pengemudi_view" id="nama_pengemudi_view" value="<?= $header->pengemudi ?>" required readonly />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-LOKASIPENERIMA">Lokasi Penerima</label>
                                    <input type="text" class="form-control" name="gudang_penerima_view" id="gudang_penerima_view" required readonly value="<?= $header->gudang ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <?php if ($header->penerimaan_surat_jalan_reason != null) { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label name="CAPTION-KETERANGAN">Keterangan</label>
                                        <textarea cols="10" style="width: 100%;height: 51px" class="form-control" name="keterangan_view" id="keterangan_view" readonly><?= $header->keterangan ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <<label name="CAPTION-REASONCANCEL">Reason Cancel</label>
                                            <textarea cols="10" style="width: 100%;height: 51px" class="form-control" name="reason_view" id="reason_view" readonly><?= $header->penerimaan_surat_jalan_reason ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
                                <label name="CAPTION-KETERANGAN">Keterangan</label>
                                <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_view" id="keterangan_view" readonly><?= $header->keterangan ?></textarea>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>