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
                <h3 name="CAPTION-135011000">Serah Terima Barang</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <!-- <div class="col-md-12">
				<a href="<?php echo site_url('WMS/PickList/PickingProgressForm') ?>" class="btn btn-primary">Picking
					Progress Form</a>
				<a href="<?php echo site_url('WMS/PickList/DikemasOrder') ?>" class="btn btn-primary">Picking Order</a>
			</div> -->
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h5 name="CAPTION-SEARCHFORPENGIRIMANBARANG">Search For Handover of Items</h5>
                    </div>
                    <div class="x_content">
                        <form id="form-filter-do" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="item form-group">
                                        <label class="col-form-label col-md-2 col-sm-2 label-align"
                                            name="CAPTION-TANGGALKIRIM">Tanggal Kirim
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" id="filter_tglkirim_date" class="form-control"
                                                name="filter_tglkirim_date" value="" />
                                        </div>
                                    </div>

                                    <!-- <div class="item form-group">
                                        <label class="col-form-label col-md-6 col-sm-6 label-align"
                                            name="CAPTION-TANGGALAKHIR">No PPB
                                        </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="picking_order_id"
                                                id="picking_order_id">
                                            <select class="form-control select2"
                                                name="pick_id" id="pick_id" required>
												<option>-- PILIH NO PERMINTAAN BARANG --</option>
												<?php foreach ($listPicking as $item) : ?>
                                                	<option value="<?php echo $item['picking_list_id'] ?>">
                                                    <?php echo $item['picking_list_kode'] ?></option>
                                                <?php endforeach; ?>
                                                
                                            </select>
                                        </div>
                                    </div> -->
                                </div>


                            </div>

                            <div class="item form-group">
                                <div class="col-md-12 col-sm-12 text-left">
                                    <a class="btn btn-md btn-primary btn-submit-filter" style="width: 5%;"
                                        onclick="getDataPengirimanSearch()"><label name="CAPTION-CARI">Cari</label></a>
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
                        <h5 name="CAPTION-DAFTARPENGIRIMANBARANG">Daftar Pengiriman Barang</h5>
                        <a class="btn btn-md btn-primary"
                            href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangForm') ?>">
                            <label name="CAPTION-ADDPENGIRIMANBARANG">ADD Pengiriman Barang</label> </a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <table id="tablePengiriman" width="100%" class="table table-responsive">
                            <thead>
                                <tr>
                                    <th name="CAPTION-NO">No</th>
                                    <th name="CAPTION-TGLSERAHTERIMA">Tanggal Serah Terima</th>
                                    <th name="CAPTION-TANGGALKIRIM">Tanggal Kirim</th>
                                    <th name="CAPTION-NOPENGIRIMANBARANG">No Pengiriman Barang</th>
                                    <th name="CAPTION-NOPENGELUARANBARANG">No Pengeluaran Barang</th>
                                    <th>No FDJR</th>
                                    <th name="CAPTION-DRIVER">Pengemudi</th>
                                    <th>Status</th>
                                    <th name="CAPTION-KETERANGAN">Keterangan</th>
                                    <th>Action</th>
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

<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<!-- modal view detail-->
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

                    <table id="tableDetail" style="width:100%" class="table table-hover  table-primary table-bordered ">
                        <thead>
                            <tr>
                                <th style="text-align: center;" name="CAPTION-NOPERMINTAANBARANG">No Permintaan Barang
                                </th>
                                <th style="text-align: center;" name="CAPTION-NODO">No DO</th>
                                <th style="text-align: center;" name="CAPTION-KODESKU">Kode SKU</th>
                                <th style="text-align: center;" name="CAPTION-SKUNAMA">Nama SKU</th>
                                <th style="text-align: center;" name="CAPTION-KEMASAN">Kemasan</th>
                                <th style="text-align: center;" name="CAPTION-SATUAN">Satuan</th>
                                <th style="text-align: center;">QTY</th>
                                <th style="text-align: center;">E.D</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <a href="#" class="btn btn-danger" data-dismiss="modal" class="btn"><label name="CAPTION-TUTUP"></label></a>
        </div>
    </div>
</div>