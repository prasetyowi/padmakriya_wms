<div class="right_col" role="main" id="divUtama">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-TINDAKLANJUTHASILSTOCKOPNAME">Tindak Lanjut Hasil Stock Opname</h3>
            </div>
            <div style="float: right">
                <a href="<?= base_url('WMS/TindakLanjutHasilStockOpname/create') ?>" class="btn btn-primary" id="btnshowtambahdata"><i class="fas fa-plus"></i> <label name="CAPTION-TAMBAHBARU">Tambah Baru</label></a>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="panel panel-default">
            <div class="panel-heading"><label name="CAPTION-PENCARIANTINDAKLANJUTHASILOPNAME">Pencarian Tindak Lanjut Hasil Stock Opname</label></div>
            <div class="panel-body form-horizontal form-label-left">
                <div class="container mt-2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label name="CAPTION-TANGGAL">Tanggal</label>
                                        <input type="text" id="filter_tanggal" class="form-control" name="filter_tanggal" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label name="CAPTION-PT">PT</label>
                                        <select onchange="getPrinciple(this.value)" class="form-control select2" name="filter_pt" id="filter_pt" style="width: 100%;">
                                            <option value="">--Pilih Perusahaan--</option>
                                            <?php foreach ($perusahaan as $key => $value) : ?>
                                                <option value="<?= $value['client_wms_id'] ?>"><?= $value['client_wms_nama'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label name="CAPTION-PRINCIPLE">Principle</label>
                                        <select id="filter_principle" name="filter_principle" class="form-control select2" style="width: 100%;">
                                            <option value="">--Pilih Principle--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button style="float: right;" class="btn btn-primary" onclick="searchFilter()" id="search_filter_data"><i class="fas fa-search"></i> <label name="CAPTION-FILTER">Filter</label></button>
                            <span id="loadingsearch" style="display:none; float: right;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                            <!-- <button class="btn btn-danger" id="clear-storage"><i class="fas fa-trash"></i> Clear Storage</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="list-data-form-search">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="container mt-2">
                        <div class="row">
                            <div class="x_content table-responsive">
                                <table id="tableTindakLanjutHasilStokOpname" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <!-- <th width="5%" class="text-center">#</th> -->
                                            <th class="text-center"><label name="CAPTION-NO">NO</label></th>
                                            <th class="text-center"><label name="CAPTION-TANGGAL">Tanggal</label></th>
                                            <th class="text-center"><label name="CAPTION-KODE">Kode</label></th>
                                            <th class="text-center"><label name="CAPTION-PERUSAHAAN">Perusahaan</label></th>
                                            <th class="text-center"><label name="CAPTION-PRINCIPLE">Principle</label></th>
                                            <th class="text-center"><label name="CAPTION-ACTION">Action</label></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>