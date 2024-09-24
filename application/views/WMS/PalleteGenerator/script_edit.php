<script type="text/javascript">
var arr_tmp_sj_id = [];
loadingBeforeReadyPage()
$(document).ready(
    function() {
        select2();
        getDataPreffixPallete();
        getPalleteGenerateByID();

    }
)

function getPalleteGenerateByID() {
    id = $('#palleteGenerateID').val();
    $.ajax({
        type: "POST",
        url: "<?= base_url("WMS/PalleteGenerator/getPalleteGenerateByID") ?>",
        data: {
            id: id
        },
        dataType: "JSON",
        success: function(response) {
            $('#perusahaan_sj').val(response.pg.perusahaanID).trigger('change');
            $('#principle_sj').val(response.pg.principleID).trigger('change');
            $('#tgl_now').val(response.pg.tgl);

            if (response.pgd != 0) {
                var no = 1;
                $.each(response.pgd, function(i, v) {
                    $("#tb_sj_pallete > tbody").append(
                        `<tr>
					<td style="display: none">
					<input type="hidden" id="pgd_id[]" name="pgd_id[]" value="${v.detailID}" />
					</td>
                      <td width="10%" class="text-center">${no ++}</td>
                      <td width="45%" class="text-center">${v.sj_kode}</td>
                      <td width="45%" class="text-center">${v.sj_eksternal}</td>
					  </tr>`
                    );
                })
            }

            if (response.pgd2 != 0) {
                $.each(response.pgd2, function(i, v) {

                    var datetime = new Date(v.pgd2LastPrint);
                    var tahun = datetime.getFullYear();
                    var bulan = datetime.getMonth() + 1;
                    var hari = datetime.getDate();
                    var hours = datetime.getHours();
                    var minutes = datetime.getMinutes();
                    var seconds = datetime.getSeconds();

                    console.log(bulan);
                    if (bulan < 10) {
                        bulan = "0" + bulan;
                    }
                    if (hari < 10) {
                        hari = "0" + hari;
                    }
                    if (hours < 10) {
                        hours = "0" + hours;
                    }
                    if (minutes < 10) {
                        minutes = "0" + minutes;
                    }
                    if (seconds < 10) {
                        seconds = "0" + seconds;
                    }

                    var lastPrint = '';
                    if (v.pgd2LastPrint == null) {
                        lastPrint = null;
                    } else {
                        lastPrint = tahun + "-" + bulan + "-" + hari + " " + hours + ":" + minutes +
                            ":" + seconds;
                    }


                    if (v.pgd2IsAktif == 0) {
                        isAktif = "No"
                    } else {
                        isAktif = "Yes"
                    }

                    let str = "";

                    str +=
                        "<input type='checkbox' class='form-control check_item' style='transform: scale(0.5)' onchange='checkButton(this)' name='chk-data-result[]' id='chk-data-result[]' value='" +
                        v.pgd2ID + "." + isAktif + "'/>";

                    $("#tb_result_pallete > tbody").append(
                        `<tr>
						<td style="display: none">
						<input type="hidden" id="pgd2_id[]" name="pgd2_id[]" value="${v.pgd2ID}" />
						</td>
						<td  width="5%" class="text-center">${str}</td>
						<td  width="5%" class="text-center" style="display: none" >${v.pjID}</td>
						<td  width="35%" class="text-center">${v.pgd2Kode}</td>
						<td  width="35%" class="text-center">${lastPrint}</td>
						<td  width="10%" class="text-center">${v.pgd2PrintCount}</td>
						<td  width="10%" class="text-center">${isAktif}</td>
						</tr>`
                    );
                })
            }
        }
    })
}

function getDataPreffixPallete() {
    $.ajax({
        type: "GET",
        url: "<?= base_url('WMS/PalleteGenerator/getDataPreffixPallete'); ?>",
        dataType: "JSON",
        success: function(data) {
            $.each(data, function(i, v) {
                $('#preffix_pallete').append('<option value="' + v.pallet_jenis_id + '">' + v
                    .pallet_jenis_kode + '</option>');
            });
        }
    })
}

// function getPrinciple() {
//     $("#principle_sj").append('<option value="">--Pilih Principle--</option>')
// }


$("#perusahaan_sj").on("change", function() {
    var id = $(this).val();
    var principle = "<?= $byid->principleID ?>";
    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/PalleteGenerator/getDataPrincipleByClientWmsID'); ?>",
        data: {
            id: id,
            principle: principle
        },
        dataType: "JSON",
        success: function(response) {
            $("#principle_sj").html(response);
            // $("#principle_sj").val(principleID);
        }
    })
})

