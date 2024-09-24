<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	let arr_checkbox_sku = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	var arr_do = [];
	var arr_do_not_reschedule = [];
	var delivery_order_batch_id = "";
	let delivery_order_batch_tgl_update = "";
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2({
				width: '100%'
			});
			getSegment();
			// delete_delivery_order_draft_detail_msg();
			if ($('#filter-do-date').length > 0) {
				$('#filter-do-date').daterangepicker({
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

			if ($('#filter-sj-date').length > 0) {
				$('#filter-sj-date').daterangepicker({
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
			// getSegment();
		}
	);

	function previewFile() {
		const file = document.querySelector('input[type=file]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		$('#hide-file').hide();
		reader.addEventListener("load", function() {
			let result = reader.result.split(',');
			$('#show-file').append(`
		<div class="col-md-6">
                    <a class="text-primary klik-saya" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
                </div>
                <div class="col-md-6">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefile" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
                </div>
				`);
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
	}

	$(document).on('click', '.klik-saya', function() {
		let url = $(this).data('url');
		let ex = $(this).data('ex');
		let pdfWindow = window.open("")
		pdfWindow.document.write(
			"<iframe width='100%' height='100%' src='" + ex + ", " + encodeURI(url) + "'></iframe>"
		)
	});

	$(document).on('click', '.btndeletefile', function() {
		$('#show-file').empty();
		$("#file").val("");
		// $(".file-upload-wrapper").attr("data-text", "Select your file!");
	});

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		})
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

	function getSegment() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/GetSegment1') ?>",
			dataType: "JSON",
			success: function(response) {
				console.log(response);
				$("#filter-segment1").empty()
				$("#filter-segment1").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment1").append('<option value="' + segment_id + '">' + segment_nama +
						'</option>');
				})
			}
		})
	}

	$(document).on('change', '#filter-segment1', function() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/GetSegment2') ?>",
			data: {
				id: $(this).val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter-segment2").empty()
				$("#filter-segment2").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment2").append('<option value="' + segment_id + '">' +
						segment_nama + '</option>');
				})
			}
		})
	});

	$(document).on('change', '#filter-segment2', function() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/GetSegment3') ?>",
			data: {
				id: $(this).val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter-segment3").empty()
				$("#filter-segment3").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment3").append('<option value="' + segment_id + '">' +
						segment_nama + '</option>');
				})
			}
		})
	})

	$('#select-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$('#select-do').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxDO"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxDO"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$('#select-do-not-reschedule').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxDONotReschedule"]:checkbox').each(function(idx) {
				this.checked = true;
				var delivery_order_id = this.getAttribute('data-delivery_order_id_not_reschedule');
				var delivery_order_kode = $("#delivery_order_kode_" + idx + "_not_schedule").val();
				var tanggal_rencana_kirim = $("#tanggal_rencana_kirim_" + idx + "_not_schedule").val();
				var tipe_delivery_order_id = $("#tipe_delivery_order_id_" + idx + "_not_schedule").val();

				arr_do_not_reschedule.push({
					'delivery_order_id': delivery_order_id,
					'delivery_order_kode': delivery_order_kode,
					'tanggal_rencana_kirim': tanggal_rencana_kirim,
					'tipe_delivery_order_id': tipe_delivery_order_id
				})
			});
		} else {
			$('[name="CheckboxDONotReschedule"]:checkbox').each(function() {
				this.checked = false;
				arr_do_not_reschedule = [];
			});
		}
	});

	$("#btn_search_data_do_draft").on("click", function() {
		GetDODraftByFilter();
	});

	function GetDODraftByFilter() {
		var idx = 0;

		arr_do_not_reschedule = [];

		Swal.fire({
			title: 'Loading ...',
			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			timerProgressBar: false,
			showConfirmButton: false
		});

		setTimeout(() => {
			$.ajax({
				async: false,
				url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalNotReScheduleByFilter'); ?>",
				type: "POST",
				beforeSend: function() {
					$("#btn_search_data_do_draft").prop("disabled", true);
					// $("#btn_insert_do_to_fdjr").prop("disabled", true);
					// $("#loadingviewdodraft").show();
				},
				data: {
					tgl: $("#filter-do-date").val(),
					do_no: $("#filter-do-number").val(),
					so_eksternal: $("#filter-so-eksternal").val(),
					customer: $("#filter-customer").val(),
					alamat: $("#filter-address").val(),
					tipe_pembayaran: $("#filter-payment-type").val(),
					tipe_layanan: $("#filter-service-type").val(),
					status: $("#filter-status").val(),
					tipe: $("#filter-do-type").val(),
					segmen1: $("#filter-segment1").val(),
					segmen2: $("#filter-segment2").val(),
					segmen3: $("#filter-segment3").val()
				},
				dataType: "JSON",
				success: function(response) {
					$('#table_list_data_do_draft_not_reschedule').fadeOut("slow", function() {
						$(this).hide();

						$("#table_list_data_do_draft_not_reschedule > tbody").empty();

						if ($.fn.DataTable.isDataTable('#table_list_data_do_draft_not_reschedule')) {
							$('#table_list_data_do_draft_not_reschedule').DataTable().clear();
							$('#table_list_data_do_draft_not_reschedule').DataTable().destroy();
						}

					}).fadeIn("slow", function() {
						$(this).show();
						if (response.length > 0) {

							// console.log(response);
							$.each(response, function(i, v) {
								$("#table_list_data_do_draft_not_reschedule > tbody").append(`
								<tr>
									<td class="text-center">
										<input type="checkbox" name="CheckboxDONotReschedule" id="check-do-${i}-not-reschedule" value="${v.delivery_order_id}" data-delivery_order_id_not_reschedule="${v.delivery_order_id}" onclick="PushArrayDONotReschedule('${v.delivery_order_id}','${i}')"> <input type="hidden" id="item-${i}-ListDODraft-delivery_order_status-not-reschedule" value="${v.delivery_order_status}">
										<input type="hidden" id="tipe_delivery_order_id_${i}_not_schedule" class="form-control" name="tipe_delivery_order_id_${i}_not_schedule" autocomplete="off" value="${v.tipe_delivery_order_id}">
										<input type="hidden" id="delivery_order_kode_${i}_not_schedule" class="form-control" name="delivery_order_kode_${i}_not_schedule" autocomplete="off" value="${v.delivery_order_kode}">
									</td>
									<td class="text-center">
										<input type="date" id="tanggal_rencana_kirim_${i}_not_schedule" class="form-control" name="tanggal_rencana_kirim_${i}_not_schedule" autocomplete="off" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" onchange="UpdateArrayDONotReschedule('${v.delivery_order_id}','${i}')">
									</td>
									<td class="text-center">${v.delivery_order_kode}</td>
									<td class="text-center">${v.sales_order_kode}</td>
									<td class="text-center">${v.sales_order_no_po}</td>
									<td class="text-center">${v.delivery_order_kirim_nama}</td>
									<td class="text-center">${v.delivery_order_kirim_alamat}</td>
									<td class="text-center">${v.delivery_order_kirim_area}</td>
									<td class="text-center">${v.tipe_delivery_order_alias}</td>
									<td class="text-center">${Math.round(v.delivery_order_nominal_tunai)}</td>
									<td class="text-center">${v.delivery_order_status}</td>
									<td class="text-center">
										<a href="<?= base_url() ?>WMS/KelolaDOGagal/detail/?id=${v.delivery_order_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>
									</td>
								</tr>
							`).fadeIn("slow");
							});

							$('#table_list_data_do_draft_not_reschedule').DataTable({
								// "scrollX": true,
								'paging': true,
								'searching': false,
								'ordering': false,
								'lengthMenu': [
									[10, 50, 100],
									[10, 50, 100],
								],
							});
						}
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
					$("#btn_search_data_do_draft").prop("disabled", false);
					// $("#btn_insert_do_to_fdjr").prop("disabled", false);
					// $("#loadingviewdodraft").hide();
					Swal.close();
				},
				complete: function() {
					$("#btn_search_data_do_draft").prop("disabled", false);
					// Swal.close();
				}
			});


			$.ajax({
				async: false,
				url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalNotRescheduleBuatDraftByFilter'); ?>",
				type: "POST",
				beforeSend: function() {

					// Swal.fire({
					// 	title: 'Loading ...',
					// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					// 	timerProgressBar: false,
					// 	showConfirmButton: false
					// });

					$("#btn_search_data_do_draft").prop("disabled", true);
					// $("#btn_insert_do_to_fdjr").prop("disabled", true);
					// $("#loadingviewdodraft").show();
				},
				data: {
					tgl: $("#filter-do-date").val(),
					do_no: $("#filter-do-number").val(),
					so_eksternal: $("#filter-so-eksternal").val(),
					customer: $("#filter-customer").val(),
					alamat: $("#filter-address").val(),
					tipe_pembayaran: $("#filter-payment-type").val(),
					tipe_layanan: $("#filter-service-type").val(),
					status: $("#filter-status").val(),
					tipe: $("#filter-do-type").val(),
					segmen1: $("#filter-segment1").val(),
					segmen2: $("#filter-segment2").val(),
					segmen3: $("#filter-segment3").val()
				},
				dataType: "JSON",
				success: function(response) {
					$('#table_list_data_do_draft_not_reschedule_buat_draft').fadeOut("slow", function() {
						$(this).hide();

						$("#table_list_data_do_draft_not_reschedule_buat_draft > tbody").empty();

						if ($.fn.DataTable.isDataTable('#table_list_data_do_draft_not_reschedule_buat_draft')) {
							$('#table_list_data_do_draft_not_reschedule_buat_draft').DataTable().clear();
							$('#table_list_data_do_draft_not_reschedule_buat_draft').DataTable().destroy();
						}

					}).fadeIn("slow", function() {
						$(this).show();
						if (response.length > 0) {

							// console.log(response);
							$.each(response, function(i, v) {
								$("#table_list_data_do_draft_not_reschedule_buat_draft > tbody").append(`
								<tr>
									<td class="text-center">${i+1}</td>
									<td class="text-center">${v.delivery_order_tgl_rencana_kirim}</td>
									<td class="text-center">${v.delivery_order_kode}</td>
									<td class="text-center">${v.sales_order_kode}</td>
									<td class="text-center">${v.sales_order_no_po}</td>
									<td class="text-center">${v.delivery_order_kirim_nama}</td>
									<td class="text-center">${v.delivery_order_kirim_alamat}</td>
									<td class="text-center">${v.delivery_order_kirim_area}</td>
									<td class="text-center">${v.tipe_delivery_order_alias}</td>
									<td class="text-center">${Math.round(v.delivery_order_nominal_tunai)}</td>
									<td class="text-center">${v.delivery_order_status}</td>
									<td class="text-center">
										<a href="<?= base_url() ?>WMS/KelolaDOGagal/detail/?id=${v.delivery_order_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>
									</td>
								</tr>
							`).fadeIn("slow");
							});

							$('#table_list_data_do_draft_not_reschedule_buat_draft').DataTable({
								'paging': true,
								'searching': false,
								'ordering': false,
								'lengthMenu': [
									[10, 50, 100],
									[10, 50, 100],
								],
							});
						}
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
					$("#btn_search_data_do_draft").prop("disabled", false);
					// $("#btn_insert_do_to_fdjr").prop("disabled", false);
					// $("#loadingviewdodraft").hide();
					Swal.close();
				},
				complete: function() {
					$("#btn_search_data_do_draft").prop("disabled", false);
					// Swal.close();
				}
			});


			const load_start = () => {

				$('#select-do').prop("checked", false);
				$('#select-do-not-reschedule').prop("checked", false);
				$("#loadingviewdodraft").show();

			};

			const proses = () => {

				$('#table_list_data_do_draft').DataTable().clear();
				$('#table_list_data_do_draft').DataTable().destroy();

				$('#table_list_data_do_draft').DataTable({
					// "scrollX": true,
					'paging': true,
					'searching': false,
					'ordering': false,
					'processing': true,
					'serverSide': true,
					'lengthMenu': [
						[10, 50, 100],
						[10, 50, 100],
					],
					'ajax': {
						async: false,
						url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalByFilter') ?>",
						type: "POST",
						dataType: "json",
						data: {
							tgl: $("#filter-do-date").val(),
							do_no: $("#filter-do-number").val(),
							so_eksternal: $("#filter-so-eksternal").val(),
							customer: $("#filter-customer").val(),
							alamat: $("#filter-address").val(),
							tipe_pembayaran: $("#filter-payment-type").val(),
							tipe_layanan: $("#filter-service-type").val(),
							status: $("#filter-status").val(),
							tipe: $("#filter-do-type").val(),
							segmen1: $("#filter-segment1").val(),
							segmen2: $("#filter-segment2").val(),
							segmen3: $("#filter-segment3").val(),
							is_reschedule: ""
						}
					},
					'columns': [{
							render: function(v) {
								return `<input type="checkbox" name="CheckboxDO" id="check-do-${v.idx}" value="${v.delivery_order_id}" data-lastUpdate="${v.delivery_order_update_tgl}"> <input type="hidden" id="item-${v.idx}-ListDODraft-delivery_order_status" value="${v.delivery_order_status}"> <input type="hidden" id="counter" value="${v.idx}">`;
							},
							data: null,
							targets: 0
						},
						{
							// untuk menghitung nomor urut berdasarkan halaman saat ini dan indeks baris dalam halaman tersebut
							render: function(data, type, row, meta) {
								return meta.row + meta.settings._iDisplayStart + 1;
							},
							data: null,
							orderable: false
						},
						{
							data: 'delivery_order_tgl_rencana_kirim'
						},
						{
							data: 'delivery_order_kode'
						},
						{
							data: 'sales_order_kode'
						},
						{
							data: 'sales_order_no_po'
						},
						{
							data: 'delivery_order_kirim_nama'
						},
						{
							data: 'delivery_order_kirim_alamat'
						},
						{
							data: 'delivery_order_kirim_area'
						},
						{
							data: 'tipe_delivery_order_alias'
						},
						{
							render: function(v) {
								return Math.round(v.delivery_order_nominal_tunai);
							},
							data: null,
							targets: 8
						},
						{
							data: 'delivery_order_status'
						},
						{
							render: function(v) {
								return `<input type="date" id="tanggal_rencana_kirim_${v.idx}" class="form-control" name="tanggal_rencana_kirim_${v.idx}" autocomplete="off" value="<?= date('Y-m-d', strtotime('+1 day')) ?>">`
							},
							data: null,

						},
						{
							render: function(v) {
								return `<a href="<?= base_url() ?>WMS/KelolaDOGagal/detail/?id=${v.delivery_order_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>`;
							},
							data: null,
							targets: 13
						},
					]
				});

				$('#table_list_data_do_draft_reschedule').DataTable().clear();
				$('#table_list_data_do_draft_reschedule').DataTable().destroy();

				$('#table_list_data_do_draft_reschedule').DataTable({
					// "scrollX": true,
					'paging': true,
					'searching': false,
					'ordering': false,
					'processing': true,
					'serverSide': true,
					'lengthMenu': [
						[10, 50, 100],
						[10, 50, 100],
					],
					'ajax': {
						async: false,
						url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalByFilter') ?>",
						type: "POST",
						dataType: "json",
						data: {
							tgl: $("#filter-do-date").val(),
							do_no: $("#filter-do-number").val(),
							so_eksternal: $("#filter-so-eksternal").val(),
							customer: $("#filter-customer").val(),
							alamat: $("#filter-address").val(),
							tipe_pembayaran: $("#filter-payment-type").val(),
							tipe_layanan: $("#filter-service-type").val(),
							status: $("#filter-status").val(),
							tipe: $("#filter-do-type").val(),
							segmen1: $("#filter-segment1").val(),
							segmen2: $("#filter-segment2").val(),
							segmen3: $("#filter-segment3").val(),
							is_reschedule: "1"
						}
					},
					'columns': [{
							// untuk menghitung nomor urut berdasarkan halaman saat ini dan indeks baris dalam halaman tersebut
							render: function(data, type, row, meta) {
								return meta.row + meta.settings._iDisplayStart + 1;
							},
							data: null,
							orderable: false
						},
						{
							data: 'delivery_order_tgl_rencana_kirim'
						},
						{
							data: 'delivery_order_kode'
						},
						{
							data: 'sales_order_kode'
						},
						{
							data: 'sales_order_no_po'
						},
						{
							data: 'delivery_order_kirim_nama'
						},
						{
							data: 'delivery_order_kirim_alamat'
						},
						{
							data: 'delivery_order_kirim_area'
						},
						{
							data: 'tipe_delivery_order_alias'
						},
						{
							render: function(v) {
								return Math.round(v.delivery_order_nominal_tunai);
							},
							data: null,
							targets: 8
						},
						{
							data: 'delivery_order_status'
						},
						{
							render: function(v) {
								return `<a href="<?= base_url() ?>WMS/KelolaDOGagal/detail/?id=${v.delivery_order_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>`;
							},
							data: null,
							targets: 13
						},
					]
				});

				var jml_data_do = $('#table_list_data_do_draft').DataTable().page.info().recordsTotal;
				$("#jml_do").val(jml_data_do);

			};

			const load_end = () => {
				$("#loadingviewdodraft").hide();
				Swal.close();
			};

			load_start();
			proses();
			load_end();
		}, 0);
	}

	$("#btn_insert_do_to_fdjr").on("click", function() {
		var count_do_list = $("#jml_do").val();
		var cek_success = 0;
		arr_do = [];
		if (count_do_list > 0) {

			for (var i = 0; i < count_do_list; i++) {
				var checked = $('[id="check-do-' + i + '"]:checked').length;
				var do_id = "'" + $("#check-do-" + i).val() + "'";

				if (checked > 0) {
					arr_do.push(do_id);
				}
			}

			var result = arr_do.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_do = result;

			console.log(arr_do);
			if (arr_do.length == 0) {
				let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
				message("Error", alert_tes, "error");
				return false;
			}

			$("#modal_sisipan_fdjr").modal('show');

		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
	});

	$("#btn_handler_save_fdjr").on("click", function() {
		var cek_success = 0;

		if (arr_do.length > 0) {
			Swal.fire({
				title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
				cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
			}).then((result) => {
				if (result.value == true) {

					delete_delivery_order_draft_detail_msg();

					//ajax save data
					$("#btn_insert_do_to_fdjr").prop("disabled", true);
					$("#btn_search_data_do_draft").prop("disabled", true);
					// $("#loadingviewdodraft").show();

					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
					// setTimeout(function() {
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/KelolaDOGagal/CekLastUpdateFDJR'); ?>",
						data: {
							delivery_order_batch_id: delivery_order_batch_id,
							delivery_order_batch_tgl_update: delivery_order_batch_tgl_update == "" ?
								NULL : delivery_order_batch_tgl_update
						},
						dataType: "JSON",
						success: function(response) {
							if (response == 1) {
								console.log("Data Aman, Tidak Dihandle orang lain");
							}
							if (response == 2) {
								return messageNotSameLastUpdated()
								Swal.close();
							}
						}
					})
					// }, 500)

					setTimeout(function() {
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalById'); ?>",
							data: {
								arr_do: arr_do
							},
							dataType: "JSON",
							success: function(response) {
								console.log(response);
								return false;
								if (response.DOHeader != 0) {

									$.each(response.DOHeader, function(i, v) {
										if (v.client_wms_id !== null) {
											$.ajax({
												async: false,
												url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalDetailByListId'); ?>",
												type: "POST",
												data: {
													id: v
														.delivery_order_draft_reff_id
												},
												dataType: "JSON",
												success: function(
													response) {
													arr_detail = [];

													if (response
														.DODetail !=
														0) {
														$.each(response
															.DODetail,
															function(
																i2,
																v2
															) {
																arr_detail
																	.push({
																		'delivery_order_detail_draft_id': v2
																			.delivery_order_detail_draft_id,
																		'delivery_order_draft_id': v2
																			.delivery_order_draft_id,
																		'sku_id': v2
																			.sku_id,
																		'gudang_id': v2
																			.gudang_id,
																		'gudang_detail_id': v2
																			.gudang_detail_id,
																		'sku_kode': v2
																			.sku_kode,
																		'sku_nama_produk': v2
																			.sku_nama_produk,
																		'sku_harga_satuan': v2
																			.sku_harga_satuan,
																		'sku_disc_percent': v2
																			.sku_disc_percent,
																		'sku_disc_rp': v2
																			.sku_disc_rp,
																		'sku_harga_nett': v2
																			.sku_harga_nett,
																		'sku_request_expdate': v2
																			.sku_request_expdate,
																		'sku_filter_expdate': v2
																			.sku_filter_expdate,
																		'sku_filter_expdatebulan': v2
																			.sku_filter_expdatebulan,
																		'sku_filter_expdatetahun': v2
																			.sku_filter_expdatetahun,
																		'sku_weight': v2
																			.sku_weight,
																		'sku_weight_unit': v2
																			.sku_weight_unit,
																		'sku_length': v2
																			.sku_length,
																		'sku_length_unit': v2
																			.sku_length_unit,
																		'sku_width': v2
																			.sku_width,
																		'sku_width_unit': v2
																			.sku_width_unit,
																		'sku_height': v2
																			.sku_height,
																		'sku_height_unit': v2
																			.sku_height_unit,
																		'sku_volume': v2
																			.sku_volume,
																		'sku_volume_unit': v2
																			.sku_volume_unit,
																		'sku_qty': v2
																			.sku_qty,
																		'sku_keterangan': v2
																			.sku_keterangan,
																		'sku_tipe_stock': v2
																			.tipe_stock_nama
																	});
															});
													}
												},
												error: function(xhr,
													status, error) {
													$("#btn_insert_do_to_fdjr")
														.prop(
															"disabled",
															false);
													$("#btn_search_data_do_draft")
														.prop(
															"disabled",
															false);
													$("#loadingviewdodraft")
														.hide();

													message_topright
														("error",
															"Data gagal Diapprove"
														);
												}
											});

											if (arr_detail.length > 0) {

												if (v
													.delivery_order_draft_kirim_area !=
													"") {
													$.ajax({
														async: false,
														url: "<?= base_url('WMS/KelolaDOGagal/insert_delivery_order_draft'); ?>",
														type: "POST",
														beforeSend: function() {

															Swal.fire({
																title: 'Loading ...',
																html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
																timerProgressBar: false,
																showConfirmButton: false
															});

															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	true
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	true
																);
															$("#loadingviewdodraft")
																.show();
														},
														data: {
															delivery_order_batch_id: delivery_order_batch_id,
															delivery_order_draft_id: v
																.delivery_order_draft_id,
															sales_order_id: v
																.sales_order_id,
															delivery_order_draft_kode: v
																.delivery_order_draft_kode,
															delivery_order_draft_yourref: v
																.delivery_order_draft_yourref,
															client_wms_id: v
																.client_wms_id,
															delivery_order_draft_tgl_buat_do: v
																.delivery_order_draft_tgl_buat_do,
															delivery_order_draft_tgl_expired_do: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
																)
																.val(),
															delivery_order_draft_tgl_surat_jalan: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
																)
																.val(),
															delivery_order_draft_tgl_rencana_kirim: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
																)
																.val(),
															delivery_order_draft_tgl_aktual_kirim: v
																.delivery_order_draft_tgl_aktual_kirim,
															delivery_order_draft_keterangan: v
																.delivery_order_draft_keterangan,
															delivery_order_draft_status: v
																.delivery_order_draft_status,
															delivery_order_draft_is_prioritas: v
																.delivery_order_draft_is_prioritas,
															delivery_order_draft_is_need_packing: v
																.delivery_order_draft_is_need_packing,
															delivery_order_draft_tipe_layanan: v
																.delivery_order_draft_tipe_layanan,
															delivery_order_draft_tipe_pembayaran: v
																.delivery_order_draft_tipe_pembayaran,
															delivery_order_draft_sesi_pengiriman: v
																.delivery_order_draft_sesi_pengiriman,
															delivery_order_draft_request_tgl_kirim: v
																.delivery_order_draft_request_tgl_kirim,
															delivery_order_draft_request_jam_kirim: v
																.delivery_order_draft_request_jam_kirim,
															tipe_pengiriman_id: v
																.tipe_pengiriman_id,
															nama_tipe: v
																.nama_tipe,
															confirm_rate: v
																.confirm_rate,
															delivery_order_draft_reff_id: v
																.delivery_order_draft_reff_id,
															delivery_order_draft_reff_no: v
																.delivery_order_draft_reff_no,
															delivery_order_draft_total: v
																.delivery_order_draft_total,
															unit_mandiri_id: v
																.unit_mandiri_id,
															depo_id: v
																.depo_id,
															client_pt_id: v
																.client_pt_id,
															delivery_order_draft_kirim_nama: v
																.delivery_order_draft_kirim_nama,
															delivery_order_draft_kirim_alamat: v
																.delivery_order_draft_kirim_alamat,
															delivery_order_draft_kirim_telp: v
																.delivery_order_draft_kirim_telp,
															delivery_order_draft_kirim_provinsi: v
																.delivery_order_draft_kirim_provinsi,
															delivery_order_draft_kirim_kota: v
																.delivery_order_draft_kirim_kota,
															delivery_order_draft_kirim_kecamatan: v
																.delivery_order_draft_kirim_kecamatan,
															delivery_order_draft_kirim_kelurahan: v
																.delivery_order_draft_kirim_kelurahan,
															delivery_order_draft_kirim_kodepos: v
																.delivery_order_draft_kirim_kodepos,
															delivery_order_draft_kirim_area: v
																.delivery_order_draft_kirim_area,
															delivery_order_draft_kirim_invoice_pdf: v
																.delivery_order_draft_kirim_invoice_pdf,
															delivery_order_draft_kirim_invoice_dir: v
																.delivery_order_draft_kirim_invoice_dir,
															pabrik_id: v
																.pabrik_id,
															delivery_order_draft_ambil_nama: v
																.delivery_order_draft_ambil_nama,
															delivery_order_draft_ambil_alamat: v
																.delivery_order_draft_ambil_alamat,
															delivery_order_draft_ambil_telp: v
																.delivery_order_draft_ambil_telp,
															delivery_order_draft_ambil_provinsi: v
																.delivery_order_draft_ambil_provinsi,
															delivery_order_draft_ambil_kota: v
																.delivery_order_draft_ambil_kota,
															delivery_order_draft_ambil_kecamatan: v
																.delivery_order_draft_ambil_kecamatan,
															delivery_order_draft_ambil_kelurahan: v
																.delivery_order_draft_ambil_kelurahan,
															delivery_order_draft_ambil_kodepos: v
																.delivery_order_draft_ambil_kodepos,
															delivery_order_draft_ambil_area: v
																.delivery_order_draft_ambil_area,
															delivery_order_draft_update_who: v
																.delivery_order_draft_update_who,
															delivery_order_draft_update_tgl: v
																.delivery_order_draft_update_tgl,
															delivery_order_draft_approve_who: v
																.delivery_order_draft_approve_who,
															delivery_order_draft_approve_tgl: v
																.delivery_order_draft_approve_tgl,
															delivery_order_draft_reject_who: v
																.delivery_order_draft_reject_who,
															delivery_order_draft_reject_tgl: v
																.delivery_order_draft_reject_tgl,
															delivery_order_draft_reject_reason: v
																.delivery_order_draft_reject_reason,
															tipe_delivery_order_id: v
																.tipe_delivery_order_id,
															is_from_so: v
																.is_from_so,
															delivery_order_draft_nominal_tunai: v
																.delivery_order_draft_nominal_tunai,
															delivery_order_draft_attachment: v
																.delivery_order_draft_attachment,
															is_promo: v
																.is_promo,
															is_canvas: v
																.is_canvas,
															detail: JSON
																.stringify(
																	arr_detail
																),
															file: "reschedule"
														},
														dataType: "JSON",
														success: function(
															data) {
															// console.log(data.data);

															// if (data.status == true) {
															// 	cek_success++;
															// } else {
															// 	var alert_tes = GetLanguageByKode("CAPTION-ALERT-DATAGAGALDISIMPAN");
															// 	new PNotify
															// 		({
															// 			title: 'Error',
															// 			text: "<strong>Delivery Order " + v.delivery_order_draft_kode + "</strong> " + alert_tes,
															// 			type: 'error',
															// 			styling: 'bootstrap3',
															// 			delay: 3000,
															// 			stack: stack_center
															// 		});
															// }

															if (data
																.type ===
																200
															) {
																cek_success++;
															}

															if (data
																.type ===
																201
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKCUKUP"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});
																// let arrayOfErrorsToDisplay = [];
																// let indexError = [];
																// $.each(data.data, (index, item) => {
																//     let response = item.data;
																//     // arrayOfErrorsToDisplay = []
																//     // indexError = []

																//     indexError.push(index + 1);

																//     arrayOfErrorsToDisplay.push({
																//         title: 'Data Gagal diapprove!',
																//         html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
																//     });
																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexError
																//     })
																//     .queue(arrayOfErrorsToDisplay)
															}

															if (data
																.type ===
																202
															) {
																message_topright
																	("error",
																		"Data gagal Diapprove"
																	);
															}

															if (data
																.type ===
																203
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak cukup, silahkan dicek kembali!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplayEmptySku = [];
																// let indexErrorEmptySku = [];
																// arrayOfErrorsToDisplayEmptySku = []
																// indexErrorEmptySku = []
																// $.each(data.data, (index, item) => {
																//     let response = item.data;


																//     indexErrorEmptySku.push(index + 1);

																//     arrayOfErrorsToDisplayEmptySku
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																//         });

																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorEmptySku
																//     })
																//     .queue(arrayOfErrorsToDisplayEmptySku)
															}

															if (data
																.type ===
																204
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOKURANG"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> kurang!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplaySkuStokKurang = [];
																// let indexErrorSkuStokKurang = [];
																// $.each(data.data, (index, item) => {
																//     let response = item.data;

																//     // arrayOfErrorsToDisplaySkuStokKurang
																//     // = []
																//     // indexErrorSkuStokKurang = []

																//     indexErrorSkuStokKurang.push(index +
																//         1);

																//     arrayOfErrorsToDisplaySkuStokKurang
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
																//         });
																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorSkuStokKurang
																//     })
																//     .queue(arrayOfErrorsToDisplaySkuStokKurang)
															}

															if (data
																.type ===
																205
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak ada!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplayEmptySku = [];
																// let indexErrorEmptySku = [];
																// arrayOfErrorsToDisplayEmptySku = []
																// indexErrorEmptySku = []
																// $.each(data.data, (index, item) => {
																//     let response = item.data;


																//     indexErrorEmptySku.push(index + 1);

																//     arrayOfErrorsToDisplayEmptySku
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																//         });

																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorEmptySku
																//     })
																//     .queue(arrayOfErrorsToDisplayEmptySku)
															}
														},
														error: function(
															xhr,
															ajaxOptions,
															thrownError
														) {
															message("Error",
																"Error 500 Internal Server Connection Failure",
																"error"
															);
															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	false
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	false
																);
															$("#loadingviewdodraft")
																.hide();
															Swal
																.close();
														}
													});
												} else {
													let alert_tes =
														GetLanguageByKode(
															"CAPTION-ALERT-AREATIDAKDIPILIH"
														);

													var msg =
														"<span style='font-weight:bold'>DO " +
														v
														.delivery_order_draft_reff_no +
														"</span> " + alert_tes;
													var msgtype = 'warning';

													//if (!window.__cfRLUnblockHandlers) return false;
													new PNotify
														({
															title: 'Info',
															text: msg,
															type: msgtype,
															styling: 'bootstrap3',
															delay: 3000,
															stack: stack_center
														});

												}
											} else {
												console.log(
													"DO Detail Not Found");
											}
										} else {
											let alert_tes = GetLanguageByKode(
												"CAPTION-ALERT-ADADOYGTIDAKMEMILIKIPERUSAHAAN"
											);

											var msg =
												"<span style='font-weight:bold'>DO " +
												v.delivery_order_draft_kode +
												"</span> " + alert_tes;
											var msgtype = 'error';

											//if (!window.__cfRLUnblockHandlers) return false;
											new PNotify
												({
													title: 'Info',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										}
									});

									if (cek_success > 0) {

										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										$("#loadingviewdodraft").hide();

										let alert_tes = GetLanguageByKode(
											"CAPTION-ALERT-DATABERHASILDISIMPAN");
										message("Success", alert_tes, "success");

										$('input:checkbox').prop("checked", false);
										$("#modal_sisipan_fdjr").modal('hide');

										GetDODraftByFilter();
									} else {
										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										$("#loadingviewdodraft").hide();

										let alert_tes = GetLanguageByKode(
											"CAPTION-ALERT-DATAGAGALDISIMPAN");
										message("Error", alert_tes, "error");
									}

								} else {
									Swal.close();
									message("Error",
										"Error 500 Internal Server Connection Failure",
										"error");
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error",
									"Error 500 Internal Server Connection Failure",
									"error");
								Swal.close();
							}
						});
						CekErrorMsgDOMasal();
					}, 500);
				}
			});
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
	});

	function handlerDataSearchFDJR() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/GetFDJRByFilter') ?>",
			dataType: "JSON",
			data: {
				tgl: $("#filter-sj-date").val(),
				area: $("#area_fdjr").val()
			},
			success: function(response) {
				$("#table_list_data_do_batch > tbody").html('');
				$("#table_list_data_do_batch > tbody").empty();

				if ($.fn.dataTable.isDataTable('#table_list_data_do_batch')) {
					$('#table_list_data_do_batch').DataTable().clear();
					$('#table_list_data_do_batch').DataTable().destroy();
				}

				$("#jml_fdjr").val(response.length);

				if (response != 0) {
					$.each(response, function(i, v) {
						$("#table_list_data_do_batch > tbody").append(`
							<tr>
								<td class="text-center">
									<input type="checkbox" name="CheckboxFDJR" id="check-fdjr-${i}" value="${v.delivery_order_batch_id}" onClick="SetFDJRId(${i},this.value,'${v.delivery_order_batch_update_tgl}')">
								</td>
								<td class="text-center">${i + 1}</td>
								<td class="text-center">${v.delivery_order_batch_tanggal}</td>
								<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
								<td class="text-center">${v.delivery_order_batch_kode}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.karyawan_nama}</td>
								<td class="text-center">${v.delivery_order_batch_tipe_layanan_nama}</td>
								<td class="text-center">${getStringOfArrayString(v.area)}</td>
								<td class="text-center">${v.delivery_order_batch_status}</td>
								<td class="text-center" style="display:none;">${v.delivery_order_batch_update_tgl}</td>
							</tr>
						`);
					});

					$("#table_list_data_do_batch").DataTable({
						'lengthMenu': [
							[-1],
							["All"]
						]
					});

				} else {
					$("#table_list_data_do_batch > tbody").append(`
						<tr style="bg-color: #7E1717;">
							<td colspan="9" class="text-danger text-center" style="color:black;">
								Data Not Found
							</td>
						</tr>
					`);

				}
			}
		})
	}

	function getStringOfArrayString(data) {
		let string = "";

		data.forEach(datum => string += datum + ", ");

		return string.slice(0, -2);
	}

	function SetFDJRId(idx, id, LastUpdate) {
		delivery_order_batch_id = id;
		delivery_order_batch_tgl_update = LastUpdate;

		var checked = $('[id="check-fdjr-' + idx + '"]:checked').length;

		if (checked == 1) {

			$("input:checkbox[name='CheckboxFDJR']").prop("checked", false);
			$("#check-fdjr-" + idx).prop("checked", true);

		} else {
			$("#check-fdjr-" + idx).prop("checked", false);
		}

		// console.log(delivery_order_batch_id);
	}


	function delete_delivery_order_draft_detail_msg() {
		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/delete_delivery_order_draft_detail_msg'); ?>",
			type: "POST",
			dataType: "JSON",
			success: function(data) {
				console.log("delete_delivery_order_draft_detail_msg success");
			}
		});
	}

	function CekErrorMsgDOMasal() {

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/get_delivery_order_draft_detail_msg') ?>",
			dataType: "JSON",
			success: function(response) {

				$('#CAPTION-JUMLAHERRORMSG').fadeOut("slow", function() {
					$(this).hide();
					$("#CAPTION-JUMLAHERRORMSG").html('');
				}).fadeIn("slow", function() {
					$(this).show();
					$("#CAPTION-JUMLAHERRORMSG").html('');
					$("#CAPTION-JUMLAHERRORMSG").append(response.length);

					if (response != "0") {
						document.getElementById("btnerrormsg").classList.add('btn-danger');
						document.getElementById("btnerrormsg").classList.remove('btn-primary');
					} else {
						document.getElementById("btnerrormsg").classList.add('btn-primary');
						document.getElementById("btnerrormsg").classList.remove('btn-danger');
					}
				});

			}
		});
	}

	function ErrorMsgDOMasal() {

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/KelolaDOGagal/get_delivery_order_draft_detail_msg') ?>",
			dataType: "JSON",
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});

				$("#btnapprovdodraft").prop("disabled", true);
				$("#btn-search-data-do-draft").prop("disabled", true);
				$("#loadingviewdodraft").show();
			},
			success: function(response) {

				Swal.close();

				$('#CAPTION-JUMLAHERRORMSG').fadeOut("slow", function() {
					$(this).hide();
					$("#CAPTION-JUMLAHERRORMSG").html('');
				}).fadeIn("slow", function() {
					$(this).show();
					$("#CAPTION-JUMLAHERRORMSG").html('');
					$("#CAPTION-JUMLAHERRORMSG").append(response.length);

					if (response != "") {
						document.getElementById("btnerrormsg").classList.add('btn-danger');
						document.getElementById("btnerrormsg").classList.remove('btn-primary');
					} else {
						document.getElementById("btnerrormsg").classList.add('btn-primary');
						document.getElementById("btnerrormsg").classList.remove('btn-danger');
					}
				});

				$("#modal-pesan-error").modal('show');

				if (response != 0) {

					$('#table-pesan-error').fadeOut("slow", function() {
						$(this).hide();

						$("#table-pesan-error > tbody").empty();

						if ($.fn.DataTable.isDataTable('#table-pesan-error')) {
							$('#table-pesan-error').DataTable().clear();
							$('#table-pesan-error').DataTable().destroy();
						}

					}).fadeIn("slow", function() {
						$(this).show();

						// console.log(response);
						$.each(response, function(i, v) {
							$("#table-pesan-error > tbody").append(`
								<tr>
									<td class="text-center">${i+1}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.delivery_order_draft_kode}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_kode}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
									<td class="text-center text-danger" style="text-align: center; vertical-align: middle;">${v.pesan_error}</td>
								</tr>
							`).fadeIn("slow");
						});

						$('#table-pesan-error').DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All']
							],
							ordering: false,
							// "scrollX": true
						});
					});

				} else {
					$("#table-pesan-error > tbody").append(`
						<tr>
							<td class="text-center" colspan="5"><strong class="text-danger">DATA NOT FOUND</strong></td>
						</tr>
					`);
				}

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			},
			complete: function() {

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			}
		});
	}

	// baru dadakan
	function handlerBuatBaruSTK() {
		var count_do_list = $("#jml_do").val();
		var cek_success = 0;
		arr_do = []
		if (count_do_list > 0) {

			for (var i = 0; i < count_do_list; i++) {
				var checked = $('[id="check-do-' + i + '"]:checked').length;
				var do_id = "'" + $("#check-do-" + i).val() + "'";

				if (checked > 0) {
					arr_do.push(do_id);
				}
			}

			var result = arr_do.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_do = result;
			if (arr_do.length == 0) {
				let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
				message("Error", alert_tes, "error");
				return false;
			}
			$('#modal_sisipan_fdjr_baru').modal('show')

		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
		// var count_do_list = $("#jml_do").val();
		// var cek_success = 0;

		// if (count_do_list > 0) {
		// 	for (var i = 0; i < count_do_list; i++) {
		// 		var checked = $('[id="check-do-' + i + '"]:checked').length;
		// 		var do_id = "'" + $("#check-do-" + i).val() + "'";

		// 		if (checked > 0) {
		// 			arr_do.push(do_id);
		// 		}
		// 	}
		// 	var result = arr_do.reduce((unique, o) => {
		// 		if (!unique.some(obj => obj === o)) {
		// 			unique.push(o);
		// 		}
		// 		return unique;
		// 	}, []);
		// 	arr_do = result;
		// 	// console.log(arr_do);
		// }

		let arr_cb_area_temp = [];
		$('#table_list_data_do_draft tbody tr').each(function(i, v) {
			let cbk = $(this).find("td:eq(0) input[type='checkbox']");
			console.log(cbk.is(":checked"));
			if (cbk.is(":checked")) {
				var tdValue = cbk.closest("tr").find("td:eq(8)").text();
				arr_cb_area_temp.push(tdValue)
				// Do something with the value of the second <td> element
			}
		})
		// console.log(arr_cb_area_temp);

	}

	$("#btn_handler_save_fdjr_baru").on('click', function() {
		var cek_success = 0;
		let tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id option:selected").val()
		let tipe = $("#deliveryorderbatch-tipe_delivery_order_id option:selected").val()
		let karyawan_id = $("#deliveryorderbatch-karyawan_id option:selected").val()
		let kendaraan_id = $("#deliveryorderbatch-kendaraan_id option:selected").val()
		let tanggal_kirim = $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val()
		let tipe_ekspedisi = $("#deliveryorderbatch-tipe_ekspedisi_id").val()
		if (tipe_layanan == '') {
			message("Error", 'Tipe Layanan Kosong Mohon Pilih', "error");
			return false;
		}
		if (tipe == '') {
			message("Error", 'Tipe Kosong Mohon Pilih', "error");
			return false;
		}
		if (tipe_ekspedisi == '') {
			message("Error", 'Tipe Ekspidisi Kosong Mohon Pilih', "error");
			return false;
		}
		if (karyawan_id == '') {
			message("Error", 'Karyawan/Driver Kosong Mohon Pilih', "error");
			return false;
		}
		if (kendaraan_id == '') {
			message("Error", 'Kendaraan Kosong Mohon Pilih', "error");
			return false;
		}
		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();

		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

		let arr_cb_area_temp = [];
		$('#table_list_data_do_draft tbody tr').each(function(i, v) {
			let cbk = $(this).find("td:eq(0) input[type='checkbox']");
			console.log(cbk.is(":checked"));
			if (cbk.is(":checked")) {
				var tdValue = cbk.closest("tr").find("td:eq(8)").text();
				arr_cb_area_temp.push(tdValue)
				// Do something with the value of the second <td> element
			}
		})

		if (arr_do.length > 0) {
			Swal.fire({
				title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
				cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
			}).then((result) => {
				if (result.value == true) {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
					// requestAjax("<?= base_url('WMS/KelolaDOGagal/BuatBaruDariKelolaDOGagal'); ?>", {
					// 	area: arr_cb_area_temp
					// 	// lastUpdated: $("#lastUpdated").val()
					// }, "POST", "JSON", function(response) {
					// 	console.log("response.do_id1", response.do_id);
					// 	if (response.type == 1) {
					// 		console.log("response.do_id2", response.do_id);
					// 		$('#id_do_batch_baru_temp').val(response.do_id);
					// 		// message_topright("success", "Data berhasil Dibuat");
					// 		// setTimeout(() => {
					// 		// 	// location.reload();
					// 		// 	location.reload();
					// 		// }, 1000);
					// 	}
					// 	if (response == 0) return message_topright("error", "Data gagal disimpan");
					// 	// if (response == 2) return messageNotSameLastUpdated()
					// }, "#btnBuatBaru")
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/KelolaDOGagal/BuatBaruDariKelolaDOGagal'); ?>",
						data: {
							area: arr_cb_area_temp,
							// tipe_layanan: tipe_layanan,
							tipe: tipe,
							tanggal_kirim: tanggal_kirim,
							tipe_ekspedisi: tipe_ekspedisi,
							delivery_order_batch_tipe_layanan_id: arr_tipe_layanan[0],
							delivery_order_batch_tipe_layanan_no: arr_tipe_layanan[1],
							delivery_order_batch_tipe_layanan_nama: arr_tipe_layanan[2],
							karyawan_id: karyawan_id,
							kendaraan_id: kendaraan_id,
							arr_do: arr_do
						},
						dataType: "JSON",
						success: function(response) {
							console.log("response.do_id2", response.do_id);
							$('#id_do_batch_baru_temp').val(response.do_id);
							// message_topright("success", "Data berhasil Dibuat");
							// setTimeout(() => {
							// 	// location.reload();
							// 	location.reload();
							// }, 1000);

						}
					})
					// Swal.close();
					delete_delivery_order_draft_detail_msg();

					//ajax save data
					$("#btn_insert_do_to_fdjr").prop("disabled", true);
					$("#btn_search_data_do_draft").prop("disabled", true);
					$("#loadingviewdodraft").show();


					// // requestAjax("<?= base_url('WMS/KelolaDOGagal/BuatBaruDariKelolaDOGagal'); ?>", {

					// // 	area: arr_cb_area_temp
					// // 	// lastUpdated: $("#lastUpdated").val()
					// // }, "POST", "JSON", function(response) {
					// // 	if (response == 1) {
					// // 		message_topright("success", "Data berhasil Dibuat");
					// // 		setTimeout(() => {
					// // 			// location.reload();
					// // 			location.reload();
					// // 		}, 1000);
					// // 	}
					// // 	if (response == 0) return message_topright("error", "Data gagal disimpan");
					// // 	// if (response == 2) return messageNotSameLastUpdated()
					// // }, "#btnBuatBaru")
					// return false;

					setTimeout(function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});
						let id_do_batch_baru_temp = $('#id_do_batch_baru_temp').val();
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalById'); ?>",
							data: {
								arr_do: arr_do
							},
							dataType: "JSON",
							success: function(response) {
								if (response.DOHeader != 0) {

									$.each(response.DOHeader, function(i, v) {
										if (v.client_wms_id !== null) {
											$.ajax({
												async: false,
												url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalDetailByListId'); ?>",
												type: "POST",
												data: {
													id: v
														.delivery_order_draft_reff_id
												},
												dataType: "JSON",
												success: function(
													response) {
													arr_detail = [];

													if (response
														.DODetail !=
														0) {
														$.each(response
															.DODetail,
															function(
																i2,
																v2
															) {
																arr_detail
																	.push({
																		'delivery_order_detail_draft_id': v2
																			.delivery_order_detail_draft_id,
																		'delivery_order_draft_id': v2
																			.delivery_order_draft_id,
																		'sku_id': v2
																			.sku_id,
																		'gudang_id': v2
																			.gudang_id,
																		'gudang_detail_id': v2
																			.gudang_detail_id,
																		'sku_kode': v2
																			.sku_kode,
																		'sku_nama_produk': v2
																			.sku_nama_produk,
																		'sku_harga_satuan': v2
																			.sku_harga_satuan,
																		'sku_disc_percent': v2
																			.sku_disc_percent,
																		'sku_disc_rp': v2
																			.sku_disc_rp,
																		'sku_harga_nett': v2
																			.sku_harga_nett,
																		'sku_request_expdate': v2
																			.sku_request_expdate,
																		'sku_filter_expdate': v2
																			.sku_filter_expdate,
																		'sku_filter_expdatebulan': v2
																			.sku_filter_expdatebulan,
																		'sku_filter_expdatetahun': v2
																			.sku_filter_expdatetahun,
																		'sku_weight': v2
																			.sku_weight,
																		'sku_weight_unit': v2
																			.sku_weight_unit,
																		'sku_length': v2
																			.sku_length,
																		'sku_length_unit': v2
																			.sku_length_unit,
																		'sku_width': v2
																			.sku_width,
																		'sku_width_unit': v2
																			.sku_width_unit,
																		'sku_height': v2
																			.sku_height,
																		'sku_height_unit': v2
																			.sku_height_unit,
																		'sku_volume': v2
																			.sku_volume,
																		'sku_volume_unit': v2
																			.sku_volume_unit,
																		'sku_qty': v2
																			.sku_qty,
																		'sku_keterangan': v2
																			.sku_keterangan,
																		'sku_tipe_stock': v2
																			.tipe_stock_nama
																	});
															});
													}
												},
												error: function(xhr,
													status, error) {
													$("#btn_insert_do_to_fdjr")
														.prop(
															"disabled",
															false);
													$("#btn_search_data_do_draft")
														.prop(
															"disabled",
															false);
													$("#loadingviewdodraft")
														.hide();

													message_topright
														("error",
															"Data gagal Diapprove"
														);
												}
											});

											if (arr_detail.length > 0) {

												if (v
													.delivery_order_draft_kirim_area !=
													"") {
													$.ajax({
														async: false,
														url: "<?= base_url('WMS/KelolaDOGagal/insert_delivery_order_draft'); ?>",
														type: "POST",
														beforeSend: function() {
															Swal.fire({
																title: 'Loading ...',
																html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
																timerProgressBar: false,
																showConfirmButton: false
															});

															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	true
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	true
																);
															$("#loadingviewdodraft")
																.show();
														},
														data: {
															delivery_order_batch_id: id_do_batch_baru_temp,
															delivery_order_draft_id: v
																.delivery_order_draft_id,
															sales_order_id: v
																.sales_order_id,
															delivery_order_draft_kode: v
																.delivery_order_draft_kode,
															delivery_order_draft_yourref: v
																.delivery_order_draft_yourref,
															client_wms_id: v
																.client_wms_id,
															delivery_order_draft_tgl_buat_do: v
																.delivery_order_draft_tgl_buat_do,
															delivery_order_draft_tgl_expired_do: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
																)
																.val(),
															delivery_order_draft_tgl_surat_jalan: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
																)
																.val(),
															delivery_order_draft_tgl_rencana_kirim: tanggal_kirim,
															delivery_order_draft_tgl_aktual_kirim: tanggal_kirim,
															delivery_order_draft_keterangan: v
																.delivery_order_draft_keterangan,
															delivery_order_draft_status: v
																.delivery_order_draft_status,
															delivery_order_draft_is_prioritas: v
																.delivery_order_draft_is_prioritas,
															delivery_order_draft_is_need_packing: v
																.delivery_order_draft_is_need_packing,
															delivery_order_draft_tipe_layanan: v
																.delivery_order_draft_tipe_layanan,
															delivery_order_draft_tipe_pembayaran: v
																.delivery_order_draft_tipe_pembayaran,
															delivery_order_draft_sesi_pengiriman: v
																.delivery_order_draft_sesi_pengiriman,
															delivery_order_draft_request_tgl_kirim: v
																.delivery_order_draft_request_tgl_kirim,
															delivery_order_draft_request_jam_kirim: v
																.delivery_order_draft_request_jam_kirim,
															tipe_pengiriman_id: v
																.tipe_pengiriman_id,
															nama_tipe: v
																.nama_tipe,
															confirm_rate: v
																.confirm_rate,
															delivery_order_draft_reff_id: v
																.delivery_order_draft_reff_id,
															delivery_order_draft_reff_no: v
																.delivery_order_draft_reff_no,
															delivery_order_draft_total: v
																.delivery_order_draft_total,
															unit_mandiri_id: v
																.unit_mandiri_id,
															depo_id: v
																.depo_id,
															client_pt_id: v
																.client_pt_id,
															delivery_order_draft_kirim_nama: v
																.delivery_order_draft_kirim_nama,
															delivery_order_draft_kirim_alamat: v
																.delivery_order_draft_kirim_alamat,
															delivery_order_draft_kirim_telp: v
																.delivery_order_draft_kirim_telp,
															delivery_order_draft_kirim_provinsi: v
																.delivery_order_draft_kirim_provinsi,
															delivery_order_draft_kirim_kota: v
																.delivery_order_draft_kirim_kota,
															delivery_order_draft_kirim_kecamatan: v
																.delivery_order_draft_kirim_kecamatan,
															delivery_order_draft_kirim_kelurahan: v
																.delivery_order_draft_kirim_kelurahan,
															delivery_order_draft_kirim_kodepos: v
																.delivery_order_draft_kirim_kodepos,
															delivery_order_draft_kirim_area: v
																.delivery_order_draft_kirim_area,
															delivery_order_draft_kirim_invoice_pdf: v
																.delivery_order_draft_kirim_invoice_pdf,
															delivery_order_draft_kirim_invoice_dir: v
																.delivery_order_draft_kirim_invoice_dir,
															pabrik_id: v
																.pabrik_id,
															delivery_order_draft_ambil_nama: v
																.delivery_order_draft_ambil_nama,
															delivery_order_draft_ambil_alamat: v
																.delivery_order_draft_ambil_alamat,
															delivery_order_draft_ambil_telp: v
																.delivery_order_draft_ambil_telp,
															delivery_order_draft_ambil_provinsi: v
																.delivery_order_draft_ambil_provinsi,
															delivery_order_draft_ambil_kota: v
																.delivery_order_draft_ambil_kota,
															delivery_order_draft_ambil_kecamatan: v
																.delivery_order_draft_ambil_kecamatan,
															delivery_order_draft_ambil_kelurahan: v
																.delivery_order_draft_ambil_kelurahan,
															delivery_order_draft_ambil_kodepos: v
																.delivery_order_draft_ambil_kodepos,
															delivery_order_draft_ambil_area: v
																.delivery_order_draft_ambil_area,
															delivery_order_draft_update_who: v
																.delivery_order_draft_update_who,
															delivery_order_draft_update_tgl: v
																.delivery_order_draft_update_tgl,
															delivery_order_draft_approve_who: v
																.delivery_order_draft_approve_who,
															delivery_order_draft_approve_tgl: v
																.delivery_order_draft_approve_tgl,
															delivery_order_draft_reject_who: v
																.delivery_order_draft_reject_who,
															delivery_order_draft_reject_tgl: v
																.delivery_order_draft_reject_tgl,
															delivery_order_draft_reject_reason: v
																.delivery_order_draft_reject_reason,
															tipe_delivery_order_id: v
																.tipe_delivery_order_id,
															is_from_so: v
																.is_from_so,
															delivery_order_draft_nominal_tunai: v
																.delivery_order_draft_nominal_tunai,
															delivery_order_draft_attachment: v
																.delivery_order_draft_attachment,
															is_promo: v
																.is_promo,
															is_canvas: v
																.is_canvas,
															detail: JSON
																.stringify(
																	arr_detail
																),
															file: "reschedule",
															area: arr_cb_area_temp,
															arr_do: arr_do
														},
														dataType: "JSON",
														success: function(
															data) {
															if (data
																.type ===
																200 ||
																data
																.type ==
																200
															) {
																cek_success++;
															}

															if (data
																.type ===
																201
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKCUKUP"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});
															}

															if (data
																.type ===
																202
															) {
																message_topright
																	("error",
																		"Data gagal Diapprove"
																	);
															}

															if (data
																.type ===
																203
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak cukup, silahkan dicek kembali!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

															}

															if (data
																.type ===
																204
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOKURANG"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> kurang!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

															}

															if (data
																.type ===
																205
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak ada!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

															}
															if (data
																.type !=
																200
															) {
																$.ajax({
																	async: false,
																	type: 'POST',
																	url: "<?= base_url('WMS/KelolaDOGagal/DeleteBuatBaruDariKelolaDOGagal'); ?>",
																	data: {
																		// area: arr_cb_area_temp
																		delivery_order_batch_id: id_do_batch_baru_temp
																	},
																	dataType: "JSON",
																	success: function(
																		response
																	) {

																		console
																			.log(
																				"delete fdjr baru karena gagal"
																			);
																		// message_topright("success", "Data berhasil Dibuat");
																		// setTimeout(() => {
																		// 	// location.reload();
																		// 	location.reload();
																		// }, 1000);
																		console
																			.log(
																				cek_success
																			);

																	}
																})
															}
														},
														error: function(
															xhr,
															ajaxOptions,
															thrownError
														) {
															message("Error",
																"Error 500 Internal Server Connection Failure",
																"error"
															);
															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	false
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	false
																);
															$("#loadingviewdodraft")
																.hide();
															Swal
																.close();
														}
													});
												} else {
													let alert_tes =
														GetLanguageByKode(
															"CAPTION-ALERT-AREATIDAKDIPILIH"
														);

													var msg =
														"<span style='font-weight:bold'>DO " +
														v
														.delivery_order_draft_reff_no +
														"</span> " + alert_tes;
													var msgtype = 'warning';

													//if (!window.__cfRLUnblockHandlers) return false;
													new PNotify
														({
															title: 'Info',
															text: msg,
															type: msgtype,
															styling: 'bootstrap3',
															delay: 3000,
															stack: stack_center
														});

												}
											} else {
												console.log(
													"DO Detail Not Found");
											}
										} else {
											let alert_tes = GetLanguageByKode(
												"CAPTION-ALERT-ADADOYGTIDAKMEMILIKIPERUSAHAAN"
											);

											var msg =
												"<span style='font-weight:bold'>DO " +
												v.delivery_order_draft_kode +
												"</span> " + alert_tes;
											var msgtype = 'error';

											//if (!window.__cfRLUnblockHandlers) return false;
											new PNotify
												({
													title: 'Info',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										}
									});

									if (cek_success > 0) {

										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										// $("#loadingviewdodraft").hide();

										// let alert_tes = GetLanguageByKode("CAPTION-ALERT-DATABERHASILDISIMPAN");
										setTimeout(() => {
											message_topright("success",
												"Data berhasil disimpan");
											// location.href = "<?= base_url(); ?>WMS/KelolaDOGagal/KelolaDOGagalMenu";
										}, 1000);
										setTimeout(() => {
											location.href =
												"<?= base_url(); ?>WMS/KelolaDOGagal/KelolaDOGagalMenu";
											// $("#modal_sisipan_fdjr").modal('hide');
										}, 500);
										// message("Success", alert_tes, "success");

										$('input:checkbox').prop("checked", false);
										return false;
										// GetDODraftByFilter();
									} else {
										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										$("#loadingviewdodraft").hide();

										let alert_tes = GetLanguageByKode(
											"CAPTION-ALERT-DATAGAGALDISIMPAN");
										message("Error", alert_tes, "error");
									}

								} else {
									Swal.close();
									message("Error",
										"Error 500 Internal Server Connection Failure",
										"error");
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error",
									"Error 500 Internal Server Connection Failure",
									"error");
								Swal.close();
							}
						});
						CekErrorMsgDOMasal();
						Swal.close();
					}, 1000);
					// Swal.close();

				}
			});


		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}



		//end







		// Swal.fire({
		// 	title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
		// 	text: "Apa Anda Yakin Membuat STK Baru?!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
		// 	cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
		// }).then((result) => {
		// if (result.value == true) {


		// messageBoxBeforeRequest('Apa Anda Yakin Membuat STK Baru?!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
		// 	if (result.value == true) {
		// 		requestAjax("<?= base_url('WMS/KelolaDOGagal/BuatBaruDariKelolaDOGagal'); ?>", {
		// 			tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
		// 			tr_mutasi_pallet_id: $("#mutasi_no").val(),
		// 			tipe_mutasi: $("#mutasi_tipe_transaksi").val(),
		// 			mutasi_status: $("#mutasi_status").val(),
		// 			mutasi_keterangan: $("#mutasi_keterangan").val(),
		// 			lastUpdated: $("#lastUpdated").val()
		// 		}, "POST", "JSON", function(response) {
		// 			if (response == 1) {
		// 				message_topright("success", "Data berhasil Dibuat");
		// 				setTimeout(() => {
		// 					// location.reload();
		// 					location.reload();
		// 				}, 1000);

		// 			}
		// 			if (response == 0) return message_topright("error", "Data gagal disimpan");
		// 			if (response == 2) return messageNotSameLastUpdated()
		// 		}, "#btn_update_mutasi")
		// 	}
		// });


		// Swal.fire({
		// 	title: 'STK Berhasil Dibuat',
		// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
		// 	timerProgressBar: false,
		// 	showConfirmButton: false,
		// 	timer: 3000
		// });
		// setTimeout(() => {
		// 	// location.reload();
		// 	location.reload()
		// }, 1000);

		// }
		// })
	})

	$("#btn_rescheduled_save_fdjr").on("click", function() {
		var count_do_list = $("#jml_do").val();
		var cek_success = 0;
		arr_do = [];
		var arr_tgl_kirim_ulang = [];
		if (count_do_list > 0) {

			for (var i = 0; i < count_do_list; i++) {
				var checked = $('[id="check-do-' + i + '"]:checked').length;
				var do_id = "'" + $("#check-do-" + i).val() + "'";
				var tgl_kirim_ulang = $("#tanggal_rencana_kirim_" + i).val();

				if (checked > 0) {
					arr_do.push(do_id);
					arr_tgl_kirim_ulang.push({
						'do_id': $("#check-do-" + i).val(),
						'tgl_kirim_ulang': tgl_kirim_ulang
					});
				}
			}

			var result = arr_do.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_do = result;

			if (arr_do.length == 0) {
				let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
				message("Error", alert_tes, "error");
				return false;
			}
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}

		if (arr_do.length > 0) {

			Swal.fire({
				title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
				cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
			}).then((result) => {
				if (result.value == true) {

					delete_delivery_order_draft_detail_msg();
					//ajax save data
					$("#btn_insert_do_to_fdjr").prop("disabled", true);
					$("#btn_search_data_do_draft").prop("disabled", true);
					// $("#loadingviewdodraft").show();

					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false,
						allowOutsideClick: false
					});
					// setTimeout(function() {
					// $.ajax({
					//     async: false,
					//     type: 'POST',
					//     url: "<?= base_url('WMS/KelolaDOGagal/CekLastUpdateFDJR'); ?>",
					//     data: {
					//         delivery_order_batch_id: delivery_order_batch_id,
					//         delivery_order_batch_tgl_update: delivery_order_batch_tgl_update == "" ? null : delivery_order_batch_tgl_update
					//     },
					//     dataType: "JSON",
					//     success: function(response) {
					//         if (response == 1) {
					//             console.log("Data Aman, Tidak Dihandle orang lain");
					//         }
					//         if (response == 2) {
					//             return messageNotSameLastUpdated()
					//             Swal.close();
					//         }
					//     }
					// })
					// }, 500)

					setTimeout(function() {
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalById'); ?>",
							data: {
								arr_do: arr_do
							},
							dataType: "JSON",
							success: function(response) {
								if (response.DOHeader != 0) {

									$.each(response.DOHeader, function(i, v) {
										var getTglKirimUlang = arr_tgl_kirim_ulang.filter(function(q) {
											return v.delivery_order_draft_id == q.do_id;
										});

										if (v.client_wms_id !== null) {
											$.ajax({
												async: false,
												url: "<?= base_url('WMS/KelolaDOGagal/GetKelolaDOGagalDetailByListId'); ?>",
												type: "POST",
												data: {
													id: v
														.delivery_order_draft_reff_id
												},
												dataType: "JSON",
												success: function(
													response) {
													arr_detail = [];

													if (response
														.DODetail !=
														0) {
														$.each(response
															.DODetail,
															function(
																i2,
																v2
															) {
																arr_detail
																	.push({
																		'delivery_order_detail_draft_id': v2
																			.delivery_order_detail_draft_id,
																		'delivery_order_draft_id': v2
																			.delivery_order_draft_id,
																		'sku_id': v2
																			.sku_id,
																		'gudang_id': v2
																			.gudang_id,
																		'gudang_detail_id': v2
																			.gudang_detail_id,
																		'sku_kode': v2
																			.sku_kode,
																		'sku_nama_produk': v2
																			.sku_nama_produk,
																		// 'sku_harga_satuan': v2
																		// 	.sku_harga_satuan,
																		'sku_harga_satuan': 1,
																		'sku_disc_percent': v2
																			.sku_disc_percent,
																		'sku_disc_rp': v2
																			.sku_disc_rp,
																		// 'sku_harga_nett': v2
																		// 	.sku_harga_nett,
																		'sku_harga_nett': 1,
																		'sku_request_expdate': v2
																			.sku_request_expdate,
																		'sku_filter_expdate': v2
																			.sku_filter_expdate,
																		'sku_filter_expdatebulan': v2
																			.sku_filter_expdatebulan,
																		'sku_filter_expdatetahun': v2
																			.sku_filter_expdatetahun,
																		'sku_weight': v2
																			.sku_weight,
																		'sku_weight_unit': v2
																			.sku_weight_unit,
																		'sku_length': v2
																			.sku_length,
																		'sku_length_unit': v2
																			.sku_length_unit,
																		'sku_width': v2
																			.sku_width,
																		'sku_width_unit': v2
																			.sku_width_unit,
																		'sku_height': v2
																			.sku_height,
																		'sku_height_unit': v2
																			.sku_height_unit,
																		'sku_volume': v2
																			.sku_volume,
																		'sku_volume_unit': v2
																			.sku_volume_unit,
																		'sku_qty': v2
																			.sku_qty,
																		'sku_keterangan': v2
																			.sku_keterangan,
																		'sku_tipe_stock': v2
																			.tipe_stock_nama
																	});
															});
													}
												},
												error: function(xhr,
													status, error) {
													$("#btn_insert_do_to_fdjr")
														.prop(
															"disabled",
															false);
													$("#btn_search_data_do_draft")
														.prop(
															"disabled",
															false);
													$("#loadingviewdodraft")
														.hide();

													message_topright
														("error",
															"Data gagal Diapprove"
														);
												}
											});

											if (arr_detail.length > 0) {

												if (v
													.delivery_order_draft_kirim_area !=
													"") {
													$.ajax({
														async: false,
														url: "<?= base_url('WMS/KelolaDOGagal/insert_delivery_order_draft2'); ?>",
														type: "POST",
														beforeSend: function() {

															Swal.fire({
																title: 'Loading ...',
																html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
																timerProgressBar: false,
																showConfirmButton: false
															});

															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	true
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	true
																);
															$("#loadingviewdodraft")
																.show();
														},
														data: {
															delivery_order_batch_id: delivery_order_batch_id,
															delivery_order_draft_id: v
																.delivery_order_draft_id,
															sales_order_id: v
																.sales_order_id,
															delivery_order_draft_kode: v
																.delivery_order_draft_kode,
															delivery_order_draft_yourref: v
																.delivery_order_draft_yourref,
															client_wms_id: v
																.client_wms_id,
															delivery_order_draft_tgl_buat_do: v
																.delivery_order_draft_tgl_buat_do,
															delivery_order_draft_tgl_expired_do: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
																)
																.val(),
															delivery_order_draft_tgl_surat_jalan: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
																)
																.val(),
															delivery_order_draft_tgl_rencana_kirim: $(
																	"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
																)
																.val(),
															delivery_order_draft_tgl_aktual_kirim: v
																.delivery_order_draft_tgl_aktual_kirim,
															delivery_order_draft_keterangan: v
																.delivery_order_draft_keterangan,
															delivery_order_draft_status: v
																.delivery_order_draft_status,
															delivery_order_draft_is_prioritas: v
																.delivery_order_draft_is_prioritas,
															delivery_order_draft_is_need_packing: v
																.delivery_order_draft_is_need_packing,
															delivery_order_draft_tipe_layanan: v
																.delivery_order_draft_tipe_layanan,
															delivery_order_draft_tipe_pembayaran: v
																.delivery_order_draft_tipe_pembayaran,
															delivery_order_draft_sesi_pengiriman: v
																.delivery_order_draft_sesi_pengiriman,
															delivery_order_draft_request_tgl_kirim: v
																.delivery_order_draft_request_tgl_kirim,
															delivery_order_draft_request_jam_kirim: v
																.delivery_order_draft_request_jam_kirim,
															tipe_pengiriman_id: v
																.tipe_pengiriman_id,
															nama_tipe: v
																.nama_tipe,
															confirm_rate: v
																.confirm_rate,
															delivery_order_draft_reff_id: v
																.delivery_order_draft_reff_id,
															delivery_order_draft_reff_no: v
																.delivery_order_draft_reff_no,
															delivery_order_draft_total: v
																.delivery_order_draft_total,
															unit_mandiri_id: v
																.unit_mandiri_id,
															depo_id: v
																.depo_id,
															client_pt_id: v
																.client_pt_id,
															delivery_order_draft_kirim_nama: v
																.delivery_order_draft_kirim_nama,
															delivery_order_draft_kirim_alamat: v
																.delivery_order_draft_kirim_alamat,
															delivery_order_draft_kirim_telp: v
																.delivery_order_draft_kirim_telp,
															delivery_order_draft_kirim_provinsi: v
																.delivery_order_draft_kirim_provinsi,
															delivery_order_draft_kirim_kota: v
																.delivery_order_draft_kirim_kota,
															delivery_order_draft_kirim_kecamatan: v
																.delivery_order_draft_kirim_kecamatan,
															delivery_order_draft_kirim_kelurahan: v
																.delivery_order_draft_kirim_kelurahan,
															delivery_order_draft_kirim_kodepos: v
																.delivery_order_draft_kirim_kodepos,
															delivery_order_draft_kirim_area: v
																.delivery_order_draft_kirim_area,
															delivery_order_draft_kirim_invoice_pdf: v
																.delivery_order_draft_kirim_invoice_pdf,
															delivery_order_draft_kirim_invoice_dir: v
																.delivery_order_draft_kirim_invoice_dir,
															pabrik_id: v
																.pabrik_id,
															delivery_order_draft_ambil_nama: v
																.delivery_order_draft_ambil_nama,
															delivery_order_draft_ambil_alamat: v
																.delivery_order_draft_ambil_alamat,
															delivery_order_draft_ambil_telp: v
																.delivery_order_draft_ambil_telp,
															delivery_order_draft_ambil_provinsi: v
																.delivery_order_draft_ambil_provinsi,
															delivery_order_draft_ambil_kota: v
																.delivery_order_draft_ambil_kota,
															delivery_order_draft_ambil_kecamatan: v
																.delivery_order_draft_ambil_kecamatan,
															delivery_order_draft_ambil_kelurahan: v
																.delivery_order_draft_ambil_kelurahan,
															delivery_order_draft_ambil_kodepos: v
																.delivery_order_draft_ambil_kodepos,
															delivery_order_draft_ambil_area: v
																.delivery_order_draft_ambil_area,
															delivery_order_draft_update_who: v
																.delivery_order_draft_update_who,
															delivery_order_draft_update_tgl: v
																.delivery_order_draft_update_tgl,
															delivery_order_draft_approve_who: v
																.delivery_order_draft_approve_who,
															delivery_order_draft_approve_tgl: v
																.delivery_order_draft_approve_tgl,
															delivery_order_draft_reject_who: v
																.delivery_order_draft_reject_who,
															delivery_order_draft_reject_tgl: v
																.delivery_order_draft_reject_tgl,
															delivery_order_draft_reject_reason: v
																.delivery_order_draft_reject_reason,
															tipe_delivery_order_id: v.tipe_delivery_order_nama == 'Retur' ? 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD' : v.tipe_delivery_order_id,
															is_from_so: v
																.is_from_so,
															delivery_order_draft_nominal_tunai: v
																.delivery_order_draft_nominal_tunai,
															delivery_order_draft_attachment: v
																.delivery_order_draft_attachment,
															is_promo: v
																.is_promo,
															is_canvas: v
																.is_canvas,
															detail: JSON
																.stringify(
																	arr_detail
																),
															file: "reschedule",
															tipe_delivery_order_nama: v.tipe_delivery_order_nama,
															tgl_kirim_ulang: getTglKirimUlang[0]['tgl_kirim_ulang'],
															status: 'reschedule'
														},
														dataType: "JSON",
														success: function(
															data) {
															// console.log(data.data);

															// if (data.status == true) {
															// 	cek_success++;
															// } else {
															// 	var alert_tes = GetLanguageByKode("CAPTION-ALERT-DATAGAGALDISIMPAN");
															// 	new PNotify
															// 		({
															// 			title: 'Error',
															// 			text: "<strong>Delivery Order " + v.delivery_order_draft_kode + "</strong> " + alert_tes,
															// 			type: 'error',
															// 			styling: 'bootstrap3',
															// 			delay: 3000,
															// 			stack: stack_center
															// 		});
															// }

															if (data
																.type ===
																200
															) {
																cek_success++;
															}

															if (data
																.type ===
																201
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKCUKUP"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});
																// let arrayOfErrorsToDisplay = [];
																// let indexError = [];
																// $.each(data.data, (index, item) => {
																//     let response = item.data;
																//     // arrayOfErrorsToDisplay = []
																//     // indexError = []

																//     indexError.push(index + 1);

																//     arrayOfErrorsToDisplay.push({
																//         title: 'Data Gagal diapprove!',
																//         html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
																//     });
																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexError
																//     })
																//     .queue(arrayOfErrorsToDisplay)
															}

															if (data
																.type ===
																202
															) {
																message_topright
																	("error",
																		"Data gagal Diapprove"
																	);
															}

															if (data
																.type ===
																203
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak cukup, silahkan dicek kembali!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplayEmptySku = [];
																// let indexErrorEmptySku = [];
																// arrayOfErrorsToDisplayEmptySku = []
																// indexErrorEmptySku = []
																// $.each(data.data, (index, item) => {
																//     let response = item.data;


																//     indexErrorEmptySku.push(index + 1);

																//     arrayOfErrorsToDisplayEmptySku
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																//         });

																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorEmptySku
																//     })
																//     .queue(arrayOfErrorsToDisplayEmptySku)
															}

															if (data
																.type ===
																204
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOKURANG"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> kurang!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplaySkuStokKurang = [];
																// let indexErrorSkuStokKurang = [];
																// $.each(data.data, (index, item) => {
																//     let response = item.data;

																//     // arrayOfErrorsToDisplaySkuStokKurang
																//     // = []
																//     // indexErrorSkuStokKurang = []

																//     indexErrorSkuStokKurang.push(index +
																//         1);

																//     arrayOfErrorsToDisplaySkuStokKurang
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
																//         });
																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorSkuStokKurang
																//     })
																//     .queue(arrayOfErrorsToDisplaySkuStokKurang)
															}

															if (data
																.type ===
																205
															) {
																let alert_tes =
																	GetLanguageByKode(
																		"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																	);

																var msg =
																	"<span style='font-weight:bold'>DO " +
																	v
																	.delivery_order_draft_kode +
																	"</span> " +
																	alert_tes;
																var msgtype =
																	'error';

																//if (!window.__cfRLUnblockHandlers) return false;
																new PNotify
																	({
																		title: 'Info',
																		text: msg,
																		type: msgtype,
																		styling: 'bootstrap3',
																		delay: 3000,
																		stack: stack_center
																	});

																$.each(data
																	.data,
																	(index,
																		item
																	) => {
																		let response =
																			item
																			.data;
																		// arrayOfErrorsToDisplay = []
																		// indexError = []

																		$.ajax({
																			async: false,
																			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																			type: "POST",
																			dataType: "JSON",
																			data: {
																				delivery_order_draft_id: v
																					.delivery_order_draft_id,
																				sku_kode: response
																					.sku_kode,
																				msg: "Qty dari SKU <strong>" +
																					response
																					.sku_kode +
																					", " +
																					response
																					.sku +
																					"</strong> tidak ada!!"
																			},
																			success: function(
																				data
																			) {
																				console
																					.log(
																						"insert_delivery_order_draft_detail_msg success"
																					);
																			}
																		});
																	}
																);

																// let arrayOfErrorsToDisplayEmptySku = [];
																// let indexErrorEmptySku = [];
																// arrayOfErrorsToDisplayEmptySku = []
																// indexErrorEmptySku = []
																// $.each(data.data, (index, item) => {
																//     let response = item.data;


																//     indexErrorEmptySku.push(index + 1);

																//     arrayOfErrorsToDisplayEmptySku
																//         .push({
																//             title: 'Data Gagal diapprove!',
																//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																//         });

																//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																// });

																// Swal.mixin({
																//         icon: 'error',
																//         confirmButtonText: 'Next &rarr;',
																//         showCancelButton: true,
																//         progressSteps: indexErrorEmptySku
																//     })
																//     .queue(arrayOfErrorsToDisplayEmptySku)
															}
														},
														error: function(
															xhr,
															ajaxOptions,
															thrownError
														) {
															message("Error",
																"Error 500 Internal Server Connection Failure",
																"error"
															);
															$("#btn_search_data_do_draft")
																.prop(
																	"disabled",
																	false
																);
															$("#btn_insert_do_to_fdjr")
																.prop(
																	"disabled",
																	false
																);
															$("#loadingviewdodraft")
																.hide();
															Swal
																.close();
														}
													});
												} else {
													let alert_tes =
														GetLanguageByKode(
															"CAPTION-ALERT-AREATIDAKDIPILIH"
														);

													var msg =
														"<span style='font-weight:bold'>DO " +
														v
														.delivery_order_draft_reff_no +
														"</span> " + alert_tes;
													var msgtype = 'warning';

													//if (!window.__cfRLUnblockHandlers) return false;
													new PNotify
														({
															title: 'Info',
															text: msg,
															type: msgtype,
															styling: 'bootstrap3',
															delay: 3000,
															stack: stack_center
														});

												}
											} else {
												console.log(
													"DO Detail Not Found");
											}
										} else {
											let alert_tes = GetLanguageByKode(
												"CAPTION-ALERT-ADADOYGTIDAKMEMILIKIPERUSAHAAN"
											);

											var msg =
												"<span style='font-weight:bold'>DO " +
												v.delivery_order_draft_kode +
												"</span> " + alert_tes;
											var msgtype = 'error';

											//if (!window.__cfRLUnblockHandlers) return false;
											new PNotify
												({
													title: 'Info',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										}
									});

									if (cek_success > 0) {

										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										$("#loadingviewdodraft").hide();

										let alert_tes = GetLanguageByKode(
											"CAPTION-ALERT-DATABERHASILDISIMPAN");
										message("Success", alert_tes, "success");

										$('input:checkbox').prop("checked", false);
										$("#modal_sisipan_fdjr").modal('hide');

										GetDODraftByFilter();
									} else {
										$("#btn_insert_do_to_fdjr").prop("disabled",
											false);
										$("#btn_search_data_do_draft").prop("disabled",
											false);
										$("#loadingviewdodraft").hide();

										let alert_tes = GetLanguageByKode(
											"CAPTION-ALERT-DATAGAGALDISIMPAN");
										message("Error", alert_tes, "error");
									}

								} else {
									Swal.close();
									message("Error",
										"Error 500 Internal Server Connection Failure",
										"error");
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error",
									"Error 500 Internal Server Connection Failure",
									"error");
								Swal.close();
							}
						});
						CekErrorMsgDOMasal();
					}, 500);
				}
			});
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
	});

	$("#btn_not_rescheduled_save_fdjr").on("click", function() {
		var count_do_list = $("#jml_do").val();
		arr_do = [];

		if (count_do_list > 0) {

			for (var i = 0; i < count_do_list; i++) {
				var checked = $('[id="check-do-' + i + '"]:checked').length;
				var do_id = $("#check-do-" + i).val();

				if (checked > 0) {
					arr_do.push(do_id);
				}
			}

			var result = arr_do.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_do = result;

			if (arr_do.length == 0) {
				let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
				message("Error", alert_tes, "error");
				return false;
			}
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}

		if (arr_do.length > 0) {
			$("#modal_not_rescheduled_alert").modal('show');

			// Swal.fire({
			// 	title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
			// 	icon: "warning",
			// 	text: 'Pilih Salah Satu Aksi Dibawah Ini!',
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	denyButtonColor: "#28a745",
			// 	confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
			// 	cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		requestAjax("<?= base_url('WMS/KelolaDOGagal/updateIsRescheduleDeliveryOrder'); ?>", {
			// 			arr_do_id: arr_do,
			// 			status: 'not reschedule'
			// 		}, "POST", "JSON", function(data) {
			// 			if (data == 1) {
			// 				message("Success", 'Data Berhasil Disimpan!', "success");
			// 				GetDODraftByFilter();
			// 			} else {
			// 				message("Error", 'Data Gagal Disimpan!', "error");
			// 			}

			// 		}, "#btn_not_rescheduled_save_fdjr")
			// 	} else if (result.isDenied) {
			// 		// Aksi untuk tombol Delete
			// 		message("Info", "Anda memilih untuk menghapus data.", "info");
			// 	}
			// });
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
	})

	function generateDraftMutasi() {
		$("#modal_not_rescheduled_alert").modal('hide');
		requestAjax("<?= base_url('WMS/KelolaDOGagal/updateIsRescheduleDeliveryOrder'); ?>", {
			arr_do_id: arr_do,
			status: 'not reschedule'
		}, "POST", "JSON", function(data) {
			if (data == 1) {
				message("Success", 'Data Berhasil Disimpan!', "success");
				GetDODraftByFilter();

			} else {
				message("Error", 'Data Gagal Disimpan!', "error");

			}

		}, "#btn_not_rescheduled_save_fdjr")
	}

	function generateLayoutingPallet() {
		$("#modal_not_rescheduled_alert").modal('hide');
		requestAjax("<?= base_url('WMS/KelolaDOGagal/updateIsRescheduleDeliveryOrder2'); ?>", {
			arr_do_id: arr_do,
			status: 'not reschedule'
		}, "POST", "JSON", function(data) {
			if (data == 1) {
				message("Success", 'Data Berhasil Disimpan!', "success");
				GetDODraftByFilter();
			} else {
				message("Error", 'Data Gagal Disimpan!', "error");
			}

		}, "#btn_not_rescheduled_save_fdjr")
	}

	function PushArrayDONotReschedule(delivery_order_id, idx) {

		var checked = $('[id="check-do-' + idx + '-not-reschedule"]:checked').length;

		$("#select-do-not-reschedule").prop("checked", false);

		if (checked > 0) {

			arr_do_not_reschedule.push({
				'delivery_order_id': delivery_order_id,
				'delivery_order_kode': $("#delivery_order_kode_" + idx + "_not_schedule").val(),
				'tanggal_rencana_kirim': $("#tanggal_rencana_kirim_" + idx + "_not_schedule").val(),
				'tipe_delivery_order_id': $("#tipe_delivery_order_id_" + idx + "_not_schedule").val(),
			})
		} else {
			const findIndexData = arr_do_not_reschedule.findIndex((value) => value.delivery_order_id == delivery_order_id);
			if (findIndexData > -1) { // only splice array when item is found
				arr_do_not_reschedule.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
		}

		console.log(arr_do_not_reschedule);
	}

	function UpdateArrayDONotReschedule(delivery_order_id, idx) {
		const findIndexData = arr_do_not_reschedule.findIndex((value) => value.delivery_order_id == delivery_order_id);
		const isChecked = $("#check-do-" + idx + "-not-reschedule").prop('checked');

		if (isChecked) {
			if (findIndexData > -1) {
				arr_do_not_reschedule[findIndexData] = ({
					'delivery_order_id': delivery_order_id,
					'delivery_order_kode': $("#delivery_order_kode_" + idx + "_not_schedule").val(),
					'tanggal_rencana_kirim': $("#tanggal_rencana_kirim_" + idx + "_not_schedule").val(),
					'tipe_delivery_order_id': $("#tipe_delivery_order_id_" + idx + "_not_schedule").val(),
				});
			}
		} else {
			arr_do_not_reschedule.splice(findIndexData, 1);
		}
	}

	$("#btn_generate_do_not_reschedule").on("click", function() {
		// console.log(arr_do_not_reschedule);

		if (arr_do_not_reschedule.length == 0) {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
			return false;
		}

		Swal.fire({
			title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
			cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					async: false,
					url: "<?= base_url('WMS/KelolaDOGagal/GenerateDONotReschedule'); ?>",
					type: "POST",
					beforeSend: function() {

						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn_generate_do_not_reschedule").prop("disabled", true);
					},
					data: {
						arr_do_not_reschedule: arr_do_not_reschedule
					},
					dataType: "JSON",
					success: function(response) {

						if (response.type == 1) {
							let alert_tes = GetLanguageByKode("CAPTION-ALERT-GENERATEDDOSUCCESS");
							message("Success", alert_tes, "success");

							setTimeout(() => {
								GetDODraftByFilter();
							}, 1000);
						} else if (response.type == 2) {
							let arrayOfErrorsToDisplay = [];
							let indexError = [];
							$.each(response.data, (index, item) => {
								// arrayOfErrorsToDisplay = []
								// indexError = []
								indexError.push(index + 1);
								arrayOfErrorsToDisplay.push({
									title: 'Data Gagal Generate!',
									html: `Tipe delivery order <strong>${item}</strong> adalah retur!`
								});
								// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
							});

							Swal.mixin({
								icon: 'error',
								confirmButtonText: 'Next &rarr;',
								showCancelButton: true,
								progressSteps: indexError
							}).queue(arrayOfErrorsToDisplay)
						} else {
							let alert_tes = GetLanguageByKode("CAPTION-ALERT-GENERATEDDOGAGAL");
							message("Error", alert_tes, "error");
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");
						$("#btn_generate_do_not_reschedule").prop("disabled", false);
						Swal.close();
					},
					complete: function() {
						$("#btn_generate_do_not_reschedule").prop("disabled", false);
						// Swal.close();
					}
				});
			}
		});
	});
</script>