<style>
#overlay {
    position: fixed;
    top: 0;
    z-index: 100;
    width: 100%;
    height: 100%;
    display: none;
    background: rgba(0, 0, 0, 0.6);
}

.cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
}

@keyframes sp-anime {
    100% {
        transform: rotate(360deg);
    }
}

.is-hide {
    display: none;
}

#tbody-listSKU tr,
#tbody-listLokasiSKU tr {
    cursor: pointer;
}
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-PERMINTAANBARANGDETAIL">Permintaan Barang Detail</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5 name="CAPTION-PERMINTAANBARANGTAMBAH">Create Permintaan Barang</h5>
                    </div>
                    <div class="x_content">
                        <form id="form-filter-pick-form" action="<?php echo site_url('PickList/PickingProgressForm') ?>"
                            method="POST" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TANGGALPERMINTAANBARANG">Tanggal Permintaan Barang </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="date" class="form-control input-date-start date" name="tgl"
                                                id="tgl" placeholder="dd-mm-yyyy" required="required" readonly
                                                value="<?= $picking->tgl_picking; ?>">
                                        </div>

                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4  label-align"
                                            name="CAPTION-NOPERMINTAANBARANG">No Permintaan Barang
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="no_picking" readonly
                                                value="<?= $picking->picking_list_kode; ?>">

                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TIPE">Tipe
                                        </label>
                                        <div class="col-md- col-sm-6">
                                            <select class="form-control select2" name="tipe_picklist" id="tipe_picklist"
                                                required disabled>
                                                <option value="<?= $picking->tipe_delivery_order_id; ?>">
                                                    <?= $picking->tipe_delivery_order_alias; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="area"
                                            name="CAPTION-AREAKIRIM">Area Kirim

                                        </label>
                                        <div class="col-md- col-sm-6">
                                            <textarea id="area" class="form-control" disabled><?php foreach ($area as $data) {
																									echo $data['area_nama'] . ', ';
																								} ?></textarea>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align">No FDJR
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control select2" name="no_batch_do" id="no_batch_do"
                                                required disabled>
                                                <option value="<?= $picking->do_batch_kode; ?>">
                                                    <?= $picking->do_batch_kode; ?></option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TANGGALFDJR">Tgl FDJR
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tgl_fdjr" id="tgl_fdjr"
                                                required="required" value="<?= $picking->tgl_fdjr; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TIPELAYANAN">Tipe Layanan
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control tipe_layanan select2" name="tipe_layanan"
                                                id="tipe_layanan" required disabled>
                                                <option value="<?= $picking->nama_layanan; ?>">
                                                    <?= $picking->nama_layanan; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TIPEPENGIRIMAN">Tgl Pengiriman
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control " name="tgl_kirim" id="tgl_kirim"
                                                readonly value="<?= $picking->tgl_kirim; ?>">
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-KETERANGAN">Keterangan
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control " name="keterangan" id="keterangan"
                                                value="<?= $picking->picking_list_keterangan; ?>" disabled>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-lg-6">
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="depo">Depo

                                        </label>
                                        <div class="col-md- col-sm-6">
                                            <select class="form-control select2" name="depo" id="depo" disabled>

                                                <?php foreach ($depo as $dp) : ?>
                                                <option value="<?php echo $dp['depo_id'] ?>">
                                                    <?php echo $dp['depo_nama'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="item form-group">
										<label class="col-form-label col-md-4 col-sm-4 label-align" for="depo" name="CAPTION-GUDANGBARANG">Gudang

										</label>
										<div class="col-md- col-sm-6">
											<select class="form-control select2" name="depo_detail" id="depo_detail" disabled>
												<option value="<?= $picking->depo_detail_nama; ?>"><?= $picking->depo_detail_nama; ?></option>
											</select>
										</div>
									</div> -->
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="is_packing"
                                            name="CAPTION-PERLUDIKEMAS">Perlu dikemas

                                        </label>
                                        <div class="col-md- col-sm-6">
                                            <input type="text" class="form-control " name="is_packing" id="is_packing"
                                                readonly value="<?= $picking->packed ?>">

                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align" for="status">Status

                                        </label>
                                        <div class="col-md- col-sm-6">
                                            <input type="text" class="form-control " name="status" id="status"
                                                value="<?= $picking->picking_list_status ?>" readonly>


                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-4 col-sm-4 label-align"
                                            name="CAPTION-TIPEEKSPEDISI">Tipe Ekspedisi
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control " name="tipe_ekspedisi"
                                                id="tipe_ekspedisi" value="<?= $picking->tipe_ekspedisi_nama ?>"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-2 col-sm-2 label-align"
                                            name="CAPTION-ARMADA">Armada
                                        </label>
                                        <div class="col-md-4 col-sm-4">
                                            <input type="text" class="form-control " name="armada" id="armada"
                                                value="<?= $picking->armada ?>" readonly>
                                        </div>

                                        <label class="col-form-label col-md-2 col-sm-2 label-align"
                                            name="CAPTION-DRIVER">Driver
                                        </label>
                                        <div class="col-md-4 col-sm-4">
                                            <input type="text" class="form-control " name="driver" id="driver"
                                                value="<?= $picking->karyawan_nama ?>" readonly>
                                        </div>
                                    </div>
                                    <table id="tableMuatan" width="100%" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><label name="CAPTION-PROFILMUATAN">Profil Muatan</label> </th>
                                                <th><label name="CAPTION-BERATGRAM">Berat (gr)</label></th>
                                                <th>Volume (cm3)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" name="CAPTION-KAPASITASMAKSIMAL">Kapasitas
                                                    Maksimal</td>
                                                <td class="text-center"><input class="form-control" type="text"
                                                        id="weight_max" readonly
                                                        value="<?= $picking->kendaraan_berat_gr_max ?>"></td>
                                                <td class="text-center"><input class="form-control" type="text"
                                                        id="volume_max" readonly
                                                        value="<?= $picking->kendaraan_volume_cm3_max ?>"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" name="Kapasitas Tersedia">Kapasitas Tersedia
                                                </td>
                                                <td class="text-center"><input class="form-control" type="text"
                                                        id="weight_terpakai" readonly
                                                        value="<?= $picking->kendaraan_berat_gr_sisa ?>"></td>
                                                <td class="text-center"><input class="form-control" type="text"
                                                        id="volume_terpakai" readonly
                                                        value="<?= $picking->kendaraan_volume_cm3_sisa ?>"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ro-batch" id="do-table">

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5 name="CAPTION-LISTDO">List DO</h5>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <table id="tableDO" width="100%" class="table">
                            <thead>
                                <tr>
                                    <!-- <th>Action</th> -->
                                    <th name="CAPTION-NO">No</th>
                                    <th name="CAPTION-TGLDO">Tgl DO</th>
                                    <th name="CAPTION-NODO">No DO</th>
                                    <th name="CAPTION-TIPEDO">Tipe DO</th>
                                    <th>Outlet</th>
                                    <th name="CAPTION-ALAMATOUTLET">Alamat Outlet</th>
                                    <th name="CAPTION-NOTELEPON">No Telepon</th>
                                    <th name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</th>
                                    <th name="CAPTION-TIPELAYANAN">Tipe Layanan</th>
                                    <th name="CAPTION-NOURURTRUTE">No Urut Rute</th>
                                    <th name="CAPTION-PRIORITASSTOCK">Prioritas Stock</th>
                                    <th>Status</th>
                                    <th name="">View</th>
                                    <th name="">Checked</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="display: flex;align-items:center;justify-content:space-between">

                    <div class="text-right">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>


<!-- modal view DO detail-->
<div class="modal fade" id="viewDetail" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <div class="row">

                    <!-- <h5 class="modal-title"><label>Add SKU</label></h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
            </div>
            <!-- <div class="container"></div> -->
            <div class="modal-body">
                <div class="table-responsive">

                    <table id="tableDOD" style="width:100%" class="table table-hover  table-primary table-bordered ">
                        <thead>
                            <tr>
                                <th style="text-align: center;" name="CAPTION-NODO">No DO</th>
                                <th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
                                <th style="text-align: center;" name="CAPTION-KODESKUPRINCIPLE">Kode SKU Principle</th>
                                <th style="text-align: center;" name="CAPTION-SKUNAMA">Nama SKU</th>
                                <th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
                                <th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
                                <th style="text-align: center;" name="CAPTION-REQED">Req E.D</th>
                                <th style="text-align: center;">QTY</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-primary" data-dismiss="modal" onclick="addDOD()"><label
                    name="CAPTION-SAVE">Simpan</label></a>
            <a href="#" class="btn btn-danger" data-dismiss="modal" class="btn"><label
                    name="CAPTION-CLOSE">Tutup</label></a>
        </div>
    </div>
</div>

<!-- modal add sku_stock-->
<div class="modal fade" id="modalSKU">
    <div class="modal-dialog modal-lg" style="width:70%">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <div class="row">


                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
            </div>
            <!-- <div class="container"></div> -->
            <div class="modal-body">
                <h5 class="modal-title"><label>Detail SKU</label></h5>

            </div>

            <br>
            <div class="table-responsive">

                <table id="tableSKU" style="width:100%" class="table table-hover  table-primary table-bordered ">
                    <thead>
                        <tr>
                            <th style="text-align: center;width:10%" name="CAPTION-NODO">No DO</th>
                            <th style="text-align: center;width:10%" name="CAPTION-SKUINDUK">SKU Induk</th>
                            <th style="text-align: center;width:10%">SKU</th>
                            <th style="text-align: center;width:5%" name="CAPTION-KEMASAN">Kemasan</th>
                            <th style="text-align: center;width:5%" name="CAPTION-SATUAN">Satuan</th>
                            <th style="text-align: center;" name="CAPTION-PRINCIPLE">Principle</th>
                            <th style="text-align: center;" name="CAPTION-BRAND">Brand</th>
                            <th style="text-align: center;">E.D</th>
                            <th style="text-align: center;width:10%">Qty</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- <div class="modal-footer">
			<a href="#" class="btn btn-primary" data-dismiss="modal" onclick="addSkuED()">Save</a>
			<a href="#" class="btn btn-danger" data-dismiss="modal" class="btn">Close</a>
		</div> -->
</div>
</div>