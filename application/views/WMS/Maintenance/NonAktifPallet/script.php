<script type="text/javascript">
    const html5QrCode = new Html5Qrcode("preview");
    let timerInterval

    $(document).ready(
        function() {
            $('.select2').select2({
                width: '100%'
            });
        }
    );

    function message_custom(titleType, iconType, htmlType) {
        Swal.fire({
            title: titleType,
            icon: iconType,
            html: htmlType,
        });
    }

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

    $("#btn-history-pallet").on("click", function() {
        let depo = $('span[class="pallet-depo_nama').eq(0).text();
        let pallet_kode = $('span[class="pallet-pallet_kode').eq(0).text();

        $.ajax({
            url: "<?= base_url('WMS/Maintenance/NonAktifPallet/get_history_pallet_by_id'); ?>",
            type: "POST",
            data: {
                pallet_id: $("#pallet-pallet_id").val()
            },
            dataType: "JSON",
            success: function(response) {
                $("#table-history-pallet > tbody").empty();

                $("#txtpreviewscan").val('');
                $(".pallethistory-pallet_kode").html('');
                $(".pallethistory-depo_nama").html('');

                if (response != 0) {

                    $(".pallethistory-pallet_kode").append(pallet_kode);
                    $(".pallethistory-depo_nama").append(depo);

                    $.each(response, function(i, v) {
                        $("#table-history-pallet > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">${v.depo_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.tanggal_create}</td>
							</tr>
						`);
                    });

                } else {
                    $(".pallethistory-pallet_kode").html('');
                    $(".pallethistory-depo_nama").html('');
                }

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
            $("#kode_barcode").val("");
            $("#txtpreviewscan").val("");
            $('#myFileInput').val("");
            $('#show-file').empty();
        }
    });

    $(document).on("click", "#input_manual", function() {
        $("#input_manual").hide();
        $("#close_input").show();
        $("#preview_input_manual").show();
        // $("#start_scan").attr("disabled", true);
    });

    $(document).on("click", "#close_input", function() {
        remove_close_input();
    });

    function remove_close_input() {
        $("#input_manual").show();
        $("#close_input").hide();
        $("#preview_input_manual").hide();
        $("#kode_barcode").val("");
        $("#txtpreviewscan").val("");
        $('#myFileInput').val("");
        $('#show-file').empty();
        $("#start_scan").attr("disabled", false);
    }

    $(document).on("click", "#start_scan", function() {

        var idx = 0;

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

            $.ajax({
                url: "<?= base_url('WMS/Maintenance/NonAktifPallet/get_pallet_by_kode'); ?>",
                type: "POST",
                data: {
                    pallet_kode: decodedText
                },
                dataType: "JSON",
                beforeSend: () => {
                    $("#loading_cek_manual").show();
                },
                success: function(response) {
                    $("#table-pallet-detail > tbody").empty();

                    $("#txtpreviewscan").val('');
                    $("#pallet-pallet_id").val('');
                    $(".pallet-pallet_kode").html('');
                    $(".pallet-depo_nama").html('');
                    $(".pallet-depo_detail_nama").html('');
                    $(".pallet-rak_lajur_nama").html('');
                    $(".pallet-rak_lajur_detail_nama").html('');
                    $(".pallet-is_lock").html('');
                    $(".pallet-is_lock_reason").html('');

                    if (response.detail != 0) {

                        $.each(response.detail, function(i, v) {
                            $("#table-pallet-detail > tbody").append(`
								<tr>
									<td style="text-align: center; vertical-align: middle;">${v.sku_kode}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
									<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_stock_qty}</td>
								</tr>
							`);
                        });

                        var cekDetail = $("#table-pallet-detail > tbody > tr").length;

                        if (cekDetail < 1) {
                            $("#nonAktifPallet").removeAttr('disabled', true);
                        } else {
                            $("#nonAktifPallet").attr("disabled", true);
                        }

                        $.each(response.header, function(i, v) {
                            if (v.pallet_is_lock == 1) {
                                v.pallet_is_lock =
                                    'Locked';
                            } else {
                                v.pallet_is_lock = 'Unlocked'
                            }
                            $("#pallet-pallet_id").val(v.pallet_id);
                            $(".pallet-pallet_kode").append(v.pallet_kode);
                            $(".pallet-depo_nama").append(v.depo_nama);
                            $(".pallet-depo_detail_nama").append(v.depo_detail_nama);
                            $(".pallet-rak_lajur_nama").append(v.rak_lajur_nama);
                            $(".pallet-rak_lajur_detail_nama").append(v
                                .rak_lajur_detail_nama);
                            $(".pallet-is_lock").append(v
                                .pallet_is_lock);
                            $(".pallet-is_lock_reason").append(v
                                .pallet_is_lock_reason);

                            $("#txtpreviewscan").val(decodedText);
                        });

                    } else {
                        $("#pallet-pallet_id").val('');
                        $(".pallet-pallet_kode").html('');
                        $(".pallet-depo_nama").html('');
                        $(".pallet-depo_detail_nama").html('');
                        $(".pallet-rak_lajur_nama").html('');
                        $(".pallet-rak_lajur_detail_nama").html('');
                        $(".pallet-is_lock").html('');
                        $(".pallet-is_lock_reason").html('');

                        $("#txtpreviewscan").val(decodedText);

                        // let alert_tes = $('span[name="CAPTION-ALERT-PALLETIDAKADA').eq(0).text();
                        let alert_tes = "Pallet Tidak Ada!";
                        message_custom("Error", "error", alert_tes);

                    }

                },
                complete: () => {
                    $("#loading_cek_manual").hide();
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
        html5QrCode.stop();
        $("#select_kamera").empty();
        $("#start_scan").show();
        $("#stop_scan").hide();
        $("#modal_scan").modal('hide');
    });

    $("#nonAktifPallet").on('click', function() {
        var pallet_id = $("#pallet-pallet_id").val();

        confirmAlert('Apakah anda yakin ingin non-aktifkan pallet ini?',
            'Tekan ya untuk melanjutkan dan tidak untuk kembali',
            'warning', 'Ya, non-aktifkan', 'Tidak').then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/Maintenance/NonAktifPallet/nonAktifPallet') ?>",
                    data: {
                        pallet_id: pallet_id
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 'success') {
                            message_topright(response.status, response
                                .message);
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            message_topright(response.status, response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ", " + error);
                    }
                })
            }
        })
    })

    $(document).on("click", "#check_kode", () => {
        let barcode = $("#kode_barcode").val();
        var idx = 0;

        if (barcode == "") {
            message("Error!", "Kode Pallet tidak boleh kosong", "error");
            return false;
        } else {
            $.ajax({
                url: "<?= base_url('WMS/Maintenance/NonAktifPallet/get_pallet_by_kode'); ?>",
                type: "POST",
                data: {
                    pallet_kode: barcode
                },
                dataType: "JSON",
                beforeSend: () => {
                    $("#loading_cek_manual").show();
                },
                success: function(response) {
                    $("#table-pallet-detail > tbody").empty();

                    if ($.fn.DataTable.isDataTable('#table-pallet-detail')) {
                        $('#table-pallet-detail').DataTable().clear();
                        $('#table-pallet-detail').DataTable().destroy();
                    }

                    $("#txtpreviewscan").val('');
                    $("#pallet-pallet_id").val('');
                    $(".pallet-pallet_kode").html('');
                    $(".pallet-depo_nama").html('');
                    $(".pallet-depo_detail_nama").html('');
                    $(".pallet-rak_lajur_nama").html('');
                    $(".pallet-rak_lajur_detail_nama").html('');
                    $(".pallet-is_lock").html('');
                    $(".pallet-is_lock_reason").html('');

                    if (response != 0) {
                        var cekQty = 0;
                        $.each(response.detail, function(i, v) {
                            //cek total qty
                            cekQty = cekQty + v.sku_stock_qty;

                            $("#table-pallet-detail > tbody").append(`
								<tr>
									<td style="text-align: center; vertical-align: middle;">
										<input type="checkbox" class="form-control check-item chk-sku" name="chk-sku" style="transform: scale(1.5)" id="chk-sku" value="${v.pallet_detail_id}" ${v.sku_stock_qty <= 0 ? '' : 'disabled'}/>
									</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_kode}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
									<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date}</td>
									<td style="text-align: center; vertical-align: middle;">${v.batch_no}</td>
									<td style="text-align: center; vertical-align: middle;">${v.sku_stock_qty}</td>
								</tr>
							`);
                        });

                        var cekDetail = $("#table-pallet-detail > tbody > tr").length;

                        if (cekDetail < 1) {
                            $("#nonAktifPallet").removeAttr('disabled', true);
                        } else {
                            $("#nonAktifPallet").attr("disabled", true);
                        }

                        $('#table-pallet-detail').DataTable({
                            'ordering': false,
                            lengthMenu: [
                                [-1],
                                ['All']
                            ]
                        });

                        $(".chk-sku").on('change', function() {
                            var totalSelected = $(".chk-sku:checked").length;
                            $("#totalSelected").text("(" + totalSelected + ")");
                            $("#deletePalletDetail").prop("disabled", totalSelected === 0);
                        });

                        // ambil pallet id
                        var pallet_id = '';
                        $.each(response.header, function(i, v) {
                            if (v.pallet_is_lock == 1) {
                                v.pallet_is_lock =
                                    'Locked';
                            } else {
                                v.pallet_is_lock = 'Unlocked'
                            }
                            $("#pallet-pallet_id").val(v.pallet_id);
                            $(".pallet-pallet_kode").append(v.pallet_kode);
                            $(".pallet-depo_nama").append(v.depo_nama);
                            $(".pallet-depo_detail_nama").append(v.depo_detail_nama);
                            $(".pallet-rak_lajur_nama").append(v.rak_lajur_nama);
                            $(".pallet-rak_lajur_detail_nama").append(v.rak_lajur_detail_nama);
                            $(".pallet-is_lock").append(v.pallet_is_lock);
                            $(".pallet-is_lock_reason").append(v.pallet_is_lock_reason);

                            $("#txtpreviewscan").val(barcode);

                            pallet_id = v.pallet_id;
                        });

                        // jika cekQty 0 maka button release pallet enable dan sebaliknya
                        if (cekQty == 0) {
                            $('#release_pallet').removeAttr('disabled', true);
                            $('#release_pallet').attr('data-pallet-id', pallet_id);
                        } else {
                            $('#release_pallet').attr('disabled', true);
                            $('#release_pallet').removeAttr('data-pallet-id', true);
                        }

                    } else {

                        $("#pallet-pallet_id").val('');
                        $(".pallet-pallet_kode").html('');
                        $(".pallet-depo_nama").html('');
                        $(".pallet-depo_detail_nama").html('');
                        $(".pallet-rak_lajur_nama").html('');
                        $(".pallet-rak_lajur_detail_nama").html('');
                        $(".pallet-is_lock").html('');
                        $(".pallet-is_lock_reason").html('');

                        $("#txtpreviewscan").val(barcode);

                        // let alert_tes = $('span[name="CAPTION-ALERT-PALLETIDAKADA').eq(0).text();
                        let alert_tes = "Pallet Tidak Ada!";
                        message_custom("Error", "error", alert_tes);

                    }
                },
                complete: () => {
                    $("#loading_cek_manual").hide();
                },
            });
        }
    })

    $("#search_kode_pallet").on('click', function() {
        if ($("#kode_barcode").val() != "") {
            fetch('<?= base_url('WMS/Maintenance/NonAktifPallet/getKodeAutoComplete?params='); ?>' + $(
                    "#kode_barcode").val())
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
    })


    // document.getElementById('kode_barcode').addEventListener('keyup', function() {
    //     const typeScan = $("#tempValForScan").val();
    //     if (this.value != "") {
    //         fetch('<?= base_url('WMS/Maintenance/NonAktifPallet/getKodeAutoComplete?params='); ?>' + this.value)
    //             .then(response => response.json())
    //             .then((results) => {
    //                 if (!results[0]) {
    //                     document.getElementById('table-fixed').style.display = 'none';
    //                 } else {
    //                     let data = "";
    //                     // console.log(results);
    //                     results.forEach(function(e, i) {
    //                         data += `
    // 								<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
    // 										<td class="col-xs-1">${i + 1}.</td>
    // 										<td class="col-xs-11">${e.kode}</td>
    // 								</tr>
    // 								`;
    //                     })

    //                     document.getElementById('konten-table').innerHTML = data;
    //                     // console.log(data);
    //                     document.getElementById('table-fixed').style.display = 'block';
    //                 }
    //             });
    //     } else {
    //         document.getElementById('table-fixed').style.display = 'none';
    //     }
    // });

    function getNoSuratJalanEks(data) {
        $("#kode_barcode").val(data);
        document.getElementById('table-fixed').style.display = 'none'
        $("#check_kode").click()
    }

    $('#release_pallet').on('click', function() {
        var pallet_id = $(this).attr('data-pallet-id');

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/Maintenance/NonAktifPallet/releasePallet'); ?>",
            data: {
                pallet_id: pallet_id
            },
            dataType: "JSON",
            success: function(response) {
                if (response != 0) {
                    message_topright('Success', 'success', 'Berhasil Update');
                    setTimeout(() => {
                        location.href =
                            "<?= base_url("WMS/Maintenance/NonAktifPallet/NonAktifPalletMenu") ?>";
                    }, 2000);
                } else {
                    message_topright('Gagal', 'error', 'Gagal Update');
                }
            }
        })
    })

    $("#deletePalletDetail").on('click', function() {
        var selectedValues = $(".chk-sku:checked").map(function() {
            return $(this).val();
        }).get();

        var pallet_kode = $("#txtpreviewscan").val();

        confirmAlert('Apakah anda yakin ingin menghapus?', 'Tekan ya untuk hapus dan tidak untuk kembali',
            'warning', 'Ya, hapus', 'Tidak').then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/Maintenance/NonAktifPallet/deletePalletDetail') ?>",
                    data: {
                        pallet_kode: pallet_kode,
                        pallet_detail_id: selectedValues
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == 'success') {
                            message_topright(response.status, response
                                .message);
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else if (response.status == 'warning') {
                            message_topright(response.status, response.message);
                        } else {
                            message_topright(response.status, response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ", " + error);
                    }
                })
            }
        })
    })
</script>