<script type="text/javascript">
var ChannelCode = '';
var arr_penerimaan_tipe = [];
var arr_list_penerimaan_tipe = [];
var cek_btb = [];
var do_batch_id = '';

loadingBeforeReadyPage()
<?php if ($act == "ProsesBTB" || $act == "ProsesBTBKiriman" || $act == "ProsesBTBRetur") { ?>
const html5QrCode = new Html5Qrcode("preview");
const html5QrCode2 = new Html5Qrcode("preview_by_one");
let timerInterval
<?php } ?>

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

$(document).ready(
    function() {
        var delivery_order_batch_id = $("#penerimaanpenjualan-delivery_order_batch_id").val();
        var delivery_order_id = $("#penerimaanpenjualan-delivery_order_id").val();

        if ($('#filter_fdjr_date').length > 0) {
            $('#filter_fdjr_date').daterangepicker({
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
        //$('.select2').select2();

        $('input[type="checkbox"]').change(function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"]').change(function() {
            var kondisi = $(this).next('label').text()
                .trim(); // Mendapatkan teks dari label terkait checkbox

            $('select[name="kondisiBarang"]').each(function() {
                $(this).find('option').each(function() {
                    if ($(this).text().trim() === kondisi) {
                        $(this).prop('selected', true);
                    }
                });
            });
        });

        <?php if ($act == "ProsesBTB" || $act == "ProsesBTBKiriman" || $act == "ProsesBTBRetur") { ?>
        // $.ajax({
        // 	type: 'POST',
        // 	url: "<?= base_url('WMS/PenerimaanRetur/check_sisa_btb_by_do') ?>",
        // 	data: "delivery_order_id=" + delivery_order_id,
        // 	success: function(response) {
        // 		$("#loadingview").hide();

        // 		if (response == 0) {
        // 			Swal.fire({
        // 				position: 'center',
        // 				icon: 'error',
        // 				title: '<span name="CAPTION-ALERT-BTBSUDAHDIBUAT">BTB Sudah dibuat!',
        // 				timer: 500
        // 			});

        // 			setTimeout(() => {
        // 				window.location.href = "<?php echo base_url() ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" + delivery_order_batch_id;
        // 			}, 1000);
        // 		}
        // 	}
        // });

        if ($("#penerimaanpenjualan-depo_detail_id").val() != "") {
            GetRakLajurDetail();
        }
        <?php } ?>

        <?php if ($act == "ProsesBTBMenu") { ?>
        <?php foreach ($FDJRDetail as $i => $detail) : ?>
        cek_btb.push({
            'delivery_order_id': "<?= $detail['delivery_order_id'] ?>",
            'delivery_order_kode': "<?= $detail['delivery_order_kode'] ?>",
            'sudah_btb': "<?= $detail['sudah_btb'] ?>"
        });
        <?php endforeach ?>
        <?php } ?>

        var urlParams = new URLSearchParams(window.location.search);
        var delivery_order_batch_id = urlParams.get('delivery_order_batch_id');
        do_batch_id = delivery_order_batch_id;
        var newUrl = '<?= base_url("WMS/PenerimaanRetur/ProsesBTBKiriman/") ?>' + delivery_order_batch_id;
        var newUrl2 = '<?= base_url("WMS/PenerimaanRetur/ProsesBTBRetur/") ?>' + delivery_order_batch_id;
        var newUrl3 = '<?= base_url("WMS/PenerimaanRetur/ViewBTBKiriman/") ?>' + delivery_order_batch_id;

        $("#btn_btb_kiriman").attr('href', newUrl);
        $("#btn_btb_retur").attr('href', newUrl2);
        $("#btn_btb_view").attr('href', newUrl3);
    }
);

$("#showAll").on('change', function() {
    var is_btb = 0;
    var urlParams = new URLSearchParams(window.location.search);
    var delivery_order_batch_id = urlParams.get('delivery_order_batch_id');

    if ($(this).is(':checked')) {
        is_btb = 1;
    } else {
        is_btb = 0;
    }

    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/PenerimaanRetur/filterIsBTB') ?>",
        data: {
            is_btb: is_btb,
            id: delivery_order_batch_id
        },
        dataType: "JSON",
        success: function(response) {
            $("#tabledomenu > tbody").empty();
            // console.log(response.length);
            $.each(response, function(i, v) {
                $("#tabledomenu > tbody").append(`
					<tr id="row-${i}" class="row-item">
						<td class="text-center">${i + 1}</td>
						<td class="text-center">${v.delivery_order_tgl_buat_do}</td>
						<td class="text-center">${v.delivery_order_kode}</td>
						<td class="text-center">${v.tipe_delivery_order_alias}</td>
						<td class="text-center">${v.delivery_order_kirim_nama}</td>
						<td class="text-center">${v.delivery_order_kirim_alamat}</td>
						<td class="text-center">${v.delivery_order_kirim_telp}</td>
						<td class="text-center">${v.delivery_order_tipe_pembayaran}</td>
						<td class="text-center">${v.delivery_order_no_urut_rute}</td>
						<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim"
								id="chk_do_terkirim_${i}" value="${v.delivery_order_id}"
								${v.delivery_order_status == 'delivered' ? 'checked' : ''}
								disabled>
						</td>
						<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian"
								id="chk_do_terkirim_sebagian_${i}" value="${v.delivery_order_id}"
								${v.delivery_order_status == 'partially delivered' ? 'checked' : ''}
								disabled>
						</td>
						<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal_${i}"
								id="chk_do_gagal_${i}" value="${v.delivery_order_id}"
								${v.delivery_order_status == 'not delivered' ? 'checked' : ''}
								disabled>
						</td>
						<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang_${i}"
								id="chk_do_kirim_ulang_${i}" value="${v.delivery_order_id}"
								${v.delivery_order_status == 'rescheduled' ? 'checked' : ''}
								disabled>
						</td>
						<td class="text-center">${v.reason_keterangan == null ? '' : v.reason_keterangan}</td>
						<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_${i}"
								value="${v.delivery_order_id}"
								${v.ada_titipan == 1 ? 'checked' : ''}
								disabled></td>
						<td>
							${v.sudah_btb == "SUDAH BTB" ?
								`<a href="<?= base_url() ?>WMS/PenerimaanRetur/BTBDetail/?delivery_order_batch_id=${v.delivery_order_batch_id}&delivery_order_id=${v.delivery_order_id}"
									type="button" class="btn btn-primary" id="btn_view_do_retur_${i}">
									<i class="fas fa-eye"></i>
								</a>
								<a href="<?= base_url() ?>WMS/PenerimaanRetur/print_btb/?delivery_order_batch_id=${v.delivery_order_batch_id}&delivery_order_id=${v.delivery_order_id}&nama_toko=${v.delivery_order_kirim_nama}"
									type="button" target="_blank" class="btn btn-success" id="">
									<i class="fas fa-print"></i>
								</a>` : ''}
						</td>
					</tr>
				`);
            });
        }
    })
    // ${v.sudah_btb == "BELUM BTB" ?
    // 	`<a href="<?= base_url() ?>WMS/PenerimaanRetur/ProsesBTB/?delivery_order_batch_id=${v.delivery_order_batch_id}&delivery_order_id=${v.delivery_order_id}"
    // 		type="button" class="btn btn-primary" id="btn_proses_do_retur_${i}"><span
    // 			name="CAPTION-PROSESBTB">Proses BTB</span></a>` :
    // 	(v.sudah_btb == "SUDAH BTB" ?
    // 		`<a href="<?= base_url() ?>WMS/PenerimaanRetur/BTBDetail/?delivery_order_batch_id=${v.delivery_order_batch_id}&delivery_order_id=${v.delivery_order_id}"
    // 			type="button" class="btn btn-primary" id="btn_view_do_retur_${i}"><span
    // 				name="CAPTION-VIEWBTB">View BTB</span></a>
    // 		<a href="<?= base_url() ?>WMS/PenerimaanRetur/print_btb/?delivery_order_batch_id=${v.delivery_order_batch_id}&delivery_order_id=${v.delivery_order_id}&nama_toko=${v.delivery_order_kirim_nama}"
    // 			type="button" target="_blank" class="btn btn-success" id=""><span
    // 				name="CAPTION-CETAK">Cetak</span></a>` : '')
    // }

})