$("#pilihSuratJalan").on("click", function() {
    $("#table_list_pilih_sj > tbody").html('');

    perusahaan_sj = $("#perusahaan_sj").val();
    principle_sj = $("#principle_sj").val();

    if (perusahaan_sj == "") {
        message("Error!", "Perusahaan tidak boleh kosong", "error");
        return false;
    } else if (principle_sj == "") {
        message("Error!", "Principle tidak boleh kosong", "error");
        return false;
    } else {
        $.ajax({
            type: "POST",
            url: "<?= base_url("WMS/PalleteGenerator/getDataSuratJalan") ?>",
            data: {
                perusahaan_sj: perusahaan_sj,
                principle_sj: principle_sj
            },
            dataType: "JSON",
            success: function(response) {
                $("#perusahaan_disable").val(response.perusahaan.client_wms_nama);
                $("#principle_disable").val(response.principle.nama);
                $("#check-all-pilih-sj").prop("checked", false);

                if (response.suratjalan.length > 0) {
                    if ($.fn.DataTable.isDataTable('#table_list_pilih_sj')) {
                        $('#table_list_pilih_sj').DataTable().destroy();
                    }
                    $('#table_list_pilih_sj > tbody').empty();

                    $.each(response.suratjalan, function(i, v) {
                        let str = "";

                        str +=
                            "<input type='checkbox' class='form-control check_item' style='transform: scale(1.5)' name='chk-data[]' id='chk-data[]' value='" +
                            v.sj_id + "'/>";

                        $("#table_list_pilih_sj > tbody").append(
                            `<tr>
							<td class="text-center">${str}</td>
                      <td>${v.sj_kode}</td>
                      <td>${v.no_sj}</td>
                      <td>${v.tgl}</td>
                      <td>${v.pt}</td>
                      <td class="text-center">${v.p_kode}</td>
                      <td class="text-center">${v.tipe}</td>
                      <td class="text-center">${v.keterangan}</td>
                      <td class="text-center">${v.status}</td>
					  </tr>`
                        );
                    });
                } else {
                    if ($.fn.DataTable.isDataTable('#table_list_pilih_sj')) {
                        $('#table_list_pilih_sj').DataTable().destroy();
                    }
                    $('#table_list_pilih_sj > tbody').empty();
                }

                $('#table_list_pilih_sj').DataTable({
                    columnDefs: [{
                        sortable: false,
                        targets: [0, 1, 2, 3, 4, 5, 6, 7]
                    }],
                    lengthMenu: [
                        [-1],
                        ['All']
                    ],
                });

                $("#modalPilihSJ").modal("show");
            }
        })
    }
})

function checkAllSJ(e) {
    var checkboxes = $("input[name='chk-data[]']");

    if (e.checked) {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }
}



function checkAllPallete(e) {
    var checkboxes = $("input[name='chk-data-result[]']");
    var plus_checkboxes = $("input[name='chk-data-pallet[]']");

    if (e.checked) {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
        for (i = 0; i < plus_checkboxes.length; i++) {
            if (plus_checkboxes[i].type == 'checkbox') {
                plus_checkboxes[i].checked = true;
            }
        }
        $('.btn-save-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').css('pointer-events', 'none');
        $('.btn-del-pallete').removeAttr('disabled', 'disabled');
        $('.btn-print_pallete').removeAttr('disabled', 'disabled');
    } else {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
        for (i = 0; i < plus_checkboxes.length; i++) {
            if (plus_checkboxes[i].type == 'checkbox') {
                plus_checkboxes[i].checked = false;
            }
        }
        $('.btn-save-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('style');
        $('.btn-del-pallete').attr('disabled', 'disabled');
        $('.btn-print_pallete').attr('disabled', 'disabled');
    }
}

$('.btn_pilih_sj').on('click', function() {
    if (arr_tmp_sj_id == 0) {
        $("#tb_sj_pallete > tbody tr").each(function() {
            let pgd_sj_id = $(this).find("td:eq(0) input[name='pgd_id[]']").val();
            arr_tmp_sj_id.push(
                pgd_sj_id
            );
        })
    }

    let arr_chk = [];
    var checkboxes = $("input[name='chk-data[]']");
    for (i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked == true) {
            arr_chk.push(checkboxes[i].value);
        }
    }

    if (arr_chk.length == 0) {
        message("Info!", "Pilih data yang akan dipilih", "info");
    } else {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PalleteGenerator/getDataSuratJalanPallete') ?>",
            data: {
                id: arr_chk,
            },
            dataType: "JSON",
            success: function(response) {
                $('#tb_sj_pallete > tbody').html('');
                no = 1;
                $.each(response, function(i, v) {
                    $("#tb_sj_pallete > tbody").append(
                        `<tr>
						<td style="display: none">
						<input type="hidden" id="sj_id[]" name="sj_id[]" value="${v.sj_id}" />
						</td>
						<td width="10%" class="text-center">${no ++}</td>
                      <td width="45%" class="text-center">${v.sj_kode}</td>
                      <td width="45%" class="text-center">${v.sj_eksternal}</td>
					  </tr>`
                    );
                })
                $("#modalPilihSJ").modal("hide");
            }
        })
    }
})

