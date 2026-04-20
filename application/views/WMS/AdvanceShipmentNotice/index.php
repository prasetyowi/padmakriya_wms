<div class="right_col" role="main">
    <div>
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-ADVANCESHIPMENTNOTICE">Advance Shipment Notice</h3>
            </div>
            <div style="float: right;">
                <button type="button" id="btnImportASN" class="btn btn-md btn-primary btn-download-asn"><i class="fa fa-upload"></i>
                    <label name="CAPTION-IMPORTFILEASN"> Import File ASN</label></button>
                <button type="button" id="btnImportBatch" class="btn btn-md btn-primary btn-download-asn"><i class="fa fa-upload"></i>
                    <label name="CAPTION-IMPORTFILEBATCH"> Import File Batch</label></button>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                    </div>
                    <div class="x_content">
                        <form id="form-filter-do" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6" style="margin-bottom: 10px;">
                                    <div class="item form-group">
                                        <label class="col-form-label col-lg-6 col-md-6 col-sm-6 label-align" name="CAPTION-PRINCIPLE">Principle</label>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <select class="form-control select2" name="principle" id="principle" required>
                                                <option value="">-- Pilih Principle --</option>
                                                <?php foreach ($principle as $row) { ?>
                                                    <option value="<?= $row['principle_id'] ?>">
                                                        <?= $row['principle_kode'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" id="lastUpdate" name="lastUpdate" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <div class="col-md-12 col-sm-12 text-left">
                                        <button type="button" id="btn-download-asn" class="btn btn-md btn-primary btn-download-asn"><i class="fa fa-download"></i>
                                            <label name="CAPTION-DOWNLOADASN"> Download ASN</label></button>
                                        <button type="button" id="btn-submit-filter" class="btn btn-md btn-success btn-submit-filter"><i class="fa fa-search"></i>
                                            <label name="CAPTION-CARI"> Cari</label></button>
                                        <span id="loadingaction" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                            <label name="CAPTION-LOADING">Loading</label>...</span>
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
                                                <th width="15%" class="text-center" style="color: white;" name="CAPTION-DEPO">Depo
                                                </th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-SHIPMENT">Shipment</th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-JUMLAHDO">Jumlah DO</th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-QTY">Qty</th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-LASTDOWNLOAD">Last Download</th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-STATUS">Status</th>
                                                <th width="10%" class="text-center" style="color: white;" name="CAPTION-ACTION">Action</th>
                                                <th width="20%" class="text-center" style="color: white;" name="CAPTION-KETERANGAN">keterangan
                                                </th>
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
                            <h4 class="modal-title"><label name="CAPTION-LISTDELIVERYORDERBYSHIPMENT">List Delivery
                                    Order By Shipment</label>
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
                                                <th class="text-center" style="color:white;" name="CAPTION-SKUKODE">
                                                    SKU KODE
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-SKUNAMAPRODUK">
                                                    SKU NAMA PRODUK
                                                </th>
                                                <th class="text-center" style="color:white;" name="CAPTION-QTY">
                                                    QTY
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

            <div class="modal fade" id="modal-import-asn" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title"><label name="CAPTION-IMPORTFILEASN">Import FILE ASN</label>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
                                        <input type="file" class="form-control" name="files[]" id="file" placeholder="upload attachment" accept=".txt" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="alert alert-info">
                                        <strong><label name="CAPTION-CATATAN">Catatan</label></strong> : <label name="CAPTION-FORMATTXT">hanya bisa import file txt dengan format file</label> <strong>(.txt)</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
                            <button type="button" id="btnSimpanUpload" class="btn btn-success"><label name="CAPTION-SIMPAN">Simpan</label></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-import-batch" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title"><label name="CAPTION-IMPORTFILEBATCH">Import FILE BATCH</label>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="" name="CAPTION-FILEATTACHMENT">File Attachment</label>
                                        <input type="file" class="form-control" name="filesBatch[]" id="fileBatch" placeholder="upload attachment" accept=".txt" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="alert alert-info">
                                        <strong><label name="CAPTION-CATATAN">Catatan</label></strong> : <label name="CAPTION-FORMATTXT">hanya bisa import file txt dengan format file</label> <strong>(.txt)</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
                            <button type="button" id="btnSimpanUploadBatch" class="btn btn-success"><label name="CAPTION-SIMPAN">Simpan</label></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>