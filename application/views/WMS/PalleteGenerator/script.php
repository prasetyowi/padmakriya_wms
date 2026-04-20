<script type="text/javascript">
loadingBeforeReadyPage()
$(document).ready(
    function() {
        select2();
        if ($('#filter-do-date').length > 0) {
            $('#filter-do-date').daterangepicker({
                'applyClass': 'btn-sm btn-success',
                'cancelClass': 'btn-sm btn-default',
                locale: {
                    "format": "DD/MM/YYYY",
                    applyLabel: 'Apply',
                    cancelLabel: 'Cancel',
                },
                'startDate': '<?= date("01-m-Y") ?>',
                'endDate': '<?= date("t-m-Y") ?>'
            });
        }
        // getDataPalletGenerator();
        getDataPreffixPallete();
    }
)
$("#filter_perusahaan").on("change", function() {
    var id = $(this).val();
    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/PalleteGenerator/getDataPrincipleByClientWmsID'); ?>",
        data: {
            id: id
        },
        dataType: "JSON",
        success: function(response) {
            $("#filter_principle").html(response);
        }
    })
})

$('#btn-search-data-pallet_gen').on('click', function() {
    $('#loadingsearch').show();

    var tgl = $("#filter-do-date").val();
    var perusahaan = $("#filter_perusahaan").val();
    var principle = $("#filter_principle").val();

    $.ajax({
        type: "POST",
        url: "<?= base_url("WMS/PalleteGenerator/getDataFilterPalletGenerator") ?>",
        data: {
            tgl: tgl,
            perusahaan: perusahaan,
            principle: principle
        },
        dataType: "JSON",
        success: function(data) {
            console.log(data);
            $("#table_list_pallet_generator > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_list_pallet_generator')) {
                $('#table_list_pallet_generator').DataTable().clear();
                $('#table_list_pallet_generator').DataTable().destroy();
            }

            if (data != 0) {
                no = 1;
                $.each(data, function(i, v) {
                    $("#table_list_pallet_generator > tbody").append(`
						<tr>
					<td class="text-center">${no ++}</td>
					<td class="text-center">${v.pg_tgl}</td>
					<td class="text-center">${v.client_wms_nama}</td>
					<td class="text-center">${v.principle_nama}</td>
					<td class="text-center"><a href="<?= base_url("WMS/PalleteGenerator/edit/") ?>${v.pallet_generate_id}" class="btn btn-warning form-control"><i class="fa fa-pencil"></i></a></td>
					</tr>
						`)
                });
                $('#table_list_pallet_generator').DataTable({
                    'ordering': false,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'All']
                    ],
                });
            } else {
                $("#table_list_pallet_generator > tbody").append(`
								<tr>
									<td colspan="5" class="text-danger text-center">
										Data Kosong
									</td>
								</tr>
					`);
            }

            $('#loadingsearch').hide();
        }
    })


})