$("#btnviewfdjr").on("click", function() {
    var no = 1;

    $("#loadingview").show();

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/search_fdjr_by_filter') ?>",
        data: {
            Tgl_FDJR: $("#filter_fdjr_date").val(),
            No_FDJR: $("#filter_fdjr_no").val(),
            karyawan_id: $("#filter_fdjr_driver").val(),
            Status_FDJR: $("#filter_fdjr_status").val()
        },
        dataType: "JSON",
        success: function(response) {
            $("#tablefdjrmenu > tbody").empty();

            if (response != 0) {

                if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
                    $('#tablefdjrmenu').DataTable().clear();
                    $('#tablefdjrmenu').DataTable().destroy();
                }

                $.each(response, function(i, v) {
                    if (v.delivery_order_batch_status == 'Closing Delivery Confirm' || v
                        .delivery_order_batch_status == 'completed') {
                        $("#tablefdjrmenu > tbody").append(`
								<tr>
									<td class="text-center">${no}</td>
									<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
									<td class="text-center">${v.karyawan_nama}</td>
									<td class="text-center">${v.delivery_order_batch_status}</td>
									<td class="text-center">${v.tipe_delivery_order_alias}</td>
									<td class="text-center"><a href="<?= base_url(); ?>PenerimaanRetur/detail/?id=${v.delivery_order_batch_id}" class="btn btn-link" target="_blank">${v.delivery_order_batch_kode}</a></td>
									<td class="text-center">${v.settlement_status}</td>
									<td class="text-center"><a href="<?= base_url() ?>PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=${v.delivery_order_batch_id}" class="btn btn-link"><span name="CAPTION-PROSESBTB">View BTB</span></a></td>
								</tr>
							`);
                    } else {
                        $("#tablefdjrmenu > tbody").append(`
								<tr>
									<td class="text-center">${no}</td>
									<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
									<td class="text-center">${v.karyawan_nama}</td>
									<td class="text-center">${v.delivery_order_batch_status}</td>
									<td class="text-center">${v.tipe_delivery_order_alias}</td>
									<td class="text-center"><a href="<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/PenerimaanRetur/detail/?id=${v.delivery_order_batch_id}" class="btn btn-link" target="_blank">${v.delivery_order_batch_kode}</a></td>
									<td class="text-center">${v.settlement_status}</td>
									<td class="text-center"><a href="<?= base_url() ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=${v.delivery_order_batch_id}" class="btn btn-link"><span name="CAPTION-PROSESBTB">Proses BTB</span></a></td>
								</tr>
							`);

                    }
                    no++;
                });

                $('#tablefdjrmenu').DataTable({
                    'ordering': false
                });

            } else {
                $("#tablefdjrmenu > tbody").append(`
						<tr>
							<td colspan="8" class="text-danger text-center">Data Kosong</td>
						</tr>
					`);
            }

            $("#loadingview").hide();
        }
    });
});

$(document).on("change", "#check_scan", function(e) {
    if (e.target.checked) {
        $("#start_scan").hide();
        $("#input_manual").show();
        $("#stop_scan").hide();
        $("#preview").hide();
        $("#txtpreviewscan").val("");
        $("#select_kamera").empty();
    } else {
        $("#start_scan").show();
        $("#input_manual").hide();
        $("#close_input").hide();
        $("#preview_input_manual").hide();
        $("#kode_barcode_notone").val("");
        $("#txtpreviewscan").val("");
        $('#myFileInput').val("");
        $('#show-file').empty();
    }
});

$(document).on("click", "#input_manual", function() {
    $("#input_manual").hide();
    $("#close_input").show();
    $("#preview_input_manual").show();
    $("#start_scan").attr("disabled", true);
});

$(document).on("click", "#close_input", function() {
    remove_close_input();
});

function remove_close_input() {
    $("#input_manual").show();
    $("#close_input").hide();
    $("#preview_input_manual").hide();
    $("#kode_barcode_notone").val("");
    $("#txtpreviewscan").val("");
    $('#myFileInput').val("");
    $('#show-file').empty();
    $("#start_scan").attr("disabled", false);
}

$(document).on("click", "#start_scan", function() {

    Swal.fire({
        title: 'Memuat Kamera',
        html: '<span><i class="fa fa-spinner fa-spin"></i></span>',
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
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
            console.log('I was closed by the timer')
        }
    })

    $("#start_scan").hide();
    $("#stop_scan").show();
    $("#preview").show();
    $("#input_manual").attr("disabled", true);

    //handle succes scan
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        let temp = decodedText;
        if (temp != "") {
            html5QrCode.pause();
            scan(decodedText);
        }

        // console.log(decodedText)
    };

    const scan = (decodedText) => {

        //check ada apa kosong di tabel distribusi_penerimaan_detail_temp
        //jika ada update statusnya jadi 1

        if ($("#penerimaanpenjualan-depo_detail_id").val() != "") {
            $.ajax({
                url: "<?= base_url('WMS/PenerimaanRetur/check_kode_pallet'); ?>",
                type: "POST",
                data: {
                    id: idd,
                    kode_pallet: decodedText,
                    depo_detail_id: $("#penerimaanpenjualan-depo_detail_id").val()
                },
                dataType: "JSON",
                success: function(data) {
                    $("#txtpreviewscan").val(data.kode);
                    if (data.type == 200) {
                        Swal.fire("Success!", data.message, "success").then(function(result) {
                            if (result.value == true) {
                                html5QrCode.resume();

                            }
                        });
                        $('#table_mutasi_pallet').fadeOut("slow", function() {
                            $(this).hide();

                        }).fadeIn("slow", function() {
                            $(this).show();
                            initDataPallet();
                        });
                    } else if (data.type == 201) {
                        Swal.fire("Error!", data.message, "error").then(function(result) {
                            if (result.value == true) {
                                html5QrCode.resume();

                            }
                        });
                        // message("Error!", data.message, "error");
                    } else {
                        Swal.fire("Info!", data.message, "info").then(function(result) {
                            if (result.value == true) {
                                html5QrCode.resume();

                            }
                        });
                    }
                },
            });
        } else {
            message("Error!",
                "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
        }


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
                $("#select_kamera").append(`
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
        $("#start_scan").show();
        $("#stop_scan").hide();
        $("#input_manual").attr("disabled", false);
    });
});

$(document).on("click", "#stop_scan", function() {
    html5QrCode2.stop();
    $("#modal_scan").modal('hide');
});

$(document).on("click", ".start_scan_by_one", function() {
    let idd = $("#penerimaanpenjualan-delivery_order_batch_id").val()
    if (idd != "") {
        const index = $(this).attr('data-idx');
        $("#modal_scan").modal('show');

        $("#pallet-rak_lajur_detail_id_" + index).html('');

        Swal.fire({
            title: 'Memuat Kamera',
            html: '<span ><i class="fa fa-spinner fa-spin"></i></span>',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
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
                console.log('I was closed by the timer')
            }
        })

        //handle succes scan
        const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
            let temp = decodedText;
            if (temp != "") {
                html5QrCode2.pause();
                scan2(decodedText);
            }
        };

        const scan2 = (decodedText) => {
            // alert(pallet_id + " - " + gudang_tujuan)
            $.ajax({
                url: "<?= base_url('WMS/PenerimaanRetur/check_rak_lajur_detail'); ?>",
                type: "POST",
                data: {
                    depo_detail_id: $("#penerimaanpenjualan-depo_detail_id").val(),
                    pallet_id: $("#item-" + index + "-penerimaanpenjualandetail2-pallet_id").val(),
                    kode: decodedText
                },
                dataType: "JSON",
                success: function(data) {
                    $("#txtpreviewscan2").val(data.kode);
                    $("#pallet-rak_lajur_detail_id_" + index).append(data.kode);

                    if (data.type == 200) {
                        Swal.fire("Success!", data.message, "success").then(function(result) {
                            if (result.value == true) {
                                html5QrCode2.stop();
                                $("#modal_scan").modal('hide');

                            }
                        });
                        $('#table_mutasi_pallet').fadeOut("slow", function() {
                            $(this).hide();

                        }).fadeIn("slow", function() {
                            $(this).show();
                            reload_pallet();
                        });
                    } else if (data.type == 201) {
                        Swal.fire("Error!", data.message, "error").then(function(result) {
                            if (result.value == true) {
                                html5QrCode2.resume();

                            }
                        });
                    } else {
                        Swal.fire("Info!", data.message, "info").then(function(result) {
                            if (result.value == true) {
                                html5QrCode2.resume();

                            }
                        });
                        $('#table_mutasi_pallet').fadeOut("slow", function() {
                            $(this).hide();

                        }).fadeIn("slow", function() {
                            $(this).show();

                        });
                    }
                },
            });
        }

        // atur kotak nng kini, set 0.sekian pngin jlok brpa persen
        const qrboxFunction2 = function(viewfinderWidth, viewfinderHeight) {
            let minEdgePercentage = 0.9; // 90%
            let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
            let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
            return {
                width: qrboxSize,
                height: qrboxSize
            };
        }

        const config2 = {
            fps: 10,
            qrbox: qrboxFunction2,
        };
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                $("#select_kamera_by_one").empty();
                $.each(devices, function(i, v) {
                    $("#select_kamera_by_one").append(`
                        <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
                });

                $("#select_kamera_by_one :input[name='tools2']").each(function(i, v) {
                    if (i == 0) {
                        $(this).attr('checked', true);
                    }
                });

                let cameraId2 = devices[0].id;
                // html5QrCode.start(devices[0]);
                $('input[name="tools2"]').on('click', function() {
                    // alert($(this).val());
                    html5QrCode2.stop();
                    html5QrCode2.start({
                        deviceId: {
                            exact: $(this).val()
                        }
                    }, config2, qrCodeSuccessCallback2);
                });
                //start scan
                html5QrCode2.start({
                    deviceId: {
                        exact: cameraId2
                    }
                }, config2, qrCodeSuccessCallback2);

            }
        }).catch(err => {
            message("Error!", "Kamera tidak ditemukan", "error");
            $("#modal_scan").modal('hide');
        });

    } else {
        message("Error!", "Pilih No Mutasi Draft", "error");
    }

});

