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
<div class="modal fade" id="previewaddnewoutlet" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button <h4 class="modal-title"><label>Tambah Data Pelanggan</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <div class="form-group txtname-coorporate">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname-coorporate" id="txtname-coorporate" placeholder="Nama Coorporate" required/>
                            <div class="invalid-feedback invalid-nama-corporate"></div>
                        </div>

                        <div class="form-group txtaddress-coorporate">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" rows="3" name="txtaddress-coorporate" id="txtaddress-coorporate" placeholder="Alamat Coorporate" required></textarea>
                            <div class="invalid-feedback invalid-alamat-corporate"></div>
                        </div>

                        <!-- <div class="form-group listcoorporate-group">
                            <label>Coorporate Group</label>
                            <select class="form-control" name="listcoorporate-group" id="listcoorporate-group">
                                <option value="">--Pilih Coorporate Group--</option>
                            </select>
                        </div> -->

                        <div class="form-group txtphone-coorporate">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone-coorporate" id="txtphone-coorporate" placeholder="Telepon Coorporate" required/>
                            <div class="invalid-feedback invalid-telepon-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-province">
                            <label>Provinsi</label>
                            <select class="form-control" name="listcoorporate-province" id="listcoorporate-province" required>
                                <option value="">--Pilih Provinsi--</option>
                            </select>
                            <div class="invalid-feedback invalid-provinsi-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-city">
                            <label>Kota</label>
                            <select class="form-control" name="listcoorporate-city" id="listcoorporate-city" required>
                                <option value="">--Pilih Kota--</option>
                            </select>
                            <div class="invalid-feedback invalid-kota-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-districts">
                            <label>Kecamatan</label>
                            <select class="form-control" name="listcoorporate-districts" id="listcoorporate-districts" required>
                                <option value="">--Pilih Kecamatan--</option>
                            </select>
                            <div class="invalid-feedback invalid-kecamatan-corporate"></div>
                            <input type="hidden" id="data-districts"/>
                        </div>

                        <div class="form-group listcoorporate-ward">
                            <label>Kelurahan</label>
                            <select class="form-control" name="listcoorporate-ward" id="listcoorporate-ward" required>
                                <option value="">--Pilih Kelurahan--</option>
                            </select>
                            <div class="invalid-feedback invalid-kelurahan-corporate"></div>
                            <input type="hidden" id="data-ward"/>
                        </div>

                        <div class="form-group txtpostalcode-coorporate">
                            <label>Kode Pos</label>
                            <input type="text" class="form-control" name="txtpostalcode-coorporate" id="txtpostalcode-coorporate" placeholder="Kode Pos sesuai alamat" required/>
                            <div class="invalid-feedback invalid-kode-pos-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-stretclass">
                            <label>Kelas Jalan</label>
                            <select class="form-control" name="listcoorporate-stretclass" id="listcoorporate-stretclass" required>
                                <option value="">--Pilih Kelas Jalan--</option>
                            </select>
                            <div class="invalid-feedback invalid-kelas-jalan-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-area">
                            <label>Area</label>
                            <select class="form-control" name="listcoorporate-area" id="listcoorporate-area" required>
                                <option value="">--Pilih Area--</option>
                            </select>
                            <div class="invalid-feedback invalid-area-corporate"></div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtlattitude-coorporate">
                                    <label>Lattitude</label>
                                    <input type="text" class="form-control" name="txtlattitude-coorporate" id="txtlattitude-coorporate" placeholder="Lattitude Coorporate" required/>
                                    <div class="invalid-feedback invalid-lattitude-corporate"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtlongitude-coorporate">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" name="txtlongitude-coorporate" id="txtlongitude-coorporate" placeholder="Longitude Coorporate" required/>
                                    <div class="invalid-feedback invalid-longitude-corporate"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <h5 class="text-center badge badge-info">Contact Person</h5>

                        <div class="form-group txtname-contact-person">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname-contact-person" id="txtname-contact-person" placeholder="Nama Contact Person" required/>
                            <div class="invalid-feedback invalid-nama-contact-person"></div>
                        </div>

                        <div class="form-group txtphone-contact-person">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone-contact-person" id="txtphone-contact-person" placeholder="Telepon Contact Person" required/>
                            <div class="invalid-feedback invalid-telepon-contact-person"></div>
                        </div>

                        <div class="form-group txtkreditlimit-contact-person">
                            <label>Kredit Limit</label>
                            <input type="text" class="form-control" name="txtkreditlimit-contact-person" id="txtkreditlimit-contact-person" placeholder="Kredit Limit Contact Person" required/>
                            <div class="invalid-feedback invalid-kredit-limit-contact-person"></div>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 1</label>
                            <select class="form-control" name="listcontactperson-segment1" id="listcontactperson-segment1">
                                <option value="">--Pilih Segmentasi 1--</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 2</label>
                            <select class="form-control" name="listcontactperson-segment2" id="listcontactperson-segment2">
                                <option value="">--Pilih Segmentasi 2--</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 3</label>
                            <select class="form-control" name="listcontactperson-segment3" id="listcontactperson-segment3">
                                <option value="">--Pilih Segmentasi 3--</option>
                            </select>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="multilocation">
                            <label class="form-check-label" for="multilocation">Multi Lokasi?</label>
                        </div>
                        <div class="form-group" id="showlistmultilokasi" style="display:none">
                            <label for="listcontactperson-location">Lokasi</label><br>
                            <select class="select2 form-group" style="width:100%" multiple="multiple" name="listcontactperson-location" id="listcontactperson-location"></select>
                        </div>

                        <div class="form-group">
                            <label>Waktu Operasional</label>
                            <div class="table-responsive">
                                <table class="table table-striped" id="list-day-operasional">
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
            <span id="loadingadd" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-success" id="btnsaveaddnewoutlet">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnback">Kembali</button>
            </div>
        </div>
    </div>
</div>