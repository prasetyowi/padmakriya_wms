<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Data Palette Generator</h3>
            </div>
            <div style="float: right">
                <?php if ($Menu_Access["C"] == 1) : ?>
                <a href="<?= base_url('WMS/PalleteGenerator/create') ?>" class="btn btn-primary"><i
                        class="fa fa-plus"></i> <label name="CAPTION-BUATBARU">Buat Baru</label></a>
                <?php endif; ?>
            </div>
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
                            <div class="col-xs-4">
                                <label name="CAPTION-TANGGALBUAT">Tanggal Buat</label>
                                <input type="text" id="filter-do-date" class="form-control" name="filter_do_date"
                                    value="" />
                            </div>
                            <div class="col-xs-4">
                                <label name="CAPTION-PT">Perusahaan</label>
                                <select id="filter_perusahaan" name="filter_perusahaan" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($perusahaan as $row) : ?>
                                    <option value="<?= $row->client_wms_id ?>"><?= $row->client_wms_nama ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <label name="CAPTION-PRINCIPLE">Principle</label>
                                <select id="filter_principle" name="filter_principle" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">--Pilih Principle--</label></option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <span id="loadingsearch" style="display:none;"><i class="fa fa-spinner fa-spin"></i>
                                    <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btn-search-data-pallet_gen" class="btn btn-success"><i
                                        class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_pallet_generator" width="100%"
                                    class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <td class="text-center" style="color:white;">
                                                <strong><label name="CAPTION-NO">No</label></strong>
                                            </td>
                                            <td class="text-center" style="color:white;"><strong><label
                                                        name="CAPTION-TANGGALBUAT">Tanggal Buat</label></strong></td>
                                            <td class="text-center" style="color:white;">
                                                <strong><label name="CAPTION-PT">Perusahaan</label></strong>
                                            </td>
                                            <td class="text-center" style="color:white;">
                                                <strong><label name="CAPTION-PRINCIPLE">Principle</label></strong>
                                            </td>
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