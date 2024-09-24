<script>
	const urlSearchParams = new URLSearchParams(window.location.search);
	const typeOpname = Object.fromEntries(urlSearchParams.entries()).typeSk;
	const prosesId = Object.fromEntries(urlSearchParams.entries()).prosesId;
	const status = Object.fromEntries(urlSearchParams.entries()).status;
	const depoDetailId = Object.fromEntries(urlSearchParams.entries()).depoDetailId;

	const currentUrl = window.location.href.split('?')[0].split('/')[6];

	let arrDetailTemp = [];

	let html5QrCode = "";

	if (currentUrl == "ProsesDataOpname") {
		html5QrCode = new Html5Qrcode("preview");
	}

	// console.log(html5QrCode.isScanning);
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$('#listTableDaftarSuratKerja').DataTable({
			responsive: true
		});

		$(".select2").select2({
			width: "100%"
		});

		$(".actionHeader").show()
		$(".actionBody").show()

		/** --------------------------------------- Untuk detail list data Card paling bawah -------------------------------- */

		if (typeof typeOpname !== 'undefined') {
			$("#title-type").html(typeOpname);
		}

		initDataCardDetail();

		/** --------------------------------------- End Untuk detail list data Card paling bawah -------------------------------- */

		/** Proses Baru */

		/** --------------------------------------- Untuk Proses Opname By Surat Kerja -------------------------------- */

		/** --------------------------------------- End Untuk Proses Opname By Surat Kerja -------------------------------- */

		/** Proses Baru */

		/** --------------------------------------- Untuk Proses Opname By Rak -------------------------------- */

		if (typeof status !== 'undefined') {
			$("#statusProsesOpnameDetail").html(status);
		}

		countLenghtInitDataPalletDetail();
		countLenghtInitDataPallet();

		/** --------------------------------------- End Untuk Proses Opname By Rak -------------------------------- */
	});

	/** --------------------------------------- Untuk Global ------------------------------------------- */

	// const message = (msg, msgtext, msgtype) => {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
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

	async function postData1(url = '', data = {}, type) {
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

	$(document).on("input", ".numeric", function(event) {
		this.value = this.value.replace(/[^\d.]+/g, '');
	});


	const handlerKembaliDetailData = () => {
		location.href = "<?= base_url('WMS/ProsesOpname/ProsesOpnameMenu') ?>";
	}

	const handlerkembaliDetailProses = () => {
		location.href = "<?= base_url('WMS/ProsesOpname/ProsesOpnameMenu') ?>"
	}

	/** --------------------------------------- End Untuk Global ------------------------------------------- */

	/** Proses Baru */

	/** --------------------------------------- Untuk detail list data Card paling bawah -------------------------------- */
	function chkAllBtnDel(e) {
		if (e.checked == true) {
			$('.child-parent-data-proses').each(function(index) {
				var cek = $(this).find('.btnDeleteOpnameDetail').attr('data-status');
				if (cek != "Completed") {
					$(this).find('.btnDeleteOpnameDetail').removeAttr('disabled', true);
				}
			});

		} else {
			$('.btnDeleteOpnameDetail').attr('disabled', true);
		}
	}

	function btnDeleteOpnameDetailRow(tr_opname_plan_detail_id) {
		postData1('<?= base_url('WMS/ProsesOpname/deleteOpnameDetailRow') ?>', {
				tr_opname_plan_detail_id
			}, 'POST')
			.then((response) => {
				$('#data-' + tr_opname_plan_detail_id).remove();

				$('.child-parent-data-proses').each(function(index) {
					$(this).find('.number').text(index + 1);
				});
			});
	}

	const handlerDaftarSuratKerjaDetail = (type) => {
		let typeData = (type == "PengeluaranBarang") ? "Pengeluaran barang" : (type == "MutasiPallet") ? "Mutasi Pallet" : (type == "KoreksiBarang") ? "Koreksi Stok barang" : (type == "Pemusnahan") ? "Pemusnahan" : (type == "PermintaanBarang") ? "Permintaan Barang" : (type == "PengemasanBarang") ? "Pengemasan Barang" : (type == "Koreksi Pallet") ? "Koreksi Pallet" : "Stock Opname";


		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});

			location.href = "<?= base_url('WMS/ProsesOpname/DetailData?typeSk=') ?>" + typeData;

		}, 500);
	}

	const initDataCardDetail = () => {

		if (typeof typeOpname !== 'undefined') {
			postData1('<?= base_url('WMS/ProsesOpname/getDaftarSuratKerjaDetail') ?>', {
					typeOpname
				}, 'POST')
				.then((response) => {
					$("#showDataDetail").empty()
					$.each(response, function(i, v) {
						$("#showDataDetail").append(`
              <div class="card-detail-opname">
                <table width="100%">
                  <tbody>
                    <tr>
                      <td width="39%"><label name="CAPTION-KODEDOKUMEN">Kode Dokumen</label></td>
                      <td width="2%" class="text-center">:&nbsp;</td>
                      <td width="59%">${v.kode}</td>
                    </tr>
                    <tr>
                      <td><label name="CAPTION-TIPESTOCKOPNAME">Tipe Stock Opname</label></td>
                      <td class="text-center">:&nbsp;</td>
                      <td>${v.tipe_stock}</td>
                    </tr>
                    <tr>
                      <td><label name="CAPTION-PT">Perusahaan</label></td>
                      <td class="text-center">:&nbsp;</td>
                      <td>${v.perusahaan}</td>
                    </tr>
                    <tr>
                      <td><label name="CAPTION-PRINCIPLE">principle</label></td>
                      <td class="text-center">:&nbsp;</td>
                      <td>${v.principle}</td>
                    </tr>
                    <tr>
                      <td><label name="CAPTION-JENISSTOCK">Jenis Stock</label></td>
                      <td class="text-center">:&nbsp;</td>
                      <td>${v.jenis_stok}</td>
                    </tr>
                    <tr>
                      <td><label name="CAPTION-STATUS">Status</label></td>
                      <td class="text-center">:&nbsp;</td>
                      <td>${v.status}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="text-right">
                        <button class="btn btn-primary btn-sm" onclick="handleLaksanakanOpname('${v.id}')"><label name="CAPTION-LAKSANAKAN">Laksanakan</label></button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            `)
					})

					Translate();
				});
		}
	}

	const handleLaksanakanOpname = (id) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "untuk laksanakan Opname?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Yakin",
			cancelButtonText: "Tidak, Tutup"
		}).then((response) => {
			if (response.value == true) {
				postData1('<?= base_url('WMS/ProsesOpname/updateLaksanakanOpname') ?>', {
						id
					}, 'POST')
					.then((response) => {
						if (response) {
							message_topright("success", "berhasil melaksanakan opname")
							setTimeout(() => {
								location.href = "<?= base_url('WMS/ProsesOpname/ProsesOpnameMenu') ?>";
							}, 1000)
						}
					});

			}
		});
	}

	/** --------------------------------------- End Untuk detail list data Card paling bawah -------------------------------- */

	/** Proses Baru */

	/** --------------------------------------- Untuk Proses Opname By Surat Kerja -------------------------------- */

	const handlerProsesDataOpname = (id, status, depo_detail_id) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/ProsesOpname/ProsesDataOpname?prosesId=') ?>" + id + "&status=" + status + "&depoDetailId=" + depo_detail_id;
		}, 500);

	}
	/** fungsi cetak */
	const cetakOpname = (id, status, depo_detail_id) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			window.open("<?= base_url('WMS/ProsesOpname/CetakData?id=') ?>" + id + "&status=" + status + "&depoDetailId=" + depo_detail_id);
		}, 500);
	}

	/** --------------------------------------- End Untuk Proses Opname By Surat Kerja -------------------------------- */

	/** Proses Baru */

	/** --------------------------------------- Untuk Proses Opname By Rak -------------------------------- */

	// const handlerProsesOpnameByRak = (opnameId, id, rak_lajur_nama, status, depo_detail_id) => {
	//   setTimeout(() => {
	//     Swal.fire({
	//       title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
	//       showConfirmButton: false,
	//       timer: 500
	//     });
	//     location.href = "<?= base_url('WMS/ProsesOpname/ProsesDataOpnameByRak?opnameId=') ?>" + opnameId + "&opnameDetailId=" + id + "&rakLajurNama=" + rak_lajur_nama + "&status=" + status + "&depoDetailId=" + depo_detail_id;
	//   }, 500);
	// }


	const handlerOpenModalScan = (type, skuId, index) => {
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading</span>',
			timer: 700,
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
			if (result.dismiss === Swal.DismissReason.timer) {
				// event.currentTarget.disabled = false
				$("#modalScan").modal("show");
				$("#tempValForScan").val(type);
				$("#SkuIdValForScan").val(skuId);
				$("#indexValForScan").val(index);

				if (type == "lokasi") {
					$("#modalScan .modal-title").html('<label name="CAPTION-SCANLOKASI">Scan Lokasi</label>');

					// $(".wrapper").show();
					// $(".option-2").show();
				}

				if (type == "pallet") {
					$("#modalScan .modal-title").html('<label name="CAPTION-SCANPALLETE">Scan Pallet</label>');

					// $(".wrapper").show();
					// $(".option-2").show();
				}

				if (type == "newPallet") {
					$("#modalScan .modal-title").html('<label name="CAPTION-SCANPALLETE">Scan Pallet</label>');

					// $(".wrapper").show();
					// $(".option-2").show();
				}

				if (type == "barcode") {
					$("#modalScan .modal-title").html('<label>Scan Barcode SKU</label>');

					// $(".wrapper").show();
					// $(".option-2").show();
				}

				if (type == "addScanSku") {
					$("#modalScan .modal-title").html('<label>Scan SKU</label>');

					// $(".wrapper").hide();
					// $(".option-2").hide();

					// $('#closeModal-1').show();
					// $('#closeModal-2').hide();

					// add scan sku 
					// 	Swal.fire({
					// 		title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat Kamera</span>',
					// 		timer: 1000,
					// 		timerProgressBar: true,
					// 		showConfirmButton: false,
					// 		allowOutsideClick: false,
					// 		didOpen: () => {
					// 			Swal.showLoading();
					// 			const b = Swal.getHtmlContainer().querySelector('b')
					// 			timerInterval = setInterval(() => {
					// 				b.textContent = Swal.getTimerLeft()
					// 			}, 100)
					// 		},
					// 		willClose: () => {
					// 			clearInterval(timerInterval)
					// 		}
					// 	}).then((result) => {
					// 		/* Read more about handling dismissals below */
					// 		if (result.dismiss === Swal.DismissReason.timer) {
					// 			$(`#preview`).show();

					// 			//handle succes scan
					// 			const qrCodeSuccessCallback = (decodedText, decodedResult) => {
					// 				let temp = decodedText;
					// 				if (temp != "") {

					// 					html5QrCode.pause()
					// 					requestCheckScanKode(decodedText, typeScan, null, null, null);
					// 				}
					// 			};

					// 			// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
					// 			const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
					// 				let minEdgePercentage = 0.9; // 90%
					// 				let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
					// 				let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
					// 				return {
					// 					width: qrboxSize,
					// 					height: qrboxSize
					// 				};
					// 			}

					// 			const config = {
					// 				fps: 10,
					// 				qrbox: qrboxFunction,
					// 				// rememberLastUsedCamera: true,
					// 				// Only support camera scan type.
					// 				supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
					// 			};

					// 			Html5Qrcode.getCameras().then(devices => {
					// 				$("#checkCameraIsRunning").val("1");
					// 				if (devices && devices.length) {
					// 					$.each(devices, function(i, v) {
					// 						$(`#select_kamera`).append(`
					//     <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
					//     <label class="for-checkbox-tools" for="tool-${i}">
					//         ${v.label}
					//     </label>
					// `)
					// 					});

					// 					$(`#select_kamera :input[name='tools']`).each(function(i, v) {
					// 						if (i == 0) {
					// 							$(this).attr('checked', true);
					// 						}
					// 					});

					// 					let cameraId = devices[0].id;
					// 					// html5QrCode.start(devices[0]);
					// 					$('input[name="tools"]').on('click', function() {
					// 						// alert($(this).val());
					// 						html5QrCode.stop();
					// 						html5QrCode.start({
					// 							deviceId: {
					// 								exact: $(this).val()
					// 							}
					// 						}, config, qrCodeSuccessCallback);
					// 					});
					// 					//start scan
					// 					html5QrCode.start({
					// 						deviceId: {
					// 							exact: cameraId
					// 						}
					// 					}, config, qrCodeSuccessCallback);

					// 				}
					// 			}).catch(err => {
					// 				$("#checkCameraIsRunning").val("0");
					// 				message("Error!", "Kamera tidak ditemukan", "error");
					// 				$('#closeModal-1').hide();
					// 				$('#closeModal-2').show();
					// 				// handlerCloseScan()
					// 				// html5QrCode.stop();
					// 				// $(`#start_scan_${type}`).show();
					// 				// $(`#stop_scan_${type}`).hide();
					// 			});
					// 		}
					// 	});

					// 	$(`#preview_input_manual`).hide();
					// 	$(`#kode_barcode_auto`).val("");
				}

				Translate();
			}
		})

	}

	const handleTypeScan = (event) => {
		const chooseScan = parseInt(event.currentTarget.value);
		const typeScan = $("#tempValForScan").val();
		const skuId = $("#SkuIdValForScan").val();
		const index = $("#indexValForScan").val();
		// return false;
		// $('#closeModal-1').show();
		// $('#closeModal-2').hide();

		if (chooseScan === 0) {
			// scan with barcode

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
					$(`#preview`).show();

					//handle succes scan
					const qrCodeSuccessCallback = (decodedText, decodedResult) => {
						let temp = decodedText;
						if (temp != "") {

							html5QrCode.pause()
							requestCheckScanKode(decodedText, typeScan, null, null, null);
						}
					};

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
						$("#checkCameraIsRunning").val("1");
						if (devices && devices.length) {
							$.each(devices, function(i, v) {
								$(`#select_kamera`).append(`
                    <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
                    <label class="for-checkbox-tools" for="tool-${i}">
                        ${v.label}
                    </label>
                `)
							});

							$(`#select_kamera :input[name='tools']`).each(function(i, v) {
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
						$("#checkCameraIsRunning").val("0");
						message("Error!", "Kamera tidak ditemukan", "error");
						// $('#closeModal-1').hide();
						// $('#closeModal-2').show();
						// handlerCloseScan()
						// html5QrCode.stop();
						// $(`#start_scan_${type}`).show();
						// $(`#stop_scan_${type}`).hide();
					});
				}
			});

			$(`#preview_input_manual`).hide();
			$(`#kode_barcode_auto`).val("");
		} else {
			document.getElementById('konten-table').innerHTML = "";

			if ($("#checkCameraIsRunning").val() == "1") {
				html5QrCode.stop();
				$("#checkCameraIsRunning").val("0");
			}

			if (typeScan == "addScanSku") {
				$("#filterPrincipleSKU").html("");

				fetch('<?= base_url('WMS/ProsesOpname/getPrinciple?tr_opname_plan_id='); ?>' + prosesId + '')
					.then(response => response.json())
					.then((results) => {
						$("#filterPrincipleSKU").append(`
						<option value="">--Pilih Principle--</option>
						`)

						$.each(results.data, function(i, v) {
							var selected = "";
							if (results.principle_id != "" || results.principle_id != null) {
								if (results.principle_id == v.principle_id) {
									selected = "selected";
									$("#filterPrincipleSKU").attr("disabled", true);
								}
							}

							$("#filterPrincipleSKU").append(`
							<option ${selected} value="${v.principle_id}">${v.principle_nama}</option>
							`)
						})
					});


				$("#principleSKU").show();


			} else {
				$("#principleSKU").hide()
			}

			if (typeScan !== 'barcode') {
				$("#scanWithAutoComplete").show()
				$("#scanWithoutAutoComplete").hide()
				$("#kode_barcode_auto").val('')
				$("#kode_barcode_auto").val('')
			} else {
				$("#scanWithAutoComplete").hide()
				$("#scanWithoutAutoComplete").show()
				$("#kode_barcode_auto").val('')
				$("#kode_barcode_auto").val('')
			}

			$(`#preview_input_manual`).show();
			$(`#preview`).hide();
			$(`#select_kamera`).empty();
		}
	}

	const handlerCloseModalScan = () => {
		const chkType = $("input[name*='selectSplit']").map(function() {
			if (this.checked == true) {
				return this.value
			};
		}).get().toString();

		if (chkType == "") {
			$("#modalScan").modal('hide')
			return false;
		}

		if (chkType == '0') {
			html5QrCode.stop();
			$("#select_kamera").empty();
			// $("#modalScan").modal('hide')
		} else {
			$(`#kode_barcode_auto`).val("");
			$("#preview_input_manual").hide();
		}


		$(`input[name*='selectSplit']`).map(function() {
			return this.checked = false;
		})

		$("#modalScan").modal('hide')

	}

	const handlerCloseModalScan2 = () => {
		const chkType = $("input[name*='selectSplit']").map(function() {
			if (this.checked == true) {
				return this.value
			};
		}).get().toString();

		if (chkType == "") {
			$("#modalScan").modal('hide')
			return false;
		}

		if (chkType == '0') {
			// html5QrCode.stop();
			// $("#select_kamera").empty();
			$("#modalScan").modal('hide')
		} else {
			$(`#kode_barcode_auto`).val("");
			$("#preview_input_manual").hide();
		}


		$(`input[name*='selectSplit']`).map(function() {
			return this.checked = false;
		})

		$("#modalScan").modal('hide')

	}

	const handleCheckKode = () => {
		const typeScan = $("#tempValForScan").val();
		const kode = typeScan === "barcode" ? $(`#kode_barcode`).val() : $(`#kode_barcode_auto`).val();
		const skuId = $("#SkuIdValForScan").val();
		const index = $("#indexValForScan").val();
		const lokasi = $("#scanLokasiRak").val();

		if (typeScan === "lokasi" || typeScan === "pallet" || typeScan === "newPallet" || typeScan === "barcode") {
			if (kode == "") {
				message("Error!", `Kode ${typeScan === "lokasi" ? 'Lokasi' : typeScan === "pallet" || typeScan === "newPallet" ? 'Pallet' : 'Barcode'} tidak boleh kosong`, "error");
				return false;
			} else {
				handlereRquestToCheckKodePallet(kode, typeScan, skuId, index, lokasi);
			}
		}
	}

	function handleChecKodeByEnter(e, kode) {

		const typeScan = $("#tempValForScan").val();
		const skuId = $("#SkuIdValForScan").val();
		const index = $("#indexValForScan").val();

		if (typeScan === "lokasi" || typeScan === "pallet" || typeScan === "newPallet" || typeScan === "barcode") {
			if (e.keyCode == 13) {
				if (kode == "") {
					message("Error!", `Kode ${typeScan === "lokasi" ? 'Lokasi' : typeScan === "pallet" || typeScan === "newPallet" ? 'Pallet' : 'Barcode'} tidak boleh kosong`, "error");
					return false;
				} else {
					handlereRquestToCheckKodePallet(kode, typeScan, skuId, index);
				}
			}
		}
	}

	const handlereRquestToCheckKodePallet = (kode, typeScan, skuId, index, lokasi) => {

		$(`#loading_cek_manual`).show();

		requestCheckScanKode(kode, typeScan, skuId, index, lokasi)

	}

	const requestCheckScanKode = (kode, typeScan, skuId, index, lokasi) => {
		postData1('<?= base_url('WMS/ProsesOpname/checkKodeScan') ?>', {
				opnameId: (typeof prosesId !== 'undefined') && prosesId,
				depo_detail_id: (typeof depoDetailId !== 'undefined') && depoDetailId,
				kode,
				typeScan,
				rakNama: $('#scanLokasiRak').val() != "" ? $('#scanLokasiRak').val() : null,
				skuId,
				lokasi
			}, 'POST')
			.then((response) => {
				$(`#loading_cek_manual`).hide();
				if (typeScan == "lokasi") {
					$("#statusScanLokasi").empty();
					if (response.type == 200) {
						if (response.statusRak == "Completed") {
							$("#addNewPallet").hide()
							$("#btnSimpanProsesOpnameByRak").hide();
							$("#btnKonfirmasiProsesOpnameByRak").hide();
						} else {
							$("#addNewPallet").show()
							$("#btnSimpanProsesOpnameByRak").show();

							if (response.count == 1) {
								$("#btnKonfirmasiProsesOpnameByRak").show();
							} else {
								$("#btnKonfirmasiProsesOpnameByRak").hide();
							}

						}

						$("#initDataPalletDetailScan > tbody").empty();
						countLenghtInitDataPalletDetail()

						message_topright("success", response.message);
						$(".chkTypeScan").prop('checked', false);
						$("#modalScan").modal("hide");
						removeInput();
						$("#scanLokasiRak").val(response.kode)
						$("#statusScanLokasi").html(`<i class="fas fa-check text-success"></i> <span class="text-success">valid</span>`)
						$("#scanLokasiPallet").prop("disabled", false)

						if (response.data.length > 0) {

							initDataPallet(response.data, response.statusOpname)

						} else {
							$("#initDataPalletScan tbody").empty();
						}

						$(".parent-data-proses").empty();
						$(response.dataTrOpnamePlan).each(function(i, v) {
							$(".parent-data-proses").append(`
							<div class="child-parent-data-proses table-responsive check" style="border: none" id="data-${v.tr_opname_plan_detail_id}">
								<h6 class="number">${i + 1}</h6>
								<h6>${v.tr_opname_plan_kode}</h6>
								<h6>${v.rak_lajur_detail_nama}</h6>
								<h6 class="statusOpnameDetail">${v.status}</h6>
								<button class="btn btn-danger btnDeleteOpnameDetail" data-status="${v.status}" onclick="btnDeleteOpnameDetailRow('${v.tr_opname_plan_detail_id}')" disabled id="btnDeleteOpnameDetail"><i class="fas fa-trash"></i></button>
							</div>
							`);
						})
					}

					if (response.type == 201) {
						message("Error!", response.message, "error");
						$("#scanLokasiRak").val(response.kode)
						$("#statusScanLokasi").html(`<i class="fas fa-xmark text-danger"></i> <span class="text-danger">Invalid</span>`)
						$("#scanLokasiPallet").prop("disabled", true)
					}
				}

				if (typeScan == "pallet") {
					$("#statusScanPallet").empty();
					if (response.type == 200) {
						message_topright("success", response.message);
						$(".chkTypeScan").prop('checked', false);
						$("#modalScan").modal("hide");
						removeInput();
						$("#scanLokasiPallet").val(response.kode)
						$("#statusScanPallet").html(`<i class="fas fa-check text-success"></i> <span class="text-success">valid</span>`)

						$(`#initDataPalletScan`).fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataPallet(response.data, response.statusOpname);
						});
					}

					if (response.type == 201) {
						message("Error!", response.message, "error");
						$("#scanLokasiPallet").val(response.kode)
						$("#statusScanPallet").html(`<i class="fas fa-xmark text-danger"></i> <span class="text-danger">Invalid</span>`)
						// $("#initDataPalletScan tbody").empty();
						// $("#initDataPalletScan tbody").append(`<tr class="text-center"><td colspan="5" class="text-danger">Not data available</td></tr>`)
					}
				}

				if (typeScan == "newPallet") {
					if (response.type == 200) {
						message_topright("success", response.message);
						$(".chkTypeScan").prop('checked', false);
						$("#modalScan").modal("hide");
						removeInput();

						let sts = (response.data.status_opname == "") ? "disabled" : (response.data.status == "" || response.data.status == "Invalid") ? "disabled" : "";
						let btnDelete = "";

						// if (typeof response.data.is_new_pallet !== 'undefined') {
						// 	if (response.data.is_new_pallet == 1) {
						// 	} else {
						// 		btnDelete = ""
						// 	}
						// }
						btnDelete = `<button type="button" class="btn btn-danger btn-sm btndeletepallet" data-id="${response.data.tr_opname_plan_detail2_id},${response.data.pallet_id},${response.tr_opname_plan_detail_id}"><i class="fas fa-trash" style="cursor: pointer"></i></button>`;

						let idxDataPallet = $("#initDataPalletScan tbody tr").length;
						$("#initDataPalletScan tbody").append(`
              <tr class="text-center">
                  <td>${idxDataPallet + 1}</td>
                  <td>${response.data.pallet_kode}</td>
                  <td>${response.data.status}</td>
                  <td>${response.data.status_opname}</td>
                  <td>${response.data.totalSKU}</td>
                  <td>
                    <div class="row">
                      <button class="btn btn-primary btn-sm" id="handlerStartPalletOpnameDetail_${response.data.pallet_id.replaceAll("-", "")}_${response.data.tr_opname_plan_detail2_id.replaceAll("-", "")}" ${sts} onclick="handlerStartPalletOpnameDetail('${response.data.pallet_id}', '${response.data.tr_opname_plan_detail2_id}', '${response.data.pallet_kode}')"><i class="fas fa-arrow-right"></i></button>
                      ${btnDelete}
                    </div>
                    
                  </td>
              </tr>
            `);

						// countLenghtInitDataPallet();

						// $(`#initDataPalletScan`).fadeOut("slow", function() {
						//   $(this).hide();
						// }).fadeIn("slow", function() {
						//   $(this).show();
						//   initDataPallet(response.data, response.statusOpname);
						// });

						if (response.count == 1) {
							$("#btnKonfirmasiProsesOpnameByRak").show();
						} else {
							$("#btnKonfirmasiProsesOpnameByRak").hide();
						}

					}

					if (response.type == 201) {
						message("Error!", response.message, "error");
					}

					if (response.type == 202) {
						message("Error!", response.message, "error");
					}

					// if (response.type == 203) {
					// 	Swal.fire({
					// 		title: "Apakah anda yakin?",
					// 		html: response.message,
					// 		icon: "warning",
					// 		showCancelButton: true,
					// 		confirmButtonColor: "#3085d6",
					// 		cancelButtonColor: "#d33",
					// 		confirmButtonText: "Ya, Yakin",
					// 		cancelButtonText: "Tidak, Tutup"
					// 	}).then((result) => {
					// 		if (result.value == true) {
					// 			postData1('<?= base_url('WMS/ProsesOpname/updatePalletProsesOpnameByRak') ?>', {
					// 					opnameId: (typeof prosesId !== 'undefined') && prosesId,
					// 					depo_detail_id: (typeof depoDetailId !== 'undefined') && depoDetailId,
					// 					rakNama: $('#scanLokasiRak').val() != "" ? $('#scanLokasiRak').val() : null,
					// 					tr_opname_plan_detail2_id: response.data.tr_opname_plan_detail2_id
					// 				}, 'POST')
					// 				.then((response) => {
					// 					console.log(response);
					// 					if (response) {
					// 						message_topright("success", response.message);
					// 						$(".chkTypeScan").prop('checked', false);
					// 						$("#modalScan").modal("hide");
					// 						removeInput();

					// 						let sts = (response.data.status_opname == "") ? "disabled" : (response.data.status == "" || response.data.status == "Invalid") ? "disabled" : "";
					// 						let btnDelete = "";

					// 						// if (typeof response.data.is_new_pallet !== 'undefined') {
					// 						// 	if (response.data.is_new_pallet == 1) {
					// 						// 	} else {
					// 						// 		btnDelete = ""
					// 						// 	}
					// 						// }
					// 						btnDelete = `<button type="button" class="btn btn-danger btn-sm btndeletepallet" data-id="${response.data.tr_opname_plan_detail2_id},${response.data.pallet_id}"><i class="fas fa-trash" style="cursor: pointer"></i></button>`;

					// 						let idxDataPallet = $("#initDataPalletScan tbody tr").length;
					// 						$("#initDataPalletScan tbody").append(`
					// 						<tr class="text-center">
					// 							<td>${idxDataPallet + 1}</td>
					// 							<td>${response.data.pallet_kode}</td>
					// 							<td>${response.data.status}</td>
					// 							<td>${response.data.status_opname}</td>
					// 							<td>${response.data.totalSKU}</td>
					// 							<td>
					// 								<div class="row">
					// 								<button class="btn btn-primary btn-sm" id="handlerStartPalletOpnameDetail_${response.data.pallet_id.replaceAll("-", "")}_${response.data.tr_opname_plan_detail2_id.replaceAll("-", "")}" ${sts} onclick="handlerStartPalletOpnameDetail('${response.data.pallet_id}', '${response.data.tr_opname_plan_detail2_id}', '${response.data.pallet_kode}')"><i class="fas fa-arrow-right"></i></button>
					// 								${btnDelete}
					// 								</div>

					// 							</td>
					// 						</tr>
					// 						`);
					// 					}
					// 				})
					// 		}
					// 	});
					// }
				}

				if (typeScan == "barcode") {
					if (response.type == 200) {
						message("Success!", response.message, "success");
						$(".chkTypeScan").prop('checked', false);
						$("#modalScan").modal("hide");
						removeInput();
						$(`#barcode${index}`).html(response.kode)
					}
					if (response.type == 201) {
						message("Error!", response.message, "error");
					}
				}

				if (typeScan == "addScanSku") {
					if (response.type == 200) {
						message("Success!", response.message, "success");
						$(".chkTypeScan").prop('checked', false);
						$("#modalScan").modal("hide");
						removeInput();
						append_list_data_setelah_pilih_sku(response.data, null);
					}
					if (response.type == 201) {
						message("Error!", response.message, "error");
					}
				}
			});
	}

	const initDataPallet = (response, status) => {

		$("#initDataPalletScan tbody").empty();
		$.each(response, function(i, v) {

			let sts = (v.status_opname == "") ? "disabled" : (v.status == "" || v.status == "Invalid") ? "disabled" : "";
			let btnDelete = "";

			if (v.status_opname != "Completed") {
				btnDelete = `<button type="button" class="btn btn-danger btn-sm btndeletepallet" data-id="${v.tr_opname_plan_detail2_id},${v.pallet_id}"><i class="fas fa-trash" style="cursor: pointer"></i></button>`;
			}
			// if (typeof v.is_new_pallet !== 'undefined') {
			// 	if (v.status_opname != "Completed") {
			// 		if (v.is_new_pallet == 1) {
			// 		} else {
			// 			btnDelete = ""
			// 		}
			// 	} else {
			// 		btnDelete = ""
			// 	}
			// }

			$("#initDataPalletScan tbody").append(`
          <tr class="text-center">
              <td>${i + 1}</td>
              <td>${v.pallet_kode}</td>
              <td>${v.status}</td>
              <td>${v.status_opname}</td>
              <td>${v.totalSKU}</td>
              <td>
                <div class="row">
                  <button class="btn btn-primary btn-sm" id="handlerStartPalletOpnameDetail_${v.pallet_id.replaceAll("-", "")}_${v.tr_opname_plan_detail2_id.replaceAll("-", "")}" ${sts} onclick="handlerStartPalletOpnameDetail('${v.pallet_id}', '${v.tr_opname_plan_detail2_id}', '${v.pallet_kode}')"><i class="fas fa-arrow-right"></i></button>
                  ${btnDelete}
                </div>
              </td>
          </tr>
      `);
		})

		// countLenghtInitDataPallet();

		Translate();
	}

	$(document).on('click', '.btndeletepallet', function() {
		let dataProps = $(this).attr('data-id').split(',');
		let opnameDetail2Id = dataProps[0];
		let palletId = dataProps[1];
		let tr_opname_plan_detail_id = dataProps[2];
		$(this).parent().parent().parent().remove();
		postData1('<?= base_url('WMS/ProsesOpname/deleteOpnameDetail2Temp') ?>', {
				opnameId: (typeof prosesId !== 'undefined') && prosesId,
				opnameDetail2Id,
				palletId,
				tr_opname_plan_detail_id
			}, 'POST')
			.then((response) => {
				if (response == 1) {
					$("#btnKonfirmasiProsesOpnameByRak").show();
				} else {
					$("#btnKonfirmasiProsesOpnameByRak").hide();
				}

			})
		$("#initDataPalletScan tbody tr").each(function(i, v) {
			let counter = $(this).find("td:eq(0)")
			counter.html(i + 1)
		});

		$("#palletKodeData").empty();
		$("#addNewSku").hide();
		$("#addScanSku").hide();
		$("#initDataPalletDetailScan tbody").empty();

	});

	const handlerStartPalletOpnameDetail = (pallet_id, tr_opname_plan_detail2_id, pallet_kode) => {

		// console.log(isSimpan);

		$("#btnSimpanProsesOpnameByRak").attr('data-temp', JSON.stringify({
			pallet: pallet_id,
			tempId2: tr_opname_plan_detail2_id
		}))

		$("#btnKonfirmasiProsesOpnameByRak").attr('data-temp', JSON.stringify({
			pallet: pallet_id,
			tempId2: tr_opname_plan_detail2_id
		}))


		postData1('<?= base_url('WMS/ProsesOpname/getDataPalletDetailById') ?>', {
				opnameId: (typeof prosesId !== 'undefined') && prosesId,
				depo_detail_id: (typeof depoDetailId !== 'undefined') && depoDetailId,
				rakNama: $('#scanLokasiRak').val() != "" ? $('#scanLokasiRak').val() : null,
				opnameDetailId2: tr_opname_plan_detail2_id,
				pallet_id,
			}, 'POST')
			.then((response) => {

				if (response.isSimpan == '0' || response.isSimpan == 0) {
					$("#addNewSku").show();
					$("#addScanSku").show();
					$("#btnKonfirmasiProsesOpnameByRak").hide();
				} else {
					if (response.statusRak == "Completed") {
						$("#addNewSku").hide();
						$("#addScanSku").hide();
						$("#btnSimpanProsesOpnameByRak").hide();
						$("#btnKonfirmasiProsesOpnameByRak").hide();
					} else {
						$("#addNewSku").show();
						$("#addScanSku").show()
						$("#btnSimpanProsesOpnameByRak").show();
						$("#btnKonfirmasiProsesOpnameByRak").show();
					}
				}


				if ($.fn.DataTable.isDataTable('#initDataPalletDetailScan')) {
					$('#initDataPalletDetailScan').DataTable().destroy();
				}

				$("#palletKodeData").html('')
				$("#palletKodeData").html(`<strong>${pallet_kode}</strong>`);

				let arrTemp = [];
				arrTemp = []

				$("#initDataPalletDetailScan tbody").empty();
				$.each(response.data, function(i, v) {
					var button = '';
					var qty = '';

					if (response.statusRak != "Completed") {
						// button += `<button type="button" class="btndeleteskuid" data-detail3-id="${v.id}" style="border:none;background:transparent;width:100%"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>`
						if (v.principle_id == response.dataOpnamePlan.principle_id) {
							qty += `<input type="text" class="form-control numeric" value="${v.aktual_qty}"  style="width:100%"/>`
						} else {
							qty += `<input type="text" class="form-control numeric" value="${v.aktual_qty}"  style="width:100%" disabled/>`
						}

					} else {
						qty += `<input type="text" class="form-control numeric" value="${v.aktual_qty}" style="width:100%" disabled/>`
					}

					// <input type="text" class="form-control numeric" value="${v.aktual_qty}" style="width:100%" ${response.statusRak == "Completed" ? 'disabled' : ''}/>

					$("#initDataPalletDetailScan tbody").append(`
              <tr class="text-center">
                  <td>${i + 1} <input type="hidden" value="${v.sku_id}"/></td>
                  <td>${v.sku_nama_produk} <input type="hidden" value="${v.sku_stock_id}"/></td>
                  <td>${v.sku_kemasan} <input type="hidden" value="${v.id}"/></td>
                  <td>${v.sku_satuan} <input type="hidden" value="${v.sku_qty_sistem}"/></td>
                  <td>
                    <input type="text" class="form-control batchNo" id="batchNo_${v.sku_id}_${i + 1}" value="${v.sku_batch_no == null ? "" : v.sku_batch_no}" disabled/>
                  </td>
                  <td>
                    <input type="date" class="form-control expired_date" id="expired_date_${v.sku_id}_${i + 1}" value="${v.ed}" disabled/>
                  </td>
                  <td>
				  ${qty}
                  </td>
                  <td>
				  <span class="badge badge-primary" style="background:#34d399;margin-bottom:10px" id="barcode${i + 1}">${v.sku_kode_sku_principle == null ? 'kosong' : v.sku_kode_sku_principle}</span>
				  <button type="button" class="btn btn-primary btn-sm" ${response.statusRak == "Completed" ? 'disabled' : ''} onclick="handlerOpenModalScan('barcode', '${v.sku_id}', '${i + 1}')">Scan</button>
                  </td>
                  <td>${button}</td>
				  </tr>
				  `)
				})
				// countLenghtInitDataPalletDetail();
			})

	}

	const countLenghtInitDataPalletDetail = () => {
		if ($("#initDataPalletDetailScan tbody tr").length > 0) {
			$("#addNewSku").show();
			$("#addScanSku").show()
		} else {
			$("#addNewSku").hide();
			$("#addScanSku").hide()
		}
	}

	const countLenghtInitDataPallet = () => {
		if ($("#initDataPalletScan tbody tr").length > 0) {
			$("#addNewPallet").show();
		} else {
			$("#addNewPallet").hide();
		}

		// if (typeof status !== 'undefined') {
		//   if (status == 'In Progress Revision') {
		//     $("#addNewPallet").hide();
		//   }
		// }
	}

	const handlerAddNewSkuInPalletDetail = () => {

		$("#list_data_pilih_sku").modal('show');
	}

	const handlerFilterSKU = (event) => {
		const principleId = $("#filterPrincipleSKU").children("option").filter(":selected").val();

		if (principleId == "") {
			message('Error!', 'Principle tidak boleh kosong', 'error')
			return false
		}



		postData1('<?= base_url('WMS/ProsesOpname/getDataSku') ?>', {
				opnameId: (typeof prosesId !== 'undefined') && prosesId,
				principleId
			}, 'POST')
			.then((response) => {
				if (response != null) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
						$('#table_list_data_pilih_sku').DataTable().destroy();
					}
					$('#table_list_data_pilih_sku tbody').empty();
					for (i = 0; i < response.length; i++) {
						let sku_stock_id = response[i].sku_stock_id;
						let sku_id = response[i].sku_id;
						let sku = response[i].sku_nama_produk;
						let sku_kode = response[i].sku_kode;
						let sku_satuan = response[i].sku_satuan;
						let sku_kemasan = response[i].sku_kemasan;
						var str = "";

						str += `<input type="checkbox" class="form-control check-item" name="chk-data[]" id="chk-data[]" value="${sku_id}, ${sku_stock_id}"`;

						var strmenu = '';

						strmenu += '<tr>';
						strmenu += '<td class="text-center">' + str + '</td>';
						strmenu += '<td>' + sku_kode + '</td>';
						strmenu += '<td>' + sku + '</td>';
						strmenu += '<td class="text-center">' + sku_kemasan + '</td>';
						strmenu += '<td class="text-center">' + sku_satuan + '</td>';
						strmenu += '</tr>';
						$("#table_list_data_pilih_sku > tbody").append(strmenu);
					}
				} else {
					$("#table_list_data_pilih_sku > tbody").html(`<tr><td colspan="5" class="text-center text-danger">Not data available</td></tr>`);
					// message('Info!', 'Data Kosong', 'info');
				}

				$('#table_list_data_pilih_sku').DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0, 1, 2, 3, 4]
					}],
					lengthMenu: [
						[-1],
						['All']
					],
				});
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

	$(document).on("click", ".btn_pilih_sku", function() {
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data[]']");
		arr_chk.length = 0;
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				// checkboxes[i].disabled = true;
				const dataCheked = checkboxes[i].value.split(",")
				arr_chk.push({
					sku_id: dataCheked[0],
					sku_stock_id: dataCheked[1],
				});
			}
		}

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dipilih", "info");
		} else {
			postData1('<?= base_url('WMS/ProsesOpname/getDataSkuById') ?>', {
					data: arr_chk
				}, 'POST')
				.then((response) => {
					append_list_data_setelah_pilih_sku(response, arr_chk);
					$("input[name='chk-data[]']").prop('checked', false);
					$('#table_list_data_pilih_sku').dataTable().fnFilter('');
					$("#list_data_pilih_sku").modal("hide");
				})
		}
	});

	function append_list_data_setelah_pilih_sku(response, arr_chk) {
		// console.log(response);
		$.each(response, function(i, v) {
			let countData = $("#initDataPalletDetailScan tbody tr").length;
			// console.log(countData);
			$("#initDataPalletDetailScan > tbody").append(`
        <tr class="text-center">
          <td>${countData + 1} <input type="hidden" value="${v.sku_id}"/></td>
          <td>${v.sku_nama_produk} <input type="hidden" value="null"/></td>
          <td>${v.sku_kemasan} <input type="hidden" value="null"/></td>
          <td>${v.sku_satuan} <input type="hidden" value="null"/></td>
					<td>
						<input type="text" class="form-control batchNo" id="batchNo_${v.sku_id}_${countData + 1}" value=""/>
					</td>
          <td>
            <input type="date" class="form-control expired_date" name="expired_date" id="expired_date_${v.sku_id}_${countData + 1}" value="${v.exp_date}"/>
          </td>
          <td>
            <input type="text" class="form-control numeric" style="width:100%"/>
          </td>
          <td>
                <span class="badge badge-primary" style="background:#34d399;margin-bottom:10px" id="barcode${countData + 1}">${v.sku_kode_sku_principle == null ? 'kosong' : v.sku_kode_sku_principle}</span>
                <button type="button" class="btn btn-primary btn-sm" onclick="handlerOpenModalScan('barcode', '${v.sku_id}', '${countData + 1}')">Scan</button>
          </td>
          <td>
              <button type="button" class="btndeletesku" style="border:none;background:transparent;width:100%"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
          </td>
        </tr>
      `);
		});

		// Swal.fire({
		//   html: 'Apakah anda ingin menggunakan default expired date?',
		//   showCancelButton: true,
		//   confirmButtonText: 'Iya',
		//   cancelButtonText: `Tidak`,
		//   allowOutsideClick: false,
		// }).then((result) => {
		//   if (result.value) {
		//     postData1('<?= base_url('WMS/ProsesOpname/checkMinimunExpiredDate') ?>', {
		//         data: arr_chk
		//       }, 'POST')
		//       .then((response) => {
		//         $.each(response, function(i, v) {
		//           $(`#expired_date_${v.sku_id}_${v.sku_stock_id}`).val(v.date);
		//         })
		//       })
		//   }
		// });

		// var dtToday = new Date();

		// var month = dtToday.getMonth() + 1;
		// var day = dtToday.getDate();
		// var year = dtToday.getFullYear();
		// if (month < 10)
		//   month = '0' + month.toString();
		// if (day < 10)
		//   day = '0' + day.toString();

		// var maxDate = year + '-' + month + '-' + day;
		// $('.expired_date').attr('min', maxDate);

	}

	$(document).on('click', '.btndeletesku', function() {
		$(this).parent().parent().remove();
		$("#initDataPalletDetailScan tbody tr").each(function(i, v) {
			let counter = $(this).find("td:eq(0)")
			let sku_id = $(this).find("td:eq(0) input[type='hidden']").val();
			counter.html(`${i + 1} <input type="hidden" value="${sku_id}"/>`)
		});
	});

	$(document).on('click', '.btndeleteskuid', function() {
		let opnameDetail3ID = $(this).attr('data-detail3-id');
		$(this).parent().parent().remove();
		postData1('<?= base_url('WMS/ProsesOpname/deleteOpnameDetail3Temp') ?>', {
				opnameId: (typeof prosesId !== 'undefined') && prosesId,
				opnameDetail3ID
			}, 'POST')
			.then((response) => {

			})
		$("#initDataPalletDetailScan tbody tr").each(function(i, v) {
			let counter = $(this).find("td:eq(0)")
			let sku_id = $(this).find("td:eq(0) input[type='hidden']").val();
			counter.html(`${i + 1} <input type="hidden" value="${sku_id}"/>`)
		});
	});

	const handlerSimpanProsesOpnameByRak = (event) => {
		let dataTemp = JSON.parse(event.currentTarget.getAttribute('data-temp'))

		let arrIdTempDetail3 = [];
		let arrSkuId = [];
		let arrSkuStockId = [];
		let arrBatchNo = [];
		let arrEd = [];
		let arrAktualQty = [];
		let arrSkuQtySistem = [];
		let arrFixData = [];

		if ($("#initDataPalletDetailScan tbody tr").length == 0) {
			message("Error!", "Detail Opname tidak boleh kosong, minimal ada 1 data", "error");
			error = true;
			return false;
		} else {
			$("#initDataPalletDetailScan > tbody tr").each(function(index, value) {
				let sku_id = $(this).find("td:eq(0) input[type='hidden']");
				let sku_stock_id = $(this).find("td:eq(1) input[type='hidden']");
				let idDetail3Temp = $(this).find("td:eq(2) input[type='hidden']");
				let skuQtySistem = $(this).find("td:eq(3) input[type='hidden']");
				let batchNo = $(this).find("td:eq(4) input[type='text']");
				let ed = $(this).find("td:eq(5) input[type='date']");
				let aktualQty = $(this).find("td:eq(6) input[type='text']");

				if (ed.val() == '') {
					message('Error!', 'Tidak dapat simpan, Expired date tidak boleh kosong!', 'error');
					error = true;
					return false;
				} else if (aktualQty.val() == '') {
					message('Error!', 'Tidak dapat simpan, Aktual Qty tidak boleh kosong!', 'error');
					error = true;
					return false;
				} else if (aktualQty.val() == '0' || aktualQty.val() == 0) {
					message('Error!', 'Tidak dapat simpan, Aktual Qty tidak boleh 0!', 'error');
					error = true;
					return false;
				} else {
					error = false;
					idDetail3Temp.map(function() {
						arrIdTempDetail3.push($(this).val());
					}).get();

					sku_id.map(function() {
						arrSkuId.push($(this).val());
					}).get();

					sku_stock_id.map(function() {
						arrSkuStockId.push($(this).val());
					}).get();

					batchNo.map(function() {
						arrBatchNo.push($(this).val());
					}).get();

					ed.map(function() {
						arrEd.push($(this).val());
					}).get();

					aktualQty.map(function() {
						arrAktualQty.push($(this).val());
					}).get();

					skuQtySistem.map(function() {
						arrSkuQtySistem.push($(this).val());
					}).get();
				}
			});
		}

		if (error == true) return false;

		if (arrSkuId != null) {
			for (let index = 0; index < arrSkuId.length; index++) {
				arrFixData.push({
					'id_temp3': arrIdTempDetail3[index],
					'sku_id': arrSkuId[index],
					'sku_stock_id': arrSkuStockId[index],
					'batchNo': arrBatchNo[index],
					'ed': arrEd[index],
					'aktual_qty': arrAktualQty[index],
					'sku_qty_sistem': arrSkuQtySistem[index],
				});
			}

			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Ingin simpan data opname?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Yakin",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					postData1('<?= base_url('WMS/ProsesOpname/saveDataProsesOpnameByRak') ?>', {
							opnameId: (typeof prosesId !== 'undefined') && prosesId,
							rakNama: $('#scanLokasiRak').val() != "" ? $('#scanLokasiRak').val() : null,
							depo_detail_id: (typeof depoDetailId !== 'undefined') && depoDetailId,
							opnameDetailId2: dataTemp.tempId2,
							dataDetail: arrFixData
						}, 'POST')
						.then((response) => {
							if (response.type == 200) {
								message_topright('success', 'Data berhasil disimpan');
								$(`#initDataPalletScan`).fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									let kode = $('#scanLokasiRak').val().replace(/\s/g, '');
									requestCheckScanKode(kode, 'lokasi')
									// handlerStartPalletOpnameDetail(dataTemp.pallet, dataTemp.tempId2, dataTemp.pallet_kode, dataTemp.isSimpan).click();
									$(`#handlerStartPalletOpnameDetail_${dataTemp.pallet.replaceAll("-", "")}_${dataTemp.tempId2.replaceAll("-", "")}`).click();
								});

								if (response.status == "In Progress Revision") {
									$("#initDataPalletDetailScan tbody").empty();
									countLenghtInitDataPalletDetail()
									$("#palletKodeData").html('')
								} else {
									$(`#initDataPalletDetailScan`).fadeOut("slow", function() {
										$(this).hide();
									}).fadeIn("slow", function() {
										$(this).show();
										// handlerStartPalletOpnameDetail(dataTemp.pallet, dataTemp.tempId2, dataTemp.pallet_kode, dataTemp.isSimpan).click();
										$(`#handlerStartPalletOpnameDetail_${dataTemp.pallet.replaceAll("-", "")}_${dataTemp.tempId2.replaceAll("-", "")}`).click();
									});
								}


							}
							if (response.type == 201) message_topright('error', 'Data gagal disimpan');
						})
				}
			});
		}
	}

	const handlerKonfirmasiProsesOpnameByRak = (event) => {
		let dataTemp = JSON.parse(event.currentTarget.getAttribute('data-temp'));
		let error = 0;

		if ($("#initDataPalletScan tbody tr").length == 0) {
			message("Error!", "Pallet tidak boleh kosong, minimal ada 1 data", "error");
			error = 1;
			return false;
		} else {
			$("#initDataPalletScan tbody tr").each(function() {
				let totalSKU = parseInt($(this).find("td:eq(4)").html());
				if (totalSKU == 0) {
					message("Error!", "Gagal konfirmasi data! Total SKU masih ada yang kosong di pallet, silahkan proses dan simpan terlebih dahulu jika ingin melakukan konfirmasi", "error");
					error = 1;
					return false;
				} else {
					error = 0;
				}
			});
		}

		if (error == 1) return false

		if (typeof status !== 'undefined') {
			if (status == "In Progress Revision") {
				requestDataKonfirmasi(dataTemp, status);
			} else {
				if ($("#initDataPalletDetailScan tbody tr").length == 0) {
					message("Error!", "Detail Opname tidak boleh kosong, minimal ada 1 data", "error");
					return false;
				} else {

					$("#initDataPalletDetailScan tbody tr").each(function(index, value) {
						let ed = $(this).find("td:eq(5) input[type='date']");
						let aktualQty = $(this).find("td:eq(6) input[type='text']");

						if (ed.val() == '') {
							message('Error!', 'Tidak dapat simpan, Expired date tidak boleh kosong!', 'error');
							error = 1;
							return false;
						} else if (aktualQty.val() == '') {
							message('Error!', 'Tidak dapat simpan, Aktual Qty tidak boleh kosong!', 'error');
							error = 1;
							return false;
						} else if (aktualQty.val() == '0' || aktualQty.val() == 0) {
							message('Error!', 'Tidak dapat simpan, Aktual Qty tidak boleh 0!', 'error');
							error = 1;
							return false;
						}
					});

					if (error == 1) return false

					requestDataKonfirmasi(dataTemp, status);
				}
			}
		}
	}

	const requestDataKonfirmasi = (dataTemp, respone) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang akan dikonfirmasi tidak dapat diubah!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Konfirmasi",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postData1('<?= base_url('WMS/ProsesOpname/konfirmasiDataProsesOpnameByRak') ?>', {
						opnameId: (typeof prosesId !== 'undefined') && prosesId,
						rakNama: $('#scanLokasiRak').val() != "" ? $('#scanLokasiRak').val() : null,
						depo_detail_id: (typeof depoDetailId !== 'undefined') && depoDetailId,
						opnameDetailId2: respone != "In Progress Revision" ? dataTemp.tempId2 : null,
						palletId: respone != "In Progress Revision" ? dataTemp.pallet : null
					}, 'POST')
					.then((response) => {
						if (response == true) {
							message_topright('success', 'Data berhasil di konfirmasi')
							setTimeout(() => {
								Swal.fire({
									title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
									showConfirmButton: false,
									timer: 1000
								});
								location.reload();
							}, 1000);
						}

						if (response == false) message_topright('error', 'Data gagal disimpan');
					})
			}
		});
	}

	const handlerKonfirmasiProsesOpname = () => {
		var error = 0;
		$('.statusOpnameDetail').each(function() {
			var status = $(this).text();

			console.log($(this));
			if (status == 'In Progress') {
				error = 1;
				return false;
			}
		});


		if (error == 1) {
			message("Error!", "Semua lokasi wajib complete!", "error");
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang akan dikonfirmasi tidak dapat diubah!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Konfirmasi",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postData1('<?= base_url('WMS/ProsesOpname/konfirmasiDataProsesOpname') ?>', {
						opnameId: (typeof prosesId !== 'undefined') && prosesId
					}, 'POST')
					.then((response) => {
						if (response.type == 200) {
							message_topright('success', response.message)
							setTimeout(() => {
								Swal.fire({
									title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
									showConfirmButton: false,
									timer: 1000
								});
								handlerKembaliDetailData();
							}, 1000);
						}

						if (response.type == 201) message('Error!', response.message, 'error');
					})
			}
		});

	}

	//modal close untuk pilih sku
	$(".btn_close_list_data_pilih_sku").on("click", function() {
		$('#table_list_data_pilih_sku').dataTable().fnFilter('');
		$("#list_data_pilih_sku").modal("hide");
	});

	const removeScan = () => {
		$(`#preview`).hide();
		$(`#select_kamera`).empty();
		html5QrCode.stop();
	}

	const removeInput = () => {
		$(`#preview_input_manual`).hide();
		$(`#kode_barcode_auto`).val("");
	}


	document.getElementById('kode_barcode_auto').addEventListener('keyup', function() {
		const typeScan = $("#tempValForScan").val();
		const prosesId = $("#prosesId").val();
		var principleId = '';

		if (typeScan == "addScanSku") {
			principleId = $("#filterPrincipleSKU").children("option").filter(":selected").val();

			if (principleId == "") {
				message('Error!', 'Principle tidak boleh kosong', 'error')
				return false
			}
		}

		if (this.value != "") {
			fetch('<?= base_url('WMS/ProsesOpname/getKodeAutoComplete?params='); ?>' + this.value + `&type=${typeScan}&prosesId=${prosesId}&principleId=${principleId}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);

						if (typeScan == "addScanSku") {
							results.forEach(function(e, i) {
								data += `
												<tr onclick="getNoSuratJalanEks('${e.id}')" style="cursor:pointer">
														<td >${i + 1}.</td>
														<td >${e.kode}</td>
														<td >${e.nama}</td>
												</tr>
												`;
							})
						} else if (typeScan == "newPallet") {
							results.forEach(function(e, i) {
								data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
														<td class="col-xs-1">${i + 1}.</td>
														<td class="col-xs-11">${e.kode} | ${e.is_aktif == 1 ? 'Aktif' : 'Tidak Aktif'}</td>
												</tr>
												`;
							})
						} else {
							results.forEach(function(e, i) {
								data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
														<td class="col-xs-1">${i + 1}.</td>
														<td class="col-xs-11">${e.kode}</td>
												</tr>
												`;
							})
						}

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
		const typeScan = $("#tempValForScan").val();
		const skuId = $("#SkuIdValForScan").val();
		const index = $("#indexValForScan").val();
		let str = "";
		if (typeScan == 'pallet' || typeScan == 'newPallet') {
			const split = data.split('/');
			$("#kode_barcode_auto").val(split[1] + "/" + split[2]);
			str += split[1] + "/" + split[2];
		} else if (typeScan == 'addScanSku') {
			str += data;
		} else {
			$("#kode_barcode_auto").val(data);
			str += data;
		}
		document.getElementById('table-fixed').style.display = 'none'



		handlereRquestToCheckKodePallet(str, typeScan, skuId, index);
	}



	/** --------------------------------------- End Untuk Proses Opname By Rak -------------------------------- */
</script>