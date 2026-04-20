<script>
let global_id = $("#global_id_edit").val();
let global_sku_id = [];
let global_data = [];
const html5QrCode = new Html5Qrcode("preview_edit");
loadingBeforeReadyPage()
$(document).ready(function() {
    select2();

    $(document).on("input", ".numeric", function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    $.ajax({
        url: "<?= base_url('WMS/Pemusnahan/getDataKoreksiEditById'); ?>",
        type: "POST",
        data: {
            id: global_id
        },
        dataType: "JSON",
        async: false,
        success: function(response) {
            const header = response.header;
            const detail = response.detail;

            $("#koreksi_draft_id_edit").val(header.draft_id);
            $("#gudang_id_edit").val(header.gudang_id);
            $("#principle_id_edit").val(header.principle_id);
            $("#keterangan_edit").val(header.keterangan);
            $("#tipe_id_edit").val(header.tipe_id);
            $("#checker_id_edit").val(header.checker_id);
            $("#lastUpdated").val(header.tr_koreksi_stok_pallet_tgl_update);

            $("#gudang_nama_edit").val(header.gudang);

            $("#no_koreksi_edit").val(header.kode);
            $("#tgl_edit").val(header.tgl);
            $("#no_koreksi_draft_edit").val(header.kode_draft);
            $("#gudang_asal_edit").val(header.gudang);
            $("#principle_edit").val(header.principle);
            $("#checker_edit").val(header.checker);
            $("#tipe_transaksi_edit").val(header.tipe);
            $("#status_edit").val(header.status);

            $.each(detail, function(i, v) {
                global_data.push(v);
            })

            initDetailKoreksiDraft(detail, header.gudang, header.gudang_id);

            //check exist data in tr koreksi stok
            $.ajax({
                url: "<?= base_url('WMS/Pemusnahan/check_exist_in_tr_koreksi_edit') ?>",
                type: "POST",
                data: {
                    id: header.id
                },
                dataType: "JSON",
                async: false,
                success: function(response) {
                    if (response == null) {
                        $("#konfirmasi_koreksi_edit").attr("disabled", true);
                    } else {
                        $("#konfirmasi_koreksi_edit").attr("disabled", false);
                    }
                }
            });


            // UpdateQtyAmbil(detail, header.gudang, header.gudang_id);
        }
    });
});

// function message(msg, msgtext, msgtype) {
// 	Swal.fire(msg, msgtext, msgtype);
// }

// function message_topright(type, msg) {
// 	const Toast = Swal.mixin({
// 		toast: true,
// 		position: "top-end",
// 		showConfirmButton: false,
// 		timer: 3000,
// 		didOpen: (toast) => {
// 			toast.addEventListener("mouseenter", Swal.stopTimer);
// 			toast.addEventListener("mouseleave", Swal.resumeTimer);
// 		},
// 	});

// 	Toast.fire({
// 		icon: type,
// 		title: msg,
// 	});
// }

function select2() {
    $(".select2").select2({
        width: "100%"
    });
}

function initDetailKoreksiDraft(data, gudang, gudang_id) {
    $("#show_data_edit").show();

    if ($.fn.DataTable.isDataTable('#table_data_tambah_koreksi_stok_edit')) {
        $('#table_data_tambah_koreksi_stok_edit').DataTable().destroy();
    }

    $("#table_data_tambah_koreksi_stok_edit > tbody").empty();

    if (data.length != 0) {
        $.each(data, function(i, v) {
            $("#table_data_tambah_koreksi_stok_edit > tbody").append(`
            <tr>
                <td class="text-center">${i + 1}</td>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id_edit" id="sku_stock_id_edit" value="${v.id}"/></td>
                <td>${v.sku_nama} <input type="hidden" name="sku_id_edit" id="sku_id_edit" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_plan}</td>
                <td class="text-center">${v.qty_aktual == null ? 0 : v.qty_aktual}</td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Pilih Pallet" data-id="${v.id}" data-gudang="${gudang}" data-gudang-id="${gudang_id}" data-ed="${v.ed}" data-sku-kode="${v.sku_kode}" data-sku-nama="${v.sku_nama}" class="btn btn-info pilihpallet_edit" ><i class="fas fa-hand-point-up"></i></button> 
                </td>
            </tr>
        `);
        });
    } else {
        $("#table_data_tambah_koreksi_stok_edit > tbody").html(
            `<tr><td colspan="9" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
    }

    $('#table_data_tambah_koreksi_stok_edit').DataTable();
}

$(document).on("click", ".pilihpallet_edit", function() {
    let id = $(this).attr('data-id');
    let gudang_id = $(this).attr('data-gudang-id');
    let gudang = $(this).attr('data-gudang');
    let ed = $(this).attr('data-ed');
    let sku_kode = $(this).attr('data-sku-kode');
    let sku_nama = $(this).attr('data-sku-nama');
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();

    $("#start_scan_edit").attr('data-id', id);
    $("#start_scan_edit").attr('data-gudang-id', gudang_id);
    $("#start_scan_edit").attr('data-ed', ed);

    $("#input_manual_edit").attr('data-id', id);
    $("#input_manual_edit").attr('data-gudang-id', gudang_id);
    $("#input_manual_edit").attr('data-ed', ed);


    $("#list_data_pilih_pallet_edit").modal("show");
    $("#gudang_asal_pallet_edit").val(gudang);
    $("#sku_kode_pallet_edit").val(sku_kode);
    $("#sku_nama_pallet_edit").val(sku_nama);
    $("#ed_pallet_edit").val(ed);

    get_data(koreksi_draft_id, id, gudang_id, ed);
});

function get_data(koreksi_draft_id, id, gudang_id, ed) {
    $.ajax({
        url: "<?= base_url('WMS/Pemusnahan/getDataPalletBySkuStockId'); ?>",
        type: "POST",
        data: {
            koreksi_draft_id: koreksi_draft_id,
            id: id,
            gudang_id: gudang_id,
            ed: ed
        },
        dataType: "JSON",
        async: false,
        success: function(response) {
            if (response.length > 0) {
                if ($.fn.DataTable.isDataTable('#table_list_data_pilih_pallet_edit')) {
                    $('#table_list_data_pilih_pallet_edit').DataTable().destroy();
                }

                $("#table_list_data_pilih_pallet_edit > tbody").empty();
                $.each(response, function(i, v) {
                    let str = "";
                    let action = "";
                    let is_scan = "";
                    let is_value = 0;
                    if (v.scan == null) {
                        str +=
                            `<input type='checkbox' class='form-control check-item_edit' style="transform:scale(1.5)" disabled name='chk-data_edit' id='chk-data'/>`;

                        is_scan += "readonly";
                        is_value = 0;
                    } else if (v.scan == 0) {
                        str +=
                            `<input type='checkbox' class='form-control check-item_edit' style="transform:scale(1.5)" disabled name='chk-data_edit' id='chk-data'/>`;

                        is_scan += "readonly";
                        is_value = 0;
                    } else {
                        str +=
                            `<input type='checkbox' class='form-control check-item_edit' style="transform:scale(1.5)" disabled checked name='chk-data_edit' id='chk-data'/>`;

                        action +=
                            `<button type='button' class='form-control btn btn-danger batalkan_pilih_pallet_edit' name='batalkan_pilih_pallet_edit' id='batalkan_pilih_pallet_edit' title="batalkan pilih pallet" data-id="${id}" data-gudang-id="${gudang_id}" data-ed="${ed}" data-id-temp="${v.id_temp}" data-pallet-id="${v.pallet_id}" data-sku-stock-id="${v.sku_stock_id}"><i class="fas fa-xmark"></i></button>`;

                        is_scan += "";
                        is_value = v.qty_ambil == null ? 0 : v.qty_ambil;

                    }

                    $("#table_list_data_pilih_pallet_edit > tbody").append(`
              <tr class="text-center">
                <td>${str}</td>
                <td>${v.lokasi_rak}</td>
                <td>${v.lokasi_bin}</td>
                <td>${v.no_pallet}</td>
                <td>${v.ed}</td>
                <td>${v.qty}</td>
                <td>${v.sku_qty_plan_koreksi}</td>
                <td><input type='text' class='form-control numeric  UpdateQtyAmbil_edit' ${is_scan} name='qty_ambil[]' id='qty_ambil[]' data-id-temp="${v.id_temp}" data-pallet-id="${v.pallet_id}" data-qty="${v.qty}" data-sku-stock-id="${v.sku_stock_id}" value='${is_value}'/></td>
                <td>${action}</td>
              </tr>
          `);
                });

                $('#table_list_data_pilih_pallet_edit').DataTable({
                    columnDefs: [{
                        sortable: false,
                        targets: [0, 1, 2, 3, 4, 5, 6]
                    }],
                    lengthMenu: [
                        [-1],
                        ['All']
                    ],
                });
            } else {
                $("#table_list_data_pilih_pallet_edit > tbody").html(
                    `<tr><td colspan="7" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`
                    );
            }

        }
    })
}

$(document).on("change", ".UpdateQtyAmbil_edit", function() {
    let id_change = $("#id_change_no_draft_edit").val();
    let gudang_id = $("#gudang_id_edit").val();
    let gudang = $("#gudang_nama_edit").val();
    let id_temp = $(this).attr('data-id-temp');
    let sku_stock_id = $(this).attr('data-sku-stock-id');
    let pallet_id = $(this).attr('data-pallet-id');
    let qty_ready = parseInt($(this).attr('data-qty'));
    let qty_ambil = parseInt($(this).val());

    let new_qty_ = 0;
    $("#table_list_data_pilih_pallet_edit > tbody tr").each(function() {
        let qty_ = $(this).find("td:eq(7) input[type='text']").val();
        new_qty_ += parseInt(qty_);
    });

    if (qty_ambil <= qty_ready) {

        //requst to update qty
        $.ajax({
            url: "<?= base_url('WMS/Pemusnahan/UpdateQtyAmbilInDetail2Temp'); ?>",
            type: "POST",
            data: {
                id: id_temp,
                pallet_id: pallet_id,
                qty: qty_ambil
            },
            dataType: "JSON",
            async: false,
            success: function(response) {
                $('#table_data_tambah_koreksi_stok_edit').fadeOut("slow", function() {
                    $(this).hide();
                }).fadeIn("slow", function() {
                    $(this).show();
                    let new_data = global_data.findIndex((value) => value.id ==
                        sku_stock_id);
                    global_data[new_data]['qty_aktual'] = new_qty_;
                    initDetailKoreksiDraft(global_data, gudang, gudang_id)
                });
            }
        });
    } else {
        message("Error!", "Qty ambil melebihi jumlah qty yang ada", "error");
        return false;
    }
});

$(document).on("click", ".batalkan_pilih_pallet_edit", function() {
    let id = $(this).attr('data-id');
    let gudang_id = $(this).attr('data-gudang-id');
    let gudang = $("#gudang_nama_edit").val();
    let ed = $(this).attr('data-ed');
    let id_temp = $(this).attr('data-id-temp');
    let pallet_id = $(this).attr('data-pallet-id');
    let sku_stock_id = $(this).attr('data-sku-stock-id');
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();

    Swal.fire({
        title: "Apakah anda yakin?",
        text: "Ingin membatalkan pilih pallet ini!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Batalkan",
        cancelButtonText: "Tidak, Tutup"
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                url: "<?= base_url('WMS/Pemusnahan/cancel_pallet') ?>",
                type: "POST",
                data: {
                    id: id_temp,
                    pallet_id: pallet_id,
                    sku_stock_id: sku_stock_id
                },
                dataType: "JSON",
                success: function(data) {
                    if (data) {
                        message_topright("success", "Berhasil membatalkan pallet");
                        $('#table_list_data_pilih_pallet_edit').fadeOut("slow", function() {
                            $(this).hide();
                        }).fadeIn("slow", function() {
                            $(this).show();

                            get_data(koreksi_draft_id, id, gudang_id, ed);
                        });

                        $('#table_data_tambah_koreksi_stok_edit').fadeOut("slow",
                    function() {
                            $(this).hide();
                        }).fadeIn("slow", function() {
                            $(this).show();

                            let new_qty_ = 0;
                            $("#table_list_data_pilih_pallet_edit > tbody tr").each(
                                function() {
                                    let qty_ = $(this).find(
                                        "td:eq(7) input[type='text']").val();
                                    new_qty_ += parseInt(qty_);
                                });

                            let new_data = global_data.findIndex((value) => value
                                .id == sku_stock_id);
                            global_data[new_data]['qty_aktual'] = new_qty_;
                            initDetailKoreksiDraft(global_data, gudang, gudang_id)
                        });
                    }
                },
            });
        }
    });
});

$(document).on("change", "#check_scan_edit", function(e) {
    if (e.target.checked) {
        $("#start_scan_edit").hide();
        $("#input_manual_edit").show();
        $("#stop_scan_edit").hide();
        $("#preview_edit").hide();
        $("#txtpreviewscan_edit").val("");
        $("#select_kamera_edit").empty();
    } else {
        $("#start_scan_edit").show();
        $("#input_manual_edit").hide();
        $("#close_input_edit").hide();
        $("#preview_input_manual_edit").hide();
        $("#kode_barcode_edit").val("");
        $("#txtpreviewscan_edit").val("");
        $('#myFileInput_edit').val("");
        $('#show-file_edit').empty();
    }
});

$(document).on("click", "#start_scan_edit", function() {
    let id = $(this).attr('data-id');
    let gudang_id = $(this).attr('data-gudang-id');
    let ed = $(this).attr('data-ed');
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();
    $("#start_scan_edit").hide();
    $("#stop_scan_edit").show();
    Swal.fire({
        title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-MEMUATKAMERA">Memuat Kamera</label></span>',
        timer: 1000,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            $("#preview_edit").show();

            //handle succes scan
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                let temp = decodedText;
                if (temp != "") {
                    html5QrCode.pause();
                    scan(decodedText);
                }
            };

            const scan = (decodedText) => {

                //check ada apa kosong di tabel distribusi_penerimaan_detail_temp
                //jika ada update statusnya jadi 1
                $.ajax({
                    url: "<?= base_url('WMS/Pemusnahan/check_kode_pallet_by_no_pallet'); ?>",
                    type: "POST",
                    data: {
                        id: id,
                        kode_pallet: decodedText
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $("#txtpreviewscan_edit").val(data.kode);
                        if (data.type == 200) {
                            Swal.fire("Success!", data.message, "success").then(
                                function(result) {
                                    if (result.value == true) {
                                        html5QrCode.resume();
                                    }
                                });
                            $('#table_list_data_pilih_pallet_edit').fadeOut("slow",
                                function() {
                                    $(this).hide();
                                }).fadeIn("slow", function() {
                                $(this).show();

                                get_data(koreksi_draft_id, id, gudang_id, ed);
                            });
                        } else if (data.type == 201) {
                            Swal.fire("Error!", data.message, "error").then(function(
                                result) {
                                if (result.value == true) {
                                    html5QrCode.resume();
                                }
                            });
                            // message("Error!", data.message, "error");
                        } else {
                            Swal.fire("Info!", data.message, "info").then(function(
                                result) {
                                if (result.value == true) {
                                    html5QrCode.resume();
                                }
                            });
                        }
                    },
                });
            }

            // atur kotak nng kini, set 0.sekian pngin jlok brpa persen
            const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                let minEdgePercentage = 0.9; // 90%
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            }

            const config = {
                fps: 10,
                qrbox: qrboxFunction,
                // rememberLastUsedCamera: true,
                // Only support camera scan type.
                supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
            };
            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    $.each(devices, function(i, v) {
                        $("#select_kamera_edit").append(`
                        <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
                    });

                    $("#select_kamera :input[name='tools']").each(function(i, v) {
                        if (i == 0) {
                            $(this).attr('checked', true);
                        }
                    });

                    let cameraId = devices[0].id;
                    // html5QrCode.start(devices[0]);
                    $('input[name="tools"]').on('click', function() {
                        // alert($(this).val());
                        html5QrCode.stop();
                        html5QrCode.start({
                            deviceId: {
                                exact: $(this).val()
                            }
                        }, config, qrCodeSuccessCallback);
                    });
                    //start scan
                    html5QrCode.start({
                        deviceId: {
                            exact: cameraId
                        }
                    }, config, qrCodeSuccessCallback);

                }
            }).catch(err => {
                message("Error!", "Kamera tidak ditemukan", "error");
                $("#start_scan_edit").show();
                $("#stop_scan_edit").hide();
            });
        }
    });
});

