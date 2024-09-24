<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 id="titleMenu" name="CAPTION-TINDAKLANJUTHASILSTOCKOPNAME">Tindak Lanjut Hasil Stock Opname</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="page-title">
                        <div style="float: right">
                            <a href="<?= base_url('WMS/TindakLanjutHasilStockOpname/TindakLanjutHasilStockOpnameMenu') ?>" class="btn btn-sm btn-dark" id="btnKembali"><i class="fas fa-arrow-left"></i> <span name="CAPTION-KEMBALI">Kembali</span></a>
                            <button type="button" class="btn btn-success btn-sm" id="btnKonfirmasiDataOpname" onclick="handlerSaveDataOpname('konfirmasi')"><i class="fas fa-check"></i> <span name="CAPTION-KONFIRMASI" class="text-white">Konfirmasi</span></button>
                            <button type="button" class="btn btn-primary btn-sm" id="btnSaveDataOpname" onclick="handlerSaveDataOpname('update')"><i class="fas fa-save"></i> <span name="CAPTION-SIMPAN" class="text-white">Simpan</span></button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="container mt-2">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label name="CAPTION-KODEDOKUMEN">Kode Dokumen</label>
                                    <select name="kode_dokumen" disabled id="kode_dokumen" class="form-control select2" onchange="handlerGetDataOpnameByKode(this.value)">
                                        <option value=""><?= $header['kode'] ?></option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-PENANGGUNGJAWAB ">Penanggung Jawab</label>
                                            <input type="text" class="form-control" name="penanggung_jawab" id="penanggung_jawab" placeholder="Penanggung Jawab" disabled value="<?= $header['penanggung_jawab'] ?>">
                                            <input type="hidden" class="form-control" name="lastUpdate" id="lastUpdate" value="<?= $header['tglUpdate'] ?>">
                                            <input type="hidden" class="form-control" name="trOpnameResultID" id="trOpnameResultID" value="<?= $header['id'] ?>">
                                            <input type="hidden" class="form-control" name="jmlDataDetail" id="jmlDataDetailEdit" value="<?= COUNT($detail) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-TANGGAL">Tanggal</label>
                                            <input type="text" class="form-control" name="tgl" id="tgl" placeholder="dd-mm-yyyy" disabled value="<?= $header['tanggal'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-PT">Perusahaan</label>
                                            <input type="text" class="form-control" name="perusahaan" id="perusahaan" placeholder="Perusahaan" disabled value="<?= $header['perusahaan'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-PRINCIPLE">Principle</label>
                                            <input type="text" class="form-control" name="principle" id="principle" placeholder="Principle" disabled value="<?= $header['principle'] ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-JENISSTOCK">Jenis Stock</label>
                                            <input type="text" class="form-control" name="jenis_stock" id="jenis_stock" placeholder="Jenis Stock" disabled value="<?= $header['jenis_stock'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label>
                                            <input type="text" class="form-control" name="tipe_stock_opname" id="tipe_stock_opname" placeholder="Tipe Stock Opname" disabled value="<?= $header['tipe_stock_opname'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-009001000">Unit Cabang</label>
                                            <input type="text" class="form-control" name="unit_cabang" id="unit_cabang" placeholder="Unit Cabang" disabled value="<?= $header['unit_cabang'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="CAPTION-AREAOPNAME">Area Opname</label>
                                            <input type="text" class="form-control" name="area_opname" id="area_opname" placeholder="Area Opname" disabled value="<?= $header['area_opname'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label name="CAPTION-STATUS">Status</label>
                                    <input type="text" class="form-control" name="status" id="status" placeholder="Status" disabled value="<?= $header['status'] ?>">
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                                <div class="form-group">
                                    <label name="CAPTION-KETERANGAN">Keterangan</label>
                                    <textarea readonly cols="10" style="width: 100%;height: 120px" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"><?= $header['keterangan'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="showDetailTableEdit">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4><strong>List SKU</strong></h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="container mt-2">
                        <div class="table-responsive">
                            <table id="tableSKUOpname" width="100%" class="table table-striped table-bordered">
                                <thead class="bg-primary">
                                    <th class="text-center" style="color: white;">#</th>
                                    <th class="text-center" style="color: white;">Kode SKU</th>
                                    <th class="text-center" style="color: white;">Nama SKU</th>
                                    <th class="text-center" style="color: white;">Satuan</th>
                                    <th class="text-center" style="color: white;">Qty Actual</th>
                                    <th class="text-center" style="color: white;">Qty Sistem</th>
                                    <th class="text-center" style="color: white;">Selisih</th>
                                    <th class="text-center" style="color: white;">Invoice</th>
                                    <th class="text-center" style="color: white;">Pemutihan</th>
                                    <th class="text-center" style="color: white;">Opname Ulang</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($detail as $key => $value) : ?>
                                        <tr>
                                            <td class="text-center"><?= $key + 1 ?></td>
                                            <td class="text-center"><?= $value['sku_kode'] ?></td>
                                            <td class="text-center"><?= $value['sku_nama_produk'] ?></td>
                                            <td class="text-center"><?= $value['sku_satuan'] ?></td>
                                            <td class="text-center"><?= $value['sku_actual_qty_opname'] ?></td>
                                            <td class="text-center"><?= $value['sku_qty_sistem'] ?></td>
                                            <td class="text-center"><?= $value['sku_qty_selisih'] ?><input type="hidden" data-sku="<?= $value['sku_stock_id'] ?>_<?= $value['sku_id'] ?>" name="selisihQty" id="selisihQty-<?= $key ?>" value="<?= $value['sku_qty_selisih'] ?>" /></td>
                                            <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyInvoice" id="qtyInvoice" placeholder="0" value="<?= $value['qty_invoice'] ?>" /></td>
                                            <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyPemutihan" id="qtyPemutihan" value="<?= $value['qty_pemutihan'] ?>" placeholder="0" /></td>
                                            <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyOpnameUlang" id="qtyOpnameUlang" value="<?= $value['qty_opname_ulang'] ?>" placeholder="0" /></td>
                                        </tr>
                                    <?php endforeach  ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>