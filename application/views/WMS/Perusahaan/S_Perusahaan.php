<script type="text/javascript">
    var PerusahaanCode = '';

    $(document).ready(
        function() {
            GetPerusahaanMenu();
            $('.numeric').on('input', function(event) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $(".select2").select2({
                width: "100%"
            });
            

            $("#txtpelanggan_coorporate_perusahaan, #txtprinciple_coorporate_perusahaan").each(function() {
                $(this).select2({
                    placeholder: 'Select data',
                    closeOnSelect: false,
                    multiple: true,
                    width: '100%'
                });
            });
        }
    );

    function url_like(url) {
        return base_url + url;
    }

    function GetPerusahaanMenu() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('Perusahaan/GetPerusahaanMenu') ?>",
            // data: "PerusahaanId=" + id,
            async: "true",
            success: function(response) {
                if (response) {
                    ChPerusahaanMenu(response);
                }
            }
        });
    }

    //var DTABLE;

    function ChPerusahaanMenu(JSONPerusahaan) {

        $("#tableperusahaanmenu > tbody").html('');

        var Perusahaan = JSON.parse(JSONPerusahaan);

        var StatusC = Perusahaan.AuthorityMenu[0].StatusC;
        var StatusU = Perusahaan.AuthorityMenu[0].StatusU;
        var StatusD = Perusahaan.AuthorityMenu[0].StatusD;


        if (StatusC == 0) {
            $("#btnaddnewperusahaan").attr('style', 'display: none;');
        }

        $("#cbperusahaanjenis").html('');
        $("#cbupdateperusahaanjenis").html('');

        

        AppendProvince(Perusahaan);

        //append dropdow kelas jalan
        AppendStreetClass(Perusahaan);
        AppendStreetClass2(Perusahaan);

        //append dropdown area
        AppendArea(Perusahaan);

        AppendSelect2Pelanggan(Perusahaan);

        AppendSelect2Principle(Perusahaan);

        AppendTimeOperasional(Perusahaan);

        AppendMenu(Perusahaan);

        $('#tableperusahaanmenu').DataTable();
    }

    function AppendMenu(Perusahaan) {
        if (Perusahaan.PerusahaanMenu != 0) {

            if ($.fn.DataTable.isDataTable('#tableperusahaanmenu')) {
                $('#tableperusahaanmenu').DataTable().destroy();
            }

            $('#tableperusahaanmenu tbody').empty();

            let no = 1;
            for (i = 0; i < Perusahaan.PerusahaanMenu.length; i++) {
                var perusahaan_id = Perusahaan.PerusahaanMenu[i].client_wms_id;
                var perusahaan_nama = Perusahaan.PerusahaanMenu[i].client_wms_nama;
                var perusahaan_alamat = Perusahaan.PerusahaanMenu[i].client_wms_alamat;
                var perusahaan_telepon = Perusahaan.PerusahaanMenu[i].client_wms_telepon;
                var perusahaan_nama_contact_person = Perusahaan.PerusahaanMenu[i].client_wms_nama_contact_person;
                var perusahaan_telepon_contact_person = Perusahaan.PerusahaanMenu[i].client_wms_telepon_contact_person;

                var isAktif = Perusahaan.PerusahaanMenu[i].client_wms_is_aktif;

                if (isAktif == 0) {
                    Status_PT = 'Non Aktif';
                } else {
                    Status_PT = 'Aktif';
                }

                var strmenu = '';

                var strU = '';
                var strD = '';

                <?php
                if ($Menu_Access["U"] == 1) {
                ?>
                    strU = '<a href="' + url_like("Perusahaan/EditData/" + perusahaan_id) + '" style="width: 40px;" data-action="edit" class="btn btn-warning btneditperusahaanmenu form-control" data-id="' + perusahaan_id + '"><i class="fa fa-pencil"></i></a>';
                <?php
                }
                if ($Menu_Access["D"] == 1) {
                ?>
                    strD = '<button style="width: 40px;" class="btn btn-danger btndeleteperusahaanmenu form-control" onclick="DeletePerusahaanMenu(\'' + perusahaan_id + '\')"><i class="fa fa-times"></i></button>';
                <?php
                }
                ?>

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td>' + no++ + '</td>';
                strmenu = strmenu + '	<td>' + perusahaan_nama + '</td>';
                strmenu = strmenu + '	<td>' + perusahaan_alamat + '</td>';
                strmenu = strmenu + '	<td>' + perusahaan_telepon + '</td>';
                strmenu = strmenu + '	<td>' + perusahaan_nama_contact_person + '</td>';
                strmenu = strmenu + '	<td>' + perusahaan_telepon_contact_person + '</td>';
                strmenu = strmenu + '	<td>' + Status_PT + '</td>';
                strmenu = strmenu + '	<td><span>';
                strmenu = strmenu + strU;
                strmenu = strmenu + strD;
                strmenu = strmenu + '	</span></td>';
                strmenu = strmenu + '</tr>';

                $("#tableperusahaanmenu > tbody").append(strmenu);
            }
        }
    }

    $(document).ready(function(){
        $("#listcoorporate_province_perusahaan").change(function() {
            let provinsi = $(this).val();
            AppendCity(provinsi);

        });

        $("#listcoorporate_city_perusahaan").change(function() {
            let provinsi = $("#listcoorporate_province_perusahaan").val();
            let kota = $(this).val();
            AppendDistrict(provinsi, kota);
        });

        $("#listcoorporate_districts_perusahaan").on("change", function() {
            let kecamatan = $(this).val();
            let nama_kecamatan = $('#listcoorporate_districts_perusahaan option').filter(':selected').text();
            $("#data_districts_perusahaan").val(nama_kecamatan);
            AppendWard(kecamatan);
        });

        $("#listcoorporate_ward_perusahaan").on("change", function() {
            let kelurahan = $(this).val();
            let nama_kelurahan = $('#listcoorporate_ward_perusahaan option').filter(':selected').text();
            $("#data_ward_perusahaan").val(nama_kelurahan);
            $("#txtpostalcode_coorporate_perusahaan").val(kelurahan);
        });
    });

    function AppendProvince(Perusahaan) {
        if (Perusahaan.Provinsi != null) {
            $("#listcoorporate_province_perusahaan").empty();
            $("#listcoorporate_province_perusahaan_update").empty();
            let html = '';
            html += '<option value="">--Pilih Provinsi--</option>';
            $.each(Perusahaan.Provinsi, function(i, v) {
                html += '<option value="' + v.reffregion_nama + '">' + v.reffregion_nama + '</option>';
                $("#listcoorporate_province_perusahaan").html(html);
            });
        } else {
            $("#listcoorporate_province_perusahaan").append('<option value="">--Pilih Provinsi--</option>');
        }
    }

    function AppendCity(provinsi) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Perusahaan/Get_Request_Data_Kota') ?>",
            data: {
                id: provinsi
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_city_perusahaan").html(response);
            }
        });
    }

    function AppendDistrict(provinsi, kota) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Perusahaan/Get_Request_Data_Kecamatan') ?>",
            data: {
                provinsi: provinsi,
                id: kota
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_districts_perusahaan").html(response);
            }
        });
    }

    function AppendWard(kecamatan) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Perusahaan/Get_Request_Data_Kelurahan') ?>",
            data: {
                id: kecamatan
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_ward_perusahaan").html(response);
            }
        });
    }


    function AppendStreetClass(Perusahaan) {
        if (Perusahaan.KelasJalan != null) {
            $("#listcoorporate_stretclass_perusahaan").empty();
            let html = '';
            html += '<option value="">--Pilih Kelas jalan--</option>';
            $.each(Perusahaan.KelasJalan, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_stretclass_perusahaan").html(html);
            });
        } else {
            $("#listcoorporate_stretclass_perusahaan").append('<option value="">--Pilih Kelas Jalan--</option>');
        }
    }

    function AppendStreetClass2(Perusahaan, kelas_jalan2) {
        if (Perusahaan.KelasJalan2 != null) {
            $("#listcoorporate_stretclass2_perusahaan").empty();
            let html = '';
            html += '<option value="">--Pilih Kelas jalan--</option>';
            $.each(Perusahaan.KelasJalan2, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_stretclass2_perusahaan").html(html);
            });
        } else {
            $("#listcoorporate_stretclass2_perusahaan").append('<option value="">--Pilih Kelas Jalan--</option>');
        }
    }

    function AppendSelect2Pelanggan(Perusahaan) {
        $("#txtpelanggan_coorporate_perusahaan").empty();
        let html = '';
        if (Perusahaan.Pelanggan != null) {
            $.each(Perusahaan.Pelanggan, function(i, v) {
                let area = v.area == null ? '' : v.area;
                html += '<option value="' + v.id + '">' + v.nama + ' - ' + area + '</option>';
                $("#txtpelanggan_coorporate_perusahaan").html(html);
            });
        } else {
            $("#txtpelanggan_coorporate_perusahaan").append('<option>Data Not Found</option>');
        }

    }

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


    function AppendSelect2Principle(Perusahaan) {
        $("#txtprinciple_coorporate_perusahaan").empty();
        let html = '';
        if (Perusahaan.Principle != null) {
            $.each(Perusahaan.Principle, function(i, v) {
                html += '<option value="' + v.id + '">' + v.kode + ' - ' + v.nama + '</option>';
                $("#txtprinciple_coorporate_perusahaan").html(html);
            });

        } else {
            $("#txtprinciple_coorporate_perusahaan").append('<option>Data Not Found</option>');
        }


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

    function AppendArea(Perusahaan, area) {
        if (Perusahaan.Area != null) {
            $("#listcoorporate_area_perusahaan").empty();
            let html = '';
            html += '<option value="">--Pilih Area--</option>';
            $.each(Perusahaan.Area, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_area_perusahaan").html(html);
            });
        } else {
            $("#listcoorporate_area_perusahaan").append('<option value="">--Pilih Area--</option>');
        }
    }

    function AppendTimeOperasional(Perusahaan) {
        $('#list_day_operasional_perusahaan tbody').empty();

        $.each(Perusahaan.Day, function(i, v) {
            var html = '';

            var Jam_Buka = '<input type="time" class="from-control" id="jam_buka_perusahaan" name="jam_buka_perusahaan"/>';
            var Jam_Tutup = '<input type="time" class="from-control" id="jam_tutup_perusahaan" name="jam_tutup_perusahaan"/>';
            var status = `<select class="form-control" id="status_operasional_perusahaan" name="status_operasional_perusahaan">
                            <option value="1">BUKA</option>
                            <option value="0" selected>TUTUP</option>
                        </select>`;

            html = html + '<tr>';
            html = html + '	<td>' + i + ' <input type="hidden" id="no_urut_hari_perusahaan" name="no_urut_hari_perusahaan" value="' + i + '"/></td>';
            html = html + '	<td>' + v + ' <input type="hidden" id="nama_hari_perusahaan" name="nama_hari_perusahaan" value="' + v + '"/></td>';
            html = html + '	<td>' + Jam_Buka + '</td>';
            html = html + '	<td>' + Jam_Tutup + '</td>';
            html = html + '	<td>' + status + '</td>';
            html = html + '</tr>';

            $("#list_day_operasional_perusahaan > tbody").append(html);
        });
    }

    $("#btnbackperusahaan").click(
        function() {
            ResetForm();
            GetPerusahaanMenu();
        }
    );


    <?php if ($Menu_Access["D"] == 1) { ?>
        $("#btndeleteperusahaanmenu").click(function() {
            GetPerusahaanMenu();
        });

        // Delete Channel
        function DeletePerusahaanMenu(PerusahaanID) {
            // $("#lbdeletePerusahaanname").html(ChannelName);
            $("#hddeleteperusahaanid").val(PerusahaanID);

            $("#previewdeleteperusahaan").modal('show');
        }

        $("#btnyesdeleteperusahaan").click(
            function() {
                var PerusahaanID = $("#hddeleteperusahaanid").val();

                $("#loadingdeleteperusahaan").show();
                $("#btnyesdeleteperusahaan").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Perusahaan/DeletePerusahaanMenu') ?>",
                    data: {
                        PerusahaanID: PerusahaanID
                    },
                    success: function(response) {
                        $("#loadingdeleteperusahaan").hide();
                        $("#btnyesdeleteperusahaan").prop("disabled", false);

                        if (response == 1) {
                            var msg = 'Data Perusahaan berhasil dihapus.';
                            var msgtype = 'success';

                            Swal.fire(
                                'Success!',
                                msg,
                                msgtype
                            )
                        } else {
                            var ErrMsg = response.split('$$$');
                            ErrMsg = ErrMsg[1];

                            var msg = ErrMsg;
                            var msgtype = 'error';

                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )
                        }

                        $("#previewdeleteperusahaan").modal('hide');
                        GetPerusahaanMenu();

                    },
                    error: function(xhr, ajaxOptions, thrownError) {

                        $("#loadingdeleteperusahaan").hide();
                        $("#btnyesdeleteperusahaan").prop("disabled", false);
                    }
                });
            }
        );
    <?php } ?>


    <?php if ($Menu_Access["C"] == 1) { ?>
        $("#btnaddnewperusahaan").removeAttr('style');

        // Add New Channel
        $("#btnaddnewperusahaan").click(
            function() {
                ResetForm();
                $("#previewaddnewperusahaan").modal('show');
            }
        );

        $("#btnsaveaddnewperusahaan").click(
            function() {
                let kode_corporate = $("#txtkode_coorporate_perusahaan");
                let name_corporate = $("#txtname_coorporate_perusahaan");
                let address_corporate = $("#txtaddress_coorporate_perusahaan");
                let phone_corporate = $("#txtphone_coorporate_perusahaan");
                // let corporate_group = $("#listcoorporate_group_perusahaan");
                let stretclass_corporate = $("#listcoorporate_stretclass_perusahaan");
                let stretclass2_corporate = $("#listcoorporate_stretclass2_perusahaan");
                let area_corporate = $("#listcoorporate_area_perusahaan");
                let province = $("#listcoorporate_province_perusahaan");
                let city = $("#listcoorporate_city_perusahaan");
                // let districts = $("#listcoorporate_districts_perusahaan");
                let districts = $("#data_districts_perusahaan");
                // let ward = $("#listcoorporate_ward_perusahaan");
                let ward = $("#data_ward_perusahaan");
                let kodepos_corporate = $("#txtpostalcode_coorporate_perusahaan");
                let pelanggan = $("#txtpelanggan_coorporate_perusahaan");
                let principle = $("#txtprinciple_coorporate_perusahaan");

                let name_contact_person = $("#txtname_contact_person_perusahaan");
                let phone_contact_person = $("#txtphone_contact_person_perusahaan");
                let keterangan_contact_person = $("#txtketerangan_contact_person_perusahaan");
                let status = '';
                if ($("#txtstatus_perusahaan").is(':checked')) {
                    status = 1;
                } else {
                    status = 0;
                }

                let no_urut_hari = [];
                let nama_hari = [];
                let jam_buka = [];
                let jam_tutup = [];
                let status_operasional = [];

                $("input[name='no_urut_hari_perusahaan']").each(function(i, v) {
                    no_urut_hari.push($(this).val());
                });

                $("input[name='nama_hari_perusahaan']").each(function(i, v) {
                    nama_hari.push($(this).val());
                });

                $("input[name='jam_buka_perusahaan']").each(function(i, v) {
                    jam_buka.push($(this).val());
                });

                $("input[name='jam_tutup_perusahaan']").each(function(i, v) {
                    jam_tutup.push($(this).val());
                });

                $("select[name='status_operasional_perusahaan']").each(function(i, v) {
                    status_operasional.push($(this).val());

                });

                let final_arr = [];
                for (let i = 0; i < no_urut_hari.length; i++) {
                    final_arr.push({
                        no_urut: no_urut_hari[i],
                        hari: nama_hari[i],
                        buka: jam_buka[i],
                        tutup: jam_tutup[i],
                        status: status_operasional[i]
                    });
                }

                validasi(kode_corporate, name_corporate, address_corporate, phone_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, keterangan_contact_person, pelanggan, principle);

                $("#loadingaddperusahaan").show();
                $("#btnsaveaddnewperusahaan").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Perusahaan/SaveAddNewPerusahaan') ?>",
                    dataType: "json",
                    async: "true",
                    data: {
                        kode_corporate: kode_corporate.val(),
                        name_corporate: name_corporate.val(),
                        address_corporate: address_corporate.val(),
                        phone_corporate: phone_corporate.val(),
                        // corporate_group: corporate_group.val(),
                        stretclass_corporate: stretclass_corporate.val(),
                        stretclass2_corporate: stretclass2_corporate.val(),
                        area_corporate: area_corporate.val(),
                        province: province.val(),
                        city: city.val(),
                        districts: districts.val(),
                        ward: ward.val(),
                        kodepos_corporate: kodepos_corporate.val(),
                        name_contact_person: name_contact_person.val(),
                        phone_contact_person: phone_contact_person.val(),
                        keterangan_contact_person: keterangan_contact_person.val(),
                        timeoperasional: final_arr,
                        pelanggan: pelanggan.val(),
                        principle: principle.val(),
                        status: status
                    },

                    success: function(response) {
                        $("#loadingaddperusahaan").hide();
                        $("#btnsaveaddnewperusahaan").prop("disabled", false);
                        if (response == 1) {
                            var msg = 'Data Perusahaan berhasil ditambah';
                            var msgtype = 'success';

                            Swal.fire(
                                'Success!',
                                msg,
                                msgtype
                            )

                            ResetForm();

                            $("#previewaddnewperusahaan").modal('hide');

                            GetPerusahaanMenu();
                        } else if (response == 0) {
                            var msg = 'Gagal tambah';
                            var msgtype = 'error';

                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )

                            ResetForm();

                            $("#previewaddnewperusahaan").modal('hide');

                            GetPerusahaanMenu();
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
                        $("#loadingaddperusahaan").hide();
                        $("#btnsaveaddnewperusahaan").prop("disabled", false);
                    }
                });
            }
        );
    <?php
    }
    ?>

    function ResetForm() {
        <?php if ($Menu_Access["U"] == 1) { ?>
            $("#txtkode_coorporate_perusahaan, #txtname_coorporate_perusahaan_update, #txtaddress_coorporate_perusahaan_update, #txtphone_coorporate_perusahaan_update, #txtlattitude_coorporate_perusahaan_update, #txtlongitude_coorporate_perusahaan_update, #txtpostalcode_coorporate_perusahaan_update, #data_districts_perusahaan_update, #data_ward_perusahaan_update, #txtname_contact_person_perusahaan_update, #txtphone_contact_person_perusahaan_update, #txtketerangan_contact_person_perusahaan_update").each(function() {
                $(this).val("");
            });

            $("#listcoorporate_stretclass_perusahaan_update, #listcoorporate_stretclass2_perusahaan_update, #listcoorporate_area_perusahaan_update, #listcoorporate_province_perusahaan_update, #listcoorporate_city_perusahaan_update, #listcoorporate_districts_perusahaan_update, #listcoorporate_ward_perusahaan_update").each(function() {
                // $(this).prop('selectedIndex',0);
                $(this).val("");
            });
        <?php } ?>

        <?php if ($Menu_Access["C"] == 1) { ?>
            $("#txtkode_coorporate_perusahaan, #txtname_coorporate_perusahaan, #txtaddress_coorporate_perusahaan, #txtphone_coorporate_perusahaan, #txtlattitude_coorporate_perusahaan, #txtlongitude_coorporate_perusahaan, #txtpostalcode_coorporate_perusahaan, #data_districts_perusahaan, #data_ward_perusahaan, #txtname_contact_person_perusahaan, #txtphone_contact_person_perusahaan, #txtketerangan_contact_person_perusahaan").each(function() {
                $(this).val("");
            });

            $("#listcoorporate_stretclass_perusahaan, #listcoorporate_stretclass2_perusahaan, #listcoorporate_area_perusahaan, #listcoorporate_province_perusahaan, #listcoorporate_city_perusahaan, #listcoorporate_districts_perusahaan, #listcoorporate_ward_perusahaan, #txtpelanggan_coorporate_perusahaan, #txtprinciple_coorporate_perusahaan").each(function() {
                $(this).prop('selectedIndex', 0);
            });
        <?php } ?>

    }

    function validasi(kode_corporate, name_corporate, address_corporate, phone_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, keterangan_contact_person, pelanggan, principle) {

        kode_corporate.prop("required", true);
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
        pelanggan.prop("required", true);
        principle.prop("required", true);

        if (kode_corporate.val() == "") {
            $(".txtkode_coorporate_perusahaan").addClass("has-error");
            $(".invalid_kode_corporate_perusahaan").html("Kode Corporate tidak boleh kosong");
            kode_corporate.focus();
        } else {
            $(".txtkode_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_kode_corporate_perusahaan").html("");
        }

        if (name_corporate.val() == "") {
            $(".txtname_coorporate_perusahaan").addClass("has-error");
            $(".invalid_nama_corporate_perusahaan").html("Nama Corporate tidak boleh kosong");
            name_corporate.focus();
        } else {
            $(".txtname_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_nama_corporate_perusahaan").html("");
        }

        if (address_corporate.val() == "") {
            $(".txtaddress_coorporate_perusahaan").addClass("has-error");
            $(".invalid_alamat_corporate_perusahaan").html("Alamat Corporate tidak boleh kosong");
            address_corporate.focus();
        } else {
            $(".txtaddress_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_alamat_corporate_perusahaan").html("");
        }

        if (phone_corporate.val() == "") {
            $(".txtphone_coorporate_perusahaan").addClass("has-error");
            $(".invalid_telepon_corporate_perusahaan").html("Telephon Corporate tidak boleh kosong");
            phone_corporate.focus();
        } else {
            $(".txtphone_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_telepon_corporate_perusahaan").html("");
        }

        if (stretclass_corporate.val() == "") {
            $(".listcoorporate_stretclass_perusahaan").addClass("has-error");
            $(".invalid_kelas_jalan_corporate_perusahaan").html("Kelas Jalan Berdasarkan barang muatan tidak boleh kosong");
            stretclass_corporate.focus();
        } else {
            $(".listcoorporate_stretclass_perusahaan").removeClass("has-error");
            $(".invalid_kelas_jalan_corporate_perusahaan").html("");
        }

        if (stretclass2_corporate.val() == "") {
            $(".listcoorporate_stretclass2_perusahaan").addClass("has-error");
            $(".invalid_kelas_jalan2_corporate_perusahaan").html("Kelas Jalan Berdasarkan fungsi jalan tidak boleh kosong");
            stretclass2_corporate.focus();
        } else {
            $(".listcoorporate_stretclass2_perusahaan").removeClass("has-error");
            $(".invalid_kelas_jalan2_corporate_perusahaan").html("");
        }

        if (area_corporate.val() == "") {
            $(".listcoorporate_area_perusahaan").addClass("has-error");
            $(".invalid_area_corporate_perusahaan").html("Area Corporate tidak boleh kosong");
            area_corporate.focus();
        } else {
            $(".listcoorporate_area_perusahaan").removeClass("has-error");
            $(".invalid_area_corporate_perusahaan").html("");
        }

        if (province.val() == "") {
            $(".listcoorporate_province_perusahaan").addClass("has-error");
            $(".invalid_provinsi_corporate_perusahaan").html("Provinsi Corporate tidak boleh kosong");
            province.focus();
        } else {
            $(".listcoorporate_province_perusahaan").removeClass("has-error");
            $(".invalid_provinsi_corporate_perusahaan").html("");
        }

        if (city.val() == "") {
            $(".listcoorporate_city_perusahaan").addClass("has-error");
            $(".invalid_kota_corporate_perusahaan").html("Kota Corporate tidak boleh kosong");
            city.focus();
        } else {
            $(".listcoorporate_city_perusahaan").removeClass("has-error");
            $(".invalid_kota_corporate_perusahaan").html("");
        }

        if (districts.val() == "") {
            $(".listcoorporate_districts_perusahaan").addClass("has-error");
            $(".invalid_kecamatan_corporate_perusahaan").html("Kecamatan Corporate tidak boleh kosong");
            districts.focus();
        } else {
            $(".listcoorporate_districts_perusahaan").removeClass("has-error");
            $(".invalid_kecamatan_corporate_perusahaan").html("");
        }

        if (ward.val() == "") {
            $(".listcoorporate_ward_perusahaan").addClass("has-error");
            $(".invalid_kelurahan_corporate_perusahaan").html("Kelurahan Corporate tidak boleh kosong");
            ward.focus();
        } else {
            $(".listcoorporate_ward_perusahaan").removeClass("has-error");
            $(".invalid_kelurahan_corporate_perusahaan").html("");
        }

        if (kodepos_corporate.val() == "") {
            $(".txtpostalcode_coorporate_perusahaan").addClass("has-error");
            $(".invalid_kode_pos_corporate_perusahaan").html("Kode Pos Corporate tidak boleh kosong");
            kodepos_corporate.focus();
        } else {
            $(".txtpostalcode_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_kode_pos_corporate_perusahaan").html("");
        }

        if (pelanggan.val() == null) {
            $(".txtpelanggan_coorporate_perusahaan").addClass("has-error");
            $(".invalid_pelanggan_corporate_perusahaan").html("Pelanggan tidak boleh kosong");
            pelanggan.focus();
        } else {
            $(".txtpelanggan_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_pelanggan_corporate_perusahaan").html("");
        }

        if (principle.val() == null) {
            $(".txtprinciple_coorporate_perusahaan").addClass("has-error");
            $(".invalid_principle_corporate_perusahaan").html("Corporate tidak boleh kosong");
            principle.focus();
        } else {
            $(".txtprinciple_coorporate_perusahaan").removeClass("has-error");
            $(".invalid_principle_corporate_perusahaan").html("");
        }

        if (name_contact_person.val() == "") {
            $(".txtname_contact_person_perusahaan").addClass("has-error");
            $(".invalid_nama_contact_person_perusahaan").html("Nama Contact Person tidak boleh kosong");
            name_contact_person.focus();
        } else {
            $(".txtname_contact_person_perusahaan").removeClass("has-error");
            $(".invalid_nama_contact_person_perusahaan").html("");
        }

        if (phone_contact_person.val() == "") {
            $(".txtphone_contact_person_perusahaan").addClass("has-error");
            $(".invalid_telepon_contact_person_perusahaan").html("Telepon Contact Person tidak boleh kosong");
            phone_contact_person.focus();
        } else {
            $(".txtphone_contact_person_perusahaan").removeClass("has-error");
            $(".invalid_telepon_contact_person_perusahaan").html("");
        }

        if (keterangan_contact_person.val() == "") {
            $(".txtketerangan_contact_person_perusahaan").addClass("has-error");
            $(".invalid_keterangan_contact_person_perusahaan").html("Keterangan Contact Person tidak boleh kosong");
            keterangan_contact_person.focus();
        } else {
            $(".txtketerangan_contact_person_perusahaan").removeClass("has-error");
            $(".invalid_keterangan_contact_person_perusahaan").html("");
        }

    }
</script>