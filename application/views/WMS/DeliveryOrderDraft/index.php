<style>
    .switch-toggle input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        z-index: -2;
    }

    .switch-toggle input[type="checkbox"]+label {
        position: relative;
        display: inline-block;
        width: 100px;
        height: 40px;
        border-radius: 20px;
        margin: 0;
        cursor: pointer;
        box-shadow: 1px 1px 4px 1px;

    }

    .switch-toggle input[type="checkbox"]+label::before {
        position: absolute;
        content: 'Tidak';
        font-size: 13px;
        text-align: center;
        line-height: 25px;
        top: 8px;
        left: 8px;
        width: 45px;
        height: 25px;
        color: #fff;
        border-radius: 20px;
        background-color: #dc3545;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #dc3545;
        transition: .3s ease-in-out;
    }

    .switch-toggle input[type="checkbox"]:checked+label::before {
        left: 50%;
        content: 'Iya';
        color: #fff;
        background-color: #5cb85c;
        box-shadow: -3px -3px 5px rgba(255, 255, 255, .5),
            3px 3px 5px #5cb85c;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3 name="CAPTION-DRAFTSURATTUGASPENGIRIMAN">Draft Surat Tugas Pengiriman</h3>
            </div>
            <div style="float: right">
                <?php if ($Menu_Access["C"] == 1) : ?>
                    <a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/DeliveryOrderDraftPrioritasStok') ?>" class="btn btn-primary" target="_blank"><i class="fa fa-edit"></i> <span name="CAPTION-DOPRIORITAS">DO Prioritas</span></a>
                    <button class="btn-submit btn btn-primary" id="btnerrormsg" onclick="ErrorMsgDOMasal()"><i class="fa fa-message"></i> <span name="CAPTION-PESANERRORDOAPPROVE">Pesan Error DO
                            Approve</span></button>
                    <!-- <button class="btn-submit btn btn-primary" id="btnerrormsg" onclick="ErrorMsgDOMasal()"><i class="fa fa-message"></i> <span name="CAPTION-PESANERRORDOAPPROVE">Pesan Error DO
                            Approve</span> (<span id="CAPTION-JUMLAHERRORMSG">0</span>) </button> -->
                    <!-- <button class="btn-submit btn btn-primary" id="btnpembongkaranbarang" onclick="PembongkaranBarang()"><i class="fa fa-box"></i> <span name="CAPTION-PERSETUJUANPEMBONGKARANKEMASAN">Persetujuan Pembongkaran Kemasan</span> (<span id="CAPTION-JUMLAHPEMBONGKARAN">0</span>) </button> -->
                    <button class="btn-submit btn btn-danger" onclick="handlerPembatalanDO()"><i class="fa-solid fa-xmark"></i> <span>Pembatalan DO</label></span></button>
                    <button class="btn-submit btn btn-warning" onclick="handlerUbahKeDraft()"><i class="fa-solid fa-arrows-rotate"></i> <span name="CAPTION-UBAHKEDRAFT">Ubah Ke Draft</label></span></button>
                    <button class="btn-submit btn btn-primary" onclick="handlerEditMultiDateDO()"><i class="fa fa-pencil"></i> <span>Edit</label></span></button>
                    <button class="btn-submit btn btn-primary" id="btnapprovdodraft"><i class="fa fa-check"></i> <span name="CAPTION-APPROV">Approv</span></button>
                    <a href="<?= base_url('WMS/Distribusi/DeliveryOrderDraft/create') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <span name="CAPTION-BARU">Baru</span></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h4>Filter Data</h4>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input type="text" id="filter-do-date" class="form-control" name="filter_do_date" value="" />
                                <input type="hidden" id="jml_do" class="form-control" name="jml_do" value="" />
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-DONO">DO No.</label>
                                <input type="text" id="filter-do-number" class="form-control" name="filter_do_number" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-CUSTOMER">Customer</label>
                                <input type="text" id="filter-customer" class="form-control" name="filter_customer" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-ALAMAT">Alamat</label>
                                <input type="text" id="filter-address" class="form-control" name="filter_address" value="">
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-NOSO">No. SO</label>
                                <input type="text" id="filter-so" class="form-control" name="filter_so" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label>
                                <input type="text" id="filter-so-eksternal" class="form-control" name="filter_so_eksternal" value="">
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-TIPEPEMBAYARAN">Tipe Pembayaran</label>
                                <select id="filter-payment-type" name="filter_payment_type" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <option value="0"><label name="CAPTION-TUNAI">Tunai</label></option>
                                    <option value="1"><label name="CAPTION-NONTUNAI">Non Tunai</label></option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-TIPELAYANAN">Tipe Layanan</label>
                                <select id="filter-service-type" name="filter_service_type" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($TipePelayanan as $row) : ?>
                                        <option value="<?= $row['tipe_layanan_nama'] ?>"><?= $row['tipe_layanan_nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-TIPE">Tipe</label>
                                <select id="filter-do-type" name="filter_do_type" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($TipeDeliveryOrder as $type) : ?>
                                        <option value="<?= $type['tipe_delivery_order_id'] ?>">
                                            <?= $type['tipe_delivery_order_alias'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SEGMENT1">Segment 1</label>
                                <select id="filter-segment1" name="filter_segment_1" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SEGMENT2">Segment 2</label>
                                <select id="filter-segment2" name="filter_segment2" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SEGMENT3">Segment 3</label>
                                <select id="filter-segment3" name="filter_segment3" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-PRINCIPLE">Principle</label>
                                <select id="filter-principle" name="filter_principle" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($Principle as $row) : ?>
                                        <option value="<?= $row['principle_id'] ?>"><?= $row['principle_kode'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-SALES">Sales</label>
                                <select id="filter-sales" name="filter_sales" class="form-control select2">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <?php foreach ($SalesEksternal as $row) : ?>
                                        <option value="<?= $row['sales_eksternal_id'] ?>"><?= $row['sales_eksternal_id'] ?>
                                            || <?= $row['karyawan_nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-PRIORITY">Prioritas</label>
                                <select id="filter-priority" class="input-sm form-control select2" name="filter-priority">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <option value="1">Tampilkan yg prioritas</option>
                                    <option value="0">Tampilkan yg bukan prioritas</option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label name="CAPTION-STATUSDO">Status DO</label>
                                <select id="filter-status" name="filter_status" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
                                    <option value="Approved"><label name="CAPTION-APPROVED">Approved</label></option>
                                    <option value="Rejected"><label name="CAPTION-REJECTED">Rejected</label></option>
                                    <option value="canceled"><label name="CAPTION-REJECTED">Canceled</label></option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-xs-3">
                                <label name="CAPTION-STATUSPENDING">Status Pending</label>
                                <select id="filter-status-pending" name="filter_status_pending" class="form-control">
                                    <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                    <option value="1"><label name="CAPTION-PENDING">Pending</label></option>
                                    <option value="0"><label name="CAPTION-TIDAKPENDING">Tidak Pending</label></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <span id="loadingviewdodraft" style="display:none;"><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>
                                <button type="button" id="btn-search-data-do-draft" class="btn btn-success"><i class="fa fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="x_content table-responsive">
                                <table id="table_list_data_do_draft" width="100%" class="table table-striped table-bordered">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th class="text-center" style="color:white;"><input type="checkbox" name="select-do" id="select-do" value="1"></th>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana
                                                        Kirim</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-NODO">No. DO</label></br>
                                                    <div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div>
                                                </strong><strong><label name="CAPTION-NOSO">No. SO</label></strong></td>
                                            <!-- <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSO">No. SO</label></strong></td> -->
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSOEKSTERNAL">No. SO Eksternal</label></strong>
                                            </td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></br>
                                                <div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div><strong><label name="CAPTION-SALES">Sales</label></strong>
                                            </td>
                                            <!-- <td class="text-center" style="color:white;"><strong><label name="CAPTION-SALES">Sales</label></strong></td> -->
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-NAMACUSTOMER">Nama Customer</label></strong></br>
                                                <div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div><strong><label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label></strong>
                                            </td>
                                            <!-- <td class="text-center" style="color:white;"><strong><label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label></strong></td> -->
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
                                            </td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPE">Tipe</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-UMUR">Umur SO</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOMINAL">Nominal</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-SEGMENT1">Segment 1</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-SEGMENT2">Segment 2</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-SEGMENT3">Segment 3</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-PRIORITY">Prioritas</label></strong></td>
                                            <td class="text-center" style="color:white;"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
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

<div class="modal fade" id="modalPembatalanDO" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:80%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Pembatalan DO</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input type="text" id="filter-do-pembatalan-date2" class="form-control" name="filter_do_pembatalan_date2" value="" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-PRINCIPLE">Principle</label>
                                <select name="principlePembatalan" id="principlePembatalan" class="form-control select2">
                                    <option value="">All</option>
                                    <?php foreach ($Principle as $key => $value) { ?>
                                        <option value="<?= $value['principle_id'] ?>"><?= $value['principle_kode'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label name="CAPTION-STATUS">Status</label>
                            <select id="filter-status-pembatalan" name="filter_status_pembatalan" class="form-control">
                                <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                <option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
                                <option value="Approved"><label name="CAPTION-APPROVED">Approved</label></option>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <button class="btn btn-primary" onclick="handlerDataSearchPembatalanDO()" style="margin-top: 24px;"><i class="fas fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                        </div>
                    </div>

                    <div class="table-responsinve">
                        <table class="table table-striped table-bordered" width="100%" id="tableDataDOPembatalan">
                            <thead>
                                <tr class="bg-primary">
                                    <td>
                                        <input type="checkbox" id="check-all-pilih-sj" style="transform: scale(1)" class="form-control" onchange="checkAllSJ(this)" />
                                    </td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-PRINCIPLE">Principle</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-TGLRENCANAKIRIM">Tgl Rencana Kirim</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NODO">No.
                                                DO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSO">No.
                                                SO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSOEKSTERNAL">No. SO External</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NAMACUSTOMER">Nama Customer</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="handlerSavePembatalanDO()"><i class="fas fa-save"></i> <label name="CAPTION-BATALKAN">Batalkan</label></button>
                <button type="button" class="btn btn-dark" onclick="handlerClosePembatalanDO()"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUbahKeDraft" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:80%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Ubah Ke Draft</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input type="text" id="filter-tgl-ubah-ke-draft" class="form-control" name="filter-tgl-ubah-ke-draft" value="" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-AREA">Area</label>
                                <select name="areaUbahKeDraft" id="areaUbahKeDraft" class="form-control select2">
                                    <option value="">All</option>
                                    <?php foreach ($areas as $key => $value) { ?>
                                        <option value="<?= $value->area_nama ?>"><?= $value->area_nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <button class="btn btn-primary" onclick="handlerDataSearchUbahKeDraft()" style="margin-top: 24px;"><i class="fas fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                        </div>
                    </div>

                    <div class="table-responsinve">
                        <table class="table table-striped table-bordered" width="100%" id="tableDataUbahKeDraft">
                            <thead>
                                <tr class="bg-primary">
                                    <td>
                                        <input type="checkbox" id="check-all-ubah-draft" style="transform: scale(1)" class="form-control" onchange="checkAllUbahDraft(this)" />
                                    </td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NODO">No.
                                                DO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSO">No.
                                                SO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSOEKSTERNAL">No. SO External</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NAMACUSTOMER">Nama Customer</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-TIPEDELIVERYORDER">Tipe Delivery Order</label></strong></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="handlerSaveUbahKeDraft()"><i class="fas fa-save"></i> <label name="CAPTION-Ubah">Ubah</label></button>
                <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditDataDO" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" style="width:80%">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Edit Data DO</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana Kirim</label>
                                <input type="text" id="filter-do-date2" class="form-control" name="filter_do_date2" value="" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label name="CAPTION-AREA">Area</label>
                                <select name="areaEdit" id="areaEdit" class="form-control select2">
                                    <option value="">All</option>
                                    <?php foreach ($areas as $key => $value) { ?>
                                        <option value="<?= $value->area_nama ?>"><?= $value->area_nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label name="CAPTION-STATUS">Status</label>
                            <select id="filter-status-edit" name="filter_status_edit" class="form-control">
                                <option value=""><label name="CAPTION-SEMUA">Semua</label></option>
                                <option value="Draft"><label name="CAPTION-DRAFT">Draft</label></option>
                                <option value="Approved"><label name="CAPTION-APPROVED">Approved</label></option>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <button class="btn btn-primary" onclick="handlerDataSearchEditDO()" style="margin-top: 24px;"><i class="fas fa-search"></i> <label name="CAPTION-CARI">Cari</label></button>
                        </div>

                        <div class="col-md-12" style="display: none;" id="fieldChangeAllDate">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label name="CAPTION-GANTIAKTUALTGL">Ganti Aktual Rencana Kirim Untuk
                                            Semua</label>
                                        <input type="date" name="allChangeRencanaKirim" id="allChangeRencanaKirim" class="form-control" value="<?= date('Y-m-d') ?>" onchange="handlerAllChangeRencanaKirim(this.value)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsinve">
                        <table class="table table-striped table-bordered" width="100%" id="tableDataDOEdit">
                            <thead>
                                <tr class="bg-primary">
                                    <td>
                                        <input type="checkbox" id="check-all-pilih-sj" style="transform: scale(1)" class="form-control" onchange="checkAllSJ(this)" />
                                    </td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NODO">No.
                                                DO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSO">No.
                                                SO</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NOSOEKSTERNAL">No. SO External</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-NAMACUSTOMER">Nama Customer</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-ALAMATKIRIM">Alamat Kirim</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-AREA">Area</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALRENCANAKIRIM">Tanggal Rencana
                                                Kirim</label></strong></td>
                                    <td class="text-center" style="color:white;"><strong><label name="CAPTION-TANGGALRENCANAKIRIM">Aktual Rencana Kirim</label></strong>
                                    </td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="handlerSaveEditDO()"><i class="fas fa-save"></i>
                    <label name="CAPTION-SAVE">Simpan</label></button>
                <button type="button" class="btn btn-dark" onclick="handlerCloseEditDO()"><i class="fas fa-xmark"></i>
                    <label name="CAPTION-TUTUP">Tutup</label></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pembongkaran-sku" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-PERSETUJUANPEMBONGKARANKEMASAN">Persetujuan Pembongkaran
                        Kemasan</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
                                        <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-KODE">Kode</label>
                                        <input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_kode" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_kode]" autocomplete="off" value="">
                                        <input readonly="readonly" type="hidden" id="persetujuanpembongkaran-tr_konversi_sku_id" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_id]" autocomplete="off" value="<?= $tr_konversi_sku_id ?>">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_tanggal">
                                        <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_tanggal" name="CAPTION-TANGGAL">Tanggal</label>
                                        <input type="text" id="persetujuanpembongkaran-tr_konversi_sku_tanggal" class="form-control datepicker" name="persetujuanpembongkaran[tr_konversi_sku_tanggal]" autocomplete="off" value="<?= date('d-m-Y') ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_kode">
                                        <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_kode" name="CAPTION-TIPE">Tipe</label>
                                        <select name="persetujuanpembongkaran[tipe_konversi_id]" class="form-control" id="persetujuanpembongkaran-tipe_konversi_id" disabled>
                                            <option value="">** <label name="CAPTION-TIPE">Tipe</label> **</option>
                                            <?php foreach ($TipeKonversi as $type) : ?>
                                                <option value="<?= $type['tipe_konversi_id'] ?>" <?= $type['tipe_konversi_id'] == 'B5B99B77-86D2-48B8-964F-D4B91CDD9B0C' ? 'selected' : '' ?>>
                                                    <?= $type['tipe_konversi_nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_status">
                                        <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_status">Status</label>
                                        <input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_status" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_status]" autocomplete="off" value="In Progress Approval">
                                    </div>
                                    <div class="form-group field-persetujuanpembongkaran-konversi_is_need_approval">
                                        <input type="checkbox" id="persetujuanpembongkaran-konversi_is_need_approval" name="persetujuanpembongkaran[konversi_is_need_approval]" autocomplete="off" value="1" checked> <span name="CAPTION-PENGAJUAN">Pengajuan Approval</span>
                                    </div>
                                    <!-- <div class="form-group field-persetujuanpembongkaran-client_wms_id">
										<label for="persetujuanpembongkaran-client_wms_id" class="control-label" name="CAPTION-PERUSAHAAN">Perusahaan</label>
										<select id="persetujuanpembongkaran-client_wms_id" class="form-control select2" name="persetujuanpembongkaran[client_wms_id]">
											<option value="">** <span name="CAPTION-PERUSAHAAN">Perusahaan</span> **</option>
											<?php foreach ($Perusahaan as $row) : ?>
												<option value="<?= $row['client_wms_id'] ?>"><?= $row['client_wms_nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_keterangan">
                                        <label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_keterangan" name="CAPTION-KETERANGAN">Keterangan</label>
                                        <textarea rows="5" type="text" id="persetujuanpembongkaran-tr_konversi_sku_keterangan" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_keterangan]" autocomplete="off"></textarea>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <!-- <div class="form-group field-persetujuanpembongkaran-depo_detail_id">
										<label for="persetujuanpembongkaran-depo_detail_id" class="control-label" name="CAPTION-GUDANG">Gudang</label>
										<select id="persetujuanpembongkaran-depo_detail_id" class="form-control select2" name="persetujuanpembongkaran[depo_detail_id]">
											<option value="">** <span name="CAPTION-GUDANG">Gudang</span> **</option>
											<?php foreach ($Gudang as $row) : ?>
												<option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div> -->
                                    <!-- <div class="form-group field-persetujuanpembongkaran-tr_konversi_sku_status">
										<label class="control-label" for="persetujuanpembongkaran-tr_konversi_sku_status">Status</label>
										<input readonly="readonly" type="text" id="persetujuanpembongkaran-tr_konversi_sku_status" class="form-control" name="persetujuanpembongkaran[tr_konversi_sku_status]" autocomplete="off" value="Draft">
									</div>
									<div class="form-group field-persetujuanpembongkaran-konversi_is_need_approval">
										<input type="checkbox" id="persetujuanpembongkaran-konversi_is_need_approval" name="persetujuanpembongkaran[konversi_is_need_approval]" autocomplete="off" value="1" disabled> <span name="CAPTION-PENGAJUAN">Pengajuan Approval</span>
									</div> -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row" id="panel-sku">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <h4 class="pull-left" name="CAPTION-SKUYGDIPILIH">SKU Yang Dipilih</h4>
                            <table class="table table-bordered" id="table-pembongkaran-sku">
                                <thead>
                                    <tr class="bg-primary">
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-BRAND">Brand</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-KEMASAN">Kemasan</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SATUAN">Satuan</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PERUSAHAAN">Perusahaan</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-GUDANG">Gudang</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUREQEXPDATE">Tgl Kadaluwarsa SKU</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUQTY">SKU QTY</th>
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
                <button type="button" class="btn btn-info" id="btnsavekonversi"><i class="fa fa-save"></i> <span name="CAPTION-SAVE"></span></button>
                <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pesan-error" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title"><label name="CAPTION-PESANERRORDOAPPROVE">Pesan Error DO Approve</label></h4>
            </div>
            <div class="modal-body">
                <div class="row" id="panel-sku">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <table class="table table-bordered" id="table-pesan-error" style="width:100%;">
                                <thead>
                                    <tr class="bg-primary">
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NODO">No</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-NODO">No. DO</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-OUTLET">Outlet</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SEGMEN">Segmen</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PRINCIPLE">Principle</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKUKODE">SKU Kode</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-SKU">SKU</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-FLAG">Flag</th>
                                        <th style="text-align: center; vertical-align: middle;color:white;" name="CAPTION-PESAN">Pesan</th>
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
                <button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> <span name="CAPTION-TUTUP">Tutup</span></button>
            </div>
        </div>
    </div>
</div>