$('#btn_generate').on('click', function() {
    jml_gen = $('#jumlah').val();
    preffix_pallete = $('#preffix_pallete').val();

    if (jml_gen == 0 || jml_gen == null) {
        message("Error!", "Isi jumlah generate dengan benar", "error")
    } else {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PalleteGenerator/getJumlahGenerate') ?>",
            data: {
                jml_gen: jml_gen,
                preffix_pallete: preffix_pallete
            },
            dataType: "JSON",
            success: function(response) {
                $.each(response, function(i, v) {
                    let str = "";
                    str +=
                        "<input type='checkbox' onchange='checkButton(this)' class='form-control check_item' style='transform: scale(1.5)' name='chk-data-pallet[]' id='chk-data-pallet[]' value=''/>";

                    $("#tb_result_pallete > tbody").append(
                        `<tr>
						<td style="display: none">
						<input type="hidden" id="pgd2_id[]" name="pgd2_id[]" value="" />
						</td>
						<td  width="5%" class="text-center">${str}</td>
						<td  width="5%" class="text-center" style="display: none">${preffix_pallete}</td>
						<td  width="35%" class="text-center">${v}</td>
						<td  width="35%" class="text-center">null</td>
						<td  width="10%" class="text-center">null</td>
						<td  width="10%" class="text-center">No</td>
						</tr>`
                    );
                })

            }
        })
    }
})

$(".btn-save-pallete").on('click', function() {
    let perusahaan = $("#perusahaan_sj").val();
    let principle = $("#principle_sj").val();
    let preffix_pallete = $("#preffix_pallete").val();
    let id = $('#palleteGenerateID').val();
    let lastUpdated = $('#lastUpdated').val();
    let count_sj = $("#tb_sj_pallete > tbody tr").length;
    let count_result = $("#tb_result_pallete > tbody tr").length;

    if (count_result == 0) {
        message("Error!", "Pallete masih kosong, silahkan tambah pallete terlebih dahulu", "error");
    } else {
        var arr_sj_id = [];
        $("#tb_sj_pallete > tbody tr").each(function() {
            let sj_id = $(this).find("td:eq(0) input[type='hidden']").val();
            arr_sj_id.push({
                sj_id
            });
        })

        var arr_pallete_kode = [];
        $("#tb_result_pallete > tbody tr").each(function() {
            let pallete_id = $(this).find("td:eq(0) input[type='hidden']").val();
            let pallete_preffix = $(this).find("td:eq(2)").text();
            let pallete_kode = $(this).find("td:eq(3)").text();
            let last_printed = $(this).find("td:eq(4)").text();
            let print_amount = $(this).find("td:eq(5)").text();
            let isAktif = $(this).find("td:eq(6)").text();
            arr_pallete_kode.push({
                pallete_id: pallete_id,
                pallete_preffix: pallete_preffix,
                pallete_kode: pallete_kode,
                last_printed: last_printed,
                print_amount: print_amount,
                isAktif: isAktif,
            });
        })

        messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Update', 'Tidak, Tutup')
            .then((result) => {
                if (result.value == true) {
                    requestAjax("<?= base_url('WMS/PalleteGenerator/saveUpdatePalleteGenerate'); ?>", {
                        id: id,
                        perusahaan: perusahaan,
                        principle: principle,
                        preffix_pallete: preffix_pallete,
                        arr_tmp_sj_id: arr_tmp_sj_id,
                        arr_sj_id: arr_sj_id,
                        arr_pallete_kode: arr_pallete_kode,
                        lastUpdated
                    }, "POST", "JSON", function(response) {
                        if (response == 1) {
                            message_topright("success", "Data berhasil disimpan");
                            setTimeout(() => {
                                location.href =
                                    "<?= base_url("WMS/PalleteGenerator/PalleteGeneratorMenu") ?>";
                            }, 500);
                        }

                        if (response == 0) return message_topright("error", "Data gagal disimpan");
                        if (response == 2) return messageNotSameLastUpdated()
                    }, "#btn-save-pallete")
                }
            });
    }
})

