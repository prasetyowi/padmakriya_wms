<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-GENERATENOMORPALLET">Pallete Number Generator</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <!-- <h3><strong>Filter Data</strong></h3> -->
                        <div class="clearfix"></div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label name="CAPTION-PT">Perusahaan</label>
                                        <input type="hidden" name="palleteGenerateID" id="palleteGenerateID"
                                            value="<?= $byid->id ?>">
                                        <input type="hidden" name="lastUpdated" id="lastUpdated"
                                            value="<?= $byid->pallet_generate_tgl_update ?>">
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <select name="perusahaan_sj" id="perusahaan_sj" class="form-control select2">
                                            <option value="">--<label name="CAPTION-PERUSAHAAN">Pilih
                                                    Perusahaan</label>--</option>
                                            <?php foreach ($perusahaan as $val) : ?>
                                            <option value="<?= $val->client_wms_id ?>"
                                                <?= $byid->perusahaanID != null && $byid->perusahaanID === $val->client_wms_id ? 'selected' : '' ?>>
                                                <?= $val->client_wms_nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label name="CAPTION-PRINCIPLE">Principle</label>
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <select name="principle_sj" id="principle_sj" class="form-control select2">
                                            <option value="">--Pilih Principle--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="x_panel">
                                    <div class="row">
                                        <div class="x_title">
                                            <h5 name="CAPTION-REFERENSISURATJALAN">Referensi Surat Jalan</h5>
                                            <button type="button" name="pilihSuratJalan" id="pilihSuratJalan"
                                                class="btn btn-success"><i class="fa fa-search"></i>
                                                <label name="CAPTION-PILIHSURATJALAN">Pilih Surat Jalan</label></button>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tb_sj_pallete" width="100%" class="table table-striped">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="10%" class="text-center" name="CAPTION-NO">No</th>
                                                        <th width="45%" class="text-center" name="CAPTION-SURATJALAN">
                                                            Surat Jalan</th>
                                                        <th width="45%" class="text-center"
                                                            name="CAPTION-SURATJALANEKTERNAL">Surat Jalan Eksternal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- form ke 2  -->
                            <div class="col-xl-8 col-lg-8 col-md-8 col-xs-8">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label name="">Preffix Unit</label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <input type="text" class="form-control" name="preffix_unit" id="preffix_unit"
                                            placeholder="Auto" value="<?= $depo->depo_kode_preffix ?>" required
                                            readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <label name="CAPTION-TANGGAL">Tanggal :</label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <input type="text" class="form-control" name="tgl_now" id="tgl_now"
                                            placeholder="Auto" value="<?= date("Y-m-d") ?>" required readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label name="">Preffix Pallete :</label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <select name="preffix_pallete" id="preffix_pallete"
                                            class="form-control select2">
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label name="CAPTION-JUMLAHGENERATE">Jumlah Generate :</label>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <input type="text" class="form-control" name="jumlah" id="jumlah"
                                            placeholder="Jumlah" onkeypress="return hanyaAngka(event)" required>

                                    </div>
                                    <div class="col-lg-2">
                                        <button type="button" name="btn_generate" id="btn_generate"
                                            class="btn btn-primary"><i class="fa fa-play"></i>
                                            Generate</button>
                                    </div>
                                </div>
                                <div class="x_panel">
                                    <div class="row">
                                        <div class="x_title">
                                            <h5 name="CAPTION-RESULT">Result</h5>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tb_result_pallete" width="100%" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="5%"> <input type="checkbox" id="check-all-pallete"
                                                                style="transform: scale(0.5)" class="form-control"
                                                                onchange="checkAllPallete(this)" /></th>
                                                        <!-- <th width="5%" class="text-center" name="CAPTION-NO">No</th> -->
                                                        <th width="35%" class="text-center" name="CAPTION-PALLETENO">
                                                            Pallete No.</th>
                                                        <th width="35%" class="text-center"
                                                            name="CAPTION-TGLCETAKTERAKHIR">Tanggal Terkahir Cetak
                                                        </th>
                                                        <th width="15%" class="text-center" name="CAPTION-JUMLAHCETAK">
                                                            Jumlah Cetak</th>
                                                        <th width="10%" class="text-center" name="CAPTION-AKTIF">Aktif
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
                    <div class="" style="float:right">
                        <button type="button" class="btn btn-success btn-save-pallete"><i class="fa fa-save"></i>
                            <label name="CAPTION-SIMPAN">Simpan</label></button>
                        <button type="button" disabled class="btn btn-danger btn-del-pallete"><i
                                class="fa fa-trash"></i>
                            <label name="CAPTION-HAPUS">Hapus</label></button>
                        <button type="button" disabled class="btn btn-primary btn-print_pallete"><i
                                class="fa fa-print"></i>
                            <label name="CAPTION-CETAK">Cetak</label></button>
                        <a href="<?= base_url('WMS/PalleteGenerator/PalleteGeneratorMenu') ?>"
                            class="btn btn-info btn-back-pallete"><i class="fa fa-reply"></i> <label
                                name="CAPTION-KEMBALI">Kembali</label></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPilihSJ" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" name="CAPTION-PILIHSURATJALAN">Pilih Surat Jalan</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="container mt-2">
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label name="CAPTION-PERUSAHAAN">perusahaan</label>
                                            <input type="text" class="form-control" name="perusahaan_disable"
                                                id="perusahaan_disable" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <label name="CAPTION-PRINCIPLE">Principle</label>
                                            <input type="text" class="form-control" name="principle_disable"
                                                id="principle_disable" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="container mt-2">
                            <div class="row">
                                <div class="x_content table-responsive">
                                    <table id="table_list_pilih_sj" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">
                                                    <input type="checkbox" id="check-all-pilih-sj"
                                                        style="transform: scale(1.5)" class="form-control"
                                                        onchange="checkAllSJ(this)" />
                                                </th>
                                                <th width="10%"><strong><label name="CAPTION-NOSURATJALAN">No. Surat
                                                            jalan</label></strong></th>
                                                <th width="10%"><strong><label name="CAPTION-NOSURATJALANEKSTERNAL">No.
                                                            Surat jalan Eksternal</label></strong></th>
                                                <th width="10%" class="text-center"><strong><label
                                                            name="CAPTION-TANGGAL">Tgl</label></strong></th>
                                                <th width="20%" class="text-center"><strong><label
                                                            name="CAPTION-PERUSAHAAN">Perusahaan</label></strong></th>
                                                <th width="15%" class="text-center"><strong><label
                                                            name="CAPTION-PRINCIPLE">Principle</label></strong></th>
                                                <th width="10%" class="text-center"><strong><label
                                                            name="CAPTION-TIPE">Tipe</label></strong></th>
                                                <th width="10%" class="text-center"><strong><label
                                                            name="CAPTION-KETERANGAN">Keterangan</label></strong></th>
                                                <th width="10%" class="text-center"><strong><label
                                                            name="CAPTION-STATUS">Status</label></strong></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn_pilih_sj"><i class="fas fa-plus"></i> <label
                        name="CAPTION-PILIH">Pilih</label></button>
                <button type="button" class="btn btn-dark" id="btnCloseSJ"><i class="fas fa-xmark"></i> <label
                        name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<script>
function hanyaAngka(event) {
    var angka = (event.which) ? event.which : event.keyCode
    if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
        return false;
    return true;
}
</script>