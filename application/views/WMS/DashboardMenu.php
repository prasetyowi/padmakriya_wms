<style>
    .custom-checkbox-input {
        position: relative;
        z-index: 1;
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5rem;
    }

    .custom-checkbox-input .form-check-input {
        width: 20px;
        height: 20px;
    }

    .custom-checkbox-input .form-check-input::after {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        border: solid 1px grey;
        border-radius: 2px;
    }

    .custom-checkbox-input .form-check-input-acc {
        width: 20px;
        height: 20px;
    }

    .custom-checkbox-input .form-check-input-acc::after {
        content: '';
        display: inline-block;
        width: 20px;
        height: 20px;
        border: solid 1px grey;
        border-radius: 2px;
    }

    .custom-checkbox-input .form-check-input:checked::after {
        width: 20px;
        height: 20px;
        border: solid 1px red;
        content: 'X';
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        background-color: red;
        color: white;
        text-align: center;
        font-size: 13px;
        position: relative;
        top: 0px;

    }

    .custom-checkbox-input .form-check-input-acc:checked::after {
        width: 20px;
        height: 20px;
        border: solid 1px lightgreen;
        content: '\f00c';
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        background-color: lightgreen;
        color: white;
        text-align: center;
        font-size: 13px;
        position: relative;
        top: 0px;
    }

    .custom-checkbox-input .form-check-input .custom-color-grey::after {
        background-color: grey;
    }

    .custom-checkbox-input .form-check-input .custom-color-white::after {
        background-color: white;
    }

    .custom-checkbox-input .form-check-input-acc .custom-color-white::after {
        background-color: white;
    }

    .test-parent {
        width: 100%;
        display: flex;
        overflow-x: auto;
    }

    .test-parent .test-child {
        flex: 0 0 auto;
        width: 20%
    }

    @media only screen and (max-width: 600px) {
        .test-parent .test-child {
            width: 50%
        }
    }

    .x_title_custom {
        border-bottom: 2px solid;
        padding: 20px 5px 6px;
        margin-bottom: 10px;
    }

    .tile-stats h5 {
        color: #BAB8B8;
        font-size: 18px;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 5px;
    }

    @media only screen and (max-width: 600px) {
        .tile-stats h5 {
            font-size: 14px;
        }
    }


    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
        .tile-stats h5 {
            font-size: 16px;
        }
    }
</style>


<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><label id="lbtitleoutlet"></label></h3>
            </div>
        </div>

        <!-- <div style="display: inline-block;width:100%">
            <div class="tile_count test-parent">
                <div class="tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> In Progress</span>
                    <div class="count" id="countinprogress"></div>
                </div>
                <div class=" tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> Pick Up Item Confirmed</span>
                    <div class="count" id="countpickupitemconfirmed"></div>
                </div>
                <div class="  tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> Packing Item Confirmed</span>
                    <div class="count" id="countpackingitemconfirmed"></div>
                </div>
                <div class=" tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> In Transit</span>
                    <div class="count" id="countintransit"></div>
                </div>
                <div class=" tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> Delivered</span>
                    <div class="count" id="countdelivered"></div>
                </div>
                <div class=" tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> Not Delivered</span>
                    <div class="count" id="countnotdelivered"></div>
                </div>
                <div class=" tile_stats_count test-child">
                    <span class="count_top"><i class="fa fa-info-circle"></i> Partially Delivered</span>
                    <div class="count" id="countpartiallydelivered"></div>
                </div>
            </div>
        </div> -->

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <label name="CAPTION-MODE">Mode</label>
                <select id="filter_mode" onchange="filterMode(this.value)" name="filter_mode" class="form-control">
                    <option value="0"><label name="CAPTION-REALTIME">Realtime</label></option>
                    <option value="1"><label name="CAPTION-HISTORI">Histori</label>
                    </option>
                </select>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
                <label name="CAPTION-FILTERTANGGALSTATUS">Filter Tanggal Status</label>
                <input onchange="filterTglStatus()" type="date" disabled id="filter_tgl_status" class="form-control" name="filter_tgl_status" autocomplete="off" value="<?= getLastTbgDepo() ?>" />
            </div>
        </div>

        <div class="row">
            <?= CardDashboard() ?>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row ro-batch" id="do-table">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h5 name="CAPTION-PENGAJUANAPPROVAL">Pengajuan Approval</h5>
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="table_approval" width="100%" class="table">
                                        <thead>
                                            <tr>
                                                <th name="CAPTION-NO">No</th>
                                                <th name="CAPTION-JENISPENGAJUAN">Jenis Pengajuan</th>
                                                <th name="CAPTION-JUMLAH">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <div style="display: flex;justify-content: space-between">
                                    <h5 name="CAPTION-LISTSKUBELUMTERDAFTAR">List SKU yang belum terdaftar</h5>
                                    <button class="btn btn-primary btn-sm" title="refresh" onclick="handlerRefreshSKU()"><i class="fas fa-refresh"></i></button>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="listSKUNotInWMS" width="100%" class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <td width="7%" name="CAPTION-NO"><strong>No</strong></td>
                                                <td width="10%" name="CAPTION-PRINCIPLEKODE"><strong>Principle
                                                        Kode</strong></td>
                                                <td width="15%" name="CAPTION-SKUKONVERSI"><strong>SKU Konversi</strong>
                                                </td>
                                                <td width="25%" name="CAPTION-SKU"><strong>SKU</strong></td>
                                                <td width="15%" name="CAPTION-DATASOURCE"><strong>Datasource</strong>
                                                </td>
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

            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="row ro-batch" id="do-table">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title col-md-12 col-sm-12 col-xs-12">
                                <h5 name="CAPTION-JUMLAHSTATUSDELIVERYORDER">Jumlah Status Delivery Order</h5>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table id="table_status_do" width="100%" class="table">
                                            <thead>
                                                <tr>
                                                    <th name="CAPTION-STATUSDO">Status DO</th>
                                                    <th name="CAPTION-JUMLAH">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td style='vertical-align:middle;'>In Progress</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in progress')" class="btn btn-primary" id="countinprogress" style="margin-bottom: 1px"></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>In Progress Item Request</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in progress item request')" class="btn btn-primary" id="countinprogressitemrequest" style="margin-bottom: 1px"></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>In Progress Pick Up Item</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in progress pick up item')" class="btn btn-primary" id="countinprogresspickupitem" style="margin-bottom: 1px"></button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Pick Up Item Confirmed</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('pick up item confirmed')" class="btn btn-primary" id="countpickupitemconfirmed" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>In Progress Packing Item</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in progress packing item')" class="btn btn-primary" id="countinprogresspackingitem" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Packing Item Confirmed</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('packing item confirmed')" class="btn btn-primary" id="countpackingitemconfirmed" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>In Transit Validation</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in transit validation')" class="btn btn-primary" id="countintransitvalidation" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>In Transit Validation Completed
                                                    </td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in transit validation completed')" class="btn btn-primary" id="countintransitvalidationcompleted" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Intransit</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('in transit')" class="btn btn-primary" id="countintransit" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Delivered</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('delivered')" class="btn btn-primary" id="countdelivered" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Partially Delivered</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('partially delivered')" class="btn btn-primary" id="countpartiallydelivered" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Not Delivered</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('not delivered')" class="btn btn-primary" id="countnotdelivered" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style='vertical-align:middle;'>Canceled</td>
                                                    <td style='vertical-align:middle;'>
                                                        <button onclick="listdo('canceled')" class="btn btn-primary" id="countcanceled" style="margin-bottom: 1px"></button class="btn btn-primary">
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>
<!-- /page content -->