$(document).on("click", ".stop_scan_by_one", function() {
    html5QrCode2.stop();
    $("#modal_scan").modal('hide');
});

$(document).on("click", "#check_kode", () => {
    let barcode = $("#kode_barcode_notone").val();

    if (barcode == "") {
        message("Error!", "Kode Pallet tidak boleh kosong", "error");
        return false;
    } else {

        if ($("#penerimaanpenjualan-depo_detail_id").val() != "") {
            $.ajax({
                url: "<?= base_url('WMS/PenerimaanRetur/check_kode_pallet'); ?>",
                type: "POST",
                data: {
                    kode_pallet: barcode,
                    depo_detail_id: $("#penerimaanpenjualan-depo_detail_id").val()
                },
                dataType: "JSON",
                beforeSend: () => {
                    $("#loading_cek_manual").show();
                },
                success: function(data) {
                    $("#txtpreviewscan").val(data.kode);
                    if (data.type == 200) {
                        message("Success!", data.message, "success");
                        $("#kode_barcode_notone").val("");
                        // arr_pallet_id.push(data.pallet_id);
                        // console.log(arr_pallet_id);

                        $.ajax({
                            async: false,
                            type: 'POST',
                            url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletTemp2') ?>",
                            data: {
                                delivery_order_batch_id: $(
                                        "#penerimaanpenjualan-delivery_order_batch_id")
                                    .val(),
                                pallet_id: data.pallet_id,
                                depo_detail_id: $("#penerimaanpenjualan-depo_detail_id")
                                    .val()
                            },
                            dataType: "JSON",
                            success: function(data) {

                            }
                        });

                        $('#tablepallet').fadeOut("slow", function() {
                            $(this).hide();

                        }).fadeIn("slow", function() {
                            $(this).show();
                            initDataPallet();
                        });

                    } else if (data.type == 201) {
                        message("Error!", data.message, "error");

                    } else {
                        message("Info!", data.message, "info");

                    }
                },
                complete: () => {
                    $("#loading_cek_manual").hide();
                },
            });
        } else {
            message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>",
                "error");
        }
    }
});

$(document).on("click", ".input_rak", function() {
    $("#nama_rak").attr("data-idx", $(this).attr('data-idx'));
    $("#manual_input_rak").modal('show');
});

$(document).on("change", "#nama_rak", function() {
    let rak_val = $(this);
    const idx = $(this).attr('data-idx');
    var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

    $("#pallet-rak_lajur_detail_id_" + idx).html('');
    $.ajax({
        async: false,
        url: "<?= base_url('WMS/PenerimaanRetur/check_rak_lajur_detail'); ?>",
        type: "POST",
        data: {
            depo_detail_id: depo_detail_id,
            pallet_id: $("#item-" + idx + "-penerimaanpenjualandetail2-pallet_id").val(),
            kode: rak_val.val()
        },
        dataType: "JSON",
        success: function(data) {
            $("#txtpreviewscan2").val(data.kode);
            $("#pallet-rak_lajur_detail_id_" + idx).append(data.kode);

            if (data.type == 200) {
                Swal.fire("Success!", data.message, "success").then(function(result) {
                    if (result.value == true) {
                        $("#manual_input_rak").modal('hide');
                        rak_val.val("");
                    }
                });
                $('#tablepallet').fadeOut("slow", function() {
                    $(this).hide();
                }).fadeIn("slow", function() {
                    $(this).show();
                    initDataPallet();
                });
            } else if (data.type == 201) {
                message("Error!", data.message, "error");
            } else {
                message("Info!", data.message, "info");
            }
        },
    });
});

$(document).on("click", ".tutup_input_manual", function() {
    $("#manual_input_rak").modal('hide');
    $("#nama_rak").val("");
});

function GetCheckerByPrinciple(principle) {

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/GetCheckerByPrinciple') ?>",
        data: {
            perusahaan: $("#penerimaanpenjualan-client_wms_id").val(),
            principle: principle
        },
        dataType: "JSON",
        success: function(response) {

            $("#penerimaanpenjualan-karyawan_id").html('');

            $("#penerimaanpenjualan-karyawan_id").append(
                '<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');

            $.each(response, function(i, v) {
                $("#penerimaanpenjualan-karyawan_id").append(
                    `<option value="${v.karyawan_id}">${v.karyawan_nama}</option>`);
            });
        }
    });
}

$("#btnpenerimaanperpallet").click(
    function() {
        var delivery_order_batch_id = $("#penerimaanpenjualan-delivery_order_batch_id").val();
        var rak_lajur_detail_id = $("#penerimaanpenjualan-rak_lajur_detail_id").val();
        var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

        $("#loadingview").show();

        if (depo_detail_id != "") {

            $.ajax({
                async: false,
                type: 'POST',
                url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletTemp') ?>",
                data: {
                    delivery_order_batch_id: delivery_order_batch_id,
                    rak_lajur_detail_id: rak_lajur_detail_id,
                    depo_detail_id: depo_detail_id
                },
                dataType: "JSON",
                success: function(data) {
                    $("#loadingview").hide();
                    initDataPallet();
                }
            });

        } else {
            message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>",
                "error");
        }
    }
);

