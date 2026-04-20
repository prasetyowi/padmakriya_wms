<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-DRAFTMUTASIPALLET">Draft Mutasi Pallet</h3>
            </div>
        </div>
        <button class="btn btn-primary pull-right" name="btn_history_approval" id="btn_history_approval" <?php if ($MutasiPallet->distribusi_penerimaan_id == "null") {
                                                                                                                echo 'style="display:none;"';
                                                                                                            } else if ($MutasiPallet->tr_mutasi_pallet_draft_status == "Draft") {
                                                                                                                echo 'style="display:none;"';
                                                                                                            } else {
                                                                                                                echo "";
                                                                                                            } ?>><i class="fa fa-clock"></i> <label name="CAPTION-HISTORYAPPROVAL">History
                Approval</label></button>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-FORM">Form</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NODRAFTMUTASI">No
                                Draft Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="mutasi_draft_no_draft" class="form-control" name="mutasi_draft_no_draft" value="<?= $MutasiPallet->tr_mutasi_pallet_draft_kode ?>" readonly />
                                <input type="hidden" id="mutasi_pallet_draft_id" name="mutasi_pallet_draft_id" value="<?= $MutasiPallet->tr_mutasi_pallet_draft_id ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TGLDRAFTMUTASI">Tgl Draft Mutasi</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" class="form-control datepicker" name="mutasi_draft_tanggal" id="mutasi_draft_tanggal" value="<?= date('d/m/Y', strtotime($MutasiPallet->tr_mutasi_pallet_draft_tanggal)) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TIPETRANSAKSI">Tipe Transaksi</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_tipe_transaksi" name="mutasi_draft_tipe_transaksi" class="form-control select2" style="width:100%" disabled required>
                                    <!-- <option value="Mutasi Pallet Antar Gudang">Mutasi Pallet Antar Gudang</option> -->
                                    <?php foreach ($TipeTransaksi as $row) { ?>
                                        <option value="<?= $row['tipe_mutasi_id']; ?>" <?= $MutasiPallet->tipe_mutasi_id == $row['tipe_mutasi_id'] ? 'selected' : ''; ?>>
                                            <?= $row['tipe_transaksi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGASAL">Gudang
                                Asal</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="gudang_asal_mutasi_draft" name="gudang_asal_mutasi_draft" class="form-control select2" style="width:100%" disabled>
                                    <?php foreach ($Gudang as $row) { ?>
                                        <option value="<?= $row['depo_detail_id']; ?>" <?= $MutasiPallet->depo_detail_id_asal == $row['depo_detail_id'] ? 'selected' : ''; ?>>
                                            <?= $row['depo_detail_nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="gudang_tujuan_mutasi_draft" name="gudang_tujuan_mutasi_draft" class="form-control select2" style="width:100%" disabled required>
                                    <?php foreach ($Gudang as $row) { ?>
                                        <option value="<?= $row['depo_detail_id']; ?>" <?= $MutasiPallet->depo_detail_id_tujuan == $row['depo_detail_id'] ? 'selected' : ''; ?>>
                                            <?= $row['depo_detail_nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_principle" name="mutasi_draft_principle" class="form-control select2" style="width:100%" disabled>
                                    <?php foreach ($Principle as $row) { ?>
                                        <option value="<?= $row['principle_id']; ?>" <?= $MutasiPallet->principle_id == $row['principle_id'] ? 'selected' : ''; ?>>
                                            <?= $row['principle_kode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_principle" name="mutasi_draft_principle" class="form-control select2" style="width:100%" disabled>
                                    <?php foreach ($Principle as $row) { ?>
                                        <option value="<?= $row['principle_id']; ?>" <?= $MutasiPallet->principle_id == $row['principle_id'] ? 'selected' : ''; ?>>
                                            <?= $row['principle_kode']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-CHECKER">Checker</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="mutasi_draft_checker" class="form-control select2" style="width:100%" disabled required>
                                    <?php foreach ($Checker as $row) { ?>
                                        <option value="<?= $row['karyawan_id'] . " || " . $row['karyawan_nama']; ?>" <?= $MutasiPallet->tr_mutasi_pallet_draft_nama_checker == $row['karyawan_nama'] ? 'selected' : ''; ?>>
                                            <?= $row['karyawan_nama']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-KETERANGAN">Keterangan</label>
                            <div class="col-md-6 col-sm-6">
                                <textarea class="form-control" id="mutasi_draft_keterangan" name="mutasi_draft_keterangan" disabled required><?= $MutasiPallet->tr_mutasi_pallet_draft_keterangan ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUS">Status</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" name="mutasi_draft_status" id="mutasi_draft_status" class="form-control" value="<?= $MutasiPallet->tr_mutasi_pallet_draft_status ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"></label>
                            <div class="col-md-6 col-sm-6">
                                <input type="checkbox" name="mutasi_draft_approval" id="mutasi_draft_approval" value="In Progress Approval" <?= $MutasiPallet->tr_mutasi_pallet_draft_status != "Draft" ? 'checked' : ''; ?> disabled> <label name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <table id="table_mutasi_draft" width="100%" class="table table-striped">
                        <thead>
                            <tr style="background:#F0EBE3;">
                                <th class="text-center" name="CAPTION-NOPALLET">No Pallet</th>
                                <th class="text-center" name="CAPTION-JENISPALLET">Jenis Pallet</th>
                                <th class="text-center" name="CAPTION-LOKASIRAKASAL">Lokasi Rak Asal</th>
                                <th class="text-center" name="CAPTION-LOKASIBINASAL">Lokasi Bin Asal</th>
                                <th class="text-center" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $i = 0;

                            if ($MutasiPalletDetail != null) {
                                foreach ($MutasiPalletDetail as $detail) {
                            ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="hidden" id="pallet_id<?= $i; ?>" name="pallet_id<?= $i; ?>" value="<?= $detail['pallet_id'] ?>">
                                            <input type="hidden" id="rak_lajur_detail_id<?= $i; ?>" name="rak_lajur_detail_id<?= $i; ?>" value="<?= $detail['rak_lajur_detail_id'] ?>">
                                            <?= $detail['pallet_kode'] ?>
                                        </td>
                                        <td class="text-center"><?= $detail['pallet_jenis_nama'] ?></td>
                                        <td class="text-center"><?= $detail['rak_nama'] ?></td>
                                        <td class="text-center"><?= $detail['rak_lajur_detail_nama'] ?></td>
                                        <td class="text-center"><button class="btn btn-success" onclick="ViewDetailPallet2('<?= $detail['pallet_id'] ?>')"><i class="fa fa-search"></i></button><button class="btn btn-danger" <?= $MutasiPallet->tr_mutasi_pallet_draft_status != "Draft" ? 'style="display: none;"' : ''; ?> onclick="DeletePallet(this)"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                            <?php $no++;
                                    $i++;
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                    <a href="<?= base_url(); ?>WMS/MutasiPalletDraft/MutasiPalletDraftMenu" class="btn btn-danger" id="btn_kembali"><i class="fa fa-reply"></i> <label name="CAPTION-MENUUTAMA">Menu
                            Utama</label></a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-pallet-detail-2" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-DETAILPALLET">Detail Pallet</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="container mt-2">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                                <label name="CAPTION-PALLETKODE">Pallet Kode</label>
                                            </div>
                                            <div class="col-lg-4 col-sm-4 col-xs-8">
                                                <input type="text" class="form-control" id="pallet_detail_pallet_kode" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                                                <label name="CAPTION-JENISPALLET">Jenis Pallet</label>
                                            </div>
                                            <div class="col-lg-4 col-sm-4 col-xs-8">
                                                <input type="text" class="form-control" id="pallet_detail_jenis_pallet" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="x_content table-responsive">
                                        <table id="data-table-pallet-detail" width="100%" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td class="text-center" width="5%">#</td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
                                                    <td width="30%"><strong><label name="CAPTION-SKU">SKU</label></strong></td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-EXPDATE">Exp Date</label></strong></td>
                                                    <td width="10%" class="text-center"><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
                                                    <td width="5%" class="text-center"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah
                                                                Barang</label></strong></td>
                                                </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="btn-back-detail-pallet-2"><label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>
<!-- modal history approval-->
<div class="modal fade" id="modalHistoryApproval" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-HISTORYAPPROVAL">History Approval</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2"><label name="CAPTION-JENISPENGAJUAN">Jenis Pengajuan</label></div>
                        <div class="col-lg-4"><input class="form-control" id="txt_jenis_pengajuan" value="" readonly>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tableHistoryApproval" style="width:100%" class="table table-hover  table-primary table-bordered ">
                            <thead>
                                <tr style="background:#F0EBE3;">
                                    <th style="text-align: center;" name="CAPTION-NO">No</th>
                                    <th style="text-align: center;" name="CAPTION-TGLAPPROVAL">Tgl Approval</th>
                                    <th style="text-align: center;" name="CAPTION-NODOKUMEN">No Dokumen</th>
                                    <th style="text-align: center;" name="CAPTION-STATUS">Status</th>
                                    <th style="text-align: center;" name="CAPTION-OLEH">Oleh</th>
                                    <th style="text-align: center;" name="CAPTION-NOTE">Note</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger btnclosemodalbuatpackingdo" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>

            </div>
        </div>
    </div>
</div>