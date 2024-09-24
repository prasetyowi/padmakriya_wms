<div class="modal fade" id="view_logbook_security" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">
                    <label name="CAPTION-VIEWLOGBOOKSECURITY">View Logbook Security</label>
                </h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="container mt-2">
                            <div class="row">
                                <div class="x_content table-responsive">
                                    <table id="table_logbook_security" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td class="text-center">#</td>
                                                <td class="text-center"><strong><label name="CAPTION-SLKODE">Logbook
                                                            Security Kode</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-NOKENDARAAN">No. Kendaraan</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-DRIVER">Pengemudi</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-TGLMASUK">Tanggal
                                                            Masuk</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-TGLKELUAR">Tanggal
                                                            Keluar</label></strong></td>
                                                <td class="text-center"><strong><label name="CAPTION-TOTALSJ">Jumlah
                                                            Surat Jalan</label></strong></td>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="pilihSJ"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
                <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i>
                    <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="generate_logbook_security" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:90%">
        <!-- Modal content-->
        <form role="form" class="surat_jalanModify form-horizontal" id="surat_jalanModify" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">
                        <label name="CAPTION-GENERATELOGBOOKSECURITY">Generate Penerimaan Surat Jalan</label>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div id="surat_jalan">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="generateSJ"><i class="fas fa-save"></i> <label name="CAPTION-GENERATE">Generate</label></button>
                    <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i>
                        <label name="CAPTION-TUTUP">Tutup</label></button>
                </div>
            </div>
        </form>
    </div>
</div>