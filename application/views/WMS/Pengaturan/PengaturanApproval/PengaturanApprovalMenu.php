<style>
#overlay {
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    height: 100%;
    display: none;
    background: rgba(0, 0, 0, 0.6);
}

.cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
}

@keyframes sp-anime {
    100% {
        transform: rotate(360deg);
    }
}

.is-hide {
    display: none;
}

#tbody-listSKU tr,
#tbody-listLokasiSKU tr {
    cursor: pointer;
}
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PENGATURANAPPROVAL">Pengaturan Approval</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5 name="CAPTION-PENCARIANPENGATURANAPPROVAL">Pencarian Pengaturan Approval</h5>
                    </div>
                    <div class="x_content">
                        <form id="form-filter-do" class="form-horizontal form-label-left">
                            <div class="row">


                                <div class="item form-group">
                                    <div class="col-md-12 col-sm-12 text-left">
                                        <a class="btn btn-md btn-primary btn-submit-filter"
                                            onclick="getDataSearch()"><label name="CAPTION-CARI">Cari</label></a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ro-batch" id="do-table">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5 name="CAPTION-DAFTARPENGATURANAPPROVAL">Daftar Pengaturan Approval</h5>
                        <a class="btn btn-md btn-primary"
                            href="<?php echo site_url('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalForm'); ?>"><label
                                name="CAPTION-BUATPENGATURANAPPROVAL">Buat
                                Pengaturan Approval</label></a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <table id="tablePengaturanApproval" width="100%" class="table table-responsive">
                            <thead>
                                <tr>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-NO">No</th>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-NAMAPARAMETER">
                                        Nama Parameter</th>
                                    <th style='vertical-align:middle; text-align: center;'
                                        name="CAPTION-NAMAPENGATURANAPPROVAL">Nama Pengaturan Approval</th>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-STATUS">Status
                                    </th>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-LASTUPDATE">
                                        Last Update</th>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-WHOUPDATE">Who
                                        Update</th>
                                    <th style='vertical-align:middle; text-align: center;' name="CAPTION-ACTION">Action
                                    </th>
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

