use filter;
<script type="text/javascript">
	var ChannelCode = '';
	var jml_fdjr = 0;
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
		}
	);

	$('#select-fdjr').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxFDJR_0"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxFDJR_0"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	function GetSuratTugasPengirimanMenu() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SettlementPengiriman/GetSuratTugasPengirimanMenu') ?>",
			//data: "Location="+ Location,
			success: function(response) {
				if (response) {
					ChSuratTugasPengirimanMenu(response);
				}
			}
		});
	}

	//var DTABLE;

	function ChSuratTugasPengirimanMenu(JSONChannel) {
		$("#tablefdjrmenu > tbody").html('');
		$("#filter_fdjr_driver").html('');
		$("#filter_fdjr_status").html('');

		var Channel = JSON.parse(JSONChannel);

		var StatusC = Channel.AuthorityMenu[0].status_c;
		var StatusU = Channel.AuthorityMenu[0].status_u;
		var StatusD = Channel.AuthorityMenu[0].status_d;

		if (Channel.Driver != 0) {
			$("#filter_fdjr_driver").append('<option value="">All</option>');

			for (i = 0; i < Channel.Driver.length; i++) {
				karyawan_id = Channel.Driver[i].karyawan_id;
				karyawan_nama = Channel.Driver[i].karyawan_nama;
				$("#filter_fdjr_driver").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
			}
		}

		if (Channel.StatusFDJR != 0) {
			$("#filter_fdjr_status").append('<option value="">All</option>');

			for (i = 0; i < Channel.StatusFDJR.length; i++) {
				delivery_order_batch_status = Channel.StatusFDJR[i].delivery_order_batch_status;
				$("#filter_fdjr_status").append('<option value="' + delivery_order_batch_status + '">' +
					delivery_order_batch_status + '</option>');
			}
		}
	}

	$("#btnviewfdjr").click(
		function() {
			var Tgl_FDJR = $("#filter_fdjr_date").val();
			var No_FDJR = $("#filter_fdjr_no").val();
			var karyawan_id = $("#filter_fdjr_driver").val();
			var no = 1;

			$("#loadingview").show();

			Swal.fire({
				title: 'Loading ...',
				html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				timerProgressBar: false,
				showConfirmButton: false,
				allowOutsideClick: false
			});

			$.ajax({
				// async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SettlementPengiriman/get_settlement_pengiriman_by_filter') ?>",
				data: {
					Tgl_FDJR: Tgl_FDJR,
					No_FDJR: No_FDJR,
					karyawan_id: karyawan_id
				},
				dataType: "JSON",
				success: function(response) {
					$("#loadingview").hide(); //Alert sukses memuat data
					setTimeout(function() {
						Swal.fire({
							title: 'Sukses!',
							text: 'Berhasil memuat data.',
							icon: 'success',
							timer: 3000,
							showCancelButton: false,
							showConfirmButton: true,
							allowOutsideClick: false
						}).then(
							function() {},
							// handling the promise rejection
							function(dismiss) {
								if (dismiss === 'timer') {
									//console.log('your message')
								}
							}
						)
					}, 1000);
					if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
						$('#tablefdjrmenu').DataTable().clear().draw();
						$('#tablefdjrmenu').DataTable().destroy();
					}
					if (response.length != 0) {

						jml_fdjr = response.length;

						$('#tablefdjrmenu').fadeOut("slow", function() {
							$(this).hide();
							if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
								$('#tablefdjrmenu').DataTable().clear().draw();
								$('#tablefdjrmenu').DataTable().destroy();
							}
						}).fadeIn("slow", function() {
							if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
								$('#tablefdjrmenu').DataTable().clear().draw();
								$('#tablefdjrmenu').DataTable().destroy();
							}

							$('#tablefdjrmenu > tbody').empty();

							var arr = [];
							$.each(response, function(i, v) {
								if (arr[v.delivery_order_batch_kode]) {
									arr[v.delivery_order_batch_kode] += 1;
								} else {
									arr[v.delivery_order_batch_kode] = 1;
								}
							})

							var cek_do_batch_kode = '';
							let cbdisabled = '';
							let cbnumb = '';
							$.each(response, function(i, v) {
								if (arr[v.delivery_order_batch_kode]) {
									if (cek_do_batch_kode == v.delivery_order_batch_kode) {
										$("#tablefdjrmenu > tbody").append(`
										<tr>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											
											
											<td class="text-center" style="vertical-align: middle;">
												<a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/${v.picking_list_id}" class="btn btn-link" target="_blank">${v.picking_list_kode}</a>
											</td>
											<td class="text-center" style="vertical-align: middle;">
												<a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=${v.picking_order_id}" class="btn btn-link" target="_blank">${v.picking_order_kode}</a>
											</td>
											<td class="text-center" style="vertical-align: middle;">
												<a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/${v.serah_terima_kirim_id}" class="btn btn-link" target="_blank">${v.serah_terima_kirim_kode}</a>
											</td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
											<td style="display:none"></td>
										</tr>
										`);
										// <tr>
										// 	<td class="text-center">
										// 		<a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/${v.picking_list_id}" class="btn btn-link" target="_blank">${v.picking_list_kode}</a>
										// 	</td>
										// 	<td class="text-center">
										// 		<a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=${v.picking_order_id}" class="btn btn-link" target="_blank">${v.picking_order_kode}</a>
										// 	</td>
										// 	<td class="text-center">
										// 		<a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/${v.serah_terima_kirim_id}" class="btn btn-link" target="_blank">${v.serah_terima_kirim_kode}</a>
										// 	</td>
										// </tr>
									} else {
										cek_do_batch_kode = v.delivery_order_batch_kode

										// if (v.settlement_status == 'Settlement Success' || (v.status_barang != v.status_tunai)) {
										// 	cbdisabled = 'disabled';
										// } else {
										// 	cbdisabled = '';
										// }
										// if (v.settlement_status == 'Settlement Success' || (v.status_barang != v.status_tunai)) {
										// 	cbnumb = 1
										// } else {
										// 	cbnumb = 0
										// }
										if (v.status_barang == 'Cocok' && v.status_tunai ==
											'Cocok') {
											if (v.settlement_status ==
												'Settlement Success' || (v.status_barang !=
													v.status_tunai)) {
												cbdisabled = 'disabled';
											} else {
												cbdisabled = '';
											}
										} else {
											cbdisabled = 'disabled';
										}
										if (v.status_barang == 'Cocok' && v.status_tunai ==
											'Cocok') {
											if (v.settlement_status ==
												'Settlement Success' || (v.status_barang !=
													v.status_tunai)) {
												cbnumb = 1
											} else {
												cbnumb = 0
											}
										} else {
											cbnumb = 1
										}


										$("#tablefdjrmenu > tbody").append(`
									<tr>
										<td class="text-center" style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}">
											<input type="checkbox" class="cbFDJR" name="CheckboxFDJR_${cbnumb}" id="check_fdjr_${i}" data-LastUpdate="${v.delivery_order_batch_update_tgl}" value="${v.delivery_order_batch_id}" ${cbdisabled}>
											<input type="hidden" class="cbFDJRKODE" id="fdjr_kode_${i}" value="${v.delivery_order_batch_kode}">
										</td>
										<td style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}"  class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
                        				<td style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}"  class="text-center">${v.karyawan_nama}</td>
                        				<td style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}"  class="text-center">${v.delivery_order_batch_status}</td>
                        				<td style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}"  class="text-center">${v.tipe_delivery_order_nama}</td>
                        				<td style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}"  class="text-center">
                        					<a href="<?php echo base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=${v.delivery_order_batch_id}" class="btn btn-link" target="_blank">${v.delivery_order_batch_kode}</a>
                        				</td>
                        				<td class="text-center" style="vertical-align: middle;" rowspan="1">
                        					<a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/${v.picking_list_id}" class="btn btn-link" target="_blank">${v.picking_list_kode}</a>
                        				</td>
                        				<td class="text-center" style="vertical-align: middle;" rowspan="1">
                        					<a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=${v.picking_order_id}" class="btn btn-link" target="_blank">${v.picking_order_kode}</a>
                        				</td>
                        				<td class="text-center" style="vertical-align: middle;" rowspan="1">
                        					<a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/${v.serah_terima_kirim_id}" class="btn btn-link" target="_blank">${v.serah_terima_kirim_kode}</a>
                        				</td>
                        				<td class="text-center ${v.status_barang == 'Cocok' ? 'text-success' : 'text-danger' }" style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}">${v.status_barang}</td>
                        				<td class="text-center ${v.status_tunai == 'Cocok' ? 'text-success' : 'text-danger' }" style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}">${v.status_tunai}</td>
                        				<td class="text-center" style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}">${v.settlement_status}</td>
                        				<td class="text-center" style="vertical-align: middle;" rowspan="${arr[v.delivery_order_batch_kode]}">
                        					<a href="<?php echo base_url(); ?>WMS/SettlementPengiriman/SettlementMenu/?delivery_order_batch_id=${v.delivery_order_batch_id}" class="btn btn-primary" target="_blank">Settlement</a>
                        				</td>
									</tr>
								`);
									}
								}
							});
							$('#tablefdjrmenu').DataTable({
								columnDefs: [{
									sortable: false,
									targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
								}],
								lengthMenu: [
									[100, 200, 500, -1],
									[100, 200, 500, 'All']
								],
							});
						});
					}
				}
			});
		});

	$("#btnsettlement").click(function() {
		var status_settlement = [];
		var status_penerimaan_barang = [];
		var status_penerimaan_tunai = [];
		var jumlah_barang = 0;
		var cek_barang = 0;
		var jumlah_tunai = 0;
		var cek_tunai = 0;
		var cek_error = 0;
		let breakErr = 0;
		let arrDetail = []


		$('#tablefdjrmenu').find('tr').each(function(i, v) {
			if ($(this).find(".cbFDJR").is(":checked")) {
				let cstatus_barang = $(this).find('td').eq(9).text()
				let cstatus_tunai = $(this).find('td').eq(10).text()
				let fdjr_id = $(this).find(".cbFDJR").eq(0).val()
				let fdjr_kode = $(this).find(".cbFDJRKODE").eq(0).val()
				let lastUpdateValue = $(this).find(".cbFDJR").eq(0).attr("data-lastupdate");
				if (cstatus_barang != cstatus_tunai) {
					alert(`Item ke ${i} tidak cocok`)
					return false
				} else {
					arrDetail.push({
						fdjr_id: fdjr_id,
						fdjr_kode: fdjr_kode,
						status_barang: cstatus_barang,
						status_tunaai: cstatus_tunai,
						lastUpdateValue: lastUpdateValue
					})
				}
			}
		})
		// console.log(arrDetail);
		if (arrDetail.length == 0) {
			message('Warning!', `Mohon Pilih Data`, 'warning')
			return false
		} else {
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
						showConfirmButton: false,
						allowOutsideClick: false
					});

					setTimeout(() => {
						$.each(arrDetail, function(i, v) {
							var fdjr_id = v.fdjr_id;
							var fdjr_kode = v.fdjr_kode;
							var status_barang = v.cstatus_barang;
							var status_tunaai = v.cstatus_tunai;
							var fdjr_lastUpdate = v.lastUpdateValue;
							requestAjax(
								"<?= base_url('WMS/SettlementPengiriman/CekLastUpdateFDJR'); ?>", {
									delivery_order_batch_id: fdjr_id,
									lastUpdated: fdjr_lastUpdate
								}, "POST", "JSON",
								function(response) {
									if (response == 1) {
										console.log(
											"Ok Data Aman Tidak Di Handle Orang Lain"
										);
									}
									if (response == 2) {
										breakErr = 1
										messageNotSameLastUpdated()
										return false;
									}
								}, "#btnsettlement")
							// console.log(breakErr);
							if (breakErr == 1) {
								Swal.close()
								return false;
							} else {
								$("#loadingview").show();
								$("#btnsettlement").prop("disabled", true);
								$("#btnviewfdjr").prop("disabled", true);

								$.ajax({
									// async: false,
									type: 'POST',
									url: "<?= base_url('WMS/SettlementPengiriman/InsertSettlement') ?>",
									data: {
										delivery_order_batch_id: fdjr_id,
										statussettlement: 'Cocok'
									},
									beforeSend: function() {
										Swal.fire({
											title: 'Loading ...',
											html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
											timerProgressBar: false,
											showConfirmButton: false,
											allowOutsideClick: false
										});
									},
									success: function(data) {
										if (data == 1) {
											$("#loadingview").hide();
											$("#btnsettlement").prop(
												"disabled",
												false);
											$("#btnviewfdjr").prop(
												"disabled",
												false);

											var msg = GetLanguageByKode(
												'CAPTION-ALERT-SETTLEMENTBERHASIL'
											);
											var msgtype = "success";

											new PNotify
												({
													title: 'success',
													text: "FDJR " +
														fdjr_kode +
														" " +
														msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
											location.reload();

										} else if (data == 2) {
											$("#loadingview").hide();
											$("#btnsettlement").prop(
												"disabled",
												false);
											$("#btnviewfdjr").prop(
												"disabled",
												false);

											var msg = GetLanguageByKode(
												'CAPTION-ALERT-SUDAHSETTLEMENT'
											);
											var msgtype = "error";

											new PNotify
												({
													title: 'error',
													text: "FDJR " +
														fdjr_kode +
														" " +
														msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										} else {
											$("#loadingview").hide();
											$("#btnsettlement").prop(
												"disabled",
												false);
											$("#btnviewfdjr").prop(
												"disabled",
												false);

											var msg = GetLanguageByKode(
												'CAPTION-ALERT-SETTLEMENTGAGAL'
											);
											var msgtype = "error";

											new PNotify
												({
													title: 'error',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										}
									}
								});
							}

						})
					}, 500);
					Swal.close()
				}
			});
			return false

		}

		return false
		/** func lama
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
				const dataTable = $('#tablefdjrmenu').DataTable();
				const rows_selected = dataTable.table().node();
				const allChecked = $('tbody input[type="checkbox"]:checked:not(:disabled)', rows_selected);
				if (allChecked.length == 0) {

					var msgtype = "error";
					new PNotify
						({
							title: 'error',
							text: "Mohon Pilih Data",
							type: msgtype,
							styling: 'bootstrap3',
							delay: 3000,
							stack: stack_center
						});
					return false;
				}
				for (var i = 0; i < jml_fdjr; i++) {
					var checked = $('[id="check_fdjr_' + i + '"]:checked').length;
					var fdjr_id = $("#check_fdjr_" + i).val();
					var fdjr_lastUpdate = $("#check_fdjr_" + i).attr("data-LastUpdate");
					var fdjr_kode = $("#fdjr_kode_" + i).val();

					if (checked > 0) {

						$("#loadingview").show();
						$("#btnsettlement").prop("disabled", true);
						$("#btnviewfdjr").prop("disabled", true);

						$.ajax({
							async: false,
							type: 'GET',
							url: "<?= base_url('WMS/SettlementPengiriman/get_settlement_status_by_fdjr') ?>",
							data: {
								fdjr_id: fdjr_id
							},
							dataType: "JSON",
							success: function(response) {
								$("#loadingview").hide();
								$("#btnsettlement").prop("disabled", false);
								$("#btnviewfdjr").prop("disabled", false);

								// jumlah_barang = 0;
								// cek_barang = 0;
								// jumlah_tunai = 0;
								// cek_tunai = 0;
								// cek_error = 0;

								if (response.PenerimaanBarang != 0) {
									$.each(response.PenerimaanBarang, function(i, v) {
										status_penerimaan_barang.push({
											"delivery_order_batch_id": v
												.delivery_order_batch_id,
											"status_penerimaan_barang": v
												.statussettlement
										});

										if (v.statussettlement == "Cocok") {
											cek_barang++;
										}

										jumlah_barang++;
									});
								}

								if (response.PenerimaanBarang != 0) {
									$.each(response.PenerimaanTunai, function(i, v) {
										status_penerimaan_tunai.push({
											"delivery_order_batch_id": v
												.delivery_order_batch_id,
											"nominalkumulatif": v
												.nominalkumulatif
										});

										var nilai_tunai = isNaN(parseInt(v
											.nominalkumulatif)) ? 0 : parseInt(v
											.nominalkumulatif);
										// nilai_tunai = nilai_tunai == 'Nan' ? 0 : nilai_tunai;
										console.log(nilai_tunai);

										if (i == response.PenerimaanBarang.length - 1) {
											if (nilai_tunai == 0) {
												cek_tunai++;
											}
										}
										jumlah_tunai++;
									});
								}

								if (cek_barang != jumlah_barang) {
									var msg = GetLanguageByKode(
										'CAPTION-ALERT-SETTLEMENTBARANGTIDAKCOCOK');
									var msgtype = "error";

									new PNotify
										({
											title: 'error',
											text: "FDJR " + fdjr_kode + " " + msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
									cek_error++;
								}

								if (cek_tunai == 0) {
									var msg = GetLanguageByKode(
										'CAPTION-ALERT-SETTLEMENTTUNAITIDAKCOCOK');
									var msgtype = "error";

									new PNotify
										({
											title: 'error',
											text: "FDJR " + fdjr_kode + " " + msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
									cek_error++;
								}

								if (cek_error == 0) {
									requestAjax(
										"<?= base_url('WMS/SettlementPengiriman/CekLastUpdateFDJR'); ?>", {
											delivery_order_batch_id: fdjr_id,
											lastUpdated: fdjr_lastUpdate
										}, "POST", "JSON",
										function(response) {
											if (response == 1) {
												console.log(
													"Ok Data Aman Tidak Di Handle Orang Lain"
												);
											}
											if (response == 2) {
												messageNotSameLastUpdated()
												return false;
											}
										}, "#btnsettlement")

									$("#loadingview").show();
									$("#btnsettlement").prop("disabled", true);
									$("#btnviewfdjr").prop("disabled", true);

									$.ajax({
										async: false,
										type: 'POST',
										url: "<?= base_url('WMS/SettlementPengiriman/InsertSettlement') ?>",
										data: {
											delivery_order_batch_id: fdjr_id,
											statussettlement: 'Cocok'
										},
										success: function(data) {
											if (data == 1) {
												$("#loadingview").hide();
												$("#btnsettlement").prop(
													"disabled",
													false);
												$("#btnviewfdjr").prop(
													"disabled",
													false);

												var msg = GetLanguageByKode(
													'CAPTION-ALERT-SETTLEMENTBERHASIL'
												);
												var msgtype = "success";

												new PNotify
													({
														title: 'success',
														text: "FDJR " +
															fdjr_kode +
															" " +
															msg,
														type: msgtype,
														styling: 'bootstrap3',
														delay: 3000,
														stack: stack_center
													});

											} else if (data == 2) {
												$("#loadingview").hide();
												$("#btnsettlement").prop(
													"disabled",
													false);
												$("#btnviewfdjr").prop(
													"disabled",
													false);

												var msg = GetLanguageByKode(
													'CAPTION-ALERT-SUDAHSETTLEMENT'
												);
												var msgtype = "error";

												new PNotify
													({
														title: 'error',
														text: "FDJR " +
															fdjr_kode +
															" " +
															msg,
														type: msgtype,
														styling: 'bootstrap3',
														delay: 3000,
														stack: stack_center
													});
											} else {
												$("#loadingview").hide();
												$("#btnsettlement").prop(
													"disabled",
													false);
												$("#btnviewfdjr").prop(
													"disabled",
													false);

												var msg = GetLanguageByKode(
													'CAPTION-ALERT-SETTLEMENTGAGAL'
												);
												var msgtype = "error";

												new PNotify
													({
														title: 'error',
														text: msg,
														type: msgtype,
														styling: 'bootstrap3',
														delay: 3000,
														stack: stack_center
													});
											}
										}
									});

								} else {
									var msg = GetLanguageByKode(
										'CAPTION-ALERT-SETTLEMENTGAGAL');
									var msgtype = "error";

									new PNotify
										({
											title: 'error',
											text: "FDJR " + fdjr_kode + " " + msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}

							},
							error: function(xhr, ajaxOptions, thrownError) {
								$("#loadingview").hide();
								$("#btnsettlement").prop("disabled", false);
								$("#btnviewfdjr").prop("disabled", false);
							}
						});
					}
				}
			}
		});
 */
	});

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtupdatechannelnama").val('');
			$("#cbupdatechanneljenis").prop('selectedIndex', 0);
			$("#chupdatechannelsppbr").prop('checked', false);
			$("#chupdatechanneltipeecomm").prop('checked', false);
			$("#chupdatechannelisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveupdatenewchannel").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtchannelnama").val('');
			$("#cbchanneljenis").prop('selectedIndex', 0);
			$("#chchannelsppbr").prop('checked', false);
			$("#chchanneltipeecomm").prop('checked', false);
			$("#chchannelisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveaddnewchannel").prop("disabled", false);
		<?php
		}
		?>

	}

	$(document).ready(function() {
		// if ($('#filter_fdjr_date').length > 0) {
		// 	$('#filter_fdjr_date').daterangepicker({
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

	});
</script>