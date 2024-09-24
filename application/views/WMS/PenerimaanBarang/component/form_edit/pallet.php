<div class="row" id="show_add_pallet_edit" style="margin-top: 10px;display:none">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="container mt-2">
                <div class="row">
                    <div class="col-md-6">
                        <fieldset>
                            <legend>
                                <label name="CAPTION-PALLET">Pallet</label>
                                <button type="button" class="btn btn-primary btn-xs" id="add-row-edit"><i class="fas fa-plus"></i> <label name="CAPTION-ADDROW">Add Row</label></button>
                            </legend>
                            <div class="table-responsive">
                                <table id="data_pallet_edit" class="table table-striped" width="100%">
                                    <thead>
                                        <tr class="text-center">
                                            <td width="5%"><strong>#</strong></td>
                                            <td width="7%"><strong><label name="CAPTION-NOPALLET">No. Pallet</label></strong></td>
                                            <td width="15%"><strong><label name="CAPTION-JENISPALLET">Jenis Pallet</label></strong></td>
                                            <td width="15%"><strong><label name="CAPTION-LOKASITUJUAN">Lokasi Tujuan</label></strong></td>
                                            <td width="10%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-center bg-success" id="total_pallet_edit"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12" id="show_isi_sku_pallet_edit" style="display: none;">
                        <fieldset>
                            <legend>
                                <label name="CAPTION-DETAILPALLET">Detail Pallet</label>
                                <button type="button" class="btn btn-primary btn-xs" id="add-sku-edit"><i class="fas fa-plus"></i> <label name="CAPTION-ADDSKU">Add SKU</label></button>

                                <strong>[ <Span class="detail_pallet_ke_edit"></Span> ]</strong>
                            </legend>

                            <div class="span-example alert alert-info" style="display: none;">
                                <h4><i class="fa fa-info"></i> Informasi!</h4> <label name="CAPTION-MSG01_1">Jika melakukan perubahan pada Jumlah barang harap tekan enter setelah mengubahnya!</label>, <label name="CAPTION-MSG01_2">maupun itu nilainya null atau kosong</label>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" width="100%" id="list_detail_pallet_edit">
                                    <thead>
                                        <tr class="text-center">
                                            <td><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                                            <td><strong><label name="CAPTION-KODESKU">Kode SKU</label></strong></td>
                                            <td><strong><label name="CAPTION-NAMABARANG">Nama Barang</label></strong></td>
                                            <td><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                                            <td><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                                            <td><strong><label name="CAPTION-EXPIREDDATE">Exp Date</label></strong></td>
                                            <td><strong><label name="CAPTION-ACTUALEXPIREDDATE">Aktual Exp Date</label></strong></td>
                                            <td><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
                                            <td><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
                                            <td><strong><label name="CAPTION-TERIMA">Terima</label></strong></td>
                                            <td><strong><label name="CAPTION-SISA">Sisa</label></strong></td>
                                            <td width="6%"><strong><label name="CAPTION-JUMLAHBARANG">Jumlah Barang</label></strong></td>
                                            <td><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="13" class="text-center bg-success" id="total_detail_pallet_edit"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>