function initDataPallet() {

    // var result = arr_pallet_id.reduce((unique, o) => {
    // 	if (!unique.some(obj => obj === o)) {
    // 		unique.push(o);
    // 	}
    // 	return unique;
    // }, []);

    // arr_pallet_id = result;

    // console.log(arr_pallet_id);

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/get_pallet_by_arr_id') ?>",
        data: {
            delivery_order_batch_id: $("#penerimaanpenjualan-delivery_order_batch_id").val()
            // pallet_id: arr_pallet_id
        },
        dataType: "JSON",
        success: function(response) {
            $('#tablepallet > tbody').empty();
            $.each(response, function(i, v) {
                $("#tablepallet > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-penerimaanpenjualandetail2-pallet_id" value="${v.pallet_id}"/>
								</td>
								<td class="text-center">
									<div class="head-switch">
									<div class="switch-holder">
											<div class="switch-toggle">
												<input type="checkbox" id="check_scan_${i}" class="check_scan">
												<label for="check_scan_${i}"></label>
											</div>
											<div class="switch-label">
												<button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-idx="${i}"> <i class="fas fa-qrcode"></i> Scan</button>
												<button type="button" class="btn btn-warning input_rak" name="input_rak" data-idx="${i}" style="display:none"> <i class="fas fa-keyboard"></i> Input</button>
											</div>
										</div>
									</div
								</td>
								<td class="text-center"><span id="pallet-rak_lajur_detail_id_${i}">${v.rak_lajur_detail_nama}</span></td>
								<td class="text-center">
									<input type="hidden" id="pallet-pallet_kode_${i}" class="form-control" name="pallet[pallet_kode_${i}]" value="${v.pallet_kode}" />
									${v.pallet_kode} - ${v.status_pallet == '0' ? 'Pallet Baru' : 'Pallet Lama'}
								</td>
								<td class="text-center">${v.pallet_jenis_nama}</td>
								<td class="text-center">
									<button type="button" title="tambah sku" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPallet('${v.pallet_id}')"><i class="fa fa-plus text-primary"></i></button>
									<button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet('${v.pallet_id}')"><i class="fa fa-trash text-danger"></i></button></td>
							</tr>
					`);
            });

            $("#tablepallet > tbody tr").each(function(i, v) {
                let check = $(this).find("td:eq(1) input[type='checkbox']");
                // let check = $(this).find("td:eq(7) input[id='check_scan_'" + i + "]");
                let scan = $(this).find("td:eq(1) button[name='start_scan_by_one']");
                let input = $(this).find("td:eq(1) button[name='input_rak']");
                check.change(function(e) {
                    if (e.target.checked) {
                        input.show();
                        scan.hide();
                    } else {
                        input.hide();
                        scan.show();
                    }
                });
            });

            $('.select2').select2();
        }
    });
}

function sku_fdjr_by_pallet() {
    var delivery_order_id = $('#penerimaanpenjualan-delivery_order_id').val();
    var fdjr_type = $('#penerimaanpenjualan-penerimaan_tipe').val();
    var pallet_id = $('#penerimaanpenjualandetail-pallet_id').val();
    var depo_detail_id = $('#penerimaanpenjualan-depo_detail_id').val();
    var client_wms_id = $('#penerimaanpenjualan-client_wms_id').val();
    var tipe_do = $('#penerimaanpenjualan-tipe_delivery_order').val();
    var act = '<?= $act ?>';

    // Membagi string berdasarkan koma
    var wordsArray = fdjr_type.split(',');
    var resultTipeDO = tipe_do.split(',');

    // Mengambil kata pertama
    var do_status = wordsArray[0];
    var tipe_do = resultTipeDO[0];

    // var delivery_order_ids = do_id.split(',');
    // var fdjr_types = fdjr_type.split(',');
    // console.log(delivery_order_ids.length);

    // for (var i = 0; i < delivery_order_ids.length; i++) {
    //     var delivery_order_id = delivery_order_ids[i];
    //     var do_status = fdjr_types[i];
    // console.log('do_id :' + delivery_order_id + ' [' + do_status + ']');

    var ajaxUrl;
    var dataToSend;

    // if (do_status === 'terkirim sebagian') {
    // 	ajaxUrl = "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempTerkirimSebagian') ?>";
    // 	dataToSend = {
    // 		pallet_id: pallet_id,
    // 		delivery_order_id: delivery_order_id,
    // 		client_wms_id: client_wms_id,
    // 		depo_detail_id: depo_detail_id,
    // 		act: act
    // 	};
    // } else 
    if (do_status === 'tidak terkirim' || do_status === 'terkirim sebagian') {
        ajaxUrl = "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempGagal') ?>";
        dataToSend = {
            pallet_id: pallet_id,
            delivery_order_id: delivery_order_id,
            client_wms_id: client_wms_id,
            depo_detail_id: depo_detail_id,
            act: act
        };
    } else if (do_status === 'rescheduled') {
        ajaxUrl = "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempRescheduled') ?>";
        dataToSend = {
            pallet_id: pallet_id,
            delivery_order_id: delivery_order_id,
            client_wms_id: client_wms_id,
            depo_detail_id: depo_detail_id
        };
    } else if (do_status == "retur" || (do_status == "terkirim" && tipe_do == "Retur")) {
        ajaxUrl = "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempDoRetur') ?>";
        dataToSend = {
            pallet_id: pallet_id,
            delivery_order_id: delivery_order_id,
            client_wms_id: client_wms_id,
            depo_detail_id: depo_detail_id,
            act: act
        };
    }

    // Show loading view
    $("#loadingview").show();

    // Send AJAX request
    $.ajax({
        async: false,
        type: 'POST',
        url: ajaxUrl,
        data: dataToSend,
        success: function(response) {
            if (response) {
                if (response == 1) {
                    console.log('success');
                    // initDataTablePalletDetail(pallet_id);
                } else {
                    var msg;
                    if (response == 0) {
                        msg = 'Tidak Ada Barang Terima Barang';
                    } else {
                        msg = response;
                    }
                    var msgtype = 'error';

                    Swal.fire({
                        icon: msgtype,
                        title: 'Error',
                        text: msg
                    });
                }
            }
            $("#loadingview").hide(); // Hide loading view
        }
    });
}

// initDataTablePalletDetail(pallet_id);
// }


// $("#btn-tambah-sku-fdjr").click(
// 	function() {
// 		var delivery_order_id = $('#penerimaanpenjualan-delivery_order_id').val();
// 		var pallet_id = $('#penerimaanpenjualandetail-pallet_id').val();
// 		var depo_detail_id = $('#penerimaanpenjualan-depo_detail_id').val();
// 		var client_wms_id = $('#penerimaanpenjualan-client_wms_id').val();
// 		var fdjr_type = $('#penerimaanpenjualan-penerimaan_tipe').val();
// 		var tipe_do = $('#penerimaanpenjualan-tipe_delivery_order').val();
// 		var act = '<?= $act ?>';

// 		if (fdjr_type == "retur" || (fdjr_type == "terkirim" && tipe_do == "Retur")) {

// 			$("#loadingview").show();
// 			$.ajax({
// 				async: false,
// 				type: 'POST',
// 				url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempDoRetur') ?>",
// 				data: {
// 					pallet_id: pallet_id,
// 					delivery_order_id: delivery_order_id,
// 					client_wms_id: client_wms_id,
// 					depo_detail_id: depo_detail_id
// 				},
// 				success: function(response) {
// 					if (response) {
// 						if (response == 1) {
// 							$("#loadingview").hide();
// 							console.log('success');
// 							initDataTablePalletDetail(pallet_id);
// 						} else {
// 							if (response == 0) {
// 								var msg = 'Tidak Ada Barang Terima Barang DO Retur';
// 							} else {
// 								var msg = response;
// 							}
// 							var msgtype = 'error';

// 							// if (!window.__cfRLUnblockHandlers) return false;
// 							Swal.fire({
// 								icon: msgtype,
// 								title: 'Error',
// 								text: msg
// 							});

// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);

// 							// console.log(msg);
// 						}
// 					}
// 				}
// 			});

// 		} else if (fdjr_type == "terkirim sebagian") {
// 			$("#loadingview").show();
// 			$.ajax({
// 				async: false,
// 				type: 'POST',
// 				url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempTerkirimSebagian') ?>",
// 				data: {
// 					pallet_id: pallet_id,
// 					delivery_order_id: delivery_order_id,
// 					client_wms_id: client_wms_id,
// 					depo_detail_id: depo_detail_id
// 				},
// 				success: function(response) {
// 					if (response) {
// 						if (response == 1) {
// 							console.log('success');
// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);
// 						} else {
// 							if (response == 0) {
// 								var msg = 'Tidak Ada Barang Terima Barang Terkirim Sebagian';
// 							} else {
// 								var msg = response;
// 							}
// 							var msgtype = 'error';

// 							// if (!window.__cfRLUnblockHandlers) return false;
// 							Swal.fire({
// 								icon: msgtype,
// 								title: 'Error',
// 								text: msg
// 							});

// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);

// 							// console.log(msg);
// 						}
// 					}
// 				}
// 			});
// 		} else if (fdjr_type == "tidak terkirim") {
// 			$("#loadingview").show();
// 			$.ajax({
// 				async: false,
// 				type: 'POST',
// 				url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempGagal') ?>",
// 				data: {
// 					pallet_id: pallet_id,
// 					delivery_order_id: delivery_order_id,
// 					client_wms_id: client_wms_id,
// 					depo_detail_id: depo_detail_id,
// 					act: act
// 				},
// 				success: function(response) {
// 					if (response) {
// 						if (response == 1) {
// 							console.log('success');
// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);
// 						} else {
// 							if (response == 0) {
// 								var msg = 'Tidak Ada Barang Terima Barang Gagal Kirim';
// 							} else {
// 								var msg = response;
// 							}
// 							var msgtype = 'error';

// 							// if (!window.__cfRLUnblockHandlers) return false;
// 							Swal.fire({
// 								icon: msgtype,
// 								title: 'Error',
// 								text: msg
// 							});

// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);

// 							// console.log(msg);
// 						}
// 					}
// 				}
// 			});

// 		} else if (fdjr_type == "rescheduled") {
// 			$("#loadingview").show();
// 			$.ajax({
// 				async: false,
// 				type: 'POST',
// 				url: "<?= base_url('WMS/PenerimaanRetur/InsertPalletDetailTempRescheduled') ?>",
// 				data: {
// 					pallet_id: pallet_id,
// 					delivery_order_id: delivery_order_id,
// 					client_wms_id: client_wms_id,
// 					depo_detail_id: depo_detail_id
// 				},
// 				success: function(response) {
// 					if (response) {
// 						if (response == 1) {
// 							console.log('success');
// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);
// 						} else {
// 							if (response == 0) {
// 								var msg = 'Tidak Ada Barang Terima Barang Gagal Kirim';
// 							} else {
// 								var msg = response;
// 							}
// 							var msgtype = 'error';

// 							// if (!window.__cfRLUnblockHandlers) return false;
// 							Swal.fire({
// 								icon: msgtype,
// 								title: 'Error',
// 								text: msg
// 							});

// 							$("#loadingview").hide();
// 							initDataTablePalletDetail(pallet_id);

// 							// console.log(msg);
// 						}
// 					}
// 				}
// 			});

// 		}
// 	}
// );

function ViewPallet(pallet_id) {
    $("#viewdetailpallet").show();
    // $("#viewmodaldetailpallet").modal('show');
    $('#penerimaanpenjualandetail-pallet_id').val(pallet_id);
    sku_fdjr_by_pallet();
    initDataTablePalletDetail(pallet_id);
}

function ViewPallet2(pallet_id) {
    $("#viewdetailpallet").show();

    initDataTablePalletDetail2(pallet_id);
}

function initDataTablePallet() {
    var delivery_order_batch_id = $("#penerimaanpenjualan-delivery_order_batch_id").val();

    $("#tablepallet tbody").html('');

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/GetPallet') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id
        },
        dataType: "JSON",
        success: function(response) {
            if (response > 0) {
                $.each(response, function(i, v) {
                    $("#tablepallet > tbody").append(`
								<tr id="row-${i}">
									<td style="display: none">
										<input type="hidden" id="item-${i}-penerimaanpenjualandetail2-pallet_id" value="${v.pallet_id}"/>
									</td>
									<td class="text-center">
										<div class="head-switch">
										<div class="switch-holder">
												<div class="switch-toggle">
													<input type="checkbox" id="check_scan_${i}" class="check_scan">
													<label for="check_scan_${i}"></label>
												</div>
												<div class="switch-label">
													<button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-idx="${i}"> <i class="fas fa-qrcode"></i> Scan</button>
													<button type="button" class="btn btn-warning input_rak" name="input_rak" data-idx="${i}" style="display:none"> <i class="fas fa-keyboard"></i> Input</button>
												</div>
											</div>
										</div
									</td>
									<td class="text-center"><span id="pallet-rak_lajur_detail_id_${i}"></span></td>
									<td class="text-center">${v.pallet_kode}</td>
									<td class="text-center">${v.pallet_jenis_nama}</td>
									<td class="text-center"><button type="button" title="tambah sku" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPallet('${v.pallet_id}')"><i class="fa fa-plus text-primary"></i></button><button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet('${v.pallet_id}')"><i class="fa fa-trash text-danger"></i></button></td>
								</tr>
						`);
                });

                $("#tablepallet > tbody tr").each(function(i, v) {
                    let check = $(this).find("td:eq(1) input[type='checkbox']");
                    // let check = $(this).find("td:eq(7) input[id='check_scan_'" + i + "]");
                    let scan = $(this).find("td:eq(1) button[name='start_scan_by_one']");
                    let input = $(this).find("td:eq(1) button[name='input_rak']");
                    check.change(function(e) {
                        if (e.target.checked) {
                            input.show();
                            scan.hide();
                        } else {
                            input.hide();
                            scan.show();
                        }
                    });
                });
            }
        }
    });
}

function initDataTablePalletDetail(pallet_id) {
    var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

    $("#tablepalletdetail tbody").html('');

    if (depo_detail_id != "") {

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/PenerimaanRetur/GetPalletDetail') ?>",
            data: {
                pallet_id: pallet_id
            },
            dataType: "JSON",
            success: function(response) {
                if (response != 0) {
                    $.each(response, function(i, v) {
                        $("#tablepalletdetail > tbody").append(`
								<tr id="row-${i}">
									<td class="text-center">${v.principle}</td>
									<td class="text-center">${v.sku_kode}</td>
									<td class="text-center">${v.sku_nama_produk}</td>
									<td class="text-center">${v.sku_satuan}</td>
									<td class="text-center">${v.sku_kemasan}</td>
									<td class="text-center">
										<input type="date" class="form-control" id="slcskuexpireddate${i}" value="${v.sku_stock_expired_date}" onchange="UpdateSkuExpDatePallet('${v.pallet_detail_id}','${v.sku_id}',this.value,'${depo_detail_id}')" disabled>
										<input type="hidden" id="slcskuexpireddate${i}" value="${v.sku_stock_expired_date}">
									</td>
									<td class="text-center">
										<input type="text" style="width:100%;" class="form-control" id="jumlah_barang${i}" value="${v.jumlah_sisa}" onchange="UpdateQtySKUPallet('${v.delivery_order_batch_id}','${v.pallet_detail_id}','${v.sku_id}',this.value,'${v.penerimaan_tipe_nama}')">
									</td>
									<td class="text-center">${v.sku_stock_batch_no}</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail('${v.pallet_id}','${v.pallet_detail_id}')"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							`);

                        // if (v.penerimaan_tipe_nama == "retur") {
                        // 	$("#tablepalletdetail > tbody").append(`
                        // 		<tr id="row-${i}">
                        // 			<td class="text-center">${v.principle}</td>
                        // 			<td class="text-center">${v.sku_kode}</td>
                        // 			<td class="text-center">${v.sku_nama_produk}</td>
                        // 			<td class="text-center">${v.sku_satuan}</td>
                        // 			<td class="text-center">${v.sku_kemasan}</td>
                        // 			<td class="text-center">
                        // 				<input type="date" class="form-control" id="slcskuexpireddate${i}" value="${v.sku_stock_expired_date}" onchange="UpdateSkuExpDatePallet('${v.pallet_detail_id}','${v.sku_id}',this.value,'${depo_detail_id}')">
                        // 				<input type="hidden" id="slcskuexpireddate${i}" value="${v.sku_stock_expired_date}">
                        // 			</td>
                        // 			<td class="text-center">
                        // 				<input type="text" style="width:100%;" class="form-control" id="jumlah_barang${i}" value="${v.sku_stock_qty}" onchange="UpdateQtySKUPallet('${v.delivery_order_batch_id}','${v.pallet_detail_id}','${v.sku_id}',this.value,'${v.penerimaan_tipe_nama}')">
                        // 			</td>
                        // 			<td class="text-center">${v.penerimaan_tipe_nama}</td>
                        // 			<td class="text-center">
                        // 				<button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail('${v.pallet_id}','${v.pallet_detail_id}')"><i class="fa fa-trash"></i></button>
                        // 			</td>
                        // 		</tr>
                        // 	`);

                        // } else {
                        // 	$("#tablepalletdetail > tbody").append(`
                        // 		<tr id="row-${i}">
                        // 			<td class="text-center">${v.principle}</td>
                        // 			<td class="text-center">${v.sku_kode}</td>
                        // 			<td class="text-center">${v.sku_nama_produk}</td>
                        // 			<td class="text-center">${v.sku_satuan}</td>
                        // 			<td class="text-center">${v.sku_kemasan}</td>
                        // 			<td class="text-center">
                        // 				<select class="form-control" id="slcskuexpireddate${i}" onchange="UpdateSkuExpDatePallet('${v.pallet_detail_id}','${v.sku_id}',this.value,'${depo_detail_id}')">
                        // 				</select>
                        // 			</td>
                        // 			<td class="text-center">
                        // 				<input type="text" style="width:100%;" class="form-control" id="jumlah_barang${i}" value="${v.sku_stock_qty}" onchange="UpdateQtySKUPallet('${v.delivery_order_batch_id}','${v.pallet_detail_id}','${v.sku_id}',this.value,'${v.penerimaan_tipe_nama}')">
                        // 			</td>
                        // 			<td class="text-center">${v.penerimaan_tipe_nama}</td>
                        // 			<td class="text-center">
                        // 				<button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail('${v.pallet_id}','${v.pallet_detail_id}')"><i class="fa fa-trash"></i></button>
                        // 			</td>
                        // 		</tr>
                        // 	`);

                        // 	get_sku_exp_date(v.pallet_id, v.pallet_detail_id, v.sku_id, v.sku_stock_id, v.sku_stock_expired_date, i)

                        // }
                    });
                }
            }
        });

    } else {
        message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
    }
}

