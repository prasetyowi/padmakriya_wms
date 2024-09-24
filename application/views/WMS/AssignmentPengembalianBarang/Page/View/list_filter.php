<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="container mt-2">
                <div class="row" style="margin-top: 10px;">
                    <div class="x_content table-responsive">
                        <table id="list_data_tambah_koreksi_pallet" width="100%" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <td width="5%"><strong>#</strong></td>
                                    <td width="10%"><strong><label>DO Batch Kode</label></strong></td>
                                    <td width="10%"><strong><label>Picking Validation Kode</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-SKUKODE">SKU Kode</label></strong></td>
                                    <td width="15%"><strong><label name="CAPTION-SKUNAMA">SKU Nama</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-SATUAN">Satuan</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-EXPIREDDATE">Expired Date</label></strong></td>
                                    <td width="7%"><strong><label name="CAPTION-QTYAVAILABLE">Qty Available</label></strong></td>
                                    <td width="10%"><strong><label name="CAPTION-QTYPLANKOREKSI">Qty Plan Koreksi</label></strong></td>
                                    <td width="7%"><strong><label name="CAPTION-TOTAL">Total</label></strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detail as $key => $value) {  ?>
                                    <tr class="text-center">
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value->delivery_order_batch_kode ?></td>
                                        <td><?= $value->picking_validation_kode ?></td>
                                        <td><?= $value->sku_kode ?></td>
                                        <td><?= $value->sku_nama_produk ?></td>
                                        <td><?= $value->sku_satuan ?></td>
                                        <td><?= $value->sku_kemasan ?></td>
                                        <td><?= $value->tgl ?></td>
                                        <td><?= $value->sku_qty_availabe ?></td>
                                        <td><?= $value->sku_qty_koreksi ?></td>
                                        <td><?= str_replace("-", "", $value->qtyTot) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr noshade="1">
                <div style="float: right">
                    <a href="<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/AssignmentPengembalianBarangMenu') ?>" class="btn btn-dark"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                </div>
            </div>
        </div>
    </div>
</div>