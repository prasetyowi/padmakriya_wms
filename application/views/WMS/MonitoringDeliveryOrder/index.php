<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-MONITORINGDELIVERYORDER">Monitoring Delivery Order</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <label name="CAPTION-PENCARIANMONITORINGDELIVERYORDER" style="padding-top: 10px;">Pencarian Monitoring Delivery Order</label>
                    </div>
                    <!-- <div class="col-md-6">
                        <button class="btn btn-danger" style="float: right;" onclick="cetakStatusDO()"><i class="fas fa-print"></i><label name="CAPTION-CETAK" style="margin-left: 0.5rem;">Cetak</label></button>
                    </div> -->
                </div>
            </div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-2">
                        <label class="col-form-label label-align" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="filter_perusahaan" id="filter_perusahaan">
                            <option value="">All</option>
                            <?php foreach ($perusahaan as $value) : ?>
                                <option value="<?= $value['client_wms_id'] ?>"><?= $value['client_wms_nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="col-form-label label-align" name="CAPTION-PRINCIPLE">Principle</label>

                    </div>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="filter_principle" id="filter_principle">
                            <option value="">All</option>
                            <?php foreach ($principle as $value) : ?>
                                <option value="<?= $value['principle_id'] ?>"><?= $value['principle_kode'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-2">
                        <label class="col-form-label label-align" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                    </div>
                    <div class="col-lg-4">
                        <input type="text" id="filter_tgl_kirim" class="form-control filter_tgl_kirim" name="filter_tgl_kirim" value="" />
                    </div>
                    <div class="col-lg-2">
                        <label class="col-form-label label-align" name="CAPTION-STATUS">Status</label>
                    </div>
                    <div class="col-lg-4">
                        <select class="form-control select2" name="filter_status" id="filter_status">
                            <option value="">All</option>
                            <option value="in progress">in progress</option>
                            <option value="canceled">canceled</option>
                            <option value="delivered">delivered</option>
                            <option value="not delivered">not delivered</option>
                            <option value="partially delivered">partially delivered</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="form-group" style="float: right;">
                        <button type="button" id="btncari" class="btn btn-primary"><i class="fa fa-search"></i>
                            <span name="CAPTION-CARI">Cari</span></button>
                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-HASILMONITORINGDELIVERYORDER">Hasil Monitoring Delivery Order</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row table-responsive">
                    <table id="tableviewdo" width="100%" class="table table-striped">
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-center" style="color: white;" name="CAPTION-PERUSAHAAN">Perusahaan</th>
                                <th class="text-center" style="color: white;" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" style="color: white;" name="CAPTION-DODRAFT">DO Draft</th>
                                <th class="text-center" style="color: white;" name="CAPTION-DODIBATALKAN">DO Dibatalkan</th>
                                <th class="text-center" style="color: white;" name="CAPTION-INPROGRESS">In Progress</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TERKIRIM">Terkirim</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TIDAKTERKIRIM">Tidak Terkirim</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TERKIRIMSEBAGIAN">Terkirim Sebagian</th>
                                <th class="text-center" style="color: white;" name="CAPTION-TOTALDO">Total DO</th>
                                <th class="text-center" style="color: white;" name="CAPTION-SERVICELEVEL">Service Level (%)</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot style="background-color: #FCDC2A">

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-dodetail" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog" style="width: 80%;">
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
                                            <th class="text-center" style="color: white;" name="CAPTION-TANGGALKIRIM">Tgl Kirim</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-SOEKSTERNAL">SO Eksternal</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-DO">DO</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-NOFDJR">No FDJR</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-DRIVER">Driver</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>
                                            <th class="text-center" style="color:white;" name="CAPTION-ALAMAT">Alamat</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody></tbody> -->
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