function initDataTablePalletDetail2(pallet_id) {

    $("#tablepalletdetail tbody").html('');

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/GetPalletDetail2') ?>",
        data: {
            penerimaan_penjualan_id: $("#penerimaanpenjualan-penerimaan_penjualan_id").val(),
            delivery_order_id: $("#penerimaanpenjualan-delivery_order_id").val(),
            pallet_id: pallet_id
        },
        dataType: "JSON",
        success: function(response) {
            if (response != 0) {
                $.each(response, function(i, v) {
                    $("#tablepalletdetail > tbody").append(`
						<tr id="row-${i}">
							<td class="text-center">${v.principle}</td>
							<td class="text-center">${v.sku_kode}</td>
							<td class="text-center">${v.sku_nama_produk}</td>
							<td class="text-center">${v.sku_satuan}</td>
							<td class="text-center">${v.sku_kemasan}</td>
							<td class="text-center">${v.sku_stock_expired_date}</td>
							<td class="text-center">${v.sku_stock_terima}</td>
							<td class="text-center">${v.sku_stock_batch_no}</td>
						</tr>
					`);
                });
            }
        }
    });
}

function get_sku_exp_date(pallet_id, pallet_detail_id, sku_id, sku_stock_id, sku_stock_expired_date, index) {

    var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

    $("#slcskuexpireddate" + index).html('');

    $.ajax({
        async: false,
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/GetSKUExpiredDate') ?>",
        data: "sku_id=" + sku_id,
        dataType: "json",
        success: function(response) {
            $("#slcskuexpireddate" + index).append('<option value="">** Pilih SKU Exp **</option>');
            $.each(response, function(i, v) {
                if (sku_stock_id == v.sku_stock_id) {
                    $("#slcskuexpireddate" + index).append('<option value="' + v
                        .sku_stock_expired_date + '" selected>' + v.sku_stock_expired_date +
                        '</option>');
                    UpdateSkuExpDatePallet(pallet_detail_id, sku_id, sku_stock_expired_date,
                        depo_detail_id);
                } else {
                    $("#slcskuexpireddate" + index).append('<option value="' + v
                        .sku_stock_expired_date + '">' + v.sku_stock_expired_date + '</option>');
                }

                // $("#slcskuexpireddate" + index).append('<option value="' + v.sku_stock_expired_date + '">' + v.sku_stock_expired_date + '</option>');
            });
        }
    });
}