function getDataPalletGenerator() {
    $.ajax({
        type: "GET",
        url: "<?= base_url("WMS/PalleteGenerator/getPalletGenerator"); ?>",
        dataType: "JSON",
        success: function(data) {
            no = 1;
            $.each(data, function(i, v) {
                $('#table_list_pallet_generator > tbody').append(
                    `<tr>
					<td class="text-center">${no ++}</td>
					<td class="text-center">${v.pg_tgl}</td>
					<td class="text-center">${v.client_wms_nama}</td>
					<td class="text-center">${v.principle_nama}</td>
					<td class="text-center"><a href="<?= base_url("WMS/PalleteGenerator/edit/") ?>${v.pallet_generate_id}" class="btn btn-warning form-control"><i class="fa fa-pencil"></i></a></td>
					</tr>
					`
                )
            })

            $('#table_list_pallet_generator').DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"],
                ]
            });
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

$("#perusahaan_sj").on("change", function() {
    var id = $(this).val();
    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/PalleteGenerator/getDataPrincipleByClientWmsID'); ?>",
        data: {
            id: id
        },
        dataType: "JSON",
        success: function(response) {
            $("#principle_sj").html(response);
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
                            "<input type='checkbox' class='form-control check_item' style='transform: scale(0.5)' name='chk-data[]' id='chk-data[]' value='" +
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

    if (e.checked) {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
        $('.btn-del-pallete').removeAttr('disabled', 'disabled');
        $('.btn-save-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').css('pointer-events', 'none');
    } else {
        for (i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
        $('.btn-del-pallete').attr('disabled', 'disabled');
        $('.btn-save-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('style');
    }
}

$('.btn_pilih_sj').on('click', function() {
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
                $("#tb_sj_pallete > tbody").html('');
                var no = 1;
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
                // var no = 1;
                $.each(response, function(i, v) {
                    let str = "";

                    str +=
                        "<input type='checkbox' onchange='checkButton(this)' class='form-control check_item' style='transform: scale(0.5)' name='chk-data-result[]' id='chk-data-result[]' value='" +
                        i + "'/>";

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

    // if (perusahaan == "") {
    //     message("Error!", "Perusahaan tidak boleh kosong", "error");
    // } else if (principle == "") {
    //     message("Error!", "Principle tidak boleh kosong", "error")
    // } else {
    //     let count_sj = $("#tb_sj_pallete > tbody tr").length;
    //     if (count_sj == 0) {
    //         message("Error!", "Surat Jalan masih kosong, silahkan tambah surat jalan terlebih dahulu", "error");
    //     } else {
    //         let count_result = $("#tb_result_pallete > tbody tr").length;
    //         if (count_result == 0) {
    //             message("Error!", "Pallete masih kosong, silahkan tambah pallete terlebih dahulu", "error");
    //         } else {
    var arr_sj_id = [];
    $("#tb_sj_pallete > tbody tr").each(function() {
        let sj_id = $(this).find("td:eq(0) input[type='hidden']").val();
        arr_sj_id.push({
            sj_id
        });
    })

    var arr_pallete_kode = [];
    $("#tb_result_pallete > tbody tr").each(function() {
        let pallete_kode = $(this).find("td:eq(3)").text();
        let preffix_pallete = $(this).find("td:eq(2)").text();
        arr_pallete_kode.push({
            pallete_kode: pallete_kode,
            preffix_pallete: preffix_pallete,
        });
    })

    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/PalleteGenerator/savePalleteGenerate'); ?>",
        data: {
            perusahaan: perusahaan,
            principle: principle,
            arr_sj_id: arr_sj_id,
            arr_pallete_kode: arr_pallete_kode
        },
        dataType: "JSON",
        success: function(response) {
            if (response == 1) {
                message_topright("success", "Data berhasil disimpan");
                setTimeout(() => {
                    location.href =
                        "<?= base_url("WMS/PalleteGenerator/PalleteGeneratorMenu") ?>";
                }, 500);
            } else {
                message_topright("error", "Data gagal disimpan");
            }
        }
    })
    // }
    // }
    // }
})

function checkButton(e) {
    if (e.checked) {
        $('.btn-save-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').attr('disabled', 'disabled');
        $('.btn-back-pallete').css('pointer-events', 'none');
        $('.btn-del-pallete').removeAttr('disabled', 'disabled');
    } else {
        $('.btn-save-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('disabled', 'disabled');
        $('.btn-back-pallete').removeAttr('style');
        $('.btn-del-pallete').attr('disabled', 'disabled');
    }
}

$('.btn-del-pallete').click(function() {
    $("table input[name='chk-data-result[]']:checked").parent().parent().remove();
    $('#check-all-pallete').prop('checked', false);
});

function DeleteSJ(row, index) {
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

// function message(msg, msgtext, msgtype) {
//     Swal.
//     fire(
//         msg, msgtext, msgtype);
// }

// function message_topright(type, msg) {
//     const Toast = Swal.mixin({
//         toast: true,
//         position: "top-end",
//         showConfirmButton: false,
//         timer: 3000,
//         didOpen: (toast) => {
//             toast.addEventListener("mouseenter", Swal.stopTimer);
//             toast.addEventListener("mouseleave", Swal.resumeTimer);
//         },
//     });

//     Toast.fire({
//         icon: type,
//         title: msg,
//     })
// }
</script>