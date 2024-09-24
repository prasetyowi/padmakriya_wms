<style>
    .invalid-feedback{
        color: red;
    }

    .fa-spin{
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}
</style>
<div class="modal fade" id="previewupdateoutlet" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button <h4 class="modal-title"><label>Edit Data Pelanggan</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <div class="form-group txtname-coorporate-update">
                            <label>Nama</label>
                            <input type="hidden" id="txthideOutletId-update"/>
                            <input type="text" class="form-control" id="txtname-coorporate-update" placeholder="Nama Coorporate"/>
                            <div class="invalid-feedback invalid-nama-corporate-update"></div>
                        </div>

                        <div class="form-group txtaddress-coorporate-update">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" rows="3" id="txtaddress-coorporate-update" placeholder="Alamat Coorporate"></textarea>
                            <div class="invalid-feedback invalid-alamat-corporate-update"></div>
                        </div>

                        <!-- <div class="form-group listcoorporate-group-update">
                            <label>Coorporate Group</label>
                            <select class="form-control" name="listcoorporate-group-update" id="listcoorporate-group-update">
                                <option value="">--Pilih Coorporate Group--</option>
                            </select>
                        </div> -->

                        <div class="form-group txtphone-coorporate-update">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" id="txtphone-coorporate-update" placeholder="Telepon Coorporate-update"/>
                            <div class="invalid-feedback invalid-telepon-corporate-update"></div>
                        </div>

                        <div class="form-group listcoorporate-province-update">
                            <label>Provinsi</label>
                            <select class="form-control" id="listcoorporate-province-update">
                            </select>
                            <div class="invalid-feedback invalid-provinsi-corporate-update"></div>
                        </div>

                        <div class="form-group listcoorporate-city-update">
                            <label>Kota</label>
                            <select class="form-control" id="listcoorporate-city-update">
                            </select>
                            <div class="invalid-feedback invalid-kota-corporate-update"></div>
                        </div>

                        <div class="form-group listcoorporate-districts-update">
                            <label>Kecamatan</label>
                            <select class="form-control" id="listcoorporate-districts-update">
                            </select>
                            <div class="invalid-feedback invalid-kecamatan-corporate-update"></div>
                            <input type="hidden" id="data-districts-update"/>
                        </div>

                        <div class="form-group listcoorporate-ward-update">
                            <label>Kelurahan</label>
                            <select class="form-control" id="listcoorporate-ward-update">
                            </select>
                            <div class="invalid-feedback invalid-kelurahan-corporate-update"></div>
                            <input type="hidden" id="data-ward-update"/>
                        </div>

                        <div class="form-group txtpostalcode-coorporate-update">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" id="txtpostalcode-coorporate-update" placeholder="Kode Pos sesuai alamat"/>
                            <div class="invalid-feedback invalid-kode-pos-corporate-update"></div>
                        </div>

                        <div class="form-group listcoorporate-stretclass-update">
                            <label>Kelas Jalan</label>
                            <select class="form-control" id="listcoorporate-stretclass-update">
                                <option value="">--Pilih Kelas Jalan--</option>
                            </select>
                            <div class="invalid-feedback invalid-kelas-jalan-corporate-update"></div>
                        </div>

                        <div class="form-group listcoorporate-area-update">
                            <label>Area</label>
                            <select class="form-control" id="listcoorporate-area-update">
                                <option value="">--Pilih Area--</option>
                            </select>
                            <div class="invalid-feedback invalid-area-corporate-update"></div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtlattitude-coorporate-update">
                                    <label>Lattitude</label>
                                    <input type="text" class="form-control" id="txtlattitude-coorporate-update" placeholder="Lattitude Coorporate"/>
                                    <div class="invalid-feedback invalid-lattitude-corporate-update"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtlongitude-coorporate-update">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" id="txtlongitude-coorporate-update" placeholder="Longitude Coorporate"/>
                                    <div class="invalid-feedback invalid-longitude-corporate-update"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <h5 class="text-center badge badge-info">Contact Person</h5>

                        <div class="form-group txtname-contact-person-update">
                            <label>Nama</label>
                            <input type="text" class="form-control" id="txtname-contact-person-update" placeholder="Nama Contact Person"/>
                            <div class="invalid-feedback invalid-nama-contact-person-update"></div>
                        </div>

                        <div class="form-group txtphone-contact-person-update">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" id="txtphone-contact-person-update" placeholder="Telepon Contact Person"/>
                            <div class="invalid-feedback invalid-telepon-contact-person-update"></div>
                        </div>

                        <div class="form-group txtkreditlimit-contact-person-update">
                            <label>Kredit Limit</label>
                            <input type="text" class="form-control" id="txtkreditlimit-contact-person-update" placeholder="Kredit Limit Contact Person"/>
                            <div class="invalid-feedback invalid-kredit-limit-contact-person-update"></div>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 1</label>
                            <select class="form-control" id="listcontactperson-segment1-update">
                                <option value="">--Pilih Segmentasi 1--</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 2</label>
                            <select class="form-control" id="listcontactperson-segment2-update">
                                <option value="">--Pilih Segmentasi 2--</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 3</label>
                            <select class="form-control" id="listcontactperson-segment3-update">
                                <option value="">--Pilih Segmentasi 3--</option>
                            </select>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="multilocation">
                            <label class="form-check-label" for="multilocation">Multi Lokasi?</label>
                        </div>
                        <div class="form-group" id="showlistmultilokasi" style="display:none">
                            <label for="listcontactperson-location">Lokasi</label><br>
                            <select class="form-group" style="width:100%" name="listcontactperson-location" id="listcontactperson-location"></select>
                        </div>

                        <div class="form-group">
                            <label>Waktu Operasional</label>
                            <div class="table-responsive">
                                <table class="table table-striped" id="list-day-operasional-update">
                                    <thead>
                                        <tr>
                                            <td width="5%">No.</td>
                                            <td width="10%">Hari</td>
                                            <td width="10%">Jam Buka</td>
                                            <td width="10%">Jam Tutup</td>
                                            <td width="30%">Status</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingupdate" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-success" id="btnsaveupdateoutlet">Ubah</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnupdateback">Kembali</button>
            </div>
        </div>
    </div>
</div>