<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <!-- <h3><strong>Filter Data</strong></h3> -->
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-xl-5 col-lg-5 col-md-5 col-xs-12">
                        <button type="button" class="btn btn-info" id="start_scan"><i class="fas fa-qrcode"></i> <label
                                name="CAPTION-STARTSCAN">Start Scan</label></button>
                        <button type="button" class="btn btn-danger" style="display:none" id="stop_scan"><i
                                class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label></button>

                        <button type="button" class="btn btn-warning" id="input_manual"><i class="fas fa-keyboard"></i>
                            <label name="CAPTION-MANUALINPUT">Manual Input</label></button>
                        <button type="button" class="btn btn-danger" style="display:none" id="close_input"><i
                                class="fas fa-xmark"></i> <label name="CAPTION-CLOSEINPUT">Close Input</label></button>

                        <div id="select_kamera"></div>
                        <div id="preview" style="display: none;"></div>

                        <div id="preview_input_manual" style="display: none;margin-top:10px">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label name="CAPTION-KODEPALLET">Kode Pallet</label>
                                        <input type="text" class="form-control" id="kode_barcode"
                                            placeholder="nomor setelah tanda '/' terakhir">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="margin-top: 23px;">
                                        <button type="button" class="btn btn-success" id="check_kode"><i
                                                class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check
                                                Kode</label></button>
                                        <span id="loading_cek_manual" style="display:none;"><i
                                                class="fa fa-spinner fa-spin"></i> <label
                                                name="CAPTION-LOADING">Loading</label>...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
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
                <div class="row" style="margin-top: 10px;">
                    <div class="x_content table-responsive">
                        <table id="list_data_konfirmasi" width="100%" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <td width="5%"><strong><label name="CAPTION-NO">No</label></strong></td>
                                    <td width="15%"><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong>
                                    </td>
                                    <td width="10%"><strong><label name="CAPTION-JENISPALLET">Jenis
                                                Pallet</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                    <td width="20%"><strong><label name="CAPTION-LOKASIBINTUJUAN">Lokasi Bin
                                                Tujuan</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                                </tr>
                            </thead>
      
                      <tbody></tbody>
                        </table>
                    </div>
                </div>
                <hr noshade="1">
                <div style="float: right">
                    <button type="button" class="btn btn-success" id="konfirmasi_distribusi"><i
                            class="fas fa-check"></i> <label
                            name="CAPTION-KONFIRMASIDISTRIBUSIPENERIMAANSELESAI">Konfirmasi Distribusi Penerimaan
                            Selesai</label></button>
                    <button type="button" class="btn btn-primary" id="simpan_distribusi"><i class="fas fa-save"></i>
                        <label name="CAPTION-SIMPAN">Simpan</label></button>
                    <button type="button" class="btn btn-dark" id="kembali_distribusi"><i class="fas fa-arrow-left"></i>
                        <label name="CAPTION-KEMBALI">Kembali</label></button>
                </div>
            </div>
        </div>
    </div>
</div>