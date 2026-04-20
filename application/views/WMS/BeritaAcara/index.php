<div class="right_col" role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-BERITAACARA">Berita Acara</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">

                        <!-- <h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label></strong></h3> -->
                        <!-- <div class="clearfix"></div> -->
                    </div>
                    <div class="x_content">
                        <form id="form-filter-do" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6" style="margin-bottom: 10px;">
                                    <div class="item form-group">
                                        <label class="col-form-label col-lg-6 col-md-6 col-sm-6 label-align" name="CAPTION-TANGGAL">
                                            Tanggal
                                        </label>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <input type="text" id="filter-sj-date" class="form-control" name="filter-sj-date" value="" />
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-lg-6 col-md-6 col-sm-6 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <select class="form-control select2" name="principle" id="principle" required>
                                                <option value="">-- Pilih Principle --</option>
                                                <?php foreach ($principle as $row) { ?>
                                                    <option value="<?= $row->principle_id ?>">
                                                        <?= $row->principle_kode ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 text-left">
                                        <div style="float: right;">
                                            <button type="button" id="btn-submit-filter" onclick="GetDataFilter()" class="btn btn-md btn-success btn-submit-filter"><i class="fa fa-search"></i>
                                                <label name="CAPTION-CARI"> Cari</label></button>
                                            <span id="loadingaction" style="display: none;"><i class="fa fa-spinner fa-spin"></i>
                                                <label name="CAPTION-LOADING">Loading</label>...</span>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row" id="list-data-asn">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="container mt-2">
                            <div class="row">
                                <div class="x_content table-responsive">
                                    <table id="table_data_asn" width="100%" class="table table-striped table-bordered">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th width="5%" class="text-center" style="color: white;">#</th>
                                                <th width="20%" class="text-center" style="color: white;" name="CAPTION-TANGGAL">Tanggal</th>
                                                <th width="20%" class="text-center" style="color: white;" name="CAPTION-PENERIMAANSURATJALANKODE">Penerimaan Surat Jalan Kode</th>
                                                <th width="20%" class="text-center" style="color: white;" name="CAPTION-PENERIMAANSURATJALAN">Penerimaan Surat Jalan</th>
                                                <th width="20%" class="text-center" style="color: white;" name="CAPTION-STATUS">Status</th>
                                                <th width="15%" class="text-center" style="color: white;" name="CAPTION-ACTION">Action</th>
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

            <div class="modal fade" id="modal-listdoasn" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-lg" style="width:80%;">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title"><label name="CAPTION-LISTDELIVERYORDER">List Delivery
                                    Order</label>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="table-listdoasn" width="100%" class="table table-striped">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th class="text-center" style="color:white;" name="CAPTION-SHIPMENT">
                                                    Shipment
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-DELIVERYORDER">
                                                    Delivery Order
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-ACTION">
                                                    Action
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

            <div class="modal fade" id="modal-listskudetailori" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-lg" style="width:90%;">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title"><label name="CAPTION-LISTSELISIHSKU">List Selisih SKU </label>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table id="table-listsku" width="100%" class="table table-striped">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th class="text-center" style="color:white;" name="CAPTION-SHIPMENT">
                                                    Shipment
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-SKUKODE">
                                                    SKU Kode
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-SKUNAMAKODE">
                                                    SKU Nama Produk
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-JUMLAHBARANG">
                                                    Jumlah Barang
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-TERIMA">
                                                    Total Terima
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-RUSAK">
                                                    Rusak
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-BARANGTERTINGGAL">
                                                    Barang Tertinggal
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-KARDUSRUSAK">
                                                    Kardus Rusak
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-TOTAL">
                                                    Total
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-SUMZ">
                                                    SUM Z
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="simpansku()" id="btn_simpan_list_sku" class="btn btn-primary"><label name="CAPTION-SIMPAN">Simpan</label></button>
                            <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>