function UpdateSkuExpDatePallet(pallet_detail_id, sku_id, sku_stock_expired_date, depo_detail_id) {
    // var arr_sku_stock_id = sku_stock_id.split(" || ");

    $.ajax({
        async: false,
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/UpdateSkuExpDatePalletTemp') ?>",
        data: {
            pallet_detail_id: pallet_detail_id,
            sku_id: sku_id,
            sku_stock_expired_date: sku_stock_expired_date,
            depo_detail_id: depo_detail_id
        },
        success: function(response) {
            if (response) {
                if (response == 1) {
                    console.log('success');
                } else {
                    console.log(response);
                }
            }
        }
    });
}

function DeletePallet(pallet_id) {
    Swal.fire({
        title: 'Apa anda yakin ingin menghapus data pallet ?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?= base_url('WMS/PenerimaanRetur/DeletePalletTemp') ?>",
                data: {
                    pallet_id: pallet_id
                },
                success: function(response) {
                    if (response) {
                        if (response == 1) {
                            console.log('success');
                            initDataPallet();
                            initDataTablePalletDetail(pallet_id);
                            $("#viewdetailpalletdoretur").hide();
                        } else {
                            if (response == 0) {
                                var msg = 'Data Gagal Dihapus';
                            } else {
                                var msg = response;
                            }
                            var msgtype = 'error';

                            //if (!window.__cfRLUnblockHandlers) return false;
                            // new PNotify
                            // 	({
                            // 		title: 'Error',
                            // 		text: msg,
                            // 		type: msgtype,
                            // 		styling: 'bootstrap3',
                            // 		delay: 3000,
                            // 		stack: stack_center
                            // 	});

                            // console.log(msg);
                        }
                    }
                }
            });
        }
    });
}

function DeletePalletDetail(pallet_id, pallet_detail_id) {
    $.ajax({
        async: false,
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/DeletePalletDetailTemp') ?>",
        data: {
            pallet_detail_id: pallet_detail_id
        },
        success: function(response) {
            if (response) {
                if (response == 1) {
                    console.log('success');
                    initDataTablePalletDetail(pallet_id);
                } else {
                    if (response == 0) {
                        var msg = 'Data Gagal Dihapus';
                    } else {
                        var msg = response;
                    }
                    var msgtype = 'error';

                    // console.log(msg);
                }
            }
        }
    });
}

