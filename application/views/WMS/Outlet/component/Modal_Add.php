<div class="modal fade" id="previewaddnewoutlet" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button <h4 class="modal-title"><label>Tambah Data Outlet</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <div class="form-group txtname-coorporate">
                            <label>Nama</label>
                            <input type="text" class="form-control<?php if (form_error('name_corporate')) echo 'has-error'; ?>" name="txtname-coorporate" id="txtname-coorporate" placeholder="Nama Outlet" required />
                            <div class="invalid-feedback invalid-nama-corporate"></div>
                            <?= form_error('name_corporate', '<small class="text-danger pl-2" style="margin-top:3px;">', '</small>'); ?>
                        </div>

                        <div class="form-group txtaddress-coorporate">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" rows="3" name="txtaddress-coorporate" id="txtaddress-coorporate" placeholder="Alamat Outlet" required></textarea>
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
                            <input type="text" class="form-control numeric <?php if (form_error('phone_corporate')) echo 'has-error'; ?>" name="txtphone-coorporate" id="txtphone-coorporate" placeholder="Telepon Outlet" required />
                            <div class="invalid-feedback invalid-telepon-corporate"></div>
                            <?= form_error('phone_corporate', '<small class="text-danger pl-2" style="margin-top:3px;">', '</small>'); ?>
                        </div>

                        <div class="form-group listcoorporate-province">
                            <label>Provinsi</label>
                            <select class="select2 form-control" name="listcoorporate-province" id="listcoorporate-province" required>

                            </select>
                            <div class="invalid-feedback invalid-provinsi-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-city">
                            <label>Kota</label>
                            <select class="select2 form-control" name="listcoorporate-city" id="listcoorporate-city" required>

                            </select>
                            <div class="invalid-feedback invalid-kota-corporate"></div>
                        </div>

                        <div class="form-group listcoorporate-districts">
                            <label>Kecamatan</label>
                            <select class="select2 form-control" name="listcoorporate-districts" id="listcoorporate-districts" required>

                            </select>
                            <div class="invalid-feedback invalid-kecamatan-corporate"></div>
                            <input type="hidden" id="data-districts" />
                        </div>

                        <div class="form-group listcoorporate-ward">
                            <label>Kelurahan</label>
                            <select class="select2 form-control" name="listcoorporate-ward" id="listcoorporate-ward" required>

                            </select>
                            <div class="invalid-feedback invalid-kelurahan-corporate"></div>
                            <input type="hidden" id="data-ward" />
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtpostalcode-coorporate">
                                    <label>Kode Pos</label>
                                    <input type="text" class="form-control numeric" name="txtpostalcode-coorporate" id="txtpostalcode-coorporate" placeholder="Kode Pos sesuai alamat" required />
                                    <div class="invalid-feedback invalid-kode-pos-corporate"></div>
                                </div>

                                <div class="form-group listcoorporate-stretclass">
                                    <label>Kelas Jalan Berdasarkan Beban muatan</label>
                                    <select class="select2 form-control" name="listcoorporate-stretclass" id="listcoorporate-stretclass" required>
                                    </select>
                                    <div class="invalid-feedback invalid-kelas-jalan-corporate"></div>
                                </div>

                                <div class="form-group txtlattitude-coorporate">
                                    <label>Lattitude</label>
                                    <input type="text" class="form-control " name="txtlattitude-coorporate" id="txtlattitude-coorporate" placeholder="Lattitude Outlet" required />
                                    <div class="invalid-feedback invalid-lattitude-corporate"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group listcoorporate-area">
                                    <label>Area</label>
                                    <select class="select2 form-control" name="listcoorporate-area" id="listcoorporate-area" required>

                                    </select>
                                    <div class="invalid-feedback invalid-area-corporate"></div>
                                </div>

                                <div class="form-group listcoorporate-stretclass2">
                                    <label>Kelas Jalan Berdasarkan Fungsi jalan</label>
                                    <select class="select2 form-control" name="listcoorporate-stretclass2" id="listcoorporate-stretclass2" required>
                                    </select>
                                    <div class="invalid-feedback invalid-kelas-jalan2-corporate"></div>
                                </div>

                                <div class="form-group txtlongitude-coorporate">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control " name="txtlongitude-coorporate" id="txtlongitude-coorporate" placeholder="Longitude Outlet" required />
                                    <div class="invalid-feedback invalid-longitude-corporate"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <h5 class="text-center badge badge-info">Contact Person</h5>

                        <div class="form-group txtname-contact-person">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname-contact-person" id="txtname-contact-person" placeholder="Nama Contact Person" required />
                            <div class="invalid-feedback invalid-nama-contact-person"></div>
                        </div>

                        <div class="form-group txtphone-contact-person">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone-contact-person" id="txtphone-contact-person" placeholder="Telepon Contact Person" required />
                            <div class="invalid-feedback invalid-telepon-contact-person"></div>
                        </div>

                        <div class="form-group txtkreditlimit-contact-person">
                            <label>Kredit Limit</label>
                            <input type="text" class="form-control numeric" name="txtkreditlimit-contact-person" id="txtkreditlimit-contact-person" placeholder="Kredit Limit Contact Person" required />
                            <div class="invalid-feedback invalid-kredit-limit-contact-person"></div>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 1</label>
                            <select class="select2 form-control" name="listcontactperson-segment1" id="listcontactperson-segment1">

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 2</label>
                            <select class="select2 form-control" name="listcontactperson-segment2" id="listcontactperson-segment2">

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Segmentasi 3</label>
                            <select class="select2 form-control" name="listcontactperson-segment3" id="listcontactperson-segment3">

                            </select>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="multilocation">
                            <label class="form-check-label" for="multilocation">Multi Lokasi?</label>
                        </div>
                        <div class="form-group listcontactperson-location" id="showlistmultilokasi" style="display:none">
                            <label for="listcontactperson-location">Lokasi</label><br>
                            <select class="select2 form-control" name="listcontactperson-location" id="listcontactperson-location">

                            </select>
                            <div class="invalid-feedback invalid-list-location-contact-person"></div>
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

                        <div class="form-group">
                            <input type="checkbox" class="form-check-input" checked required id="txtstatus-coorporate">
                            <label class="form-check-label" for="txtstatus-coorporate">Status Aktif</label>
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