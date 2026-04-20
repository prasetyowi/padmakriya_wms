<script type="text/javascript">
    loadingBeforeReadyPage()

    <?php if ($act == "ProsesTerimaMutasi") { ?>
        const html5QrCode = new Html5Qrcode("preview");
        const html5QrCode2 = new Html5Qrcode("preview_by_one");
        let timerInterval
    <?php } ?>

    function confirmAlert(title, text, icon, btnYes, btnNo) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#c9302c",
            confirmButtonText: btnYes,
            cancelButtonText: btnNo
        })
    }

    $(document).ready(function() {
        $("#filter_dokumen").select2();
        $("#mutasi_depo_gudang").select2();

        $('#filter_date').daterangepicker({
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
    })

    $("#searchData").on('click', function() {
        var filter_date = $("#filter_date").val();
        var filter_dokumen = $("#filter_dokumen").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/getDataMutasi') ?>",
            data: {
                filter_date: filter_date,
                filter_dokumen: filter_dokumen
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            success: function(response) {
                Swal.close();

                $("#table_mutasiAntarUnit > tbody").empty();

                if ($.fn.DataTable.isDataTable('#table_mutasiAntarUnit')) {
                    $('#table_mutasiAntarUnit').DataTable().clear();
                    $('#table_mutasiAntarUnit').DataTable().destroy();
                }

                if (response.length > 0) {
                    $.each(response, function(i, v) {
                        var button = ""
                        if ('<?= $this->session->userdata('depo_id') ?>' == v.depo_id_tujuan) {
                            if (v.tr_mutasi_depo_info == "Belum diterima") {
                                button =
                                    `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/prosesTerimaMutasi/') ?>${v.tr_mutasi_depo_id}" type="button" id="terimaMutasi" class="btn btn-warning" target="_blank" title="Proses terima"><i class="fa fa-pencil"></i></a>`;
                            } else {
                                if (v.is_generate == 0) {
                                    button =
                                        `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/viewDetailMutasi/') ?>${v.tr_mutasi_depo_id}" type="button" id="terimaMutasi" class="btn btn-info" target="_blank" title="Lihat detail"><i class="fa fa-eye"></i></a>
                                        <button onclick="generateMutasiStock('${v.tr_mutasi_depo_terima_id}')" type="button" id="generateMutasi" class="btn btn-primary" title="Generate mutasi stok"><i class="fa fa-save"></i></button>`;
                                } else {
                                    button =
                                        `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/viewDetailMutasi/') ?>${v.tr_mutasi_depo_id}" type="button" id="terimaMutasi" class="btn btn-info" target="_blank" title="Lihat detail"><i class="fa fa-eye"></i></a>
                                        <button onclick="showKodeMutasiStock('${v.tr_mutasi_depo_terima_id}')" type="button" id="generateMutasi" class="btn btn-success" title="Lihat kode generate mutasi stok"><i class="fa fa-search"></i></button>`;
                                }
                            }
                        }

                        $("#table_mutasiAntarUnit > tbody").append(`
                            <tr class="text-center va-m-i">
                                <td>${i + 1}</td>
                                <td>${v.tr_mutasi_depo_tgl_create}</td>
                                <td>${v.tr_mutasi_depo_kode}</td>
                                <td>${v.tr_mutasi_depo_info}</td>
                                <td>${v.depo_asal}</td>
                                <td>${v.depo_tujuan}</td>
                                <td>
                                    ${button}
                                </td>
                            </tr>
                        `);
                    })
                }
                $("#resultData").show('slow');
                $('#table_mutasiAntarUnit').DataTable();
            }
        })
    })

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
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/check_kode_pallet'); ?>",
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
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/check_rak_lajur_detail'); ?>",
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
            if ($("#mutasi_depo_gudang").val() != "") {
                $.ajax({
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/check_kode_pallet'); ?>",
                    type: "POST",
                    data: {
                        kode_pallet: barcode,
                        depo_detail_id: $("#mutasi_depo_gudang").val()
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

                            $('#tablepallet').fadeOut("slow", function() {
                                $(this).hide();

                            }).fadeIn("slow", function() {
                                $(this).show();
                                initDataPallet(data.kode, data.pallet_id);
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

    $(document).on("click", ".tutup_input_manual", function() {
        $("#manual_input_rak").modal('hide');
        $("#nama_rak").val("");
    });

    $("#searh_kode_pallet").on('click', function() {
        var type = $("#kode_barcode_notone").attr('data-type');
        const idx = type == 'notone' ? null : event.currentTarget.getAttribute('data-idx');
        if ($("#kode_barcode_notone").val() != "") {
            fetch('<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/getKodeAutoComplete?params='); ?>' +
                    $("#kode_barcode_notone")
                    .val() + `&type=${type}`)
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
                                    <td class="col-xs-8">${e.kode}</td>
                                    <td class="col-xs-3">${e.is_aktif == 0 ? 'Tidak Aktif' : 'Aktif'}</td>
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
    })

    function getNoSuratJalanEks(data, type, idx) {
        $(`#kode_barcode_${type}`).val(data);
        $(`#table-fixed-${type}`).css('display', 'none')
        if (type == 'notone') {
            $("#check_kode").click()
        }
    }

    function initDataPallet(kode, pallet_id) {
        $('#tablepallet > tbody').empty();

        $("#tablepallet > tbody").append(`
        <tr id="row">
            <td style="display: none">
                <input type="hidden" id="pallet_id" value="${pallet_id}"/>
            </td>
            <td class="text-center">${kode}</td>
            <td class="text-center">Pallet</td>
            <td class="text-center">
                <button type="button" title="tambah sku" class="btn btn-primary" id="btnInsertPalletDetailTemp" style="border:none;background:transparent" onClick="ViewPallet('${pallet_id}')"><i class="fa fa-plus text-primary"></i></button>
                <button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet('${pallet_id}')"><i class="fa fa-trash text-danger"></i></button></td>
        </tr>
    `);
    }

    function ViewPallet(pallet_id) {
        $("#viewdetailpallet").show();
        // $("#viewmodaldetailpallet").modal('show');
        $('#mutasi_depo-pallet_id').val(pallet_id);
        insertIntoPalletDetailTemp();
        setTimeout(() => {
            initDataTablePalletDetail(pallet_id);
        }, 500);
    }

    function insertIntoPalletDetailTemp() {
        var pallet_id = $('#mutasi_depo-pallet_id').val();
        var mutasi_depo_id = $('#mutasi_depo_id').val();
        var depo_detail_id = $("#mutasi_depo_gudang").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/insertIntoPalletDetailTemp') ?>",
            data: {
                pallet_id: pallet_id,
                mutasi_depo_id: mutasi_depo_id,
                depo_detail_id: depo_detail_id
            },
            dataType: "JSON",
            success: function(response) {
                if (response == 1) {
                    message_topright('success', 'Berhasil menyimpan data ke pallet temp!');
                    return false;
                } else {
                    message_topright('error', 'Gagal menyimpan data ke pallet temp!');
                    return false;
                }
            }
        })
    }

    function initDataTablePalletDetail(pallet_id) {
        $('#tablepalletdetail > tbody').empty();
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/getDataMutasiDetailTemp') ?>",
            data: {
                pallet_id: pallet_id
            },
            dataType: "JSON",
            success: function(response) {
                if (response != 0) {
                    $('#tablepalletdetail').fadeOut("slow", function() {
                        $(this).hide();
                    }).fadeIn("slow", function() {
                        $(this).show();
                        $.each(response, function(i, v) {
                            $("#tablepalletdetail > tbody").append(`
								<tr>
									<td class="text-center">
                                        ${i + 1}
                                        <input type="hidden" class="form-control sku_id" id="sku_id" value="${v.sku_id}">
                                    </td>
									<td class="text-center">
                                        ${v.principle_kode}
                                        <input type="hidden" class="form-control jumlah_sisa" id="jumlah_sisa" value="${v.jumlah_sisa}">
                                    </td>
									<td class="text-center">
                                        ${v.sku_kode}
                                        <input type="hidden" class="form-control jumlah_plan" id="jumlah_plan" value="${v.sku_stock_qty}">
                                    </td>
									<td class="text-center">${v.sku_nama_produk}</td>
									<td class="text-center">${v.sku_kemasan}</td>
									<td class="text-center">${v.sku_satuan}</td>
									<td class="text-center">
										<input type="date" id="slcskuexpireddate" value="${v.sku_stock_expired_date}" class="form-control" disabled>
									</td>
									<td class="text-center">
										<input type="text" style="width:100%;" class="form-control" id="jumlah_barang" value="${v.jumlah_sisa}" disabled>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail('${v.pallet_id}','${v.pallet_detail_id}')"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							`);
                        });
                    });
                }
            }
        });
    }

    function DeletePalletDetail(pallet_id, pallet_detail_id) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/DeletePalletDetailTemp') ?>",
            data: {
                pallet_detail_id: pallet_detail_id
            },
            success: function(response) {
                if (response) {
                    if (response == 1) {
                        message_topright('success', 'Berhasil menghapus data dari pallet temp!');
                        initDataTablePalletDetail(pallet_id);
                    } else {
                        message_topright('error', 'Gagal menghapus data dari pallet temp!');
                        return false;
                    }
                }
            }
        });
    }

    $("#btnSaveTerimaMutasi").on('click', function() {
        var tr_mutasi_depo_id = $("#mutasi_depo_id").val();
        var tr_mutasi_depo_tgl_upd = $("#mutasi_depo_tgl_upd").val();
        var depo_detail_id = $("#mutasi_depo_gudang").val();
        var pallet_id = $("#mutasi_depo-pallet_id").val();

        var arr_mutasiDetail = [];
        $("#tablepalletdetail > tbody tr").each(function() {
            var sku_id = $(this).find("td:eq(0) input[type='hidden']").val();
            var jumlah_sisa = $(this).find("td:eq(1) input[type='hidden']").val();
            var jumlah_plan = $(this).find("td:eq(2) input[type='hidden']").val();
            var expired_date = $(this).find("td:eq(6) input[type='date']").val();

            arr_mutasiDetail.push({
                sku_id: sku_id,
                jumlah_sisa: jumlah_sisa,
                jumlah_plan: jumlah_plan,
                pallet_id: pallet_id,
                depo_detail_id: depo_detail_id,
                expired_date: expired_date
            })
        })

        confirmAlert('Apakah data yg akan diterima sudah benar?',
            'Tekan ya untuk melanjutkan dan tidak untuk kembali',
            'warning', 'Ya, terima', 'Tidak').then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/saveTerimaMutasi') ?>",
                    data: {
                        tr_mutasi_depo_id: tr_mutasi_depo_id,
                        tr_mutasi_depo_tgl_upd: tr_mutasi_depo_tgl_upd,
                        depo_detail_id: depo_detail_id,
                        pallet_id: pallet_id,
                        arr_mutasiDetail: arr_mutasiDetail
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response == 1) {
                            message_topright('success', 'Mutasi antar unit berhasil diterima!');
                            setTimeout(() => {
                                window.location.href =
                                    "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/PenerimaanMutasiAntarUnitMenu') ?>"
                            }, 1000);
                        } else if (response == 2) {
                            messageNotSameLastUpdated();
                            return false;
                        } else {
                            message_topright('error',
                                'Mutasi antar unit gagal diterima!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ", " + error);
                    }
                })
            }
        })
    })

    function generateMutasiStock(tr_mutasi_depo_terima_id) {
        confirmAlert('Apakah data yakin akan generate mutasi stok untuk dokumen ini?',
            'Tekan ya untuk melanjutkan dan tidak untuk kembali',
            'warning', 'Ya, generate', 'Tidak').then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/generateMutasiStock') ?>",
                    data: {
                        tr_mutasi_depo_terima_id: tr_mutasi_depo_terima_id
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response == 1) {
                            message_topright('success', 'Mutasi stock antar unit berhasil digenerate!');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            message_topright('error',
                                'Mutasi stock antar unit gagal digenerate!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ", " + error);
                    }
                })
            }
        })
    }

    function showKodeMutasiStock(tr_mutasi_depo_terima_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PenerimaanMutasiAntarUnit/showKodeMutasiStock') ?>",
            data: {
                id: tr_mutasi_depo_terima_id
            },
            dataType: "JSON",
            success: function(response) {
                $("#tableViewKodeGen > tbody").empty();

                $.each(response, function(i, v) {
                    $("#tableViewKodeGen > tbody").append(`
                    <tr>
                        <td class="text-center">${i + 1}</td>
                        <td class="text-center"><a href="<?= base_url('WMS/MutasiStokDraft/MutasiStokDraftEdit/?tr_mutasi_stok_id=') ?>${v.tr_mutasi_stok_id}" target="_blank">${v.tr_mutasi_stok_kode}</a></td>
                    </tr>
                `);
                });

                $('#modalViewKodeGen').modal('show');
            }
        })
    }
</script>