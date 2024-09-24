<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><span name="CAPTION-NONAKTIFPALLET2">Non Aktif Pallet 2</span></h3>
            </div>
            <div class="title_right">
                <div class="pull-right">
                    <button id="nonAktifPallet" class="btn btn-danger" type="button"><i class="fa fa-link-slash"></i>
                        Non Aktif Pallet</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content table-responsive">
                        <table id="table_pallet" width="100%" class="table table-hover table-bordered dataTable-sm-td">
                            <thead>
                                <tr class="bg-primary">
                                    <th class="text-center" style="color:white;">Pilih</th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-KODEPALLET">Kode
                                            Pallet</span></th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-LOKASI">Lokasi</span>
                                    </th>
                                    <th class="text-center" style="color:white;"><span name="CAPTION-RAK">Rak</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pallet as $key => $data) { ?>
                                    <tr class="text-center">
                                        <td style="width: 5%;">
                                            <input type="checkbox" onchange="selectPallet('<?= $data['pallet_id'] ?>', event)" class="form-check-input" />
                                        </td>
                                        <td><?= $data['pallet_kode'] ?></td>
                                        <td><?= $data['depo_detail_nama'] ?></td>
                                        <td><?= $data['rak_lajur_detail_nama'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>