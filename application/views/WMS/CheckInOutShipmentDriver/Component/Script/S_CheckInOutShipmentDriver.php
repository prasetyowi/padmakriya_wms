<script>
	let arrFileFoto = [];
	const urlSearchParams = new URLSearchParams(window.location.search);
	const shipmentDriverId = Object.fromEntries(urlSearchParams.entries()).id;

	loadingBeforeReadyPage()

	$(document).ready(function() {

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		let date = new Date();

		const month = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

		if ("<?= $this->uri->segment(3) ?>" == 'CheckInOutShipmentDriverMenu') {
			$('#filterTglMasuk').daterangepicker({
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

		if ("<?= $this->uri->segment(3) ?>" == 'add') {
			$('.datetimepicker').datetimepicker({
				format: 'DD-MM-YYYY HH:mm'
			});

			if (typeof shipmentDriverId !== "undefined") {
				if ($("#isConfirmDeparture").val() == 1) {
					$('#kmKembali').prop('disabled', false);
				} else {
					$('#kmKembali').prop('disabled', true);
				}
			} else {
				$('#kmKembali').prop('disabled', true);
			}
		}

		if (typeof shipmentDriverId !== "undefined") {
			// $(".btn-konfirmasi").show();
			// $(".btn-konfirmasiKeberangkatan").show();
			$('#nopol').trigger('change');
			// setTimeout(() => $('#namaDriver').trigger('change'), 1000)
		} else {
			$("#jamTrukKeberangkatan").val(date.getDate() + '-' + month[date.getMonth()] + '-' + date
				.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
		}



		$(".select2").select2({
			width: "100%"
		});

		$('#dataInboundSupplier').DataTable();


	})

	/** Global Variable */


	// const message = (msg, msgtext, msgtype) => {
	//     Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
	//     const Toast = Swal.mixin({
	//         toast: true,
	//         position: "top-end",
	//         showConfirmButton: false,
	//         timer: 3000,
	//         didOpen: (toast) => {
	//             toast.addEventListener("mouseenter", Swal.stopTimer);
	//             toast.addEventListener("mouseleave", Swal.resumeTimer);
	//         },
	//     });

	//     Toast.fire({
	//         icon: type,
	//         title: msg,
	//     });
	// }


	async function postDataRequest(url = '', data = {}, type) {
		if (type == "GET") {
			const response = await fetch(url);

			if (!response.ok) return response
			return response.json();
		} else {
			const response = await fetch(url, {
				method: type,
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			});

			if (!response.ok) return response
			return response.json();
		}

		// Default options are marked with *

	}

	/** Global Variable */


	/** Halaman Depan */

	const handlerFilterData = () => {
		$("#loadingfilter").show();
		postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/filteredData') ?>', {
				tglMulai: $('#filterTglMasuk').val(),
				kode: $('#kodeLogSecurity').val()
			}, 'POST')
			.then((response) => {
				$("#loadingfilter").hide();
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#dataInboundSupplier')) {
						$('#dataInboundSupplier').DataTable().destroy();
					}
					$('#dataInboundSupplier > tbody').empty();
					$.each(response, function(i, v) {

						let action = "";
						if (v.tglKeluar != "-") {
							action += `<button type="button" style="border:none;background:transparent" title="view data" onclick="handlerViewData('${v.id}')"><i class="fas fa-eye text-primary"></i></button>`;
						} else {
							if (v.is_confirm_departure != 1) {
								//bisa delete, edit dan konfirmasi
								action += `<button type="button" style="border:none;background:transparent" title="konfirmasi berangkat" onclick="handlerKonfirmasiBerangkat('${v.id}', 'home')"><i class="fa-solid fa-truck-fast text-primary" ></i></button>`;
								action += `<button type="button" style="border:none;background:transparent" title="edit data" onclick="handlerEditData('${v.id}')"><i class="fas fa-pencil text-warning"></i></button>`;
								action += `<button type="button" style="border:none;background:transparent" title="hapus data" onclick="handlerDeleteData('${v.id}')"><i class="fas fa-trash text-danger"></i></button>`;
							} else if (v.is_confirm_departure == 1) {
								//bisa view saja
								action += `<button type="button" style="border:none;background:transparent" title="view data" onclick="handlerViewData('${v.id}')"><i class="fas fa-eye text-primary"></i></button>`;
								action += `<button type="button" style="border:none;background:transparent" title="konfirmasi data" onclick="handlerKonfirmasiData('${v.id}')"><i class="fas fa-check text-success"></i></button>`;
							}
						}


						$("#dataInboundSupplier > tbody").append(`
                            <tr class="text-center">
                                <td>${i + 1}</td>
                                <td>${v.kode}</td>
                                <td>${v.karyawan_nama}</td>
                                <td>${v.kendaraan_nopol}</td>
                                <td>${v.tglMasuk}</td>
                                <td>${v.tglKeluar}</td>
                                <td>${action}</td>
                            </tr>
                        `)

					});

				} else {
					$("#dataInboundSupplier > tbody").html(
						`<tr><td colspan="8" class="text-center text-danger">Data Kosong</td></tr>`);
				}
				$('#dataInboundSupplier').DataTable();

			});
	}

	const handlerKonfirmasiBerangkat = (id, mode) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "ingin konfirmasi keberangkatan ini!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Konfirmasi",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/KonfirmasiBerangkat') ?>', {
						id
					}, 'POST')
					.then((response) => {
						if (response == 1) {
							message_topright('success', 'Konfirmasi Keberangkatan Berhasil');
							if (mode == 'home') {
								$(`#dataInboundSupplier > tbody`).fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									handlerFilterData().click();
								});
							}

							if (mode == 'edit') {
								setTimeout(() => {
									Swal.fire({
										title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
										showConfirmButton: false,
										timer: 1000
									});
									location.href = "<?= base_url('WMS/CheckInOutShipmentDriver/CheckInOutShipmentDriverMenu') ?>";
								}, 1000);
							}
						} else {
							message_topright('error', 'Konfirmasi Keberangkatan Gagal');
							return false;
						}
					})
			}
		})
	}

	const handlerNopolSelected = (value) => {
		let kendaraanValue = value.split(',');
		if (value == "") {
			message('Error!', 'Pilih Nopol dengan benar!', 'error');
			return false;
		}
		postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/requestGetKaryawanByNopol') ?>', {
				kendaraanId: kendaraanValue[0]
			}, 'POST')
			.then((response) => {
				let html = "";
				$("#namaDriver").empty();
				html += "<option value=''>--Pilih Driver--</option>";
				if (response.length > 0) {
					$.each(response, function(i, v) {
						if (kendaraanValue[1] != "null") {
							if (kendaraanValue[1] == v.id) {
								html += `<option value="${v.id}" selected>${v.nama}</option>`;
							} else {
								html += `<option value="${v.id}">${v.nama}</option>`;
							}
						} else {
							html += `<option value="${v.id}">${v.nama}</option>`;
						}
					})
				}
				$("#namaDriver").append(html)
			});
	}

	const handlerDriverSelected = (value) => {
		// let driverValue = value.split(',');
		if (value == "") {
			message('Error!', 'Pilih Driver dengan benar!', 'error');
			return false;
		}
		postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/requestGetDeleveryOrderBatch') ?>', {
				karyawanId: value
			}, 'POST')
			.then((response) => {
				$("#initListDeliveryOrderBatch").empty();
				let html = "";
				html += `<h4 style="margin-left: 10px;">List Surat Tugas Pengiriman</h4>`;
				response.deliveryOrderBatch.map((item) => {
					return html += `<div class="col-xl-6 col-md-6 col-sm-6 col-xs-6">
                      <div class="listDeliveryOrderBatch" data-id="${item.id}" style="background: #1e293b;color:white;text-align: center;padding: 10px;border: 1px solid #2A3F54;border-radius: 10px 10px;margin-bottom: 10px;font-weight:700">${item.kode}</div>
                  </div>`;
				})
				$("#initListDeliveryOrderBatch").append(html);
				$("#noHandphone").val(response.karyawan.phone)
			});
	}

	const handlerFDJRSelected = (deliveryOrderBatchId) => {
		if (deliveryOrderBatchId == "") {
			message('Error!', 'Pilih Kode Delivery Order Batch dengan benar!', 'error');
			return false;
		}
		postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/requestGetNopolByFDJR') ?>', {
				deliveryOrderBatchId
			}, 'POST')
			.then((response) => {
				if (response) {
					$("#nopol").val(response.nopol)
				}
			});
	}

	const handlerKonfirmasiData = (shipmentDriverId) => {

		$("#modalAddKmKembali").modal('show')
		$(".btnKonfirmasi").attr('data-id', shipmentDriverId)

	}

	const handlerCloseKonfirmasiDriverShipment = (event) => {

		$("#modalAddKmKembali").modal('hide')
		$("#kmKembaliModal").val('')

	}

	const handlerKonfirmasiDriverShipment = (event) => {
		const shipmentDriverId = event.currentTarget.getAttribute('data-id');
		const kmKembali = $("#kmKembaliModal").val();

		if (kmKembali == "") {
			message("Error!", "Km Kembali harus diisi, simpan dan lakukan konfirmasi kembali", "error");
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "ingin konfirmasi data ini!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Konfirmasi",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/konfirmasiData') ?>', {
						shipmentDriverId,
						kmKembali
					}, 'POST')
					.then((response) => {
						if (response.status) {
							message_topright('success', response.message)
							$("#modalAddKmKembali").modal('hide')
							$("#kmKembaliModal").val('')
							$(`#dataInboundSupplier > tbody`).fadeOut("slow", function() {
								$(this).hide();
							}).fadeIn("slow", function() {
								$(this).show();
								handlerFilterData().click();
							});
						} else {
							message('Error!', response.message, 'error');
						}
					});
			}
		});
	}

	const handlerEditData = (shipmentDriverId) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});

			location.href = "<?= base_url('WMS/CheckInOutShipmentDriver/add?id=') ?>" + shipmentDriverId;

		}, 500);

	}

	const handlerDeleteData = (shipmentDriverId) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "ingin hapus data ini!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Hapus",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/deleteData') ?>', {
						shipmentDriverId
					}, 'POST')
					.then((response) => {
						if (response) {
							message_topright('success', 'delete data berhasil')
							$(`#dataInboundSupplier > tbody`).fadeOut("slow", function() {
								$(this).hide();
							}).fadeIn("slow", function() {
								$(this).show();

								handlerFilterData().click();
							});
						}
					});
			}
		});

	}

	const handlerViewData = (shipmentDriverId) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});

			location.href = "<?= base_url('WMS/CheckInOutShipmentDriver/view?id=') ?>" + shipmentDriverId;

		}, 500);
	}

	/** Halaman Depan */


	function previewFile(e) {

		imagesPreview(e, 'div.show-file');
		$("#fileFoto").val("")

		// const file = document.querySelector('input[type=file]').files.length;

		// const reader = new FileReader();

		// $('.show-file').empty()
		// reader.addEventListener("load", function() {
		//   let result = reader.result;
		//   let idx = $('.img-view').length;

		//   $('.show-file').append(`
		//         <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_${idx}" style="margin-bottom: 20px;">
		//           <div class="img-view">
		//             <div class="delete" onclick="handlerRemove(${idx})">X</div>
		//             <img src="${result}" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">  
		//           </div>
		//         </div>
		//     `);
		//   // preview.src = reader.result;
		// }, false);

		// if (file) {
		//   reader.readAsDataURL(file);
		// }
	}

	const imagesPreview = function(input, placeToInsertImagePreview) {

		if (input.files) {
			const filesAmount = input.files.length;

			for (let i = 0; i < filesAmount; i++) {
				// let idx = 0;


				const reader = new FileReader();
				reader.fileName = input.files[i]
				reader.addEventListener("load", function(event) {
					// let result = reader.result;
					const idx = $('.img-view').length;

					arrFileFoto.push({
						idx,
						files: event.target.fileName
					})

					$('.show-file').append(`
                <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_${idx}" style="margin-bottom: 20px;">
                  <div class="img-view">
                    <div class="delete" onclick="handlerRemove(${idx})">X</div>
                    <img src="${event.target.result}" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">  
                  </div>
                </div>
            `);
					// preview.src = reader.result;
				}, false);

				// reader.onload = function(event) {
				//   let idx = $('.img-view').length;

				//   console.log(input.files[i]);

				//   $('.show-file').append(`
				//         <div class="col-md-12 col-sm-12 col-xs-12 counterDiv" id="counterDiv_${idx}" style="margin-bottom: 20px;">
				//           <div class="img-view">
				//             <div class="delete" onclick="handlerRemove(${idx})">X</div>
				//             <img src="${event.target.result}" class="img-fluid img-responsive" style="width:100%; height: 50vh;border-radius: 10px">  
				//           </div>
				//         </div>
				//     `);
				//   // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
				// }

				reader.readAsDataURL(input.files[i]);
			}

		}

	};

	function handlerRemove(counter) {

		const filterData = arrFileFoto.filter((item) => item.idx !== counter);

		arrFileFoto.length = 0;
		filterData.forEach((item, index) => {
			arrFileFoto.push({
				idx: index,
				files: item.files
			})
		})

		const rowDiv = document.getElementById("counterDiv_" + counter);
		rowDiv.remove();
		$("#fileFoto").val("")

		$(".counterDiv").each(function(i, v) {
			let divDelete = $(this).closest('div').children('div').children('div');

			$(this).attr('id', `counterDiv_${i}`);
			divDelete.attr('onclick', `handlerRemove(${i})`)
		})
	}

	$(document).on('click', '.delete', function() {
		let dataImage = $(this).attr('data-img');

		if (typeof dataImage !== 'undefined') {
			postDataRequest('<?= base_url('WMS/CheckInOutShipmentDriver/deleteImageById') ?>', {
					dataImage
				}, 'POST')
				.then((response) => {});
		}
	})

	const handlerSaveData = (mode) => {
		let kendaraanId = $("#nopol").val();
		let karyawanId = $("#namaDriver").val();
		let noHandphone = $("#noHandphone").val();
		let tglTrukKeberangkatan = $("#tglTrukKeberangkatan").val();
		let jamTrukKeberangkatan = $("#jamTrukKeberangkatan").val();
		let kmKeberangkatan = $("#kmKeberangkatan").val();
		let kmKembali = $("#kmKembali").val();
		let keterangan = $("#keterangan").val();

		const listDeliveryOrderBatch = $(".listDeliveryOrderBatch").map((idx, event) => {
			return event.dataset.id
		}).get();


		if (kendaraanId == "") {
			message("Error!", "Nopol tidak boleh kosong", "error");
			return false;
		}

		if (karyawanId == "") {
			message("Error!", "Driver tidak boleh kosong", "error");
			return false;
		}

		if (kmKeberangkatan == "") {
			message("Error!", "Km Keberangkatan tidak boleh kosong", "error");
			return false;
		}

		// if (typeof shipmentDriverId !== 'undefined') {
		//   if (kmKembali == "") {
		//     message("Error!", "Km Kembali tidak boleh kosong", "error");
		//     return false;
		//   }
		// }


		let formData = new FormData();
		formData.append('shipmentDriverId', typeof shipmentDriverId !== "undefined" ? shipmentDriverId : '');
		formData.append('kendaraanId', kendaraanId);
		formData.append('karyawanId', karyawanId);
		formData.append('noHandphone', noHandphone);
		formData.append('tglTrukKeberangkatan', tglTrukKeberangkatan);
		formData.append('jamTrukKeberangkatan', jamTrukKeberangkatan);
		formData.append('kmKeberangkatan', kmKeberangkatan);
		formData.append('kmKembali', kmKembali);
		formData.append('keterangan', keterangan);
		formData.append('listDeliveryOrderBatch', listDeliveryOrderBatch);
		formData.append('mode', mode);
		// formData.append('file', $('#fileFoto')[0].files[0]);
		if (arrFileFoto.length > 0) {
			$.each(arrFileFoto, function(i, v) {
				formData.append('files[]', v.files);
			})
		} else {
			formData.append('files', null);
		}


		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/CheckInOutShipmentDriver/saveData'); ?>",
			data: formData,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(response) {
				if (response.status) {
					message_topright('success', response.message)
					setTimeout(() => {
						Swal.fire({
							title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
							showConfirmButton: false,
							timer: 1000
						});
						location.href = "<?= base_url('WMS/CheckInOutShipmentDriver/add?id=') ?>" +
							response.shipmentId;
					}, 1000);
				} else {
					message('Error!', response.message, 'error');
				}
			}
		});
	}

	const handlerBackData = () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/CheckInOutShipmentDriver/CheckInOutShipmentDriverMenu') ?>";
		}, 500);
	}
</script>