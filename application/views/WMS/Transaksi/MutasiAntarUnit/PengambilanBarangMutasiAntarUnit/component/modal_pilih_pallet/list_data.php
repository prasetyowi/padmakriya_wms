<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="container mt-2">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-5 col-xs-12">
                    <div class="head-switch-global">
                        <div class="switch-holder">
                            <div class="switch-toggle">
                                <input type="checkbox" id="check_scan" class="check_scan">
                                <label for="check_scan"></label>
                            </div>
                            <div class="switch-label-global">
                                <button type="button" class="btn btn-info" id="start_scan"><i class="fas fa-qrcode"></i>
                                    <label name="CAPTION-STARTSCAN">Start Scan</label></button>
                                <button type="button" class="btn btn-danger" style="display:none" id="stop_scan"><i
                                        class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop
                                        Scan</label></button>
                                <button type="button" class="btn btn-warning" style="display:none" id="input_manual"><i
                                        class="fas fa-keyboard"></i> <label name="CAPTION-MANUALINPUT">Manual
                                        Input</label></button>
                                <button type="button" class="btn btn-danger" style="display:none" id="close_input"><i
                                        class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close
                                        Input</label></button>
                            </div>
                        </div>
                    </div>
                    <div id="select_kamera"></div>
                    <div id="preview" style="display: none;"></div>

                    <div id="preview_input_manual" style="display: none;margin-top:10px">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label name="CAPTION-KODEPALLET">Kode Pallet</label>
                                    <input type="text" class="form-control" autocomplete="off" id="kode_barcode"
                                        placeholder="nomor setelah tanda '/' terakhir">
                                    <div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
                                        <table class="table table-striped table-sm table-hover" id="table-fixed">
                                            <tbody id="konten-table"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
									<label name="CAPTION-KODEPALLET">Kode Pallet</label>
									<input type="text" class="form-control" id="kode_barcode" placeholder="nomor setelah tanda '/' terakhir">
								</div> -->
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-success" id="search_kode_pallet"
                                    style="margin-top: 2rem;"><i class="fas fa-search"></i>
                                    <label name="CAPTION-CHECKKODE">Check Kode</label></button>
                                <button type="button" class="btn btn-success" id="check_kode" style="display: none;"><i
                                        class="fas fa-search"></i> Check Kode</button>
                                <span id="loading_cek_manual" style="display:none;"><i
                                        class="fa fa-spinner fa-spin"></i> Loading...</span>
                            </div>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label name="CAPTION-UPLOADBUKTICEKFISIK">Upload bukti cek fisik</label>
                            <input id="myFileInput" type="file" class="form-control" accept="image/*;capture=camera"
                                onchange="previewFile()">
                        </div>

                        <div id="show-file" style="margin-top: 7px;"></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                    <div class="from-group">
                        <label name="CAPTION-HASILSCANNOPALLET">Hasil Scan No. Pallet</label>
                        <input type="text" class="form-control" id="txtpreviewscan" readonly />
                    </div>
                </div>
            </div>
            <hr noshade="1">

            <div class="row" style="margin-top: 12px;">
                <div class="x_content table-responsive">
                    <table id="table_list_data_pilih_pallet" width="100%" class="table">
                        <thead>
                            <tr class="text-center">
                                <td><strong></strong></td>
                                <td><strong><label name="CAPTION-LOKASIRAK">Lokasi Rak</label></strong></td>
                                <td><strong><label name="CAPTION-LOKASIBIN">Lokasi Bin</label></strong></td>
                                <td><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
                                <td><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
                                <td><strong><label name="CAPTION-QTY">Qty</label></strong></td>
                                <!-- <td><strong><label name="CAPTION-QTYPLANKOREKSI">Qty Plan Koreksi</label></strong></td> -->
                                <td><strong><label name="CAPTION-QTYAKTUALKOREKSI">Qty Aktual Koreksi</label></strong>
                                </td>
                                <td><strong><label>Status</label></strong></td>
                                <td><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id='test'></div>
        </div>
    </div>
</div>