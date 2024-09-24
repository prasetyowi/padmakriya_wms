<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-STATUSDELIVERYORDER">Status Delivery Order</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <label name="CAPTION-PENCARIANDELIVERYORDER" style="padding-top: 10px;">Pencarian Status
                            Delivery
                            Order</label>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-danger" style="float: right;" onclick="cetakStatusDO()"><i class="fas fa-print"></i><label name="CAPTION-CETAK" style="margin-left: 0.5rem;">Cetak</label></button>
                    </div>
                </div>
            </div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" name="CAPTION-MODE">Mode</label>
                            <select id="mode_filter" class="form-control select2" style="width:100%">
                                <option value="0">Sales</option>
                                <option value="1">Driver</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="mode_sales">
                    <div class="col-lg-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label label-align" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                            <input type="text" id="filter_date_so" class="form-control filter_date" name="filter_date_so" value="" />
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" name="CAPTION-SALES">Sales</label>
                            <select id="sales" class="form-control select2" style="width:100%">
                                <option value="">-- Pilih semua sales --</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" name="CAPTION-NOSO">No SO</label>
                            <select id="noso" class="form-control select2" style="width:100%">
                                <option value="">-- Pilih semua no SO --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" id="mode_driver" style="display: none;">
                    <div class="col-lg-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label label-align" name="CAPTION-TANGGALKIRIM">Tanggal
                                Kirim</label>
                            <input type="text" id="filter_date_do" class="form-control filter_date" name="filter_date_do" value="" />
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" name="CAPTION-DRIVER">Driver</label>
                            <select id="driver" class="form-control select2" style="width:100%">
                                <option value="">-- Pilih semua driver --</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="col-form-label" name="CAPTION-NOFDJR">No FDJR</label>
                            <select id="nofdjr" class="form-control select2" style="width:100%">
                                <option value="">-- Pilih semua no FDJR --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <button type="button" id="btncari" class="btn btn-primary"><i class="fa fa-search"></i>
                                <span name="CAPTION-CARI">Cari</span></button>
                            <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-DAFTARDELIVERYORDER">Daftar Delivery Order</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row table-responsive">
                    <table id="tableviewdo" width="100%" class="table table-striped">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center" style="color: white;" name="CAPTION-NO">No</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TGLBUAT">Tgl Buat</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TGLRENCANAKIRIM">Tgl Rencana Kirim</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TGLAKTUALKIRIM">Tgl Aktual Kirim</th>
                                <th class="text-center" style="color: white;" name="CAPTION-DO">DO</th>
                                <th class="text-center" style="color: white;" name="CAPTION-NOSO">NO SO</th>
                                <th class="text-center" style="color: white;" name="CAPTION-NOSOEKSTERNAL">NO SO
                                    Eksternal</th>
                                <th class="text-center" style="color: white;" name="CAPTION-NAMA">Nama</th>
                                <th class="text-center" style="color: white;" name="CAPTION-ALAMAT">Alamat</th>
                                <th class="text-center" style="color: white;" name="CAPTION-STATUSTERAKHIR">Status
                                    Terakhir</th>
                                <th class="text-center" style="color: white;" name="CAPTION-FLAG">Flag</th>
                                <th class="text-center" style="color: white;" name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-dodetail" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" style="width:80%;">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"><label name="CAPTION-DELIVERYORDERDETAIL">Delivery Order Detail</label>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <table id="table-dodetail" width="100%" class="table table-striped">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center" style="color:white;" name="CAPTION-TANGGAL">Tanggal
                                            </th>
                                            <th class="text-center" style="color:white;" name="CAPTION-STATUS">Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>