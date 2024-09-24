<script type="text/javascript">
    function url_like(url) {
        return base_url + url;
    }

    function main_page() {
        setTimeout(() => {
            window.location = url_like("Principle/PrincipleMenu");
        }, 2000);
    }

    $(document).ready(function() {
        $(".select2").select2({
            width: "100%"
        });
        let PrincipleId = location.pathname;
        var id = PrincipleId.substring(PrincipleId.lastIndexOf('/') + 1);
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/GetDataByIdForEdit') ?>",
            data: {
                id: id
            },
            dataType: "json",
            async: "true",
            success: function(data) {
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

                $("#listcoorporate_city_principle_update").empty();
                $("#listcoorporate_province_principle_update").change(function() {
                    let id = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Principle/Get_Request_Data_Kota') ?>",
                        data: {
                            id: id,
                            kota: db_kota
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_city_principle_update").html(response);
                        }
                    });
                });

                $("#listcoorporate_districts_principle_update").empty();
                $("#listcoorporate_city_principle_update").change(function() {
                    let kotaId = $(this).val();
                    let id = kotaId == null ? db_kota : kotaId;
                    let provinsi = $("#listcoorporate_province_principle_update").val();
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Principle/Get_Request_Data_Kecamatan') ?>",
                        data: {
                            id: id,
                            provinsi: provinsi,
                            kecamatan: db_kecamatan
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_districts_principle_update").html(response).trigger('change');
                        }
                    });
                });

                $("#listcoorporate_ward_principle_update").empty();
                $("#listcoorporate_districts_principle_update").on("change", function() {
                    let kecamatanId = $(this).val();
                    let id = kecamatanId == null ? db_kecamatan : kecamatanId;
                    // console.log(db_kelurahan);
                    let nama_kecamatan = $('#listcoorporate_districts_principle_update option').filter(':selected').text();
                    let kota = $('#listcoorporate-city-update option').filter(':selected').val();
                    $("#data_districts_principle_update").val(nama_kecamatan);
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('Principle/Get_Request_Data_Kelurahan') ?>",
                        data: {
                            id: id,
                            kelurahan: db_kelurahan
                        },
                        dataType: "JSON",
                        success: function(response) {
                            $("#listcoorporate_ward_principle_update").html(response).trigger("change");
                        }
                    });
                });

                $("#listcoorporate_ward_principle_update").on("change", function() {
                    let kelurahan = $(this).val();
                    let nama_kelurahan = $('#listcoorporate_ward_principle_update option').filter(':selected').text();
                    $("#data_ward_principle_update").val(nama_kelurahan);
                    $("#txtpostalcode_coorporate_principle_update").val(kelurahan);
                });

                $("#tambah_principle_brand_update").on("keypress", function(event) {
                    let text = $(this);
                    AppendListPrincipleBrand(event, text);
                });
            }
        });

        // let arr_sku_principle_id = [];
        // $.ajax({
        //     type: "GET",
        //     url: "<?= base_url('Principle/get_data_sku_by_principle_brand') ?>",
        //     dataType: "JSON",
        //     success: function(response) {
        //         arr_sku_principle_id.push(response);
        //     }
        // });

        // console.log(arr_sku_principle_id);

        get_data_principle_brand_detail(id);
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/Get_Data_Principle_By_Id') ?>",
            data: {
                PrincipleId: id
            },
            dataType: "JSON",
            async: "true",
            success: function(response) {
                $.each(response, function(i, v) {
                    $("#txthidePrincipleId_update").val(i);
                    $("#txtkode_coorporate_principle_update").val(v.kode);
                    $("#txtname_coorporate_principle_update").val(v.nama);
                    $("#txtaddress_coorporate_principle_update").val(v.alamat);
                    $("#txtphone_coorporate_principle_update").val(v.telepon);

                    $("#listcoorporate_province_principle_update").val(v.provinsi).trigger('change');
                    $("#listcoorporate_city_principle_update").val(v.kota).trigger('change');
                    $("#listcoorporate_district_principle_update").val(v.kecamatan).trigger('change');
                    $("#listcoorporate_ward_principle_update").val(v.kelurahan).trigger('change');
                    $("#txtpostalcode_coorporate_principle_update").val(v.kodepos).trigger('change');

                    $("#listcoorporate_stretclass_principle_update").val(v.kelas_jalan).trigger('change');
                    $("#listcoorporate_stretclass2_principle_update").val(v.kelas_jalan2).trigger('change');
                    $("#listcoorporate_area_principle_update").val(v.area).trigger('change');

                    $("#txtlattitude_coorporate_principle_update").val(v.lattitude);
                    $("#txtlongitude_coorporate_principle_update").val(v.longitude);
                    $("#txtname_contact_person_principle_update").val(v.nama_cp);
                    $("#txtphone_contact_person_principle_update").val(v.telepon_cp);
                    $("#txtkreditlimit_contact_person_principle_update").val(v.kredit_limit_cp);
                    if (v.aktif == 1) {
                        $("#txtstatus_principle_update").prop("checked", true);
                    } else {
                        $("#txtstatus_principle_update").prop("checked", false);
                    }
                    $("#list_day_operasional_principle_update > tbody").empty();
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
                            var Jam_Buka = '<input type="time" class="from-control" id="jam_buka_principle_update" value="' + clock_open + '" name="jam_buka_principle_update"/>';
                            var Jam_Tutup = '<input type="time" class="from-control" value="' + clock_close + '" id="jam_tutup_principle_update" name="jam_tutup_principle_update"/>';
                            var status = `<select class="form-control" id="status_operasional_principle_update" name="status_operasional_principle_update">` + select + `</select>`;

                            html = html + '<tr>';
                            html = html + '	<td>' + value.no_urut + ' <input type="hidden" id="no_urut_hari_principle_update" name="no_urut_hari_principle_update" value="' + value.no_urut + '"/> <input type="hidden" id="id_detail_principle_update" name="id_detail_principle_update" value="' + value.id_detail + '"/></td>';
                            html = html + '	<td>' + value.hari + ' <input type="hidden" id="nama_hari_principle_update" name="nama_hari_principle_update" value="' + value.hari + '"/></td>';
                            html = html + '	<td>' + Jam_Buka + '</td>';
                            html = html + '	<td>' + Jam_Tutup + '</td>';
                            html = html + '	<td>' + status + '</td>';
                            html = html + '</tr>';

                            $("#list_day_operasional_principle_update > tbody").append(html);
                        } else {
                            $("#list_day_operasional_principle_update > tbody").append(`<tr><td colspan="5" class="text-center text-danger">Data Kosong</td></tr>`);
                        }
                    });
                });
            }
        });

        $("#btnsaveupdateprinciple").click(
            function() {
                var principle_id = $("#txthidePrincipleId_update").val();
                var kode_corporate = $("#txtkode_coorporate_principle_update");
                var name_corporate = $("#txtname_coorporate_principle_update");
                var address_corporate = $("#txtaddress_coorporate_principle_update");
                var phone_corporate = $("#txtphone_coorporate_principle_update");
                // var corporate_group = $("#listcoorporate_group_principle_update");
                var lattitude_corporate = $("#txtlattitude_coorporate_principle_update");
                var longitude_corporate = $("#txtlongitude_coorporate_principle_update");
                var stretclass_corporate = $("#listcoorporate_stretclass_principle_update");
                var stretclass2_corporate = $("#listcoorporate_stretclass2_principle_update");
                var area_corporate = $("#listcoorporate_area_principle_update");
                var province = $("#listcoorporate_province_principle_update");
                var city = $("#listcoorporate_city_principle_update");
                // var districts = $("#listcoorporate_districts_principle_update");
                var districts = $("#data_districts_principle_update");
                // var ward = $("#listcoorporate_ward_principle_update");
                var ward = $("#data_ward_principle_update");
                var kodepos_corporate = $("#txtpostalcode_coorporate_principle_update");

                var name_contact_person = $("#txtname_contact_person_principle_update");
                var phone_contact_person = $("#txtphone_contact_person_principle_update");
                var kreditlimit_contact_person = $("#txtkreditlimit_contact_person_principle_update");
                let status = '';
                if ($("#txtstatus_principle_update").is(':checked')) {
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
                let principle_brand_id = [];
                let principle_brand = [];

                $("input[name='id_detail_principle_update']").each(function(i, v) {
                    id_detail.push($(this).val());
                });

                $("input[name='no_urut_hari_principle_update']").each(function(i, v) {
                    no_urut_hari.push($(this).val());
                });

                $("input[name='nama_hari_principle_update']").each(function(i, v) {
                    nama_hari.push($(this).val());
                });

                $("input[name='jam_buka_principle_update']").each(function(i, v) {
                    jam_buka.push($(this).val());
                });

                $("input[name='jam_tutup_principle_update']").each(function(i, v) {
                    jam_tutup.push($(this).val());
                });

                $("select[name='status_operasional_principle_update']").each(function(i, v) {
                    status_operasional.push($(this).val());
                });

                $("input[name='txthidePrincipleBrandId_update']").each(function(i, v) {
                    principle_brand_id.push($(this).val());
                    console.log($(this).val());
                });

                $("input[name='data_principle_brand_update']").each(function(i, v) {
                    principle_brand.push($(this).val());
                    // console.log($(this).val());
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

                // console.log(principle_brand_id);

                validasi_update(kode_corporate, name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person)

                $("#loadingupdateprinciple").show();
                $("#btnsaveupdateprinciple").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Principle/SaveUpdatePrinciple') ?>",
                    data: {
                        principle_id: principle_id,
                        principle_brand_id: principle_brand_id,
                        principle_brand: principle_brand,
                        kode_corporate_update: kode_corporate.val(),
                        name_corporate_update: name_corporate.val(),
                        address_corporate_update: address_corporate.val(),
                        phone_corporate_update: phone_corporate.val(),
                        // corporate_group_update: corporate_group.val(),
                        lattitude_corporate_update: lattitude_corporate.val(),
                        longitude_corporate_update: longitude_corporate.val(),
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
                        kreditlimit_contact_person_update: kreditlimit_contact_person.val(),
                        timeoperasional_update: final_arr,
                        status: status
                    },
                    async: "true",
                    success: function(response) {
                        // console.log(response);
                        $("#loadingupdateprinciple").hide();
                        $("#btnsaveupdateprinciple").prop("disabled", false);
                        if (response == 1) {
                            var msg = 'Data Principle berhasil diubah';
                            var msgtype = 'success';

                            Swal.fire(
                                'Success!',
                                msg,
                                msgtype
                            )

                            main_page();
                        } else if (response == 0) {
                            var msg = 'Gagal mengubah';
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
                        $("#loadingupdateprinciple").hide();
                        $("#btnsaveupdateprinciple").prop("disabled", false);
                    }
                });
            }
        );

        $("#btnbackprincipleupdate").click(function() {
            window.location = url_like("Principle/PrincipleMenu");
        });
    });

    function get_data_principle_brand_detail(PrincipleId) {
        let PrincipleId_ = PrincipleId;
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/Get_Data_Principle_Brand_By_Id') ?>",
            data: {
                PrincipleId: PrincipleId_
            },
            dataType: "json",
            // async: "true",
            success: function(response) {
                // console.log(response);
                $("#list_principle_brand_update").empty();
                $("#list_principle_brand_update").append(response);
                // $.each(response, function(i, v) {

                // });
                WidthDynamicAppend();
            }
        });
    }

    function AppendListPrincipleBrand(event, text) {
        let text_ = text;
        if (event.key == "Enter") {
            event.preventDefault();
            $("#list_principle_brand_update").append(`
                <div class="new-row">
                    <button type="button" class="principle-brand-btn">
                        <span></span><text class="check-length">` + text.val() + `</text>
                    </button><input type="hidden" name="data_principle_brand_update" class="form-control" value="` + text.val() + `" />
                </div>
            `);
            WidthDynamicAppend();

            // WidthDynamicAppend();
            text.val("");
            text.focus();
            text[0].scrollIntoView({
                behavior: "smooth",
            });
        }
    }

    function WidthDynamicAppend() {
        let length_text_btn = $(".check-length");
        let arr = [];
        $(".new-row").each(function() {
            let currentRow = $(this);
            let element = currentRow.find(".principle-brand-btn");
            arr.push(element);
        });
        check_lenght_principle(arr);
    }

    function check_lenght_principle(length_text_btn) {
        let data = length_text_btn;
        $.each(data, function(i, v) {
            if (v.length >= 0) {
                let row = $(this).find(".check-length");
                let width = row.width() + 100;
                $(this).css("width", width + "px");
            }
        });

    }

    $("#list_principle_brand_update").on("click", '.principle-brand-btn', function() {
        $(this).addClass("active");
        setTimeout(() => {
            $(this).removeClass("active");
            $(this).parents(".new-row").remove();
        }, 1000);
    });

    function validasi_update(kode_corporate, name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person) {

        kode_corporate.prop("required", true);
        name_corporate.prop("required", true);
        address_corporate.prop("required", true);
        phone_corporate.prop("required", true);
        lattitude_corporate.prop("required", true);
        longitude_corporate.prop("required", true);
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
        kreditlimit_contact_person.prop("required", true);

        if (kode_corporate.val() == "") {
            $(".txtkode_coorporate_principle_update").addClass("has-error");
            $(".invalid_kode_corporate_principle_update").html("Kode Principle tidak boleh kosong");
            kode_corporate.focus();
        } else {
            $(".txtname_coorporate_principle_update").removeClass("has-error");
            $(".invalid_kode_corporate_principle_update").html("");
        }

        if (name_corporate.val() == "") {
            $(".txtname_coorporate_principle_update").addClass("has-error");
            $(".invalid_nama_corporate_principle_update").html("Nama Principle tidak boleh kosong");
            name_corporate.focus();
        } else {
            $(".txtname_coorporate_principle_update").removeClass("has-error");
            $(".invalid_nama_corporate_principle_update").html("");
        }

        if (address_corporate.val() == "") {
            $(".txtaddress_coorporate_principle_update").addClass("has-error");
            $(".invalid_alamat_corporate_principle_update").html("Alamat Principle tidak boleh kosong");
            address_corporate.focus();
        } else {
            $(".txtaddress_coorporate_principle_update").removeClass("has-error");
            $(".invalid_alamat_corporate_principle_update").html("");
        }

        if (phone_corporate.val() == "") {
            $(".txtphone_coorporate_principle_update").addClass("has-error");
            $(".invalid_telepon_corporate_principle_update").html("Telephon Principle tidak boleh kosong");
            phone_corporate.focus();
        } else {
            $(".txtphone_coorporate_principle_update").removeClass("has-error");
            $(".invalid_telepon_corporate_principle_update").html("");
        }

        if (lattitude_corporate.val() == "") {
            $(".txtlattitude_coorporate_principle_update").addClass("has-error");
            $(".invalid_lattitude_corporate_principle_update").html("Lattitude Principle tidak boleh kosong");
            lattitude_corporate.focus();
        } else {
            $(".txtlattitude_coorporate_principle_update").removeClass("has-error");
            $(".invalid_lattitude_corporate_principle_update").html("");
        }

        if (longitude_corporate.val() == "") {
            $(".txtlongitude_coorporate_principle_update").addClass("has-error");
            $(".invalid_longitude_corporate_principle_update").html("Longitude Principle tidak boleh kosong");
            longitude_corporate.focus();
        } else {
            $(".txtlongitude_coorporate_principle_update").removeClass("has-error");
            $(".invalid_longitude_corporate_principle_update").html("");
        }

        if (stretclass_corporate.val() == "") {
            $(".listcoorporate_stretclass_principle_update").addClass("has-error");
            $(".invalid_kelas_jalan_corporate_principle_update").html("Kelas Jalan berdasarkan barang muatan tidak boleh kosong");
            stretclass_corporate.focus();
        } else {
            $(".listcoorporate_stretclass_principle_update").removeClass("has-error");
            $(".invalid_kelas_jalan_corporate_principle_update").html("");
        }

        if (stretclass2_corporate.val() == "") {
            $(".listcoorporate_stretclass2_principle_update").addClass("has-error");
            $(".invalid_kelas_jalan2_corporate_principle_update").html("Kelas Jalan berdasarkan fungsi jalan tidak boleh kosong");
            stretclass2_corporate.focus();
        } else {
            $(".listcoorporate_stretclass2_principle_update").removeClass("has-error");
            $(".invalid_kelas_jalan2_corporate_principle_update").html("");
        }

        if (area_corporate.val() == "") {
            $(".listcoorporate_area_principle_update").addClass("has-error");
            $(".invalid_area_corporate_principle_update").html("Area Principle tidak boleh kosong");
            area_corporate.focus();
        } else {
            $(".listcoorporate_area_principle_update").removeClass("has-error");
            $(".invalid_area_corporate_principle_update").html("");
        }

        if (province.val() == "") {
            $(".listcoorporate_province_principle_update").addClass("has-error");
            $(".invalid_provinsi_corporate_principle_update").html("Provinsi Principle tidak boleh kosong");
            province.focus();
        } else {
            $(".listcoorporate_province_principle_update").removeClass("has-error");
            $(".invalid_provinsi_corporate_principle_update").html("");
        }

        if (city.val() == "") {
            $(".listcoorporate_city_principle_update").addClass("has-error");
            $(".invalid_kota_corporate_principle_update").html("Kota Principle tidak boleh kosong");
            city.focus();
        } else {
            $(".listcoorporate_city_principle_update").removeClass("has-error");
            $(".invalid_kota_corporate_principle_update").html("");
        }

        if (districts.val() == "") {
            $(".listcoorporate_districts_principle_update").addClass("has-error");
            $(".invalid_kecamatan_corporate_principle_update").html("Kecamatan Principle tidak boleh kosong");
            districts.focus();
        } else {
            $(".listcoorporate_districts_principle_update").removeClass("has-error");
            $(".invalid_kecamatan_corporate_principle_update").html("");
        }

        if (ward.val() == "") {
            $(".listcoorporate_ward_principle_update").addClass("has-error");
            $(".invalid_kelurahan_corporate_principle_update").html("Kelurahan Principle tidak boleh kosong");
            ward.focus();
        } else {
            $(".listcoorporate_ward_principle_update").removeClass("has-error");
            $(".invalid_kelurahan_corporate_principle_update").html("");
        }

        if (kodepos_corporate.val() == "") {
            $(".txtpostalcode_coorporate_principle_update").addClass("has-error");
            $(".invalid_kode_pos_corporate_principle_update").html("Kode Pos Principle tidak boleh kosong");
            kodepos_corporate.focus();
        } else {
            $(".txtpostalcode_coorporate_principle_update").removeClass("has-error");
            $(".invalid_kode_pos_corporate_principle_update").html("");
        }

        if (name_contact_person.val() == "") {
            $(".txtname_contact_person_principle_update").addClass("has-error");
            $(".invalid_nama_contact_person_principle_update").html("Nama Contact Person tidak boleh kosong");
            name_contact_person.focus();
        } else {
            $(".txtname_contact_person_principle_update").removeClass("has-error");
            $(".invalid_nama_contact_person_principle_update").html("");
        }

        if (phone_contact_person.val() == "") {
            $(".txtphone_contact_person_principle_update").addClass("has-error");
            $(".invalid_telepon_contact_person_principle_update").html("Telepon Contact Person tidak boleh kosong");
            phone_contact_person.focus();
        } else {
            $(".txtphone_contact_person_principle_update").removeClass("has-error");
            $(".invalid_telepon_contact_person_principle_update").html("");
        }

        if (kreditlimit_contact_person.val() == "") {
            $(".txtkreditlimit_contact_person_principle_update").addClass("has-error");
            $(".invalid_kredit_limit_contact_person_principle_update").html("Kredit Limit Contact Person tidak boleh kosong");
            kreditlimit_contact_person.focus();
        } else {
            $(".txtkreditlimit_contact_person_principle_update").removeClass("has-error");
            $(".invalid_kredit_limit_contact_person_principle_update").html("");
        }

    }
</script>