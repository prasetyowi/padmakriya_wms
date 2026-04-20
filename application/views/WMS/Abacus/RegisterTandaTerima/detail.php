<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-136000100">Register Tanda Terima</h3>
            </div>
            <div style="float: right">
            </div>
        </div>
        <?php foreach ($Header as $header) : ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_kode">
                                    <label class="control-label" for="TrAbacus-tr_abacus_kode" name="CAPTION-KODEABACUS">Kode Abacus</label>
                                    <input readonly="readonly" type="text" id="TrAbacus-tr_abacus_kode" class="form-control" name="TrAbacus[tr_abacus_kode]" autocomplete="off" value="<?= $header['tr_abacus_kode'] ?>">
                                    <input readonly="readonly" type="hidden" id="TrAbacus-tr_abacus_id" class="form-control" name="TrAbacus[tr_abacus_id]" autocomplete="off" value="<?= $header['tr_abacus_id'] ?>">
                                    <input readonly="readonly" type="hidden" id="TrAbacus-tr_abacus_tgl_update" class="form-control" name="TrAbacus[tr_abacus_tgl_update]" autocomplete="off" value="<?= $header['tr_abacus_tgl_update'] ?>">
                                    <input readonly="readonly" type="hidden" id="TrAbacus-tr_abacus_who_update" class="form-control" name="TrAbacus[tr_abacus_who_update]" autocomplete="off" value="<?= $header['tr_abacus_who_update'] ?>">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-client_wms_id">
                                    <label for="TrAbacus-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                    <select id="TrAbacus-client_wms_id" class="form-control select2" name="TrAbacus[client_wms_id]" disabled>
                                        <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                                        <?php foreach ($Perusahaan as $row) : ?>
                                            <option value="<?= $row['client_wms_id'] ?>" <?= $row['client_wms_id'] == $header['client_wms_id'] ? 'selected' : '' ?>><?= $row['client_wms_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_kode">
                                    <label class="control-label" for="TrAbacus-tr_abacus_kode" name="CAPTION-TANGGAL">Tanggal</label>
                                    <input type="date" id="TrAbacus-tr_abacus_tanggal" class="form-control" name="TrAbacus[tr_abacus_tanggal]" autocomplete="off" value="<?= $header['tr_abacus_tanggal'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_reff_kode">
                                    <label class="control-label" for="TrAbacus-tr_abacus_reff_kode" name="CAPTION-REFFKODE">Reff Kode</label>
                                    <input type="text" id="TrAbacus-tr_abacus_reff_kode" class="form-control" name="TrAbacus[tr_abacus_reff_kode]" autocomplete="off" value="<?= $header['tr_abacus_reff_kode'] ?>" disabled>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_keterangan">
                                    <label class="control-label" for="TrAbacus-tr_abacus_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                    <input type="text" id="TrAbacus-tr_abacus_keterangan" class="form-control" name="TrAbacus[tr_abacus_keterangan]" autocomplete="off" value="<?= $header['tr_abacus_keterangan'] ?>" disabled>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_status">
                                    <label class="control-label" for="TrAbacus-tr_abacus_status" name="CAPTION-STATUS">Status</label>
                                    <input readonly="readonly" type="text" id="TrAbacus-tr_abacus_status" class="form-control" name="TrAbacus[tr_abacus_status]" autocomplete="off" value="<?= $header['tr_abacus_status'] ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group field-TrAbacus-tr_abacus_grand_total">
                                    <label class="control-label" for="TrAbacus-tr_abacus_grand_total" name="CAPTION-GRANDTOTAL">Grand Total</label>
                                    <input readonly="readonly" type="text" id="TrAbacus-tr_abacus_grand_total" class="form-control text-right mask-money" name="TrAbacus[tr_abacus_grand_total]" autocomplete="off" value="<?= round($header['tr_abacus_grand_total']) ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row" id="panel-sku">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-ABACUSDETAIL">Abacus Detail</h4>
                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-bordered" id="table_abacus_detail" style="width: 100%;">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center" style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BANK">Bank</th>
                                <th class="text-center" style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NOREKENING">Rekening</th>
                                <th class="text-center" style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-TOTAL">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div style="float: right">
                    <a href="<?= base_url('WMS/Abacus/RegisterTandaTerima/RegisterTandaTerimaMenu') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <span name="CAPTION-KEMBALI">Kembali</span></a>
                    <button class="btn-submit btn btn-primary" id="btn_buka_abacus" onclick="bukaData()"><i class="fa-solid fa-box-open"></i> <span name="CAPTION-BUKADATA">Buka Data</span></button>
                </div>
            </div>
        </div>
    </div>
</div>