$(document).on("click", "#input_manual_edit", function() {
    let id = $(this).attr('data-id');
    let gudang_id = $(this).attr('data-gudang-id');
    let ed = $(this).attr('data-ed');

    $("#check_kode_edit").attr('data-id', id);
    $("#check_kode_edit").attr('data-gudang-id', gudang_id);
    $("#check_kode_edit").attr('data-ed', ed);

    $("#input_manual_edit").hide();
    $("#close_input_edit").show();
    $("#preview_input_manual_edit").show();
});

function previewFile() {
    const file = document.querySelector('input[id=myFileInput_edit]').files[0];
    const reader = new FileReader();

    $('#show-file_edit').empty();
    reader.addEventListener("load", function() {
        $('#show-file_edit').append(`
                <a href="${reader.result}" data-lightbox="image-1">
                <img src="${reader.result}" style="cursor: pointer" class="img-fluid" width="300" height="350" />
                </a>
            `);
        // preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

$(document).on("click", "#check_kode_edit", function() {
    let id = $(this).attr('data-id');
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();
    let gudang_id = $(this).attr('data-gudang-id');
    let ed = $(this).attr('data-ed');
    let barcode = $("#kode_barcode_edit").val();
    let attachment = $("#myFileInput_edit")

    if (barcode == "") {
        message("Error!", "Kode Pallet tidak boleh kosong", "error");
        return false;
    } else if (attachment.val() == "") {
        message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
        return false;
    } else {
        let new_form = new FormData();
        let files = attachment[0].files[0];
        new_form.append('id', id);
        new_form.append('kpdd_id', koreksi_draft_id);
        new_form.append('kode_pallet', barcode);
        new_form.append('file', files);

        $.ajax({
            url: "<?= base_url('WMS/Pemusnahan/check_kode_pallet_by_no_pallet'); ?>",
            type: "POST",
            data: new_form,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: () => {
                $("#loading_cek_manual_edit").show();
            },
            success: function(data) {
                $("#txtpreviewscan_edit").val(data.kode);
                if (data.type == 200) {
                    message("Success!", data.message, "success");
                    $("#kode_barcode_edit").val("");
                    $('#myFileInput_edit').val("");
                    $('#show-file_edit').empty();
                    $('#table_list_data_pilih_pallet_edit').fadeOut("slow", function() {
                        $(this).hide();
                    }).fadeIn("slow", function() {
                        $(this).show();
                        get_data(koreksi_draft_id, id, gudang_id, ed);
                    });
                } else if (data.type == 201) {
                    message("Error!", data.message, "error");
                } else {
                    message("Info!", data.message, "info");
                }
            },
            complete: () => {
                $("#loading_cek_manual_edit").hide();
            },
        });
    }
})

$(document).on("click", "#stop_scan_edit", function() {
    remove_stop_scan();
});

$(document).on("click", "#close_input_edit", function() {
    remove_close_input();
});

function remove_stop_scan() {
    $("#start_scan_edit").show();
    $("#stop_scan_edit").hide();
    $("#preview_edit").hide();
    $("#txtpreviewscan_edit").val("");
    $("#select_kamera_edit").empty();
    html5QrCode.stop();
}

function remove_close_input() {
    $("#input_manual_edit").show();
    $("#close_input_edit").hide();
    $("#preview_input_manual_edit").hide();
    $("#kode_barcode_edit").val("");
    $("#txtpreviewscan_edit").val("");
    $('#myFileInput_edit').val("");
    $('#show-file_edit').empty();
}

$(document).on("click", ".btn_pilih_pallet_edit", function() {
    let is_undefined = 0;
    let count = $("#table_list_data_pilih_pallet_edit > tbody tr").length;
    $("#table_list_data_pilih_pallet_edit > tbody tr").each(function() {
        let is_checked = $(this).find("td:eq(0) input[type='checkbox']:checked");
        if (is_checked.val() == undefined) {
            is_undefined++;
        }
    });

    if (is_undefined == count) {
        message("Error!", "Belum ada pallet yang terpilih!", "error");
        return false;
    } else {
        $("#list_data_pilih_pallet_edit").modal("hide");
        $("#check_scan_edit").attr('checked', false).trigger('change');
    }
});

$(document).on("click", ".btn_close_list_data_pilih_pallet_edit", function() {
    $("#list_data_pilih_pallet_edit").modal("hide");
    $("#check_scan_edit").attr('checked', false).trigger('change');
});

$(document).on("click", "#simpan_koreksi_edit", function() {
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();
    let principle_id = $("#principle_id_edit").val();
    let tgl = $("#tgl_edit").val();
    let tipe_id = $("#tipe_id_edit").val();
    let keterangan = $("#keterangan_edit").val();
    let gudang_id = $("#gudang_id_edit").val();
    let checker = $("#checker_edit").val();
    let koreksi_id = global_id;

    let arr_sku_stock_id = [];
    let arr_is_Qty_plan = [];
    let arr_detail = [];

    let is_zero = 0;
    let count = $("#table_data_tambah_koreksi_stok_edit > tbody tr").length;
    $("#table_data_tambah_koreksi_stok_edit > tbody tr").each(function() {
        let is_Qty_plan = $(this).find("td:eq(8)").text();
        if (is_Qty_plan == 0) {
            is_zero++;
        }
    });

    $("#table_data_tambah_koreksi_stok_edit > tbody tr").each(function() {
        let sku_stock_id = $(this).find("td:eq(1) input[type='hidden']");
        let is_Qty_Ready = $(this).find("td:eq(7)");
        let is_Qty_plan = $(this).find("td:eq(8)");
        let int_Qty_Ready = parseInt(is_Qty_Ready.text());
        let int_Qty_plan = parseInt(is_Qty_plan.text());

        if (is_Qty_Ready.text() != is_Qty_plan.text()) {
            message("Error!",
                "Qty Available dan Qty Plan ada yang tidak compare, Silahkan cek kembali!", "error");
            $("#error_edit").val("1");
            return false;
        } else {
            $("#error_edit").val("0");
            sku_stock_id.map(function() {
                arr_sku_stock_id.push($(this).val());
            }).get();

            is_Qty_plan.map(function() {
                arr_is_Qty_plan.push($(this).text());
            }).get();
        }
    });

    if (is_zero == count) {
        message("Error!", "Qty Plan Koreksi setidaknya mininal 1 yg terisi!", "error");
        return false;
    } else {
        let error = $("#error_edit").val();
        if (error == 1) {
            return false;
        } else {
            messageBoxBeforeRequest('Data yang sudah dikonfirmasi tidak dapat diganti!!', 'Iya, Simpan',
                'Tidak, Tutup').then((result) => {
                if (result.value == true) {
                    requestAjax("<?= base_url('WMS/Pemusnahan/update_data'); ?>", {
                        koreksi_id: koreksi_id,
                        koreksi_draft_id: koreksi_draft_id,
                        keterangan: keterangan,
                        lastUpdated: $("#lastUpdated").val()
                    }, "POST", "JSON", function(data) {
                        if (data == 2) return messageNotSameLastUpdated()
                        if (data == 1) {
                            message_topright("success", "Data berhasil disimpan");
                            setTimeout(() => {
                                location.href =
                                    "<?= base_url(); ?>WMS/Pemusnahan/PemusnahanMenu";
                            }, 500);
                        } else {
                            message_topright("error", "Data gagal disimpan");
                        }
                        // if (response == 0) return message_topright("error", "Data gagal disimpan");
                    }, "#simpan_koreksi_edit")
                }
            });
            // Swal.fire({
            // 	title: "Apakah anda yakin?",
            // 	text: "Pastikan data yang sudah anda input benar!",
            // 	icon: "warning",
            // 	showCancelButton: true,
            // 	confirmButtonColor: "#3085d6",
            // 	cancelButtonColor: "#d33",
            // 	confirmButtonText: "Ya, Simpan",
            // 	cancelButtonText: "Tidak, Tutup"
            // }).then((result) => {
            // 	if (result.value == true) {
            // 		//ajax save data
            // 		$.ajax({
            // 			url: "<?= base_url('WMS/Pemusnahan/update_data'); ?>",
            // 			type: "POST",
            // 			data: {
            // 				koreksi_id: koreksi_id,
            // 				koreksi_draft_id: koreksi_draft_id,
            // 				keterangan: keterangan
            // 			},
            // 			dataType: "JSON",
            // 			success: function(data) {
            // 				if (data == 1) {
            // 					message_topright("success", "Data berhasil disimpan");
            // 					setTimeout(() => {
            // 						location.reload();
            // 					}, 1000);
            // 				} else {
            // 					message_topright("error", "Data gagal disimpan");
            // 				}
            // 			}
            // 		});
            // 	}
            // });
        }
    }
});

$(document).on("click", "#konfirmasi_koreksi_edit", function() {
    let koreksi_draft_id = $("#koreksi_draft_id_edit").val();
    let principle_id = $("#principle_id_edit").val();
    let tgl = $("#tgl_edit").val();
    let tipe_id = $("#tipe_id_edit").val();
    let keterangan = $("#keterangan_edit").val();
    let gudang_id = $("#gudang_id_edit").val();
    let checker = $("#checker_edit").val();
    let koreksi_id = global_id;

    let arr_sku_stock_id = [];
    let arr_is_Qty_plan = [];
    let arr_detail = [];
    $("#table_data_tambah_koreksi_stok_edit > tbody tr").each(function() {
        let sku_stock_id = $(this).find("td:eq(1) input[type='hidden']");
        let is_Qty_Ready = $(this).find("td:eq(7)");
        let is_Qty_plan = $(this).find("td:eq(8)");
        let int_Qty_Ready = parseInt(is_Qty_Ready.text());
        let int_Qty_plan = parseInt(is_Qty_plan.text());

        if (is_Qty_Ready.text() != is_Qty_plan.text()) {
            message("Error!",
                "Qty Available dan Qty Plan ada yang tidak compare, Silahkan cek kembali!", "error");
            $("#error_edit").val("1");
            return false;
        } else {
            $("#error_edit").val("0");
            sku_stock_id.map(function() {
                arr_sku_stock_id.push($(this).val());
            }).get();

            is_Qty_plan.map(function() {
                arr_is_Qty_plan.push($(this).text());
            }).get();
        }
    });

    let error = $("#error_edit").val();
    if (error == 1) {
        return false;
    } else {
        if (arr_sku_stock_id != null) {
            for (let index = 0; index < arr_sku_stock_id.length; index++) {
                arr_detail.push({
                    'sku_stock_id': arr_sku_stock_id[index],
                    'qty_aktual': arr_is_Qty_plan[index]
                });
            }
        }
        messageBoxBeforeRequest('Data yang sudah dikonfirmasi tidak dapat diganti!!', 'Iya, Simpan',
            'Tidak, Tutup').then((result) => {
            if (result.value == true) {
                requestAjax("<?= base_url('WMS/Pemusnahan/confirm_data'); ?>", {
                    koreksi_id: koreksi_id,
                    koreksi_draft_id: koreksi_draft_id,
                    keterangan: keterangan,
                    tipe_id: tipe_id,
                    data_detail: arr_detail,
                    lastUpdated: $("#lastUpdated").val()
                }, "POST", "JSON", function(data) {
                    if (data == 2) return messageNotSameLastUpdated()
                    if (data == 1) {
                        message_topright("success", "Data berhasil dikonfirmasi");
                        setTimeout(() => {
                            location.href =
                                "<?= base_url(); ?>WMS/Pemusnahan/PemusnahanMenu";
                        }, 500);
                    } else {
                        message_topright("error", "Data gagal disimpan");
                    }
                    // if (response == 0) return message_topright("error", "Data gagal disimpan");

                }, "#konfirmasi_koreksi_edit")
            }
        });

        // Swal.fire({
        // 	title: "Apakah anda yakin?",
        // 	text: "Pastikan data yang sudah anda input benar!",
        // 	icon: "warning",
        // 	showCancelButton: true,
        // 	confirmButtonColor: "#3085d6",
        // 	cancelButtonColor: "#d33",
        // 	confirmButtonText: "Ya, Konfirmasi",
        // 	cancelButtonText: "Tidak, Tutup"
        // }).then((result) => {
        // 	if (result.value == true) {
        // 		// console.log(sku_stock_id);
        // 		$.ajax({
        // 			url: "<?= base_url('WMS/Pemusnahan/confirm_data') ?>",
        // 			type: "POST",
        // 			data: {
        // 				koreksi_id: koreksi_id,
        // 				koreksi_draft_id: koreksi_draft_id,
        // 				keterangan: keterangan,
        // 				tipe_id: tipe_id,
        // 				data_detail: arr_detail
        // 			},
        // 			dataType: "JSON",
        // 			success: function(data) {
        // 				if (data == 1) {
        // 					message_topright("success", "Data berhasil dikonfirmasi");
        // 					setTimeout(() => {
        // 						location.href = "<?= base_url('WMS/Pemusnahan/PemusnahanMenu') ?>";
        // 					}, 500);
        // 				} else {
        // 					message_topright("error", "Data gagal dikonfirmasi");
        // 				}
        // 			},
        // 		});
        // 	}
        // });
    }
});

$(document).on("click", "#kembali_koreksi_edit", function() {
    setTimeout(() => {
        Swal.fire({
            title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
            showConfirmButton: false,
            timer: 500
        });
        location.href = "<?= base_url('WMS/Pemusnahan/PemusnahanMenu') ?>";
    }, 500);
})

document.getElementById('kode_barcode_edit').addEventListener('keyup', function() {
    if (this.value != "") {
        fetch('<?= base_url('WMS/Pemusnahan/getKodeAutoComplete?params='); ?>' + this.value)
            .then(response => response.json())
            .then((results) => {
                if (!results[0]) {
                    document.getElementById('table-fixed').style.display = 'none';
                } else {
                    let data = "";
                    // console.log(results);
                    results.forEach(function(e, i) {
                        data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
														<td class="col-xs-1">${i + 1}.</td>
														<td class="col-xs-11">${e.kode}</td>
												</tr>
												`;
                    })

                    document.getElementById('konten-table').innerHTML = data;
                    // console.log(data);
                    document.getElementById('table-fixed').style.display = 'block';
                }
            });
    } else {
        document.getElementById('table-fixed').style.display = 'none';
    }
});

function getNoSuratJalanEks(data) {
    $("#kode_barcode_edit").val(data);
    document.getElementById('table-fixed').style.display = 'none'
    $("#check_kode_edit").click()
}
</script>