function checkButton(e) {
    if (e.checked) {
        $('.btn-save-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').css('pointer-events', 'none');
        $('.btn-del-pallete').removeAttr('disabled', 'disabled');
        $('.btn-print_pallete').removeAttr('disabled', 'disabled');
    } else {
        $('.btn-save-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('style');
        $('.btn-del-pallete').attr('disabled', 'disabled');
        $('.btn-print_pallete').attr('disabled', 'disabled');
    }
}

$('.btn-del-pallete').click(function() {
    arr_chk_pg = [];
    var plus_checkboxes = $("input[name='chk-data-pallet[]']:checked");
    var checkboxes = $("input[name='chk-data-result[]']:checked");

    if (checkboxes.length == 0) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Jika dihapus, data yang sudah ada akan hilang!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Tidak, Tutup"
        }).then((result) => {
            $("table input[name='chk-data-pallet[]']:checked")
                .parent()
                .parent().remove();
        })
        $('.btn-save-pallete').removeAttr('disabled',
            'disabled');
        $('.btn-back-pallete').removeAttr('disabled',
            'disabled');
        $('.btn-del-pallete').attr('disabled', 'disabled');
        $('.btn-print_pallete').attr('disabled', 'disabled');
    } else {
        for (i = 0; i < checkboxes.length; i++) {
            arr_chk_pg.push(checkboxes[i].value);

            var cek = checkboxes[i].value;
            var explode = cek.split(".");

            if (explode[1] == "Yes") {
                message("Error!", "Pallete aktif tidak dapat dihapus", "error");
                return false;
            } else {
                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Jika dihapus, data yang sudah ada akan hilang!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus",
                    cancelButtonText: "Tidak, Tutup"
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('WMS/PalleteGenerator/hapusPalletGeneratorDetail2') ?>",
                            data: {
                                arr_chk_pg: arr_chk_pg
                            },
                            dataType: "JSON",
                            success: function(data) {
                                if (data == 1) {
                                    $("table input[name='chk-data-result[]']:checked")
                                        .parent()
                                        .parent().remove();
                                    $("table input[name='chk-data-pallet[]']:checked")
                                        .parent()
                                        .parent().remove();
                                    $('#check-all-pallete').prop('checked', false);

                                    $('.btn-save-pallete').removeAttr('disabled',
                                        'disabled');
                                    $('.btn-back-pallete').removeAttr('disabled',
                                        'disabled');
                                    $('.btn-del-pallete').attr('disabled', 'disabled');
                                    $('.btn-print_pallete').attr('disabled', 'disabled');
                                }
                            }
                        })
                    }
                })
            }
        }
    }
});

