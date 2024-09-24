<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <?php $this->load->view("WMS/AssignmentPengembalianBarang/component/Button/scan") ?>

                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row" style="margin-top: 10px;">
                    <div class="x_content table-responsive">
                        <table id="list_data_tambah_koreksi_pallet" width="100%" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <td style="display:none"></td>
                                    <!-- <td class="text-center" width="7%">
                                        <input type="checkbox" id="example-select-all" style="transform: scale(1.5)" class="form-control input-sm" width="20" />
                                    </td> -->
                                    <td width="10%"><strong><label>DO Batch Kode</label></strong></td>
                                    <td width="10%"><strong><label>Picking Validation Kode</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-SKUKODE">SKU Kode</label></strong></td>
                                    <td width="15%"><strong><label name="CAPTION-SKUNAMA">SKU Nama</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
                                    <td width="7%"><strong><label name="CAPTION-QTYAVAILABLE">Qty Available</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-QTYPLANKOREKSI">Qty Plan Koreksi</label></strong></td>
                                    <td width="7%"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10" class="text-center bg-success" id="total_detail_pallet"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <hr noshade="1">
                <div style="float: right">
                    <button type="button" class="btn btn-success" onclick="handleSaveDataKoreksiPallet()"><i class="fas fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                    <button type="button" class="btn btn-dark" onclick="handleBackKoreksiPallet()"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">Kembali</label></button>
                </div>
            </div>
        </div>
    </div>
</div>