<script type="text/javascript">
	const html5QrCode = new Html5Qrcode("preview");
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
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

	$(document).on("click", "#search_filter_data", function() {
		let no_mutasi = $("#no_draft_mutasi").val();
		// let tgl = $("#tgl").val();
		let gudang_tujuan = $("#gudang_tujuan").val();
		let status = $("#status").val();
		$("#loadingsearch").show();
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/get_data_mutasi_pallet_draft_by_kode') ?>",
			type: "POST",
			data: {
				no_mutasi: no_mutasi,
				gudang_tujuan: gudang_tujuan,
				status: status,
			},
			dataType: "JSON",
			success: function(data) {
				$("#loadingsearch").hide();
				$("#list-data-form-search").show();


				$('#list-data-form-search').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					initDataToTable(data)
				});

			}
		});
	});

	function initDataToTable(response) {
		if (response != null) {
			if ($.fn.DataTable.isDataTable('#listdata')) {
				$('#listdata').DataTable().destroy();
			}
			$('#listdata > tbody').empty();
			$.each(response, function(i, v) {
				let str = "";
				if (v.status == "In Progress") {
					str += "<button data-href='<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/konfirmasi/') ?>" + v.id + "' class='btn btn-warning form-control btnkonfirmasi' title='Konfirmasi distribusi penerimaan barang'><i class='fa fa-check'></i></button>";
				} else {
					str += "<button type='button' class='btn btn-primary btnviewdetail' data-href='<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/view/') ?>" + v.id + "' title='detail data'><i class='fas fa-eye'> </i>";
				}
				$("#listdata > tbody").append(`
                <tr class="text-center">
                    <td><input type="checkbox" value="${v.id}" id="cb_print_${i + 1}" class="form-control cbprint"></td>
                    <td>${i + 1}</td>
                    <td>${v.kode}</td>
                    <td>${v.tgl}</td>
                    <td>${v.pb_kode}</td>
                    <td>${v.depo_asal}</td>
                    <td>${v.depo_tujuan}</td>
                    <td>${v.status}</td>
                    <td>${str}</td>
                </tr>
            `);
			});
		} else {
			$("#listdata > tbody").html(`<tr><td colspan="8" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
		}

		$('#listdata').DataTable({
			columnDefs: [{
				sortable: false,
				targets: [0, 1, 2, 3, 4, 5, 6, 7]
			}],
		});
	}
	$(document).on("click", "#print", function() {
		let arr_chk = [];
		var checkboxes = $(".cbprint");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				arr_chk.push(checkboxes[i].value);
			}
		}
		if (arr_chk.length == 0) {
			message("Kosong!", "Mohon Pilih Data", "error");
			return false;
		}

		// return false;
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/setprint'); ?>",
			type: "POST",
			data: {
				id: arr_chk
			},
			dataType: "JSON",
			success: function(data) {
				if (data == 1) {
					window.open(
						'<?= base_url("WMS/KonfirmasiDistribusiPenerimaan/print_cetak") ?>'
					);

				}
			}
		})
	});

	$(document).on("click", ".btnkonfirmasi", function() {
		let href = $(this).attr("data-href");
		location.href = href;
	});

	$(document).on("click", ".btnviewdetail", function() {
		let href = $(this).attr("data-href");
		location.href = href;
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
		}
	});

	$(document).on("click", "#start_scan", function() {
		$("#start_scan").hide();
		$("#stop_scan").show();
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat Kamera</span>',
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
				$("#preview").show();

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
						url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/checkKodePallet'); ?>",
						type: "POST",
						data: {
							kode_pallet: decodedText
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

								$('#list-data-form-search').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									initDataToTable(data.data)
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
				});
			}
		});
	});

	$(document).on("click", "#input_manual", function() {
		$("#input_manual").hide();
		$("#close_input").show();
		$("#preview_input_manual").show();
	});

	$(document).on("click", "#check_kode", () => {
		let barcode = $("#kode_barcode").val();

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			$.ajax({
				url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/checkKodePallet'); ?>",
				type: "POST",
				data: {
					kode_pallet: barcode
				},
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode").val("");
						$('#list-data-form-search').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataToTable(data.data)
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
		}
	})

	$(document).on("click", "#stop_scan", function() {
		remove_stop_scan();
	});

	$(document).on("click", "#close_input", function() {
		remove_close_input();
	});

	function remove_stop_scan() {
		$("#start_scan").show();
		$("#stop_scan").hide();
		$("#preview").hide();
		$("#txtpreviewscan").val("");
		$("#select_kamera").empty();
		html5QrCode.stop();
	}

	function remove_close_input() {
		$("#input_manual").show();
		$("#close_input").hide();
		$("#preview_input_manual").hide();
		$("#kode_barcode").val("");
		$("#txtpreviewscan").val("");
	}

	const handleRemoveData = () => {
		$('#listdata > tbody').empty();
		$('#list-data-form-search').hide()
	}

	document.getElementById('kode_barcode').addEventListener('keyup', function() {
		const typeScan = $("#tempValForScan").val();
		if (this.value != "") {
			fetch('<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/getKodeAutoComplete?params='); ?>' + this.value + `&type=notone`)
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
		$("#kode_barcode").val(data);
		document.getElementById('table-fixed').style.display = 'none'
		$("#check_kode").click()
	}
</script>