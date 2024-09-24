<script type="text/javascript">
	var ChannelCode = '';
	var arr_list_customer = [];

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

	$('#select-sync-all-customer').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxCustomer"]:checkbox').each(function() {
				this.checked = true;
				var data_customer = JSON.parse(this.getAttribute('data-customer'));
				arr_list_customer.push({
					...data_customer
				});
				// console.log(this.getAttribute('data-customer'));
			});
		} else {
			$('[name="CheckboxCustomer"]:checkbox').each(function() {
				this.checked = false;
				arr_list_customer = [];
			});
		}
	});

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	function GetPrincipleByPerusahaan() {
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/SalesOrder/GetPrincipleByPerusahaan') ?>",
			data: {
				perusahaan: $("#filter_perusahaan").val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#principle").html('');
				$("#principle").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);

				if (response.length > 0) {
					$.each(response, function(i, v) {
						$("#principle").append(`<option value="${v.principle_kode}">${v.principle_kode}</option>`);
					});
				}
			}
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
			var principle = $("#principle").val();
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
					principle: principle,
					switched: switched
				},
				dataType: "JSON",
				success: function(response) {

					// console.log(response);
					$.each(response, function(i, v) {
						if (v.kode == 1) {

							// let alert_tes = GetLanguageByKode(v.msg);
							message_custom("Hasil", "", v.msg);

						} else if (v.kode == 2) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " +
								alert_tes;
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

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " +
								alert_tes;
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

							let alert_tes = GetLanguageByKode(
								'CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED');
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
			var perusahaan_eksternal = $("#filter_perusahaan").val();
			var principle = $("#principle").val();

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
					perusahaan_eksternal: perusahaan_eksternal,
					principle: principle
				},
				dataType: "JSON",
				beforeSend: function() {

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

						} else if (v.kode == 2) {
							let alert_tes = GetLanguageByKode(v.msg);

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " +
								alert_tes;
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

							var msg = "<span style='font-weight:bold'>" + v.so + "</span> " +
								alert_tes;
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

							let alert_tes = GetLanguageByKode(
								'CAPTION-ALERT-GENERATEDSOEKSTERNALFAILED');
							message_custom("Error", "error", alert_tes);

						}
					});

					$("#loadingview").hide();
					$("#btnrefreshsofas").prop("disabled", false);
					$("#btnsavesofas").prop("disabled", false);
					$("#btnsavedowms").prop("disabled", false);

					// console.log(response);

				},
				error: function(xhr, ajaxOptions, thrownError) {
					// let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
					message_custom("Error", "error", "Error 500, Internal Server Error");
				},
				complete: function(response) {
					Swal.close();
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

	$("#btnsyncalloutlet").click(function() {
		$.each(arr_list_customer, function(i, v) {
			SyncCustomer(v.szCustId, v.no_urut, v.status_customer);
		});
	});

	function GetSalesOrderFAS() {
		var tgl = $("#datesofas").val();
		var status = $("#status_sync").val();

		$("#select-sync-all-customer").prop("checked", false);

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
				perusahaan_eksternal: $("#filter_perusahaan").val(),
				principle: $("#principle").val()
			},
			dataType: "JSON",
			success: function(response) {

				$("#table-so-in-do > tbody").empty();
				$("#table-so-not-in-do > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table-so-in-do')) {
					$('#table-so-in-do').DataTable().clear();
					$('#table-so-in-do').DataTable().destroy();
				}

				if ($.fn.DataTable.isDataTable('#table-so-not-in-do')) {
					$('#table-so-not-in-do').DataTable().clear();
					$('#table-so-not-in-do').DataTable().destroy();
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
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.client_pt_nama}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.sales_order_status}</td>
							</tr>
						`);
					});

					$('#table-so-in-do').DataTable({
						lengthMenu: [
							[10, 50, 100],
							[10, 50, 100]
						],
					});
				}

				if (response.SalesOrderNotInDO != 0) {

					// console.log(response.SalesOrderInDO);

					$.each(response.SalesOrderNotInDO, function(i, v) {

						const data_customer = {
							'szCustId': v.client_pt_id,
							'no_urut': i,
							'status_customer': v.status_customer
						}

						$("#table-so-not-in-do > tbody").append(`
							<tr>
								<td class="text-center">${v.sales_order_kode}</td>
								<td class="text-center">${v.sales_order_tgl}</td>
								<td class="text-center">${v.sales_order_tgl_kirim}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.client_pt_nama}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.sales_order_status}</td>
								<td class="text-center">
									<span id="item-${i}-loading-customer" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
									<input type="checkbox" name="${v.status_customer == '1' ? 'CheckboxCustomerChecked' : 'CheckboxCustomer'}" id="item-${i}-sync-customer" value="${v.client_pt_id}" data-customer='${JSON.stringify({...data_customer})}' OnClick="PushArrayCustomer('${v.client_pt_id}',${i},'${v.status_customer}')" ${v.status_customer == '1' ? 'disabled checked' : ''}>
									<button class="btn ${v.status_customer == '1' ? 'btn-success' : 'btn-danger'} btn-sm btn-sync" id="item-${i}-status_customer" ${v.status_customer == '1' ? 'disabled checked' : ''}>${v.status_customer == '1' ? 'SYNCED' : 'NOT SYNCED'}</button>
								</td>
							</tr>
						`);
					});

					$('#table-so-not-in-do').DataTable({
						lengthMenu: [
							[10, 50, 100],
							[10, 50, 100]
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
		$("#select-sync-all-customer").prop("disabled", true);
		// $(".btn-sync").prop("disabled", true);

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/SalesOrder/Sync_customer') ?>",
			data: {
				customer_id: client_pt_id,
				perusahaan_eksternal: $("#filter_perusahaan").val(),
				switched: $("#filter_switched").val()
			},
			dataType: "JSON",
			beforeSend: function() {

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			success: function(response) {
				$("#item-" + idx + "-loading-customer").hide();
				$("#item-" + idx + "-status_customer").prop("disabled", false);
				$("#select-sync-all-customer").prop("disabled", false);
				// $(".btn-sync").prop("disabled", false);

				if (response != "") {

					$.each(response, function(i, v) {
						if (v.kode == 1) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERBERHASIL');
							message_custom("Success", "success", alert_tes);

							GetSalesOrderFAS();

						} else if (v.kode == 2) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SUDAHSYNCCUSTOMER');
							// message_custom("Error", "error", alert_tes);

							new PNotify
								({
									title: 'Error',
									text: msg,
									type: 'error',
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});

						} else if (v.kode == 3) {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-MAPPINGCUSTOMEREKSTERNALNOTFOUND');
							// message_custom("Error", "error", alert_tes);

							new PNotify
								({
									title: 'Error',
									text: msg,
									type: 'error',
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});

						} else {

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
							message_custom("Error", "error", alert_tes);

							// new PNotify
							// 	({
							// 		title: 'Error',
							// 		text: msg,
							// 		type: 'error',
							// 		styling: 'bootstrap3',
							// 		delay: 3000,
							// 		stack: stack_center
							// 	});

						}
					})

				} else {
					let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
					message_custom("Warning", "warning", alert_tes);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {

				$("#item-" + idx + "-loading-customer").hide();
				$("#item-" + idx + "-status_customer").prop("disabled", false);
				$(".btn-sync").prop("disabled", false);

				// let alert_tes = GetLanguageByKode('CAPTION-ALERT-SYNCCUSTOMERGAGAL');
				message_custom("Error", "error", "Error 500, Internal Server Error");
			}
		});
		// } else {

		// 	let alert_tes = GetLanguageByKode('CAPTION-ALERT-SUDAHSYNCCUSTOMER');
		// 	message_custom("Warning", "warning", alert_tes);

		// }

	}

	function PushArrayCustomer(szCustId, idx, status_customer) {

		// console.log(idx);
		var checked = $('[id="item-' + idx + '-sync-customer"]:checked').length;

		$("#select-sync-all-customer").prop("checked", false);

		if (checked > 0) {

			arr_list_customer.push({
				'szCustId': szCustId,
				'no_urut': idx,
				'status_customer': status_customer
			})
		} else {
			const findIndexData = arr_list_customer.findIndex((value) => value.no_urut == idx);
			if (findIndexData > -1) { // only splice array when item is found
				arr_list_customer.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
		}
	}
</script>