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
                                    <input type="hidden" class="form-control" id="error_save_data_edit" />
                                    <input type="hidden" class="form-control" id="sj_id" />
                                    <input type="hidden" class="form-control" id="global_pallet_id_edit" />
                                    <input type="hidden" class="form-control" id="penerimaan_pembelian_id" value="<?= $header->id ?>" />
                                    <input type="hidden" class="form-control" id="client_wms_id" value="<?= $header->client_wms_id ?>" />
                                    <input type="hidden" class="form-control" id="principle_id" value="<?= $header->principle_id ?>" />
                                    <input type="hidden" class="form-control" id="session_depo_edit" value="<?= $this->session->userdata('depo_id') ?>" />
                                    <input type="text" class="form-control" name="doc_po" id="doc_po" placeholder="Auto" required readonly value="<?= $header->kode ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control" name="tgl_edit" id="tgl_edit" value="<?= $header->tgl ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label name="CAPTION-JASAPENGANGKUT">Jasa Pengangkut</label>
                                    <input type="text" class="form-control" name="expedisi_edit" id="expedisi_edit" value="<?= $header->e_kode ?> - <?= $header->e_nama ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
                                    <input type="text" class="form-control" name="no_kendaraan_edit" id="no_kendaraan_edit" required readonly value="<?= $header->nopol ?>" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-NAMAPENGEMUDI">Nama Pengemudi</label>
                            <input type="text" class="form-control" name="nama_pengemudi_edit" id="nama_pengemudi_edit" required readonly value="<?= $header->pengemudi ?>" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-LOKASIPENERIMA">Lokasi Penerima</label>
                                    <input type="text" class="form-control" name="gudang_penerima_edit" id="gudang_penerima_edit" required readonly value="<?= $header->gudang ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="keterangan_edit" id="keterangan_edit" placeholder="Keterangan"><?= $header->keterangan ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>