<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<!-- modal  -->
<div class="modal fade" id="modalDetail" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xlg">
        <!-- Modal content-->
        <form class="form-horizontal" enctype="multipart/form-data" id="addDetailKredit">
            <div class="modal-content">
                <div class="modal-header bg-primary form-horizontal">
                    <h4 class="modal-title"><label name="CAPTION-PENGELUARANDANA">Pengeluaran Dana</label></h4>
                </div>
                <div class="modal-body form-label-left form-horizontal">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding tab-content" id="anylist">
                            <div class="tab-pane active" id="event">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <input type="hidden" id="add_id" name="add_id" class="txtkode form-control" />
                                    <input type="hidden" id="add_detail_id" name="add_detail_id"
                                        class="txtkode form-control" />
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-NOTRANSAKSI">No
                                            Transaksi
                                        </label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <input type="text" id="kode" name="kode"
                                                class="txtselected_date form-control" value="(Auto)" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-TANGGALTRANSAKSI">Tanggal
                                            Transaksi</label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <input type="date" id="add_tanggal_Pengeluaran"
                                                name="add_tanggal_Pengeluaran" class="txtselected_date form-control"
                                                value="<?= date("Y-m-d") ?>" readonly />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-NOPERKIRAAN">No
                                            Perkiraan
                                        </label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <select id="add_no_perkiraan" name="add_no_perkiraan"
                                                class="form-control custom-select">
                                                <!-- <option value="">-- Pilih Tipe --</option> -->

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-KETERANGAN">Keterangan</label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <textarea id="add_keterangan" name="add_keterangan"
                                                class="txtjudul form-control"
                                                style="height: 100px; width: 100%; resize: none;"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-STATUSPOSTING">Status
                                            Posting
                                        </label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <input type="text" id="add_status" name="add_status"
                                                class="txtselected_date form-control" value="Belum" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-KATEGORIBIAYA">Kategori
                                            Biaya</label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <select id="add_kategori_biaya" name="add_kategori_biaya"
                                                class="form-control custom-select">
                                                <option value="">-- <label name="CAPTION-PILIHTIPE">Pilih Tipe</label>
                                                    --</option>
                                                <?php foreach ($kategori_biaya as $key => $value) { ?>
                                                <option value="<?= $value['kategori_biaya_id'] ?>">
                                                    <?= $value['kategori_biaya_nama'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-NAMAPEMOHON">Nama
                                            Pemohon</label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <input type="text" id="add_nama_penerima" name="add_nama_penerima"
                                                class="txtno_rekening form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-JENISPENGELUARAN">Jenis
                                            Pengeluaran</label>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <select id="add_default_pembayaran" name="add_default_pembayaran"
                                                class="cbdefaultpembayaran form-control">
                                                <option value="Tunai"><label name="CAPTION-TUNAI">Tunai</label></option>
                                                <option value="Non Tunai"><label name="CAPTION-NONTUNAI">Non
                                                        Tunai</label></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-BANKPENERIMA">Bank
                                            Penerima</label>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <select id="add_bank" class="form-control custom-select" disabled>
                                                <option value="">-- <label name="CAPTION-PILIHBANK">Pilih Bank</label>
                                                    --</option>
                                                <?php foreach ($bank as $key => $value) { ?>
                                                <option value="<?= $value['bank_account_id'] ?>">
                                                    <?= $value['bank_account_nama'] ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"
                                            name="CAPTION-NOREKENING">No.
                                            Rekening</label>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                            <input type="text" id="add_no_rekening" name="add_no_rekening"
                                                class="txtno_rekening form-control"
                                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
                <div class="modal-body form-label-left">
                    <a class="btn btn-sm btn-primary " onclick="searchPermintaanPengeluaran()"><i class="fa fa-plus"
                            aria-hidden="true"></i> <label name="CAPTION-PERMINTAANPENGELUARANDANA">Permintaan
                            Pengeluaran dana</label></a>
                    <div class="table-responsive">

                        <table id="tableDOD" style="width:100%"
                            class="table table-hover  table-primary table-bordered ">
                            <thead>
                                <tr>
                                    <th style="text-align: center;" name="CAPTION-NODOKUMEN">No Dokumen</th>
                                    <th style="text-align: center;" name="CAPTION-TGLPERMINTAAN">Tgl Permintaan</th>
                                    <th style="text-align: center;" name="CAPTION-TGLDIBUTUHKAN">Tgl Dibutuhkan</th>
                                    <th style="text-align: center;" name="CAPTION-JUDULPERMINTAAN">Judul Permintaan</th>
                                    <th style="text-align: center;" name="CAPTION-ANGGARAN">Anggaran</th>
                                   
 <th style="text-align: center;" name="CAPTION-KETERANGAN">Keterangan</th>
                                    <th style="text-align: center;" name="CAPTION-ESTIMASIPENGELUARAN">Estimasi
                                        Pengeluaran</th>
                                    <th style="text-align: center;" name="CAPTION-AKTUALPENGELUARAN">Aktual Pengeluaran
                                    </th>
                                    <th style="text-align: center;" name="CAPTION-ACTION">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-body form-label-left">
                    <div class="x_p
anel">
                        <div class="x_title">
                            <h5 name="CAPTION-UPLOADATTACHMENT">Upload Attachment</h5>
                            <div cl ass="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2"
                                    name="CAPTION-FILE">File</label>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <input type="file" id="add_file" name="add_file" class="txtnilai form-control"
                                        accept="application/pdf,application/vnd.ms-excel" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" title="Save" type="submit" id="savePengeluaran">
                        <label name="CAPTION-SIMPAN">Simpan</label></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><label
                            name="CAPTION-BATAL">Batal</label></button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal view pemngajuan dana -->