$("#btnsavebtb").click(
    function() {
        var delivery_order_batch_id = $("#penerimaanpenjualan-delivery_order_batch_id").val();
        var delivery_order_id = $("#penerimaanpenjualan-delivery_order_id").val();
        var client_wms_id = $("#penerimaanpenjualan-client_wms_id").val();
        var principle_id = $("#penerimaanpenjualan-principle_id").val();
        var karyawan_id = $("#penerimaanpenjualan-karyawan_id").val();
        var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();
        var lastUpdated = $("#penerimaanpenjualan-delivery_order_update_tgl").val();
        var penerimaan_penjualan_keterangan = "";
        var act = '<?= $act ?>';

        arr_penerimaan_tipe = [];
        // arr_list_penerimaan_tipe = [];

        let error = false;

        $("select[name*='kondisiBarang']").map(function() {
            if (this.value == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Kondisi barang tidak boleh kosong!'
                });

                error = true;
                return false;
            }
        });

        if (error) return false;


        const kondisiBarang = $("select[name*='kondisiBarang']").map(function() {
            const splitData = this.value.split(',')
            return {
                delivery_order_id: splitData[1],
                sku_id: splitData[2],
                kondisiBarang: splitData[0],
            }
        }).get();

        var arr_list_penerimaan_tipe = $('#penerimaanpenjualan-penerimaan_tipe').val();
        // arr_list_penerimaan_tipe.push($("#penerimaanpenjualan-penerimaan_tipe").val());

        // var result = arr_list_penerimaan_tipe.reduce((unique, o) => {
        // 	if (!unique.some(obj => obj === o)) {
        // 		unique.push(o);
        // 	}
        // 	return unique;
        // }, []);

        // arr_list_penerimaan_tipe = result;
        arr_do_status = arr_list_penerimaan_tipe.split(',');

        // console.log(arr_do_status);
        // return false;

        for (var i = 0; i < arr_do_status.length; i++) {
            var do_status = arr_do_status[i];
            if (do_status == "retur" || do_status == "terkirim") {
                arr_penerimaan_tipe.push({
                    'tipe': "retur",
                    'depo_detail_id': $("#penerimaanpenjualan-depo_detail_id").val(),
                    'penerimaan_tipe_id': "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D"
                });
            } else if (do_status == "terkirim sebagian") {
                arr_penerimaan_tipe.push({
                    'tipe': "terkirim sebagian",
                    'depo_detail_id': $("#penerimaanpenjualan-depo_detail_id").val(),
                    'penerimaan_tipe_id': "29F5A94F-C55E-4BA0-B3EE-57A93327735F"
                });
            } else if (do_status == "tidak terkirim") {
                arr_penerimaan_tipe.push({
                    'tipe': "tidak terkirim",
                    'depo_detail_id': $("#penerimaanpenjualan-depo_detail_id").val(),
                    'penerimaan_tipe_id': "79F2522A-CEA5-4FF8-BC79-C0B28808877D"
                });
            } else if (do_status == "titipan") {
                arr_penerimaan_tipe.push({
                    'tipe': "titipan",
                    'depo_detail_id': $("#penerimaanpenjualan-depo_detail_id").val(),
                    'penerimaan_tipe_id': "A9F3967E-94ED-4761-B385-4770FEEC229A"
                });
            } else if (do_status == "rescheduled") {
                arr_penerimaan_tipe.push({
                    'tipe': "rescheduled",
                    'depo_detail_id': $("#penerimaanpenjualan-depo_detail_id").val(),
                    'penerimaan_tipe_id': "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D"
                });
            }
        }

        var arr_dodetail = [];
        $("#tabledoretur > tbody tr").each(function() {
            var do_id = $(this).find("td:eq(0) input[class='doid']").val();
            var sku_id = $(this).find("td:eq(0) input[class='skuid']").val();
            var principle_id = $(this).find("td:eq(0) input[class='principleid']").val();
            var sku_qty = $(this).find("td:eq(8)").text();
            var sku_sisa = $(this).find("td:eq(9)").text();
            var exp_date = $(this).find("td:eq(10)").text();
            var kondisi = $(this).find("td:eq(11) select").val();
            var client_wms_id = $(this).find("td:eq(12)").text();

            arr_dodetail.push({
                do_id: do_id,
                sku_id: sku_id,
                principle_id: principle_id,
                sku_qty: sku_qty,
                sku_sisa: sku_sisa,
                exp_date: exp_date,
                kondisi: kondisi,
                client_wms_id: client_wms_id
            })
        })

        // console.log(arr_dodetail);
        // return false;

        Swal.fire({
            title: '<b>APA ANDA YAKIN?</b>',
            text: "BTB yang sudah diibuat tidak bisa diubah",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.value == true) {

                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });


                if ($("#penerimaanpenjualan-karyawan_id").val() == "") {
                    message("Error", "Checker Tidak Dipilih!", "error");
                    return false;
                }
                // console.log(delivery_order_id);
                // return false;
                setTimeout(function() {

                    messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!',
                        'Iya, Simpan', 'Tidak, Tutup').then((result) => {
                        if (result.value == true) {
                            requestAjax(
                                "<?= base_url('WMS/PenerimaanRetur/InsertPenerimaanPenjualan') ?>", {
                                    delivery_order_batch_id: delivery_order_batch_id,
                                    delivery_order_id: delivery_order_id,
                                    karyawan_id: karyawan_id,
                                    client_wms_id: client_wms_id,
                                    principle_id: "",
                                    penerimaan_penjualan_keterangan: penerimaan_penjualan_keterangan,
                                    depo_detail_id: depo_detail_id,
                                    arr_penerimaan_tipe: arr_penerimaan_tipe,
                                    kondisiBarang,
                                    lastUpdated: lastUpdated,
                                    arr_dodetail: arr_dodetail,
                                    act: act
                                }, "POST", "JSON",
                                function(response) {
                                    if (response == 1) {

                                        var msg = 'Data berhasil disimpan';
                                        var msgtype = 'success';

                                        //if (!window.__cfRLUnblockHandlers) return false;
                                        Swal.fire({
                                            position: 'center',
                                            icon: msgtype,
                                            title: msg,
                                            timer: 1000
                                        });

                                        // window.location.reload();

                                        setTimeout(function() {
                                            window.location.href =
                                                "<?php echo base_url() ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" +
                                                delivery_order_batch_id;
                                        }, 500);

                                        // window.close();

                                        // GetBTBFormMenu();
                                    } else {
                                        if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        } else if (response == 3) {
                                            var msg = 'Jumlah SKU Pallet Masih Kosong!';

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: msg
                                            });

                                        } else if (response == 4) {
                                            var msg = 'FDJR Sudah Memiliki Pallet!';

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: msg
                                            });

                                        } else if (response == 5) {
                                            var msg = 'Tambah SKU Master';

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'SKU Tidak Ada!',
                                                text: msg
                                            });

                                        } else {
                                            var msg = response;
                                            var msgtype = 'error';

                                            // if (!window.__cfRLUnblockHandlers) return false;
                                            new PNotify
                                                ({
                                                    title: 'Error',
                                                    text: msg,
                                                    type: msgtype,
                                                    styling: 'bootstrap3',
                                                    delay: 3000,
                                                    stack: stack_center
                                                });
                                        }
                                        console.log(msg);
                                    }
                                }, "#btnsavebtb")
                        }
                    });

                    // $.ajax({
                    // 	async: false,
                    // 	type: 'POST',
                    // 	url: "<?= base_url('WMS/PenerimaanRetur/InsertPenerimaanPenjualan') ?>",
                    // 	data: {
                    // 		delivery_order_batch_id: delivery_order_batch_id,
                    // 		delivery_order_id: delivery_order_id,
                    // 		karyawan_id: karyawan_id,
                    // 		client_wms_id: client_wms_id,
                    // 		principle_id: "",
                    // 		penerimaan_penjualan_keterangan: penerimaan_penjualan_keterangan,
                    // 		depo_detail_id: depo_detail_id,
                    // 		arr_penerimaan_tipe: arr_penerimaan_tipe,
                    // 		kondisiBarang
                    // 	},
                    // 	beforeSend: function() {
                    // 		Swal.fire({
                    // 			title: 'Loading ...',
                    // 			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    // 			timerProgressBar: false,
                    // 			showConfirmButton: false
                    // 		});

                    // 		$("#btnsavebtb").prop("disabled", true);
                    // 		$("#loadingview").show();
                    // 	},
                    // 	success: function(response) {

                    // 		Swal.close();

                    // 		if (response) {
                    // 			if (response == 1) {

                    // 				var msg = 'Data berhasil disimpan';
                    // 				var msgtype = 'success';

                    // 				//if (!window.__cfRLUnblockHandlers) return false;
                    // 				Swal.fire({
                    // 					position: 'center',
                    // 					icon: msgtype,
                    // 					title: msg,
                    // 					timer: 1000
                    // 				});

                    // 				// window.location.reload();

                    // 				setTimeout(function() {
                    // 					window.location.href = "<?php echo base_url() ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" + delivery_order_batch_id;
                    // 				}, 500);

                    // 				// window.close();

                    // 				// GetBTBFormMenu();
                    // 			} else {
                    // 				if (response == 2) {
                    // 					var msg = 'BTB Sudah Ada!';

                    // 					Swal.fire({
                    // 						icon: 'error',
                    // 						title: 'Error',
                    // 						text: msg
                    // 					});

                    // 				} else if (response == 3) {
                    // 					var msg = 'Jumlah SKU Pallet Masih Kosong!';

                    // 					Swal.fire({
                    // 						icon: 'error',
                    // 						title: 'Error',
                    // 						text: msg
                    // 					});

                    // 				} else if (response == 4) {
                    // 					var msg = 'FDJR Sudah Memiliki Pallet!';

                    // 					Swal.fire({
                    // 						icon: 'error',
                    // 						title: 'Error',
                    // 						text: msg
                    // 					});

                    // 				} else if (response == 5) {
                    // 					var msg = 'Tambah SKU Master';

                    // 					Swal.fire({
                    // 						icon: 'error',
                    // 						title: 'SKU Tidak Ada!',
                    // 						text: msg
                    // 					});

                    // 				} else {
                    // 					var msg = response;
                    // 					var msgtype = 'error';

                    // 					// if (!window.__cfRLUnblockHandlers) return false;
                    // 					new PNotify
                    // 						({
                    // 							title: 'Error',
                    // 							text: msg,
                    // 							type: msgtype,
                    // 							styling: 'bootstrap3',
                    // 							delay: 3000,
                    // 							stack: stack_center
                    // 						});
                    // 				}
                    // 				// console.log(msg);
                    // 			}
                    // 		}
                    // 		$("#btnsavebtb").prop("disabled", false);
                    // 		$("#loadingview").hide();
                    // 	},
                    // 	error: function(xhr, ajaxOptions, thrownError) {
                    // 		message("Error", "Error 500 Internal Server Connection Failure", "error");

                    // 		$("#btnsavebtb").prop("disabled", false);
                    // 		$("#loadingview").hide();

                    // 		Swal.close();
                    // 	}
                    // });

                }, 1000);
            }
        });
    }
);

