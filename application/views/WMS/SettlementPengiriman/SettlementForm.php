<?php foreach ($SettlementHeader as $i => $header) : ?>
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3 name="CAPTION-SETTLEMENTPENGIRIMAN">Settlement Pengiriman</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="panel panel-default">
                <div class="panel-heading"><label name="Settlement">Settlement</label></div>
                <div class=" panel-body form-horizontal form-label-left">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-NOFDJR">No
                                    FDJR</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="hidden" class="form-control" name="delivery_order_batch_id" id="delivery_order_batch_id" value="<?php echo $delivery_order_batch_id ?>" required>
                                    <input type="hidden" class="form-control" name="txt_jumlah" id="txt_jumlah" value="0" readonly>
                                    <input type="text" class="form-control" name="filter_fdjr_no" id="filter_fdjr_no" value="<?= $header['delivery_order_batch_kode'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-DRIVER">Driver</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" name="txt_driver_fdjr" id="txt_driver_fdjr" value="<?= $header['karyawan_nama'] ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="text" class="form-control" name="txt_driver_fdjr" id="txt_driver_fdjr" value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal_kirim'])) ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label col-md-4 col-sm-4 label-align" name="CAPTION-AREA">Area</label>
                                <div class="col-md-6 col-sm-6">
                                    <textarea id="fdjr_area" class="form-control" disabled><?php foreach ($getPengirimanArea as $data) {
                                                                                                echo $data['area_nama'] . ', ';
                                                                                            } ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="x-content" style="padding-top: 0.5rem;">
                <ul class="nav nav-tabs bar_tabs nav-pills" id="myTab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" id="slcBarang" data-toggle="tab" href="#home" role="tab" aria-controls="penerimaanBarang" aria-selected="true" name="CAPTION-PENERIMAANBARANG">Penerimaan
                            Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="slcKomparasi" data-toggle="tab" href="#home" role="tab" aria-controls="penerimaanBarang" aria-selected="true" name="CAPTION-KOMPARASIINVOICETUNAIVSPENERIMAAN">Komparasi Invoice Tunai vs Penerimaan
                            Tunai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="slcTunai" data-toggle="tab" href="#profile" role="tab" aria-controls="penerimaanTunai" aria-selected="false" name="CAPTION-PENERIMAANTUNAI">Penerimaan
                            Tunai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="slcBG" data-toggle="tab" href="#contact" role="tab" aria-controls="penerimaanBG" aria-selected="false" name="CAPTION-PENERIMAANBG">Penerimaan BG</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div id="penerimaanBarang" role="tabpanel" aria-labelledby="penerimaanBarang-tab">
                        <div class="panel panel-default">
                            <div class="panel-heading"><label name="CAPTION-126006000">Penerimaan Barang</label></div>
                            <div class="panel-body form-horizontal form-label-left">
                                <div class="row">
                                    <table id="tablesettlementbarang" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" name="CAPTION-NO" class="text-center">No</th>
                                                <th rowspan="2" name="CAPTION-PRINCIPLE" class="text-center">Principle</th>
                                                <th rowspan="2" name="CAPTION-BRAND" class="text-center">Brand</th>
                                                <th rowspan="2" name="CAPTION-KODESKU" class="text-center">Kode SKU</th>
                                                <th rowspan="2" name="CAPTION-NAMASKU" class="text-center">Nama SKU</th>
                                                <th rowspan="2" name="CAPTION-SATUAN" class="text-center">Satuan</th>
                                                <th rowspan="2" name="CAPTION-ORDER" class="text-center">Order</th>
                                                <th colspan="2" name="CAPTION-REGULER" class="text-center">Reguler</th>
                                                <th colspan="2" name="CAPTION-RETUR" class="text-center">Retur</th>
                                                <th rowspan="2" name="CAPTION-BTB" class="text-center">BTB</th>
                                                <th rowspan="2" name="CAPTION-STATUSSETTLEMENT" class="text-center">Status Settlement</th>
                                                <th rowspan="2" name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th>
                                            </tr>
                                            <tr>
                                                <th name="CAPTION-TERKIRIM" class="text-center">Terkirim</th>
                                                <th name="CAPTION-GAGAL" class="text-center">Gagal</th>
                                                <th name="CAPTION-TERKIRIM" class="text-center">Terkirim</th>
                                                <th name="CAPTION-GAGAL" class="text-center">Gagal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($PenerimaanBarang as $i => $value) : ?>
                                                <tr>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $i + 1 ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['principle'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['brand'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_kode'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_nama'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_satuan'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtyorder'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtyterkirim'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtygagal'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtyreturterkirim'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtyreturgagal'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['qtybtb'] ?></td>
                                                    <td class="text-center <?= $value['status_settlement'] == 'Cocok' ? 'text-success' : 'text-danger' ?>" style="vertical-align: middle;">
                                                        <input type="hidden" id="status_settlement_barang_<?= $i ?>" value="<?= $value['status_settlement'] ?>">
                                                        <?= $value['status_settlement'] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <!-- <a href="<?php echo base_url(); ?>WMS/SettlementPengiriman/DetailSettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>&sku_id=<?= $value['sku_id'] ?>&sku_kode=<?= $value['sku_kode'] ?>&sku_nama=<?= $value['sku_nama'] ?>&sku_kemasan=<?= $value['sku_kemasan'] ?>&sku_satuan=<?= $value['sku_satuan'] ?>&qty_do=<?= $value['totalqtydo'] ?>&qty_aktual=<?= $value['totalqtyinout'] ?>&status=<?= $value['status_settlement'] ?>" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a> -->
                                                        <button class="btn btn-primary" title="Lihat detail transaksi" onclick="detailTransaksi('<?= $delivery_order_batch_id ?>', '<?= $value['sku_id'] ?>', '<?= $value['sku_kode'] ?>', '<?= $value['sku_nama'] ?>', '<?= $value['sku_satuan'] ?>', '<?= $value['status_settlement'] ?>')"><i class="fas fa-eye"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                        <!-- <tbody>
                                            <?php
                                            foreach ($PenerimaanBarang as $i => $value) : ?>
                                                <tr>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $i + 1 ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['principle'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['brand'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_kode'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_nama'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_kemasan'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['sku_satuan'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['totalqtyorido'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['totalqtydo'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['totalqtyinout'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['deviasi'] ?></td>
                                                    <td class="text-center <?= $value['statussettlement'] == 'Cocok' ? 'text-success' : 'text-danger' ?>" style="vertical-align: middle;">
                                                        <input type="hidden" id="status_settlement_barang_<?= $i ?>" value="<?= $value['statussettlement'] ?>">
                                                        <?= $value['statussettlement'] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url(); ?>WMS/SettlementPengiriman/DetailSettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>&sku_id=<?= $value['sku_id'] ?>&sku_kode=<?= $value['sku_kode'] ?>&sku_nama=<?= $value['sku_nama'] ?>&sku_kemasan=<?= $value['sku_kemasan'] ?>&sku_satuan=<?= $value['sku_satuan'] ?>&qty_do=<?= $value['totalqtydo'] ?>&qty_aktual=<?= $value['totalqtyinout'] ?>&status=<?= $value['statussettlement'] ?>"
                                                    class="btn btn-link" target="_blank"><span
                                                        name="CAPTION-LIHAT">Lihat</span></a>
                                                        <button class="btn btn-primary" title="Lihat detail transaksi" onclick="detailTransaksi('<?= $delivery_order_batch_id ?>', '<?= $value['sku_id'] ?>', '<?= $value['sku_kode'] ?>', '<?= $value['sku_nama'] ?>', '<?= $value['sku_kemasan'] ?>', '<?= $value['sku_satuan'] ?>', '<?= $value['totalqtydo'] ?>', '<?= $value['totalqtyinout'] ?>', '<?= $value['statussettlement'] ?>')"><i class="fas fa-eye"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody> -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modalDetailTransaksi" role="dialog" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" style="width:80%">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title" name="CAPTION-DETAILSETTLEMENT">Detail Settlement</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="panel panel-default">
                                            <div class="panel-body form-horizontal form-label-left">
                                                <div class="row">
                                                    <table id="tableskuheader" width="100%" class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td style="border: none;" name="CAPTION-KODESKU">Kode SKU</td>
                                                                <td style="border: none;">
                                                                    <input type="hidden" id="delivery_order_batch_id" class="form-control" name="delivery_order_batch_id" />
                                                                    <input type="hidden" id="sku_id" class="form-control" name="sku_id" />
                                                                    <input type="text" id="filter_sku_kode" class="form-control" name="filter_sku_kode" readonly />
                                                                </td>
                                                                <td style="border: none;" name="CAPTION-NAMASKU">Nama SKU</td>
                                                                <td style="border: none;">
                                                                    <input type="text" id="filter_sku_nama" name="filter_sku_nama" class="form-control" readonly />
                                                                </td>

                                                                <!-- <td name="CAPTION-QTYDO">Qty DO</td>
                                                                <td>
                                                                    <input type="text" id="filter_qty_do" name="filter_qty_do" class="form-control" readonly />
                                                                </td>
                                                                <td name="CAPTION-ACTUALQTYINOUT">Aktual Qty In Out</td>
                                                                <td>
                                                                    <input type="text" id="filter_qty_aktual" name="filter_qty_aktual" class="form-control" readonly />
                                                                </td>
                                                                <td name="CAPTION-SELISIH">Selisih</td>
                                                                <td>
                                                                    <input type="text" id="filter_qty_selisih" name="filter_qty_selisih" class="form-control" readonly />
                                                                </td> -->
                                                            </tr>
                                                            <tr>
                                                                <td style="border: none;" name="CAPTION-SKSATUAN">SKU Satuan</td>
                                                                <td style="width:10%; border: none;"><input type="text" id="filter_sku_satuan" name="filter_sku_satuan" class="form-control" readonly /></td>
                                                                <td style="border: none;" name="CAPTION-STATUS">Status</td>
                                                                <td style="border: none;">
                                                                    <input type="text" id="filter_status" name="filter_status" class="form-control" readonly />
                                                                </td>
                                                            </tr>
                                                            <!-- <tr>
                                                                <td name="CAPTION-NAMASKU">Nama SKU</td>
                                                                <td colspan="5">
                                                                    <input type="text" id="filter_sku_nama" name="filter_sku_nama" class="form-control" readonly />
                                                                </td>
                                                                <td style="width:10%;"><input type="text" id="filter_sku_kemasan" name="filter_sku_kemasan" class="form-control" readonly />
                                                                </td>
                                                                <td style="width:10%;"><input type="text" id="filter_sku_satuan" name="filter_sku_satuan" class="form-control" readonly />
                                                                </td>
                                                                <td name="CAPTION-STATUS">Status</td>
                                                                <td>
                                                                    <input type="text" id="filter_status" name="filter_status" class="form-control" readonly />
                                                                </td>
                                                            </tr> -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <input type="hidden" id="txt_jumlah" name="txt_jumlah" value="0" />
                                            <div class="panel-body form-horizontal form-label-left">
                                                <div class="row">
                                                    <table id="tableskumenu" width="100%" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" name="CAPTION-NO">No</th>
                                                                <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                                                                <th class="text-center" name="CAPTION-DOKUMEN">Dokumen</th>
                                                                <th class="text-center" name="CAPTION-JENISDOKUMEN">Jenis
                                                                    Dokumen</th>
                                                                <th class="text-center" name="CAPTION-STATUSDOKUMEN">Status
                                                                    Dokumen</th>
                                                                <th class="text-center" name="CAPTION-JUMLAH">Jumlah</th>
                                                                <th class="text-center" name="CAPTION-TOTAL">Total</th>
                                                                <th class="text-center" name="CAPTION-SUGGESTIONSYSTEM">
                                                                    Suggestion System</th>
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-xmark"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="KomparasiTunai" role="tabpanel" style="display: none;" aria-labelledby="KomparasiTunai-tab">
                        <div class="panel panel-default">
                            <div class="panel-heading"><label name="CAPTION-KOMPARASIINVOICETUNAIVSPENERIMAAN">Komparasi
                                    Invoice Tunai vs Penerimaan
                                    Tunai</label></div>
                            <div class="panel-body form-horizontal form-label-left">
                                <div class="row">
                                    <table id="tablesettlementtunai" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th name="CAPTION-NO" class="text-center">No</th>
                                                <th name="CAPTION-GROUP" class="text-center">Group</th>
                                                <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                                <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                                <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                                <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                                <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                                <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                                <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                                <!-- <th name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($KomparasiInvoice as $i => $value) : ?>
                                                <tr>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $value['idx'] ?>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['groupurut'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $value['tgl'] ?>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <button type="button" id="btn_document_no<?= $i ?>" class="btn btn-link" onclick="ViewDetailDO('<?= $value['documentno']; ?>')"><?= $value['documentno']; ?></button>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['namaoutlet'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['alamatoutlet'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['keterangan'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= round($value['nominal']) ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <input type="hidden" id="status_settlement_tunai_<?= $i ?>" value="<?= $value['nominalkumulatif'] ?>">
                                                        <?= round($value['nominalkumulatif']) ?>
                                                    </td>
                                                    <!-- <td class="text-center">
                              <a href="<?php echo base_url(); ?>WMS/SettlementPengiriman/DetailSettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>&sku_id=<?= $value['sku_id'] ?>&sku_kode=<?= $value['sku_kode'] ?>&sku_nama=<?= $value['sku_nama'] ?>&sku_kemasan=<?= $value['sku_kemasan'] ?>&sku_satuan=<?= $value['sku_satuan'] ?>&qty_do=<?= $value['totalqtydo'] ?>&qty_aktual=<?= $value['totalqtyinout'] ?>&status=<?= $value['statussettlement'] ?>" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a>
                           </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <?php if (count($KomparasiInvoice) > 0) {
                                            $endDataForKomparison = (int)end($KomparasiInvoice)['nominalkumulatif'];
                                        } else {
                                            $endDataForKomparison = 0;
                                        }
                                        ?>
                                        <tfoot>
                                            <tr style="font-size: 1.3em;font-weight: bold; background: grey; color: white">
                                                <td colspan=" 9">
                                                    <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="Toleransi">Toleransi</span></div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%">+/-<?= $getToleransi ?></div>
                                                            </div>
                                                        </div>
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="CAPTION-TOLERANSI">Kumulatif Total</span>
                                                                </div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%"><?= $endDataForKomparison ?></div>
                                                            </div>
                                                        </div>
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="CAPTION-STATUS">Status</span></div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%">
                                                                    <?= ($endDataForKomparison >= ((int)$getToleransi * -1)) && ($endDataForKomparison <= (int)$getToleransi) ? 'Cocok' : 'Tidak Cocok' ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <br><br>
                                <!-- <div class="row">
                                    <div class="col-lg-6">
                                        <button type="button" id="btn_proses_selisih" class="btn btn-primary">Proses Barang Selisih</button>
                                        <button type="button" id="btn_sproses_titipan" class="btn btn-primary">Proses Barang Titipan</button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                            <label name="CAPTION-LOADING">Loading</label>...</span>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" id="btn_simpan_settlement" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/SettlementPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div id="penerimaanTunai" role="tabpanel" style="display: none;" aria-labelledby="penerimaanTunai-tab">
                        <div class="panel panel-default">
                            <div class="panel-heading"><label name="CAPTION-PENERIMAANTUNAI">Penerimaan Tunai</label></div>
                            <div class="panel-body form-horizontal form-label-left">
                                <div class="row">
                                    <table id="tablepenerimaansettlementtunai" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th name="CAPTION-NO" class="text-center">No</th>
                                                <th name="CAPTION-GROUP" class="text-center">Group</th>
                                                <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                                <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                                <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                                <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                                <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                                <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                                <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                                <!-- <th name="CAPTION-DETAILTRANSAKSI" class="text-center">Detail Transaksi</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($PenerimaanTunai as $i => $value) : ?>
                                                <tr>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $value['idx'] ?>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['groupurut'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;"><?= $value['tgl'] ?>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <button type="button" id="btn_document_no<?= $i ?>" class="btn btn-link" onclick="ViewDetailDO('<?= $value['documentno']; ?>')"><?= $value['documentno']; ?></button>
                                                    </td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['namaoutlet'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['alamatoutlet'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= $value['keterangan'] ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <?= round($value['nominal']) ?></td>
                                                    <td class="text-center" style="vertical-align: middle;">
                                                        <input type="hidden" id="status_settlement_tunai_<?= $i ?>" value="<?= $value['nominalkumulatif'] ?>">
                                                        <?= round($value['nominalkumulatif']) ?>
                                                    </td>
                                                    <!-- <td class="text-center">
                              <a href="<?php echo base_url(); ?>WMS/SettlementPengiriman/DetailSettlementMenu/?delivery_order_batch_id=<?= $delivery_order_batch_id ?>&sku_id=<?= $value['sku_id'] ?>&sku_kode=<?= $value['sku_kode'] ?>&sku_nama=<?= $value['sku_nama'] ?>&sku_kemasan=<?= $value['sku_kemasan'] ?>&sku_satuan=<?= $value['sku_satuan'] ?>&qty_do=<?= $value['totalqtydo'] ?>&qty_aktual=<?= $value['totalqtyinout'] ?>&status=<?= $value['statussettlement'] ?>" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a>
                           </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br><br>
                                <!-- <div class="row">
                                    <div class="col-lg-6">
                                        <button type="button" id="btn_proses_selisih" class="btn btn-primary">Proses Barang Selisih</button>
                                        <button type="button" id="btn_sproses_titipan" class="btn btn-primary">Proses Barang Titipan</button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                                        <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                            <label name="CAPTION-LOADING">Loading</label>...</span>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" id="btn_simpan_settlement" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                                        <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/SettlementPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div id="penerimaanBG" role="tabpanel" style="display: none;" aria-labelledby="penerimaanBG-tab">
                        <div class="panel panel-default">
                            <div class="panel-heading"><label name="CAPTION-PENERIMAANBG">Penerimaan BG</label></div>
                            <div class="panel-body form-horizontal form-label-left">
                                <div class="row">
                                    <table id="tablepenerimaanbgsettlementtunai" width="100%" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th name="CAPTION-NO" class="text-center">No</th>
                                                <th name="CAPTION-GROUP" class="text-center">Group</th>
                                                <th name="CAPTION-TANGGAL" class="text-center">Tanggal</th>
                                                <th name="CAPTION-NODO" class="text-center">NO DO</th>
                                                <th name="CAPTION-OUTLET" class="text-center">Outlet</th>
                                                <th name="CAPTION-ALAMATOUTLET" class="text-center">Alamat Outlet</th>
                                                <th name="CAPTION-KETERANGAN" class="text-center">Keterangan</th>
                                                <th name="CAPTION-NOMINAL" class="text-center">Nominal</th>
                                                <th name="CAPTION-TOTAL" class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($PenerimaanBG) > 0) {
                                                foreach ($PenerimaanBG as $i => $value) : ?>
                                                    <tr>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['idx'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['groupurut'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['tgl'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><button type="button" id="btn_document_no<?= $i ?>" class="btn btn-link" onclick="ViewDetailDO('<?= $value['documentno']; ?>')"><?= $value['documentno']; ?></button></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['namaoutlet'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['alamatoutlet'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= $value['keterangan'] ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><?= round($value['nominal']) ?></td>
                                                        <td class="text-center" style="vertical-align: middle;"><input type="hidden" id="status_settlement_tunai_<?= $i ?>" value="<?= $value['nominalkumulatif'] ?>"><?= round($value['nominalkumulatif']) ?></td>
                                                    </tr>
                                            <?php endforeach;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <br><br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/DeliveryOrderForm/?delivery_order_batch_id=<?= $delivery_order_batch_id; ?>" class="btn btn-primary" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><label name="CAPTION-PROSESBARANGSELISIH">Proses Barang Selisih</label></a>
                    <span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                        <label name="CAPTION-LOADING">Loading</label>...</span>
                </div>
                <div class="pull-right">
                    <button type="button" id="btn_simpan_settlement" class="btn btn-success" style="<?= $header['delivery_order_batch_status'] == 'completed' ? 'display:none' : '' ?>"><i class="fa fa-save"></i> <label name="CAPTION-SIMPAN">Simpan</label></button>
                    <a href="<?php echo base_url() ?>WMS/SettlementPengiriman/SettlementPengirimanMenu" class="btn btn-danger"><label name="CAPTION-KEMBALI">Kembali</label></a>
                    <!-- <button type="button" id="btn_back_settlement" class="btn btn-danger">Kembali</button> -->
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<div class="modal fade" id="previewdetaildo" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label>Detail DO</label></h4>
            </div>
            <div class="modal-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <div class="clearfix"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_kode">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_kode" name="CAPTION-NODO">DO No</label>
                                        <div id="delivery_order_kode"></div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label class="control-label" for="deliveryorderdraft-delivery_order_status" name="CAPTION-TIPE">Tipe</label>
                                    <div id="tipe_delivery_order_nama"></div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_status">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_status" name="CAPTION-STATUS">Status</label>
                                        <div id="delivery_order_status"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_buat_do">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_buat_do" name="CAPTION-TANGGALENTRYDO">Tanggal Entry DO</label>
                                        <div id="delivery_order_tgl_buat_do"></div>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_expired_do">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_expired_do" name="CAPTION-TANGGALEXPIRED">Tanggal Expired</label>
                                        <div id="delivery_order_tgl_expired_do"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_surat_jalan">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_surat_jalan" name="CAPTION-TANGGALSURATJALAN">Tanggal Surat Jalan</label>
                                        <div id="delivery_order_tgl_surat_jalan"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-3">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tgl_rencana_kirim">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_tgl_rencana_kirim" name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                        <div id="delivery_order_tgl_rencana_kirim"></div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-client_wms_id">
                                        <label for="" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                        <div id="client_wms_nama"></div>
                                    </div>
                                    <div class=" form-group field-deliveryorderdraft-client_wms_alamat">
                                        <label class="control-label" for="deliveryorderdraft-client_wms_alamat" name="CAPTION-ALAMATPERUSAHAAN">Alamat Perusahaan</label>
                                        <div id="client_wms_alamat"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_keterangan">
                                        <label class="control-label" for="deliveryorderdraft-delivery_order_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                        <div id="delivery_order_keterangan"></div>
                                    </div>
                                </div>
                            </div>
                            <div class=" row">
                                <div class="col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tipe_pembayaran">
                                        <label for="deliveryorderdraft-delivery_order_tipe_pembayaran" class="control-label" name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                        <div id="delivery_order_tipe_pembayaran"></div>
                                    </div>
                                </div>
                                <div class=" col-xs-6">
                                    <div class="form-group field-deliveryorderdraft-delivery_order_tipe_layanan">
                                        <label for="deliveryorderdraft-delivery_order_tipe_layanan" class="control-label" name="CAPTION-TIPEPELAYANAN">Tipe Pelayanan</label>
                                        <div id="delivery_order_tipe_layanan"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div <?= empty($deliveryOrderDraft['client_pt_id']) ? "style='display: none'" : "" ?> class=" row" id="panel-customer">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-CUSTOMER">Customer</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="customer-info">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTNAME">Nama</label>
                                        <div id="delivery_order_kirim_nama"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-ALAMAT">Alamat</label>
                                        <div id="delivery_order_kirim_alamat"></div>
                                    </div>
                                    <div class=" col-xs-4">
                                        <label name="CAPTION-CUSTAREA">Area</label>
                                        <div id="delivery_order_kirim_area"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="panel-factory">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-PABRIK">Pabrik</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="factory-info">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTNAME">Nama</label>
                                        <div id="delivery_order_ambil_nama"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-ALAMAT">Alamat</label>
                                        <div id="delivery_order_ambil_alamat"></div>
                                    </div>
                                    <div class="col-xs-4">
                                        <label name="CAPTION-CUSTAREA">Area</label>
                                        <div id="delivery_order_ambil_area"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" row" id="panel-sku">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h4 class="pull-left" name="CAPTION-BARANGYANGDIKIRIM">Barang Yang Dikirim</h4>
                                <div class="clearfix"></div>
                            </div>
                            <table class="table table-bordered table-striped" id="tabledodetail">
                                <thead>
                                    <tr>
                                        <th name="CAPTION-SKUKODE">Kode SKU</th>
                                        <th name="CAPTION-KODESKUPABRIK">Kode SKU Pabrik</th>
                                        <th name="CAPTION-SKU">SKU</th>
                                        <th name="CAPTION-SKUKEMASAN">Kemasan</th>
                                        <th name="CAPTION-SATUAN">Satuan</th>
                                        <th name="CAPTION-REQEXPDATEE">Req Exp Date?</th>
                                        <th name="CAPTION-KETERANGAN">Keterangan</th>
                                        <th name="CAPTION-QTYREQ">Qty Req</th>
                                        <th name="CAPTION-QTYKIRIM">Qty Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingbkb" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-danger" id="btnbackdo" data-dismiss="modal"><label name="CAPTION-KEMBALI">Kembali</label></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="previewdetailbkb" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable" style="width: 90%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-DETAILBKB">Detail BKB</label></h4>
            </div>
            <div class="modal-body form-horizontal form-label-left">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="col-4 col-sm-2">
                                <label name="CAPTION-NOBKB">NO BKB</label>
                            </div>
                            <div class="col-8 col-sm-10">
                                <input type="text" id="picking_order_aktual_kode" class="form-control" value="" readonly />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-4 col-sm-2">
                                <label name="CAPTION-NOPPB">NO PPB</label>
                            </div>
                            <div class="col-8 col-sm-10">
                                <input type="text" id="picking_order_kode" class="form-control" value="" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="col-4 col-sm-2">
                                <label name="CAPTION-CHECKER">Checker</label>
                            </div>
                            <div class="col-8 col-sm-10">
                                <input type="text" id="karyawan_nama" class="form-control" value="" readonly />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <table id="tabledetailbkb" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th name="CAPTION-NODO">No DO</th>
                                <th name="CAPTION-KODESKU">Kode SKU</th>
                                <th name="CAPTION-NAMABARANG">Nama Barang</th>
                                <th name="CAPTION-KEMASAN">Kemasan</th>
                                <th name="CAPTION-SATUAN">Satuan</th>
                                <th name="CAPTION-JUMLAHBARANG">Jumlah Barang</th>
                                <th name="CAPTION-PERMINTAANEDBARANG">Permintaan ED Barang</th>
                                <th name="CAPTION-ACTUALQTYAMBIL">Aktual Qty Ambil</th>
                                <th name="CAPTION-EXPIREDDATE">Expired Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingbkb" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <!-- <button type="button" class="btn btn-success" id="btnsaveupdatebkbstandar">Simpan</button> -->
                <button type="button" class="btn btn-danger" id="btnbackbkb" data-dismiss="modal"><label name="CAPTION-KEMBALI">Kembali</label></button>
            </div>
        </div>
    </div>
</div>

<span name="CAPTION-ALERT-SETTLEMENTBARANGTIDAKCOCOK" style="display: none;">SETTLEMENT BARANG TIDAK COCOK</span>
<span name="CAPTION-ALERT-SETTLEMENTTUNAITIDAKCOCOK" style="display: none;">SETTLEMENT TUNAI TIDAK COCOK</span>