<script type="text/javascript">
	let newIdPenerimaan = "";
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		get_data_tipe_penerimaan();
		// get_data_surat_jalan();

		get_data_perusahaan();
		get_status();
		get_principle();
		// get_data_tipe_penerimaan2();

		//get new Id penerimaan barang
		generateNewIdPenerimaan();

	});

	const generateNewIdPenerimaan = async () => {
		const url = "<?= base_url('WMS/PenerimaanBarang/generateNewIdPenerimaan') ?>";

		const request = await fetch(url, {
			method: 'POST',
		});

		const response = await request.json();

		newIdPenerimaan = response;
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

	function get_data_perusahaan() {
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanBarang/get_data_perusahaan'); ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.perusahaan').empty();
				$('#perusahaan_filter_sj').empty();
				$('.perusahaan').append('<option value=""><label name="CAPTION-ALL">All</label></option>');
				$('#perusahaan_filter_sj').append(
					'<option value="">--<label name="CAPTION-PERUSAHAAN">Pilih Perusahaan</label>--</option>'
				);

				$.each(data, function(i, v) {
					$('.perusahaan').append('<option value="' + v.client_wms_id + '">' + v
						.client_wms_nama + '</option>');
					$('#perusahaan_filter_sj').append('<option value="' + v.client_wms_id + '">' + v
						.client_wms_nama + '</option>');
				});
			}
		});
	}

	function get_status() {
		$('#status').append(`
            <option value="">--<label name="CAPTION-PILIHSTATUS">Pilih Status</label>--</option>
            <option value="Open" selected><label name="CAPTION-OPEN">Open</label></option>
        `);
	};

	function get_principle() {
		$('.principle').append(`
            <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--</option>
        `);
		$('#principle_filter_sj').append(`
            <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--</option>
        `);
	};

	function get_data_tipe_penerimaan() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_tipe_penerimaan') ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.tipe_penerimaan').empty();
				$('.tipe_penerimaan').append('<option value=""><label name="CAPTION-ALL">All</label></option>');
				$.each(data, function(i, v) {
					$('.tipe_penerimaan').append('<option value="' + v.id + '">' + v.nama
						.toUpperCase() + '</option>');
				});
			}
		});
	}

	//onchane untuk penyalur / principle
	$("#perusahaan_filter_sj").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_principle_by_client_wms_id') ?>",
			data: {
				id: id
			},
			dataType: "json",
			success: function(response) {
				$("#principle_filter_sj").empty();
				let html = "";
				html += '<option value="">--Pilih Principle--</option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#principle_filter_sj").append(html);
			}
		});
	});

	$(".perusahaan").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_principle_by_client_wms_id') ?>",
			data: {
				id: id
			},
			dataType: "json",
			success: function(response) {
				$(".principle").empty();
				let html = "";
				html += '<option value="">All</option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$(".principle").append(html);
			}
		});
	});

	$(document).on("click", "#search_filter_data", function() {
		let tahun = $("#tahun_filter").val();
		let month = $("#bulan_filter").val();
		let perusahaan = $("#perusahaan_filter").val();
		let principle = $("#principle_filter").val();
		// let tipe_penerimaan = $("#tipe_penerimaan_filter").val();
		let status = $("#status_filter").val();

		$("#loadingsearch").show();
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_surat_jalan') ?>",
			type: "POST",
			data: {
				tahun: tahun,
				bulan: month,
				perusahaan: perusahaan,
				principle: principle,
				// tipe_penerimaan: tipe_penerimaan,
				status: status
			},
			async: false,
			dataType: "JSON",
			success: function(data) {
				$("#loadingsearch").hide();
				$("#list-data-form-search").show();
				let no = 1;
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#listdatasuratjalan')) {
						$('#listdatasuratjalan').DataTable().destroy();
					}
					$('#listdatasuratjalan > tbody').empty();
					$.each(data, function(i, v) {
						let str = "";
						if (v.status == "Open" || v.status == "In Progress") {
							str += "<button data-id='" + v.id +
								"' class='btn btn-success form-control btnkonfirmasipb' title='konfirmasi data'><i class='fa fa-check'></i></button>";

							str +=
								`<button class='btn btn-warning form-control' title='edit data' onclick="handleEditDataPb('${v.id}')"><i class='fa fa-edit'></i></button>`;

							$.ajax({
								url: "<?= base_url('WMS/PenerimaanBarang/checkPalletConfirm') ?>",
								type: "POST",
								data: {
									id: v.id,
								},
								async: false,
								dataType: "JSON",
								success: function(response) {
									if (response.length == 0) {
										str += "<button data-id='" + v.id + "' data-lastUpdated='" + v.penerimaan_pembelian_tgl_update + "' class='btn btn-danger form-control btnbatalkanpb' title='batalkan data'><i class='fa fa-xmark'></i></button>";
									}
								}
							});
						}

						if (v.status == "Close") {
							str +=
								"<button type='button' class='btn btn-primary btnviewdetail' data-href='<?= base_url('WMS/PenerimaanBarang/view/') ?>" +
								v.id + "' data-id='" + v.id + "' data-kode='" + v.kode +
								"' title='detail data'><i class='fas fa-eye'> </i></button>";

							str += "<button type='button' data-id='" + v.id +
								"' data-href='<?= base_url("WMS/PenerimaanBarang/print") ?>' class='btn btn-success btnprint'><i class='fas fa-print'></i></button>";
						}
						var cetak = '<a class="btn btn-success" target="_blank" href="<?php echo site_url('WMS/PenerimaanBarang/print_bukti_penerimaan?id=') ?>' + v.id + '&p_kode=' + v.p_kode + '&p_nama=' + v.p_nama + '&pt=' + v.pt + '"><i class="fas fa-print"></i>Bukti Penerimaan</a>'
						$("#listdatasuratjalan > tbody").append(`
                  <tr>
                      <td class="text-center">${no++}</td>
                      <td class="text-center">${v.tgl}</td>
                      <td>${v.kode}</td>
                      <td>${v.pt}</td>
                      <td>${v.p_kode} - ${v.p_nama}</td>
                      <td class="text-center">${v.status}</td>
                      <td class="text-center">
                          ${str}
						  ${cetak}
                      </td>
                  </tr>
              `);
					});
				} else {
					$("#listdatasuratjalan > tbody").html(
						`<tr><td colspan="6" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
					);
				}

				$('#listdatasuratjalan').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});

			}
		});

		$(document).on("click", ".btneditpb", function() {
			let href = $(this).attr("data-href");
			location.href = href;
		});


		$(document).on("click", ".btnviewdetail", function() {
			let href = $(this).attr("data-href");
			location.href = href;
		});

		$(document).on("click", ".btnprint", function() {
			let href = $(this).attr("data-href");
			let sj_id = $(this).attr("data-id");
			window.open(href + "?id=" + sj_id + "&tipe=multiple", '_blank');
		});
	});

	const handleEditDataPb = async (id) => {

		//request to get client wms id, surat jalan id and principle id
		const url = "<?= base_url('WMS/PenerimaanBarang/getDataPenerimaanById') ?>";

		let postData = new FormData();
		postData.append('penerimaanBarangId', id);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		let arrSjId = []
		$.each(response, function(i, v) {
			arrSjId.push(v.sj_id)
		})

		location.href = "<?= base_url('WMS/PenerimaanBarang/add/?id=') ?>" + id + "&surat_jalan_id=" + encodeURI(
			arrSjId) + "&client=" + response[0].client_wms_id + "&principle=" + response[0].principle_id;

	}

	$(document).on("click", "#search_filter_data_sj", () => {
		let perusahaan_filter_sj = $("#perusahaan_filter_sj").val();
		let principle_filter_sj = $("#principle_filter_sj").val();
		if (perusahaan_filter_sj == "") {
			message("Error!", "Perusahaan tidak boleh kosong", "error");
			return false;
		} else if (principle_filter_sj == "") {
			message("Error!", "Principle tidak boleh kosong", "error");
			return false;
		} else {
			$.ajax({
				url: "<?= base_url('WMS/PenerimaanBarang/get_data_surat_jalan2') ?>",
				type: "POST",
				data: {
					perusahaan_filter_sj: perusahaan_filter_sj,
					principle_filter_sj: principle_filter_sj
				},
				dataType: "JSON",
				success: function(response) {
					$("#show_data_sj").show();
					$(".btn_pilih_sj").attr('data-client-id', perusahaan_filter_sj)
					$(".btn_pilih_sj").attr('data-principle-id', principle_filter_sj)
					if (response != null) {
						if ($.fn.DataTable.isDataTable('#table_list_pilih_sj')) {
							$('#table_list_pilih_sj').DataTable().destroy();
						}
						$('#table_list_pilih_sj > tbody').empty();
						$.each(response, function(i, v) {

							let str = "";

							str +=
								"<input type='checkbox' class='form-control check-item' style='transform: scale(1.5)' name='chk-data[]' id='chk-data[]' value='" +
								v.sj_id + "'/>";

							$("#table_list_pilih_sj > tbody").append(`
                  <tr>
                      <td class="text-center">${str}</td>
                      <td class="text-center">${v.sj_kode}</td>
                      <td class="text-center">${v.no_sj}</td>
                      <td class="text-center">${v.tgl}</td>
                      <td class="text-center">${v.pt}</td>
                      <td class="text-center">${v.p_kode}</td>
                      <td class="text-center">${v.tipe}</td>
                      <td class="text-center">${v.keterangan}</td>
                      <td class="text-center">${v.status}</td>
                  </tr>
              `);
						});
					} else {
						$("#table_list_pilih_sj > tbody").html(
							`<tr><td colspan="7" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
						);
						// message('Info!', 'Data Kosong', 'info');
					}

					$('#table_list_pilih_sj').DataTable({
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});
				}
			})
		}
	})

	$(document).on("click", "#tambah_penerimaan", function() {
		$("#modal_pilih_sj").modal("show");
	});

	function checkAllSJ(e) {
		var checkboxes = $("input[name='chk-data[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	$(document).on("click", ".btn_pilih_sj", function() {
		let client_id = $(this).attr('data-client-id');
		let principle_id = $(this).attr('data-principle-id');
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				arr_chk.push(checkboxes[i].value);
			}
		}

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dipilih", "info");
		} else {

			messageBoxBeforeRequest('Data yang anda simpan tidak dapat diubah / diedit kembali', 'Iya, Yakin', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Sudah benar', 'Tidak, Tutup').then((res) => {
						if (res.value == true) {
							messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Sudah benar', 'Tidak, Tutup').then((response) => {
								if (response.value == true) {
									requestAjax("<?= base_url('WMS/PenerimaanBarang/saveDataPenerimaanbarang'); ?>", {
										penerimaanId: newIdPenerimaan,
										sjId: arr_chk,
										client_id: client_id,
										principle_id
									}, "POST", "JSON", function(data) {
										let decode = encodeURI(arr_chk);
										if (data == 1) {
											location.href =
												"<?= base_url('WMS/PenerimaanBarang/add/?id=') ?>" +
												newIdPenerimaan +
												"&surat_jalan_id=" +
												decode + "&client=" +
												client_id + "&principle=" +
												principle_id
										}

										if (data == 0) return message("Error!", "something to wrong", "error")
									}, ".btn_pilih_sj")
								}
							});
						}
					});
				}
			});



			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Data yang anda simpan tidak dapat diubah / diedit kembali",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Yakin",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		Swal.fire({
			// 			title: "Apakah anda yakin?",
			// 			text: "Pastikan data yang sudah anda input benar!",
			// 			icon: "warning",
			// 			showCancelButton: true,
			// 			confirmButtonColor: "#3085d6",
			// 			cancelButtonColor: "#d33",
			// 			confirmButtonText: "Ya, Sudah benar",
			// 			cancelButtonText: "Tidak, Tutup"
			// 		}).then((res) => {
			// 			if (res.value == true) {
			// 				Swal.fire({
			// 					title: "Apakah anda yakin?",
			// 					text: "Data akan disimpan dan lakukan konfirmasi",
			// 					icon: "warning",
			// 					showCancelButton: true,
			// 					confirmButtonColor: "#3085d6",
			// 					cancelButtonColor: "#d33",
			// 					confirmButtonText: "Ya, Simpan",
			// 					cancelButtonText: "Tidak, Tutup"
			// 				}).then((response) => {
			// 					if (response.value == true) {
			// 						$.ajax({
			// 							url: "<?= base_url('WMS/PenerimaanBarang/saveDataPenerimaanbarang') ?>",
			// 							type: "POST",
			// 							data: {
			// 								penerimaanId: newIdPenerimaan,
			// 								sjId: arr_chk,
			// 								client_id: client_id,
			// 								principle_id
			// 							},
			// 							dataType: "JSON",
			// 							success: function(response) {
			// 								let decode = encodeURI(arr_chk);
			// 								if (response == 1) {
			// 									location.href =
			// 										"<?= base_url('WMS/PenerimaanBarang/add/?id=') ?>" +
			// 										newIdPenerimaan +
			// 										"&surat_jalan_id=" +
			// 										decode + "&client=" +
			// 										client_id + "&principle=" +
			// 										principle_id
			// 								}

			// 								if (response == 0) {
			// 									message("Error!",
			// 										"something to wrong",
			// 										"error");
			// 									return false;
			// 								}
			// 							},
			// 						});
			// 					}
			// 				});
			// 			}
			// 		});
			// 	}
			// });


			//ajax cek sj jika ada yg belum dikonfirmasi, maka munculkan alert konfirmasi terlbih dahulu
			// $.ajax({
			//   url: "<?= base_url('WMS/PenerimaanBarang/check_data_when_status_close_show_alert') ?>",
			//   type: "POST",
			//   data: {
			//     id: arr_chk,
			//   },
			//   dataType: "JSON",
			//   success: function(response) {
			//     //save to data temp terlbih dahulu
			//     let decode = encodeURI(arr_chk);
			//     if (response != null) {
			//       if (response.status_open > 0) {
			//         message("Info!", `<label name="CAPTION-MSG02">Harap konfirmasi penerimaan terlebih dahulu untuk Perusahaan</label> <strong>${response.client_wms_nama}</strong> <label name="CAPTION-DANPRINCIPLE">dan Principle</label> <strong>${response.principle_nama}</strong>`, "info");
			//         return false;
			//       }

			//       if (response.status_close > 0) {


			//         location.href = "<?= base_url('WMS/PenerimaanBarang/add/?id=') ?>" + decode + "&client=" + client_id + "&principle=" + principle_id
			//       }
			//     } else {
			//       location.href = "<?= base_url('WMS/PenerimaanBarang/add/?id=') ?>" + decode + "&client=" + client_id + "&principle=" + principle_id
			//     }

			//   }
			// })


		}
	});

	$(document).on("click", ".btn_close_list_pilih_sj", function() {
		$("#modal_pilih_sj").modal("hide");
	});

	$(document).on("click", ".btnkonfirmasipb", function() {
		let id = $(this).attr('data-id');
		$("#modal_konfirmasi").modal('show');

		$(".btn_konfirmasi").attr('data-id', id);

		getDataheaderPb(id);

		getDatadetailPb(id);

		getDataPalletPb(id);

	});

	function getDataheaderPb(id) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/getDataheaderPb') ?>",
			type: "POST",
			data: {
				id: id,
			},
			dataType: "JSON",
			success: function(response) {
				$("#idPenerimaanBarang").val(id);
				$("#doc_po_konfirmasi").val(response.result.kode);
				$("#tgl_konfirmasi").val(response.result.tgl);

				let html = "";
				html +=
					`<option value="">-- <label name="CAPTION-PILIHJASAPENGANGKUT">Pilih Jasa Pengangkut</label> --</option>`

				$.each(response.ekspedisis, function(i, v) {
					if (response.result.e_id != null) {
						if (response.result.e_id == v.ekspedisi_id) {
							html +=
								`<option value="${v.ekspedisi_id}" selected>${v.ekspedisi_kode} - ${v.ekspedisi_nama}</option>`;
						} else {
							html +=
								`<option value="${v.ekspedisi_id}">${v.ekspedisi_kode} - ${v.ekspedisi_nama}</option>`;
						}
					} else {
						html +=
							`<option value="${v.ekspedisi_id}">${v.ekspedisi_kode} - ${v.ekspedisi_nama}</option>`;
					}
				});
				$('#expedisi_konfirmasi').append(html);

				$("#no_kendaraan_konfirmasi").val(response.result.nopol != null ? response.result.nopol : "");
				$("#nama_pengemudi_konfirmasi").val(response.result.pengemudi != null ? response.result
					.pengemudi : "");
				$("#gudang_penerima_konfirmasi").val(response.result.gudang);
				$("#gudang_penerima_konfirmasi").attr('data-id', response.result.gudang_id);
				// $("#checker_konfirmasi").val(response.result.checker);
				// $("#checker_konfirmasi").attr('data-id', response.result.checker_id);
				$("#keterangan_konfirmasi").val(response.result.keterangan);
				$("#lastUpdated").val(response.result.penerimaan_pembelian_tgl_update);
			}
		})
	}

	const handleUpdateService = async (value) => {
		const gudangPenerima = $("#gudang_penerima_konfirmasi").attr('data-id');
		const penerimaanBarangId = $("#idPenerimaanBarang").val();
		const url = "<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderService') ?>";

		let postData = new FormData();
		postData.append('penerimaanBarangId', penerimaanBarangId);
		postData.append('gudangPenerima', gudangPenerima);
		postData.append('jasaPengangkut', value);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

	}

	const handleUpdateVehicle = async (value) => {
		const gudangPenerima = $("#gudang_penerima_konfirmasi").attr('data-id');
		const penerimaanBarangId = $("#idPenerimaanBarang").val();
		const url = "<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderVehicle') ?>";

		let postData = new FormData();
		postData.append('penerimaanBarangId', penerimaanBarangId);
		postData.append('gudangPenerima', gudangPenerima);
		postData.append('kendaraan', value);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();
	}

	const handleUpdateDriver = async (value) => {
		const gudangPenerima = $("#gudang_penerima_konfirmasi").attr('data-id');
		const penerimaanBarangId = $("#idPenerimaanBarang").val();
		const url = "<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderDriver') ?>";

		let postData = new FormData();
		postData.append('penerimaanBarangId', penerimaanBarangId);
		postData.append('gudangPenerima', gudangPenerima);
		postData.append('namaPengemudi', value);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();
	}

	function getDatadetailPb(id) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_view') ?>",
			type: "POST",
			data: {
				id: id
			},
			dataType: "JSON",
			beforeSend: function() {
				$("#tablelistdatadetailkonfirmasi > tbody").html(
					`<tr><td colspan="9" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span></td></tr>`
				);
			},
			success: function(data) {
				$("#showfilterdata").show();
				let no = 1;
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#tablelistdatadetailkonfirmasi')) {
						$('#tablelistdatadetailkonfirmasi').DataTable().destroy();
					}
					$('#tablelistdatadetailkonfirmasi > tbody').empty();
					$.each(data, function(i, v) {
						$("#tablelistdatadetailkonfirmasi > tbody").append(`
              <tr>
                  <td class="text-center">${no++}</td>
                  <td class="text-center">${v.sku_kode}</td>
                  <td>${v.sku_nama}</td>
                  <td class="text-center">${v.sku_kemasan}</td>
                  <td class="text-center">${v.sku_satuan}</td>
                  <td class="text-center">${v.batch_no == null ? '' : v.batch_no}</td>
                  <td class="text-center">${v.sku_exp_date}</td>
                  <td class="text-center">${v.jml_barang}</td>
                  <td class="text-center">${v.jml_terima == null ? 0 : v.jml_terima}</td>
              </tr>
          `);
					});
				} else {
					$("#tablelistdatadetailkonfirmasi > tbody").html(
						`<tr><td colspan="9" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
					);
				}

				$('#tablelistdatadetailkonfirmasi').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
			}
		});
	}

	function getDataPalletPb(id) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/getDataPalletPb') ?>",
			type: "POST",
			data: {
				id: id,
			},
			dataType: "JSON",
			success: function(response) {
				$("#data_pallet_konfirmasi > tbody").empty();
				if (response.length > 0) {
					$.each(response, function(i, v) {
						$("#data_pallet_konfirmasi > tbody").append(`
                  <tr>
                      <td>
                          <input type="text" class="form-control" value="${v.kode}" readonly/>
                          <input type="hidden" class="form-control" name="id_pallet[]" id="id_pallet" value="` + v.id +
							`"/>
                      </td>
                      <td>
                          <select class="form-control select2 jenis_palet" id="jenis_palet" name="jenis_palet" disabled>
                            <option value="">${v.jenis}</option>
                          </select>
                      </td>
                      <td>
                          <select class="form-control select2 gudang" id="gudang" name="gudang" disabled>
                            <option value="">${v.gudang}</option>
                          </select>
                      </td>
                      <td class="text-center">
                          <button type="button" data-toggle="tooltip" data-placement="top" title="detail pallet" data-id="` +
							v.id + `"  data-kode="${v.kode}" class="detail_pallet_konfirmasi" style="border:none;background:transparent"><i class="fas fa-eye text-primary" style="cursor: pointer"></i></button>
                      </td>
                  </tr>
              `);
					});
				}

				let tot_pallet = $("#data_pallet_konfirmasi > tbody tr").length;
				$("#data_pallet_konfirmasi > tfoot tr #total_pallet_konfirmasi").html("<strong><h4>Total " +
					tot_pallet + " Pallet</h4></strong>");

				setTimeout(() => {
					$(".detail_pallet_konfirmasi").first().click();
				}, 1000);
			}
		})
	}

	$(document).on("click", ".detail_pallet_konfirmasi", function() {
		let id = $(this).attr('data-id');
		let kode = $(this).attr('data-kode');
		$('#show_isi_sku_pallet_konfirmasi').fadeOut("slow", function() {
			$(this).hide();
			$("#list_detail_pallet_konfirmasi > tbody").html(
				`<tr><td colspan="7" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span></td></tr>`
			);
		}).fadeIn("slow", function() {
			$(this).show();
			append_data(id, kode);
			count_tot_detail_pallet();
		});
	});

	function append_data(pallet_id, kode) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_by_arrId_view'); ?>",
			type: "POST",
			data: {
				id: pallet_id
			},
			dataType: "JSON",
			success: function(data) {
				$(".kode_pallet_konfirmasi").empty();
				$(".kode_pallet_konfirmasi").append(kode)
				$("#list_detail_pallet_konfirmasi > tbody").empty();
				if (data.length != 0) {
					$.each(data, function(i, v) {
						$("#list_detail_pallet_konfirmasi > tbody").append(`
              <tr>
                  <td class="text-center">${v.principle}</td>
                  <td class="text-center">${v.sku_kode}</td>
                  <td>${v.sku_nama_produk}</td>
                  <td class="text-center">${v.sku_kemasan}</td>
                  <td class="text-center">${v.sku_satuan}</td>
                  <td>
                      <input type="date" class="form-control" id="exp_date" name="exp_date" value="${v.expired_date}" readonly/>
                  </td>
                  <td>
                      <input type="text" class="form-control qty_detail_pallet numeric" id="qty_detail_pallet" name="qty_detail_pallet" value="${v.qty}" readonly/>
                  </td>
              </tr>
          `);
					});
				} else {
					$("#list_detail_pallet_konfirmasi > tbody").html(
						`<tr><td colspan="7" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
					);
				}
				select2();
				count_tot_detail_pallet();
			}
		});
	}

	function count_tot_detail_pallet() {
		let no = 0;
		$("#list_detail_pallet_konfirmasi > tbody tr").each(function(i, v) {
			let first_element = $(this).find("td:eq(0)");
			if (first_element.text() != "Data Kosong") {
				no++;
			} else {
				no = 0;
			}
		});
		$("#list_detail_pallet_konfirmasi > tfoot tr #total_detail_pallet_konfirmasi").html("<strong><h4>Total " + no +
			" SKU</h4></strong>");
	}


	$(document).on("click", ".btn_konfirmasi", function() {
		let id = $(this).attr('data-id');
		let tgl = $("#tgl_konfirmasi");
		let ekspedisi = $("#expedisi_konfirmasi").val();
		let noKendaraan = $("#no_kendaraan_konfirmasi").val();
		let namaPengemudi = $("#nama_pengemudi_konfirmasi").val();
		let gudang_penerima = $("#gudang_penerima_konfirmasi");
		let checker = $("#checker_konfirmasi");
		let keterangan = $("#keterangan_konfirmasi");
		const lastUpdated = $("#lastUpdated").val();

		if (ekspedisi == "") {
			message("Error!", "Jasa Pengangkut boleh kosong!", "error");
			return false;
		}

		if (noKendaraan == "") {
			message("Error!", "No. Kendaraan boleh kosong!", "error");
			return false;
		}

		if (namaPengemudi == "") {
			message("Error!", "Nama Pengemudi boleh kosong!", "error");
			return false;
		}

		messageBoxBeforeRequest('Pastikan data yang sudah  diinput benar!', 'Ya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/PenerimaanBarang/konfirmasi_data_penerimaan'); ?>", {
					pb_id: id,
					lastUpdated
				}, "POST", "JSON", function(response) {
					if (response.status === 200) {
						// $("#lastUpdated").val(response.lastUpdatedNew);
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

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Pastikan data yang sudah  diinput benar!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Konfirmasi",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		$.ajax({
		// 			url: "<?= base_url('WMS/PenerimaanBarang/konfirmasi_data_penerimaan') ?>",
		// 			type: "POST",
		// 			data: {
		// 				pb_id: id
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				if (data == 1) {
		// 					message_topright("success", "Data berhasil dikonfirmasi");
		// 					setTimeout(() => {
		// 						location.reload();
		// 					}, 500);
		// 				} else {
		// 					message_topright("error", "Data gagal dikonfirmasi");
		// 				}
		// 			},
		// 		});
		// 	}
		// });
	});

	$(document).on("click", ".btn_close_list_konfirmasi", function() {
		let id = $(this).attr('data-id');
		$("#modal_konfirmasi").modal('hide');
	});

	$(document).on("click", ".btnbatalkanpb", function() {
		let id = $(this).attr('data-id');
		let lastUpdated = $(this).attr('data-lastUpdated');

		messageBoxBeforeRequest('Jika dibatalkan, data yang telah diisi akan ke hilang!', 'Ya, Batalkan', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/PenerimaanBarang/batalkan'); ?>", {
					id: id,
					lastUpdated
				}, "POST", "JSON", function(response) {
					if (response.status === 200) {
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

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Jika dibatalkan, data yang telah diisi akan ke hilang!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Batalkan",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		$.ajax({
		// 			url: "<?= base_url('WMS/PenerimaanBarang/batalkan') ?>",
		// 			type: "POST",
		// 			data: {
		// 				id: id,
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				if (data == 1) {
		// 					message_topright("success", "Berhasil membatalkan");
		// 					setTimeout(() => {
		// 						location.reload();
		// 					}, 500);
		// 				} else {
		// 					message_topright("error", "Gagal membatalkan");
		// 				}
		// 			},
		// 		});
		// 	}
		// });
	});
</script>