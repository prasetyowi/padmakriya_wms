<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-128006000">Pembongkaran</h3>
            </div>
            <!-- <div style="float: right">
                <?php if ($Menu_Access["C"] == 1) : ?>
                    <a href="<?= base_url('WMS/PembongkaranBarang/create') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <label name="CAPTION-BARU">Baru</label></a>
                <?php endif; ?>
            </div> -->
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-TANGGAL">Tanggal</label>
                                <input type="text" id="filter-konversi-date" class="form-control"
                                    name="filter_konversi_date" value="" />
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-PERUSAHAAN">Perusahaan</label>
                                <select id="filter-perusahaan" class="form-control select2" name="filter_perusahaan">
                                    <option value="">** <span name="CAPTION-PERUSAHAAN">Perusahaan</span> **</option>
                                    <?php foreach ($Perusahaan as $row) : ?>
                                    <option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-STATUS">Status</label>
                                <select id="filter-status" name="filter_status" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($status as $row) { ?>
                                    <option value="<?= $row['tr_konversi_sku_status'] ?>">
                                        <?= $row['tr_konversi_sku_status'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-TIPE">Tipe</label>
                                <select id="filter-konversi-type" name="filter_konversi_type" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($TipeKonversi as $row) : ?>
                                    <option value="<?= $row['tipe_konversi_id'] ?>"><?= $row['tipe_konversi_nama'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <span id="loadingviewkonversi" style="display:none;"><i
                                        class="fa fa-spinner fa-spin"></i> <label
                                        name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btn-search-data-konversi" class="btn btn-success"><i
                                        class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_data_konversi" width="100%"
                                    class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-TANGGAL">Tanggal</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-NOPERSETUJUANPEMBONGKARAN">No. Persetujuan
                                                        Pembongkaran</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-PERUSAHAAN">Perusahaan</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-TIPE">Tipe</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-STATUS">Status</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-ACTION">Action</label></strong></td>
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
    </div>
</div>