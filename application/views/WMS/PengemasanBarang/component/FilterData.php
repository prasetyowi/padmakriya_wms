<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3><strong><label name="CAPTION-FILTERDATA">Filter Data</label> </strong></h3>
                <div class="clearfix"></div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="Filter Data">Tgl PB</label>
                            <input type="date" class="form-control input-date-start" name="tgl_create" id="tgl_create" placeholder="dd-mm-yyyy" required="required" value="<?php echo Date('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label name="CAPTION-NOPB">No. PB</label>
                            <select class="form-control select2" name="no_picking_list" id="no_picking_list" required>
                                <option value="">-- Pilih No PB --</option>
                                <?php foreach ($listNoPickingList as $NoPickingList) : ?>
                                    <option value="<?php echo $NoPickingList->picking_list_kode ?>">
                                        <?php echo $NoPickingList->picking_list_kode ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>No. FDJR</label>
                            <select class="form-control select2" name="no_batch_do" id="no_batch_do" required>
                                <option value="">-- Pilih No. FDJR --</option>
                                <?php foreach ($listDoBatch as $DoBatch) : ?>
                                    <option value="<?php echo $DoBatch['delivery_order_batch_kode'] ?>">
                                        <?php echo $DoBatch['delivery_order_batch_kode'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                            <select class="form-control select2" name="tipe_layanan" id="tipe_layanan" required>
                                <option value="">-- Pilih Tipe Layanan --</option>
                                <?php foreach ($listLayanan as $layanan) : ?>
                                    <option value="<?php echo $layanan['tipe_layanan_nama'] ?>">
                                        <?php echo $layanan['tipe_layanan_nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label name="CAPTION-TIPEPENGIRIMAN">Tipe Pengiriman</label>
                            <select class="form-control select2" name="tipe_pengiriman" id="tipe_pengiriman" required>
                                <option value="">-- Pilih Tipe Pengiriman --</option>
                                <?php foreach ($listPengiriman as $pengirman) : ?>
                                    <option value="<?php echo $pengirman['tipe_pengiriman_id'] ?>">
                                        <?php echo $pengirman['tipe_pengiriman_nama_tipe'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label name="CAPTION-TIPEPICKINGLIST">Tipe Picking List</label>
                            <select class="form-control select2" name="tipe_picking_list" id="tipe_picking_list" required>
                                <option value="">-- Pilih Tipe Picking List --</option>
                                <option value="Standar">Standar</option>
                                <option value="Mix">Mix</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control select2" name="status" id="status" required>
                                <option value="">-- Pilih Status --</option>
                                <?php foreach ($statuses as $status) : ?>
                                    <option value="<?php echo $status['delivery_order_batch_status'] ?>">
                                        <?php echo $status['delivery_order_batch_status'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Area</label>
                            <select class="form-control select2" name="area" id="area" required>
                                <option value="">-- Pilih Area --</option>
                                <?php foreach ($listArea as $area) : ?>
                                    <option value="<?php echo $area['area_id'] ?>">
                                        <?php echo $area['area_nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12">
                        <button class="btn btn-primary" id="search_filter_data"><i class="fas fa-search"></i> Filter</button>
                        <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                        <!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>