<style>
    .invalid-feedback{
        color: red;
    }
</style>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Edit Data</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <!-- <h5>Batch For Pick</h5> -->
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group txtname_coorporate_perusahaan_update">
                                    <label>Nama</label>
                                    <input type="hidden" id="txthidePerusahaanId_update" value="<?= $id ?>"/>
                                    <input type="text" class="form-control" name="txtname_coorporate_perusahaan_update" id="txtname_coorporate_perusahaan_update" placeholder="Nama Perusahaan" required />
                                    <div class="invalid-feedback invalid_nama_corporate_perusahaan_update"></div>
                                </div>

                                <div class="form-group txtaddress_coorporate_perusahaan_update">
                                    <label>Alamat</label>
                                    <textarea type="text" class="form-control" rows="3" name="txtaddress_coorporate_perusahaan_update" id="txtaddress_coorporate_perusahaan_update" placeholder="Alamat Perusahaan" required></textarea>
                                    <div class="invalid-feedback invalid_alamat_corporate_perusahaan_update"></div>
                                </div>

                                <!-- <div class="form-group listcoorporate-group">
                                    <label>Coorporate Group</label>
                                    <select class="form-control" name="listcoorporate-group" id="listcoorporate-group">
                                        <option value="">--Pilih Coorporate Group--</option>
                                    </select>
                                </div> -->

                                <div class="form-group txtphone_coorporate_perusahaan_update">
                                    <label>Telepon</label>
                                    <input type="text" class="form-control numeric" name="txtphone_coorporate_perusahaan_update" id="txtphone_coorporate_perusahaan_update" placeholder="Telepon Perusahaan" required />
                                    <div class="invalid-feedback invalid_telepon_corporate_update"></div>
                                </div>

                                <div class="form-group listcoorporate_province_perusahaan_update">
                                    <label>Provinsi</label>
                                    <select class="select2 form-control" name="listcoorporate_province_perusahaan_update" id="listcoorporate_province_perusahaan_update" required>
                                        <option value="">--Pilih Provinsi--</option>
                                        <?php foreach ($Provinsi as $row) { ?>
                                            <option value="<?= $row['reffregion_nama'] ?>"><?= $row['reffregion_nama'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="invalid-feedback invalid_provinsi_corporate_perusahaan_update"></div>
                                </div>

                                <div class="form-group listcoorporate_city_perusahaan_update">
                                    <label>Kota</label>
                                    <select class="select2 form-control" name="listcoorporate_city_perusahaan_update" id="listcoorporate_city_perusahaan_update" required>
                                    </select>
                                    <div class="invalid-feedback invalid_kota_corporate_perusahaan_update"></div>
                                </div>

                                <div class="form-group listcoorporate_districts_perusahaan_update">
                                    <label>Kecamatan</label>
                                    <select class="select2 form-control" name="listcoorporate_districts_perusahaan_update" id="listcoorporate_districts_perusahaan_update" required>
                                    </select>
                                    <div class="invalid-feedback invalid_kecamatan_corporate_perusahaan_update"></div>
                                    <input type="hidden" id="data_districts_perusahaan_update" />
                                </div>

                                <div class="form-group listcoorporate_ward_perusahaan_update">
                                    <label>Kelurahan</label>
                                    <select class="select2 form-control" name="listcoorporate_ward_perusahaan_update" id="listcoorporate_ward_perusahaan_update" required>
                                    </select>
                                    <div class="invalid-feedback invalid_kelurahan_corporate_perusahaan_update"></div>
                                    <input type="hidden" id="data_ward_perusahaan_update" />
                                </div>

                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group txtpostalcode_coorporate_perusahaan_update">
                                            <label>Kode Pos</label>
                                            <input type="text" class="form-control" name="txtpostalcode_coorporate_perusahaan_update" id="txtpostalcode_coorporate_perusahaan_update" placeholder="Kode Pos sesuai alamat" required />
                                            <div class="invalid-feedback invalid_kode_pos_corporate_perusahaan_update"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group listcoorporate_area_perusahaan_update">
                                            <label>Area</label>
                                            <select class="select2 form-control" name="listcoorporate_area_perusahaan_update" id="listcoorporate_area_perusahaan_update" required>
                                                <option value="">--Pilih Area--</option>
                                                <?php foreach ($Area as $row) { ?>
                                                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback invalid_area_corporate_perusahaan_update"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group listcoorporate_stretclass_perusahaan_update">
                                            <label>Kelas Jalan Berdasarkan barang muatan</label>
                                            <select class="select2 form-control" name="listcoorporate_stretclass_perusahaan_update" id="listcoorporate_stretclass_perusahaan_update" required>
                                                <option value="">--Pilih Kelas Jalan--</option>
                                                <?php foreach ($KelasJalan as $row) { ?>
                                                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback invalid_kelas_jalan_corporate_perusahaan_update"></div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group listcoorporate_stretclass2_perusahaan_update">
                                            <label>Kelas Jalan Berdasarkan fungsi jalan</label>
                                            <select class="select2 form-control" name="listcoorporate_stretclass2_perusahaan_update" id="listcoorporate_stretclass2_perusahaan_update" required>
                                                <option value="">--Pilih Kelas Jalan--</option>
                                                <?php foreach ($KelasJalan2 as $row) { ?>
                                                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="invalid-feedback invalid_kelas_jalan2_corporate_perusahaan_update"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group txtpelanggan_coorporate_perusahaan_update">
                                    <label>Pelanggan</label>
                                    <select class="form-control" name="txtpelanggan_coorporate_perusahaan_update" id="txtpelanggan_coorporate_perusahaan_update" required>
                                    </select>
                                    <div class="invalid-feedback invalid_pelanggan_corporate_perusahaan_update"></div>
                                </div>

                                <div class="form-group txtprinciple_coorporate_perusahaan_update">
                                    <label>Principle</label>
                                    <select class="form-control" name="txtprinciple_coorporate_perusahaan_update" id="txtprinciple_coorporate_perusahaan_update" required>
                                    </select>
                                    <div class="invalid-feedback invalid_principle_corporate_perusahaan_update"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
                                <h5 class="text-center badge badge-info">Contact Person</h5>

                                <div class="form-group txtname_contact_person_perusahaan_update">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="txtname_contact_person_perusahaan_update" id="txtname_contact_person_perusahaan_update" placeholder="Nama Contact Person" required />
                                    <div class="invalid-feedback invalid_nama_contact_person_perusahaan_update"></div>
                                </div>

                                <div class="form-group txtphone_contact_person_perusahaan_update">
                                    <label>Telepon</label>
                                    <input type="text" class="form-control numeric" name="txtphone_contact_person_perusahaan_update" id="txtphone_contact_person_perusahaan_update" placeholder="Telepon Contact Person" required />
                                    <div class="invalid-feedback invalid_telepon_contact_person_perusahaan_update"></div>
                                </div>

                                <div class="form-group txtketerangan_contact_person_perusahaan_update">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control" name="txtketerangan_contact_person_perusahaan_update" id="txtketerangan_contact_person_perusahaan_update" placeholder="Keterangan Contact Person" required />
                                    <div class="invalid-feedback invalid_keterangan_contact_person_perusahaan_update"></div>
                                </div>

                                <!-- <div class="form-group">
                                    <input type="checkbox" class="form-check-input" id="iskoordinat_update">
                                    <label class="form-check-label" for="iskoordinat_update">Titik Ambil Sesuai Alamat?</label>
                                </div>
                                <div id="showiskoordinat_update">
                                    <div class="form-group listaddressget_contact_person_perusahaan_update">
                                        <label for="listaddressget_contact_person_perusahaan_update">Alamat Ambil</label>
                                        <select class="form-control" name="listaddressget_contact_person_perusahaan_update" id="listaddressget_contact_person_perusahaan_update" required>
                                            <option value="">--Pilih Alamat--</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                        <div class="invalid-feedback invalid_addressget_contact_person_perusahaan_update"></div>
                                    </div>
                                    <div class="form-group">
                                        <textarea type="text" class="form-control mt-1" rows="3" name="txtaddressget_contact_person_perusahaan_update" id="txtaddressget_contact_person_perusahaan_update" placeholder="Superindo Margorejo" required></textarea>
                                        <div class="invalid-feedback invalid_address_contact_person_perusahaan_update"></div>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label>Waktu Operasional</label>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="list_day_operasional_perusahaan_update">
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
                                    <input type="checkbox" class="form-check-input" required id="txtstatus_perusahaan_update">
                                    <label class="form-check-label" for="txtstatus_perusahaan_update">Status Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <button class="btn btn-success" type="button" id="btnsaveupdateperusahaan">Ubah</button>
                            <span id="loadingupdateperusahaan" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                            <button class="btn btn-danger" type="button" id="btnbackperusahaanupdate">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>