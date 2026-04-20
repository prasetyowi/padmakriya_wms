<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-DETAILBATCHSURATTUGASPENGIRIMAN">Detail Batch Surat Tugas Pengiriman</h3>
            </div>
            <div style="float: right">

            </div>
        </div>
        <?php foreach ($FDJR as $header) : ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="clearfix"></div>
                        </div>
                        <input type="hidden" id="deliveryorderbatch-delivery_order_batch_id" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_id]" autocomplete="off" value="<?= $header['delivery_order_batch_id'] ?>">
                        <input type="hidden" id="deliveryorderbatch-delivery_order_batch_status" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_status]" autocomplete="off" value="<?= $header['delivery_order_batch_status'] ?>">
                        <input type="hidden" id="deliveryorderbatch-depo_id" class="form-control" name="DeliveryOrderBatch[depo_id]" autocomplete="off" value="<?= $header['depo_id'] ?>">
                        <input type="hidden" id="deliveryorderbatch-unit_mandiri_id" class="form-control" name="DeliveryOrderBatch[unit_mandiri_id]" autocomplete="off" value="<?= $header['unit_mandiri_id'] ?>">
                        <input type="hidden" id="area_id" class="form-control" name="area_id" autocomplete="off" data-area="<?= $FdjrArea ?>">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal">
                                    <label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal" name="CAPTION-TANGGALBATCH">Tanggal Batch</label>
                                    <input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_tanggal" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_tanggal]" autocomplete="off" value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal'])) ?>">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-delivery_order_batch_kode">
                                    <label class="control-label" for="deliveryorderbatch-delivery_order_batch_kode" name="CAPTION-NOBATCH">No Batch</label>
                                    <input readonly="readonly" type="text" id="deliveryorderbatch-delivery_order_batch_kode" class="form-control" name="DeliveryOrderBatch[delivery_order_batch_kode]" autocomplete="off" value="<?= $header['delivery_order_batch_kode'] ?>">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
                                    <label class="control-label" for="deliveryorderbatch-delivery_order_batch_tipe_layanan_id" name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                                    <select disabled name="DeliveryOrderBatch[delivery_order_batch_tipe_layanan_id]" class="form-control" id="deliveryorderbatch-delivery_order_batch_tipe_layanan_id">
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($TipePelayanan as $row) : ?>
                                            <option value="<?= $row['tipe_layanan_id'] ?> || <?= $row['tipe_layanan_kode'] ?> || <?= $row['tipe_layanan_nama'] ?>" <?= $header['delivery_order_batch_tipe_layanan_id'] == $row['tipe_layanan_id'] ? 'selected' : '' ?>>
                                                <?= $row['tipe_layanan_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-tipe_delivery_order_id">
                                    <label class="control-label" for="deliveryorderbatch-tipe_delivery_order_id" name="CAPTION-TIPE">Tipe</label>
                                    <select disabled name="DeliveryOrderBatch[tipe_delivery_order_id]" class="form-control" id="deliveryorderbatch-tipe_delivery_order_id">
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($TipeDeliveryOrder as $row) : ?>
                                            <option value="<?= $row['tipe_delivery_order_id'] ?>" <?= $header['tipe_delivery_order_id'] == $row['tipe_delivery_order_id'] ? 'selected' : '' ?>>
                                                <?= $row['tipe_delivery_order_alias'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-delivery_order_batch_tanggal_kirim">
                                    <label class="control-label" for="deliveryorderbatch-delivery_order_batch_tanggal_kirim" name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                                    <input type="text" id="deliveryorderbatch-delivery_order_batch_tanggal_kirim" class="form-control datepicker" name="DeliveryOrderBatch[delivery_order_batch_tanggal_kirim]" autocomplete="off" value="<?= date('d-m-Y', strtotime($header['delivery_order_batch_tanggal_kirim'])) ?>">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-area_id">
                                    <label class="control-label" for="deliveryorderbatch-area_id" name="CAPTION-AREA">Area</label>
                                    <select disabled name="DeliveryOrderBatch[area_id]" class="form-control select2" id="deliveryorderbatch-area_id" multiple>
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($Area as $row) : ?>
                                            <?php if (in_array($row['area_id'], explode(',', $FdjrArea))) { ?>
                                                <option value="<?= $row['area_id'] ?>" selected><?= $row['area_nama'] ?></option>
                                            <?php } ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group field-deliveryorderbatch-tipe_ekspedisi_id">
                                    <label class="control-label" for="deliveryorderbatch-tipe_ekspedisi_id" name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi</label>
                                    <select disabled name="DeliveryOrderBatch[tipe_ekspedisi_id]" class="form-control" id="deliveryorderbatch-tipe_ekspedisi_id">
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($TipeEkspedisi as $row) : ?>
                                            <option value="<?= $row['tipe_ekspedisi_id'] ?>" <?= $header['tipe_ekspedisi_id'] == $row['tipe_ekspedisi_id'] ? 'selected' : '' ?>>
                                                <?= $row['tipe_ekspedisi_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="row" id="panel-armada">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php foreach ($FDJR as $header) : ?>
                    <div class="x_panel">
                        <div class="x_title">
                            <h4 class="pull-left" name="CAPTION-PILIHARMADA">Pilih Armada</h4>
                            <div class="clearfix"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group field-deliveryorderbatch-kendaraan_id">
                                    <label class="control-label" for="deliveryorderbatch-kendaraan_id" name="CAPTION-KENDARAAN">Kendaraan</label>
                                    <select disabled name="DeliveryOrderBatch[kendaraan_id]" class="form-control select2" id="deliveryorderbatch-kendaraan_id" onchange="GetKendaraan(this.value)">
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($Kendaraan as $row) : ?>
                                            <option value="<?= $row['kendaraan_id'] ?>" <?= $header['kendaraan_id'] == $row['kendaraan_id'] ? 'selected' : '' ?>>
                                                <?= $row['kendaraan_nopol'] ?> - <?= $row['kendaraan_model'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group field-deliveryorderbatch-karyawan_id">
                                    <label class="control-label" for="deliveryorderbatch-karyawan_id" name="CAPTION-DRIVER">Driver</label>
                                    <select disabled name="DeliveryOrderBatch[karyawan_id]" class="form-control select2" id="deliveryorderbatch-karyawan_id">
                                        <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
                                        <?php foreach ($Driver as $row) : ?>
                                            <option value="<?= $row['karyawan_id'] ?>" <?= $header['karyawan_id'] == $row['karyawan_id'] ? 'selected' : '' ?>>
                                                <?= $row['karyawan_nama'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped" id="table-armada-summary">
                            <thead>
                                <tr>
                                    <th name="CAPTION-PROFILMUATAN">Profil Muatan</th>
                                    <th class="weight-label" name="CAPTION-BERATGRAM">Berat (gram)</th>
                                    <th class="volume-label"><label name="CAPTION-VOLUME">Volume</label> (cm<sup>3</sup>)
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="max-capacity-label" name="CAPTION-KAPASITASMAKSIMAL">Kapasitas Maksimal</td>
                                    <td>
                                        <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_max]" id="deliveryorderbatch-kendaraan_berat_gr_max" value="<?= $header['beratMax'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_max]" id="deliveryorderbatch-kendaraan_volume_cm3_max" value="<?= $header['volumeMax'] ?>">
                                        <input type="hidden" name="DeliveryOrderBatch[kendaraan_km_awal]" id="deliveryorderbatch-kendaraan_km_awal" value="<?= $header['kendaraan_km_awal'] ?>">
                                        <input type="hidden" name="DeliveryOrderBatch[kendaraan_km_akhir]" id="deliveryorderbatch-kendaraan_km_akhir" value="<?= $header['kendaraan_km_akhir'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td name="CAPTION-KAPASITASTERPAKAI">Kapasitas Terpakai</td>
                                    <td>
                                        <?php if ($header['beratTerpakai'] > $header['beratMax']) { ?>
                                            <input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>">
                                        <?php } else { ?>
                                            <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-kendaraan_berat_gr_terpakai" value="<?= $header['beratTerpakai'] ?>">
                                        <?php } ?>
                                        <input type="hidden" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_berat_gr_sisa]" id="deliveryorderbatch-kendaraan_berat_gr_sisa" value="<?= $header['beratMax'] ?>">
                                    </td>
                                    <td>
                                        <?php if ($header['volumeTerpakai'] > $header['volumeMax']) { ?>
                                            <input style="background-color: #FF9999; color:black" type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>">
                                        <?php } else { ?>
                                            <input type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-kendaraan_volume_cm3_terpakai" value="<?= $header['volumeTerpakai'] ?>">
                                        <?php } ?>
                                        <input type="hidden" readonly="readonly" class="form-control" name="DeliveryOrderBatch[kendaraan_volume_cm3_sisa]" id="deliveryorderbatch-kendaraan_volume_cm3_sisa" value="<?= $header['volumeMax'] ?>">
                                    </td>
                                </tr>
                                <?php
                                $presentaseBeratTerpakai = number_format(intval($header['beratTerpakai']) / intval($header['beratMax']) * 100, 2, '.', '');
                                $presentaseVolumeTerpakai = number_format(intval($header['volumeTerpakai']) / intval($header['volumeMax']) * 100, 2, '.', '');

                                ?>
                                <tr>
                                    <td name="CAPTION-PRESENTASEKAPASITASTERPAKAI">Presentase Kapasitas Terpakai</td>
                                    <td>
                                        <input <?= intval($presentaseBeratTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_berat_gr_terpakai]" id="deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai" value="<?= $presentaseBeratTerpakai ?>%">
                                    </td>
                                    <td>
                                        <input <?= intval($presentaseVolumeTerpakai) > 100 ? 'style="background-color: #FF9999; color:black"' : '' ?> type="text" readonly="readonly" class="form-control" name="DeliveryOrderBatch[presentase_kendaraan_volume_cm3_terpakai]" id="deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai" value="<?= $presentaseVolumeTerpakai ?>%">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="max-capacity-label" name="CAPTION-SELISIHKAPASITAS">Selisih Kapasitas</td>
                                    <td>
                                        <input type="text" readonly="readonly" class="form-control" name="selisihKapasitasBerat" id="selisihKapasitasBerat" value="<?= $header['beratMax'] - $header['beratTerpakai'] ?>">
                                    </td>
                                    <td>
                                        <input type="text" readonly="readonly" class="form-control" name="selisihKapasitasVolume" id="selisihKapasitasVolume" value="<?= $header['volumeMax'] - $header['volumeTerpakai'] ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <input type="hidden" id="cek_confirm" class="form-control" name="cek_confirm" autocomplete="off" value="cek_confirm">
        <div class="row" id="panel-dodraft">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas Pengiriman</h4>
                        <?php
                        if ($UseMap[0]['vrbl_kode'] == 'TRUE') {
                        ?>
                            <div class="pull-right">
                                <button class="btn btn-primary" type="button" id="btn-check-muatan" onclick="LihatPeta()"><i class="fa-solid fa-globe"></i> <label name="CAPTION-LIHATPETA">Lihat Peta</label>
                                </button>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <em><label name="CAPTION-DRAGANDDROPDOUNTUKMENGUBAHPRIORITAS">Drag and drop DO untuk
                                    mengubah
                                    prioritas</label></em>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped" id="table-do-draft">
                        <tbody>
                            <tr>
                                <th class="text-center" name="CAPTION-PRIORITAS">Prioritas</th>
                                <th class="text-center" name="CAPTION-NAMA">Nama</th>
                                <th class="text-center"><label name="CAPTION-ALAMAT">Alamat</label></th>
                                <th class="text-center"><label name="CAPTION-TELP">Telp</label></th>
                                <th class="text-center"><label name="CAPTION-JUMLAHDO">Jumlah DO</label></th>
                                <th class="text-center" hidden>No. SO Eksternal</th>
                                <th class="text-center" hidden>Principle</th>
                                <th class="text-center">Kecamatan</th>
                                <th class="text-center">Area</th>
                                <th class="text-center"><label name="CAPTION-LATITUDE">Latitude</label></th>
                                <th class="text-center"><label name="CAPTION-LONGITUDE">Longitude</label></th>
                                <th class="text-center"><label name="CAPTION-BERATGRAM">Berat(gram)</label></th>
                                <th class="text-center"><label name="CAPTION-VOLUMECM">Volume(cm<sup>3</sup>)</label>
                                <th class="text-center" hidden>Kirim Ulang</th>
                                <th class="text-center">Composite</th>
                                <!-- <th class="text-center"><label>Order</label> -->
                                <th class="text-center" name="CAPTION-ACTION">Action</th>
                            </tr>
                            <?php if (!empty($DO)) {
                                $no2 = 1;
                                $totalBeratDO = 0;
                                $totalVolumeDO = 0; ?>
                                <?php foreach ($DO as $i => $detail) :
                                    $totalBeratDO = $totalBeratDO + intval($detail['sku_weight']);
                                    $totalVolumeDO = $totalVolumeDO + intval($detail['sku_volume']);
                                    if ($totalBeratDO > intval($rowFDJR['kendaraan_berat_gr_max']) || $totalVolumeDO > intval($rowFDJR['kendaraan_volume_cm3_max'])) { ?>
                                        <tr id="row-<?= $i ?>" class="ui-sortable-handle" style="background-color: #FF9999; color: black">
                                            <td class="text-center">
                                                <span id="urutan-prioritas-<?= $i ?>"><?= $detail['delivery_order_no_urut_rute'] ?></span>
                                            </td>
                                            <td style="display: none">
                                                <?= $detail['delivery_order_kirim_alamat'] ?>
                                            </td>
                                            <input type="hidden" class="latitudelongitude" data-nama="<?= $detail['delivery_order_kirim_nama'] ?>" data-alamat="<?= $detail['delivery_order_kirim_alamat'] ?>" data-lat="<?= $detail['delivery_order_kirim_latitude'] ?>" data-long="<?= $detail['delivery_order_kirim_longitude'] ?>" data-prioritas="<?= $no2++ ?>" />
                                            <td class="text-center"><?= $detail['delivery_order_kirim_nama'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_alamat'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_telp'] ?></td>
                                            <td class="text-center"><?= $detail['jumlah_do'] ?></td>
                                            <td class="text-center" hidden><?= $detail['sales_order_no_po'] ?></td>
                                            <td class="text-center" hidden><?= $detail['principle_kode'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_kecamatan'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_area'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_latitude'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_longitude'] ?></td>
                                            <td class="text-center"><?= intval($detail['sku_weight']) ?></td>
                                            <td class="text-center"><?= intval($detail['sku_volume']) ?></td>
                                            <td class="text-center" hidden><?= $detail['kirim_ulang'] == null ? '' : 'Kirim Ulang' ?></td>
                                            <td class="text-center"><?= $detail['composite'] ?></td>
                                            <!-- <td class="text-center"><?= formatReplaceArray($detail['product']) ?></td> -->
                                            <td class="text-center"><button class="btn btn-success btn-sm btn-view-detail-do" onclick="OnlyViewDetailDO('<?= $detail['delivery_order_batch_id'] ?>','<?= $detail['delivery_order_kirim_nama'] ?>','<?= $detail['delivery_order_kirim_alamat'] ?>')"><i class="fa fa-eye"></i></button></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr id="row-<?= $i ?>" class="ui-sortable-handle">
                                            <td class="text-center">
                                                <span id="urutan-prioritas-<?= $i ?>"><?= $detail['delivery_order_no_urut_rute'] ?></span>
                                            </td>
                                            <td style="display: none">
                                                <?= $detail['delivery_order_kirim_alamat'] ?>
                                            </td>
                                            <input type="hidden" class="latitudelongitude" data-nama="<?= $detail['delivery_order_kirim_nama'] ?>" data-alamat="<?= $detail['delivery_order_kirim_alamat'] ?>" data-lat="<?= $detail['delivery_order_kirim_latitude'] ?>" data-long="<?= $detail['delivery_order_kirim_longitude'] ?>" data-prioritas="<?= $no2++ ?>" />
                                            <td class="text-center"><?= $detail['delivery_order_kirim_nama'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_alamat'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_telp'] ?></td>
                                            <td class="text-center"><?= $detail['jumlah_do'] ?></td>
                                            <td class="text-center" hidden><?= $detail['sales_order_no_po'] ?></td>
                                            <td class="text-center" hidden><?= $detail['principle_kode'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_kecamatan'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_area'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_latitude'] ?></td>
                                            <td class="text-center"><?= $detail['delivery_order_kirim_longitude'] ?></td>
                                            <td class="text-center"><?= intval($detail['sku_weight']) ?></td>
                                            <td class="text-center"><?= intval($detail['sku_volume']) ?></td>
                                            <td class="text-center" hidden><?= $detail['kirim_ulang'] == null ? '' : 'Kirim Ulang' ?></td>
                                            <td class="text-center"><?= $detail['composite'] ?></td>
                                            <!-- <td class="text-center"><?= formatReplaceArray($detail['product']) ?></td> -->
                                            <td class="text-center"><button class="btn btn-success btn-sm btn-view-detail-do" onclick="OnlyViewDetailDO('<?= $detail['delivery_order_batch_id'] ?>','<?= $detail['delivery_order_kirim_nama'] ?>','<?= $detail['delivery_order_kirim_alamat'] ?>')"><i class="fa fa-eye"></i></button></td>
                                        </tr>
                                <?php }
                                endforeach; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" id="panel-maps" style="display: none;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left"><strong><label name="CAPTION-KODEAREAKIRIM">Kode Area Kirim</label></strong></h4>

                        <div class="pull-right"><button id="close-panel-maps" onclick="handlerClosePanelMaps()" class="btn btn-dark" type="button"><label name="CAPTION-TUTUPMAPS">Tutup Maps</label></button></div>
                        <div class="clearfix"></div>
                    </div>

                    <!-- <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 col-xl-5"></div> -->

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 20px;"></div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 0;" id="init-maps-container">
                        <!-- <div id="datamapdobatch" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 70vh;"></div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="panel-sku">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4 class="pull-left" name="CAPTION-DAFTARSKUSUMMARY">Daftar SKU Summary</h4>
                        <div class="clearfix"></div>
                    </div>
                    <!-- <div class="row">
						<div class="col-xs-1">
							Lokasi Gudang:
						</div>
						<div class="col-xs-4">
							<select disabled name="DeliveryOrderBatch[depo_detail_id]" id="deliveryorderbatch-depo_detail_id" class="form-control">
								<option value="">**Pilih**</option>
								<?php foreach ($Gudang as $row) : ?>
									<option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-xs-7">
							<button type="button" class="btn btn-info btn-small btn-check-stock">Cek Ketersediaan Stok</button>
						</div>
					</div> -->
                    <!-- <br /> -->

                    <button type="button" class="btn btn-secondary" style="font-weight: bold;" id="totalComposite">
                        <?= $totalComposite ?>
                    </button>

                    <table class="table table-bordered table-striped" id="table-summary-sku">
                        <thead>
                            <tr>
                                <th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>
                                <th class="text-center" name="CAPTION-KODESKU">Kode SKU</th>
                                <!-- <th>Kode SKU Pabrik</th> -->
                                <th class="text-center" name="CAPTION-NAMASKU">Nama SKU</th>
                                <th class="text-center" name="CAPTION-KEMASAN">Kemasan</th>
                                <th class="text-center" name="CAPTION-SATUAN">Satuan</th>
                                <th class="text-center" name="CAPTION-QTYREQ">Qty Req</th>
                                <th class="text-center" name="CAPTION-TIPESTOCK">Tipe Stock</th>
                                <th class="text-center" name="CAPTION-REQEXPDATE">Req Exp Date?</th>
                                <th class="text-center" name="CAPTION-REQEXPFILTER">Req Exp Filter</th>
                                <th class="text-center" name="CAPTION-REQEXPFILTERBULAN">Req Exp Filter Bulan</th>
                                <!-- <th>Qty Available</th> -->
                                <th name="CAPTION-ACTION">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($DO_SKU != 0) { ?>
                                <?php foreach ($DO_SKU as $i => $sku) : ?>
                                    <tr id="row-<?= $i ?>" class="ui-sortable-handle">
                                        <td class="text-center"><?= $sku['principle_kode'] ?></td>
                                        <td class="text-center">
                                            <?= $sku['sku_kode'] ?>
                                            <input type="hidden" id="item-<?= $i ?>-DeliveryOrderDetailDraft-sku_id" value="<?= $i ?>" />
                                        </td>
                                        <td class="text-center"><?= $sku['sku_nama_produk'] ?></td>
                                        <td class="text-center"><?= $sku['sku_kemasan'] ?></td>
                                        <td class="text-center"><?= $sku['sku_satuan'] ?></td>
                                        <td class="text-center"><?= $sku['sku_qty'] ?></td>
                                        <td class="text-center"><?= $sku['tipe_stock_nama'] ?></td>
                                        <td class="text-center"><?= $sku['sku_request_expdate'] ?></td>
                                        <td class="text-center"><?= $sku['sku_filter_expdate'] ?></td>
                                        <td class="text-center"><?= $sku['sku_filter_expdatebulan'] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKUDO('<?= $sku['sku_id'] ?>', '<?= $sku['sku_kode'] ?>', '<?= $sku['sku_nama_produk'] ?>')"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div style="float: right">
                    <a href="<?= base_url('WMS/Distribusi/DeliveryOrderBatch/index') ?>" class="btn btn-info"><i class="fa fa-reply"></i> <label name="CAPTION-KEMBALI">Kembali</label></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-dodraft" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-CARIDRAFTSURATTUGASPENGIRIMAN">Cari Draft Surat Tugas
                        Pengiriman</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-3">
                        <label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                        <div class="label-service-type"></div>
                    </div>
                    <div class="col-xs-3">
                        <label name="CAPTION-TIPE">Tipe</label>
                        <div class="label-do-type"></div>
                    </div>
                    <div class="col-xs-3">
                        <label name="CAPTION-AREA">Area</label>
                        <div class="label-area"></div>
                    </div>
                    <div class="col-xs-3">
                        <label name="CAPTION-TANGGALKIRIM">Tanggal Kirim</label>
                        <div class="label-delivery-date"></div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table id="data-table-dodraft" width="100%" class="table table-striped">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center" style="color:white;"><input type="checkbox" name="select-sku" id="select-dodraft" value="1"></th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TANGGALENTRYDO">Tanggal
                                        Entry DO</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-NODO">No DO</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-CUSTOMER">Customer</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-ALAMATKIRIM">Alamat Kirim
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPEPEMBAYARAN">Tipe
                                        Pembayaran</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPELAYANAN">Tipe Layanan
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-STATUS">Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-info btn-choose-do-draft-multi"><label name="CAPTION-PILIH">Pilih</label></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger"><label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-do-draft-sku" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas
                        Pengiriman</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-2">
                                <label name="CAPTION-KODESKU">Kode SKU</label>
                            </div>
                            <div class="col-xs-1">
                                <label>:</label>
                            </div>
                            <div class="col-xs-9">
                                <label id="kode-sku"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <label name="CAPTION-SKU">SKU</label>
                            </div>
                            <div class="col-xs-1">
                                <label>:</label>
                            </div>
                            <div class="col-xs-9">
                                <label id="sku-nama-produk"></label>
                            </div>
                        </div>
                    </div>
                </div><br><br>

                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-summary-sku-detail">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center" style="color:white;" name="CAPTION-PRIORITAS">Prioritas</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TANGGALDO">Tanggal DO
                                    </th>
                                    <th class="text-center" style="color:white;"><label name="CAPTION-NODODRAFT">No. DO
                                            Draft</label>
                                        <hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No. DO</label>
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-NAMA">Nama</th>
                                    <th class="text-center" style="color:white;"><label name="CAPTION-ALAMAT">Alamat</label>
                                        <hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-TELP">Telp</label>
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPEPEMBAYARAN">Tipe
                                        Pembayaran</th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPELAYANAN">Tipe Layanan
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe</th>






                                    <th class="text-center" style="color:white;" name="CAPTION-QTY">Qty</th>





                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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

<div class="modal fade" id="modal-do-by-alamat" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-SURATTUGASPENGIRIMAN">Surat Tugas
                        Pengiriman</label></h4>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-2">
                                <label name="CAPTION-KODESKU">Nama</label>
                            </div>
                            <div class="col-xs-1">
                                <label>:</label>
                            </div>
                            <div class="col-xs-9">
                                <label id="nama_do"></label>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <label name="CAPTION-SKU">Alamat</label>
                            </div>
                            <div class="col-xs-1">
                                <label>:</label>
                            </div>
                            <div class="col-xs-9">
                                <label id="alamat_do"></label>
                            </div>
                        </div>
                    </div>
                </div><br><br>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-striped" id="table-do-by-alamat">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center" style="color:white;" name="CAPTION-TANGGALDO">Tanggal DO
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TANGGALKIRIM">Tanggal Kirim
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-PRINCIPLE">Principle
                                    </th>
                                    <th class="text-center" style="color:white;"><label name="CAPTION-NODODRAFT">No. DO
                                            Draft</label>
                                        <hr style="margin-top: 5px; margin-bottom: 5px;" /><label name="CAPTION-NODO">No. DO</label>
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-TIPE">Tipe
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-KIRIMULANG">Kirim Ulang
                                    </th>
                                    <th class="text-center" style="color:white;" name="CAPTION-QTY">Qty
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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