<script>
	let global_sku_id = [];
	let globalTypeTransaction = "";
	let globalData = [];
	let chkLenghtData = [];
	chkLenghtData = [];
	loadingBeforeReadyPage()
	const html5QrCode = new Html5Qrcode("preview");

	const initDataTableKaryawan = () => {
		let tableInit = $('#list_data_tambah_koreksi_pallet').DataTable({
			'lengthMenu': [
				[-1],
				['All']
			],
			'paging': true,
			'ordering': false
		});

		return tableInit;
	}

	const initDataTableListSku = () => {
		const table = $('#table_list_data_pilih_sku').DataTable({
			// columnDefs: [{
			//   sortable: false,
			//   targets: [0, 1, 2, 3, 4, 5, 6, 7]
			// }],
			lengthMenu: [
				[-1],
				['All']
			],
		});

		return table
	}

	$(document).ready(function() {
		select2();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		checkLenghtkoreksiPallet();

		initDataTableListSku()


	});

	const postDataRequest = async (url = '', data = {}, type) => {
		// Default options are marked with *
		const response = await fetch(url, {
			method: type,
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});
		return response.json();
	}


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



	const checkLenghtkoreksiPallet = () => {

		// let table = $('#list_data_tambah_koreksi_pallet').DataTable({
		//   'lengthMenu': [
		//     [10, 20, 50, 100, -1],
		//     [10, 20, 50, 100, 'All']
		//   ],
		//   'paging': true,
		//   'ordering': false
		// });

		let table = initDataTableKaryawan()

		let rows = table.rows({
			'search': 'applied'
		}).nodes();

		// let count_sku = $("#list_data_tambah_koreksi_pallet > tbody tr").length;
		let count_sku = table.page.info().recordsTotal;
		$("#list_data_tambah_koreksi_pallet > tfoot tr #total_detail_pallet").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");

		$("#koreksi_pallet_kode_pallet").val() != "" ? $("#pilih_sku").prop('disabled', false) : $("#pilih_sku").prop('disabled', true);

		let arrSkuId = [];

		// Check/uncheck checkboxes for all rows in the table
		$('input[name*="sku_id"]', rows).map(function() {
			arrSkuId.push(this.value)
		});

		// globalData.map((item, index) => {
		//   if (item.sku_stock_expired_date != null) {
		//     $('input[name*="expired_date"]', rows).map(function() {
		//       if (item.sku_id == $(this).attr("data-skuId") && index == $(this).attr("data-index")) {
		//         $(this).val(item.sku_stock_expired_date);
		//       }
		//     });
		//   }
		// })


		// $("#pilih_sku").attr('data-skuId', JSON.stringify(arrSkuId))
	}

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
					postDataRequest('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/checkKodePallet') ?>', {
							kode_pallet: decodedText
						}, 'POST')
						.then((response) => {
							$("#txtpreviewscan").val(response.kode);
							if (response.type == 200) {
								Swal.fire("Success!", response.message, "success").then(function(result) {
									if (result.value == true) {
										html5QrCode.resume();
									}
								});

								$('#list_data_tambah_koreksi_pallet').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									initDataToTableKoreksiPallet(response.data)
								});
							} else if (response.type == 201) {
								Swal.fire("Error!", response.message, "error").then(function(result) {
									if (result.value == true) {
										html5QrCode.resume();
									}
								});
								// message("Error!", response.message, "error");
							} else {
								Swal.fire("Info!", response.message, "info").then(function(result) {
									if (result.value == true) {
										html5QrCode.resume();
									}
								});
							}
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
			requestCheckKodePallet(barcode)
		}
	});

	const requestCheckKodePallet = (barcode) => {
		postDataRequest('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/checkKodePallet') ?>', {
				kode_pallet: barcode
			}, 'POST')
			.then((response) => {
				$("#txtpreviewscan").val(response.kode);
				if (response.type == 200) {
					message("Success!", response.message, "success");
					$("#kode_barcode").val("");
					$('#list_data_tambah_koreksi_pallet').fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						initDataToTableKoreksiPallet(response.data)
					});

				} else if (response.type == 201) {
					message("Error!", response.message, "error");
				} else {
					message("Info!", response.message, "info");
				}
			});
	}

	const initDataToTableKoreksiPallet = (response) => {
		$("#pallet_id").val(response[0].pallet_id);
		$("#depo_detail_id").val(response[0].depo_detail_id);
		$("#client_wms_id").val(response[0].client_wms_id);
		$("#principle_id").val(response[0].principle_id);
		$("#koreksi_pallet_gudang").val(response[0].rak_lajur_detail_nama);
		$("#koreksi_pallet_kode_pallet").val(response[0].pallet_kode);
		$("#koreksi_pallet_tgl_buat_pallet").val(response[0].tgl_create);
		$("#koreksi_pallet_pembuat_pallet").val(response[0].pallet_who_create);
		$("#lastUpdated").val(response[0].pallet_tgl_update);

		if ($.fn.DataTable.isDataTable('#list_data_tambah_koreksi_pallet')) {
			$('#list_data_tambah_koreksi_pallet').DataTable().clear();
			$('#list_data_tambah_koreksi_pallet').DataTable().destroy();
		}
		checkLenghtkoreksiPallet();

		// if (response.length != 0) {

		//   globalData = [];
		//   response.map((item) => {
		//     globalData.push({
		//       ...item,
		//       qtyPlanKoreksi: item.qty,
		//       checked: false,
		//       isAddSku: 0
		//     })
		//   })

		//   $("#list_data_tambah_koreksi_pallet > tbody").empty();

		//   initDataSku(globalData, '')
		// }
	}

	$(document).on("click", ".deletePalletDetail", function() {
		const skuId = $(this).attr('data-skuId');
		const indexData = $(this).attr('data-index');
		const finIndex = globalData.findIndex((item, index) => item.sku_id == skuId && index == indexData);
		if (finIndex > -1) { // only splice array when item is found
			globalData.splice(finIndex, 1); // 2nd parameter means remove one item only
		}

		initDataSku(globalData, '')
	});


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

	const handleOpenPilihSKU = (event) => {

		$("#modal_list_data_pilih_sku").modal('show');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/getAllSKU') ?>",
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
						$('#table_list_data_pilih_sku').DataTable().destroy();
					}
					$("#table_list_data_pilih_sku > tbody").empty();

					$.each(response, function(i, v) {

						$("#table_list_data_pilih_sku > tbody").append(`
                <tr>
                    <td width="5%" class="text-center">
                      <input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' style="transform: scale(1.5)" value="${v.sku_id},${v.picking_validation_detail_4_id}"/>
                    </td>
                    <td width="10%">${v.delivery_order_batch_kode}</td>
                    <td width="10%">${v.picking_validation_kode}</td>
                    <td width="15%">${v.sku_induk_nama}</td>
                    <td width="25%">${v.sku_nama_produk}</td>
                    <td width="8%" class="text-center">${v.sku_kemasan}</td>
                    <td width="8%" class="text-center">${v.sku_satuan}</td>
                    <td width="10%" class="text-center">${v.principle_nama}</td>
                    <td width="10%" class="text-center">${v.principle_brand_nama}</td>
                </tr>
            `);
					});

					initDataTableListSku()
				} else {
					$("#table_list_data_pilih_sku > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
				}
			}
		})
	}

	function checkAllSKU(e) {
		var checkboxes = $("input[name='chk-data[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	const handleGetPilihSKU = () => {
		let arrSkuId = [];
		let arrpickingValidationDetail4Id = [];
		var checkboxes = $("input[name='chk-data[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			const dataValue = checkboxes[i].value.split(',')
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				// checkboxes[i].disabled = true;
				arrSkuId.push(dataValue[0]);
				arrpickingValidationDetail4Id.push(dataValue[1]);
			}
		}

		if (arrSkuId.length == 0) {
			message("Error!", "Pilih data yang akan dipilih", "error");
			return false;
		} else {
			postDataRequest('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/getAllSKUById') ?>', {
					pickingValidationDetail4Id: arrpickingValidationDetail4Id,
				}, 'POST')
				.then((response) => {

					response.map((itemChooseSku) => {
						globalData.push({
							...itemChooseSku,
							sku_stock_expired_date: itemChooseSku.sku_expdate,
							qtyPlanKoreksi: 0,
							checked: false,
							isAddSku: 1
						})
					})

					// initDataSku(globalData, 'add')

				});

			Swal.fire({
				html: 'Apakah anda ingin menggunakan default expired date?',
				showCancelButton: true,
				confirmButtonText: 'Iya',
				cancelButtonText: `Tidak`,
				allowOutsideClick: false,
			}).then((result) => {
				if (result.value) {

					$.ajax({
						type: "POST",
						url: "<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/checkMinimunExpiredDate'); ?>",
						data: {
							id: arrSkuId
						},
						dataType: "JSON",
						success: function(response) {

							$.each(response, function(i, v) {
								const findIndex = globalData.findIndex((item) => item.sku_id == v.sku_id && item.qtyPlanKoreksi == 0);
								globalData[findIndex]['sku_stock_expired_date'] = v.date;
							})

							arrSkuId = []
							arrpickingValidationDetail4Id = []
							removeModalListSku()
							initDataSku(globalData, 'chked');

							checkLenghtkoreksiPallet();

							// initDataSku(globalData, 'add')
						}
					});
				}

				if (result.dismiss == 'cancel') {
					arrSkuId = []
					arrpickingValidationDetail4Id = []
					removeModalListSku()
					initDataSku(globalData, 'chked');
					checkLenghtkoreksiPallet();
				}
			});

		}
	}

	const initDataSku = (response, mode) => {
		if ($.fn.DataTable.isDataTable('#list_data_tambah_koreksi_pallet')) {
			$('#list_data_tambah_koreksi_pallet').DataTable().clear();
			$('#list_data_tambah_koreksi_pallet').DataTable().destroy();
		}

		$.each(response, function(i, v) {
			$("#list_data_tambah_koreksi_pallet > tbody").append(`
            <tr>
                <td style="display:none">
                  <input type="hidden" name="pallet_detail_id" id="pallet_detail_id" value="${v.isAddSku == 0 ? v.pallet_detail_id : 'null'}"/>
                  <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.isAddSku == 0 ? v.sku_stock_id : 'null'}"/>
                  <input type="hidden" name="sku_id" id="sku_id" class="skuIdTable" value="${v.sku_id}"/>
                  <input type="hidden" name="ed_sj" id="ed_sj" value="${v.isAddSku == 0 ? v.sku_stock_expired_date_sj : 'null'}"/>
                  <input type="hidden" name="sku_induk_id" id="sku_induk_id" value="${v.sku_induk_id}"/>
                  <input type="hidden" name="picking_validation_detail_4_id" id="picking_validation_detail_4_id" value="${v.picking_validation_detail_4_id}"/>
                  <input type="hidden" name="delivery_order_batch_id" id="delivery_order_batch_id" value="${v.delivery_order_batch_id}"/>
                </td>
                <td class="text-center">${v.delivery_order_batch_kode} </td>
                <td class="text-center">${v.picking_validation_kode} </td>
                <td class="text-center">${v.sku_kode} </td>
                <td>${v.sku_nama_produk}</td>
                <td class="text-center">${v.sku_satuan} </td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">
                    <input type="date" class="form-control expired_date" name="expired_date" id="expired_date_${v.sku_id}" data-skuId="${v.sku_id}" value="${v.isAddSku == 0 ? v.sku_stock_expired_date : v.sku_stock_expired_date != null ? v.sku_stock_expired_date: ''}" onkeydown="return false" onchange="handlerDataExpDate('${v.sku_id}', this.value, '${i}')"/>
                </td>
                <td class="text-center">${v.qty}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan_${v.sku_id}" value="${v.qtyPlanKoreksi}" onchange="handlerDataQty('${v.sku_id}', this.value, '${i}')"/>
                </td>
                <td class="text-center">
                  <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" class="btn btn-danger btn-sm deletePalletDetail" data-skuId="${v.sku_id}" data-index="${i}" ${v.isAddSku == 0 ? 'disabled' : ''}><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `);
		});

		if (mode == '') {
			checkLenghtkoreksiPallet();
		}
	}


	$('#example-select-all').on('click', function() {

		initDataSku(globalData, 'chked');

		let table = initDataTableKaryawan()

		// Get all rows with search applied
		let rows = table.rows({
			'search': 'applied'
		}).nodes();

		// Check/uncheck checkboxes for all rows in the table
		$('input[name*="chkDataSku"]', rows).map(function() {
			const dataIndex = this.getAttribute('data-index')
			const findIndex = globalData.findIndex((item, index) => item.sku_id == this.value && index == dataIndex);
			const findIndexSKU = chkLenghtData.findIndex((item, index) => item.sku_id == this.value && index == dataIndex);
			if (this.checked == false) {
				globalData[findIndex]['checked'] = true;

				chkLenghtData.push({
					sku_id: this.value
				})

				return this.checked = true;
			} else {
				globalData[findIndex]['checked'] = false;

				if (findIndexSKU > -1) {
					chkLenghtData.splice(findIndexSKU, 1)
				}

				return this.checked = false;
			}
		});

		initDataSku(globalData, 'chked');
		initDataTableKaryawan()
	});

	// Handle click on checkbox to set state of "Select all" control
	$('#list_data_tambah_koreksi_pallet tbody').on('change', 'input[name*="chkDataSku"]', function() {
		const dataIndex = this.getAttribute('data-index')
		const findIndex = globalData.findIndex((item, index) => item.sku_id == this.value && index == dataIndex);
		const findIndexSKU = chkLenghtData.findIndex((item, index) => item.sku_id == this.value && index == dataIndex);
		// If checkbox is not checked
		let el = $('#example-select-all').get(0);
		if (!this.checked) {

			if (el && el.checked && ('indeterminate' in el)) {
				el.indeterminate = true;
			}

			globalData[findIndex]['checked'] = false;

			if (findIndexSKU > -1) {
				chkLenghtData.splice(findIndexSKU, 1)
			}

			this.checked = false
		} else {

			if (el && el.checked && ('indeterminate' in el)) {
				el.indeterminate = false;
			}
			globalData[findIndex]['checked'] = true;

			chkLenghtData.push({
				sku_id: this.value
			})

			this.checked = true
		}

		initDataSku(globalData, 'chked');
		initDataTableKaryawan()
	});

	const handlerDataQty = (skuId, value, idx) => {
		const qty = value == "" ? 0 : parseInt(value)

		const findIndex = globalData.findIndex((item, index) => item.sku_id == skuId && index == idx);
		globalData[findIndex]['qtyPlanKoreksi'] = qty;

		initDataSku(globalData, 'chked');
		initDataTableKaryawan()
	}

	const handlerDataExpDate = (skuId, value, idx) => {
		// const qty = value == "" ? 0 : parseInt(value)

		const findIndex = globalData.findIndex((item, index) => item.sku_id == skuId && index == idx);
		globalData[findIndex]['sku_stock_expired_date'] = value;

		initDataSku(globalData, 'chked');
		initDataTableKaryawan()
	}


	const handleTutupPilihSKU = () => {
		removeModalListSku()

	}

	const removeModalListSku = () => {
		$('#principle_sku').val('')
		$("#brand_sku").find('option').remove().end().append('<option value="">--Pilih Brand--</option>').val('')

		if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
			$('#table_list_data_pilih_sku').DataTable().destroy();
		}
		$("#table_list_data_pilih_sku > tbody").empty();

		$("#modal_list_data_pilih_sku").modal('hide');

		initDataTableListSku()
	}

	const handleSaveDataKoreksiPallet = () => {
		let typeTransaction = $("#koreksi_pallet_tipe_transaksi").val();
		let pallet_id = $("#pallet_id").val();
		let lastupdate = $("#lastUpdated").val();
		let depo_detail_id = $("#depo_detail_id").val();
		let client_wms_id = $("#client_wms_id").val();
		let principle_id = $("#principle_id").val();
		let keterangan = $("#koreksi_pallet_keterangan").val();


		let arrPalletDetailId = []
		let arrSkuStockId = []
		let arrSkuId = []
		let arrSkuIndukId = []
		let arrExpiredDate = []
		let arrExpiredDateSJ = []
		let arrQtyAvailable = []
		let arrQtyPlan = []
		let arrPickingValidationDetail4Id = []
		let arrDeliveryOrderBacthId = []
		let finalDetailData = [];

		let error = 0;

		if (typeTransaction == "") {
			message("Error!", "Tipe Transaksi boleh kosong", "error");
			error = 1;
			return false;
		}

		// if (chkLenghtData.length == 0) {
		//   message("Error!", "Data yang akan dikoreksi minimal 1 data", "error");
		//   error = 1;
		//   return false;
		// }

		$("#list_data_tambah_koreksi_pallet > tbody tr").each(function() {
			let palletDetailId = $(this).find("td:eq(0) input[name='pallet_detail_id']")
			let skuStockId = $(this).find("td:eq(0) input[name='sku_stock_id']")
			let skuId = $(this).find("td:eq(0) input[name='sku_id']")
			let skuIndukId = $(this).find("td:eq(0) input[name='sku_induk_id']")
			let expiredDateSj = $(this).find("td:eq(0) input[name='ed_sj']")
			let pickingValidationDetail4Id = $(this).find("td:eq(0) input[name='picking_validation_detail_4_id']")
			let deliveryOrderBacthId = $(this).find("td:eq(0) input[name='delivery_order_batch_id']")

			let expiredDate = $(this).find("td:eq(7) input[type='date']")
			let qtyAvailable = $(this).find("td:eq(8)")
			let qtyPlan = $(this).find("td:eq(9) input[type='text']")

			if (palletDetailId.val() == "null") {
				if (expiredDate.val() == "") {
					message("Error!", "Expired tidak boleh kosong", "error");
					error = 1;
					return false;
				}

				if (qtyPlan.val() == "0") {
					message("Error!", "Qty Plan Koreksi tidak boleh kosong", "error");
					error = 1;
					return false;
				}

				error = 0;
			}

			if (error == 1) return false;

			palletDetailId.map(function() {
				arrPalletDetailId.push($(this).val());
			}).get();

			skuStockId.map(function() {
				arrSkuStockId.push($(this).val());
			}).get();

			skuId.map(function() {
				arrSkuId.push($(this).val());
			}).get();

			skuIndukId.map(function() {
				arrSkuIndukId.push($(this).val());
			}).get();

			expiredDate.map(function() {
				arrExpiredDate.push($(this).val());
			}).get();

			expiredDateSj.map(function() {
				arrExpiredDateSJ.push($(this).val());
			}).get();

			qtyAvailable.map(function() {
				arrQtyAvailable.push(parseFloat($(this).text()));
			}).get();

			qtyPlan.map(function() {
				arrQtyPlan.push(parseFloat($(this).val()));
			}).get();

			pickingValidationDetail4Id.map(function() {
				arrPickingValidationDetail4Id.push($(this).val());
			}).get();

			deliveryOrderBacthId.map(function() {
				arrDeliveryOrderBacthId.push($(this).val());
			}).get();
		});

		if (error == 1) return false;

		if (arrPalletDetailId != null) {
			for (let index = 0; index < arrPalletDetailId.length; index++) {
				finalDetailData.push({
					palletDetailId: arrPalletDetailId[index],
					skuStockId: arrSkuStockId[index],
					skuId: arrSkuId[index],
					skuIndukId: arrSkuIndukId[index],
					expiredDate: arrExpiredDate[index],
					expiredDateSJ: arrExpiredDateSJ[index],
					qtyAvailable: arrQtyAvailable[index],
					qtyPlan: arrQtyPlan[index],
					pickingValidationDetail4Id: arrPickingValidationDetail4Id[index],
					deliveryOrderBacthId: arrDeliveryOrderBacthId[index]
				});
			}
		}

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/saveData'); ?>", {
					pallet_id,
					depo_detail_id,
					client_wms_id,
					principle_id,
					typeTransaction,
					keterangan,
					finalDetailData,
					lastupdate
				}, "POST", function(response) {
					if (response.status == 200) {
						message_topright("success", "Data berhasil ditambahkan")
						setTimeout(() => {
							Swal.fire({
								title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
								showConfirmButton: false,
								timer: 500
							});
							location.href = "<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/AssignmentPengembalianBarangMenu') ?>";
						}, 500);

					}
					if (response.status == 401) return message_topright("error", "Data gagal disimpan");
					if (response.status == 400) return messageNotSameLastUpdated()
				}, "#btn_save")
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
		// 		postDataRequest('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/saveData') ?>', {
		// 				pallet_id,
		// 				depo_detail_id,
		// 				client_wms_id,
		// 				principle_id,
		// 				typeTransaction,
		// 				keterangan,
		// 				finalDetailData
		// 			}, 'POST')
		// 			.then((response) => {
		// 				if (response == true) {
		// 					message_topright("success", "Data berhasil ditambahkan")
		// 					setTimeout(() => {
		// 						Swal.fire({
		// 							title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
		// 							showConfirmButton: false,
		// 							timer: 500
		// 						});
		// 						location.href = "<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/AssignmentPengembalianBarangMenu') ?>";
		// 					}, 500);
		// 				}if(){

		// 				}
		// 			});
		// 	}
		// });
	}

	const handleBackKoreksiPallet = () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/AssignmentPengembalianBarangMenu') ?>";
		}, 500);
	}

	document.getElementById('kode_barcode').addEventListener('keyup', function() {
		const typeScan = $("#tempValForScan").val();
		if (this.value != "") {
			fetch('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/getKodeAutoComplete?params='); ?>' + this.value)
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
		document.getElementById('table-fixed').style.display = 'none'

		requestCheckKodePallet(data)
	}
</script>