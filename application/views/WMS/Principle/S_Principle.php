<script type="text/javascript">
    var PrincipleCode = '';

    $(document).ready(
        function() {
            GetPrincipleMenu();
            url_like();
            $('.numeric').on('input', function(event) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $(".select2").select2({
                width: "100%"
            });
        }
    );

    function url_like(url) {
        return base_url + url;
    }


    function GetPrincipleMenu() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('Principle/GetPrincipleMenu') ?>",
            // data: "PrincipleId=" + id,
            success: function(response) {
                if (response) {
                    ChPrincipleMenu(response);
                }
            }
        });
    }

    //var DTABLE;

    function ChPrincipleMenu(JSONPrinciple) {
        $("#tableprinciplemenu > tbody").html('');

        var Principle = JSON.parse(JSONPrinciple);

        var StatusC = Principle.AuthorityMenu[0].StatusC;
        var StatusU = Principle.AuthorityMenu[0].StatusU;
        var StatusD = Principle.AuthorityMenu[0].StatusD;


        if (StatusC == 0) {
            $("#btnaddnewprinciple").attr('style', 'display: none;');
        }

        $("#cbprinciplejenis").html('');
        $("#cbupdateprinciplejenis").html('');

        AppendProvince(Principle);

        //append dropdow kelas jalan
        AppendStreetClass(Principle);
        AppendStreetClass2(Principle);

        //append dropdown area
        AppendArea(Principle);

        CheckIsValidKoordinat();

        AppendTimeOperasional(Principle);

        if (Principle.PrincipleMenu != 0) {

            if ($.fn.DataTable.isDataTable('#tableprinciplemenu')) {
                $('#tableprinciplemenu').DataTable().destroy();
            }

            $('#tableprinciplemenu tbody').empty();

            // console.log($Menu_Access);
            let no = 1;
            for (i = 0; i < Principle.PrincipleMenu.length; i++) {
                var principle_id = Principle.PrincipleMenu[i].principle_id;
                var principle_kode = Principle.PrincipleMenu[i].principle_kode;
                var principle_nama = Principle.PrincipleMenu[i].principle_nama;
                var principle_alamat = Principle.PrincipleMenu[i].principle_alamat;
                var principle_telepon = Principle.PrincipleMenu[i].principle_telepon;
                var principle_nama_contact_person = Principle.PrincipleMenu[i].principle_nama_contact_person;
                var principle_email_contact_person = Principle.PrincipleMenu[i].principle_email_contact_person;
                var principle_telepon_contact_person = Principle.PrincipleMenu[i].principle_telepon_contact_person;

                var isAktif = Principle.PrincipleMenu[i].principle_is_aktif;

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
                    strU = '<a href="' + url_like("Principle/EditData/" + principle_id) + '" style="width: 40px;" data-action="edit" class="btn btn-warning btneditprinciplemenu form-control" data-id="' + principle_id + '"><i class="fa fa-pencil"></i></a>';
                <?php
                }
                if ($Menu_Access["D"] == 1) {
                ?>
                    strD = '<button style="width: 40px;" class="btn btn-danger btndeleteprinciplemenu form-control" onclick="DeletePrincipleMenu(\'' + principle_id + '\')"><i class="fa fa-times"></i></button>';
                <?php
                }
                ?>

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td>' + no++ + '</td>';
                strmenu = strmenu + '	<td>' + principle_kode + '</td>';
                strmenu = strmenu + '	<td>' + principle_nama + '</td>';
                strmenu = strmenu + '	<td>' + principle_alamat + '</td>';
                strmenu = strmenu + '	<td>' + principle_telepon + '</td>';
                strmenu = strmenu + '	<td>' + principle_nama_contact_person + '</td>';
                strmenu = strmenu + '	<td>' + principle_telepon_contact_person + '</td>';
                strmenu = strmenu + '	<td>' + Status_PT + '</td>';
                strmenu = strmenu + '	<td><span>';
                strmenu = strmenu + strU;
                strmenu = strmenu + strD;
                strmenu = strmenu + '	</span></td>';
                strmenu = strmenu + '</tr>';

                $("#tableprinciplemenu > tbody").append(strmenu);
            }
        }

        $('#tableprinciplemenu').DataTable();
        // $('#tablePrinciplemenu').DataTable({
        //     retrieve: true,
        //     "dom": '<"left"f>rtp'
        // });
    }

    $(document).ready(function(){
        $("#listcoorporate_province_principle").change(function() {
            let provinsi = $(this).val();
            AppendCity(provinsi);

        });

        $("#listcoorporate_city_principle").change(function() {
            let provinsi = $("#listcoorporate_province_principle").val();
            let kota = $(this).val();
            AppendDistrict(provinsi, kota);
        });
        

        $("#listcoorporate_districts_principle").on("change", function() {
            let kecamatan = $(this).val();
            let nama_kecamatan = $('#listcoorporate_districts_principle option').filter(':selected').text();
            $("#data_districts_principle").val(nama_kecamatan);
            AppendWard(kecamatan);
        });
        
        $("#listcoorporate_ward_principle").on("change", function() {
            let kelurahan = $(this).val();
            let nama_kelurahan = $('#listcoorporate_ward_principle option').filter(':selected').text();
            $("#data_ward_principle").val(nama_kelurahan);
            $("#txtpostalcode_coorporate_principle").val(kelurahan);
        });

        $("#tambah_principle_brand").on("keypress", function(event) {
            let text = $(this);
            AppendListPrincipleBrand(event, text);
        });
       
    })

    function AppendProvince(Principle) {
        if (Principle.Provinsi != null) {
            $("#listcoorporate_province_principle").empty();
            let html = '';
            html += '<option value="">--Pilih Provinsi--</option>';
            $.each(Principle.Provinsi, function(i, v) {
                html += '<option value="' + v.reffregion_nama + '">' + v.reffregion_nama + '</option>';
                $("#listcoorporate_province_principle").html(html);
            });
        } else {
            $("#listcoorporate_province_principle").append('<option value="">--Pilih Provinsi--</option>');
        }
    }

    function AppendCity(provinsi) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/Get_Request_Data_Kota') ?>",
            data: {
                id: provinsi
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_city_principle").html(response);
            }
        });
    }


    function AppendDistrict(provinsi, kota) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/Get_Request_Data_Kecamatan') ?>",
            data: {
                provinsi: provinsi,
                id: kota
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_districts_principle").html(response);
            }
        });
    }


    function AppendWard(kecamatan) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('Principle/Get_Request_Data_Kelurahan') ?>",
            data: {
                id: kecamatan
            },
            dataType: "json",
            success: function(response) {
                $("#listcoorporate_ward_principle").html(response);
            }
        });
    }

    function AppendStreetClass(Principle) {
        if (Principle.KelasJalan != null) {
            $("#listcoorporate_stretclass_principle").empty();
            let html = '';
            html += '<option value="">--Pilih Kelas jalan--</option>';
            $.each(Principle.KelasJalan, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_stretclass_principle").html(html);
            });
        } else {
            $("#listcoorporate_stretclass_principle").append('<option value="">--Pilih Kelas Jalan--</option>');
        }
    }

    function AppendStreetClass2(Principle) {
        if (Principle.KelasJalan2 != null) {
            $("#listcoorporate_stretclass2_principle").empty();
            // $("#listcoorporate_stretclass2_principle_update").empty();
            let html = '';
            html += '<option value="">--Pilih Kelas jalan--</option>';
            $.each(Principle.KelasJalan2, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_stretclass2_principle").html(html);
            });
        } else {
            $("#listcoorporate_stretclass2_principle").append('<option value="">--Pilih Kelas Jalan--</option>');
        }
    }

    function AppendArea(Principle, area) {
        if (Principle.Area != null) {
            $("#listcoorporate_area_principle").empty();
            // $("#listcoorporate_area_principle_update").empty();
            let html = '';
            html += '<option value="">--Pilih Area--</option>';
            $.each(Principle.Area, function(i, v) {
                html += '<option value="' + v.id + '">' + v.nama + '</option>';
                $("#listcoorporate_area_principle").html(html);
            });
        } else {
            $("#listcoorporate_area_principle").append('<option value="">--Pilih Area--</option>');
        }
    }

    //function check checklist sesuai atau tidak titik lokasi untuk principle
    function CheckIsValidKoordinat() {
        $("#iskoordinat_principle").on("click", function() {
            if ($("#iskoordinat_principle").prop('checked') == true) {
                $("#showiskoordinat_principle").hide("slow");
            } else {
                $("#showiskoordinat_principle").show("slow");
            }
        });
    }

    function AppendTimeOperasional(Principle) {
        $('#list_day_operasional_principle tbody').empty();

        $.each(Principle.Day, function(i, v) {
            var html = '';

            var Jam_Buka = '<input type="time" class="from-control" id="jam_buka_principle" name="jam_buka_principle"/>';
            var Jam_Tutup = '<input type="time" class="from-control" id="jam_tutup_principle" name="jam_tutup_principle"/>';
            var status = `<select class="form-control" id="status_operasional_principle" name="status_operasional_principle">
                            <option value="1" selected>BUKA</option>
                            <option value="0">TUTUP</option>
                        </select>`;

            html = html + '<tr>';
            html = html + '	<td>' + i + ' <input type="hidden" id="no_urut_hari_principle" name="no_urut_hari_principle" value="' + i + '"/></td>';
            html = html + '	<td>' + v + ' <input type="hidden" id="nama_hari_principle" name="nama_hari_principle" value="' + v + '"/></td>';
            html = html + '	<td>' + Jam_Buka + '</td>';
            html = html + '	<td>' + Jam_Tutup + '</td>';
            html = html + '	<td>' + status + '</td>';
            html = html + '</tr>';

            $("#list_day_operasional_principle > tbody").append(html);
        });
    }

    $("#btnbackprinciple").click(
        function() {
            ResetForm();
            GetPrincipleMenu();
        }
    );

    function AppendListPrincipleBrand(event, text) {
        let text_ = text;
        if (event.key == "Enter") {
            event.preventDefault();
            $("#list_principle_brand").append(`
                <div class="new-row">
                    <button type="button" class="principle-brand-btn">
                        <span></span><text class="check-length">` + text.val() + `</text>
                    </button><input type="hidden" name="data_principle_brand" class="form-control" value="` + text.val() + `" />
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

    $("#list_principle_brand").on("click", '.principle-brand-btn', function() {
        $(this).addClass("active");
        setTimeout(() => {
            $(this).removeClass("active");
            $(this).parents(".new-row").remove();
        }, 1000);
    });

    <?php
    if ($Menu_Access["D"] == 1) {
    ?>
        $("#btndeleteprinciplemenu").click(
            function() {
                GetPrincipleMenu();
            }
        );

        // Delete Channel
        function DeletePrincipleMenu(PrincipleID) {
            // $("#lbdeletePrinciplename").html(ChannelName);
            $("#hddeleteprincipleid").val(PrincipleID);

            $("#previewdeleteprinciple").modal('show');
        }

        $("#btnyesdeleteprinciple").click(
            function() {
                var PrincipleID = $("#hddeleteprincipleid").val();

                $("#loadingdeleteprinciple").show();
                $("#btnyesdeleteprinciple").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Principle/DeletePrincipleMenu') ?>",
                    data: {
                        PrincipleID: PrincipleID
                    },
                    success: function(response) {
                        $("#loadingdeleteprinciple").hide();
                        $("#btnyesdeleteprinciple").prop("disabled", false);

                        if (response == 1) {
                            var msg = 'Data Principle berhasil dihapus.';
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

                        $("#previewdeleteprinciple").modal('hide');
                        GetPrincipleMenu();

                    },
                    error: function(xhr, ajaxOptions, thrownError) {

                        $("#loadingdeleteprinciple").hide();
                        $("#btnyesdeleteprinciple").prop("disabled", false);
                    }
                });
            }
        );
    <?php
    }
    ?>


    <?php
    if ($Menu_Access["C"] == 1) {
    ?>
        $("#btnaddnewprinciple").removeAttr('style');

        // Add New Channel
        $("#btnaddnewprinciple").click(
            function() {
                ResetForm();
                $("#previewaddnewprinciple").modal('show');
            }
        );

        $("#btnsaveaddnewprinciple").click(
            function() {
                let kode_corporate = $("#txtkode_coorporate_principle");
                let name_corporate = $("#txtname_coorporate_principle");
                let address_corporate = $("#txtaddress_coorporate_principle");
                let phone_corporate = $("#txtphone_coorporate_principle");
                // let corporate_group = $("#listcoorporate_group_principle");
                let lattitude_corporate = $("#txtlattitude_coorporate_principle");
                let longitude_corporate = $("#txtlongitude_coorporate_principle");
                let stretclass_corporate = $("#listcoorporate_stretclass_principle");
                let stretclass2_corporate = $("#listcoorporate_stretclass2_principle");
                let area_corporate = $("#listcoorporate_area_principle");
                let province = $("#listcoorporate_province_principle");
                let city = $("#listcoorporate_city_principle");
                // let districts = $("#listcoorporate_districts_principle");
                let districts = $("#data_districts_principle");
                // let ward = $("#listcoorporate_ward_principle");
                let ward = $("#data_ward_principle");
                let kodepos_corporate = $("#txtpostalcode_coorporate_principle");

                let name_contact_person = $("#txtname_contact_person_principle");
                let phone_contact_person = $("#txtphone_contact_person_principle");
                let kreditlimit_contact_person = $("#txtkreditlimit_contact_person_principle");

                let status = '';
                if ($("#txtstatus_principle").is(':checked')) {
                    status = 1;
                } else {
                    status = 0;
                }

                let no_urut_hari = [];
                let nama_hari = [];
                let jam_buka = [];
                let jam_tutup = [];
                let status_operasional = [];
                let principle_brand = [];

                $("input[name='no_urut_hari_principle']").each(function(i, v) {
                    no_urut_hari.push($(this).val());
                });

                $("input[name='nama_hari_principle']").each(function(i, v) {
                    nama_hari.push($(this).val());
                });

                $("input[name='jam_buka_principle']").each(function(i, v) {
                    jam_buka.push($(this).val());
                });

                $("input[name='jam_tutup_principle']").each(function(i, v) {
                    jam_tutup.push($(this).val());
                });

                $("select[name='status_operasional_principle']").each(function(i, v) {
                    status_operasional.push($(this).val());
                    // console.log($(this).val());
                });

                $("input[name='data_principle_brand']").each(function(i, v) {
                    principle_brand.push($(this).val());
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

                validasi(kode_corporate, name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person);

                $("#loadingaddprinciple").show();
                $("#btnsaveaddnewprinciple").prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('Principle/SaveAddNewPrinciple') ?>",
                    dataType: "json",
                    async: "true",
                    data: {
                        kode_corporate: kode_corporate.val(),
                        name_corporate: name_corporate.val(),
                        address_corporate: address_corporate.val(),
                        phone_corporate: phone_corporate.val(),
                        // corporate_group: corporate_group.val(),
                        lattitude_corporate: lattitude_corporate.val(),
                        longitude_corporate: longitude_corporate.val(),
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
                        kreditlimit_contact_person: kreditlimit_contact_person.val(),
                        status: status,
                        principle_brand: principle_brand,
                        timeoperasional: final_arr
                    },

                    success: function(response) {
                        $("#loadingaddprinciple").hide();
                        $("#btnsaveaddnewprinciple").prop("disabled", false);
                        if (response == 1) {
                            var msg = 'Data Principle berhasil ditambah';
                            var msgtype = 'success';

                            Swal.fire(
                                'Success!',
                                msg,
                                msgtype
                            )

                            GetPrincipleMenu();

                            $("#previewaddnewprinciple").modal('hide');
                            ResetForm();
                        } else if (response == 0) {
                            var msg = 'Gagal tambah data';
                            var msgtype = 'error';
                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )
                        } else {
                            // console.log(response);
                            var msg = 'Gagal tambah data';
                            var msgtype = 'error';
                            Swal.fire(
                                'Error!',
                                msg,
                                msgtype
                            )
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $("#loadingaddprinciple").hide();
                        $("#btnsaveaddnewprinciple").prop("disabled", false);
                    }
                });
            }
        );
    <?php
    }
    ?>

    function ResetForm() {
        <?php
        if ($Menu_Access["U"] == 1) {
        ?>
            $("#txtkode_coorporate_principle, #txtname_coorporate_principle_update, #txtaddress_coorporate_principle_update, #txtphone_coorporate_principle_update, #txtlattitude_coorporate_principle_update, #txtlongitude_coorporate_principle_update, #txtpostalcode_coorporate_principle_update, #data_districts_principle_update, #data_ward_principle_update, #txtname_contact_person_principle_update, #txtphone_contact_person_principle_update, #txtkreditlimit_contact_person_principle_update").each(function() {
                $(this).val("");
            });

            $("#listcoorporate_stretclass_principle_update, #listcoorporate_stretclass2_principle_update, #listcoorporate_area_principle_update, #listcoorporate_province_principle_update, #listcoorporate_city_principle_update, #listcoorporate_districts_principle_update, #listcoorporate_ward_principle_update").each(function() {
                // $(this).prop('selectedIndex',0);
                $(this).val("");
            });
        <?php
        }
        ?>

        <?php
        if ($Menu_Access["C"] == 1) {
        ?>
            $("#txtkode_coorporate_principle, #txtname_coorporate_principle, #txtaddress_coorporate_principle, #txtphone_coorporate_principle, #txtlattitude_coorporate_principle, #txtlongitude_coorporate_principle, #txtpostalcode_coorporate_principle, #data_districts_principle, #data_ward_principle, #txtname_contact_person_principle, #txtphone_contact_person_principle, #txtkreditlimit_contact_person_principle").each(function() {
                $(this).val("");
            });

            $("#listcoorporate_stretclass_principle, #listcoorporate_stretclass2_principle, #listcoorporate_area_principle, #listcoorporate_province_principle, #listcoorporate_city_principle, #listcoorporate_districts_principle, #listcoorporate_ward_principle").each(function() {
                $(this).prop('selectedIndex', 0);
            });

            $(".new-row").each(function() {
                $(this).remove();
            });
        <?php
        }
        ?>

    }

    function validasi(kode_corporate, name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person) {

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
            $(".txtkode_coorporate_principle").addClass("has-error");
            $(".invalid_kode_corporate_principle").html("Kode Principle tidak boleh kosong");
            kode_corporate.focus();
        } else {
            $(".txtkode_coorporate_principle").removeClass("has-error");
            $(".invalid_kode_corporate_principle").html("");
        }

        if (name_corporate.val() == "") {
            $(".txtname_coorporate_principle").addClass("has-error");
            $(".invalid_nama_corporate_principle").html("Nama Principle tidak boleh kosong");
            name_corporate.focus();
        } else {
            $(".txtname_coorporate_principle").removeClass("has-error");
            $(".invalid_nama_corporate_principle").html("");
        }

        if (address_corporate.val() == "") {
            $(".txtaddress_coorporate_principle").addClass("has-error");
            $(".invalid_alamat_corporate_principle").html("Alamat Principle tidak boleh kosong");
            address_corporate.focus();
        } else {
            $(".txtaddress_coorporate_principle").removeClass("has-error");
            $(".invalid_alamat_corporate_principle").html("");
        }

        if (phone_corporate.val() == "") {
            $(".txtphone_coorporate_principle").addClass("has-error");
            $(".invalid_telepon_corporate_principle").html("Telephon Principle tidak boleh kosong");
            phone_corporate.focus();
        } else {
            $(".txtphone_coorporate_principle").removeClass("has-error");
            $(".invalid_telepon_corporate_principle").html("");
        }

        if (lattitude_corporate.val() == "") {
            $(".txtlattitude_coorporate").addClass("has-error");
            $(".invalid_lattitude_corporate_principle").html("Lattitude Principle tidak boleh kosong");
            lattitude_corporate.focus();
        } else {
            $(".txtlattitude_coorporate_principle").removeClass("has-error");
            $(".invalid_lattitude_corporate_principle").html("");
        }

        if (longitude_corporate.val() == "") {
            $(".txtlongitude_coorporate").addClass("has-error");
            $(".invalid_longitude_corporate_principle").html("Longitude Principle tidak boleh kosong");
            longitude_corporate.focus();
        } else {
            $(".txtlongitude_coorporate_principle").removeClass("has-error");
            $(".invalid_longitude_corporate_principle").html("");
        }

        if (stretclass_corporate.val() == "") {
            $(".listcoorporate_stretclass_principle").addClass("has-error");
            $(".invalid_kelas_jalan_corporate_principle").html("Kelas Jalan berdasarkan barang muatan tidak boleh kosong");
            stretclass_corporate.focus();
        } else {
            $(".listcoorporate_stretclass_principle").removeClass("has-error");
            $(".invalid_kelas_jalan_corporate_principle").html("");
        }

        if (stretclass2_corporate.val() == "") {
            $(".listcoorporate_stretclass2_principle").addClass("has-error");
            $(".invalid_kelas_jalan2_corporate_principle").html("Kelas Jalan berdasarkan fungsi barang tidak boleh kosong");
            stretclass2_corporate.focus();
        } else {
            $(".listcoorporate_stretclass2_principle").removeClass("has-error");
            $(".invalid_kelas_jalan2_corporate_principle").html("");
        }

        if (area_corporate.val() == "") {
            $(".listcoorporate_area_principle").addClass("has-error");
            $(".invalid_area_corporate_principle").html("Area Principle tidak boleh kosong");
            area_corporate.focus();
        } else {
            $(".listcoorporate_area_principle").removeClass("has-error");
            $(".invalid_area_corporate_principle").html("");
        }

        if (province.val() == "") {
            $(".listcoorporate_province_principle").addClass("has-error");
            $(".invalid_provinsi_corporate_principle").html("Provinsi Principle tidak boleh kosong");
            province.focus();
        } else {
            $(".listcoorporate_province_principle").removeClass("has-error");
            $(".invalid_provinsi_corporate_principle").html("");
        }

        if (city.val() == "") {
            $(".listcoorporate_city_principle").addClass("has-error");
            $(".invalid_kota_corporate_principle").html("Kota Principle tidak boleh kosong");
            city.focus();
        } else {
            $(".listcoorporate_city_principle").removeClass("has-error");
            $(".invalid_kota_corporate_principle").html("");
        }

        if (districts.val() == "") {
            $(".listcoorporate_districts_principle").addClass("has-error");
            $(".invalid_kecamatan_corporate_principle").html("Kecamatan Principle tidak boleh kosong");
            districts.focus();
        } else {
            $(".listcoorporate_districts_principle").removeClass("has-error");
            $(".invalid_kecamatan_corporate_principle").html("");
        }

        if (ward.val() == "") {
            $(".listcoorporate_ward_principle").addClass("has-error");
            $(".invalid_kelurahan_corporate_principle").html("Kelurahan Principle tidak boleh kosong");
            ward.focus();
        } else {
            $(".listcoorporate_ward_principle").removeClass("has-error");
            $(".invalid_kelurahan_corporate_principle").html("");
        }

        if (kodepos_corporate.val() == "") {
            $(".txtpostalcode_coorporate_principle").addClass("has-error");
            $(".invalid_kode_pos_corporate_principle").html("Kode Pos Principle tidak boleh kosong");
            kodepos_corporate.focus();
        } else {
            $(".txtpostalcode_coorporate_principle").removeClass("has-error");
            $(".invalid_kode_pos_corporate_principle").html("");
        }

        if (name_contact_person.val() == "") {
            $(".txtname_contact_person_principle").addClass("has-error");
            $(".invalid_nama_contact_person_principle").html("Nama Contact Person tidak boleh kosong");
            name_contact_person.focus();
        } else {
            $(".txtname_contact_person_principle").removeClass("has-error");
            $(".invalid_nama_contact_person_principle").html("");
        }

        if (phone_contact_person.val() == "") {
            $(".txtphone_contact_person_principle").addClass("has-error");
            $(".invalid_telepon_contact_person_principle").html("Telepon Contact Person tidak boleh kosong");
            phone_contact_person.focus();
        } else {
            $(".txtphone_contact_person_principle").removeClass("has-error");
            $(".invalid_telepon_contact_person_principle").html("");
        }

        if (kreditlimit_contact_person.val() == "") {
            $(".txtkreditlimit_contact_person_principle").addClass("has-error");
            $(".invalid_kredit_limit_contact_person_principle").html("Kredit Limit Contact Person tidak boleh kosong");
            kreditlimit_contact_person.focus();
        } else {
            $(".txtkreditlimit_contact_person_principle").removeClass("has-error");
            $(".invalid_kredit_limit_contact_person_principle").html("");
        }

    }

    
</script>