<style>
    .invalid-feedback{
        color: red;
    }
</style>
<div class="modal fade" id="previewupdateprinciple" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button <h4 class="modal-title"><label>Edit Data Principle</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtkode_coorporate_principle_update">
                                    <label>Kode</label>
                                    <input type="text" class="form-control" name="txtkode_coorporate_principle_update" id="txtkode_coorporate_principle_update" placeholder="Kode Principle" required/>
                                    <div class="invalid-feedback invalid_kode_corporate_principle_update"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtname_coorporate_principle_update">
                                    <label>Nama</label>
                                    <input type="hidden" id="txthidePrincipleId_update"/>
                                    <input type="text" class="form-control" name="txtname_coorporate_principle_update" id="txtname_coorporate_principle_update" placeholder="Nama Principle" required/>
                                    <div class="invalid-feedback invalid_nama_corporate_principle_update"></div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="form-group txtaddress_coorporate_principle_update">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" rows="3" name="txtaddress_coorporate_principle_update" id="txtaddress_coorporate_principle_update" placeholder="Alamat Principle" required></textarea>
                            <div class="invalid-feedback invalid_alamat_corporate_principle_update"></div>
                        </div>

                        <!-- <div class="form-group listcoorporate-group">
                            <label>Coorporate Group</label>
                            <select class="form-control" name="listcoorporate-group" id="listcoorporate-group">
                                <option value="">--Pilih Coorporate Group--</option>
                            </select>
                        </div> -->

                        <div class="form-group txtphone_coorporate_principle_update">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone_coorporate_principle_update" id="txtphone_coorporate_principle_update" placeholder="Telepon Principle" required/>
                            <div class="invalid-feedback invalid_telepon_corporate_update"></div>
                        </div>

                        <div class="form-group listcoorporate_province_principle_update">
                            <label>Provinsi</label>
                            <select class="form-control" name="listcoorporate_province_principle_update" id="listcoorporate_province_principle_update" required>
                                <option value="">--Pilih Provinsi--</option>
                            </select>
                            <div class="invalid-feedback invalid_provinsi_corporate_principle_update"></div>
                        </div>

                        <div class="form-group listcoorporate_city_principle_update">
                            <label>Kota</label>
                            <select class="form-control" name="listcoorporate_city_principle_update" id="listcoorporate_city_principle_update" required>
                                <option value="">--Pilih Kota--</option>
                            </select>
                            <div class="invalid-feedback invalid_kota_corporate_principle_update"></div>
                        </div>

                        <div class="form-group listcoorporate_districts_principle_update">
                            <label>Kecamatan</label>
                            <select class="form-control" name="listcoorporate_districts_principle_update" id="listcoorporate_districts_principle_update" required>
                                <option value="">--Pilih Kecamatan--</option>
                            </select>
                            <div class="invalid-feedback invalid_kecamatan_corporate_principle_update"></div>
                            <input type="hidden" id="data_districts_principle_update"/>
                        </div>

                        <div class="form-group listcoorporate_ward_principle_update">
                            <label>Kelurahan</label>
                            <select class="form-control" name="listcoorporate_ward_principle_update" id="listcoorporate_ward_principle_update" required>
                                <option value="">--Pilih Kelurahan--</option>
                            </select>
                            <div class="invalid-feedback invalid_kelurahan_corporate_principle_update"></div>
                            <input type="hidden" id="data_ward_principle_update"/>
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtpostalcode_coorporate_principle_update">
                                    <label>Kode Pos</label>
                                    <input type="text" class="form-control" name="txtpostalcode_coorporate_principle_update" id="txtpostalcode_coorporate_principle_update" placeholder="Kode Pos sesuai alamat" required/>
                                    <div class="invalid-feedback invalid_kode_pos_corporate_principle_update"></div>
                                </div>

                                <div class="form-group listcoorporate_stretclass_principle_update">
                                    <label>Kelas Jalan Berdasarkan barang muatan</label>
                                    <select class="form-control" name="listcoorporate_stretclass_principle_update" id="listcoorporate_stretclass_principle_update" required>
                                        <option value="">--Pilih Kelas Jalan--</option>
                                    </select>
                                    <div class="invalid-feedback invalid_kelas_jalan_corporate_principle_update"></div>
                                </div>

                                <div class="form-group txtlattitude_coorporate_principle_update">
                                    <label>Lattitude</label>
                                    <input type="text" class="form-control" name="txtlattitude_coorporate_principle_update" id="txtlattitude_coorporate_principle_update" placeholder="Lattitude Principle" required/>
                                    <div class="invalid-feedback invalid_lattitude_corporate_principle_update"></div>
                                </div>

                                <div class="form-group">
                                    <label>Principle Brand</label>
                                    <input type="text" class="form-control" name="tambah_principle_brand_update" class="tambah_principle_brand_update" id="tambah_principle_brand_update" placeholder="Nama brand"/>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group listcoorporate_area_principle_update">
                                    <label>Area</label>
                                    <select class="form-control" name="listcoorporate_area_principle_update" id="listcoorporate_area_principle_update" required>
                                        <option value="">--Pilih Area--</option>
                                    </select>
                                    <div class="invalid-feedback invalid_area_corporate_principle_update"></div>
                                </div>

                                <div class="form-group listcoorporate_stretclass2_principle_update">
                                    <label>Kelas Jalan Berdasarkan fungsi jalan</label>
                                    <select class="form-control" name="listcoorporate_stretclass2_principle_update" id="listcoorporate_stretclass2_principle_update" required>
                                        <option value="">--Pilih Kelas Jalan--</option>
                                    </select>
                                    <div class="invalid-feedback invalid_kelas_jalan2_corporate_principle_update"></div>
                                </div>
                                
                                <div class="form-group txtlongitude_coorporate_principle_update">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" name="txtlongitude_coorporate_principle_update" id="txtlongitude_coorporate_principle_update" placeholder="Longitude Principle" required/>
                                    <div class="invalid-feedback invalid_longitude_corporate_principle_update"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <h5 class="text-center badge badge-info">Contact Person</h5>

                        <div class="form-group txtname_contact_person_principle_update">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname_contact_person_principle_update" id="txtname_contact_person_principle_update" placeholder="Nama Contact Person" required/>
                            <div class="invalid-feedback invalid_nama_contact_person_principle_update"></div>
                        </div>

                        <div class="form-group txtphone_contact_person_principle_update">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone_contact_person_principle_update" id="txtphone_contact_person_principle_update" placeholder="Telepon Contact Person" required/>
                            <div class="invalid-feedback invalid_telepon_contact_person_principle_update"></div>
                        </div>

                        <div class="form-group txtkreditlimit_contact_person_principle_update">
                            <label>Kredit Limit</label>
                            <input type="text" class="form-control" name="txtkreditlimit_contact_person_principle_update" id="txtkreditlimit_contact_person_principle_update" placeholder="Kredit Limit Contact Person" required/>
                            <div class="invalid-feedback invalid_kredit_limit_contact_person_principle_update"></div>
                        </div>

                        <!-- <div class="form-group">
                            <input type="checkbox" class="form-check-input" id="iskoordinat_update">
                            <label class="form-check-label" for="iskoordinat_update">Titik Ambil Sesuai Alamat?</label>
                        </div>
                        <div id="showiskoordinat_update">
                            <div class="form-group listaddressget_contact_person_principle_update">
                                <label for="listaddressget_contact_person_principle_update">Alamat Ambil</label>
                                <select class="form-control" name="listaddressget_contact_person_principle_update" id="listaddressget_contact_person_principle_update" required>
                                    <option value="">--Pilih Alamat--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <div class="invalid-feedback invalid_addressget_contact_person_principle_update"></div>
                            </div>
                            <div class="form-group">
                                <textarea type="text" class="form-control mt-1" rows="3" name="txtaddressget_contact_person_principle_update" id="txtaddressget_contact_person_principle_update" placeholder="Superindo Margorejo" required></textarea>
                                <div class="invalid-feedback invalid_address_contact_person_principle_update"></div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label>Waktu Operasional</label>
                            <div class="table-responsive">
                                <table class="table table-striped" id="list_day_operasional_principle_update">
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
                            <input type="checkbox" class="form-check-input" required id="txtstatus_principle_update">
                            <label class="form-check-label" for="txtstatus_principle_update">Status Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div id="list_principle_brand_update"></div>
                </div>
            </div>
            <div class="modal-footer">
            <span id="loadingupdateprinciple" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-success" id="btnsaveupdateprinciple">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackprincipleupdate">Kembali</button>
            </div>
        </div>
    </div>
</div>