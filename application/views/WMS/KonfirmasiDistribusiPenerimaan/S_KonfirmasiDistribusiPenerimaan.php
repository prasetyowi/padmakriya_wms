<script>
	let global_id = $("#global_id").val();
	loadingBeforeReadyPage()
	const html5QrCode = new Html5Qrcode("preview");
	const html5QrCode2 = new Html5Qrcode("preview_by_one");

	$(document).ready(function() {
		select2();
		get_data();
		check_exist_data();
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

	function get_data() {
		//ajax get data header
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/get_data_header_konfirmasi') ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				const header = response.header;
				const detail = response.detail;
				$("#no_draft_mutasi").html("<option value='" + header.id + "' selected>" + header.kode + "</option>");
				$("#no_penerimaan").val(header.pb_kode);
				$("#principle").val(header.principle);
				$("#client_wms").val(header.client_wms);
				// $("#no_sj").val(header.no_sj);
				$("#gudang_asal").html("<option value='" + header.depo_asal_id + "' selected>" + header.depo_asal + "</option>");
				$("#gudang_tujuan").html("<option value='" + header.depo_tujuan_id + "' selected>" + header.depo_tujuan + "</option>");
				$("#checker").val(header.checker);
				$("#tipe_transaksi").html("<option value='" + header.tipe_id + "' selected>" + header.tipe + "</option>");
				$("#status").val(header.status);
				$("#tr_mutasi_draft_id").val(header.id);
				$("#distribusi_id").val(header.distribusi_id);
				$("#lastUpdated").val(header.tr_mutasi_pallet_draft_tgl_update);

				append_detail_konfirmasi(detail);
			}
		});
	}

	function append_detail_konfirmasi(response) {

		if ($.fn.DataTable.isDataTable('#list_data_konfirmasi')) {
			$('#list_data_konfirmasi').DataTable().destroy();
		}

		$("#list_data_konfirmasi > tbody").empty();
		let no = 1;
		$.each(response, function(i, v) {
			let rak = "";
			let status = "";
			let checked1 = ''
			let checked2 = ''
			if (v.pallet_id_tujuan != null) {
				rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" style="width:100%" disabled value="` + v.pallet_kode_tujuan + `"/>`;
				checked1 = 'checked';
				checked2 = '';
				updateTableHeaderText(1)

			}
			if (v.rak_jalur != null) {
				rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" style="width:100%" value="` + v.rak_nama + `" disabled/>`;
				checked1 = '';
				checked2 = 'checked';
				updateTableHeaderText(2)
			}

			if (v.status == null) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px' name='CAPTION-BELUMDIVALIDASI'>Belum Divalidasi</span>";
			} else if (v.status == 0) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px' name='CAPTION-TIDAKVALID'>Tidak Valid</span>";
			} else {
				status += "<span class='btn btn-success' style='cursor: context-menu;padding:0px' name='CAPTION-VALID'>Valid </span>";
			}
			$("#list_data_konfirmasi > tbody").append(`
                <tr>
                    <td class="text-center">${no++} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + v.id + `"/></td>
                    <td class="text-center">${v.kode}</td>
                    <td class="text-center">${status}</td>
                    <td class="text-center">
						<label>
							<input type="radio" name="tipepilih_${v.pallet_id}" ${checked1} class="radioku" onchange="updateTableHeaderText(this.value)" id="tipepilih_${v.pallet_id}" value="1"> Pallet
						</label>
					</td>
					<td class="text-center">
						<label>
							<input type="radio" name="tipepilih_${v.pallet_id}" ${checked2} class="radioku" onchange="updateTableHeaderText(this.value)" id="tipepilih_${v.pallet_id}" value="2"> Rak
						</label>
					</td>
					
                    <td>
                        <div class="row">
                            <div class="col-md-4">${rak}</div>
                            <div class="col-md-8"">
                                <div class="head-switch">
                                    <div class="switch-holder">
                                        <div class="switch-toggle">
                                            <input type="checkbox" id="check_scan_${i}" class="check_scan">
                                            <label for="check_scan_${i}"></label>
                                        </div>
                                    
                                        <div class="switch-label">
                                            <button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-id="${v.pallet_id}"> <i class="fas fa-qrcode"></i> Scan</button>
                                            <button type="button" class="btn btn-warning input_rak" name="input_rak" data-id="${v.pallet_id}"  data-trdid="${v.id}" style="display:none"> <i class="fas fa-keyboard"></i> Input</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" data-id="` + v.id + `" title="detail pallet" class="btn btn-primary detail_pallet"><i class="fas fa-eye"></i> Detail</button>
                    </td>
                </tr>
            `);
		});

		// $('#list_data_konfirmasi').DataTable({
		// 	// columnDefs: [{
		// 	// 	sortable: false,
		// 	// 	targets: [0, 1, 2, 3, 4, 5, 6]
		// 	// }],
		// });

		$("#list_data_konfirmasi > tbody tr").each(function(i, v) {
			let check = $(this).find("td:eq(5) input[type='checkbox']");
			let scan = $(this).find("td:eq(5) button[name='start_scan_by_one']");
			let input = $(this).find("td:eq(5) button[name='input_rak']");
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

	function updateTableHeaderText(value) {
		console.log(value);
		var selectedValue = $('input[name="tipepilih"]:checked').val();
		var tableHeaderText = $('#lokasiBinTujuanLabel');
		if (value == '1') {
			tableHeaderText.text('Lokasi Pallet');
		} else if (value == '2') {
			tableHeaderText.text('Lokasi Rak');
		}
	}

	$(document).on("click", ".detail_pallet", function() {
		let id = $(this).attr('data-id');
		$("#modal_view_detail").modal('show');
		//ajax request detail
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/get_data_detail_pallet'); ?>",
			type: "POST",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#table_list_detail')) {
						$('#table_list_detail').DataTable().destroy();
					}
					$('#table_list_detail > tbody').empty();
					let no = 1;
					$.each(data, function(i, v) {
						$("#table_list_detail > tbody").append(`
                            <tr>
                                <td class="text-center">${no++}</td>
                                <td class="text-center">${v.principle}</td>
                                <td class="text-center">${v.sku_kode}</td>
                                <td>${v.sku_nama}</td>
                                <td class="text-center">${v.sku_kemasan}</td>
                                <td class="text-center">${v.sku_satuan}</td>
                                <td class="text-center">${v.ed}</td>
                                <td class="text-center">${v.tipe}</td>
                                <td class="text-center">${v.qty}</td>
                            </tr>
                        `);
					});
				} else {
					$("#table_list_detail > tbody").html(`<tr><td colspan="9" class="text-center text-danger" name='CAPTION-DATAKOSONG'>Data Kosong</td></tr>`);
				}

				$('#table_list_detail').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
			},
		});
	});

	$(document).on("click", ".btn_close_detail", function() {
		$("#modal_view_detail").modal('hide');
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
	$(document).on("change", "#check_scan_pallet", function(e) {
		if (e.target.checked) {
			$("#start_scan_pallet").hide();
			$("#input_manual_pallet").show();
			$("#stop_scan_pallet").hide();
			$("#preview_pallet").hide();
			$("#txtpreviewscan_pallet").val("");
			$("#select_kamera_pallet").empty();
		} else {
			$("#start_scan_pallet").show();
			$("#input_manual_pallet").hide();
			$("#close_input_pallet").hide();
			$("#preview_input_manual_pallet").hide();
			$("#kode_barcode_notone_pallet").val("");
			$("#txtpreviewscan_pallet").val("");
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
						url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_kode_pallet_by_no_mutasi'); ?>",
						type: "POST",
						data: {
							id: global_id,
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
								$('#list_data_konfirmasi').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									get_data();
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
	$(document).on("click", "#input_manual_pallet", function() {
		$("#input_manual_pallet").hide();
		$("#close_input_pallet").show();
		$("#preview_input_manual_pallet").show();
	});

	function previewFile() {
		const file = document.querySelector('input[id=myFileInput]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		reader.addEventListener("load", function() {
			$('#show-file').append(`
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

	$(document).on("click", "#check_kode", () => {
		const type = 'notone'
		let value = $('#kode_barcode_notone').val()
		if (value != "") {
			fetch('<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/getKodeAutoComplete?params='); ?>' + value + `&type=notone`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}','${type}','null')" style="cursor:pointer">
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
		return false;

	})
	$(document).on("click", "#check_kode_pallet", () => {
		const type = 'notone-pallet'
		let value = $('#kode_barcode_notone_pallet').val()
		if (value != "") {
			fetch('<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/getKodeAutoComplete?params='); ?>' + value + `&type=notone`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}','notone_pallet','null')" style="cursor:pointer">
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
		return false;

	})
	const pilihpallet = () => {
		let barcode = $("#kode_barcode_notone").val();
		// let attachment = $("#myFileInput")

		//  else if (attachment.val() == "") {
		// 	message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
		// 	return false;
		// } 

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			let new_form = new FormData();
			// let files = attachment[0].files[0];
			new_form.append('id', global_id);
			new_form.append('kode_pallet', barcode);
			// new_form.append('file', files);

			$.ajax({
				url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_kode_pallet_by_no_mutasi'); ?>",
				type: "POST",
				data: new_form,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode_notone").val("");
						$('#myFileInput').val("");
						$('#show-file').empty();
						$('#list_data_konfirmasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							get_data();
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
	}
	const pilihpalletTujuan = () => {
		let barcode = $("#kode_barcode_notone_pallet").val();
		// let attachment = $("#myFileInput")

		//  else if (attachment.val() == "") {
		// 	message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
		// 	return false;
		// } 

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			let new_form = new FormData();
			// let files = attachment[0].files[0];
			new_form.append('id', global_id);
			new_form.append('kode_pallet', barcode);
			new_form.append("tr_mutasi_pallet_detail_draft_id", $("#kode_barcode_notone_pallet").attr("data-trdid"));
			new_form.append('rak_lajur', $('#gudang_tujuan option:selected').val());
			// new_form.append('file', files);

			$.ajax({
				url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_kode_pallet_tujuan_by_no_mutasi'); ?>",
				type: "POST",
				data: new_form,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode_notone_pallet").val("");
						$('#myFileInput').val("");
						$('#show-file').empty();
						$('#manual_input_pallet').modal('hide')
						$('#list_data_konfirmasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							get_data();
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
	}

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
		$("#kode_barcode_notone").val("");
		$("#txtpreviewscan").val("");
		$('#myFileInput').val("");
		$('#show-file').empty();
	}

	function check_exist_data() {
		//ajax to check dratf_id exist or not in tr_mutasi_pallet
		let mutasi_draft_id = $("#tr_mutasi_draft_id").val();
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_exist_in_tr_mutasi_pallet') ?>",
			type: "POST",
			data: {
				id: mutasi_draft_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response == null) {
					$("#konfirmasi_distribusi").attr("disabled", true);
				} else {
					$("#konfirmasi_distribusi").attr("disabled", false);
				}
			}
		});
	}

	$(document).on("click", ".start_scan_by_one", function() {
		const pallet_id = $(this).attr('data-id');
		const gudang_tujuan = $("#gudang_tujuan").val();
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
				$("#modal_scan").modal('show');

				//handle succes scan
				const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {
						html5QrCode2.pause();
						scan2(decodedText);
					}
				};

				const scan2 = (decodedText) => {
					$.ajax({
						url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_rak_lajur_detail'); ?>",
						type: "POST",
						data: {
							id: global_id,
							pallet_id: pallet_id,
							gudang_tujuan: gudang_tujuan,
							kode: decodedText
						},
						dataType: "JSON",
						success: function(data) {
							$("#txtpreviewscan2").val(data.kode);
							if (data.type == 200) {
								Swal.fire("Success!", data.message, "success").then(function(result) {
									if (result.value == true) {
										html5QrCode2.stop();
										$("#modal_scan").modal('hide');
									}
								});
								$('#list_data_konfirmasi').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									get_data();
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
								$('#list_data_konfirmasi').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									get_data();
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
			}
		});

	});

	$(document).on("click", ".stop_scan_by_one", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
	});

	$(document).on("click", ".input_rak", function() {
		$("#kode_barcode_one").attr("data-id", $(this).attr('data-id'));
		let pallet_id = $(this).attr('data-id')
		// let tipepilih = $(`input[name='tipepilih_${pallet_id}']`);
		let tipepilih = $(`input[name='tipepilih_${pallet_id}']`);
		// console.log(tipepilih);

		// if (!tipepilih.is(':checked')) {
		// 	message('info', 'Pilih tipe palet/rak', 'info');
		// 	return false;
		// }
		// if (tipepilih.val() == 1) {
		// 	$("#manual_input_pallet").modal('show');
		// } else {
		// 	$("#manual_input_rak").modal('show');
		// }
		let checkedValue = tipepilih.filter(':checked').val();
		if (!checkedValue) {
			message('info', 'Pilih tipe palet/rak', 'info');
			return false;
		}
		// Now you can use the checkedValue in your logic
		if (checkedValue == 1) {
			$("#kode_barcode_notone_pallet").attr("data-id", $(this).attr('data-id'));
			$("#kode_barcode_notone_pallet").attr("data-trdid", $(this).attr('data-trdid'));
			$("#manual_input_pallet").modal('show');
		} else {
			$("#manual_input_rak").modal('show');
		}
	});

	// $(document).on("change", "#nama_rak", function() {
	// 	let rak_val = $(this);
	// 	const pallet_id = $(this).attr('data-id');
	// 	const gudang_tujuan = $("#gudang_tujuan").val();
	// 	$.ajax({
	// 		url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_rak_lajur_detail'); ?>",
	// 		type: "POST",
	// 		data: {
	// 			id: global_id,
	// 			pallet_id: pallet_id,
	// 			gudang_tujuan: gudang_tujuan,
	// 			kode: rak_val.val()
	// 		},
	// 		dataType: "JSON",
	// 		success: function(data) {
	// 			$("#txtpreviewscan2").val(data.kode);
	// 			if (data.type == 200) {
	// 				Swal.fire("Success!", data.message, "success").then(function(result) {
	// 					if (result.value == true) {
	// 						$("#manual_input_rak").modal('hide');
	// 						rak_val.val("");
	// 					}
	// 				});
	// 				$('#list_data_konfirmasi').fadeOut("slow", function() {
	// 					$(this).hide();
	// 				}).fadeIn("slow", function() {
	// 					$(this).show();
	// 					get_data();
	// 				});
	// 			} else if (data.type == 201) {
	// 				message("Error!", data.message, "error");
	// 			} else {
	// 				message("Info!", data.message, "info");
	// 			}
	// 		},
	// 	});
	// });

	$(document).on("click", ".tutup_input_manual", function() {
		$("#manual_input_rak").modal('hide');
		$("#kode_barcode_one").val("");
	});

	$(document).on("click", "#simpan_distribusi", () => {
		let mutasi_draft_id = $("#tr_mutasi_draft_id").val();
		let principle = $("#principle").val();
		let client_wms = $("#client_wms").val();
		let tgl = $("#tgl").val();
		let tipe_transaksi = $("#tipe_transaksi").val();
		let keterangan = $("#keterangan").val();
		let status = $("#status").val();
		let gudang_detail_asal = $("#gudang_asal").val();
		let gudang_detail_tujuan = $("#gudang_tujuan").val();
		let checker = $("#checker").val();
		const lastUpdated = $("#lastUpdated").val();

		let error = false;

		$("#list_data_konfirmasi > tbody tr").each(function() {
			let is_valid = $(this).find("td:eq(2)").text().replace(/\s+/g, '');
			let rak = $(this).find("td:eq(5) input[type='text']").val();
			if (is_valid != "Valid") {
				message("Error!", "Pallet masih ada yang belum tervalidasi, silahkan cek terlebih dahulu!", "error");
				error = true;
				return false;
			} else if (rak == "") {
				message("Error!", "Lokasi Bin Tujuan tidak boleh kosong!", "error");
				error = true;
				return false;
			} else {
				error = false;
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
				// 			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/save_data'); ?>",
				// 			type: "POST",
				// 			data: {
				// 				mutasi_draft_id: mutasi_draft_id,
				// 				principle: principle,
				// 				client_wms,
				// 				tgl: tgl,
				// 				tipe_transaksi: tipe_transaksi,
				// 				status: status,
				// 				keterangan: keterangan,
				// 				gudang_detail_asal: gudang_detail_asal,
				// 				gudang_detail_tujuan: gudang_detail_tujuan,
				// 				checker: checker
				// 			},
				// 			dataType: "JSON",
				// 			success: function(data) {
				// 				if (data == 1) {
				// 					message_topright("success", "Data berhasil disimpan");
				// 					setTimeout(() => {
				// 						location.reload();
				// 					}, 500);
				// 				} else {
				// 					message_topright("error", "Data gagal disimpan");
				// 				}
				// 			}
				// 		});
				// 	}
				// });

			}

			if (!error) {
				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Ya, Simpan', 'Tidak, Tutup').then((result) => {
					if (result.value == true) {
						requestAjax("<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/save_data'); ?>", {
							mutasi_draft_id: mutasi_draft_id,
							principle: principle,
							client_wms,
							tgl: tgl,
							tipe_transaksi: tipe_transaksi,
							status: status,
							keterangan: keterangan,
							gudang_detail_asal: gudang_detail_asal,
							gudang_detail_tujuan: gudang_detail_tujuan,
							checker: checker,
							lastUpdated
						}, "POST", "JSON", function(response) {

							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								message_topright("success", response.message);
								message_topright("success", response.message);
								setTimeout(() => {
									location.reload();
								}, 500);
							}
							if (response.status === 400) return messageNotSameLastUpdated()
							if (response.status === 401) return message_topright('error', response.message)
						})
					}
				});
			}
		});
	});

	$(document).on("click", "#konfirmasi_distribusi", () => {
		const distribusi_id = $("#distribusi_id").val();
		const tipe_transaksi = $("#tipe_transaksi").val();
		const gudang_detail_asal = $("#gudang_asal").val();
		const gudang_detail_tujuan = $("#gudang_tujuan").val();
		const lastUpdated = $("#lastUpdated").val();

		messageBoxBeforeRequest('Ingin konfirmasi data ini!', 'Ya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/confirm_data'); ?>", {
					mutasi_draft_id: global_id,
					distribusi_id: distribusi_id,
					tipe_transaksi: tipe_transaksi,
					gudang_detail_asal: gudang_detail_asal,
					gudang_detail_tujuan: gudang_detail_tujuan,
					lastUpdated
				}, "POST", "JSON", function(response) {
					if (response.status === 200) {
						message_topright("success", "Data berhasil dikonfirmasi");
						setTimeout(() => {
							location.href = "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/KonfirmasiDistribusiPenerimaanMenu') ?>";
						}, 500);
					}
					if (response.status === 400) return messageNotSameLastUpdated()
					if (response.status === 401) return message_topright('error', response.message)
				})
			}
		});

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Ingin konfirmasi data ini!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Konfirmasi",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		//ajax save data
		// 		$.ajax({
		// 			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/confirm_data'); ?>",
		// 			type: "POST",
		// 			data: {
		// 				mutasi_draft_id: global_id,
		// 				distribusi_id: distribusi_id,
		// 				tipe_transaksi: tipe_transaksi,
		// 				gudang_detail_asal: gudang_detail_asal,
		// 				gudang_detail_tujuan: gudang_detail_tujuan,
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				// console.log(data);
		// 				if (data == 1) {
		// 					message_topright("success", "Data berhasil dikonfirmasi");
		// 					setTimeout(() => {
		// 						location.href = "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/KonfirmasiDistribusiPenerimaanMenu') ?>";
		// 					}, 500);
		// 				} else {
		// 					message_topright("error", "Data gagal dikonfirmasi");
		// 				}
		// 			}
		// 		});
		// 	}
		// });
	});

	$(document).on("click", "#kembali_distribusi", () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/KonfirmasiDistribusiPenerimaanMenu') ?>";
		}, 500);
	});

	const handlerScanInputManual = (event, value, type) => {
		var value = $('#kode_barcode_one').val()
		const pallet_id = type == 'notone' ? null : $('#kode_barcode_one').attr('data-id');

		if (value != "") {
			fetch('<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/getKodeAutoComplete?params='); ?>' + value + `&type=${type}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}', '${type}', '${pallet_id}')" style="cursor:pointer">
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


	function getNoSuratJalanEks(data, type, pallet_id) {
		$(`#kode_barcode_${type}`).val(data);
		$(`#table-fixed-${type}`).css('display', 'none')
		if (type == 'notone') {
			// $("#check_kode").click()
			pilihpallet()
		}
		if (type == 'notone-pallet' || type == 'notone_pallet') {
			// $("#check_kode").click()
			pilihpalletTujuan()
		}
		if (type == 'one') {
			const gudang_tujuan = $("#gudang_tujuan").val();
			$.ajax({
				url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/check_rak_lajur_detail'); ?>",
				type: "POST",
				data: {
					id: global_id,
					pallet_id: pallet_id,
					gudang_tujuan: gudang_tujuan,
					kode: data
				},
				dataType: "JSON",
				success: function(data) {
					$("#txtpreviewscan2").val(data.kode);
					if (data.type == 200) {
						Swal.fire("Success!", data.message, "success").then(function(result) {
							if (result.value == true) {
								$("#manual_input_rak").modal('hide');
								rak_val.val("");
							}
						});
						$('#list_data_konfirmasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							get_data();
						});
					} else if (data.type == 201) {
						message("Error!", data.message, "error");
					} else {
						message("Info!", data.message, "info");
					}
				},
			});
		}
	}
</script>