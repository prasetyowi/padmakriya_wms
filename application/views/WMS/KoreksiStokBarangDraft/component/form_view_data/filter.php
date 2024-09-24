<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3><strong><label name="CAPTION-DRAFTKOREKSISTOK">Draft Koreksi Stok</label></strong></h3>
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-NODRAFTKOREKSISTOK">No Draft Koreksi Stok</label>
                                    <input type="hidden" name="count_error_view" id="count_error_view">
                                    <input type="hidden" name="global_id_view" id="global_id_view" value="<?= $id ?>">
                                    <input type="hidden" name="depo_view" id="depo_view" data-id="<?= $depo->depo_id ?>" value="<?= $depo->depo_nama ?>">
                                    <input type="text" class="form-control" name="no_koreksi_draft_view" id="no_koreksi_draft_view" value="Auto" readonly required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" class="form-control" name="koreksi_draft_tgl_view" id="koreksi_draft_tgl_view" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-GUDANG">Gudang</label>
                            <select id="gudang_koreksi_draft_view" name="gudang_koreksi_draft_view" class="form-control select2" disabled>
                                <option value="">--<label name="CAPTION-PILIHGUDANG">Pilih Gudang</label>--</option>
                                <?php foreach ($warehouses as $warehouse) { ?>
                                    <option value="<?= $warehouse->id ?>"><?= $warehouse->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-PRINCIPLE">Principle</label>
                            <select id="koreksi_draft_principle_view" name="koreksi_draft_principle_view" class="form-control select2" disabled>
                                <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--
                                </option>
                                <?php foreach ($principles as $principle) { ?>
                                    <option value="<?= $principle->id ?>"><?= $principle->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-CHECKER">Checker</label>
                            <select id="koreksi_draft_checker_view" name="koreksi_draft_checker_view" class="form-control select2" disabled>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                                    <select id="koreksi_draft_tipe_transaksi_view" name="koreksi_draft_tipe_transaksi_view" class="form-control select2" disabled>
                                        <option value="">--<label name="CAPTION-PILIHTIPETRANSAKSI">Pilih Tipe
                                                Transaksi</label>--</option>
                                        <?php foreach ($type_transactions as $type_transaction) { ?>
                                            <option value="<?= $type_transaction->id ?>"><?= $type_transaction->nama ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-STATUS">Status</label>
                                    <input type="text" class="form-control" name="koreksi_draft_status_view" id="koreksi_draft_status_view" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-TIPEDOKUMEN">Tipe Dokumen</label>
                                    <select disabled id="koreksi_draft_tipe_dokumen" name="koreksi_draft_tipe_dokumen" class="form-control select2" onchange="getReferensiDokumen(this.value)">
                                        <option value="">--<label name="CAPTION-PILIHTIPEDOKUMEN">Pilih Tipe
                                                Dokumen</label>--</option>
                                        <?php foreach ($type_document as $typeDocument) { ?>
                                            <option value="<?= $typeDocument->table_name ?>-<?= $typeDocument->table_name_kode ?>-<?= $typeDocument->tipe_dokumen_nama ?>"><?= $typeDocument->tipe_dokumen_nama ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-NOREFERENSIDOKUMEN">No Referensi Dokumen</label>
                                    <input disabled readonly type="text" class="form-control" name="koreksi_draft_no_referensi_dokumen" id="koreksi_draft_no_referensi_dokumen" disabled required onkeyup="autoCompleteReferensiDokumen(this)">
                                    <!-- <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                                        <table class="table table-striped table-sm table-hover" id="table-fixed">
                                            <tbody id="konten-table"></tbody>
                                        </table>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <input type="checkbox" style="margin-top: 30px;transform: scale(1.5)" name="koreksi_draft_approval_view" id="koreksi_draft_approval_view" value="In Progress Approval" disabled>
                            <span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
                        </div> -->
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-KETERANGAN">Keterangan</label>
                            <textarea cols="10" style="width: 100%;height: 103px" class="form-control" name="koreksi_draft_keterangan_view" id="koreksi_draft_keterangan_view" placeholder="Keterangan" disabled></textarea>
                        </div>

                        <div class="row">
                            <div id="hideFileNull">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
                                        <div class="row" id="show-file"></div>
                                        <div id="hide-file">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="checkbox" style="margin-top: 30px;transform: scale(1.5)" name="koreksi_draft_approval_view" id="koreksi_draft_approval_view" value="In Progress Approval" disabled>
                                    <span style="margin-left: 10px;font-size:15px;font-weight:700" name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>