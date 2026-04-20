<script>
	let arrFileFoto = [];
	const urlSearchParams = new URLSearchParams(window.location.search);
	const inboundSupplierId = Object.fromEntries(urlSearchParams.entries()).id;
	loadingBeforeReadyPage()
	$(document).ready(function() {

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		let date = new Date();

		const month = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

		if ("<?= $this->uri->segment(4) ?>" == 'InboundSupplierMenu') {
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

		if ("<?= $this->uri->segment(4) ?>" == 'add') {
			$('.datetimepicker').datetimepicker({
				format: 'DD-MM-YYYY HH:mm'
			});
		}

		if (typeof inboundSupplierId !== "undefined") {
			$(".btn-konfirmasi").show();

		} else {
			$("#jamTrukMasuk").val(date.getDate() + '-' + month[date.getMonth()] + '-' + date.getFullYear() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
		}



		$(".select2").select2({
			width: "100%"
		});

		$('#dataInboundSupplier').DataTable();


	})

	/** Global Variable */


	// const message = (msg, msgtext, msgtype) => {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
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
		postDataRequest('<?= base_url('WMS/SecurityLogBook/InboundSupplier/filteredData') ?>', {
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
						if (v.tglKeluar == "-") {
							//bisa delete, edit dan konfirmasi
							action += `<button type="button" style="border:none;background:transparent" title="kondfirmasi data" onclick="handlerKonfirmasiData('${v.id}', 'home')"><i class="fas fa-check text-success"></i></button>`;
							action += `<button type="button" style="border:none;background:transparent" title="edit data" onclick="handlerEditData('${v.id}')"><i class="fas fa-pencil text-warning"></i></button>`;
							action += `<button type="button" style="border:none;background:transparent" title="hapus data" onclick="handlerDeleteData('${v.id}')"><i class="fas fa-trash text-danger"></i></button>`;
						} else {
							//bisa view saja
							action += `<button type="button" style="border:none;background:transparent" title="view data" onclick="handlerViewData('${v.id}')"><i class="fas fa-eye text-primary"></i></button>`;
						}

						$("#dataInboundSupplier > tbody").append(`
                  <tr class="text-center">
                      <td>${i + 1}</td>
                      <td>${v.kode}</td>
                      <td>${v.principle}</td>
                      <td>${v.tglMasuk}</td>
                      <td>${v.tglKeluar}</td>
                      <td>${action}</td>
                  </tr>
            `)

					});

				} else {
					$("#dataInboundSupplier > tbody").html(`<tr><td colspan="6" class="text-center text-danger">Data Kosong</td></tr>`);
				}
				$('#dataInboundSupplier').DataTable();

			});
	}

	const handlerKonfirmasiData = (inboundSupplierId, mode) => {
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
				postDataRequest('<?= base_url('WMS/SecurityLogBook/InboundSupplier/konfirmasiData') ?>', {
						inboundSupplierId
					}, 'POST')
					.then((response) => {
						if (response.status) {
							message_topright('success', response.message)
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
									location.href = "<?= base_url('WMS/SecurityLogBook/InboundSupplier/InboundSupplierMenu') ?>";
								}, 1000);
							}
						} else {
							message('Error!', response.message, 'error');
						}
					});
			}
		});

	}

	const handlerEditData = (inboundSupplierId) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});

			location.href = "<?= base_url('WMS/SecurityLogBook/InboundSupplier/add?id=') ?>" + inboundSupplierId;

		}, 500);

	}

	const handlerDeleteData = (inboundSupplierId) => {
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
				postDataRequest('<?= base_url('WMS/SecurityLogBook/InboundSupplier/deleteData') ?>', {
						inboundSupplierId
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

	const handlerViewData = (inboundSupplierId) => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});

			location.href = "<?= base_url('WMS/SecurityLogBook/InboundSupplier/view?id=') ?>" + inboundSupplierId;

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
			postDataRequest('<?= base_url('WMS/SecurityLogBook/InboundSupplier/deleteImageById') ?>', {
					dataImage
				}, 'POST')
				.then((response) => {});
		}
	})

	const handlerSaveData = (mode) => {
		let noSuratJalan = $("#noSuratJalan").val();
		let principle = $("#principle").val();
		let ekspedisi = $("#ekspedisi").val();
		let nopol = $("#nopol").val();
		let namaDriver = $("#namaDriver").val();
		let noHandphone = $("#noHandphone").val();
		let tglTrukMasuk = $("#tglTrukMasuk").val();
		let totalCatatanSJ = $("#totalCatatanSJ").val();
		let jamTrukMasuk = $("#jamTrukMasuk").val();

		if (noSuratJalan == "") {
			message("Error!", "No. Surat Jalan tidak boleh kosong", "error");
			return false;
		}

		if (principle == "") {
			message("Error!", "Principle tidak boleh kosong", "error");
			return false;
		}

		if (ekspedisi == "") {
			message("Error!", "Nama Ekspedisi tidak boleh kosong", "error");
			return false;
		}

		if (nopol == "") {
			message("Error!", "Nopol tidak boleh kosong", "error");
			return false;
		}

		if (namaDriver == "") {
			message("Error!", "Nama Driver tidak boleh kosong", "error");
			return false;
		}

		if (noHandphone == "") {
			message("Error!", "No. Handphone tidak boleh kosong", "error");
			return false;
		}

		if (totalCatatanSJ == "") {
			message("Error!", "Catatan SJ tidak boleh kosong", "error");
			return false;
		}

		let formData = new FormData();
		formData.append('inboundSupplierId', typeof inboundSupplierId !== "undefined" ? inboundSupplierId : '');
		formData.append('noSuratJalan', noSuratJalan);
		formData.append('principle', principle);
		formData.append('ekspedisi', ekspedisi);
		formData.append('nopol', nopol);
		formData.append('namaDriver', namaDriver);
		formData.append('noHandphone', noHandphone);
		formData.append('tglTrukMasuk', tglTrukMasuk);
		formData.append('totalCatatanSJ', totalCatatanSJ);
		formData.append('jamTrukMasuk', jamTrukMasuk);
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
			url: "<?= base_url('WMS/SecurityLogBook/InboundSupplier/saveData'); ?>",
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
						location.href = "<?= base_url('WMS/SecurityLogBook/InboundSupplier/InboundSupplierMenu') ?>";
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
			location.href = "<?= base_url('WMS/SecurityLogBook/InboundSupplier/InboundSupplierMenu') ?>";
		}, 500);
	}
</script>