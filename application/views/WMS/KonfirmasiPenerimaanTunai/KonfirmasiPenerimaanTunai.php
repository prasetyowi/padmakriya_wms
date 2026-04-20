<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-KONFIRMASIPENERIMAANTUNAI">Konfirmasi Penerimaan Tunai</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-PENCARIANSURATTUGASPENGIRIMAN">Pencarian Surat Tugas
                    Pengiriman</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALFDJR">Tanggal FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" id="filter_fdjr_date" class="form-control" name="filter_fdjr_date" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <input type="text" class="form-control" name="filter_fdjr_no" id="filter_fdjr_no" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align"></label>
                            <div class="col-md-6 col-sm-6">
                                <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btnviewfdjr" class="btn btn-primary"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_driver" class="form-control" style="width:100%"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-STATUSFDJR">Status
                                FDJR</label>
                            <div class="col-md-6 col-sm-6">
                                <select id="filter_fdjr_status" class="form-control" style="width:100%"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <d iv class="x_panel">
            <div class="form-horizontal form-label-left">
                <div class="row table-responsive">
                    <table id="tablefdjrmenu" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th name="CAPTION-NO">No</th>
                                <th name="CAPTION-TANGGALKIRIM">Tgl Kirim</th>
                                <th name="CAPTION-DRIVER">Driver</th>
                                <th name="CAPTION-STATUSFDJR">Status FDJR</th>
                                <th name="CAPTION-TIPEFDJR">Tipe FDJR</th>
                                <th name="CAPTION-NOFDJR">No FDJR</th>
                                <th name="CAPTION-NOPB">No PB</th>
                                <th name="CAPTION-NOPPB">No PPB</th>
                                <th name="CAPTION-NOSERAHTERIMA">No Serah Terima</th>
                                <th name="CAPTION-STATUS">Status</th>
                                <th name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row">


                    <a href="<?php echo base_url(); ?>" class="btn btn-primary"><i class="fa fa-home"></i> <label name="CAPTION-MENUUTAMA">Menu Utama</label></a>
                </div>
            </div>
    </div>
</div>
</div>