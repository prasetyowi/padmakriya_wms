<script type="text/javascript">
	var arr_tr_abacus_detail = [];
	var tr_abacus_grand_total = 0;

	$(document).ready(function() {
		$('.select2').select2({
			width: '100%'
		});

		run_input_mask_money();

		<?php if ($act == "index") { ?>
			if ($('#filter_tanggal_abacus').length > 0) {
				$('#filter_tanggal_abacus').daterangepicker({
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
		<?php } ?>

		<?php if ($act == "edit" || $act == "detail") { ?>

			$('#table_abacus_detail').fadeOut("slow", function() {
				$(this).hide();

				$("#table_abacus_detail > tbody").html('');
				$("#table_abacus_detail > tbody").empty();

				// if ($.fn.DataTable.isDataTable('#table_abacus_detail')) {
				// 	$('#table_abacus_detail').DataTable().clear();
				// 	$('#table_abacus_detail').DataTable().destroy();
				// }

			}).fadeIn("slow", function() {
				$(this).show();

				<?php foreach ($Detail as $value) : ?>
					var delivery_order_jumlah_bayar = isNaN(parseInt("<?= $value['tr_abacus_detail_total'] ?>")) ? 0 : parseInt("<?= $value['tr_abacus_detail_total'] ?>");
					tr_abacus_grand_total += delivery_order_jumlah_bayar;

					arr_tr_abacus_detail.push({
						'principle_id': "<?= $value['principle_id'] ?>",
						'no_rekening': "<?= $value['no_rekening'] ?>",
						'tr_abacus_detail_total': delivery_order_jumlah_bayar
					});

					$("#table_abacus_detail > tbody").append(`
							<tr>
								<td class="text-center" style="text-align: center; vertical-align: middle;"><?= $value['principle_kode'] ?></td>
								<td class="text-center" style="text-align: center; vertical-align: middle;"><?= $value['nama_bank'] ?></td>
								<td class="text-center" style="text-align: center; vertical-align: middle;"><?= $value['no_rekening'] ?></td>
								<td class="text-center" style="text-align: right; vertical-align: middle;">${formatRupiahCurr(delivery_order_jumlah_bayar.toString().replaceAll(".",","))}</td>
							</tr>
						`);

				<?php endforeach; ?>

				// $('#table_abacus_detail').DataTable({
				// 	lengthMenu: [
				// 		[50, 100, 200, -1],
				// 		[50, 100, 200, 'All']
				// 	],
				// 	ordering: false,
				// 	searching: false,
				// 	"scrollX": true
				// });
			});
		<?php } ?>
	});

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		})
	}

	function Get_tr_abacus_by_filter() {
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/Get_tr_abacus_by_filter') ?>",
			data: {
				tanggal: $("#filter_tanggal_abacus").val(),
				perusahaan: $("#filter_perusahaan").val(),
				status: $("#filter_status").val()
			},
			dataType: "JSON",
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});

				$("#btn_search_abacus").prop("disabled", true);
			},
			success: function(response) {

				$('#table_list_abacus').fadeOut("slow", function() {
					$(this).hide();

					$("#table_list_abacus > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table_list_abacus')) {
						$('#table_list_abacus').DataTable().clear();
						$('#table_list_abacus').DataTable().destroy();
					}

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length > 0) {

						$.each(response, function(i, v) {

							if (v.tr_abacus_status == "Draft") {
								$("#table_list_abacus > tbody").append(`
									<tr>
										<td class="text-left" style="text-align: center; vertical-align: middle;">${i+1}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.client_wms_nama}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_tanggal}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_kode}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_reff_kode}</td>
										<td class="text-right" style="text-align: right; vertical-align: middle;">${formatRupiahCurr(v.tr_abacus_grand_total.toString().replaceAll(".",","))}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_status}</td>
										<td class="text-center" style="text-align: left; vertical-align: middle;">
											<a href="<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/edit/?id=${v.tr_abacus_id}" class="btn btn-primary btn-small"><i class="fa fa-pencil"></i></a>
										</td>
									</tr>
								`);
							} else {
								$("#table_list_abacus > tbody").append(`
									<tr>
										<td class="text-left" style="text-align: center; vertical-align: middle;">${i+1}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.client_wms_nama}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_tanggal}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_kode}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_reff_kode}</td>
										<td class="text-right" style="text-align: right; vertical-align: middle;">${formatRupiahCurr(v.tr_abacus_grand_total.toString().replaceAll(".",","))}</td>
										<td class="text-left" style="text-align: left; vertical-align: middle;">${v.tr_abacus_status}</td>
										<td class="text-center" style="text-align: left; vertical-align: middle;">
											<a href="<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/detail/?id=${v.tr_abacus_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>
										</td>
									</tr>
								`);
							}
						});
					}

					$('#table_list_abacus').DataTable({
						lengthMenu: [
							[50, 100, 200, -1],
							[50, 100, 200, 'All']
						],
						ordering: false,
						searching: false,
						"scrollX": true
					});
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
				$("#btn_search_abacus").prop("disabled", false);
			},
			complete: function() {
				Swal.close();
				$("#btn_search_abacus").prop("disabled", false);
			}
		});
	}

	function GetJumlahBayarDO() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/GetJumlahBayarDO') ?>",
			data: {
				tanggal: $("#TrAbacus-tr_abacus_tanggal").val(),
				perusahaan: $("#TrAbacus-client_wms_id").val()
			},
			dataType: "JSON",
			success: function(response) {
				arr_tr_abacus_detail = [];
				tr_abacus_grand_total = 0;

				$('#table_abacus_detail').fadeOut("slow", function() {
					$(this).hide();

					$("#table_abacus_detail > tbody").html('');
					$("#table_abacus_detail > tbody").empty();

					// if ($.fn.DataTable.isDataTable('#table_abacus_detail')) {
					// 	$('#table_abacus_detail').DataTable().clear();
					// 	$('#table_abacus_detail').DataTable().destroy();
					// }

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length > 0) {

						$.each(response, function(i, v) {
							var delivery_order_jumlah_bayar = isNaN(parseInt(v.delivery_order_jumlah_bayar)) ? 0 : parseInt(v.delivery_order_jumlah_bayar);
							tr_abacus_grand_total += delivery_order_jumlah_bayar;

							arr_tr_abacus_detail.push({
								'principle_id': v.principle_id,
								'no_rekening': v.no_rekening,
								'tr_abacus_detail_total': delivery_order_jumlah_bayar
							});

							$("#table_abacus_detail > tbody").append(`
								<tr>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle_kode}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.nama_bank}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.no_rekening}</td>
									<td class="text-center" style="text-align: right; vertical-align: middle;">${formatRupiahCurr(delivery_order_jumlah_bayar.toString().replaceAll(".",","))}</td>
								</tr>
							`);
						});

						$("#TrAbacus-tr_abacus_grand_total").val(tr_abacus_grand_total);
					} else {
						$("#TrAbacus-tr_abacus_grand_total").val(tr_abacus_grand_total);
					}

					run_input_mask_money();

					// $('#table_abacus_detail').DataTable({
					// 	lengthMenu: [
					// 		[50, 100, 200, -1],
					// 		[50, 100, 200, 'All']
					// 	],
					// 	ordering: false,
					// 	searching: false,
					// 	"scrollX": true
					// });
				});

			}
		});
	}

	function saveData() {

		if ($("#TrAbacus-client_wms_id").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if (arr_tr_abacus_detail.length == 0) {
			var alert = "Detail Abacus Tidak Boleh Kosong, Tekan Refresh";
			// var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if ($("#TrAbacus-tr_abacus_id").val() != "") {
			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/update_tr_abacus') ?>",
				data: {
					tr_abacus_id: $('#TrAbacus-tr_abacus_id').val(),
					client_wms_id: $('#TrAbacus-client_wms_id').val(),
					depo_id: "",
					tr_abacus_kode: $('#TrAbacus-tr_abacus_kode').val(),
					tr_abacus_reff_kode: $('#TrAbacus-tr_abacus_reff_kode').val(),
					tr_abacus_grand_total: parseInt(($("#TrAbacus-tr_abacus_grand_total").val().toString().replaceAll(".", "")).replaceAll(",", ".")),
					tr_abacus_tanggal: $('#TrAbacus-tr_abacus_tanggal').val(),
					tr_abacus_status: $('#TrAbacus-tr_abacus_status').val(),
					tr_abacus_tgl_create: "",
					tr_abacus_who_create: "",
					tr_abacus_keterangan: $('#TrAbacus-tr_abacus_keterangan').val(),
					tr_abacus_tgl_update: $('#TrAbacus-tr_abacus_tgl_update').val(),
					tr_abacus_who_update: $('#TrAbacus-tr_abacus_who_update').val(),
					detail: arr_tr_abacus_detail
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});

					$("#btn_simpan").prop("disabled", true);
				},
				dataType: "JSON",
				success: function(response) {
					if (response == 1) {
						var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
						message_custom("Success", "success", alert);

						setTimeout(() => {
							location.href = "<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/RegisterTandaTerimaMenu";
						}, 1000);

					} else if (response == 2) {
						messageNotSameLastUpdated();
						return false;
					} else {
						var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
						message_custom("Error", "error", alert);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
					$("#btn_simpan").prop("disabled", false);
				},
				complete: function() {
					// Swal.close();
					$("#btn_simpan").prop("disabled", false);
				}
			});
		} else {
			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/insert_tr_abacus') ?>",
				data: {
					tr_abacus_id: $('#TrAbacus-tr_abacus_id').val(),
					client_wms_id: $('#TrAbacus-client_wms_id').val(),
					depo_id: "",
					tr_abacus_kode: $('#TrAbacus-tr_abacus_kode').val(),
					tr_abacus_reff_kode: $('#TrAbacus-tr_abacus_reff_kode').val(),
					tr_abacus_grand_total: parseInt(($("#TrAbacus-tr_abacus_grand_total").val().toString().replaceAll(".", "")).replaceAll(",", ".")),
					tr_abacus_tanggal: $('#TrAbacus-tr_abacus_tanggal').val(),
					tr_abacus_status: $('#TrAbacus-tr_abacus_status').val(),
					tr_abacus_tgl_create: "",
					tr_abacus_who_create: "",
					tr_abacus_keterangan: $('#TrAbacus-tr_abacus_keterangan').val(),
					tr_abacus_tgl_update: $('#TrAbacus-tr_abacus_tgl_update').val(),
					tr_abacus_who_update: $('#TrAbacus-tr_abacus_who_update').val(),
					detail: arr_tr_abacus_detail
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});

					$("#btn_simpan").prop("disabled", true);
				},
				dataType: "JSON",
				success: function(response) {
					if (response == 1) {
						var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
						message_custom("Success", "success", alert);

						setTimeout(() => {
							location.href = "<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/RegisterTandaTerimaMenu";
						}, 1000);

					} else {
						var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
						message_custom("Error", "error", alert);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
					$("#btn_simpan").prop("disabled", false);
				},
				complete: function() {
					// Swal.close();
					$("#btn_simpan").prop("disabled", false);
				}
			})
		}
	}

	function confirmData() {

		if ($("#TrAbacus-client_wms_id").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if (arr_tr_abacus_detail.length == 0) {
			var alert = "Detail Abacus Tidak Boleh Kosong, Tekan Refresh";
			// var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin ingin melaksanakan dokumen?",
			text: "Pastikan data yang ingin dilaksanakan benar",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				$.ajax({
					type: "POST",
					url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/update_tr_abacus') ?>",
					data: {
						tr_abacus_id: $('#TrAbacus-tr_abacus_id').val(),
						client_wms_id: $('#TrAbacus-client_wms_id').val(),
						depo_id: "",
						tr_abacus_kode: $('#TrAbacus-tr_abacus_kode').val(),
						tr_abacus_reff_kode: $('#TrAbacus-tr_abacus_reff_kode').val(),
						tr_abacus_grand_total: parseInt(($("#TrAbacus-tr_abacus_grand_total").val().toString().replaceAll(".", "")).replaceAll(",", ".")),
						tr_abacus_tanggal: $('#TrAbacus-tr_abacus_tanggal').val(),
						tr_abacus_status: "Laksanakan",
						tr_abacus_tgl_create: "",
						tr_abacus_who_create: "",
						tr_abacus_keterangan: $('#TrAbacus-tr_abacus_keterangan').val(),
						tr_abacus_tgl_update: $('#TrAbacus-tr_abacus_tgl_update').val(),
						tr_abacus_who_update: $('#TrAbacus-tr_abacus_who_update').val(),
						detail: arr_tr_abacus_detail
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn_simpan").prop("disabled", true);
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
							message_custom("Success", "success", alert);

							setTimeout(() => {
								location.href = "<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/RegisterTandaTerimaMenu";
							}, 1000);

						} else if (response == 2) {
							messageNotSameLastUpdated();
							return false;
						} else {
							var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
							message_custom("Error", "error", alert);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");
						$("#btn_simpan").prop("disabled", false);
					},
					complete: function() {
						// Swal.close();
						$("#btn_simpan").prop("disabled", false);
					}
				});
			}
		});
	}

	function bukaData() {

		if ($("#TrAbacus-client_wms_id").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if (arr_tr_abacus_detail.length == 0) {
			var alert = "Detail Abacus Tidak Boleh Kosong, Tekan Refresh";
			// var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin ingin membuka ulang dokumen?",
			text: "Pastikan data yang ingin dibuka benar",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				$.ajax({
					type: "POST",
					url: "<?= base_url('WMS/Abacus/RegisterTandaTerima/update_tr_abacus') ?>",
					data: {
						tr_abacus_id: $('#TrAbacus-tr_abacus_id').val(),
						client_wms_id: $('#TrAbacus-client_wms_id').val(),
						depo_id: "",
						tr_abacus_kode: $('#TrAbacus-tr_abacus_kode').val(),
						tr_abacus_reff_kode: $('#TrAbacus-tr_abacus_reff_kode').val(),
						tr_abacus_grand_total: parseInt(($("#TrAbacus-tr_abacus_grand_total").val().toString().replaceAll(".", "")).replaceAll(",", ".")),
						tr_abacus_tanggal: $('#TrAbacus-tr_abacus_tanggal').val(),
						tr_abacus_status: "Draft",
						tr_abacus_tgl_create: "",
						tr_abacus_who_create: "",
						tr_abacus_keterangan: $('#TrAbacus-tr_abacus_keterangan').val(),
						tr_abacus_tgl_update: $('#TrAbacus-tr_abacus_tgl_update').val(),
						tr_abacus_who_update: $('#TrAbacus-tr_abacus_who_update').val(),
						detail: arr_tr_abacus_detail
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn_simpan").prop("disabled", true);
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
							message_custom("Success", "success", alert);

							setTimeout(() => {
								location.href = "<?= base_url() ?>WMS/Abacus/RegisterTandaTerima/edit/?id=" + $('#TrAbacus-tr_abacus_id').val();
							}, 1000);

						} else if (response == 2) {
							messageNotSameLastUpdated();
							return false;
						} else {
							var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
							message_custom("Error", "error", alert);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");
						$("#btn_simpan").prop("disabled", false);
					},
					complete: function() {
						// Swal.close();
						$("#btn_simpan").prop("disabled", false);
					}
				});
			}
		});
	}
</script>