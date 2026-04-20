<script type="text/javascript">
	var ChannelCode = '';
	var arr_penerimaan_tipe = [];
	var arr_list_penerimaan_tipe = [];
	let arrDataSkuBygroup = [];
	let dataDetailPallet = [];

	const urlSearchParams = new URLSearchParams(window.location.search);
	const dataSKUGroup = Object.fromEntries(urlSearchParams.entries()).data;
	const deliveryOrderId = Object.fromEntries(urlSearchParams.entries()).doId;

	<?php if ($act == "ProsesBTB") { ?>
		const html5QrCode = new Html5Qrcode("preview");
		const html5QrCode2 = new Html5Qrcode("preview_by_one");
		let timerInterval
	<?php } ?>

	$(document).ready(
		function() {
			var delivery_order_batch_id = $("#konfirmasipengirimancanvas-delivery_order_batch_id").val();
			var delivery_order_id = $("#konfirmasipengirimancanvas-delivery_order_id").val();

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
			$('.select2').select2();

			if ($("#konfirmasipengirimancanvas-depo_detail_id").val() != "") {
				GetRakLajurDetail();
			}

			$(document).on("input", ".numeric", function(event) {
				this.value = this.value.replace(/[^\d.]+/g, '');
			});

			$.ajax({
				async: false,
				url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/getDataSKuByGroup'); ?>",
				type: "POST",
				data: {
					data: dataSKUGroup.split(','),
					deliveryOrderId
				},
				dataType: "JSON",
				success: function(response) {
					arrDataSkuBygroup = [];
					if (response.length > 0) {
						response.map((value) => arrDataSkuBygroup.push({
							...value,
							qty_terima: null
						}))
					}
				},
			});
		}
	);

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

	const handlerUpdatedQtyTerima = (event, sku_id) => {
		const findIndexSKUGroup = arrDataSkuBygroup.findIndex((value) => value.sku_id === sku_id);
		arrDataSkuBygroup[findIndexSKUGroup]['qty_terima'] = event.currentTarget.value;
	}

	$(document).on('click', '.btnRemoveListDO', function() {
		const sku_id = $(this).attr('data-id');

		$(this).closest('tr').remove();

		const findIndexSKUGroup = arrDataSkuBygroup.findIndex((value) => value.sku_id === sku_id);
		arrDataSkuBygroup.splice(findIndexSKUGroup, 1)

		dataDetailPallet.map((value) => {
			const filterDataSub = value.data.filter((item) => item.sku_id !== sku_id);
			value.data = [];
			filterDataSub.map((val) => value.data.push({
				...val
			}))

		})

		$("#viewdetailpallet").hide();

		$("#tabledoretur > tbody tr").each(function(i, v) {
			const counter = $(this).find("td:eq(0)")
			counter.html(i + 1)
		})
	})

	// const handlerRemoveRowDetail = (event, sku_id) => {
	// 	event.target.parentElement.parentElement.parentElement.remove();

	// 	const findIndexSKUGroup = arrDataSkuBygroup.findIndex((value) => value.sku_id === sku_id);
	// 	arrDataSkuBygroup.splice(findIndexSKUGroup, 1)

	// 	dataDetailPallet.map((value) => {
	// 		const filterDataSub = value.data.filter((item) => item.sku_id !== sku_id);
	// 		value.data = [];
	// 		filterDataSub.map((val) => value.data.push({
	// 			...val
	// 		}))

	// 	})

	// 	$("#viewdetailpallet").hide();

	// 	$("#tabledoretur > tbody tr").each(function(i, v) {
	// 		const counter = $(this).find("td:eq(0)")
	// 		counter.html(i + 1)
	// 	})
	// }

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

			if ($("#konfirmasipengirimancanvas-depo_detail_id").val() != "") {
				$.ajax({
					url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/check_kode_pallet'); ?>",
					type: "POST",
					data: {
						id: idd,
						kode_pallet: decodedText,
						depo_detail_id: $("#konfirmasipengirimancanvas-depo_detail_id").val()
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
				message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
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
		let idd = $("#konfirmasipengirimancanvas-delivery_order_batch_id").val()
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
					url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/check_rak_lajur_detail'); ?>",
					type: "POST",
					data: {
						depo_detail_id: $("#konfirmasipengirimancanvas-depo_detail_id").val(),
						pallet_id: $("#item-" + index + "-konfirmasipengirimancanvasdetail2-pallet_id").val(),
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

			if ($("#konfirmasipengirimancanvas-depo_detail_id").val() != "") {
				$.ajax({
					url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/check_kode_pallet'); ?>",
					type: "POST",
					data: {
						kode_pallet: barcode,
						depo_detail_id: $("#konfirmasipengirimancanvas-depo_detail_id").val()
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
								url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/InsertPalletTemp2') ?>",
								data: {
									delivery_order_batch_id: $("#konfirmasipengirimancanvas-delivery_order_batch_id").val(),
									pallet_id: data.pallet_id,
									depo_detail_id: $("#konfirmasipengirimancanvas-depo_detail_id").val()
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
				message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
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
		var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();

		$("#pallet-rak_lajur_detail_id_" + idx).html('');
		$.ajax({
			async: false,
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/check_rak_lajur_detail'); ?>",
			type: "POST",
			data: {
				depo_detail_id: depo_detail_id,
				pallet_id: $("#item-" + idx + "-konfirmasipengirimancanvasdetail2-pallet_id").val(),
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
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/GetCheckerByPrinciple') ?>",
			data: {
				perusahaan: $("#konfirmasipengirimancanvas-client_wms_id").val(),
				principle: principle
			},
			dataType: "JSON",
			success: function(response) {

				$("#konfirmasipengirimancanvas-karyawan_id").html('');

				$("#konfirmasipengirimancanvas-karyawan_id").append('<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');

				$.each(response, function(i, v) {
					$("#konfirmasipengirimancanvas-karyawan_id").append(`<option value="${v.karyawan_id}">${v.karyawan_nama}</option>`);
				});
			}
		});
	}

	$("#btnpenerimaanperpallet").click(
		function() {
			var delivery_order_batch_id = $("#konfirmasipengirimancanvas-delivery_order_batch_id").val();
			var rak_lajur_detail_id = $("#konfirmasipengirimancanvas-rak_lajur_detail_id").val();
			var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();

			$("#loadingview").show();

			if (depo_detail_id != "") {

				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/InsertPalletTemp') ?>",
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
				message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
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
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/get_pallet_by_arr_id') ?>",
			data: {
				delivery_order_batch_id: $("#konfirmasipengirimancanvas-delivery_order_batch_id").val()
				// pallet_id: arr_pallet_id
			},
			dataType: "JSON",
			success: function(response) {
				$('#tablepallet > tbody').empty();
				$.each(response, function(i, v) {
					$("#tablepallet > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-konfirmasipengirimancanvasdetail2-pallet_id" value="${v.pallet_id}"/>
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

				$('.select2').select2();
			}
		});
	}

	$("#btn-tambah-sku-fdjr").click(
		function() {
			const pallet_id = $('#konfirmasipengirimancanvasdetail-pallet_id').val();

			const findData = dataDetailPallet.find((value) => value.pallet_id === pallet_id);

			const dataSku = arrDataSkuBygroup.map((value) => {
				return {
					...value,
					expdate: null,
					qty: null,
					batch_no: null
				}
			})

			if (typeof findData === 'undefined') {
				dataDetailPallet.push({
					pallet_id,
					data: dataSku
				})
			} else {
				const findIndexParent = dataDetailPallet.findIndex((value) => value.pallet_id === pallet_id);

				const getArrayByIndex = dataDetailPallet[findIndexParent]['data'].map((items) => items)

				const dataFix = getArrayByIndex.concat(dataSku);

				dataDetailPallet[findIndexParent]['data'] = dataFix
			}

			initDataTablePalletDetail(pallet_id)

		}
	);

	function ViewPallet(pallet_id) {
		$("#viewdetailpallet").show();
		// $("#viewmodaldetailpallet").modal('show');
		$('#konfirmasipengirimancanvasdetail-pallet_id').val(pallet_id);

		initDataTablePalletDetail(pallet_id);
	}

	function ViewPallet2(pallet_id) {
		$("#viewdetailpallet").show();

		initDataTablePalletDetail2(pallet_id);
	}

	function initDataTablePallet() {
		var delivery_order_batch_id = $("#konfirmasipengirimancanvas-delivery_order_batch_id").val();

		$("#tablepallet tbody").html('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/GetPallet') ?>",
			data: {
				delivery_order_batch_id: delivery_order_batch_id
			},
			dataType: "JSON",
			success: function(response) {
				if (response > 0) {
					$.each(response.pallet, function(i, v) {
						$("#tablepallet > tbody").append(`
								<tr id="row-${i}">
									<td style="display: none">
										<input type="hidden" id="item-${i}-konfirmasipengirimancanvasdetail2-pallet_id" value="${v.pallet_id}"/>
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
		var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();

		$("#tablepalletdetail tbody").html('');



		if (depo_detail_id != "") {

			const findData = dataDetailPallet.find((value) => value.pallet_id === pallet_id)

			if (findData) {
				if (findData.data.length > 0) {
					findData.data.map((value, index) => {
						$("#tablepalletdetail > tbody").append(`
								<tr id="row-${index}">
									<td class="text-center">${value.principle}</td>
									<td class="text-center">${value.sku_kode}</td>
									<td class="text-center">${value.sku_nama_produk}</td>
									<td class="text-center">${value.sku_satuan}</td>
									<td class="text-center">${value.sku_kemasan}</td>
									<td class="text-center">
										<input type="date" class="form-control" id="slcskuexpireddate${index}" value="${value.expdate}" onchange="UpdateSkuExpDatePallet('${pallet_id}',this.value,'${index}')">
										<input type="hidden" id="slcskuexpireddate${index}" value="${value.expdate}">
									</td>
									<td class="text-center">
										<input type="text" style="width:100%;" class="form-control" id="jumlah_barang${index}" value="${value.qty == null ? '' : value.qty}" onchange="UpdateQtySKUPallet('${pallet_id}',this.value,'${index}')">
									</td>
									<td class="text-center">
										<input type="text" style="width:100%;" class="form-control" id="batch_no${index}" value="${value.batch_no == null ? '' : value.batch_no}" onchange="UpdateBatchNoPallet('${pallet_id}',this.value,'${index}')">
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail('${pallet_id}','${index}')"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							`);
					})
				}
			}

		} else {
			message("Error!", "<span name='CAPTION-ALERT-PILIHGUDANGPENERIMA'>Pilih Gudang Penerima!</span>", "error");
		}
	}

	function initDataTablePalletDetail2(pallet_id) {

		$("#tablepalletdetail tbody").html('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/GetPalletDetail2') ?>",
			data: {
				penerimaan_penjualan_id: $("#konfirmasipengirimancanvas-penerimaan_penjualan_id").val(),
				delivery_order_id: $("#konfirmasipengirimancanvas-delivery_order_id").val(),
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

	// function get_sku_exp_date(pallet_id, pallet_detail_id, sku_id, sku_stock_id, sku_stock_expired_date, index) {

	// 	var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();

	// 	$("#slcskuexpireddate" + index).html('');

	// 	$.ajax({
	// 		async: false,
	// 		type: 'POST',
	// 		url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/GetSKUExpiredDate') ?>",
	// 		data: "sku_id=" + sku_id,
	// 		dataType: "json",
	// 		success: function(response) {
	// 			$("#slcskuexpireddate" + index).append('<option value="">** Pilih SKU Exp **</option>');
	// 			$.each(response, function(i, v) {
	// 				if (sku_stock_id == v.sku_stock_id) {
	// 					$("#slcskuexpireddate" + index).append('<option value="' + v.sku_stock_expired_date + '" selected>' + v.sku_stock_expired_date + '</option>');
	// 					UpdateSkuExpDatePallet(pallet_detail_id, sku_id, sku_stock_expired_date, depo_detail_id);
	// 				} else {
	// 					$("#slcskuexpireddate" + index).append('<option value="' + v.sku_stock_expired_date + '">' + v.sku_stock_expired_date + '</option>');
	// 				}

	// 				// $("#slcskuexpireddate" + index).append('<option value="' + v.sku_stock_expired_date + '">' + v.sku_stock_expired_date + '</option>');
	// 			});
	// 		}
	// 	});
	// }

	function UpdateSkuExpDatePallet(pallet_id, dateValue, counter) {
		const findIndex = dataDetailPallet.findIndex((value) => value.pallet_id === pallet_id)
		dataDetailPallet[findIndex]['data'][counter]['expdate'] = dateValue;
		console.log(findIndex + ", " + counter + ", " + dateValue);
		console.log(dataDetailPallet);

	}

	function UpdateBatchNoPallet(pallet_id, batchValue, counter) {
		const findIndex = dataDetailPallet.findIndex((value) => value.pallet_id === pallet_id)
		dataDetailPallet[findIndex]['data'][counter]['batch_no'] = batchValue;
		console.log(dataDetailPallet);

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
					url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/DeletePalletTemp') ?>",
					data: {
						pallet_id: pallet_id
					},
					success: function(response) {
						if (response) {
							if (response == 1) {
								console.log('success');
								initDataPallet();

								const findIndex = dataDetailPallet.findIndex((value) => value.pallet_id === pallet_id)
								dataDetailPallet.splice(findIndex, 1)

								// initDataTablePalletDetail(pallet_id);
								// $("#viewdetailpalletdoretur").hide();
								$("#viewdetailpallet").hide();
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

	function DeletePalletDetail(pallet_id, counter) {

		const findData = dataDetailPallet.find((value) => value.pallet_id === pallet_id)
		findData.data.splice(counter, 1)

		initDataTablePalletDetail(pallet_id);
	}

	$("#btnsavebtb").click(
		function() {
			var delivery_order_batch_id = $("#konfirmasipengirimancanvas-delivery_order_batch_id").val();
			var delivery_order_id = $("#konfirmasipengirimancanvas-delivery_order_id").val();
			var client_wms_id = $("#konfirmasipengirimancanvas-client_wms_id").val();
			var karyawan_id = $("#konfirmasipengirimancanvas-karyawan_id").val();
			var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();
			var lastUpdated = $("#konfirmasipengirimancanvas-delivery_order_update_tgl").val();

			let error = false;

			$("input[name*='qty_terima']").map(function() {
				if (this.value == "") {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Qty Terima tidak boleh kosong!'
					});

					error = true;
					return false;
				}
			});

			if (dataDetailPallet.length === 0) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Jumlah SKU Pallet Masih Kosong!'
				});

				error = true;
				return false;
			}

			dataDetailPallet.map((value) => {
				value.data.map((items) => {
					if (items.expdate == null) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Expdate Detail Pallet masih ada yang kosong!'
						});

						error = true;
						return false;
					}

					if (items.qty == null || items.qty == "") {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Qty Detail Pallet masih ada yang kosong!'
						});

						error = true;
						return false;
					}
				})
			})

			if (error) return false;

			let fixDataDetail = [];
			fixDataDetail = [];
			dataDetailPallet.map((value) => {
				value.data.map((items) => {
					fixDataDetail.push({
						pallet_id: value.pallet_id,
						...items
					})
				})
			})

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

					setTimeout(function() {

						messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
							if (result.value == true) {
								requestAjax("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Insertkonfirmasipengirimancanvas') ?>", {
									delivery_order_batch_id: delivery_order_batch_id,
									delivery_order_id: delivery_order_id,
									karyawan_id: karyawan_id,
									client_wms_id: client_wms_id,
									principle_id: "",
									penerimaan_penjualan_keterangan: "",
									depo_detail_id: depo_detail_id,
									arrDataSkuBygroup,
									dataDetailPallet: fixDataDetail,
									lastUpdated
								}, "POST", "JSON", function(response) {
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
											window.location.href = "<?php echo base_url() ?>WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/formClosing/" + delivery_order_batch_id + "?mode=closing";
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
										// console.log(msg);
									}
								}, "#btnsavebtb")
							}
						});

					}, 1000);
				}
			});
		}
	);

	$("#btn_konfirmasi_pengiriman").on("click", function() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();

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
					$("#loadingview").show();
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/UpdateClosingPengirimanFDJR') ?>",
						data: {
							delivery_order_batch_id: delivery_order_batch_id,
							delivery_order_batch_status: 'Closing Delivery Confirm'
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
						}
					});
				}
			});
		<?php } ?>

	});

	function UpdateQtySKUPallet(pallet_id, qtyValue, counter) {
		const findIndex = dataDetailPallet.findIndex((value) => value.pallet_id === pallet_id)
		dataDetailPallet[findIndex]['data'][counter]['qty'] = qtyValue;

		console.log(dataDetailPallet);
	}

	function GetRakLajurDetail() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/GetRakLajurDetail') ?>",
			data: {
				depo_detail_id: $("#konfirmasipengirimancanvas-depo_detail_id").val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#konfirmasipengirimancanvas-rak_lajur_detail_id").html('');
				$("#konfirmasipengirimancanvas-rak_lajur_detail_id").append('<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');
				$.each(response, function(i, v) {
					$("#konfirmasipengirimancanvas-rak_lajur_detail_id").append(`<option value="${v.rak_lajur_detail_id}">${v.rak_lajur_detail_nama}</option>`);
				});
			}
		});
	}

	const handlerScanInputManual = (event, value, type) => {
		const idx = type == 'notone' ? null : event.currentTarget.getAttribute('data-idx');
		if (value != "") {
			fetch('<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/getKodeAutoComplete2?params='); ?>' + value + `&type=${type}`)
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
			var depo_detail_id = $("#konfirmasipengirimancanvas-depo_detail_id").val();

			$("#pallet-rak_lajur_detail_id_" + idx).html('');
			$.ajax({
				async: false,
				url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/check_rak_lajur_detail'); ?>",
				type: "POST",
				data: {
					depo_detail_id: depo_detail_id,
					pallet_id: $("#item-" + idx + "-konfirmasipengirimancanvasdetail2-pallet_id").val(),
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
			url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/UpdatePalletKodeTempByIdTemp') ?>",
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