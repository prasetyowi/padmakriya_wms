<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><span name="CAPTION-BTB">Bukti Terima Barang</span></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>
                    <div class="row" style="margin-bottom:15px;">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-NOFDJR">No FDJR </label>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                <input type="hidden" id="delivery_order_batch_id" class="form-control" value="<?php echo $delivery_order_batch_id; ?>" readonly />
                                <input type="text" id="filter_fdjr_no" class="form-control" value="" readonly />
                                <input type="hidden" id="fdjr_type" value="retur">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:25px;">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-DRIVER">Driver</label>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                <select id="filter_fdjr_driver" class="form-control" readonly></select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:25px;">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                <select class="form-control select2" id="filter_perusahaan" name="filter_perusahaan" onchange="GetPrincipleByPerusahaan(this.value)">
                                    <option value="">** <label name="CAPTION-PERUSAHAAN">Perusahaan</label> **</option>
                                    <?php foreach ($Perusahaan as $row) : ?>
                                        <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:25px;">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-PRINCIPLE">Principle</label>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                <select class="form-control select2" id="filter_principle" name="filter_principle" onchange="GetCheckerByPrinciple(this.value)"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:25px;">
                        <div class="form-group">
                            <label class="control-label col-lg-2 col-md-4 col-sm-4 col-xs-12" name="CAPTION-CHECKER">Checker</label>
                            <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                <select class="form-control select2" id="filter_checker" name="filter_checker"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab" href="#do-retur" onclick="ChangeTypeFDJR('retur')"><span name="CAPTION-TERIMABARANGDORETUR">Terima Barang DO Retur</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#terkirim-sebagian" onclick="ChangeTypeFDJR('terkirim sebagian')"><span name="CAPTION-TERIMABARANGTERKIRIMSEBAGIAN">Terima Barang Terkirim Sebagian</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#gagal-kirim" onclick="ChangeTypeFDJR('tidak terkirim')"><span name="CAPTION-TERIMABARANGAGALKIRIM">Terima Barang Gagal Kirim</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#barang-titipan" onclick="ChangeTypeFDJR('titipan')"><span name="CAPTION-TERIMABARANGTITIPAN">Terima Barang Titipan</span></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="do-retur" class="tab-pane fade in active">
                                <div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="DO Retur" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <select id="filter_gudang_penerima_do_retur" class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <table id="tabledoretur" width="100%" class="table table-bordered">
                                        <thead>
                                            <tr class="bg-info">
                                                <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                                <th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
                                                <th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
                                                <th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                                <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                                <th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="terkirim-sebagian" class="tab-pane fade">
                                <div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_terkirim_sebagian" class="form-control" value="autogenerate" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="Terkirim Sebagian" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <select id="filter_gudang_penerima_terkirim_sebagian" class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <table id="tableterkirimsebagian" width="100%" class="table table-bordered">
                                        <thead>
                                            <tr class="bg-info">
                                                <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                                <th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
                                                <th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
                                                <th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                                <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                                <th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="gagal-kirim" class="tab-pane fade">
                                <div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="Gagal Kirim" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <select id="filter_gudang_penerima_gagal_kirim" class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <table id="tablegagalkirim" width="100%" class="table table-bordered">
                                        <thead>
                                            <tr class="bg-info">
                                                <th class="text-center"><span name="CAPTION-NO">No</span></th>
                                                <th class="text-center"><span name="CAPTION-NODO">No DO</span></th>
                                                <th class="text-center"><span name="CAPTION-CUSTOMER">Pelanggan</span></th>
                                                <th class="text-center"><span name="CAPTION-PRINCIPLE">Principle</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                                <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                                <th class="text-center"><span name="CAPTION-QTY">Jumlah Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-QTYTERIMA">Jumlah Terima</span></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="barang-titipan" class="tab-pane fade">
                                <div class="row" style="margin-top:25px;margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-NOBTB">No BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="autogenerate" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TIPE">Tipe BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="Barang Titipan" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-TGLBTB">Tanggal BTB</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <input type="text" id="filter_fdjr_no" class="form-control" value="<?php echo date('d-m-Y') ?>" readonly />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" name="CAPTION-GUDANGPENERIMA">Gudang Penerima</label>
                                        <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4 col-xl-4">
                                            <select id="filter_gudang_penerima_barang_titipan" class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                        <div class="panel panel-default col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="viewpalletdoretur">
                            <div class="panel-heading" style="margin-left:-10px;margin-right:-10px;"><span name="CAPTION-PALLET">Pallet</span></div>
                            <div class="panel-body form-horizontal form-label-left" style="margin-left:-20px;margin-right:-20px;">
                                <table id="tablepalletdoretur" width="100%" class="table table-bordered">
                                    <thead>
                                        <tr class="bg-info">
                                            <th class="text-center"><span name="CAPTION-KODE">Kode</span></th>
                                            <th class="text-center" style="width: 50%;"><span name="CAPTION-TIPE">Jenis</span></th>
                                            <th class="text-center" style="width: 20%;"><span name="CAPTION-ACTION">Action</span></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:10px;margin-left:5px;margin-right:5px;">
                        <div class="panel panel-default col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="viewdetailpalletdoretur" style="display: none;">
                            <div class="panel-heading" style="margin-left:-10px;margin-right:-10px;">Detail Pallet</div>
                            <div class="panel-body form-horizontal form-label-left" style="margin-left:-15px;margin-right:-20px;">
                                <input type="hidden" id="pallet_id" value="">
                                <button type="button" class="btn btn-success" id="btn-tambah-sku-fdjr"><span name="CAPTION-TAMBAHSKUBYFDJR">Tambah SKU By FDJR</span></button>
                                <button type="button" class="btn btn-success" id="btn-view-sku-fdjr" data-toggle="modal" data-target="#modal-sku"><span name="CAPTION-TAMBAHSKUTITIPAN">Tambah SKU Titipan</span></button>
                                <div class="table-responsive">
                                    <table id="tabledetailpalletdoretur" width="100%" class="table table-bordered">
                                        <thead>
                                            <tr class="bg-info">
                                                <th class="text-center"><span name="CAPTION-PRINCIPLE">Principal</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKODE">Kode SKU</span></th>
                                                <th class="text-center"><span name="CAPTION-SKU">Nama Barang</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUKEMASAN">Kemasan</span></th>
                                                <th class="text-center"><span name="CAPTION-SKUSATUAN">Satuan</span></th>
                                                <th class="text-center"><span name="CAPTION-CAPTION-SKUREQEXPDATE">Exp Date</span></th>
                                                <th class="text-center"><span name="CAPTION-QTY">Jumlah</span></th>
                                                <th class="text-center"><span name="CAPTION-TIPE">Tipe</span></th>
                                                <th class="text-center"><span name="CAPTION-ACTION">Action</span></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pull-right" style="margin-top:20px;">
                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading... </span>
                        <button type="button" id="btnconfirmbtb" class="btn btn-primary"><i class="fa fa-check"></i> <span name="CAPTION-KONFIRMASI">Konfirmasi</span> </button>
                        <button type="button" id="btnsavebtb" class="btn btn-success"><i class="fa fa-save"></i> <span name="CAPTION-SAVE">Simpan</span> </button>
                        <a href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" type="button" id="btn_back" class="btn btn-danger"><i class="fa fa-undo"></i> <span name="CAPTION-BACK">Kembali</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>