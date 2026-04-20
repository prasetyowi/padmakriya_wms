<style>
    .align-middle {
        vertical-align: middle !important;
    }

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

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .orange-box {
        width: 45px;
        height: 15px;
        background-color: #E67E22;
        /* Pink Chart.js style */
        border-radius: 4px;
        /* Rounded edges */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Soft shadow */
        margin: 10px;
    }

    .green-box {
        width: 45px;
        height: 15px;
        background-color: #26B99A;
        /* Pink Chart.js style */
        border-radius: 4px;
        /* Rounded edges */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Soft shadow */
        margin: 5px;
    }

    .blue-box {
        width: 45px;
        height: 15px;
        background-color: #03586A;
        /* Pink Chart.js style */
        border-radius: 4px;
        /* Rounded edges */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Soft shadow */
        margin: 10px;
    }

    .custom-box {
        width: 45px;
        height: 15px;
        /* Pink Chart.js style */
        border-radius: 4px;
        /* Rounded edges */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Soft shadow */
        margin: 10px;
    }
</style>


<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><label id="lbtitleoutlet"></label></h3>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" align="right">
            </div>
        </div>

        <div class="row">
            <div class="x_panel">
                <div class="x_content">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active"><a onclick="changeTab('dashboard_utama')" href="#dashboard_utama" role="tab" id="dashboard_utama_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Utama</a>
                            </li>
                            <li role="presentation"><a onclick="changeTab('dashboard_doi')" href="#dashboard_doi" id="dashboard_doi_tab" role="tab"
                                    data-toggle="tab" aria-expanded="true">Dashboard DOI</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_sj_supplier')" href="#dashboard_sj_supplier" role="tab" id="dashboard_sj_supplier_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard SJ Supplier VS Penerimaan</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_truck_supplier')" href="#dashboard_truck_supplier" role="tab" id="dashboard_truck_supplier_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Truck Supplier</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_stock')" href="#dashboard_stock" role="tab" id="dashboard_stock_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Stock</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_service_level')" href="#dashboard_service_level" role="tab" id="dashboard_service_level_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Service Level</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_status_do')" href="#dashboard_status_do" role="tab" id="dashboard_status_do_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Status DO</a>
                            </li>
                            <li role="presentation" class=""><a onclick="changeTab('dashboard_fleet_productivity')" href="#dashboard_fleet_productivity" role="tab" id="dashboard_fleet_productivity_tab"
                                    data-toggle="tab" aria-expanded="false">Dashboard Fleet Productivity</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">

                            <div role="tabpanel" class="tab-pane fade active in" id="dashboard_utama" aria-labelledby="dashboard_utama_tab">
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
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_doi" aria-labelledby="dashboard_doi_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="display:none;">
                                            <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                            <select id="filter_client_wms_report_doi" onchange="filterReportDOI('filter_perusahaan', 'dashboard_doi', null)" name="filter_client_wms" class="form-control">
                                                <option value="">Semua Perusahaan</option>
                                                <?php foreach ($perusahaan as $key => $value) : ?>
                                                    <option value="<?= $value['client_wms_nama'] ?>"><?= $value['client_wms_nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TARGETDOIINTERNAL">Target DOI Internal</label>
                                            <select id="filter_doi_internal" onchange="filterReportDOI('filter_perusahaan','dashboard_doi', null)" name="filter_doi_internal" class="form-control">
                                                <option value="30">30</option>
                                                <option value="60">60</option>

                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-PENJUALANTERAKHIR">Penjualan Terakhir (Bulan)</label>
                                            <select id="filter_month" onchange="filterReportDOI('filter_perusahaan','dashboard_doi', null)" name="filter_month" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3" selected>3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_perusahaan_report_doi" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <center>
                                                <label class="green-box toggleBarPerusahaan1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan1"></strong>
                                                <label class="blue-box toggleBarPerusahaan2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan2"></strong>
                                                <label class="orange-box toggleLinePerusahaan" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePerusahaan"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan1" checked> <span class="titleBarPerusahaan1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan2" checked> <span class="titleBarPerusahaan2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePerusahaan" checked> <span class="titleLinePerusahaan"></span></label> -->
                                            </center>
                                            <canvas id="chart_perusahaan_report_doi" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_principle_report_doi" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <center>
                                                <label class="green-box toggleBarPrinciple1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple1"></strong>
                                                <label class="blue-box toggleBarPrinciple2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple2"></strong>
                                                <label class="orange-box toggleLinePrinciple" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePrinciple"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple1" checked> <span class="titleBarPrinciple1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple2" checked> <span class="titleBarPrinciple2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePrinciple" checked> <span class="titleLinePrinciple"></span></label> -->
                                            </center>
                                            <canvas id="chart_principle_report_doi" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_sku_report_doi" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>SKU (<span id="principle_sku_doi" style="color: black"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_sku_report_doi" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>SKU Konversi Group</td>
                                                            <td>Nama</td>
                                                            <td>Satuan</td>
                                                            <td>Stok</td>
                                                            <td>AVG Sales</td>
                                                            <td>DOI</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_sj_supplier" aria-labelledby="dashboard_sj_supplier_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_sj_supplier" onchange="filterReportSJSupplier('filter_perusahaan','dashboard_sj_supplier', null)" name="filter_sj_supllier" class="form-control">
                                                <option value="daily">Today</option>
                                                <option value="mtd" selected>MTD</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALPENERIMAAN">Tanggal Penerimaan</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_sj_supplier" class="form-control" name="filter_tanggal_sj_supllier" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_perusahaan_sj_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan_sj_supplier">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <div class="row">
                                            </div>
                                            <center>
                                                <label class="green-box toggleBarPerusahaan1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan1"></strong>
                                                <label class="blue-box toggleBarPerusahaan2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan2"></strong>
                                                <label class="orange-box toggleLinePerusahaan" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePerusahaan"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan1" checked><span class="titleBarPerusahaan1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan2" checked> <span class="titleBarPerusahaan2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePerusahaan" checked> <span class="titleLinePerusahaan"></span></label> -->
                                            </center>
                                            <canvas id="chart_perusahaan_report_supplier" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_principle_sj_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle_sj_supplier">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <center>
                                                <label class="green-box toggleBarPrinciple1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple1"></strong>
                                                <label class="blue-box toggleBarPrinciple2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple2"></strong>
                                                <label class="orange-box toggleLinePrinciple" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePrinciple"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple1" checked> <span class="titleBarPrinciple1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple2" checked> <span class="titleBarPrinciple2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePrinciple" checked> <span class="titleLinePrinciple"></span></label> -->
                                            </center>
                                            <canvas id="chart_principle_report_supplier" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_sku_sj_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>SKU (<span id="principle_sku_sj_supplier" style="color: black"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_sku_report_supplier" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>Tanggal Awal</td>
                                                            <td>Tanggal Akhir</td>
                                                            <td>SKU Konversi Group</td>
                                                            <td>Nama</td>
                                                            <td>Satuan</td>
                                                            <td>QTY Penerimaan SJ</td>
                                                            <td>QTY Penerimaan Barang</td>
                                                            <td>Persentase</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_truck_supplier" aria-labelledby="dashboard_truck_supplier_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_truck_supplier" onchange="filterReportTruckSupplier('filter_perusahaan','dashboard_truck_supplier', null)" name="filter_truck_supplier" class="form-control">
                                                <option value="daily">Today</option>
                                                <option value="mtd" selected>MTD</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALKEDATANGAN">Tanggal Kedatangan</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_truck_supplier" class="form-control" name="filter_tanggal_truck_supplier" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_perusahaan_truck_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan_truck_supplier">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <center>
                                                <label class="green-box toggleBarPerusahaan1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan1"></strong>
                                                <label class="blue-box toggleBarPerusahaan2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPerusahaan2"></strong>
                                                <label class="orange-box toggleLinePerusahaan" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePerusahaan"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan1" checked> <span class="titleBarPerusahaan1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPerusahaan2" checked> <span class="titleBarPerusahaan2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePerusahaan" checked> <span class="titleLinePerusahaan"></span></label> -->
                                            </center>
                                            <canvas id="chart_perusahaan_report_truck_supplier" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_principle_truck_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle_truck_supplier">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <center>
                                                <label class="green-box toggleBarPrinciple1" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple1"></strong>
                                                <label class="blue-box toggleBarPrinciple2" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleBarPrinciple2"></strong>
                                                <label class="orange-box toggleLinePrinciple" style="vertical-align: middle;"><input type="checkbox" checked style="display: none;"></label> <strong class="titleLinePrinciple"></strong>
                                                <!-- <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple1" checked> <span class="titleBarPrinciple1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple2" checked> <span class="titleBarPrinciple2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePrinciple" checked> <span class="titleLinePrinciple"></span></label> -->
                                            </center>
                                            <canvas id="chart_principle_report_truck_supplier" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_sku_truck_supplier" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>List Kedatangan (<span id="principle_list_kedatangan_truck" style="color: black"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_sku_report_truck_supplier" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>Tanggal</td>
                                                            <td>Kode Penerimaan</td>
                                                            <td>Nopol</td>
                                                            <td>Tanggal Masuk</td>
                                                            <td>Tanggal Keluar</td>
                                                            <td>Durasi</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_stock" aria-labelledby="dashboard_stock_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_stock" disabled onchange="filterReportStock('filter_perusahaan', null)" name="filter_stock" class="form-control">
                                                <option value="daily" selected>Today</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_stock" class="form-control" name="filter_tanggal_stock" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_perusahaan_stock">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan_stock">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_perusahaan_report_stock" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_principle_stock" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle_stock">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_principle_report_stock" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_gudang_stock" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_gudang_stock">Gudang</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <center class="filter_chart_donut">
                                        </center>
                                        <div class="x_content" style="height: 300px;">

                                            <canvas id="chart_gudang_report_stock" data-flag="canvas_gudang"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_sku_stock" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>SKU (<span id="gudang_sku_stock" style="color: black;"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_sku_report_stock" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>Nama</td>
                                                            <td>Kemasan</td>
                                                            <td>Kode</td>
                                                            <td>Expired Date</td>
                                                            <td>Stock Akhir</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_service_level" aria-labelledby="dashboard_service_level_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_service_level" onchange="filterReportServiceLevel('filter_chart_1', null)" name="filter_service_level" class="form-control">
                                                <option value="daily">Today</option>
                                                <option value="mtd" selected>MTD</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALSO">Tanggal SO</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_service_level" class="form-control" name="filter_tanggal_service_level" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_tipe_service_level">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_tipe_service_level">Tipe</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_tipe_report_service_level" data-flag="canvas_tipe"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_perusahaan_service_level">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan_service_level">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_perusahaan_report_service_level" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_principle_service_level" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle_service_level">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_principle_report_service_level" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_so_service_level" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>SO (<span id="so_service_level" style="color: black;"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_so_report_service_level" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>Tanggal SO</td>
                                                            <td>Tanggal Kirim</td>
                                                            <td>No SO</td>
                                                            <td>No PO</td>
                                                            <td>Kode Sales</td>
                                                            <td>Sales</td>
                                                            <td>Kode Customer Eksternal</td>
                                                            <td>Principle</td>
                                                            <td>Perusahaan</td>
                                                            <td>Customer</td>
                                                            <td>Alamat</td>
                                                            <td>Tipe</td>
                                                            <td>Status</td>
                                                            <td>Nominal SO</td>
                                                            <td>Keterangan</td>
                                                            <td>Prioritas</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_status_do" aria-labelledby="dashboard_status_do_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_status_do" onchange="filterReportStatusDO('filter_chart_1', null)" name="filter_status_do" class="form-control">
                                                <option value="daily">Today</option>
                                                <option value="mtd" selected>MTD</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_status_do" class="form-control" name="filter_tanggal_status_do" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_status_status_do">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_status_status_do">Status Delivery Order</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_status_report_status_do" data-flag="canvas_status"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_perusahaan_status_do">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_perusahaan_status_do">Perusahaan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_perusahaan_report_status_do" data-flag="canvas_perusahaan"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" id="show_principle_status_do" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="nama_principle_status_do">Principle</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <canvas id="chart_principle_report_status_do" data-flag="canvas_principle"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_do_status_do" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4>SO (<span id="do_status_do" style="color: black;"></span>)</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <table id="table_do_report_status_do" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <td>Tanggal Rencana Kirim</td>
                                                            <td>No DO</td>
                                                            <td>No SO</td>
                                                            <td>No SO Eksternal</td>
                                                            <td>Principle</td>
                                                            <td>Sales</td>
                                                            <td>Nama Customer</td>
                                                            <td>Alamat Kirim</td>
                                                            <td>Area</td>
                                                            <td>Tipe</td>
                                                            <td>Tipe Layanan</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade " id="dashboard_fleet_productivity" aria-labelledby="dashboard_fleet_productivity_tab">
                                <div class="row">
                                    <div class="alert alert-info keterangan" role="alert">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="x_panel">
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-MODE">Mode</label>
                                            <select id="filter_mode_fleet_productivity" onchange="filterReportFleetProductivity('status_kendaraan','dashboard_fleet_productivity', null)" name="filter_fleet_productivity" class="form-control">
                                                <option value="daily">Today</option>
                                                <option value="mtd" selected>MTD</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label>
                                            <input disabled type="text" onchange="changeTanggalMasuk()" id="filter_tanggal_fleet_productivity" class="form-control" name="filter_tanggal_fleet_productivity" value="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_kendaraan_fleet_productivity" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="status_kendaraan_fleet_productivity">Status Kendaraan</h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <center class="filter_chart_donut">
                                        </center>
                                        <div class="x_content" style="height: 300px;">

                                            <canvas id="chart_kendaraan_report_fleet_productivity" data-flag="kendaraan_model"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="show_kendaraan_model_fleet_productivity" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h4 id="kendaraan_model_fleet_productivity"></h4>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content" style="height: 300px;">
                                            <!-- <center>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple1" checked> <span class="titleBarPrinciple1"></span></label>
                                                <label style="padding-right: 10px;"><input type="checkbox" class="toggleBarPrinciple2" checked> <span class="titleBarPrinciple2"></span></label>
                                                <label><input type="checkbox" class="toggleLinePrinciple" checked> <span class="titleLinePrinciple"></span></label>
                                            </center> -->
                                            <canvas id="chart_kendaraan_model_report_fleet_productivity" data-flag="list_detail_kendaraan_model"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="show_list_kendaraan_fleet_productivity" style="display: none;">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <div class="row">
                                                <!-- <div class="col-lg-2 col-md-2">
                                                    <label class="control-label" for="fleet_area" name="CAPTION-FILTERAREA">Filter Area :</label>
                                                </div> -->
                                                <div class="col-lg-4 col-md-4 ">
                                                    <select id="fleet_area" class="selectpicker form-control" name="fleet_area" data-live-search="true" style="color:#000000" multiple required data-actions-box="true" title="Select System"> </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2">
                                                    <span><button class="btn btn-primary" onclick="filterAreaFleet()">Filter</button></span>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="btn-group" style="float:right">
                                                        <button id="btn_detail_chart_kendaraan" class="btn btn-sm btn-primary" onclick="showDetailKendaraan('kendaraan_by_area')" type="button"><label name="CAPTION-KENDARAANBYAREA">Kendaraan by Area</label></button>
                                                        <button id="btn_list_detail_kendaraan" class="btn btn-sm btn-default" onclick="showDetailKendaraan('list_detail_kendaraan_model')" type="button"><label name="CAPTION-LISTDETAILKENDARAAN">List Detail Kendaraan</label></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <h4>Kendaraan Model ( <strong id="title_detail_kendaraan"></strong> )</h4>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="x_content">
                                            <div class="table-responsive" id="show_list_detail_kendaraan_fleet_productivity" style="display: none;">
                                                <table id="table_list_kendaraan_report_fleet_productivity" width="100%" class="table">
                                                    <thead class="bg-primary">
                                                        <tr class="bg-primary">
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALBATCH">Tanggal Batch</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALPENGIRIMAN">Tanggal Pengiriman</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-DRIVER">Driver</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-KENDARAAN">Kendaraan</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-RITASI">Ritasi</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-BERATMAX">Berat (gram) Max</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-VOLUMEMAX">Volume (cm<sup>3</sup>) Max</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-BERATTERPAKAI">Berat (gram) Terpakai</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-VOLUMETERPAKAI">Volume (cm<sup>3</sup>) Terpakai</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-PRESENTASEBERATTERPAKAI">Presentase Berat (gram) Terpakai</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-PRESENTASEVOLUMETERPAKAI">Presentase Volume (cm<sup>3</sup>) Terpakai</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOFDJR">No. FDJR</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPELAYANAN">Tipe Layanan</label></strong></td>
                                                            <!-- <td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td> -->
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TOTALOUTLET">Total Outlet</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                                            <!-- <td class="text-center" style="color:white;"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-UBAH-DRIVER-KENDARAAN">Ubah Driver/Kendaraan</label></strong></td> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="x_panel" id="show_kendaraan_area_fleet_productivity">
                                                <div class="x_content" style="height: 300px;">
                                                    <canvas id="chart_kendaraan_area_report_fleet_productivity" data-flag="list_detail_kendaraan_nopol"></canvas>
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
    </div>
</div>
<!-- /page content -->

<div class="modal fade" id="modal-listShowDetailByCard" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="titleDetailShowCard"></h4>
            </div>
            <div class="modal-body">
                <div class="row table-responsive" style="margin-right: 10px; margin-left: 10px;">
                    <table id="table-detailShowCard" width="100%" class="table table-striped">
                        <thead>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

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
<div class="modal fade" id="modalDetalKendaraanModel" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title"><label name="CAPTION-DETAILKENDARAANMODEL">Detail Kendaraan Model</label></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="x_content" style="height: 300px;">
                            <canvas id="chart_detail_kendaraan_nopol_report_fleet_productivity"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btnCloseDetailKendaraanModel" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>