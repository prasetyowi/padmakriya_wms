<div class="modal fade" id="previewaddnewperusahaan" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <!-- Modal content-->
        <div class="modal-content modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button <h4 class="modal-title"><label>Tambah Data Perusahaan</label></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <div class="form-group txtname_coorporate_perusahaan">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname_coorporate_perusahaan" id="txtname_coorporate_perusahaan" placeholder="Nama Perusahaan" required />
                            <div class="invalid-feedback invalid_nama_corporate_perusahaan"></div>
                        </div>

                        <div class="form-group txtaddress_coorporate_perusahaan">
                            <label>Alamat</label>
                            <textarea type="text" class="form-control" rows="3" name="txtaddress_coorporate_perusahaan" id="txtaddress_coorporate_perusahaan" placeholder="Alamat Perusahaan" required></textarea>
                            <div class="invalid-feedback invalid_alamat_corporate_perusahaan"></div>
                        </div>

                        <!-- <div class="form-group listcoorporate-group">
                            <label>Coorporate Group</label>
                            <select class="form-control" name="listcoorporate-group" id="listcoorporate-group">
                                <option value="">--Pilih Coorporate Group--</option>
                            </select>
                        </div> -->

                        <div class="form-group txtphone_coorporate_perusahaan">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone_coorporate_perusahaan" id="txtphone_coorporate_perusahaan" placeholder="Telepon Perusahaan" required />
                            <div class="invalid-feedback invalid_telepon_corporate"></div>
                        </div>

                        <div class="form-group listcoorporate_province_perusahaan">
                            <label>Provinsi</label>
                            <select class="select2 form-control" name="listcoorporate_province_perusahaan" id="listcoorporate_province_perusahaan" required>

                            </select>
                            <div class="invalid-feedback invalid_provinsi_corporate_perusahaan"></div>
                        </div>

                        <div class="form-group listcoorporate_city_perusahaan">
                            <label>Kota</label>
                            <select class="select2 form-control" name="listcoorporate_city_perusahaan" id="listcoorporate_city_perusahaan" required>
                            </select>
                            <div class="invalid-feedback invalid_kota_corporate_perusahaan"></div>
                        </div>

                        <div class="form-group listcoorporate_districts_perusahaan">
                            <label>Kecamatan</label>
                            <select class="select2 form-control" name="listcoorporate_districts_perusahaan" id="listcoorporate_districts_perusahaan" required>
                            </select>
                            <div class="invalid-feedback invalid_kecamatan_corporate_perusahaan"></div>
                            <input type="hidden" id="data_districts_perusahaan" />
                        </div>

                        <div class="form-group listcoorporate_ward_perusahaan">
                            <label>Kelurahan</label>
                            <select class="select2 form-control" name="listcoorporate_ward_perusahaan" id="listcoorporate_ward_perusahaan" required>
                            </select>
                            <div class="invalid-feedback invalid_kelurahan_corporate_perusahaan"></div>
                            <input type="hidden" id="data_ward_perusahaan" />
                        </div>

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtpostalcode_coorporate_perusahaan">
                                    <label>Kode Pos</label>
                                    <input type="text" class="form-control" name="txtpostalcode_coorporate_perusahaan" id="txtpostalcode_coorporate_perusahaan" placeholder="Kode Pos sesuai alamat" required />
                                    <div class="invalid-feedback invalid_kode_pos_corporate_perusahaan"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group listcoorporate_area_perusahaan">
                                    <label>Area</label>
                                    <select class="select2 form-control" name="listcoorporate_area_perusahaan" id="listcoorporate_area_perusahaan" required>
                                    </select>
                                    <div class="invalid-feedback invalid_area_corporate_perusahaan"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group listcoorporate_stretclass_perusahaan">
                                    <label>Kelas Jalan Berdasarkan barang muatan</label>
                                    <select class="select2 form-control" name="listcoorporate_stretclass_perusahaan" id="listcoorporate_stretclass_perusahaan" required>
                                    </select>
                                    <div class="invalid-feedback invalid_kelas_jalan_corporate_perusahaan"></div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group listcoorporate_stretclass2_perusahaan">
                                    <label>Kelas Jalan Berdasarkan Fungsi jalan</label>
                                    <select class="select2 form-control" name="listcoorporate_stretclass2_perusahaan" id="listcoorporate_stretclass2_perusahaan" required>
                                    </select>
                                    <div class="invalid-feedback invalid_kelas_jalan2_corporate_perusahaan"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group txtpelanggan_coorporate_perusahaan">
                            <label>Pelanggan</label>
                            <select class="form-control" name="txtpelanggan_coorporate_perusahaan" id="txtpelanggan_coorporate_perusahaan" required>
                            </select>
                            <div class="invalid-feedback invalid_pelanggan_corporate_perusahaan"></div>
                        </div>

                        <div class="form-group txtprinciple_coorporate_perusahaan">
                            <label>Principle</label>
                            <select class="form-control" name="txtprinciple_coorporate_perusahaan" id="txtprinciple_coorporate_perusahaan" required>
                            </select>
                            <div class="invalid-feedback invalid_principle_corporate_perusahaan"></div>
                        </div>

                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                        <h5 class="text-center badge badge-info">Contact Person</h5>

                        <div class="form-group txtname_contact_person_perusahaan">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="txtname_contact_person_perusahaan" id="txtname_contact_person_perusahaan" placeholder="Nama Contact Person" required />
                            <div class="invalid-feedback invalid_nama_contact_person_perusahaan"></div>
                        </div>

                        <div class="form-group txtphone_contact_person_perusahaan">
                            <label>Telepon</label>
                            <input type="text" class="form-control numeric" name="txtphone_contact_person_perusahaan" id="txtphone_contact_person_perusahaan" placeholder="Telepon Contact Person" required />
                            <div class="invalid-feedback invalid_telepon_contact_person_perusahaan"></div>
                        </div>

                        <div class="form-group txtketerangan_contact_person_perusahaan">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="txtketerangan_contact_person_perusahaan" id="txtketerangan_contact_person_perusahaan" placeholder="Keterangan Contact Person" required />
                            <div class="invalid-feedback invalid_keterangan_contact_person_perusahaan"></div>
                        </div>

                        <!-- <div class="form-group">
                            <input type="checkbox" class="form-check-input" id="iskoordinat_perusahaan">
                            <label class="form-check-label" for="iskoordinat_perusahaan">Titik Ambil Sesuai Alamat?</label>
                        </div>
                        <div id="showiskoordinat_perusahaan">
                            <div class="form-group listaddressget_contact_person_perusahaan">
                                <label for="listaddressget_contact_person_perusahaan">Alamat Ambil</label>
                                <select class="form-control" name="listaddressget_contact_person_perusahaan" id="listaddressget_contact_person_perusahaan" required>
                                    <option value="">--Pilih Alamat--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <div class="invalid-feedback invalid_addressget_contact_person_perusahaan"></div>
                            </div>
                            <div class="form-group">
                                <textarea type="text" class="form-control mt-1" rows="3" name="txtaddressget_contact_person_perusahaan" id="txtaddressget_contact_person_perusahaan" placeholder="Superindo Margorejo" required></textarea>
                                <div class="invalid-feedback invalid_address_contact_person_perusahaan"></div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label>Waktu Operasional</label>
                            <div class="table-responsive">
                                <table class="table table-striped" id="list_day_operasional_perusahaan">
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
                            <input type="checkbox" class="form-check-input" checked required id="txtstatus_perusahaan">
                            <label class="form-check-label" for="txtstatus_perusahaan">Status Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div id="list_perusahaan_brand"></div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="loadingaddperusahaan" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-success" id="btnsaveaddnewperusahaan">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnbackperusahaan">Kembali</button>
            </div>
        </div>
    </div>
</div>