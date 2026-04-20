<script type="text/javascript">
	let pengirimanBarangId = "<?= $this->uri->segment('5') ?>";
	let scanKodeSKU = [];
	const html5QrCode = new Html5Qrcode("previewScanPallet");
	loadingBeforeReadyPage()
	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire({
	// 		title: msg,
	// 		html: msgtext,
	// 		icon: msgtype
	// 	});
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
	// 		html: msg,
	// 	});
	// }

	$(document).ready(function() {
		// alert('aa')
		$('.select2').select2({
			width: '100%'
		});

		initDataDetailStp1()
		initDataDetailStp2()
		initDataDetailStp3()
		initDataDetailStp4()

		$("#TabelDetailBulk").DataTable()
		$("#TabelDetailStandar").DataTable()
		$("#TabelDetailReschedule").DataTable()
		$("#TabelDetailCanvas").DataTable()
		// getDataPickingList();
		// $(".date").datepicker('update', '<?= date('Y-m-d ') ?>');

		// // Tambahkan opsi pada dropdown select untuk setiap kolom pada tabel
		// $('#TabelDetailBulk th').each(function(index) {
		//     var col_name = $(this).text();
		//     $('#colvis-select').append('<option value="' + index + '" selected>' + col_name + '</option>');
		// });

		// // Setiap kali dropdown select berubah
		// $('#colvis-select').change(function() {
		//     // Munculkan atau sembunyikan setiap kolom pada tabel
		//     $('#TabelDetailBulk th').each(function(index) {
		//         var show = $('#colvis-select option[value="' + index + '"]').is(':selected');
		//         $('#TabelDetailBulk td:nth-child(' + (index + 1) + ')').toggle(show);
		//         $(this).toggle(show);
		//     });
		// });
	});

	const initDataDetailStp1 = async () => {
		let postData = new FormData();
		postData.append('pengirimanBarangId', pengirimanBarangId);

		const request = await fetch("<?= base_url('WMS/Distribusi/PengirimanBarang/getDataDetailStp1') ?>", {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		const filterDataBulk = scanKodeSKU.filter((item) => item.type == 'bulk');

		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#TabelDetailBulk')) {
				$('#TabelDetailBulk').DataTable().destroy();
			}

			$('#TabelDetailBulk tbody').empty();

			let html = "";
			$.each(response, function(i, v) {

				let styleTr = "";
				let disableTd = "";
				let checkedTd = "";
				if (filterDataBulk.length != 0) {
					const findData = filterDataBulk.find((item) => (item.sku_id === v.sku_id));
					if (typeof findData !== 'undefined') {
						styleTr = "background-color: #86efac; color:#0f172a";
						disableTd = "readonly";
						checkedTd = "checked";
					} else {
						styleTr = "";
						disableTd = "";
						checkedTd = "";
					}
				}

				html +=
					`<tr style="${styleTr}">
                            <td style="text-align: center;">${i + 1}<input type="hidden" class="form-control" name="serah_terima_kirim_d1_id[]" value="${v.serah_terima_kirim_d1_id}"></td>
                            <td style="text-align: center;">${v.principle_kode}</td>
                            <td style="text-align: center;">${v.sku_kode}</td>
                            <td style="text-align: center;">${v.sku_nama_produk}</td>
                            <td style="text-align: center;">${v.sku_kemasan}</td>
                            <td style="text-align: center;">${v.sku_satuan}</td>
                            <td style="text-align: center;" hidden>${v.jumlah_ambil_plan}</td>
                            <td style="text-align: center;" hidden>
                                ${v.jumlah_ambil_aktual}
                                <input type="hidden" class="form-control" name="jumlah_ambil_aktual_1[]" value="${v.jumlah_ambil_aktual}>">
                                <input type="hidden" class="form-control" name="serah_terima_1_awal[]" id="serah_terima_1_awal-${v.sku_id}" value="${v.jumlah_serah_terima}">
                                <input type="hidden" class="form-control" name="serah_terima_rusak_1_awal[]" id="serah_terima_rusak_1_awal-${v.sku_id}" value="${v.jumlah_serah_terima_rusak}">
                            </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima}" name="serah_terima_1[]" id="serah_terima_1-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima_rusak}" name="serah_terima_rusak_1[]" id="serah_terima_rusak_1-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>`;
				if ($('#status').val() != 'Closed') {
					html +=
						`<td style="text-align: center;"><input class="form-control" ${checkedTd} type="checkbox" data-key="${v.sku_id}" name="edit_1[]" id="edit_1[]" value="0"></td>`;
				}
				html += `</tr>`;
			})
			$('#TabelDetailBulk tbody').append(html)
			$("#TabelDetailBulk").DataTable()
		}
	}

	const initDataDetailStp2 = async () => {
		let postData = new FormData();
		postData.append('pengirimanBarangId', pengirimanBarangId);

		const request = await fetch("<?= base_url('WMS/Distribusi/PengirimanBarang/getDataDetailStp2') ?>", {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		const filterDataStandar = scanKodeSKU.filter((item) => item.type == 'standar');

		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#TabelDetailStandar')) {
				$('#TabelDetailStandar').DataTable().destroy();
			}

			$('#TabelDetailStandar tbody').empty();

			let html = "";
			$.each(response, function(i, v) {

				let styleTr = "";
				let disableTd = "";
				let checkedTd = "";
				if (filterDataStandar.length != 0) {
					const findData = filterDataStandar.find((item) => (item.sku_id === v.sku_id));
					if (typeof findData !== 'undefined') {
						styleTr = "background-color: #86efac; color:#0f172a";
						disableTd = "readonly";
						checkedTd = "checked";
					} else {
						styleTr = "";
						disableTd = "";
						checkedTd = "";
					}
				}

				html +=
					`<tr style="${styleTr}">
                            <td style="text-align: center;">${i + 1}<input type="hidden" class="form-control" name="serah_terima_kirim_d2_id[]" value="${v.serah_terima_kirim_d2_id}"></td>
                            <td style="text-align: center;">${v.delivery_order_kode}</td>
                            <td style="text-align: center;">${v.delivery_order_kirim_nama}</td>
                            <td style="text-align: center;">${v.delivery_order_kirim_alamat}</td>
                            <td style="text-align: center;" hidden>
                                ${v.jumlah_paket}
                                <input type="hidden" class="form-control" name="jumlah_ambil_aktual_2[]" value="${v.jumlah_paket}">
                                <input type="hidden" class="form-control" name="serah_terima_2_awal[]" id="serah_terima_2_awal-${v.serah_terima_kirim_d2_id}" value="${v.jumlah_serah_terima}">
                            </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima}" id="serah_terima_2-${v.serah_terima_kirim_d2_id}" name="serah_terima_2[]" ${disableTd == "" ? 'readonly' : ''}> </td>`;
				if ($('#status').val() != 'Closed') {
					html +=
						`<td style="text-align: center;"><input class="form-control" ${checkedTd} type="checkbox" data-key="${v.serah_terima_kirim_d2_id}" name="edit_2[]" id="edit_2[]" value="0"></td>`;
				}
				html += `</tr>`;
			})
			$('#TabelDetailStandar tbody').append(html)
			$("#TabelDetailStandar").DataTable()
		}
	}

	const initDataDetailStp3 = async () => {
		let postData = new FormData();
		postData.append('pengirimanBarangId', pengirimanBarangId);

		const request = await fetch("<?= base_url('WMS/Distribusi/PengirimanBarang/getDataDetailStp3') ?>", {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		const filterDataReschedule = scanKodeSKU.filter((item) => item.type == 'reschedule');

		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#TabelDetailReschedule')) {
				$('#TabelDetailReschedule').DataTable().destroy();
			}

			$('#TabelDetailReschedule tbody').empty();

			let html = "";
			$.each(response, function(i, v) {

				let styleTr = "";
				let disableTd = "";
				let checkedTd = "";
				if (filterDataReschedule.length != 0) {
					const findData = filterDataReschedule.find((item) => (item.sku_id === v.sku_id));
					if (typeof findData !== 'undefined') {
						styleTr = "background-color: #86efac; color:#0f172a";
						disableTd = "readonly";
						checkedTd = "checked";
					} else {
						styleTr = "";
						disableTd = "";
						checkedTd = "";
					}
				}

				html +=
					`<tr style="${styleTr}">
                            <td style="text-align: center;">${i + 1}<input type="hidden" class="form-control" name="serah_terima_kirim_d3_id[]" value="${v.serah_terima_kirim_d3_id}"></td>
                            <td style="text-align: center;">${v.principle_kode}</td>
                            <td style="text-align: center;">${v.sku_kode}</td>
                            <td style="text-align: center;">${v.sku_nama_produk}</td>
                            <td style="text-align: center;">${v.sku_kemasan}</td>
                            <td style="text-align: center;">${v.sku_satuan}</td>
                            <td style="text-align: center;" hidden>${v.jumlah_ambil_plan}</td>
                            <td style="text-align: center;" hidden>
                                ${v.jumlah_ambil_aktual}
                                <input type="hidden" class="form-control" name="jumlah_ambil_aktual_3[]" value="${v.jumlah_ambil_aktual}>">
                                <input type="hidden" class="form-control" name="serah_terima_3_awal[]" id="serah_terima_3_awal-${v.sku_id}" value="${v.jumlah_serah_terima}">
                                <input type="hidden" class="form-control" name="serah_terima_rusak_3_awal[]" id="serah_terima_rusak_3_awal-${v.sku_id}" value="${v.jumlah_serah_terima_rusak}">
                            </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima}" name="serah_terima_3[]" id="serah_terima_3-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima_rusak}" name="serah_terima_rusak_3[]" id="serah_terima_rusak_3-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>`;
				if ($('#status').val() != 'Closed') {
					html +=
						`<td style="text-align: center;"><input class="form-control" ${checkedTd} type="checkbox" data-key="${v.sku_id}" name="edit_3[]" id="edit_3[]" value="0"></td>`;
				}
				html += `</tr>`;
			})
			$('#TabelDetailReschedule tbody').append(html)
			$("#TabelDetailReschedule").DataTable()
		}
	}

	const initDataDetailStp4 = async () => {
		let postData = new FormData();
		postData.append('pengirimanBarangId', pengirimanBarangId);

		const request = await fetch("<?= base_url('WMS/Distribusi/PengirimanBarang/getDataDetailStp4') ?>", {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		const filterDataCanvas = scanKodeSKU.filter((item) => item.type == 'canvas');

		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#TabelDetailCanvas')) {
				$('#TabelDetailCanvas').DataTable().destroy();
			}

			$('#TabelDetailCanvas tbody').empty();

			let html = "";
			$.each(response, function(i, v) {

				let styleTr = "";
				let disableTd = "";
				let checkedTd = "";
				if (filterDataCanvas.length != 0) {
					const findData = filterDataCanvas.find((item) => (item.sku_id === v.sku_id));
					if (typeof findData !== 'undefined') {
						styleTr = "background-color: #86efac; color:#0f172a";
						disableTd = "readonly";
						checkedTd = "checked";
					} else {
						styleTr = "";
						disableTd = "";
						checkedTd = "";
					}
				}

				html +=
					`<tr style="${styleTr}">
                            <td style="text-align: center;">${i + 1}<input type="hidden" class="form-control" name="serah_terima_kirim_d4_id[]" value="${v.serah_terima_kirim_d4_id}"></td>
                            <td style="text-align: center;">${v.principle_kode}</td>
                            <td style="text-align: center;">${v.sku_kode}</td>
                            <td style="text-align: center;">${v.sku_nama_produk}</td>
                            <td style="text-align: center;">${v.sku_kemasan}</td>
                            <td style="text-align: center;">${v.sku_satuan}</td>
                            <td style="text-align: center;" hidden>${v.jumlah_ambil_plan}</td>
                            <td style="text-align: center;" hidden>
                                ${v.jumlah_ambil_aktual}
                                <input type="hidden" class="form-control" name="jumlah_ambil_aktual_4[]" value="${v.jumlah_ambil_aktual}>">
                                <input type="hidden" class="form-control" name="serah_terima_4_awal[]" id="serah_terima_4_awal-${v.sku_id}" value="${v.jumlah_serah_terima}">
                                <input type="hidden" class="form-control" name="serah_terima_rusak_4_awal[]" id="serah_terima_rusak_4_awal-${v.sku_id}" value="${v.jumlah_serah_terima_rusak}">
                            </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima}" name="serah_terima_4[]" id="serah_terima_4-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>
                            <td style="text-align: center;"><input type="number" class="form-control" value="${v.jumlah_serah_terima_rusak}" name="serah_terima_rusak_4[]" id="serah_terima_rusak_4-${v.sku_id}" ${disableTd == "" ? 'readonly' : ''}> </td>`;
				if ($('#status').val() != 'Closed') {
					html +=
						`<td style="text-align: center;"><input class="form-control" ${checkedTd} type="checkbox" data-key="${v.sku_id}" name="edit_4[]" id="edit_4[]" value="0"></td>`;
				}
				html += `</tr>`;
			})
			$('#TabelDetailCanvas tbody').append(html)
			$("#TabelDetailCanvas").DataTable()
		}
	}

	$(document).on('click', 'input[name="edit_1[]"]', function() {
		var key = $(this).data("key");
		// alert(key)
		if (this.checked) {
			$('#serah_terima_1-' + key).attr('readonly', false)
			$('#serah_terima_rusak_1-' + key).attr('readonly', false)
		} else {
			// kembalikan ke nilai awal
			let val_awal = $('#serah_terima_1_awal-' + key).val()
			$('#serah_terima_1-' + key).val(val_awal)
			$('#serah_terima_1-' + key).attr('readonly', true)

			let val_awal_rusak = $('#serah_terima_rusak_1_awal-' + key).val()
			$('#serah_terima_rusak_1-' + key).val(val_awal_rusak)
			$('#serah_terima_rusak_1-' + key).attr('readonly', true)
		}
	});

	$(document).on('click', 'input[name="edit_2[]"]', function() {
		var key = $(this).data("key");
		// alert(key)
		if (this.checked) {
			$('#serah_terima_2-' + key).attr('readonly', false)
		} else {
			// kembalikan ke nilai awal
			let val_awal = $('#serah_terima_2_awal-' + key).val()
			$('#serah_terima_2-' + key).val(val_awal)
			$('#serah_terima_2-' + key).attr('readonly', true)
		}

	});

	$(document).on('click', 'input[name="edit_3[]"]', function() {
		var key = $(this).data("key");
		// alert(key)
		if (this.checked) {
			$('#serah_terima_3-' + key).attr('readonly', false)
			$('#serah_terima_rusak_3-' + key).attr('readonly', false)
		} else {
			// kembalikan ke nilai awal
			let val_awal = $('#serah_terima_3_awal-' + key).val()
			$('#serah_terima_3-' + key).val(val_awal)
			$('#serah_terima_3-' + key).attr('readonly', true)

			let val_awal_rusak = $('#serah_terima_rusak_3_awal-' + key).val()
			$('#serah_terima_rusak_3-' + key).val(val_awal_rusak)
			$('#serah_terima_rusak_3-' + key).attr('readonly', true)
		}
	});

	$(document).on('click', 'input[name="edit_4[]"]', function() {
		var key = $(this).data("key");
		// alert(key)
		if (this.checked) {
			$('#serah_terima_4-' + key).attr('readonly', false)
			$('#serah_terima_rusak_4-' + key).attr('readonly', false)
		} else {
			// kembalikan ke nilai awal
			let val_awal = $('#serah_terima_4_awal-' + key).val()
			$('#serah_terima_4-' + key).val(val_awal)
			$('#serah_terima_4-' + key).attr('readonly', true)

			let val_awal_rusak = $('#serah_terima_rusak_4_awal-' + key).val()
			$('#serah_terima_rusak_4-' + key).val(val_awal_rusak)
			$('#serah_terima_rusak_4-' + key).attr('readonly', true)
		}
	});

	const changeScanPalletHandler = (e, type) => {
		if (e.target.checked) {
			if (type == 'bulk') {
				$(`#input_pallet_bulk`).show();
				$(`#start_scan_pallet_bulk`).hide();
			}

			if (type == 'standar') {
				$(`#input_pallet_standar`).show();
				$(`#start_scan_pallet_standar`).hide();
			}

			if (type == 'reschedule') {
				$(`#input_pallet_reschedule`).show();
				$(`#start_scan_pallet_reschedule`).hide();
			}

			if (type == 'canvas') {
				$(`#input_pallet_canvas`).show();
				$(`#start_scan_pallet_canvas`).hide();
			}

		} else {
			if (type == 'bulk') {
				$(`#input_pallet_bulk`).hide();
				$(`#start_scan_pallet_bulk`).show();
			}

			if (type == 'standar') {
				$(`#input_pallet_standar`).hide();
				$(`#start_scan_pallet_standar`).show();
			}

			if (type == 'reschedule') {
				$(`#input_pallet_reschedule`).hide();
				$(`#start_scan_pallet_reschedule`).show();
			}

			if (type == 'canvas') {
				$(`#input_pallet_canvas`).hide();
				$(`#start_scan_pallet_canvas`).show();
			}

		}
	}

	const inputScanPalletHandler = (type) => {
		$("#modalInputPallet").modal('show');

		const data = {
			pengirimanBarangId,
			type
		}

		$("#kodeSKu").attr("data-handler", JSON.stringify(data));

	}

	const changeInputPalletHandler = (event) => {
		const dataHandler = JSON.parse(event.currentTarget.getAttribute('data-handler'));
		const idPengiriman = dataHandler.pengirimanBarangId;
		const type = dataHandler.type;
		const value = event.currentTarget.value;

		requestDataToScan(idPengiriman, type, value)

	}

	const closeInputPalletHandler = () => {
		$("#modalInputPallet").modal('hide');
		$("#kodeSKu").val("");
	}

	const startScanPalletHandler = (type) => {
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-ALERT-MEMUATKAMERA"></label></span>',
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
				$("#modalScanPallet").modal('show');

				//handle succes scan
				const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {
						html5QrCode.pause();
						scan(decodedText);
					}
				};


				const scan = (decodedText) => {
					requestDataToScan(pengirimanBarangId, type, decodedText)
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
						$("#selectCameraPallet").empty();
						$.each(devices, function(i, v) {
							$("#selectCameraPallet").append(`
	                      <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
	                      <label class="for-checkbox-tools" for="tool-${i}">
	                          ${v.label}
	                      </label>
	                  `)
						});

						$("#selectCameraPallet :input[name='tools2']").each(function(i, v) {
							if (i == 0) {
								$(this).attr('checked', true);
							}
						});

						let cameraId2 = devices[0].id;
						// html5QrCode.start(devices[0]);
						$('input[name="tools2"]').on('click', function() {
							// alert($(this).val());
							html5QrCode.stop();
							html5QrCode.start({
								deviceId: {
									exact: $(this).val()
								}
							}, config2, qrCodeSuccessCallback2);
						});
						//start scan
						html5QrCode.start({
							deviceId: {
								exact: cameraId2
							}
						}, config2, qrCodeSuccessCallback2);

					}
				}).catch(err => {
					message("Error!", "Kamera tidak ditemukan", "error");
					$("#modalScanPallet").modal('hide');
				});
			}
		});
	}

	const requestDataToScan = async (idPengiriman, type, value) => {
		const url = "<?= base_url('WMS/Distribusi/PengirimanBarang/CheckScanKodeSKU') ?>";

		let postData = new FormData();
		postData.append('pengirimanBarangId', idPengiriman);
		postData.append('mode', 'update');
		postData.append('type', type);
		postData.append('kode', value);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		if (response.type == 200) {
			if (scanKodeSKU.length === 0) {
				scanKodeSKU.push({
					sku_id: response.data.sku_id,
					type
				});
				message('Success!', response.message, 'success');
				if (type == 'bulk') {
					$(`#TabelDetailBulk`).fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						initDataDetailStp1()
					});
				}
				if (type == 'standar') {
					$(`#TabelDetailStandar`).fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						initDataDetailStp2()
					});
				}

				if (type == 'reschedule') {
					$(`#TabelDetailReschedule`).fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						initDataDetailStp3()
					});
				}

				if (type == 'canvas') {
					$(`#TabelDetailCanvas`).fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						initDataDetailStp4()
					});
				}

			} else {
				const findData = scanKodeSKU.find((item) => (item.sku_id === response.data.sku_id) && (item.type ===
					type));
				if (typeof findData === 'undefined') {
					scanKodeSKU.push({
						sku_id: response.data.sku_id,
						type
					});
					message('Success!', response.message, 'success');
					if (type == 'bulk') {
						$(`#TabelDetailBulk`).fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataDetailStp1()
						});
					}
					if (type == 'standar') {
						$(`#TabelDetailStandar`).fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataDetailStp2()
						});
					}

					if (type == 'reschedule') {
						$(`#TabelDetailReschedule`).fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataDetailStp3()
						});
					}

					if (type == 'canvas') {
						$(`#TabelDetailCanvas`).fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataDetailStp4()
						});
					}
				} else {
					message('Info!', 'Kode SKU sudah discan', 'info');
				}
			}
		}

		if (response.type == 201) {
			message('Error!', response.message, 'error');
		}
	}

	const closeScanPalletHandler = () => {
		html5QrCode.stop();
		$("#modalScanPallet").modal('hide');
	}

	function saveEdit() {
		let serah_terima_kirim_id = $('#serah_terima_kirim_id').val();
		let serah_terima_kirim_d1_id = null;
		let serah_terima_kirim_d2_id = null;
		let serah_terima_kirim_d3_id = null;
		let serah_terima_kirim_d4_id = null;

		let jumlah_serah_terima_1 = null;
		let jumlah_serah_terima_rusak_1 = null;

		let jumlah_serah_terima_2 = null;

		let jumlah_serah_terima_3 = null;
		let jumlah_serah_terima_rusak_3 = null;

		let jumlah_serah_terima_4 = null;
		let jumlah_serah_terima_rusak_4 = null;
		var last_update = $("#last_update").val();
		// jika ada detail 1 atau tipe bulk
		if ($("input[name='serah_terima_kirim_d1_id[]']").length > 0) {
			serah_terima_kirim_d1_id = $("input[name='serah_terima_kirim_d1_id[]']").map(function() {

				return this.value;
			}).get();
			jumlah_serah_terima_1 = $("input[name='serah_terima_1[]']").map(function() {
				return this.value;
			}).get();
			jumlah_serah_terima_rusak_1 = $("input[name='serah_terima_rusak_1[]']").map(function() {
				return this.value;
			}).get();
		}

		// jika ada detail 2 atau tipe tandar
		if ($("input[name='serah_terima_kirim_d2_id[]']").length > 0) {
			serah_terima_kirim_d2_id = $("input[name='serah_terima_kirim_d2_id[]']").map(function() {
				return this.value;
			}).get();
			jumlah_serah_terima_2 = $("input[name='serah_terima_2[]']").map(function() {
				if (parseInt(this.value) <= 0) {
					alert("Nominal serah terima tidak boleh nol!")
					return false;
				}
				return this.value;
			}).get();
		}

		// jika ada detail 3 atau tipe reschedule
		if ($("input[name='serah_terima_kirim_d3_id[]']").length > 0) {
			serah_terima_kirim_d3_id = $("input[name='serah_terima_kirim_d3_id[]']").map(function() {

				return this.value;
			}).get();
			jumlah_serah_terima_3 = $("input[name='serah_terima_3[]']").map(function() {
				return this.value;
			}).get();
			jumlah_serah_terima_rusak_3 = $("input[name='serah_terima_rusak_3[]']").map(function() {
				return this.value;
			}).get();
		}

		// jika ada detail 4 atau tipe canvas
		if ($("input[name='serah_terima_kirim_d4_id[]']").length > 0) {
			serah_terima_kirim_d4_id = $("input[name='serah_terima_kirim_d4_id[]']").map(function() {

				return this.value;
			}).get();
			jumlah_serah_terima_4 = $("input[name='serah_terima_4[]']").map(function() {
				return this.value;
			}).get();
			jumlah_serah_terima_rusak_4 = $("input[name='serah_terima_rusak_4[]']").map(function() {
				return this.value;
			}).get();
		}

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/Distribusi/PengirimanBarang/UpdatePengirimanBarang'); ?>", {
					serah_terima_kirim_id: serah_terima_kirim_id,
					last_update: last_update,
					//detail 1
					serah_terima_kirim_d1_id: serah_terima_kirim_d1_id,
					jumlah_serah_terima_1: jumlah_serah_terima_1,
					jumlah_serah_terima_rusak_1: jumlah_serah_terima_rusak_1,
					//detail 2
					serah_terima_kirim_d2_id: serah_terima_kirim_d2_id,
					jumlah_serah_terima_2: jumlah_serah_terima_2,
					//detail 3
					serah_terima_kirim_d3_id: serah_terima_kirim_d3_id,
					jumlah_serah_terima_3: jumlah_serah_terima_3,
					jumlah_serah_terima_rusak_3: jumlah_serah_terima_rusak_3,
					//detail 4
					serah_terima_kirim_d4_id: serah_terima_kirim_d4_id,
					jumlah_serah_terima_4: jumlah_serah_terima_4,
					jumlah_serah_terima_rusak_4: jumlah_serah_terima_rusak_4,
				}, "POST", "JSON", function(response) {
					if (response.status == 200) {
						Swal.fire(
							'Success!',
							'Your file has been created.',
							'success'
						)
						setTimeout(function() {
							window.location.href =
								"<?= base_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangEdit/') ?>" +
								serah_terima_kirim_id + "";
						}, 3000);
					} else {
						Swal.fire(
							'Error!',
							response.message,
							'warning'
						)
						$('#saveEdit').prop('disabled', false);
					}
					if (response.status === 400) return messageNotSameLastUpdated()
					if (response.status === 401) return message_topright('error', response.message)
				})
			}
		});
	}

	function saveConfirm() {
		let serah_terima_kirim_id = $('#serah_terima_kirim_id').val();
		var last_update = $("#last_update").val();

		messageBoxBeforeRequest('Pastikan sudah menyimpan data perubahan!', 'Ya, Konfirm !', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {

				let serah_terima_kirim_id = $("#serah_terima_kirim_id").val();
				let delivery_order_batch_id = $("#delivery_order_batch_id").val();
				let delivery_order_id = null;

				if ($("input[name='delivery_order_id[]']").length > 0) {
					delivery_order_id = $("input[name='delivery_order_id[]']").map(function() {
						return this.value;
					}).get();
				}

				// jika ada detail 1 atau tipe bulk
				if ($("input[name='serah_terima_kirim_d1_id[]']").length > 0) {
					let jumlah_ambil_aktual_1 = $("input[name='jumlah_ambil_aktual_1[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_1 = $("input[name='serah_terima_1_awal[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_rusak_1 = $("input[name='serah_terima_rusak_1_awal[]']").map(
						function() {
							return parseInt(this.value);
						}).get();
					let total_ambil_aktual_1 = jumlah_ambil_aktual_1.reduce((a, b) => a + b, 0);
					let total_serah_terima_1 = jumlah_serah_terima_1.reduce((a, b) => a + b, 0);
					let total_serah_terima_rusak_1 = jumlah_serah_terima_rusak_1.reduce((a, b) => a + b, 0);
				}

				// jika ada detail 2 atau tipe tandar
				if ($("input[name='serah_terima_kirim_d2_id[]']").length > 0) {
					let jumlah_ambil_aktual_2 = $("input[name='jumlah_ambil_aktual_2[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_2 = $("input[name='serah_terima_2_awal[]']").map(function() {
						return parseInt(this.value);
					}).get();
					// sum array
					let total_ambil_aktual_2 = jumlah_ambil_aktual_2.reduce((a, b) => a + b, 0);
					let total_serah_terima_2 = jumlah_serah_terima_2.reduce((a, b) => a + b, 0);
				}

				// jika ada detail 3 atau tipe reschedule
				if ($("input[name='serah_terima_kirim_d3_id[]']").length > 0) {
					let jumlah_ambil_aktual_3 = $("input[name='jumlah_ambil_aktual_3[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_3 = $("input[name='serah_terima_3_awal[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_rusak_3 = $("input[name='serah_terima_rusak_3_awal[]']").map(
						function() {
							return parseInt(this.value);
						}).get();
					let total_ambil_aktual_3 = jumlah_ambil_aktual_3.reduce((a, b) => a + b, 0);
					let total_serah_terima_3 = jumlah_serah_terima_3.reduce((a, b) => a + b, 0);
					let total_serah_terima_rusak_3 = jumlah_serah_terima_rusak_3.reduce((a, b) => a + b, 0);
				}

				// jika ada detail 4 atau tipe reschedule
				if ($("input[name='serah_terima_kirim_d4_id[]']").length > 0) {
					let jumlah_ambil_aktual_4 = $("input[name='jumlah_ambil_aktual_4[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_4 = $("input[name='serah_terima_4_awal[]']").map(function() {
						return parseInt(this.value);
					}).get();
					let jumlah_serah_terima_rusak_4 = $("input[name='serah_terima_rusak_4_awal[]']").map(
						function() {
							return parseInt(this.value);
						}).get();
					let total_ambil_aktual_4 = jumlah_ambil_aktual_4.reduce((a, b) => a + b, 0);
					let total_serah_terima_4 = jumlah_serah_terima_4.reduce((a, b) => a + b, 0);
					let total_serah_terima_rusak_4 = jumlah_serah_terima_rusak_4.reduce((a, b) => a + b, 0);
				}

				requestAjax("<?= base_url('WMS/Distribusi/PengirimanBarang/ConfirmPengirimanBarang'); ?>", {
					serah_terima_kirim_id: serah_terima_kirim_id,
					delivery_order_batch_id: delivery_order_batch_id,
					delivery_order_id: delivery_order_id,
					last_update: last_update
				}, "POST", "JSON", function(response) {
					if (response.status == 200) {
						Swal.fire(
							'Success!',
							'Your file has been confirmed.',
							'success'
						)
						setTimeout(function() {
							window.location.href =
								"<?= base_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/') ?>" +
								serah_terima_kirim_id + "";
						}, 3000);
					} else {
						Swal.fire(
							'Error!',
							response.message,
							'warning'
						)
						$('#saveConfirm').prop('disabled', false);
					}
					if (response.status === 400) return messageNotSameLastUpdated(
						'WMS/Distribusi/PengirimanBarang/PengirimanBarangMenu')
					if (response.status === 401) return message_topright('error', response.message)
				})
			}
		});

		// Swal.fire({
		// 	title: 'Apakah Anda yakin?',
		// 	text: "Pastikan sudah menyimpan data perubahan!",
		// 	icon: 'warning',
		// 	showCancelButton: true,
		// 	confirmButtonColor: '#3085d6',
		// 	cancelButtonColor: '#d33',
		// 	confirmButtonText: 'Ya, Konfirm !'
		// }).then((result) => {
		// 	// console.log(result);
		// 	if (result.value == true) {

		// 		let serah_terima_kirim_id = $("#serah_terima_kirim_id").val();
		// 		let delivery_order_batch_id = $("#delivery_order_batch_id").val();
		// 		let delivery_order_id = null;

		// 		if ($("input[name='delivery_order_id[]']").length > 0) {
		// 			delivery_order_id = $("input[name='delivery_order_id[]']").map(function() {
		// 				return this.value;
		// 			}).get();
		// 		}

		// 		// jika ada detail 1 atau tipe bulk
		// 		if ($("input[name='serah_terima_kirim_d1_id[]']").length > 0) {
		// 			let jumlah_ambil_aktual_1 = $("input[name='jumlah_ambil_aktual_1[]']").map(function() {
		// 				return parseInt(this.value);
		// 			}).get();
		// 			let jumlah_serah_terima_1 = $("input[name='serah_terima_1_awal[]']").map(function() {
		// 				return parseInt(this.value);
		// 			}).get();
		// 			let jumlah_serah_terima_rusak_1 = $("input[name='serah_terima_rusak_1_awal[]']").map(
		// 				function() {
		// 					return parseInt(this.value);
		// 				}).get();
		// 			let total_ambil_aktual_1 = jumlah_ambil_aktual_1.reduce((a, b) => a + b, 0);
		// 			let total_serah_terima_1 = jumlah_serah_terima_1.reduce((a, b) => a + b, 0);
		// 			let total_serah_terima_rusak_1 = jumlah_serah_terima_rusak_1.reduce((a, b) => a + b, 0);


		// 		}
		// 		// jika ada detail 2 atau tipe tandar
		// 		if ($("input[name='serah_terima_kirim_d2_id[]']").length > 0) {
		// 			let jumlah_ambil_aktual_2 = $("input[name='jumlah_ambil_aktual_2[]']").map(function() {
		// 				return parseInt(this.value);
		// 			}).get();
		// 			let jumlah_serah_terima_2 = $("input[name='serah_terima_2_awal[]']").map(function() {
		// 				return parseInt(this.value);
		// 			}).get();
		// 			// sum array
		// 			let total_ambil_aktual_2 = jumlah_ambil_aktual_2.reduce((a, b) => a + b, 0);
		// 			let total_serah_terima_2 = jumlah_serah_terima_2.reduce((a, b) => a + b, 0);

		// 		}

		// 		$.ajax({
		// 			type: 'POST',
		// 			url: "<?= base_url('WMS/Distribusi/PengirimanBarang/ConfirmPengirimanBarang') ?>",
		// 			data: {
		// 				serah_terima_kirim_id: serah_terima_kirim_id,
		// 				delivery_order_batch_id: delivery_order_batch_id,
		// 				delivery_order_id: delivery_order_id,

		// 			},
		// 			async: "true",
		// 			beforeSend: function() {
		// 				$("#saveConfirm").prop('disabled', true);
		// 			},
		// 			dataType: "json",
		// 			success: function(response) {
		// 				console.log(response);
		// 				if (response.status == 1) {
		// 					Swal.fire(
		// 						'Success!',
		// 						'Your file has been confirm.',
		// 						'success'
		// 					)
		// 					setTimeout(function() {
		// 						window.location.href =
		// 							"<?= base_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/') ?>" +
		// 							serah_terima_kirim_id + "";
		// 					}, 3000);
		// 				} else {
		// 					Swal.fire(
		// 						'Error!',
		// 						response.message,
		// 						'warning'
		// 					)
		// 					$('#saveConfirm').prop('disabled', false);
		// 				}
		// 			}
		// 		});
		// 	}
		// })
	}

	const handlerAutoCompleteSKU = (event, value) => {
		const dataku = event.currentTarget.getAttribute('data-handler');
		const requestReplace = dataku.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')

		if (value != "") {
			fetch('<?= base_url('WMS/Distribusi/PengirimanBarang/getKodeAutoComplete?params='); ?>' + value)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks(event, '${e.kode}', '${requestReplace2.replace(/"/g, '\\\'')}')" style="cursor:pointer">
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
	}

	function getNoSuratJalanEks(event, data, dataRequest) {
		const requestReplace = dataRequest.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')
		const dataFix = JSON.parse(requestReplace2);

		$("#kodeSKu").val(data);
		document.getElementById('table-fixed').style.display = 'none'

		// const idPengiriman = dataFix.pengirimanBarangId;
		// const type = dataFix.type;

		requestDataToScan(dataFix.pengirimanBarangId, dataFix.type, data)
	}
</script>