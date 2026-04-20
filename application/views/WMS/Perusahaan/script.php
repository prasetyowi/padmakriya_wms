<script type="text/javascript">
    function url_like(url) {
        return base_url + url;
    }

    function main_page() {
        setTimeout(() => {
            window.location = url_like("Perusahaan/PerusahaanMenu");
        }, 2000);
    }

    $(document).ready(function() {
        $(".select2").select2({
            width: "100%"
        });
        $("#txtpelanggan_coorporate_perusahaan_update, #txtprinciple_coorporate_perusahaan_update").each(function() {
            $(this).select2({
                placeholder: 'Select data',
                closeOnSelect: false,
                multiple: true,
                width: '100%'
            });
        });
        let PerusahaanId = location.pathname;
        var id = PerusahaanId.substring(PerusahaanId.lastIndexOf('/') + 1);
        // console.log(id);
        $.ajax({
            type: "POST",
            url: "<?= base_url('Perusahaan/GetDataByIdForEdit') ?>",
            data: {
                id: id
            },
            dataType: "json",
            async: "true",
            success: function(data) {
                // let result = JSON.stringify(data);
                let data_arr = '';
                if (data != null) {
                    let remove0 = data[0];
                    data_arr = remove0;
                }

                //append dropdown provinsi
                let provinsi_db = data_arr == null ? null : data_arr.provinsi;
                let db_kota = data_arr == null ? null : data_arr.kota;
                let db_kecamatan = data_arr == null ? null : data_arr.kode_pos_id;
                let db_kelurahan = data_arr == null ? null : data_arr.kelurahan;
                let kelas_jalan = data_arr != null ? data_arr.kelas_jalan : null;
                let area = data_arr != null ? data_arr.area : null;
                let kelas_jalan2 = data_arr != null ? data_arr.kelas_jalan2 : null;

                $("#listcoorporate_province_perusahaan_update").change(function() {
                    let id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Perusahaan/Get_Request_Data_Kota') ?>",
                        data: {
                            id: id,
                            kota: db_kota
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_city_perusahaan_update").html(response);
                        }
                    });
                });

                $("#listcoorporate_city_perusahaan_update").change(function() {
                    let kotaId = $(this).val();
                    let id = kotaId == null ? db_kota : kotaId;
                    let provinsi = $("#listcoorporate_province_perusahaan_update").val();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Perusahaan/Get_Request_Data_Kecamatan') ?>",
                        data: {
                            id: id,
                            provinsi: provinsi,
                            kecamatan: db_kecamatan
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_districts_perusahaan_update").html(response).trigger('change');
                        }
                    });
                });

                $("#listcoorporate_districts_perusahaan_update").change(function() {
                    let kecamatanId = $(this).val();
                    let id = kecamatanId == null ? db_kecamatan : kecamatanId;
                    let nama_kecamatan = $('#listcoorporate_districts_perusahaan_update option').filter(':selected').text();
                    let kota = $('#listcoorporate-city-update option').filter(':selected').val();
                    $("#data_districts_perusahaan_update").val(nama_kecamatan);
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Perusahaan/Get_Request_Data_Kelurahan') ?>",
                        data: {
                            id: id,
                            kelurahan: db_kelurahan
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_ward_perusahaan_update").html(response).trigger("change");
                        }
                    });
                });

                $("#listcoorporate_ward_perusahaan_update").change(function() {
                    let kelurahan = $(this).val();
                    let nama_kelurahan = $('#listcoorporate_ward_perusahaan_update option').filter(':selected').text();
                    $("#data_ward_perusahaan_update").val(nama_kelurahan);
                    $("#txtpostalcode_coorporate_perusahaan_update").val(kelurahan);
                });

            }
        });

        AppendSelect2PelangganUpdate(id);
        AppendSelect2PrincipleUpdate(id);
        $.ajax({
            type: "POST",
            url: "<?= base_url('Perusahaan/Get_Data_Perusahaan_By_Id') ?>",
            data: {
                PerusahaanId: id
            },
            dataType: "JSON",
            async: "true",
            success: function(response) {
                $.each(response, function(i, v) {
                    // console.log(v.kelas_jalan2);
                    $("#txthidePerusahaanId_update").val(i);
                    $("#txtname_coorporate_perusahaan_update").val(v.nama);
                    $("#txtaddress_coorporate_perusahaan_update").val(v.alamat);
                    $("#txtphone_coorporate_perusahaan_update").val(v.telepon);

                    $("#listcoorporate_province_perusahaan_update").val(v.provinsi).trigger('change');
                    $("#listcoorporate_city_perusahaan_update").val(v.kota).trigger('change');
                    $("#listcoorporate_district_perusahaan_update").val(v.kecamatan).trigger('change');
                    $("#listcoorporate_ward_perusahaan_update").val(v.kelurahan).trigger('change');
                    $("#txtpostalcode_coorporate_perusahaan_update").val(v.kodepos).trigger('change');

                    $("#listcoorporate_stretclass_perusahaan_update").val(v.kelas_jalan).trigger('change');
                    $("#listcoorporate_stretclass2_perusahaan_update").val(v.kelas_jalan2).trigger('change');
                    $("#listcoorporate_area_perusahaan_update").val(v.area).trigger('change');

                    $("#txtname_contact_person_perusahaan_update").val(v.nama_cp);
                    $("#txtphone_contact_person_perusahaan_update").val(v.telepon_cp);
                    $("#txtketerangan_contact_person_perusahaan_update").val(v.keterangan_cp);
                    if (v.aktif == 1) {
                        $("#txtstatus_perusahaan_update").prop("checked", true);
                    } else {
                        $("#txtstatus_perusahaan_update").prop("checked", false);
                    }
                    $("#list_day_operasional_perusahaan_update > tbody").empty();
                    $.each(v.data, function(index, value) {
                        if (value.id_detail && value.no_urut && value.hari && value.buka && value.tutup && value.status != null) {
                            var buka = value.buka.split(".");
                            var buka1 = buka[0].split(":");
                            var removebuku_1 = buka1.splice(-1);
                            var tutup = value.tutup.split(".");
                            var tutup1 = tutup[0].split(":");
                            var removetutup_1 = tutup1.splice(-1);
                            var select = '';
                            var clock_open = buka1.join(":") != '00:00' ? buka1.join(":") : '';
                            var clock_close = tutup1.join(":") != '00:00' ? tutup1.join(":") : '';
                            if (value.status == 0) {
                                select = "<option value='1'>BUKA</option><option value='0' selected>TUTUP</option>"
                            } else {
                                select = "<option value='1' selected>BUKA</option><option value='0'>TUTUP</option>"
                            }
                            var html = '';
                            var Jam_Buka = '<input type="time" class="from-control" id="jam_buka_perusahaan_update" value="' + clock_open + '" name="jam_buka_perusahaan_update"/>';
                            var Jam_Tutup = '<input type="time" class="from-control" value="' + clock_close + '" id="jam_tutup_perusahaan_update" name="jam_tutup_perusahaan_update"/>';
                            var status = `<select class="form-control" id="status_operasional_perusahaan_update" name="status_operasional_perusahaan_update">` + select + `</select>`;

                            html = html + '<tr>';
                            html = html + '	<td>' + value.no_urut + ' <input type="hidden" id="no_urut_hari_perusahaan_update" name="no_urut_hari_perusahaan_update" value="' + value.no_urut + '"/> <input type="hidden" id="id_detail_perusahaan_update" name="id_detail_perusahaan_update" value="' + value.id_detail + '"/></td>';
                            html = html + '	<td>' + value.hari + ' <input type="hidden" id="nama_hari_perusahaan_update" name="nama_hari_perusahaan_update" value="' + value.hari + '"/></td>';
                            html = html + '	<td>' + Jam_Buka + '</td>';
                            html = html + '	<td>' + Jam_Tutup + '</td>';
                            html = html + '	<td>' + status + '</td>';
                            html = html + '</tr>';
                            $("#list_day_operasional_perusahaan_update > tbody").append(html);
                        } else {
                            $("#list_day_operasional_perusahaan_update > tbody").append('<tr><td colspan="5" class="text-center text-danger">Data Kosong</td></tr>');
                        }

                    });
                });
            }
        });

        $("#btnsaveupdateperusahaan").click(
            function() {
                var perusahaan_id = $("#txthidePerusahaanId_update").val();
                var name_corporate = $("#txtname_coorporate_perusahaan_update");
                var address_corporate = $("#txtaddress_coorporate_perusahaan_update");
                var phone_corporate = $("#txtphone_coorporate_perusahaan_update");
                // var corporate_group = $("#listcoorporate_group_perusahaan_update");
                var stretclass_corporate = $("#listcoorporate_stretclass_perusahaan_update");
                var stretclass2_corporate = $("#listcoorporate_stretclass2_perusahaan_update");
                var area_corporate = $("#listcoorporate_area_perusahaan_update");
                var province = $("#listcoorporate_province_perusahaan_update");
                var city = $("#listcoorporate_city_perusahaan_update");
                // var districts = $("#listcoorporate_districts_perusahaan_update");
                var districts = $("#data_districts_perusahaan_update");
                // var ward = $("#listcoorporate_ward_perusahaan_update");
                var ward = $("#data_ward_perusahaan_update");
                var kodepos_corporate = $("#txtpostalcode_coorporate_perusahaan_update");

                var name_contact_person = $("#txtname_contact_person_perusahaan_update");
                var phone_contact_person = $("#txtphone_contact_person_perusahaan_update");
                var keterangan_contact_person = $("#txtketerangan_contact_person_perusahaan_update");

                let pelanggan = $("#txtpelanggan_coorporate_perusahaan_update");
                let principle = $("#txtprinciple_coorporate_perusahaan_update");
                let status = '';
                if ($("#txtstatus_perusahaan_update").is(':checked')) {
                    status = 1;
                } else {
                    status = 0;
                }

                let id_detail = [];
                let no_urut_hari = [];
                let nama_hari = [];
                let jam_buka = [];
                let jam_tutup = [];
                let status_operasional = [];

                $("input[name='id_detail_perusahaan_update']").each(function(i, v) {
                    id_detail.push($(this).val());
                });

                $("input[name='no_urut_hari_perusahaan_update']").each(function(i, v) {
                    no_urut_hari.push($(this).val());
                });

                $("input[name='nama_hari_perusahaan_update']").each(function(i, v) {
                    nama_hari.push($(this).val());
                });

                $("input[name='jam_buka_perusahaan_update']").each(function(i, v) {
                    jam_buka.push($(this).val());
                });

                $("input[name='jam_tutup_perusahaan_update']").each(function(i, v) {
                    jam_tutup.push($(this).val());
                });

                $("select[name='status_operasional_perusahaan_update']").each(function(i, v) {
                    status_operasional.push($(this).val());
                });


                let final_arr = [];
                for (let i = 0; i < no_urut_hari.length; i++) {
                    final_arr.push({
                        id_detail: id_detail[i],
                        no_urut: no_urut_hari[i],
                        hari: nama_hari[i],
                        buka: jam_buka[i],
                        tutup: jam_tutup[i],
                        status: status_operasional[i]
                    });
                }



                validasi_update(name_corporate, address_corporate, phone_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, keterangan_contact_person)

                $("#loadingupdateperusahaan").show();
                $("#btnsaveupdateperusahaan").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Perusahaan/SaveUpdatePerusahaan') ?>",
                    data: {
                        perusahaan_id: perusahaan_id,
                        name_corporate_update: name_corporate.val(),
                        address_corporate_update: address_corporate.val(),
                        phone_corporate_update: phone_corporate.val(),
                        // corporate_group_update: corporate_group.val(),
                        stretclass_corporate_update: stretclass_corporate.val(),
                        stretclass2_corporate_update: stretclass2_corporate.val(),
                        area_corporate_update: area_corporate.val(),
                        province_update: province.val(),
                        city_update: city.val(),
                        districts_update: districts.val(),
                        ward_update: ward.val(),
                        kodepos_corporate_update: kodepos_corporate.val(),
                        name_contact_person_update: name_contact_person.val(),
                        phone_contact_person_update: phone_contact_person.val(),
                        keterangan_contact_person_update: keterangan_contact_person.val(),
                        timeoperasional_update: final_arr,
                        pelanggan: pelanggan.val(),
                        principle: principle.val(),
                        status: status
                    },
                    async: "true",
                    success: function(response) {
                        $("#loadingupdateperusahaan").hide();
                        $("#btnsaveupdateperusahaan").prop("disabled", false);
                        if (response == 1) {
                            var msg = 'Data Perusahaan berhasil diubah';
                            var msgtype = 'success';

                            Swal.fire(
                                'Success!',
                                msg,
                                msgtype
                            )

                            main_page();
                        } else if (response == 0) {
                            var msg = 'Gagal diubah';
                            var msgtype = 'error';


                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )

                            main_page();
                        } else {
                            var msg = response;
                            var msgtype = 'error';

                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )
                        }

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $("#loadingupdateperusahaan").hide();
                        $("#btnsaveupdateperusahaan").prop("disabled", false);
                    }
                });
            }
        );

        $("#btnbackperusahaanupdate").click(function() {
            window.location = url_like("Perusahaan/PerusahaanMenu");
        });
    });

    function AppendSelect2PelangganUpdate(PerusahaanID) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('Perusahaan/Get_Request_Data_Pelanggan_By_Id') ?>",
            data: {
                PerusahaanID: PerusahaanID
            },
            success: function(response) {
                $("#txtpelanggan_coorporate_perusahaan_update").html(response);
            }
        });
    }

    function AppendSelect2PrincipleUpdate(PerusahaanID) {
        $("#txtprinciple_coorporate_perusahaan_update").empty();
        $.ajax({
            type: 'POST',
            url: "<?= base_url('Perusahaan/Get_Request_Data_Principle_By_Id') ?>",
            data: {
                PerusahaanID: PerusahaanID
            },
            success: function(response) {
                $("#txtprinciple_coorporate_perusahaan_update").html(response);
            }
        });
    }

    function validasi_update(name_corporate, address_corporate, phone_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, keterangan_contact_person) {

        name_corporate.prop("required", true);
        address_corporate.prop("required", true);
        phone_corporate.prop("required", true);
        stretclass_corporate.prop("required", true);
        stretclass2_corporate.prop("required", true);
        area_corporate.prop("required", true);
        province.prop("required", true);
        city.prop("required", true);
        districts.prop("required", true);
        ward.prop("required", true);
        kodepos_corporate.prop("required", true);
        name_contact_person.prop("required", true);
        phone_contact_person.prop("required", true);
        keterangan_contact_person.prop("required", true);

        if (name_corporate.val() == "") {
            $(".txtname_coorporate_perusahaan_update").addClass("has-error");
            $(".invalid_nama_corporate_perusahaan_update").html("Nama Corporate tidak boleh kosong");
            name_corporate.focus();
        } else {
            $(".txtname_coorporate_perusahaan_update").removeClass("has-error");
            $(".invalid_nama_corporate_perusahaan_update").html("");
        }

        if (address_corporate.val() == "") {
            $(".txtaddress_coorporate_perusahaan_update").addClass("has-error");
            $(".invalid_alamat_corporate_perusahaan_update").html("Alamat Corporate tidak boleh kosong");
            address_corporate.focus();
        } else {
            $(".txtaddress_coorporate_perusahaan_update").removeClass("has-error");
            $(".invalid_alamat_corporate_perusahaan_update").html("");
        }

        if (phone_corporate.val() == "") {
            $(".txtphone_coorporate_perusahaan_update").addClass("has-error");
            $(".invalid_telepon_corporate_perusahaan_update").html("Telephon Corporate tidak boleh kosong");
            phone_corporate.focus();
        } else {
            $(".txtphone_coorporate_perusahaan_update").removeClass("has-error");
            $(".invalid_telepon_corporate_perusahaan_update").html("");
        }

        if (stretclass_corporate.val() == "") {
            $(".listcoorporate_stretclass_perusahaan_update").addClass("has-error");
            $(".invalid_kelas_jalan_corporate_perusahaan_update").html("Kelas Jalan Berdasarkan barang muatan tidak boleh kosong");
            stretclass_corporate.focus();
        } else {
            $(".listcoorporate_stretclass_perusahaan_update").removeClass("has-error");
            $(".invalid_kelas_jalan_corporate_perusahaan_update").html("");
        }

        if (stretclass2_corporate.val() == "") {
            $(".listcoorporate_stretclass2_perusahaan_update").addClass("has-error");
            $(".invalid_kelas_jalan2_corporate_perusahaan_update").html("Kelas Jalan Berdasarkan fungsi jalan tidak boleh kosong");
            stretclass2_corporate.focus();
        } else {
            $(".listcoorporate_stretclass2_perusahaan_update").removeClass("has-error");
            $(".invalid_kelas_jalan2_corporate_perusahaan_update").html("");
        }

        if (area_corporate.val() == "") {
            $(".listcoorporate_area_perusahaan_update").addClass("has-error");
            $(".invalid_area_corporate_perusahaan_update").html("Area Corporate tidak boleh kosong");
            area_corporate.focus();
        } else {
            $(".listcoorporate_area_perusahaan_update").removeClass("has-error");
            $(".invalid_area_corporate_perusahaan_update").html("");
        }

        if (province.val() == "") {
            $(".listcoorporate_province_perusahaan_update").addClass("has-error");
            $(".invalid_provinsi_corporate_perusahaan_update").html("Provinsi Corporate tidak boleh kosong");
            province.focus();
        } else {
            $(".listcoorporate_province_perusahaan_update").removeClass("has-error");
            $(".invalid_provinsi_corporate_perusahaan_update").html("");
        }

        if (city.val() == "") {
            $(".listcoorporate_city_perusahaan_update").addClass("has-error");
            $(".invalid_kota_corporate_perusahaan_update").html("Kota Corporate tidak boleh kosong");
            city.focus();
        } else {
            $(".listcoorporate_city_perusahaan_update").removeClass("has-error");
            $(".invalid_kota_corporate_perusahaan_update").html("");
        }

        if (districts.val() == "") {
            $(".listcoorporate_districts_perusahaan_update").addClass("has-error");
            $(".invalid_kecamatan_corporate_perusahaan_update").html("Kecamatan Corporate tidak boleh kosong");
            districts.focus();
        } else {
            $(".listcoorporate_districts_perusahaan_update").removeClass("has-error");
            $(".invalid_kecamatan_corporate_perusahaan_update").html("");
        }

        if (ward.val() == "") {
            $(".listcoorporate_ward_perusahaan_update").addClass("has-error");
            $(".invalid_kelurahan_corporate_perusahaan_update").html("Kelurahan Corporate tidak boleh kosong");
            ward.focus();
        } else {
            $(".listcoorporate_ward_perusahaan_update").removeClass("has-error");
            $(".invalid_kelurahan_corporate_perusahaan_update").html("");
        }

        if (kodepos_corporate.val() == "") {
            $(".txtpostalcode_coorporate_perusahaan_update").addClass("has-error");
            $(".invalid_kode_pos_corporate_perusahaan_update").html("Kode Pos Corporate tidak boleh kosong");
            kodepos_corporate.focus();
        } else {
            $(".txtpostalcode_coorporate_perusahaan_update").removeClass("has-error");
            $(".invalid_kode_pos_corporate_perusahaan_update").html("");
        }

        if (name_contact_person.val() == "") {
            $(".txtname_contact_person_perusahaan_update").addClass("has-error");
            $(".invalid_nama_contact_person_perusahaan_update").html("Nama Contact Person tidak boleh kosong");
            name_contact_person.focus();
        } else {
            $(".txtname_contact_person_perusahaan_update").removeClass("has-error");
            $(".invalid_nama_contact_person_perusahaan_update").html("");
        }

        if (phone_contact_person.val() == "") {
            $(".txtphone_contact_person_perusahaan_update").addClass("has-error");
            $(".invalid_telepon_contact_person_perusahaan_update").html("Telepon Contact Person tidak boleh kosong");
            phone_contact_person.focus();
        } else {
            $(".txtphone_contact_person_perusahaan_update").removeClass("has-error");
            $(".invalid_telepon_contact_person_perusahaan_update").html("");
        }

        if (keterangan_contact_person.val() == "") {
            $(".txtketerangan_contact_person_perusahaan_update").addClass("has-error");
            $(".invalid_keterangan_contact_person_perusahaan_update").html("Keterangan Contact Person tidak boleh kosong");
            keterangan_contact_person.focus();
        } else {
            $(".txtketerangan_contact_person_perusahaan_update").removeClass("has-error");
            $(".invalid_keterangan_contact_person_perusahaan_update").html("");
        }

    }
</script>