$("#btn_konfirmasi_pengiriman").on("click", function() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();
    var belum_btb = 0;

    <?php if ($act == "ProsesBTBMenu") { ?>
    Swal.fire({
        title: '<b>APA ANDA YAKIN?</b>',
        text: "BTB yang sudah diibuat tidak bisa diubah",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya'
    }).then((result) => {
        if (result.value == true) {
            $.each(cek_btb, function(i, v) {
                if (v.sudah_btb == "BELUM BTB") {
                    var msg = GetLanguageByKode('CAPTION-ALERT-BTBBELUMDIBUAT');
                    new PNotify
                        ({
                            title: 'Error',
                            text: 'DO ' + v.delivery_order_kode + ' ' + msg,
                            type: 'error',
                            styling: 'bootstrap3',
                            delay: 3000,
                            stack: stack_center
                        });

                    belum_btb++;
                }

            });

            $("#btn_konfirmasi_pengiriman").prop("disabled", true);

            Swal.fire({
                title: 'Loading ...',
                html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                timerProgressBar: false,
                showConfirmButton: false
            });

            setTimeout(() => {

                if (belum_btb == 0) {
                    // alert("jebol")
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: "<?= base_url('WMS/PenerimaanRetur/UpdateClosingPengirimanFDJR') ?>",
                        data: {
                            delivery_order_batch_id: delivery_order_batch_id,
                            delivery_order_batch_status: 'Closing Delivery Confirm'
                        },
                        beforeSend: function() {

                            Swal.fire({
                                title: 'Loading ...',
                                html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                timerProgressBar: false,
                                showConfirmButton: false
                            });

                            $("#btn_simpan").prop("disabled", true);
                            $("#btn_konfirmasi_pengiriman").prop("disabled", true);
                            $("#loadingview").show();
                        },
                        success: function(response) {
                            if (response == 1) {
                                $("#loadingview").hide();

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Success',
                                    html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                    timer: 1000
                                });

                                window.location.reload();
                            }

                            // console.log(response);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            message("Error",
                                "Error 500 Internal Server Connection Failure",
                                "error");

                            $("#btn_simpan").prop("disabled", false);
                            $("#btn_konfirmasi_pengiriman").prop("disabled", false);
                            $("#loadingview").hide();
                        },
                        complete: function() {
                            $("#btn_simpan").prop("disabled", false);
                            $("#btn_konfirmasi_pengiriman").prop("disabled", false);
                            $("#loadingview").hide();
                        }
                    });
                } else {
                    Swal.close();

                    $("#btn_konfirmasi_pengiriman").prop("disabled", false);
                }

            }, 1000);
        }
    });
    <?php } ?>

});

function UpdateQtySKUPallet(delivery_order_batch_id, pallet_detail_id, sku_id, sku_stock_qty, penerimaan_tipe_nama, i) {
    $.ajax({
        async: false,
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/UpdateQtySKUPalletTemp') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id,
            pallet_detail_id: pallet_detail_id,
            sku_id: sku_id,
            sku_stock_qty: sku_stock_qty,
            penerimaan_tipe_nama: penerimaan_tipe_nama
        },
        success: function(response) {
            if (response) {
                if (response == 1) {
                    console.log('success');
                } else {
                    if (response == 0) {
                        var msg = 'Data Gagal Disimpan';
                    } else if (response == 2) {

                        $("#jumlah_barang" + i).val(0);

                        var msg = 'Qty Pallet Melebihi DO';
                    } else {
                        var msg = response;
                    }
                    var msgtype = 'error';

                    //if (!window.__cfRLUnblockHandlers) return false;
                    new PNotify
                        ({
                            title: 'Error',
                            text: msg,
                            type: msgtype,
                            styling: 'bootstrap3',
                            delay: 3000,
                            stack: stack_center
                        });

                    // console.log(msg);
                }
            }
        }
    });
}

function GetRakLajurDetail() {

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/GetRakLajurDetail') ?>",
        data: {
            depo_detail_id: $("#penerimaanpenjualan-depo_detail_id").val()
        },
        dataType: "JSON",
        success: function(response) {
            $("#penerimaanpenjualan-rak_lajur_detail_id").html('');
            $("#penerimaanpenjualan-rak_lajur_detail_id").append(
                '<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');
            $.each(response, function(i, v) {
                $("#penerimaanpenjualan-rak_lajur_detail_id").append(
                    `<option value="${v.rak_lajur_detail_id}">${v.rak_lajur_detail_nama}</option>`
                );
            });
        }
    });
}

const handlerScanInputManual = (event, value, type) => {
    const idx = type == 'notone' ? null : event.currentTarget.getAttribute('data-idx');
    if (value != "") {
        fetch('<?= base_url('WMS/PenerimaanRetur/getKodeAutoComplete?params='); ?>' + value + `&type=${type}`)
            .then(response => response.json())
            .then((results) => {
                if (!results[0]) {
                    $(`#table-fixed-${type}`).css('display', 'none')
                } else {
                    let data = "";
                    // console.log(results);
                    results.forEach(function(e, i) {
                        data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}', '${type}', '${idx}')" style="cursor:pointer">
											<td class="col-xs-1">${i + 1}.</td>
											<td class="col-xs-11">${e.kode}</td>
									</tr>
									`;
                    })

                    $(`#konten-table-${type}`).html(data)
                    // console.log(data);
                    $(`#table-fixed-${type}`).css('display', 'block')
                }
            });
    } else {
        $(`#table-fixed-${type}`).css('display', 'none');
    }
}


function getNoSuratJalanEks(data, type, idx) {
    $(`#kode_barcode_${type}`).val(data);
    $(`#table-fixed-${type}`).css('display', 'none')
    if (type == 'notone') {
        $("#check_kode").click()
    }
    if (type == 'one') {
        var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

        $("#pallet-rak_lajur_detail_id_" + idx).html('');
        $.ajax({
            async: false,
            url: "<?= base_url('WMS/PenerimaanRetur/check_rak_lajur_detail'); ?>",
            type: "POST",
            data: {
                depo_detail_id: depo_detail_id,
                pallet_id: $("#item-" + idx + "-penerimaanpenjualandetail2-pallet_id").val(),
                kode: data
            },
            dataType: "JSON",
            success: function(response) {
                $("#txtpreviewscan2").val(response.kode);
                $("#pallet-rak_lajur_detail_id_" + idx).append(response.kode);

                if (response.type == 200) {
                    Swal.fire("Success!", response.message, "success").then(function(result) {
                        if (result.value == true) {
                            $("#manual_input_rak").modal('hide');
                            $("#kode_barcode_notone").val('')
                        }
                    });
                    $('#tablepallet').fadeOut("slow", function() {
                        $(this).hide();
                    }).fadeIn("slow", function() {
                        $(this).show();
                        initDataPallet();
                    });
                } else if (response.type == 201) {
                    message("Error!", response.message, "error");
                } else {
                    message("Info!", response.message, "info");
                }
            },
        });
    }
}

$(document).on("click", "#check_master", function(e) {
    if (e.target.checked) {
        $("#switch-button-pallet").hide();
        $("#switch-scan-pallet").show();
    } else {
        $("#switch-button-pallet").show();
        $("#switch-scan-pallet").hide();
    }
});

function UpdatePalletKodeTempByIdTemp(pallet_id, pallet_kode) {
    $.ajax({
        async: false,
        type: 'POST',
        url: "<?= base_url('WMS/PenerimaanRetur/UpdatePalletKodeTempByIdTemp') ?>",
        data: {
            pallet_id: pallet_id,
            pallet_kode: pallet_kode
        },
        dataType: "JSON",
        success: function(response) {
            if (response == 1) {
                console.log("UpdatePalletKodeTempByIdTemp success");

            } else {
                console.log("UpdatePalletKodeTempByIdTemp failed");

            }
        }
    });

}
</script>