<div class="modal fade" id="modal-listdostatus" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-DELIVERYORDERDETAIL">List Delivery
                        Order</label>&nbsp;( <span id="statusdo"></span> )
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
                                    <th class="text-center" style="color:white;" name="CAPTION-NAMA">NAMA
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-ALAMAT">ALAMAT
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-ACTION">ACTION
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

<!-- modal view approval-->
<div class="modal fade" id="modalApproval" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 90%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-LISTAPPROVAL">List Approval</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2"><label name="CAPTION-JENISPENGAJUAN">Jenis Pengajuan</label></div>
                        <div class="col-lg-4"><input class="form-control" id="txt_jenis_pengajuan_detail" value="" readonly></div>
                    </div>
                    <div class="table-responsive">
                        <table id="table_detail_approval" style="width:100%" class="table table-hover  table-primary table-bordered ">
                            <thead>
                                <tr>
                                    <th style="text-align: center;" name="CAPTION-NO">No</th>
                                    <th style="text-align: center;" name="CAPTION-TGLPENGAJUAN">Tgl Pengajuan</th>
                                    <th style="text-align: center;" name="CAPTION-DIAJUKANOLEH">Diajukan Oleh</th>
                                    <th style="text-align: center;" name="CAPTION-NODOKUMEN">No Dokumen</th>
                                    <th style="text-align: center;" name="CAPTION-HISTORYAPPROVAL">History approval</th>
                                    <th style="text-align: center; display: none;" name="CAPTION-GUDANGASAL" id="showAsal">Gudang Asal
                                    </th>
                                    <th style="text-align: center; display: none;" name="CAPTION-GUDANGTUJUAN" id="showGudang">Gudang Tujuan</th>
                                    <th style="text-align: center; display: none;" name="CAPTION-CHECKER" id="showChecker">CHECKER</th>
                                    <th style="text-align: center;" name="CAPTION-Y">Y</th>
                                    <th style="text-align: center;" name="CAPTION-N">N</th>
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
                <button type="button" class="btn btn-success" id="saveApproval"><i class="fas fa-floppy-disk"></i>
                    <label name="CAPTION-SIMPAN">Simpan</label></button>
                <button type="button" class="btn btn-dark btnclosemodalbuatpackingdo" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>

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
                        <div class="col-lg-4"><input class="form-control" id="txt_jenis_pengajuan_history" value="" readonly></div>
                    </div><br><br>
                    <div class="table-responsive">
                        <table id="tableHistoryApproval" style="width:100%" class="table table-hover  table-primary table-bordered ">
                            <thead>
                                <tr>
                                    <th style="text-align: center;" name="CAPTION-NO">No</th>
                                    <th style="text-align: center;" name="CAPTION-TGLAPPROVAL">Tgl Approval</th>
                                    <th style="text-align: center;" name="CAPTION-NODOKUMEN">No Dokumen</th>
                                    <th style="text-align: center;" name="CAPTION-STATUS">Status</th>
                                    <th style="text-align: center;" name="CAPTION-OLEH">Oleh</th>
                                    <th style="text-align: center;" name="CAPTION-NOTE">Note</th>
                                    <th style="text-align: center;" name="CAPTION-GROUP">Group</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btnclosemodalbuatpackingdo" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>
<!-- modal view detail list sku-->
<div class="modal fade" id="modalListSKU" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 90%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-LISTSKU">List SKU</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-list-sku">
                            <thead>
                                <tr class="bg-primary">
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NO">No
                                    </th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">
                                        SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                    <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-QTYPLAN">Qty Plan</th>
                                    <!-- <th style="text-align: center; vertical-align: middle;color:white;"
                                    name="CAPTION-QTYAKTUAL">Qty Aktual</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" onclick="closeModalListSKU()"><i class="fas fa-xmark"></i>
                    <label name="CAPTION-TUTUP">Tutup</label></button>

            </div>
        </div>
    </div>
</div>