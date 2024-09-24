<script type="text/javascript">
	var ChannelCode = '';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			// if ($('#filter_tanggal').length > 0) {
			// 	$('#filter_tanggal').daterangepicker({
			// 		'applyClass': 'btn-sm btn-success',
			// 		'cancelClass': 'btn-sm btn-default',
			// 		locale: {
			// 			"format": "DD/MM/YYYY",
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		},
			// 		'startDate': '<?= date("01-m-Y") ?>',
			// 		'endDate': '<?= date("t-m-Y") ?>'
			// 	});
			// }
		}
	);

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(titleType, iconType, htmlType) {
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
	// 		title: titleType,
	// 		icon: iconType,
	// 		html: htmlType,
	// 	});
	// }

	$(document).on("change", "#check_scan", function(e) {
		if (e.target.checked) {
			$("#filter_switched").val("1");
		} else {
			$("#filter_switched").val("0");
		}
	});

	$("#btn_save_stock_movement").click(function() {

		if ($("#filter_perusahaan").val() == "") {
			let alert_tes = GetLanguageByKode("CAPTION-MSG03");
			message_custom("Error", "error", alert_tes);
			return false;
		}

		if ($("#filter_tipe_transaksi").val() == "") {
			let alert_tes = GetLanguageByKode("CAPTION-PILIHTIPETRANSAKSI");
			message_custom("Error", "error", alert_tes);
			return false;
		}

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SalesOrder/SaveStockMovementBosnet') ?>",
			data: {
				tgl: $("#filter_tanggal").val(),
				perusahaan: $("#filter_perusahaan").val(),
				tipe_transaksi: $("#filter_tipe_transaksi").val()
			},
			dataType: "JSON",
			beforeSend: function() {

				$("#loadingview").show();
				$("#btn_refresh_stock_movement").prop("disabled", true);
				$("#btn_save_stock_movement").prop("disabled", true);
				$("#btn_generate_retur_supplier").prop("disabled", true);

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			success: function(response) {

				// console.log(response);
				$.each(response, function(i, v) {
					if (v.kode == 1) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Success", "success", alert_tes);

						setTimeout(() => {
							GetStockMovementWMS();
						}, 1000);


					} else if (v.kode == 2) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					} else if (v.kode == 3) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					} else {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					}
				});

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);

				// console.log(response);

			},
			error: function(xhr, ajaxOptions, thrownError) {

				message("Error", "Error 500 Internal Server Connection Failure", "error");

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			},
			complete: function() {

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			}
		});
	});

	$("#btn_generate_retur_supplier").click(function() {
		if ($("#filter_perusahaan").val() == "") {
			let alert_tes = GetLanguageByKode("CAPTION-MSG03");
			message_custom("Error", "error", alert_tes);
			return false;
		}

		if ($("#filter_tipe_transaksi").val() == "") {
			let alert_tes = GetLanguageByKode("CAPTION-PILIHTIPETRANSAKSI");
			message_custom("Error", "error", alert_tes);
			return false;
		}

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SalesOrder/Generate_retur_supplier') ?>",
			data: {
				tgl: $("#filter_tanggal").val(),
				perusahaan: $("#filter_perusahaan").val(),
				tipe_transaksi: $("#filter_tipe_transaksi").val()
			},
			dataType: "JSON",
			beforeSend: function() {

				$("#loadingview").show();
				$("#btn_refresh_stock_movement").prop("disabled", true);
				$("#btn_save_stock_movement").prop("disabled", true);
				$("#btn_generate_retur_supplier").prop("disabled", true);

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			success: function(response) {

				$.each(response, function(i, v) {
					if (v.kode == 1) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Success", "success", alert_tes);

						setTimeout(() => {
							GetStockMovementWMS();
						}, 1000);


					} else if (v.kode == 2) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					} else if (v.kode == 3) {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					} else {

						let alert_tes = GetLanguageByKode(v.msg);
						message_custom("Error", "error", alert_tes);

					}
				});

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);

				// console.log(response);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			},
			complete: function() {

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			}
		});

	});

	$("#btn_refresh_stock_movement").click(
		function() {
			GetStockMovementWMS();
		}
	);

	function GetStockMovementWMS() {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SalesOrder/Get_stock_movement_bosnet_wms') ?>",
			data: {
				tgl: $("#filter_tanggal").val(),
				perusahaan: $("#filter_perusahaan").val(),
				tipe_transaksi: $("#filter_tipe_transaksi").val()
			},
			dataType: "JSON",
			beforeSend: function() {

				$("#loadingview").show();
				$("#btn_refresh_stock_movement").prop("disabled", true);
				$("#btn_save_stock_movement").prop("disabled", true);
				$("#btn_generate_retur_supplier").prop("disabled", true);

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			success: function(response) {

				$('#table_stock_movement_wms').fadeOut("slow", function() {
					$(this).hide();

					$("#table_stock_movement_wms > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table_stock_movement_wms')) {
						$('#table_stock_movement_wms').DataTable().clear();
						$('#table_stock_movement_wms').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();
					if (response != 0) {

						// console.log(response.CanvasInDO);
						$.each(response, function(i, v) {
							$("#table_stock_movement_wms > tbody").append(`
								<tr>
									<td class="text-left">${v.dtmTransaction}</td>
									<td class="text-left">${v.szTrnId}</td>
									<td class="text-left">${v.szPrincipleId}</td>
									<td class="text-left">${v.szProductId}</td>
									<td class="text-left">${v.szProductName}</td>
									<td class="text-right">${v.decQty}</td>
								</tr>
							`)
						});

						$('#table_stock_movement_wms').DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All']
							],
						});
					}
				});

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				Swal.close()

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			},
			complete: function() {
				Swal.close();

				$("#loadingview").hide();
				$("#btn_refresh_stock_movement").prop("disabled", false);
				$("#btn_save_stock_movement").prop("disabled", false);
				$("#btn_generate_retur_supplier").prop("disabled", false);
			}
		});
	}

	function SyncCustomer(client_pt_id, idx, status) {
		// if (status == 0) {

		$("#item-" + idx + "-loading-customer").show();
		$("#item-" + idx + "-status_customer").prop("disabled", true);
		$(".btn-sync").prop("disabled", true);

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/SalesOrder/Sync_customer') ?>",
			data: {
				customer_id: client_pt_id,
				perusahaan_eksternal: $("#filter_perusahaan").val(),
				switched: $("#filter_switched").val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#item-" + idx + "-loading-customer").hide();
				$("#item-" + idx + "-status_customer").prop("disabled", false);
				$(".btn-sync").prop("disabled", false);

				if (response != "") {

					$.each(response, function(i, v) {
						if (v.kode == 1) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERBERHASIL');
							message_custom("Success", "success", alert_tes);

							GetStockMovementWMS();

						} else if (v.kode == 0) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
							message_custom("Error", "error", alert_tes);

						} else if (v.kode == 3) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-MAPPINGCUSTOMEREKSTERNALNOTFOUND');
							message_custom("Error", "error", alert_tes);

						} else {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
							message_custom("Error", "error", alert_tes);

						}
					})

				} else {
					let alert_tes = GetLanguageByKode('CAPTION-ALERT-SUDAHSYNCCUSTOMER');
					message_custom("Warning", "warning", alert_tes);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {

				$("#item-" + idx + "-loading-customer").hide();
				$("#item-" + idx + "-status_customer").prop("disabled", false);
				$(".btn-sync").prop("disabled", false);

				let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
				message_custom("Error", "error", alert_tes);
			}
		});
		// } else {

		// 	let alert_tes = GetLanguageByKode('CAPTION-ALERT-SUDAHSYNCCUSTOMER');
		// 	message_custom("Warning", "warning", alert_tes);

		// }

	}
</script>