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

	$("#btnsavecanvasfas").click(
		function() {
			var tgl = $("#datesofas").val();
			var perusahaan = $("#filter_perusahaan").val();
			var switched = $("#filter_switched").val();

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SalesOrder/SaveCanvasFAS') ?>",
				data: {
					tgl: tgl,
					perusahaan: perusahaan,
					switched: switched
				},
				dataType: "JSON",
				beforeSend: function() {

					$("#loadingview").show();
					$("#btnrefreshcanvas").prop("disabled", true);
					$("#btnsavecanvasfas").prop("disabled", true);
					$("#btnsavecanvasdowms").prop("disabled", true);

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
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);

					// console.log(response);

				},
				error: function(xhr, ajaxOptions, thrownError) {
					Swal.close()

					$("#loadingview").hide();
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);
				},
				complete: function() {
					Swal.close();

					$("#loadingview").hide();
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);

					GetSalesOrderFAS();
				}
			});
		}
	);

	$("#btnsavecanvasdowms").click(
		function() {
			var tgl = $("#datesofas").val();
			var perusahaan_eksternal = $("#filter_perusahaan").val();
			var cek_success = 0;

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SalesOrder/SaveCanvasDeliveryOrder') ?>",
				data: {
					tgl: tgl,
					perusahaan_eksternal: perusahaan_eksternal
				},
				dataType: "JSON",
				beforeSend: function() {

					$("#loadingview").show();
					$("#btnrefreshcanvas").prop("disabled", true);
					$("#btnsavecanvasfas").prop("disabled", true);
					$("#btnsavecanvasdowms").prop("disabled", true);

					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				success: function(response) {
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/SalesOrder/GetDeliveryOrderDraftCanvasByFilter'); ?>",
						data: {
							tgl: tgl,
							perusahaan_eksternal: perusahaan_eksternal
						},
						dataType: "JSON",
						success: function(response) {
							if (response.DOHeader != 0) {

								$.each(response.DOHeader, function(i, v) {
									if (v.client_wms_id !== null) {
										$.ajax({
											async: false,
											url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftDetailByListId'); ?>",
											type: "POST",
											data: {
												id: v.delivery_order_draft_id
											},
											dataType: "JSON",
											success: function(response) {
												arr_detail = [];

												if (response.DODetail != 0) {
													$.each(response.DODetail, function(i2, v2) {
														arr_detail.push({
															'delivery_order_detail_draft_id': v2.delivery_order_detail_draft_id,
															'delivery_order_draft_id': v2.delivery_order_draft_id,
															'sku_id': v2.sku_id,
															'gudang_id': v2.gudang_id,
															'gudang_detail_id': v2.gudang_detail_id,
															'sku_kode': v2.sku_kode,
															'sku_nama_produk': v2.sku_nama_produk,
															'sku_harga_satuan': v2.sku_harga_satuan,
															'sku_disc_percent': v2.sku_disc_percent,
															'sku_disc_rp': v2.sku_disc_rp,
															'sku_harga_nett': v2.sku_harga_nett,
															'sku_request_expdate': v2.sku_request_expdate,
															'sku_filter_expdate': v2.sku_filter_expdate,
															'sku_filter_expdatebulan': v2.sku_filter_expdatebulan,
															'sku_filter_expdatetahun': v2.sku_filter_expdatetahun,
															'sku_weight': v2.sku_weight,
															'sku_weight_unit': v2.sku_weight_unit,
															'sku_length': v2.sku_length,
															'sku_length_unit': v2.sku_length_unit,
															'sku_width': v2.sku_width,
															'sku_width_unit': v2.sku_width_unit,
															'sku_height': v2.sku_height,
															'sku_height_unit': v2.sku_height_unit,
															'sku_volume': v2.sku_volume,
															'sku_volume_unit': v2.sku_volume_unit,
															'sku_qty': v2.sku_qty,
															'sku_keterangan': v2.sku_keterangan,
															'sku_tipe_stock': v2.tipe_stock
														});
													});
												}
											},
											error: function(xhr, status, error) {
												message("Error", "Error 500 Internal Server Connection Failure", "error");
											}
										});

										if (arr_detail.length > 0) {

											if (v.delivery_order_draft_status == "Draft") {

												$.ajax({
													async: false,
													url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>",
													type: "POST",
													data: {
														delivery_order_draft_id: v.delivery_order_draft_id,
														sales_order_id: v.sales_order_id,
														delivery_order_draft_kode: v.delivery_order_draft_kode,
														delivery_order_draft_yourref: v.delivery_order_draft_yourref,
														client_wms_id: v.client_wms_id,
														delivery_order_draft_tgl_buat_do: v.delivery_order_draft_tgl_buat_do,
														delivery_order_draft_tgl_expired_do: v.delivery_order_draft_tgl_expired_do,
														delivery_order_draft_tgl_surat_jalan: v.delivery_order_draft_tgl_surat_jalan,
														delivery_order_draft_tgl_rencana_kirim: v.delivery_order_draft_tgl_rencana_kirim,
														delivery_order_draft_tgl_aktual_kirim: v.delivery_order_draft_tgl_aktual_kirim,
														delivery_order_draft_keterangan: v.delivery_order_draft_keterangan,
														delivery_order_draft_status: "Approved",
														delivery_order_draft_is_prioritas: v.delivery_order_draft_is_prioritas,
														delivery_order_draft_is_need_packing: v.delivery_order_draft_is_need_packing,
														delivery_order_draft_tipe_layanan: v.delivery_order_draft_tipe_layanan,
														delivery_order_draft_tipe_pembayaran: v.delivery_order_draft_tipe_pembayaran,
														delivery_order_draft_sesi_pengiriman: v.delivery_order_draft_sesi_pengiriman,
														delivery_order_draft_request_tgl_kirim: v.delivery_order_draft_request_tgl_kirim,
														delivery_order_draft_request_jam_kirim: v.delivery_order_draft_request_jam_kirim,
														tipe_pengiriman_id: v.tipe_pengiriman_id,
														nama_tipe: v.nama_tipe,
														confirm_rate: v.confirm_rate,
														delivery_order_draft_reff_id: v.delivery_order_draft_reff_id,
														delivery_order_draft_reff_no: v.delivery_order_draft_reff_no,
														delivery_order_draft_total: v.delivery_order_draft_total,
														unit_mandiri_id: v.unit_mandiri_id,
														depo_id: v.depo_id,
														client_pt_id: v.client_pt_id,
														delivery_order_draft_kirim_nama: v.delivery_order_draft_kirim_nama,
														delivery_order_draft_kirim_alamat: v.delivery_order_draft_kirim_alamat,
														delivery_order_draft_kirim_telp: v.delivery_order_draft_kirim_telp,
														delivery_order_draft_kirim_provinsi: v.delivery_order_draft_kirim_provinsi,
														delivery_order_draft_kirim_kota: v.delivery_order_draft_kirim_kota,
														delivery_order_draft_kirim_kecamatan: v.delivery_order_draft_kirim_kecamatan,
														delivery_order_draft_kirim_kelurahan: v.delivery_order_draft_kirim_kelurahan,
														delivery_order_draft_kirim_kodepos: v.delivery_order_draft_kirim_kodepos,
														delivery_order_draft_kirim_area: v.delivery_order_draft_kirim_area,
														delivery_order_draft_kirim_invoice_pdf: v.delivery_order_draft_kirim_invoice_pdf,
														delivery_order_draft_kirim_invoice_dir: v.delivery_order_draft_kirim_invoice_dir,
														pabrik_id: v.pabrik_id,
														delivery_order_draft_ambil_nama: v.delivery_order_draft_ambil_nama,
														delivery_order_draft_ambil_alamat: v.delivery_order_draft_ambil_alamat,
														delivery_order_draft_ambil_telp: v.delivery_order_draft_ambil_telp,
														delivery_order_draft_ambil_provinsi: v.delivery_order_draft_ambil_provinsi,
														delivery_order_draft_ambil_kota: v.delivery_order_draft_ambil_kota,
														delivery_order_draft_ambil_kecamatan: v.delivery_order_draft_ambil_kecamatan,
														delivery_order_draft_ambil_kelurahan: v.delivery_order_draft_ambil_kelurahan,
														delivery_order_draft_ambil_kodepos: v.delivery_order_draft_ambil_kodepos,
														delivery_order_draft_ambil_area: v.delivery_order_draft_ambil_area,
														delivery_order_draft_update_who: v.delivery_order_draft_update_who,
														delivery_order_draft_update_tgl: v.delivery_order_draft_update_tgl,
														delivery_order_draft_approve_who: v.delivery_order_draft_approve_who,
														delivery_order_draft_approve_tgl: v.delivery_order_draft_approve_tgl,
														delivery_order_draft_reject_who: v.delivery_order_draft_reject_who,
														delivery_order_draft_reject_tgl: v.delivery_order_draft_reject_tgl,
														delivery_order_draft_reject_reason: v.delivery_order_draft_reject_reason,
														tipe_delivery_order_id: v.tipe_delivery_order_id,
														is_from_so: v.is_from_so,
														delivery_order_draft_nominal_tunai: v.delivery_order_draft_nominal_tunai,
														delivery_order_draft_attachment: v.delivery_order_draft_attachment,
														is_promo: v.is_promo,
														is_canvas: v.is_canvas,
														detail: arr_detail
													},
													dataType: "JSON",
													success: function(data) {
														if (data.type === 200) {
															cek_success++;
														}

														if (data.type === 201) {
															let alert_tes = GetLanguageByKode("CAPTION-ALERT-QTYDOTIDAKCUKUP");

															var msg = "<span style='font-weight:bold'>DO " + v.delivery_order_draft_kode + "</span> " + alert_tes;
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
															let arrayOfErrorsToDisplay = [];
															let indexError = [];
															$.each(data.data, (index, item) => {
																let response = item.data;
																// arrayOfErrorsToDisplay = []
																// indexError = []

																indexError.push(index + 1);

																arrayOfErrorsToDisplay.push({
																	title: 'Data Gagal diapprove!',
																	html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
																});
																// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
															});

															Swal.mixin({
																	icon: 'error',
																	confirmButtonText: 'Next &rarr;',
																	showCancelButton: true,
																	progressSteps: indexError
																})
																.queue(arrayOfErrorsToDisplay)
														}

														if (data.type === 202) {
															message_topright("error", "Data gagal Diapprove");
														}

														if (data.type === 203) {
															let alert_tes = GetLanguageByKode("CAPTION-ALERT-QTYDOTIDAKADASTOCK");

															var msg = "<span style='font-weight:bold'>DO " + v.delivery_order_draft_kode + "</span> " + alert_tes;
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

															let arrayOfErrorsToDisplayEmptySku = [];
															let indexErrorEmptySku = [];
															arrayOfErrorsToDisplayEmptySku = []
															indexErrorEmptySku = []
															$.each(data.data, (index, item) => {
																let response = item.data;


																indexErrorEmptySku.push(index + 1);

																arrayOfErrorsToDisplayEmptySku
																	.push({
																		title: 'Data Gagal diapprove!',
																		html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																	});

																// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
															});

															Swal.mixin({
																	icon: 'error',
																	confirmButtonText: 'Next &rarr;',
																	showCancelButton: true,
																	progressSteps: indexErrorEmptySku
																})
																.queue(arrayOfErrorsToDisplayEmptySku)
														}

														if (data.type === 204) {
															let alert_tes = GetLanguageByKode("CAPTION-ALERT-QTYDOKURANG");

															var msg = "<span style='font-weight:bold'>DO " + v.delivery_order_draft_kode + "</span> " + alert_tes;
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

															let arrayOfErrorsToDisplaySkuStokKurang = [];
															let indexErrorSkuStokKurang = [];
															$.each(data.data, (index, item) => {
																let response = item.data;

																// arrayOfErrorsToDisplaySkuStokKurang
																// = []
																// indexErrorSkuStokKurang = []

																indexErrorSkuStokKurang.push(index +
																	1);

																arrayOfErrorsToDisplaySkuStokKurang
																	.push({
																		title: 'Data Gagal diapprove!',
																		html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
																	});
																// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
															});

															Swal.mixin({
																	icon: 'error',
																	confirmButtonText: 'Next &rarr;',
																	showCancelButton: true,
																	progressSteps: indexErrorSkuStokKurang
																})
																.queue(arrayOfErrorsToDisplaySkuStokKurang)
														}
													},
													error: function(xhr, status, error) {
														message("Error", "Error 500 Internal Server Connection Failure", "error");
													}
												});

											}
										} else {
											console.log("DO Detail Not Found");
										}
									} else {
										let alert_tes = GetLanguageByKode("CAPTION-ALERT-ADADOYGTIDAKMEMILIKIPERUSAHAAN");

										var msg = "<span style='font-weight:bold'>DO " + v.delivery_order_draft_kode + "</span> " + alert_tes;
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
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							message("Error", "Error 500 Internal Server Connection Failure", "error");

							$("#loadingview").hide();
							$("#btnrefreshcanvas").prop("disabled", false);
							$("#btnsavecanvasfas").prop("disabled", false);
							$("#btnsavecanvasdowms").prop("disabled", false);
						}
					});

					// console.log(response);
					$.each(response, function(i, v) {
						if (v.kode == 1) {

							console.log("SaveCanvasDeliveryOrder success");

							// let alert_tes = GetLanguageByKode(v.msg);
							// message_custom("Success", "success", alert_tes);

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
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);

					// console.log(response);

				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");

					$("#loadingview").hide();
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);
				},
				complete: function() {

					if (cek_success > 0) {
						let alert_tes = GetLanguageByKode("CAPTION-ALERT-GENERATEDDOSUCCESS");
						message_custom("Success", "success", alert_tes);

					} else {
						let alert_tes = GetLanguageByKode("CAPTION-ALERT-GENERATEDDOGAGAL");
						message_custom("Success", "success", alert_tes);
					}

					$("#loadingview").hide();
					$("#btnrefreshcanvas").prop("disabled", false);
					$("#btnsavecanvasfas").prop("disabled", false);
					$("#btnsavecanvasdowms").prop("disabled", false);

					GetSalesOrderFAS();
				}
			});

		});

	$("#btnrefreshcanvas").click(
		function() {
			GetSalesOrderFAS();
		}
	);

	function GetSalesOrderFAS() {
		var tgl = $("#datesofas").val();
		var status = $("#status_sync").val();

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SalesOrder/GetCanvasFAS') ?>",
			data: {
				tgl: tgl,
				status: status,
				perusahaan_eksternal: $("#filter_perusahaan").val()
			},
			dataType: "JSON",
			beforeSend: function() {

				$("#loadingview").show();
				$("#btnrefreshcanvas").prop("disabled", true);
				$("#btnsavecanvasfas").prop("disabled", true);
				$("#btnsavecanvasdowms").prop("disabled", true);

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			success: function(response) {

				$('#table-canvas-in-do').fadeOut("slow", function() {
					$(this).hide();

					$("#table-canvas-in-do > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table-canvas-in-do')) {
						$('#table-canvas-in-do').DataTable().clear();
						$('#table-canvas-in-do').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();
					if (response.CanvasInDO != 0) {

						// console.log(response.CanvasInDO);
						$.each(response.CanvasInDO, function(i, v) {
							$("#table-canvas-in-do > tbody").append(`
							<tr>
								<td class="text-center">${v.delivery_order_draft_kode}</td>
								<td class="text-center">${v.canvas_kode}</td>
								<td class="text-center">${v.canvas_requestdate}</td>
								<td class="text-center">${v.delivery_order_draft_tgl_surat_jalan}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.canvas_status}</td>
							</tr>
						`).fadeIn("slow");
						});

						$('#table-canvas-in-do').DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All']
							],
						});
					}
				});

				$('#table-canvas-not-in-do').fadeOut("slow", function() {
					$(this).hide();

					$("#table-canvas-not-in-do > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table-canvas-not-in-do')) {
						$('#table-canvas-not-in-do').DataTable().clear();
						$('#table-canvas-not-in-do').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.CanvasNotInDO != 0) {

						// console.log(response.CanvasInDO);

						$.each(response.CanvasNotInDO, function(i, v) {
							$("#table-canvas-not-in-do > tbody").append(`
							<tr>
								<td class="text-center">${v.delivery_order_draft_kode}</td>
								<td class="text-center">${v.canvas_kode}</td>
								<td class="text-center">${v.canvas_requestdate}</td>
								<td class="text-center">${v.delivery_order_draft_tgl_surat_jalan}</td>
								<td class="text-center">${v.tipe_sales_order_nama}</td>
								<td class="text-center">${v.canvas_status}</td>
							</tr>
						`).fadeIn("slow");
						});

						$('#table-canvas-not-in-do').DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All']
							],
						});
					}
				});

				$("#loadingview").hide();
				$("#btnrefreshcanvas").prop("disabled", false);
				$("#btnsavecanvasfas").prop("disabled", false);
				$("#btnsavecanvasdowms").prop("disabled", false);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				Swal.close()

				$("#loadingview").hide();
				$("#btnrefreshcanvas").prop("disabled", false);
				$("#btnsavecanvasfas").prop("disabled", false);
				$("#btnsavecanvasdowms").prop("disabled", false);
			},
			complete: function() {
				Swal.close();

				$("#loadingview").hide();
				$("#btnrefreshcanvas").prop("disabled", false);
				$("#btnsavecanvasfas").prop("disabled", false);
				$("#btnsavecanvasdowms").prop("disabled", false);
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