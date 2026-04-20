<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	let arr_checkbox_sku = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	var arr_do_draft = [];

	loadingBeforeReadyPage()

	$(document).ready(
		function() {
			$('.select2').select2();
			initDataClosingGudang();
		}
	);

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

	// $("#ClosingGudang-closing_gudang_tgl").on("change", function() {
	// 	initDataClosingGudang();
	// });

	$('#btn_filter').on('click', function() {
		initDataClosingGudang();
	})

	function initDataClosingGudang() {

		var tgl = $("#ClosingGudang-closing_gudang_tgl").val();

		// alert(tgl);

		$("#btn_proses_closing").prop("disabled", true);
		$("#loadingviewclosing").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/ClosingGudang/GetClosingGudangDokumen') ?>",
			data: {
				tgl: tgl
			},
			dataType: "JSON",
			success: function(response) {

				$("#btn_proses_closing").prop("disabled", false);
				$("#loadingviewclosing").hide();

				$("#table_closing_gudang > tbody").empty();

				if (response.status_tbg != 0) {

					$("#status_not_closing").hide();
					$("#status_closing").show();

					$("#status_closing").html('');

					$.each(response.status_tbg, function(i, v) {
						$("#status_closing").append(`Closing Success on ${v.tgl_create} by ${v.tr_tutup_gudang_who_create}`);
						$("#btn_proses_closing").attr("disabled", true);
					});
				} else {
					$("#status_not_closing").show();
					$("#status_closing").hide();
				}

				if (response.dokumen != 0) {
					$.each(response.dokumen, function(i, v) {

						if (v.status_closing == 1) {
							$("#table_closing_gudang > tbody").append(`
								<tr id="row-${i}">
									<td class="text-center">
										${i+1}
										<input type="hidden" id="item-${i}-status_closing" value="${v.status_closing}">
									</td>
									<td class="text-center">${GetLanguageByKode(v.kode)}</td>
									<td class="text-center">${v.draft}</td>
									<td class="text-center">${v.completed}</td>
									<td class="text-center">${v.canceled}</td>
									<td class="text-center">${v.total}</td>
									<td class="text-center">
										<button class="btn btn-sm btn-success" OnClick="GetDetailClosingGudangDokumen('${v.kode}','${v.status_closing}')"><span name="CAPTION-COCOK">Cocok</span></button>
									</td>
								</tr>
							`);
						} else {
							$("#table_closing_gudang > tbody").append(`
								<tr id="row-${i}">
									<td class="text-center">
										${i+1}
										<input type="hidden" id="item-${i}-status_closing" value="${v.status_closing}">
									</td>
									<td class="text-center">${GetLanguageByKode(v.kode)}</td>
									<td class="text-center">${v.draft}</td>
									<td class="text-center">${v.completed}</td>
									<td class="text-center">${v.canceled}</td>
									<td class="text-center">${v.total}</td>
									<td class="text-center">
										<button class="btn btn-sm btn-danger" OnClick="GetDetailClosingGudangDokumen('${v.kode}','${v.status_closing}')"><span name="CAPTION-TIDAKCOCOK">Tidak Cocok</span></button>
									</td>
								</tr>
							`);
						}
					});
				} else {
					$("#table_closing_gudang > tbody").append(`
						<tr>
							<td colspan="7" class="text-danger text-center">
								No Result Found
							</td>
						</tr>
					`);
				}
			}
		});
	}

	function GetDetailClosingGudangDokumen(kode, status_closing) {

		var tgl = $("#ClosingGudang-closing_gudang_tgl").val();
		$("#kode-dokumen").html('');

		$("#modal-detail-dokumen").modal('show');
		$("#loadingviewclosingdetail").show();

		$("#kode-dokumen").append("Detail " + GetLanguageByKode(kode));

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/ClosingGudang/GetDetailClosingGudangDokumen') ?>",
			data: {
				tgl: tgl,
				kode: kode,
				status_closing: status_closing
			},
			dataType: "JSON",
			success: function(response) {
				$("#loadingviewclosingdetail").hide();

				$("#table-dokumen > tbody").empty();

				if (response != 0) {
					$.each(response, function(i, v) {
						$("#table-dokumen > tbody").append(`
							<tr id="row-${i}">
								<td class="text-center">${i+1}</td>
								<td class="text-center">${v.dokumen_kode}</td>
								<td class="text-center">${v.dokumen_status}</td>
							</tr>
						`);
					});
				} else {
					$("#table-dokumen > tbody").append(`
						<tr>
							<td colspan="3" class="text-danger text-center">
								No Result Found
							</td>
						</tr>
					`);
				}
			}
		});
	}

	$("#btn_proses_closing").on("click", function() {
		var cek_status = 0;
		var tgl = $("#ClosingGudang-closing_gudang_tgl").val();

		// console.log($("#table_closing_gudang > tbody tr"));

		$("#table_closing_gudang > tbody tr").each(function(idx) {
			if ($("#item-" + idx + "-status_closing").val() == 0) {
				cek_status++;
			}
		});

		if ($("#table_closing_gudang > tbody tr").length > 0) {

			if (cek_status == 0) {

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
						//ajax save data
						$.ajax({
							type: 'POST',
							url: "<?= base_url('WMS/ClosingGudang/Insert_tr_tutup_gudang') ?>",
							data: {
								tgl: tgl
							},
							dataType: "JSON",
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false
								});

								$("#btn_proses_closing").prop("disabled", true);
							},
							success: function(response) {

								if (response == 1) {

									let alert_tes = GetLanguageByKode('CAPTION-ALERT-PROSESTBGBERHASIL');
									message("Success", alert_tes, "success");
									setTimeout(() => {
										location.href = "<?= base_url('WMS/ClosingGudang/ClosingGudangMenu') ?>"
									}, 2000);

								} else if (response == 2) {
									let alert_tes = GetLanguageByKode('CAPTION-ALERT-TGLTBGTIDAKVALID');
									message("Error", alert_tes, "error");
								} else if (response == 3) {
									let alert_tes = GetLanguageByKode('CAPTION-ALERT-TGLINISUDAHTBG');
									message("Error", alert_tes, "error");
								} else {
									let alert_tes = GetLanguageByKode('CAPTION-ALERT-PROSESTBGGAGAL');
									message("Error", alert_tes, "error");
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");
								$("#btn_proses_closing").prop("disabled", false);
							},
							complete: function() {
								// Swal.close();
								$("#btn_proses_closing").prop("disabled", false);
							}
						});
					}
				});

			} else {

				let alert_tes = GetLanguageByKode('CAPTION-ALERT-ADADOKUMENYANGTIDAKCOCOK');
				message("Error", alert_tes, "error");
			}

		} else {
			let alert_tes = GetLanguageByKode('CAPTION-ALERT-ADADOKUMENYANGTIDAKCOCOK');
			message("Error", alert_tes, "error");
		}
	});

	function modalDetail() {
		$.ajax({
			type: "GET",
			url: "<?= base_url('WMS/ClosingGudang/getDetailTutupGudang') ?>",
			data: {},
			dataType: "JSON",
			success: function(response) {
				$('#list-detail-tutup-gudang tbody').empty();

				if ($.fn.DataTable.isDataTable('#list-detail-tutup-gudang')) {
					$('#list-detail-tutup-gudang').DataTable().clear();
					$('#list-detail-tutup-gudang').DataTable().destroy();
				}

				$.each(response, function(i, v) {
					$("#list-detail-tutup-gudang tbody").append(`
                        <tr>
                            <td class="text-center">${v.tr_tutup_gudang_kode}</td>
                            <td class="text-center">${v.tr_tutup_gudang_periode_bulan}</td>
                            <td class="text-center">${v.tr_tutup_gudang_periode_tahun}</td>
                            <td class="text-center">${v.tr_tutup_gudang_tgl_create2}</td>
                            <td class="text-center">${v.tr_tutup_gudang_tgl_tbg}</td>
                            <td class="text-center">${v.tr_tutup_gudang_tgl_next_tbg}</td>
                            <td class="text-center">${v.tr_tutup_gudang_status}</td>
                        </tr>
                    `)
				})

				$("#list-detail-tutup-gudang").DataTable();
			}
		});

		$("#previewdetailtutupgudang").modal('show');
	}
</script>