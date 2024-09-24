<script>
	let global_id = $("#global_id").val();
	const html5QrCode = new Html5Qrcode("preview");
	const html5QrCode2 = new Html5Qrcode("preview_by_one");
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		get_data();
	});

	function message(msg, msgtext, msgtype) {
		Swal.fire(msg, msgtext, msgtype);
	}

	function message_topright(type, msg) {
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			didOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer);
				toast.addEventListener("mouseleave", Swal.resumeTimer);
			},
		});

		Toast.fire({
			icon: type,
			title: msg,
		});
	}

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
			success: function(response) {
				const header = response.header;
				const detail = response.detail;
				$("#no_draft_mutasi").html("<option value='" + header.id + "' selected>" + header.kode + "</option>");
				$("#no_penerimaan").val(header.pb_kode);
				$("#principle").val(header.principle);
				$("#no_sj").val(header.no_sj);
				$("#gudang_asal").html("<option value='" + header.depo_asal_id + "' selected>" + header.depo_asal + "</option>");
				$("#gudang_tujuan").html("<option value='" + header.depo_tujuan_id + "' selected>" + header.depo_tujuan + "</option>");
				$("#checker").val(header.checker);
				$("#tipe_transaksi").val("Mutasi Antar Gudang");
				$("#status").val("In Progress");

				append_detail_konfirmasi(detail);
			}
		});
	}

	function append_detail_konfirmasi(response) {
		$("#list_data_konfirmasi > tbody").empty();
		let no = 1;
		$.each(response, function(i, v) {
			let rak = "";
			let status = "";

			if (v.rak_jalur == null) {
				rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" disabled placeholder="Lokasi Bin Tujuan"/>`;
			} else {
				rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" value="` + v.rak_nama + `" disabled/>`;
			}

			if (v.status == null) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
			} else if (v.status == 0) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Tidak Valid</span>";
			} else {
				status += "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
			}
			$("#list_data_konfirmasi > tbody").append(`
                <tr>
                    <td class="text-center">${no++} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + v.id + `"/></td>
                    <td class="text-center">${v.kode}</td>
                    <td class="text-center">${v.jenis}</td>
                    <td class="text-center">${status}</td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">${rak}</div>
                            <div class="col-md-6" style="text-align: end">
                                <button type="button" class="btn btn-info start_scan_by_one" data-id="${v.pallet_id}"><i class="fas fa-qrcode"></i> Scan</button>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" data-id="` + v.id + `" title="detail pallet" class="btn btn-primary detail_pallet"><i class="fas fa-eye"></i> Detail</button>
                    </td>
                </tr>
            `);
		});

		$('#list_data_konfirmasi').DataTable();
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
					$("#table_list_detail > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
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

	$(document).on("click", "#start_scan", function() {
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
			$("#input_manual").attr("disabled", false);
		});
	});

	$(document).on("click", "#input_manual", function() {
		$("#input_manual").hide();
		$("#close_input").show();
		$("#preview_input_manual").show();
		$("#start_scan").attr("disabled", true);
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
		let barcode = $("#kode_barcode_notone").val();
		let attachment = $("#myFileInput")

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else if (attachment.val() == "") {
			message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
			return false;
		} else {
			let new_form = new FormData();
			let files = attachment[0].files[0];
			new_form.append('id', global_id);
			new_form.append('kode_pallet', barcode);
			new_form.append('file', files);

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
		$("#input_manual").attr("disabled", false);
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
		$("#start_scan").attr("disabled", false);
	}

	$(document).on("click", ".start_scan_by_one", function() {
		const pallet_id = $(this).attr('data-id');
		const gudang_tujuan = $("#gudang_tujuan").val();
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
	});

	$(document).on("click", ".stop_scan_by_one", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
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
		const pallet_id = type == 'notone' ? null : event.currentTarget.getAttribute('data-id');
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
			$("#check_kode").click()
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