<script type="text/javascript">
	var ChannelCode = '';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			// if ($('#datesofas').length > 0) {
			// 	$('#datesofas').daterangepicker({
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

	$("#btnsavesofas").click(
		function() {
			var tgl = $("#datesofas").val();
			var perusahaan = $("#filter_perusahaan").val();
			var switched = $("#filter_switched").val();

			$("#loadingview").show();
			$("#btnrefreshsofas").prop("disabled", true);
			$("#btnsavesofas").prop("disabled", true);
			$("#btnsavedowms").prop("disabled", true);

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SalesOrder/SaveSalesOrderFAS') ?>",
				data: {
					tgl: tgl,
					perusahaan: perusahaan,
					switched: switched
				},
				dataType: "JSON",
				success: function(response) {

					// console.log(response);
					$.each(response, function(i, v) {
						if (v.kode == 1) {

							let alert_tes = GetLanguageByKode(v.msg);
							message_custom("Success", "success", alert_tes);

						} else if (v.kode == 2) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " + alert_tes;
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
						} else if (v.kode == 3) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " + alert_tes;
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
						} else {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED');
							message_custom("Error", "error", alert_tes);

						}
					});

					$("#loadingview").hide();
					$("#btnrefreshsofas").prop("disabled", false);
					$("#btnsavesofas").prop("disabled", false);
					$("#btnsavedowms").prop("disabled", false);

					// console.log(response);

				}
			});

			GetSalesOrderFAS();
		}
	);

	$("#btnsavedowms").click(
		function() {
			var tgl = $("#datesofas").val();
			var perusahaan_eksternal = $("#filter_perusahaan").val()

			$("#loadingview").show();
			$("#btnrefreshsofas").prop("disabled", true);
			$("#btnsavesofas").prop("disabled", true);
			$("#btnsavedowms").prop("disabled", true);

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SalesOrder/SaveDeliveryOrder') ?>",
				data: {
					tgl: tgl,
					perusahaan_eksternal: perusahaan_eksternal
				},
				dataType: "JSON",
				success: function(response) {

					// console.log(response);
					$.each(response, function(i, v) {
						if (v.kode == 1) {

							let alert_tes = GetLanguageByKode(v.msg);
							message_custom("Success", "success", alert_tes);

						} else if (v.kode == 2) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " + alert_tes;
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
						} else if (v.kode == 3) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " + alert_tes;
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
						} else if (v.kode == 4) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = alert_tes;
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
						} else {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED');
							message_custom("Error", "error", alert_tes);

						}
					});

					$("#loadingview").hide();
					$("#btnrefreshsofas").prop("disabled", false);
					$("#btnsavesofas").prop("disabled", false);
					$("#btnsavedowms").prop("disabled", false);

					// console.log(response);

				}
			});

			GetSalesOrderFAS();
		}
	);

	$("#btnrefreshsofas").click(
		function() {
			GetSalesOrderFAS();
		}
	);

	function GetSalesOrderFAS() {
		var tgl = $("#datesofas").val();
		var status = $("#status_sync").val();

		$("#loadingview").show();
		$("#btnrefreshsofas").prop("disabled", true);
		$("#btnsavesofas").prop("disabled", true);
		$("#btnsavedowms").prop("disabled", true);

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SalesOrder/GetSalesOrderFAS') ?>",
			data: {
				tgl: tgl,
				status: status,
				perusahaan_eksternal: $("#filter_perusahaan").val()
			},
			dataType: "JSON",
			success: function(response) {

				$("#table-so-not-in-do > tbody").empty();
				$("#table-so-in-do > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table-so-not-in-do')) {
					$('#table-so-not-in-do').DataTable().clear();
					$('#table-so-not-in-do').DataTable().destroy();
				}

				if ($.fn.DataTable.isDataTable('#table-so-in-do')) {
					$('#table-so-in-do').DataTable().clear();
					$('#table-so-in-do').DataTable().destroy();
				}

				if (response.SalesOrderNotInDO != 0) {

					// console.log(response.SalesOrderInDO);

					$.each(response.SalesOrderNotInDO, function(i, v) {
						$("#table-so-not-in-do > tbody").append(`
							<tr>
								<td class="text-center">${v.sales_order_kode}</td>
								<td class="text-center">${v.sales_order_tgl}</td>
								<td class="text-center">${v.sales_order_tgl_kirim}</td>
								<td class="text-center">${v.client_pt_nama}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.sales_order_status}</td>
								<td class="text-center">
									<span id="item-${i}-loading-customer" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
									<button class="btn ${v.status_customer == '1' ? 'btn-success' : 'btn-danger'} btn-sm btn-sync" id="item-${i}-status_customer" OnClick="SyncCustomer('${v.client_pt_id}',${i},'${v.status_customer}')" ${v.status_customer == '1' ? 'disabled' : ''}>${v.status_customer == '1' ? 'SYNCED' : 'NOT SYNCED'}</button>
								</td>
							</tr>
						`);
					});

					$('#table-so-not-in-do').DataTable({
						lengthMenu: [
							[50, 100, 200, -1],
							[50, 100, 200, 'All']
						],
					});
				}

				if (response.SalesOrderInDO != 0) {

					// console.log(response.SalesOrderInDO);

					$.each(response.SalesOrderInDO, function(i, v) {
						$("#table-so-in-do > tbody").append(`
							<tr>
								<td class="text-center">${v.delivery_order_draft_kode}</td>
								<td class="text-center">${v.sales_order_kode}</td>
								<td class="text-center">${v.sales_order_tgl_kirim}</td>
								<td class="text-center">${v.delivery_order_draft_tgl_surat_jalan}</td>
								<td class="text-center">${v.client_pt_nama}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.sales_order_status}</td>
							</tr>
						`);
					});

					$('#table-so-in-do').DataTable({
						lengthMenu: [
							[50, 100, 200, -1],
							[50, 100, 200, 'All']
						],
					});
				}

				$("#loadingview").hide();
				$("#btnrefreshsofas").prop("disabled", false);
				$("#btnsavesofas").prop("disabled", false);
				$("#btnsavedowms").prop("disabled", false);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$("#loadingview").hide();
				$("#btnrefreshsofas").prop("disabled", false);
				$("#btnsavesofas").prop("disabled", false);
				$("#btnsavedowms").prop("disabled", false);
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

							GetSalesOrderFAS();

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