$(".btn-print_pallete").on('click', function() {
    arr_cetak = [];
    arr_tmp_pallet = [];
    var checkboxes = $("input[name='chk-data-result[]']:checked");
    var plus_checkboxes = $("input[name='chk-data-pallet[]']:checked");


    if (plus_checkboxes.length > 0) {
        message("Error!", "Terdapat data yang belum tersimpan", "error");
        return false;
    } else {
        $("#tb_result_pallete > tbody tr").each(function() {
            let pallete_id = $(this).find("td:eq(0) input[type='hidden']").val();
            let pallete_preffix = $(this).find("td:eq(2)").text();
            let pallete_kode = $(this).find("td:eq(3)").text();
            let last_printed = $(this).find("td:eq(4)").text();
            let print_amount = $(this).find("td:eq(5)").text();
            let isAktif = $(this).find("td:eq(6)").text();
            arr_tmp_pallet.push({
                pallete_id: pallete_id,
                pallete_preffix: pallete_preffix,
                pallete_kode: pallete_kode,
                last_printed: last_printed,
                print_amount: print_amount,
                isAktif: isAktif,
            });
        })

        for (i = 0; i < checkboxes.length; i++) {
            var cek = checkboxes[i].value;
            var explode = cek.split(".");
            arr_cetak.push(explode[0]);
        }

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PalleteGenerator/saveUpdatePrintedPalleteGenerate'); ?>",
            data: {
                id: id,
                arr_cetak: arr_cetak
            },
            dataType: "JSON",
            success: function(response) {
                $("#tb_result_pallete > tbody").html('');
                $.each(response, function(i, v) {
                    var datetime = new Date(v.pgd2LastPrint);
                    var tahun = datetime.getFullYear();
                    var bulan = datetime.getMonth() + 1;
                    var hari = datetime.getDate();
                    var hours = datetime.getHours();
                    var minutes = datetime.getMinutes();
                    var seconds = datetime.getSeconds();

                    if (bulan < 10) {
                        bulan = "0" + bulan;
                    }
                    if (hari < 10) {
                        hari = "0" + hari;
                    }
                    if (hours < 10) {
                        hours = "0" + hours;
                    }
                    if (minutes < 10) {
                        minutes = "0" + minutes;
                    }
                    if (seconds < 10) {
                        seconds = "0" + seconds;
                    }

                    var lastPrint = '';
                    if (v.pgd2LastPrint == null) {
                        lastPrint = null;
                    } else {
                        lastPrint = tahun + "-" + bulan + "-" + hari + " " + hours + ":" +
                            minutes +
                            ":" + seconds;
                    }

                    if (v.pgd2IsAktif == 0) {
                        isAktif = "No"
                    } else {
                        isAktif = "Yes"
                    }

                    let str = "";

                    str +=
                        "<input type='checkbox' class='form-control check_item' style='transform: scale(0.5)' onchange='checkButton(this)' name='chk-data-result[]' id='chk-data-result[]' value='" +
                        v.pgd2ID + "." + isAktif + "'/>";

                    $("#tb_result_pallete > tbody").append(
                        `<tr>
								<td style="display: none">
								<input type="hidden" id="pgd2_id[]" name="pgd2_id[]" value="${v.pgd2ID}" />
								</td>
								<td  width="5%" class="text-center">${str}</td>
								<td  width="5%" class="text-center" style="display: none" >${v.pjID}</td>
								<td  width="35%" class="text-center">${v.pgd2Kode}</td>
								<td  width="35%" class="text-center">${lastPrint}</td>
								<td  width="10%" class="text-center">${v.pgd2PrintCount}</td>
								<td  width="10%" class="text-center">${isAktif}</td>
							</tr>`
                    );
                })

                for (i = 0; i < arr_tmp_pallet.length; i++) {
                    if (arr_tmp_pallet[i].pallete_id == '') {
                        let str = "";

                        str +=
                            "<input type='checkbox' onchange='checkButton(this)' class='form-control check_item' style='transform: scale(0.5)' name='chk-data-pallet[]' id='chk-data-pallet[]' value=''/>";

                        $("#tb_result_pallete > tbody").append(
                            `<tr>
						<td style="display: none">
						<input type="hidden" id="pgd2_id[]" name="pgd2_id[]" value="" />
						</td>
						<td  width="5%" class="text-center">${str}</td>
						<td  width="5%" class="text-center" style="display: none">${arr_tmp_pallet[i].pallete_preffix}</td>
						<td  width="35%" class="text-center">${arr_tmp_pallet[i].pallete_kode}</td>
						<td  width="35%" class="text-center">null</td>
						<td  width="10%" class="text-center">null</td>
						<td  width="10%" class="text-center">No</td>
						</tr>`
                        );
                    }
                }

                $('.btn-save-pallete').removeAttr('disabled', 'disabled');
                $('.btn-back-pallete').removeAttr('disabled', 'disabled');
                $('.btn-back-pallete').removeAttr('style');
                $('.btn-del-pallete').attr('disabled', 'disabled');
                $('.btn-print_pallete').attr('disabled', 'disabled');

                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/PalleteGenerator/saveSessionCetak') ?>",
                    data: {
                        id: arr_cetak,
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response == 1) {
                            window.open(
                                '<?= base_url("WMS/PalleteGenerator/print") ?>'
                            );
                        }

                    }
                })
            }
        })
    }
})

function DeleteSJ(row, id) {

    var row = row.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

$("#btnCloseSJ").on("click", function() {
    $("#modalPilihSJ").modal("hide");
})

function select2() {
    $(".select2").select2({
        width: "100%"
    });
}
</script>