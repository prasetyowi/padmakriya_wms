<script>
	let global_sku_id = [];
	let globalTypeTransaction = "";
	let globalData = [];

	const html5QrCode = new Html5Qrcode("preview");
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		checkLenghtkoreksiPallet();

	});

	const postData = async (url = '', data = {}, type) => {
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
	//   Swal.fire(msg, msgtext, msgtype);
	// }


	// function message_topright(type, msg) {
	//   const Toast = Swal.mixin({
	//     toast: true,
	//     position: "top-end",
	//     showConfirmButton: false,
	//     timer: 3000,
	//     didOpen: (toast) => {
	//       toast.addEventListener("mouseenter", Swal.stopTimer);
	//       toast.addEventListener("mouseleave", Swal.resumeTimer);
	//     },
	//   });

	//   Toast.fire({
	//     icon: type,
	//     title: msg,
	//   });
	// }

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	const checkLenghtkoreksiPallet = () => {
		let count_sku = $("#list_data_tambah_koreksi_pallet > tbody tr").length;
		$("#list_data_tambah_koreksi_pallet > tfoot tr #total_detail_pallet").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");

		count_sku > 0 ? $("#pilih_sku").prop('disabled', false) : $("#pilih_sku").prop('disabled', true);
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
					postData('<?= base_url('WMS/KoreksiPallet/checkKodePallet') ?>', {
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
			postData('<?= base_url('WMS/KoreksiPallet/checkKodePallet') ?>', {
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
	});

	const initDataToTableKoreksiPallet = (response) => {
		$("#pallet_id").val(response[0].pallet_id);
		$("#depo_detail_id").val(response[0].depo_detail_id);
		$("#client_wms_id").val(response[0].client_wms_id);
		$("#principle_id").val(response[0].principle_id);
		$("#koreksi_pallet_gudang").val(response[0].rak_lajur_detail_nama);
		$("#koreksi_pallet_kode_pallet").val(response[0].pallet_kode);
		$("#koreksi_pallet_tgl_buat_pallet").val(response[0].tgl_create);
		$("#koreksi_pallet_pembuat_pallet").val(response[0].pallet_who_create);

		if (response.length != 0) {
			$("#list_data_tambah_koreksi_pallet > tbody").empty();
			$.each(response, function(i, v) {
				$("#list_data_tambah_koreksi_pallet > tbody").append(`
            <tr>
                <td style="display:none">
                  <input type="hidden" name="pallet_detail_id" id="pallet_detail_id" value="${v.pallet_detail_id}"/>
                  <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.sku_stock_id}"/>
                  <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/>
                  <input type="hidden" name="ed_sj" id="ed_sj" value="${v.sku_stock_expired_date_sj}"/>
                  <input type="hidden" name="sku_induk_id" id="sku_induk_id" value="null"/>
                </td>
                <td class="text-center">${v.sku_kode} </td>
                <td>${v.sku_nama_produk} </td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan} </td>
                <td class="text-center">
                  <input type="date" class="form-control expired_date" name="expired_date" id="expired_date_${v.sku_id}_${i}" onkeydown="return false" value="${v.sku_stock_expired_date}" disabled/>
                </td>
                <td class="text-center">${v.qty}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" value="${v.qty}"/>
                </td>
                <td class="text-center">
                   <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" class="btn btn-danger btn-sm deletePalletDetail" disabled><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `);
			});
		}

		checkLenghtkoreksiPallet();
	}

	$(document).on("click", ".deletePalletDetail", function() {
		$(this).parent().parent().remove();

		checkLenghtkoreksiPallet()
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

	const handleOpenPilihSKU = () => {
		$("#modal_list_data_pilih_sku").modal('show');

		fetch('<?= base_url('WMS/KoreksiPallet/getAllSKU') ?>')
			.then((res) => res.json())
			.then((response) => {
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
						$('#table_list_data_pilih_sku').DataTable().destroy();
					}
					$("#table_list_data_pilih_sku > tbody").empty();

					$.each(response, function(i, v) {

						$("#table_list_data_pilih_sku > tbody").append(`
                <tr>
                    <td width="5%" class="text-center">
                      <input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' value="${v.sku_id}"/>
                    </td>
                    <td width="15%">${v.sku_induk_nama}</td>
                    <td width="25%">${v.sku_nama_produk}</td>
                    <td width="8%" class="text-center">${v.sku_kemasan}</td>
                    <td width="8%" class="text-center">${v.sku_satuan}</td>
                    <td width="10%" class="text-center">${v.principle_nama}</td>
                    <td width="10%" class="text-center">${v.principle_brand_nama}</td>
                    <td width="8%" class="text-center">${v.tgl_expired}</td>
                </tr>
            `);
					});

					$('#table_list_data_pilih_sku').DataTable({
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6, 7]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});
				} else {
					$("#table_list_data_pilih_sku > tbody").html(`<tr><td colspan="8" class="text-center text-danger">Data Kosong</td></tr>`);
				}
			});
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
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				// checkboxes[i].disabled = true;
				arr_chk.push(checkboxes[i].value);
			}
		}

		if (arr_chk.length == 0) {
			message("Error!", "Pilih data yang akan dipilih", "error");
			return false;
		} else {
			postData('<?= base_url('WMS/KoreksiPallet/getAllSKUById') ?>', {
					sku_id: arr_chk,
				}, 'POST')
				.then((response) => {
					$.each(response, function(i, v) {
						$("#list_data_tambah_koreksi_pallet > tbody").append(`
                <tr>
                    <td style="display:none">
                      <input type="hidden" name="pallet_detail_id" id="pallet_detail_id" value="null"/>
                      <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="null"/>
                      <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/>
                      <input type="hidden" name="ed_sj" id="ed_sj" value="null"/>
                      <input type="hidden" name="sku_induk_id" id="sku_induk_id" value="${v.sku_induk_id}"/>
                    </td>
                    <td class="text-center">${v.sku_kode} </td>
                    <td>${v.sku_nama_produk}</td>
                    <td class="text-center">${v.sku_satuan} </td>
                    <td class="text-center">${v.sku_kemasan}</td>
                    <td class="text-center">
                        <input type="date" class="form-control expired_date" name="expired_date" id="expired_date_${v.sku_id}_${i}" onkeydown="return false"/>
                    </td>
                    <td class="text-center">0</td>
                    <td class="text-center">
                      <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" value="0"/>
                    </td>
                    <td class="text-center">
                      <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" class="btn btn-danger btn-sm deletePalletDetail"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
					});
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
						url: "<?= base_url('WMS/KoreksiPallet/checkMinimunExpiredDate'); ?>",
						data: {
							id: arr_chk
						},
						dataType: "JSON",
						success: function(response) {
							$.each(response, function(i, v) {
								$(`#expired_date_${v.sku_id}_${i}`).val(v.date);
							})

							arr_chk = []
							$("#modal_list_data_pilih_sku").modal('hide');
							checkLenghtkoreksiPallet();
						}
					});
				}

				if (result.dismiss == 'cancel') {
					arr_chk = []
					$("#modal_list_data_pilih_sku").modal('hide');
					checkLenghtkoreksiPallet();
				}
			});

		}
	}

	const handleTutupPilihSKU = () => {
		$("#modal_list_data_pilih_sku").modal('hide');
	}

	const handleSaveDataKoreksiPallet = () => {
		let typeTransaction = $("#koreksi_pallet_tipe_transaksi").val();
		let pallet_id = $("#pallet_id").val();
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
		let finalDetailData = [];

		let error = 0;

		if (typeTransaction == "") {
			message("Error!", "Tipe Transaksi boleh kosong", "error");
			error = 1;
			return false;
		} else {
			$("#list_data_tambah_koreksi_pallet > tbody tr").each(function() {
				let palletDetailId = $(this).find("td:eq(0) input[name='pallet_detail_id']")
				let skuStockId = $(this).find("td:eq(0) input[name='sku_stock_id']")
				let skuId = $(this).find("td:eq(0) input[name='sku_id']")
				let skuIndukId = $(this).find("td:eq(0) input[name='sku_induk_id']")
				let expiredDateSj = $(this).find("td:eq(0) input[name='ed_sj']")
				let expiredDate = $(this).find("td:eq(5) input[type='date']")
				let qtyAvailable = $(this).find("td:eq(6)")
				let qtyPlan = $(this).find("td:eq(7) input[type='text']")

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
			});
		}



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
					qtyPlan: arrQtyPlan[index]
				});
			}
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Pastikan data yang sudah anda input benar!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postData('<?= base_url('WMS/KoreksiPallet/saveData') ?>', {
						pallet_id,
						depo_detail_id,
						client_wms_id,
						principle_id,
						typeTransaction,
						keterangan,
						finalDetailData
					}, 'POST')
					.then((response) => {
						if (response == true) {
							message_topright("success", "Data berhasil ditambahkan")
							setTimeout(() => {
								Swal.fire({
									title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
									showConfirmButton: false,
									timer: 500
								});
								location.href = "<?= base_url('WMS/KoreksiPallet/KoreksiPalletMenu') ?>";
							}, 500);
						}
					});
			}
		});
	}

	const handleBackKoreksiPallet = () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/KoreksiPallet/KoreksiPalletMenu') ?>";
		}, 500);
	}
</script>