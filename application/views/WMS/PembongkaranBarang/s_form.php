<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_sku_exp = [];
	let arr_konversi_detail = [];
	let arr_konversi_detail2 = [];
	let arr_konversi_detail3 = [];
	let arr_pallet_id = [];

	const html5QrCode = new Html5Qrcode("preview");
	const html5QrCode2 = new Html5Qrcode("preview_by_one");
	let timerInterval
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2({
				width: '100%'
			});

			var status = $("#pembongkaran-tr_konversi_sku_status").val();

			if (status == 'Completed') {
				$("#btn_update_konversi").hide();
				$("#btn_konfirmasi_konversi").hide();
			}

			var total = $(".index").length;
			var result = 0;
			for (var i = 0; i < total; i++) {
				var total1 = $("#index-" + i).attr("data-total1");
				var total2 = $("#index-" + i).attr("data-total2");
				if (total1 == total2) {
					result = 1;
				}
			}
			console.log(result);

			if (result != 1) {
				$("#btn_konfirmasi_konversi").prop("disabled", true);
			}

			// Memeriksa flag atau indikator setelah reload halaman
			var konfirmasiEnabled = sessionStorage.getItem('konfirmasiEnabled');
			if (konfirmasiEnabled === 'true') {
				// Mengaktifkan tombol konfirmasi
				$("#btn_konfirmasi_konversi").prop('disabled', false);
				// Menghapus flag atau indikator setelah digunakan
				sessionStorage.removeItem('konfirmasiEnabled');
			}

			$.ajax({
				async: false,
				url: "<?= base_url('WMS/PembongkaranBarang/delete_tr_konversi_sku_detail2_temp'); ?>",
				type: "POST",
				dataType: "JSON",
				success: function(data) {
					// console.log(data);
				}
			});

			<?php if ($act == "edit") { ?>
				<?php foreach ($KonversiDetail as $i => $detail) : ?>
					arr_konversi_detail.push({
						'tr_konversi_sku_detail_id': "<?= $detail['tr_konversi_sku_detail_id'] ?>",
						'sku_id': "<?= $detail['sku_id'] ?>",
						'sku_stock_expired_date': "<?= $detail['sku_stock_expired_date'] ?>",
						'tr_konversi_sku_detail_qty_plan': "<?= $detail['tr_konversi_sku_detail_qty_plan'] ?>"
					});

					arr_sku.push("'<?= $detail['sku_id'] ?>'");
					arr_sku_exp.push("'<?= $detail['sku_stock_expired_date'] ?>'");

					var result = arr_sku.reduce((unique, o) => {
						if (!unique.some(obj => obj === o)) {
							unique.push(o);
						}
						return unique;
					}, []);

					var result2 = arr_sku_exp.reduce((unique, o) => {
						if (!unique.some(obj => obj === o)) {
							unique.push(o);
						}
						return unique;
					}, []);

					arr_sku = result;
					arr_sku_exp = result2;
				<?php endforeach; ?>

				<?php
				foreach ($KonversiDetail2 as $i => $detail) :
					if ($detail['tr_konversi_sku_detail_id'] != "") {
				?>
						arr_konversi_detail2.push({
							'tr_konversi_sku_detail_id': "<?= $detail['tr_konversi_sku_detail_id'] ?>",
							'sku_stock_id': "<?= $detail['sku_stock_id'] ?>",
							'sku_id': "<?= $detail['sku_id'] ?>",
							'sku_stock_expired_date': "<?= $detail['sku_stock_expired_date'] ?>",
							'tr_konversi_sku_detail2_qty': "<?= $detail['tr_konversi_sku_detail2_qty'] ?>",
							'tr_konversi_sku_detail2_qty_result': "<?= $detail['tr_konversi_sku_detail2_qty_result'] ?>",
							'pallet_id_asal': "<?= $detail['pallet_id_asal'] ?>",
							'pallet_id_tujuan': "<?= $detail['pallet_id_tujuan'] ?>"
						});
				<?php }
				endforeach; ?>

				$.ajax({
					async: false,
					url: "<?= base_url('WMS/PembongkaranBarang/insert_tr_konversi_sku_detail2_temp'); ?>",
					type: "POST",
					data: {
						detail: arr_konversi_detail2
					},
					dataType: "JSON",
					success: function(data) {
						// console.log(data);
					}
				});
			<?php } ?>
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

	$('#pembongkaran-konversi_is_need_approval').click(function(event) {
		if (this.checked) {
			$("#pembongkaran-tr_konversi_sku_status").val("In Progress Approval");
		} else {
			$("#pembongkaran-tr_konversi_sku_status").val("Draft");
		}
	});

	$("#pembongkaran-tr_konversi_sku_id").on("change", function() {

		if ($("#pembongkaran-tr_konversi_sku_id").val() != "") {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/PembongkaranBarang/GetKonversiById') ?>",
				data: {
					tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val()
				},
				dataType: "JSON",
				success: function(response) {
					$("#pembongkaran-tipe_konversi_id").html('');
					$("#pembongkaran-tr_konversi_sku_tanggal").val('');
					$("#pembongkaran-client_wms_id").html('');
					$("#pembongkaran-depo_detail_id").html('');
					$("#pembongkaran-tr_konversi_sku_status").val('');

					$("#table-sku-pembongkaran > tbody").empty();

					if (response.KonversiHeader != 0) {
						$.each(response.KonversiHeader, function(i, v) {
							$("#pembongkaran-tipe_konversi_id").append(
								`<option value="${v.tipe_konversi_id}">${v.tipe_konversi_nama}</option>`
							);
							$("#pembongkaran-tr_konversi_sku_tanggal").val(v
								.tr_konversi_sku_tanggal);
							$("#pembongkaran-client_wms_id").append(
								`<option value="${v.client_wms_id}">${v.client_wms_nama}</option>`
							);
							$("#pembongkaran-depo_detail_id").append(
								`<option value="${v.depo_detail_id}">${v.depo_detail_nama}</option>`
							);
							$("#pembongkaran-tr_konversi_sku_status").val(v
								.tr_konversi_sku_status);
						});
					}

					if (response.KonversiDetail != 0) {
						$.each(response.KonversiDetail, function(i, v) {
							$("#table-sku-pembongkaran > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-tr_konversi_sku_detail_id" value="${v.tr_konversi_sku_detail_id}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_id" value="${v.sku_id}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_kode" value="${v.sku_kode}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_nama_produk" value="${v.sku_nama_produk}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-brand" value="${v.brand}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_kemasan" value="${v.sku_kemasan}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_satuan" value="${v.sku_satuan}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_expired_date" value="${v.sku_stock_expired_date}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" value="${v.tr_konversi_sku_detail_qty_plan}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-tr_konversi_sku_detail2_qty_result" value="${v.tr_konversi_sku_detail2_qty_result}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_id" value="${v.sku_stock_id}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">${i+1}</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-kode-label">${v.sku_kode}</span>
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
								</td>
								<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date}</td>
								<td style="text-align: center; vertical-align: middle;">${v.tr_konversi_sku_detail_qty_plan}</td>
								<td style="text-align: center; vertical-align: middle;">
									<button class="btn btn-primary btn-small" onclick="GetSKUKonversi(${i})"><i class="fa fa-angle-down"></i></button>
								</td>
							</tr>
						`);
						});
					}
				}
			});
		} else {
			$("#pembongkaran-tipe_konversi_id").html('');
			$("#pembongkaran-tr_konversi_sku_tanggal").val('');
			$("#pembongkaran-client_wms_id").html('');
			$("#pembongkaran-depo_detail_id").html('');
			$("#pembongkaran-tr_konversi_sku_status").val('');

			$("#table-sku-pembongkaran > tbody").empty();
		}

	});

	$("#btn_save_konversi").on("click", function() {
		var idx = 0;

		arr_konversi_detail = [];

		$("#table-sku-pembongkaran > tbody tr").each(function() {
			arr_konversi_detail.push({
				'tr_konversi_sku_detail_id': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_id").val(),
				'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
				'sku_stock_expired_date': $("#item-" + idx +
					"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
				'tr_konversi_sku_detail_qty_plan': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val()
			});
			idx++;
		});

		// console.log(arr_konversi_detail);

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/PembongkaranBarang/insert_tr_konversi_sku'); ?>", {
					tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
					client_wms_id: $("#pembongkaran-client_wms_id").val(),
					depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
					tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
					tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
					tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
					tr_konversi_sku_status: $("#pembongkaran-tr_konversi_sku_status").val(),
					tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
					tr_konversi_sku_tgl_create: "",
					tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
					konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked")
						.val(),
					detail: arr_konversi_detail2,
					tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update").val()
				}, "POST", "JSON", function(response) {
					if (response == 1) {
						$("#loadingview").hide();

						let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0)
							.text();
						message_custom("Success", "success", alert_tes);

						setTimeout(() => {
							location.href =
								"<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
						}, 500);
					} else if (response == 3) {
						messageNotSameLastUpdated();
						return false;
					} else {
						$("#loadingview").hide();

						let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
							.text();
						message_custom("Error", "error", alert_tes);
					}
				}, "#btn_save_konversi")
			}
		});

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Pastikan data yang sudah anda input benar!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Simpan",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		//ajax save data

		// 		$("#loadingview").show();

		// 		$.ajax({
		// 			async: false,
		// 			url: "<?= base_url('WMS/PembongkaranBarang/insert_tr_konversi_sku'); ?>",
		// 			type: "POST",
		// 			data: {
		// 				tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
		// 				client_wms_id: $("#pembongkaran-client_wms_id").val(),
		// 				depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
		// 				tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
		// 				tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
		// 				tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
		// 				tr_konversi_sku_status: $("#pembongkaran-tr_konversi_sku_status").val(),
		// 				tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
		// 				tr_konversi_sku_tgl_create: "",
		// 				tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
		// 				konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
		// 				detail: arr_konversi_detail2
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				if (data == 1) {
		// 					$("#loadingview").hide();

		// 					let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
		// 					message_custom("Success", "success", alert_tes);

		// 					setTimeout(() => {
		// 						location.href = "<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
		// 					}, 500);
		// 				} else {
		// 					$("#loadingview").hide();

		// 					let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0).text();
		// 					message_custom("Error", "error", alert_tes);
		// 				}
		// 			}
		// 		});
		// 	}
		// });
	});

	$("#btn_update_konversi").on("click", function() {
		var idx = 0;

		arr_konversi_detail = [];

		$("#table-sku-pembongkaran > tbody tr").each(function() {
			arr_konversi_detail.push({
				'tr_konversi_sku_detail_id': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_id").val(),
				'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
				'sku_stock_expired_date': $("#item-" + idx +
					"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
				'tr_konversi_sku_detail_qty_plan': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val()
			});
			idx++;
		});

		if (arr_konversi_detail2.length > 0) {

			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
				.then((result) => {
					if (result.value == true) {
						requestAjax("<?= base_url('WMS/PembongkaranBarang/update_tr_konversi_sku'); ?>", {
							tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
							client_wms_id: $("#pembongkaran-client_wms_id").val(),
							depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
							tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
							tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
							tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
							tr_konversi_sku_status: $("#pembongkaran-tr_konversi_sku_status").val(),
							tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan")
								.val(),
							tr_konversi_sku_tgl_create: "",
							tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
							konversi_is_need_approval: $(
								"#pembongkaran-konversi_is_need_approval:checked").val(),
							detail: arr_konversi_detail2,
							tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update")
								.val()
						}, "POST", "JSON", function(response) {
							if (response == 1) {
								$("#loadingview").hide();

								let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0)
									.text();
								message_custom("Success", "success", alert_tes);
								sessionStorage.setItem('konfirmasiEnabled', true);
								// $("#btn_konfirmasi_konversi").prop("disabled", false);
								setTimeout(() => {
									// location.href =
									//     "<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
									location.reload();
								}, 1000);
							} else if (response == 3) {
								messageNotSameLastUpdated();
								return false;
							} else {
								$("#loadingview").hide();

								let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
									.text();
								message_custom("Error", "error", alert_tes);
							}
						}, "#btn_update_konversi")
					}
				});

			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Pastikan data yang sudah anda input benar!",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Simpan",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		//ajax save data

			// 		$("#loadingview").show();

			// 		$.ajax({
			// 			async: false,
			// 			url: "<?= base_url('WMS/PembongkaranBarang/update_tr_konversi_sku'); ?>",
			// 			type: "POST",
			// 			data: {
			// 				tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
			// 				client_wms_id: $("#pembongkaran-client_wms_id").val(),
			// 				depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
			// 				tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
			// 				tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
			// 				tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
			// 				tr_konversi_sku_status: $("#pembongkaran-tr_konversi_sku_status").val(),
			// 				tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
			// 				tr_konversi_sku_tgl_create: "",
			// 				tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
			// 				konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
			// 				detail: arr_konversi_detail2
			// 			},
			// 			dataType: "JSON",
			// 			success: function(data) {
			// 				if (data == 1) {
			// 					$("#loadingview").hide();

			// 					let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
			// 					message_custom("Success", "success", alert_tes);

			// 					setTimeout(() => {
			// 						location.href = "<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
			// 					}, 500);
			// 				} else {
			// 					$("#loadingview").hide();

			// 					let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0).text();
			// 					message_custom("Error", "error", alert_tes);
			// 				}
			// 			}
			// 		});
			// 	}
			// });
		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-NILAIBELUMTERKONVERSISEMUA"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}
	});

	$("#btn_konfirmasi_konversi").on("click", function() {
		var idx = 0;

		arr_konversi_detail = [];

		$("#table-sku-pembongkaran > tbody tr").each(function() {
			arr_konversi_detail.push({
				'tr_konversi_sku_detail_id': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_id").val(),
				'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
				'sku_stock_expired_date': $("#item-" + idx +
					"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
				'tr_konversi_sku_detail_qty_plan': $("#item-" + idx +
					"-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val()
			});
			idx++;
		});

		// console.log(arr_konversi_detail);

		if (arr_konversi_detail2.length > 0) {

			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
				.then((result) => {
					if (result.value == true) {
						requestAjax("<?= base_url('WMS/PembongkaranBarang/konfirmasi_tr_konversi_sku'); ?>", {
							tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
							client_wms_id: $("#pembongkaran-client_wms_id").val(),
							depo_detail_id: $("#pembongkaran-depo_detail_id_tujuan").val(),
							tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
							tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
							tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
							tr_konversi_sku_status: "Completed",
							tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan")
								.val(),
							tr_konversi_sku_tgl_create: "",
							tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
							konversi_is_need_approval: $(
								"#pembongkaran-konversi_is_need_approval:checked").val(),
							detail: arr_konversi_detail2,
							tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update")
								.val()
						}, "POST", "JSON", function(response) {
							if (response == 1) {
								$("#loadingview").hide();

								let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0)
									.text();
								message_custom("Success", "success", alert_tes);

								setTimeout(() => {
									location.href =
										"<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
								}, 500);
							} else if (response == 3) {
								messageNotSameLastUpdated();
								return false;
							} else {
								$("#loadingview").hide();

								let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
									.text();
								message_custom("Error", "error", alert_tes);
							}
						}, "#btn_konfirmasi_konversi")
					}
				});

			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Pastikan data yang sudah anda input benar!",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Simpan",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		//ajax save data

			// 		$("#loadingview").show();

			// 		$.ajax({
			// 			async: false,
			// 			url: "<?= base_url('WMS/PembongkaranBarang/konfirmasi_tr_konversi_sku'); ?>",
			// 			type: "POST",
			// 			data: {
			// 				tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
			// 				client_wms_id: $("#pembongkaran-client_wms_id").val(),
			// 				depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
			// 				tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
			// 				tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
			// 				tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
			// 				tr_konversi_sku_status: "Completed",
			// 				tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
			// 				tr_konversi_sku_tgl_create: "",
			// 				tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
			// 				konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
			// 				detail: arr_konversi_detail2
			// 			},
			// 			dataType: "JSON",
			// 			success: function(data) {
			// 				if (data == 1) {
			// 					$("#loadingview").hide();

			// 					let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
			// 					message_custom("Success", "success", alert_tes);

			// 					setTimeout(() => {
			// 						location.href = "<?= base_url(); ?>WMS/PembongkaranBarang/PembongkaranMenu";
			// 					}, 500);
			// 				} else {
			// 					$("#loadingview").hide();

			// 					let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0).text();
			// 					message_custom("Error", "error", alert_tes);
			// 				}
			// 			}
			// 		});
			// 	}
			// });
		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-NILAIBELUMTERKONVERSISEMUA"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}
	});

	function GetSKUKonversi(index) {
		// GetSKUKonversi(tr_konversi_sku_detail_id, sku_id, sku_stock_expired_date, index, sku_kode, sku, kemasan, satuan, qty_plan, qty_result)

		var tr_konversi_sku_detail_id = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_id")
			.val();
		var sku_id = $("#item-" + index + "-persetujuanpembongkarandetail-sku_id").val();
		var sku_stock_expired_date = $("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_expired_date").val();
		var sku_kode = $("#item-" + index + "-persetujuanpembongkarandetail-sku_kode").val();
		var sku = $("#item-" + index + "-persetujuanpembongkarandetail-sku_nama_produk").val();
		var kemasan = $("#item-" + index + "-persetujuanpembongkarandetail-sku_kemasan").val();
		var satuan = $("#item-" + index + "-persetujuanpembongkarandetail-sku_satuan").val();
		var qty_plan = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val();
		var qty_result = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail2_qty_result").val();
		var sku_stock_id = $("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_id").val();

		$("#btn_save_konversi_detail").attr("data-index", index);

		$("#pembongkarandetail2-index").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_id").val('');
		$("#pembongkarandetail2-sku_kode").val('');
		$("#pembongkarandetail2-sku_nama_produk").val('');
		$("#pembongkarandetail2-sku_stock_expired_date").val('');
		$("#pembongkarandetail2-sku_satuan").val('');
		$("#pembongkarandetail2-sku_kemasan").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val('');
		$("#pembongkarandetail2-sku_stock_id").val('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PembongkaranBarang/GetKonversiDetailPallet') ?>",
			data: {
				tr_konversi_sku_detail_id: tr_konversi_sku_detail_id,
				sku_id: sku_id,
				sku_stock_expired_date: sku_stock_expired_date,
				sku_stock_id: sku_stock_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-konversi-detail > tbody").empty();

				if (response != 0) {
					$("#modal-konversi").modal('show');

					$.each(response, function(i, v) {
						$("#table-konversi-detail > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">
									<input disabled type="checkbox" name="CheckboxKonversi" id="check_konversi_${i}" value="1" ${v.tr_konversi_sku_detail2_qty > 0 ? 'checked' : '' }>
									<input type="hidden" id="check_locked_${i}" value="${v.pallet_is_lock}">
									<input type="hidden" id="pallet_id_${i}" value="${v.pallet_id}">
								</td>
								<td style="text-align: center; vertical-align: middle;">${v.depo_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.pallet_kode}</td>
								<td style="text-align: center; vertical-align: middle;">${v.pallet_is_lock}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_qty}</td>
								<td style="text-align: center; vertical-align: middle;">
									<span id="item-${i}-konversiskudetail-sku_stock_ambil">${v.tr_konversi_sku_detail2_qty > 0 ? v.tr_konversi_sku_detail2_qty : 0 }</span>
								</td>
								<td style="text-align: center; vertical-align: middle;"><button type="button" class="btn btn-primary btn-sm" id="btn_form_konversi_${i}" style="${v.tr_konversi_sku_detail2_qty > 0 ? '' : 'display:none;' }" onclick="GetFormKonversiSKUDetail2('${tr_konversi_sku_detail_id}','${i}','${satuan}', '${v.sku_stock_qty}')"><span name="CAPTION-KONVERSI">Konversi</span></button></td>
							</tr>
						`);
					});

					$("#pembongkarandetail2-index").val(index);
					$("#pembongkarandetail2-tr_konversi_sku_detail_id").val(tr_konversi_sku_detail_id);
					$("#pembongkarandetail2-sku_kode").val(sku_kode);
					$("#pembongkarandetail2-sku_nama_produk").val(sku);
					$("#pembongkarandetail2-sku_stock_expired_date").val(sku_stock_expired_date);
					$("#pembongkarandetail2-sku_satuan").val(satuan);
					$("#pembongkarandetail2-sku_kemasan").val(kemasan);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val(qty_plan);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val(qty_result);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val(qty_plan);
					$("#pembongkarandetail2-sku_stock_id").val(sku_stock_id);
				} else {
					$("#pembongkarandetail2-index").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_id").val('');
					$("#pembongkarandetail2-sku_kode").val('');
					$("#pembongkarandetail2-sku_nama_produk").val('');
					$("#pembongkarandetail2-sku_stock_expired_date").val('');
					$("#pembongkarandetail2-sku_satuan").val('');
					$("#pembongkarandetail2-sku_kemasan").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val('');
					$("#pembongkarandetail2-sku_stock_id").val('');
					$("#modal-konversi").modal('hide');
				}
			}
		});

	}

	function GetFormKonversiSKUDetail2(tr_konversi_sku_detail_id, index, satuan, sku_stock_qty) {
		var pallet_id = $("#pallet_id_" + index).val();
		var tipe_konversi = $("#pembongkaran-tipe_konversi_id").text();
		var gudang_asal = $("#pembongkaran-depo_detail_id").text().trim();
		var gudang_tujuan = $("#depo_detail_tujuan").val();
		var total_nilai = 0;

		$("#filter_pallet_id").val('');
		$("#filter_konversi_sku").val('');
		$("#filter_konversi_kemasan").val('');
		$("#filter_konversi_satuan").val('');
		$("#filter_index").val('');
		$("#filter_konversi_qty_plan").val('');
		$("#filter_konversi_qty_sisa").val('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PembongkaranBarang/Getkonversiskudetail2') ?>",
			data: {
				tr_konversi_sku_detail_id: tr_konversi_sku_detail_id,
				pallet_id: pallet_id,
				satuan: satuan,
				tipe_konversi: tipe_konversi
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-konversi-by-sku > tbody").empty();

				if (response != 0) {
					$("#modal-konversi").modal('hide');
					$("#modal-konversi-detail").modal('show');

					$.each(response.header, function(i, v) {
						$("#filter_pallet_id").val(pallet_id);
						$("#filter_konversi_sku").val(v.sku_nama_produk);
						$("#namaSKU").html(v.sku_nama_produk);
						$("#gudang_asal").val(gudang_asal);
						$("#gudang_tujuan").val(gudang_tujuan);
						$("#filter_konversi_kemasan").val(v.sku_kemasan);
						$("#filter_konversi_satuan").val(v.sku_satuan);
						$("#filter_index").val(index);
						$("#filter_konversi_qty_plan").val(v.tr_konversi_sku_detail_qty_plan);
						$("#filter_konversi_qty_sisa").val(v.tr_konversi_sku_detail_qty_plan - v
							.tr_konversi_sku_detail2_qty);
					});

					var sku_sisa = $("#filter_konversi_qty_sisa").val();

					$.each(response.detail, function(i, v) {
						if (v.is_from_do == 1) {
							$("#table-konversi-by-sku > tbody").append(`
								<tr>
									<td style="text-align: center; vertical-align: middle;">
										<input type="checkbox" class="checkboxdetail" name="CheckboxKonversi" id="check_konversi_detail3_${i}" value="1" onchange="onlyOneChecked(this)" onclick="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" ${(v.tr_konversi_sku_detail2_qty != '0' || (v.sku_konversi_hasil == v.sku_satuan)) ? '' : 'disabled'}>
										<input type="hidden" id="item-${i}-sku_stock_qty" class="form-control input-sm" value="${sku_stock_qty}" />
										<input type="hidden" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail_id" value="${v.tr_konversi_sku_detail_id}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_id" value="${v.sku_id_konversi}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_id" value="">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_konversi_faktor" value="${v.sku_konversi_faktor}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_konversi_faktor_param" value="${v.sku_konversi_faktor_param}">
										<input type="hidden" id="item-${i}-konversiskudetail2-konversi_operator" value="${v.konversi_operator}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_asal" value="${pallet_id}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_tujuan" value="${v.pallet_id_tujuan == null ? pallet_id : v.pallet_id_tujuan}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_tujuan_baru" value="${v.pallet_id_tujuan == null ? "" : v.pallet_id_tujuan}">
									</td>
									<td width="10%" style="text-align: center; vertical-align: middle;">
										<input type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${parseFloat(sku_stock_qty) <= parseFloat(sku_sisa) ? sku_stock_qty : sku_sisa}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" disabled />
									</td>
									<td width="5%" style="text-align: center; vertical-align: middle;">${v.sku_satuan_param}</td>
									<td width="5%" style="text-align: center; vertical-align: middle;"><span id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty_result">${v.tr_konversi_sku_detail2_qty_result}</span></td>
									<td width="5%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td width="10%" style="text-align: center; vertical-align: middle;">
										<select id="item-${i}-konversiskudetail2-pallet_tujuan_req" class="form-control" onchange="reqPalletTujuan(this.value,'${i}','${v.pallet_id}','${v.pallet_kode}')" disabled>
											<option value="0"><label name="CAPTION-TIDAK">Tidak</label></option>
											<option value="1" selected><label name="CAPTION-YA" >Ya</label></option>
										</select>
									</td>
									<td width="5%" style="text-align: center; vertical-align: middle;">
										<span id="pallet_kode_tujuan_${i}">${v.pallet_id_tujuan == null ? v.pallet_kode : v.pallet_kode_tujuan}</span>
									</td>
									<td width="50%" style="vertical-align: middle;">
										<div class="head-switch" id="span_scan_by_one_${i}" style="display:none;">
											<div class="switch-holder">
												<div class="switch-toggle">
													<input type="checkbox" id="check_scan_${i}" class="check_scan" onchange="scanOrinput('${i}', this)">
													<label for="check_scan_${i}"></label>
												</div>
												<div class="switch-label">
													<button type="button" data-id="${i}" class="btn btn-info start_scan_by_one" name="start_scan_by_one" id="start_scan_${i}">
														<i class="fas fa-qrcode"></i> Scan
													</button>
													<button type="button" class="btn btn-warning input_pallet" name="input_pallet" id="input_manual_${i}" style="display:none" onclick="inputManual('${i}', this)">
														<i class="fas fa-keyboard"></i> Input
													</button>
													<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_${i}">
														<i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label>
													</button>
													<button type="button" class="btn btn-danger" style="display:none" id="close_input_${i}" onclick="closeInput('${i}', this)">
														<i class="fas fa-xmark"></i> Close
													</button>
												</div>
											</div>
										</div>
										<div id="preview_input_manual_${i}" style="display: none; margin-top:10px">
											<div class="row">
												<div class="col-md-8">
													<div class="form-group">
														<label name="CAPTION-KODEPALLET">Kode Pallet</label>
														<input type="text" class="form-control" id="kode_barcode2" placeholder="PAL/00000002">
														<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
															<table class="table table-striped table-sm table-hover" id="table-fixed2">
																<tbody id="konten-table2"></tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<label name=""></label>
													<button type="button" class="btn btn-success" id="search_kode_pallet2" data-idx="${i}">
														<i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label>
													</button>
												</div>
											</div>
										</div>
									</td>

								</tr>
							`);
						} else {
							$("#table-konversi-by-sku > tbody").append(`
								<tr>
									<td style="text-align: center; vertical-align: middle;">
										<input type="checkbox" class="checkboxdetail" name="CheckboxKonversi" id="check_konversi_detail3_${i}" value="1" onchange="onlyOneChecked(this)" onclick="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" ${v.tr_konversi_sku_detail2_qty != '0' ? 'checked' : '' }>
										<input type="hidden" id="item-${i}-sku_stock_qty" class="form-control input-sm" value="${sku_stock_qty}" />
										<input type="hidden" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail_id" value="${v.tr_konversi_sku_detail_id}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_id" value="${v.sku_id_konversi}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_id" value="">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_konversi_faktor" value="${v.sku_konversi_faktor}">
										<input type="hidden" id="item-${i}-konversiskudetail2-sku_konversi_faktor_param" value="${v.sku_konversi_faktor_param}">
										<input type="hidden" id="item-${i}-konversiskudetail2-konversi_operator" value="${v.konversi_operator}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_asal" value="${pallet_id}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_tujuan" value="${v.pallet_id_tujuan == null ? pallet_id : v.pallet_id_tujuan}">
										<input type="hidden" id="item-${i}-konversiskudetail2-pallet_id_tujuan_baru" value="${v.pallet_id_tujuan == null ? "" : v.pallet_id_tujuan}">
									</td>
									<td width="10%" style="text-align: center; vertical-align: middle;">
										<input type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${parseFloat(sku_stock_qty) <= parseFloat(sku_sisa) ? sku_stock_qty : sku_sisa}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" disabled />
									</td>
									<td width="5%" style="text-align: center; vertical-align: middle;">${v.sku_satuan_param}</td>
									<td width="5%" style="text-align: center; vertical-align: middle;"><span id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty_result">${v.tr_konversi_sku_detail2_qty_result}</span></td>
									<td width="5%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td width="10%" style="text-align: center; vertical-align: middle;">
										<select id="item-${i}-konversiskudetail2-pallet_tujuan_req" class="form-control" onchange="reqPalletTujuan(this.value,'${i}','${v.pallet_id}','${v.pallet_kode}')" disabled>
											<option value="0"><label name="CAPTION-TIDAK">Tidak</label></option>
											<option value="1" selected><label name="CAPTION-YA" >Ya</label></option>
										</select>
									</td>
									<td width="5%" style="text-align: center; vertical-align: middle;">
										<span id="pallet_kode_tujuan_${i}">${v.pallet_id_tujuan == null ? v.pallet_kode : v.pallet_kode_tujuan}</span>
									</td>
									<td width="50%" style="vertical-align: middle;">
										<div class="head-switch" id="span_scan_by_one_${i}" style="display:none;">
											<div class="switch-holder">
												<div class="switch-toggle">
													<input type="checkbox" id="check_scan_${i}" class="check_scan" onchange="scanOrinput('${i}', this)">
													<label for="check_scan_${i}"></label>
												</div>
												<div class="switch-label">
													<button type="button" data-id="${i}" class="btn btn-info start_scan_by_one" name="start_scan_by_one" id="start_scan_${i}">
														<i class="fas fa-qrcode"></i> Scan
													</button>
													<button type="button" class="btn btn-warning input_pallet" name="input_pallet" id="input_manual_${i}" style="display:none" onclick="inputManual('${i}', this)">
														<i class="fas fa-keyboard"></i> Input
													</button>
													<button type="button" class="btn btn-danger" style="display:none" id="stop_scan_${i}">
														<i class="fas fa-xmark"></i> <label name="CAPTION-STOPSCAN">Stop Scan</label>
													</button>
													<button type="button" class="btn btn-danger" style="display:none" id="close_input_${i}" onclick="closeInput('${i}', this)">
														<i class="fas fa-xmark"></i> Close
													</button>
												</div>
											</div>
										</div>
										<div id="preview_input_manual_${i}" style="display: none; margin-top:10px">
											<div class="row">
												<div class="col-md-8">
													<div class="form-group">
														<label name="CAPTION-KODEPALLET">Kode Pallet</label>
														<input type="text" class="form-control" id="kode_barcode2" placeholder="PAL/00000002">
														<div style="max-height: 20vh;overflow:hidden;overflow-y:scroll">
															<table class="table table-striped table-sm table-hover" id="table-fixed2">
																<tbody id="konten-table2"></tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<label name=""></label>
													<button type="button" class="btn btn-success" id="search_kode_pallet2" data-idx="${i}">
														<i class="fas fa-search"></i> <label name="CAPTION-CHECKKODE">Check Kode</label>
													</button>
												</div>
											</div>
										</div>
									</td>

								</tr>
							`);
						}
						$(`#item-${i}-konversiskudetail2-pallet_tujuan_req`).trigger('change')
						total_nilai += v.tr_konversi_sku_detail2_qty;

						// //hanya bisa memilih 1 checkbox
						// $('input[type="checkbox"][name="CheckboxKonversi"]').on('change', function() {
						//     $('input[type="checkbox"][name="CheckboxKonversi"]').not(this).prop(
						//         'checked', false);
						// });
					});

					$("#table-konversi-by-sku > tbody tr").each(function(i, v) {
						let check = $(this).find("td:eq(6) input[type='checkbox']");
						let scan = $(this).find("td:eq(6) button[name='start_scan_by_one']");
						let input = $(this).find("td:eq(6) button[name='input_pallet']");
						check.change(function(e) {
							if (e.target.checked) {
								input.show();
								scan.hide();
							} else {
								input.hide();
								scan.show();
							}
						});
					});

				} else {
					$("#filter_pallet_id").val('');
					$("#filter_konversi_sku").val('');
					$("#filter_konversi_kemasan").val('');
					$("#filter_konversi_satuan").val('');
					$("#filter_index").val('');
					$("#filter_konversi_qty_plan").val('');
					$("#filter_konversi_qty_sisa").val('');
					$("#modal-konversi").modal('show');
					$("#modal-konversi-detail").modal('hide');
				}
			}
		});
	}

	function inputManual(index, e) {
		$("#preview_input_manual_" + index).show();
		$("#input_manual_" + index).hide();
		$("#close_input_" + index).show();

	}

	function closeInput(index, e) {
		$("#preview_input_manual_" + index).hide();
		$("#close_input_" + index).hide();
		$("#input_manual_" + index).show();

	}

	function onlyOneChecked(e) {
		var isChecked = $(e).is(':checked');
		$('input[type="checkbox"][class="checkboxdetail"]').not(e).prop(
			'checked', false);

		var index = $(e).attr('id').split('_').pop();
		var row = $(e).closest('tr');

		// if (isChecked) {
		//     // Mengaktifkan elemen terkait ketika checkbox dicentang
		//     $(e).find('[id="item-' + index + '-konversiskudetail2"]').prop('disabled', false);
		// } else {
		//     // Menonaktifkan elemen terkait ketika checkbox tidak dicentang
		//     $(e).find('[id="item-' + index + '-konversiskudetail2"]').prop('disabled', true);
		// }

		// Mengecek keseluruhan checkbox yang tidak dicentang
		var checkbox = $('input[type="checkbox"][class="checkboxdetail"]');
		// console.log(checkbox);

		for (var i = 0; i < checkbox; i++) {
			console.log(checkbox[i]);
			if (checkbox[i] == false) {
				$('[id="item-' + i + '-konversiskudetail2"]').prop('disabled', true);
			} else {
				$('[id="item-' + i + '-konversiskudetail2"]').prop('disabled', true);
			}
		}
	}


	$("#btn_close_konversi_detail").on("click", function() {
		$("#modal-konversi").modal('show');
		$("#modal-konversi-detail").modal('hide');
	});

	$("#btn_save_konversi_detail").on("click", function() {
		var idx = 0;
		var total_qty = 0;
		var qty_plan = $("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val();
		var index = $(this).attr("data-index");

		$("#loadingkonversi").show();

		$("#table-konversi-detail > tbody tr").each(function() {
			total_qty += parseInt($("#item-" + idx + "-konversiskudetail-sku_stock_ambil").text());
			idx++;
		});

		if (qty_plan == total_qty) {
			$("#loadingkonversi").hide();

			let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
			message_custom("Success", "success", alert_tes);

			$("#qtyAmbilTD-" + index + "").html(total_qty);

			$("#btn-" + index + "").attr('disabled', true);

			$("#modal-konversi").modal('hide');

		} else if (qty_plan < total_qty) {
			$("#loadingkonversi").hide();

			let alert_tes = $('span[name="CAPTION-ALERT-NILAIKONVERSIMELEBIHIQTYPLAN').eq(0).text();
			message_custom("Error", "error", alert_tes);

		} else {
			$("#loadingkonversi").hide();

			let alert_tes = $('span[name="CAPTION-ALERT-NILAIBELUMTERKONVERSISEMUA"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}

	});

	$("#btn_save_konversi_detail2").on("click", function() {
		var idx = 0;
		var check_max_qty_detail3 = 0;
		var check_qty_detail3 = 0;
		var check_checked = 0;
		var check_pallet_kode_tujuan = 0;
		var total_qty = 0;
		var max_qty = 0;
		var sku_stock_qty = 0;
		var qty_sisa = parseInt($("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val());
		var pallet_id = $("#filter_pallet_id").val();

		$("#table-konversi-by-sku > tbody tr").each(function() {
			var checkbox = $(this).find('[id^="check_konversi_detail3_"]');
			var isChecked = checkbox.is(":checked");
			var checked = $('[id="check_konversi_detail3_' + idx + '"]:checked').length;

			if (isChecked) {
				var batasQty = $("#item-" + idx + "-sku_stock_qty").val();
				sku_stock_qty = batasQty
				var qtyInput = $("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty");
				var qtyValue = parseInt(qtyInput.val());
				total_qty += qtyValue;

				var depo_detail_id = $("#pembongkaran-depo_detail_id").val();
				var depo_detail_id_tujuan = $("#pembongkaran-depo_detail_id_tujuan").val();

				if (depo_detail_id != depo_detail_id_tujuan) {
					var pallet_kode_tujuan = $("#pallet_kode_tujuan_" + idx + "").attr("data-new");

					if (pallet_kode_tujuan == undefined) {
						check_pallet_kode_tujuan++;
					}
				}
			}

			if (checked > 0) {
				if ($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val() == 0) {
					check_qty_detail3++;
				}
				check_checked++;
			}

			idx++;
		});

		if (parseInt(total_qty) > parseInt(sku_stock_qty)) {
			message_custom("Error", "error", "Pembongkaran SKU melebihi qty pallet!");
			return false;
		}

		// console.log(total_qty);
		// return false;

		// $("#table-konversi-by-sku > tbody tr").each(function() {
		//     var checked = $('[id="check_konversi_detail3_' + idx + '"]:checked').length;
		//     //besok lanjut disini, ganti total_qty sesuai value yg di centang
		//     total_qty += parseInt($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty")
		//         .val());

		//     if (checked > 0) {
		//         if ($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val() == 0) {
		//             check_qty_detail3++;
		//         }
		//         check_checked++;
		//     }

		//     idx++;
		// });
		idx = 0;
		if (check_checked > 0) {
			if (check_qty_detail3 == 0) {
				if (check_pallet_kode_tujuan == 0) {
					if (total_qty <= qty_sisa) {

						$("#table-konversi-by-sku > tbody tr").each(function() {
							var checked = $('[id="check_konversi_detail3_' + idx + '"]:checked').length;
							var select = $("#item-" + idx + "-konversiskudetail2-pallet_tujuan_req").val();
							var pallet_tujuan = '';



							if (checked > 0) {

								if (arr_konversi_detail2.length > 0) {
									$.each(arr_konversi_detail2, function(i, v) {
										if (v.tr_konversi_sku_detail_id == $("#item-" + idx +
												"-konversiskudetail2-tr_konversi_sku_detail_id")
											.val() && v.pallet_id_asal == pallet_id && v
											.pallet_id_tujuan ==
											$("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan")
											.val() && v.sku_id == $("#item-" + idx +
												"-konversiskudetail2-sku_id").val()) {
											arr_konversi_detail2[i] = {
												'tr_konversi_sku_detail_id': $("#item-" + idx +
													"-konversiskudetail2-tr_konversi_sku_detail_id"
												).val(),
												'sku_stock_id': $("#item-" + idx +
													"-konversiskudetail2-sku_stock_id").val(),
												'sku_id': $("#item-" + idx +
													"-konversiskudetail2-sku_id").val(),
												'sku_stock_expired_date': $("#item-" + idx +
													"-konversiskudetail2-sku_stock_expired_date"
												).val(),
												'tr_konversi_sku_detail2_qty': $("#item-" + idx +
													"-konversiskudetail2-tr_konversi_sku_detail2_qty"
												).val(),
												'tr_konversi_sku_detail2_qty_result': $("#item-" +
													idx +
													"-konversiskudetail2-tr_konversi_sku_detail2_qty_result"
												).text(),
												'pallet_id_asal': $("#item-" + idx +
													"-konversiskudetail2-pallet_id_asal").val(),
												'pallet_id_tujuan': $("#item-" + idx +
														"-konversiskudetail2-pallet_id_tujuan")
													.val()
											};
										} else {
											arr_konversi_detail2.push({
												'tr_konversi_sku_detail_id': $("#item-" + idx +
													"-konversiskudetail2-tr_konversi_sku_detail_id"
												).val(),
												'sku_stock_id': $("#item-" + idx +
														"-konversiskudetail2-sku_stock_id")
													.val(),
												'sku_id': $("#item-" + idx +
													"-konversiskudetail2-sku_id").val(),
												'sku_stock_expired_date': $("#item-" + idx +
													"-konversiskudetail2-sku_stock_expired_date"
												).val(),
												'tr_konversi_sku_detail2_qty': $("#item-" +
													idx +
													"-konversiskudetail2-tr_konversi_sku_detail2_qty"
												).val(),
												'tr_konversi_sku_detail2_qty_result': $(
													"#item-" + idx +
													"-konversiskudetail2-tr_konversi_sku_detail2_qty_result"
												).text(),
												'pallet_id_asal': $("#item-" + idx +
														"-konversiskudetail2-pallet_id_asal")
													.val(),
												'pallet_id_tujuan': $("#item-" + idx +
														"-konversiskudetail2-pallet_id_tujuan")
													.val()
											});

											var result = arr_konversi_detail2.reduce((unique, o) => {
												if (!unique.some(obj => obj
														.tr_konversi_sku_detail_id === o
														.tr_konversi_sku_detail_id && obj
														.pallet_id_asal === o.pallet_id_asal &&
														obj.pallet_id_tujuan === o
														.pallet_id_tujuan && obj.sku_id === o
														.sku_id)) {
													unique.push(o);
												}
												return unique;
											}, []);

											arr_konversi_detail2 = result;

										}
									});
								} else {
									// if (select == 1) {
									//     pallet_tujuan = $("#item-" + idx +
									//         "-konversiskudetail2-pallet_id_tujuan_baru").val();

									//     if (pallet_tujuan == "") {
									//         message("Error!", "Kode Pallet Tujuan Belum Ada!", "error");
									//         return false;
									//     }
									// } else {
									//     pallet_tujuan = $("#item-" + idx +
									//         "-konversiskudetail2-pallet_id_tujuan").val()
									// }

									arr_konversi_detail2.push({
										'tr_konversi_sku_detail_id': $("#item-" + idx +
											"-konversiskudetail2-tr_konversi_sku_detail_id").val(),
										'sku_stock_id': $("#item-" + idx +
											"-konversiskudetail2-sku_stock_id").val(),
										'sku_id': $("#item-" + idx + "-konversiskudetail2-sku_id")
											.val(),
										'sku_stock_expired_date': $("#item-" + idx +
											"-konversiskudetail2-sku_stock_expired_date").val(),
										'tr_konversi_sku_detail2_qty': $("#item-" + idx +
												"-konversiskudetail2-tr_konversi_sku_detail2_qty")
											.val(),
										'tr_konversi_sku_detail2_qty_result': $("#item-" + idx +
											"-konversiskudetail2-tr_konversi_sku_detail2_qty_result"
										).text(),
										'pallet_id_asal': $("#item-" + idx +
											"-konversiskudetail2-pallet_id_asal").val(),
										'pallet_id_tujuan': $("#item-" + idx +
											"-konversiskudetail2-pallet_id_tujuan").val()
										// 'pallet_id_tujuan': pallet_tujuan
									});
								}

								// console.log(arr_konversi_detail2);

								total_qty += parseInt($("#item-" + idx +
									"-konversiskudetail2-tr_konversi_sku_detail2_qty").val());
							}
							idx++;
						});


						console.log(arr_konversi_detail2);

						qty_sisa = qty_sisa - total_qty;

						$("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val(qty_sisa);

						// console.log(arr_konversi_detail2);
						// return false;

						$.ajax({
							async: false,
							url: "<?= base_url('WMS/PembongkaranBarang/insert_tr_konversi_sku_detail2_temp'); ?>",
							type: "POST",
							data: {
								detail: arr_konversi_detail2
							},
							dataType: "JSON",
							success: function(data) {
								if (data == 1) {
									$("#loadingkonversi").hide();

									let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN"]').eq(
										0).text();
									message_custom("Success", "success", alert_tes);
									$("#modal-konversi-detail").modal('hide');

									GetSKUKonversi($("#pembongkarandetail2-index").val())

								} else {
									$("#loadingkonversi").hide();

									let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
										.text();
									message_custom("Error", "error", alert_tes);
								}
							}
						});

					} else {
						let alert_tes = $('span[name="MESSAGE-200010"]').eq(0).text();
						message_custom("Error", "error", alert_tes);
					}
				} else {
					message_custom("Error", "error", "Pallet Kode Tujuan Tidak Boleh Kosong");
				}

			} else {
				let alert_tes = $('span[name="CAPTION-ALERT-SKUQTYTIDAKBOLEH0"]').eq(0).text();
				message_custom("Error", "error", alert_tes);
			}

		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
			message_custom("Error", "error", alert_tes);

		}

		// console.log($("#filter_pallet_id").val());
		// console.log(arr_konversi_detail3);

	});

	function GetHasilKonversi(index, konversi, sku_konversi_faktor_param, konversi_operator, qty_before) {
		var qty_plan = parseInt($("#filter_konversi_qty_plan").val());
		var nilai_konversi = 0;
		var qty_sisa = parseInt($("#filter_konversi_qty_sisa").val());
		var total_nilai = 0;
		var idx = 0;
		var checked = $('[id="check_konversi_detail3_' + index + '"]:checked').length;
		var sisa_bagi = 0;
		var hasil_konversi = 0;
		var depo_detail_id = $("#pembongkaran-depo_detail_id").val();
		var depo_detail_id_tujuan = $("#pembongkaran-depo_detail_id_tujuan").val();

		if (checked > 0) {
			$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").prop("disabled", false);
			$("#item-" + index + "-konversiskudetail2-pallet_tujuan_req").prop("disabled", true);

			if (depo_detail_id != depo_detail_id_tujuan) {
				$("#pallet_kode_tujuan_" + index + "").hide();
				$("#span_scan_by_one_" + index + "").show();

				$("#item-" + index + "-konversiskudetail2-pallet_tujuan_req").val("1");
			}

		} else {
			$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").prop("disabled", true);
			$("#item-" + index + "-konversiskudetail2-pallet_tujuan_req").prop("disabled", true);

			if (depo_detail_id != depo_detail_id_tujuan) {
				$("#pallet_kode_tujuan_" + index + "").show();
				$("#span_scan_by_one_" + index + "").hide();
				$("#preview_input_manual_" + index + "").hide();

				$("#item-" + index + "-konversiskudetail2-pallet_tujuan_req").val('1');
			}
		}

		$("#table-konversi-detail > tbody tr").each(function() {
			var checked = $('[id="check_konversi_' + idx + '"]:checked').length;

			if (checked > 0) {
				total_nilai += parseInt($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty")
					.val());
			}
			idx++;
		});

		$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").html('');
		$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").html('');

		// alert((qty_sisa + parseInt($("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val())))

		// qty_sisa = (qty_sisa + parseInt($("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val())) >= 0 ? qty_sisa : 0;

		if (checked > 0) {
			if (konversi > 0) {
				if ((qty_sisa + qty_before) >= parseInt($("#item-" + index +
						"-konversiskudetail2-tr_konversi_sku_detail2_qty").val())) {
					nilai_konversi = parseInt($("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty")
						.val()) * sku_konversi_faktor_param;
					sisa_bagi = nilai_konversi % parseInt(konversi);
					hasil_konversi = (nilai_konversi - sisa_bagi) / parseInt(konversi);

					if (sisa_bagi > 0) {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val((nilai_konversi -
							sisa_bagi));
						$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val(sisa_bagi);

						$("#check_konversi_detail3_" + (index + 1)).prop("checked", true);

						GetHasilKonversi((index + 1), $("#item-" + (index + 1) + "-konversiskudetail2-sku_konversi_faktor")
							.val(), $("#item-" + (index + 1) + "-konversiskudetail2-sku_konversi_faktor_param").val(),
							$("#item-" + (index + 1) + "-konversiskudetail2-konversi_operator").val(), $("#item-" + (
								index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val())
					} else {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
					}

				} else {
					nilai_konversi = qty_sisa * sku_konversi_faktor_param;
					sisa_bagi = nilai_konversi % parseInt(konversi);
					hasil_konversi = (nilai_konversi - sisa_bagi) / parseInt(konversi);

					if (sisa_bagi > 0) {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val((nilai_konversi -
							sisa_bagi));
						$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val(sisa_bagi);

						$("#check_konversi_detail3_" + (index + 1)).prop("checked", true);

						GetHasilKonversi((index + 1), $("#item-" + (index + 1) + "-konversiskudetail2-sku_konversi_faktor")
							.val(), $("#item-" + (index + 1) + "-konversiskudetail2-sku_konversi_faktor_param").val(),
							$("#item-" + (index + 1) + "-konversiskudetail2-konversi_operator").val(), $("#item-" + (
								index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val())
					} else {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
					}

					let alert_tes = $('span[name="CAPTION-ALERT-NILAIKONVERSIMELEBIHIQTYPLAN"]').eq(0).text();
					message_custom("Error", "error", alert_tes);

					$("#modal-konversi-detail").modal('hide');

					GetSKUKonversi($("#pembongkarandetail2-index").val());
				}
			} else {
				let alert_tes = $('span[name="CAPTION-ALERT-SKUKONVERSIFAKTOR0"]').eq(0).text();
				message_custom("Error", "error", alert_tes);

			}
		} else {
			$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append('0');
			$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append('0');
		}
	}

	$(document).on("change", "#check_scan", function(e) {
		if (e.target.checked) {
			$("#start_scan").hide();
			$("#input_manual").show();
			$("#stop_scan").hide();
			$("#preview").hide();
			$("#txtpreviewscan").val("");
			$("#select_kamera").empty();
		} else {
			$("#start_scan").show();
			$("#input_manual").hide();
			$("#close_input").hide();
			$("#preview_input_manual").hide();
			$("#kode_barcode").val("");
			$("#txtpreviewscan").val("");
			$('#myFileInput').val("");
			$('#show-file').empty();
		}
	});

	$(document).on("click", "#input_manual", function() {
		$("#input_manual").hide();
		$("#close_input").show();
		$("#preview_input_manual").show();
		$("#start_scan").attr("disabled", true);
	});

	$(document).on("click", "#close_input", function() {
		remove_close_input();
	});

	function remove_close_input() {
		$("#input_manual").show();
		$("#close_input").hide();
		$("#preview_input_manual").hide();
		$("#kode_barcode").val("");
		$("#txtpreviewscan").val("");
		$('#myFileInput').val("");
		$('#show-file').empty();
		$("#start_scan").attr("disabled", false);
	}

	$(document).on("click", "#start_scan", function() {

		var idx = 0;

		Swal.fire({
			title: 'Memuat Kamera',
			html: '<span><i class="fa fa-spinner fa-spin"></i></span>',
			timer: 2000,
			timerProgressBar: true,
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading()
				const b = Swal.getHtmlContainer().querySelector('b')
				timerInterval = setInterval(() => {
					b.textContent = Swal.getTimerLeft()
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			/* Read more about handling dismissals below */
			if (result.dismiss === Swal.DismissReason.timer) {
				console.log('I was closed by the timer')
			}
		})

		$("#start_scan").hide();
		$("#stop_scan").show();
		$("#preview").show();
		$("#input_manual").attr("disabled", true);

		//handle succes scan
		const qrCodeSuccessCallback = (decodedText, decodedResult) => {
			let temp = decodedText;
			if (temp != "") {
				html5QrCode.pause();
				scan(decodedText);
			}

			// console.log(decodedText)
		};

		const scan = (decodedText) => {

			//check ada apa kosong di tabel distribusi_penerimaan_detail_temp
			//jika ada update statusnya jadi 1

			$.ajax({
				url: "<?= base_url('WMS/PembongkaranBarang/check_kode_pallet'); ?>",
				type: "POST",
				data: {
					id: idd,
					kode_pallet: decodedText
				},
				dataType: "JSON",
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);

					$("#table-konversi-detail > tbody tr").each(function() {
						if ($("#pallet_id_" + idx).val() == data.pallet_id) {
							if ($("#check_locked_" + idx).val() == "LOCKED") {
								$("#check_konversi_" + idx).prop("checked", false);
							} else {
								$("#check_konversi_" + idx).prop("checked", true);
							}
							$("#btn_form_konversi_" + idx).show();
						}
						idx++;
					});

					if (data.type == 200) {
						Swal.fire("Success!", data.message, "success").then(function(result) {
							if (result.value == true) {
								html5QrCode.resume();

							}
						});

					} else if (data.type == 201) {
						Swal.fire("Error!", data.message, "error").then(function(result) {
							if (result.value == true) {
								html5QrCode.resume();

							}
						});
						// message("Error!", data.message, "error");
					} else {
						Swal.fire("Info!", data.message, "info").then(function(result) {
							if (result.value == true) {
								html5QrCode.resume();

							}
						});
					}
				},
			});
		}

		// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
		const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
			let minEdgePercentage = 0.9; // 90%
			let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
			let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
			return {
				width: qrboxSize,
				height: qrboxSize
			};
		}

		const config = {
			fps: 10,
			qrbox: qrboxFunction,
			// rememberLastUsedCamera: true,
			// Only support camera scan type.
			supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
		};
		Html5Qrcode.getCameras().then(devices => {
			if (devices && devices.length) {
				$.each(devices, function(i, v) {
					$("#select_kamera").append(`
                        <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
				});

				$("#select_kamera :input[name='tools']").each(function(i, v) {
					if (i == 0) {
						$(this).attr('checked', true);
					}
				});

				let cameraId = devices[0].id;
				// html5QrCode.start(devices[0]);
				$('input[name="tools"]').on('click', function() {
					// alert($(this).val());
					html5QrCode.stop();
					html5QrCode.start({
						deviceId: {
							exact: $(this).val()
						}
					}, config, qrCodeSuccessCallback);
				});
				//start scan
				html5QrCode.start({
					deviceId: {
						exact: cameraId
					}
				}, config, qrCodeSuccessCallback);

			}
		}).catch(err => {
			message("Error!", "Kamera tidak ditemukan", "error");
			$("#start_scan").show();
			$("#stop_scan").hide();
			$("#input_manual").attr("disabled", false);
		});
	});

	$(document).on("click", "#stop_scan", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
	});

	$(document).on('click', '#search_kode_pallet', function() {
		if ($("#kode_barcode").val() != "") {
			fetch('<?= base_url('WMS/PembongkaranBarang/getKodeAutoComplete?params='); ?>' + $("#kode_barcode")
					.val())
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
								<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
										<td class="col-xs-1">${i + 1}.</td>
										<td class="col-xs-11">${e.kode}</td>
								</tr>
								`;
						})

						document.getElementById('konten-table').innerHTML = data;
						// console.log(data);
						document.getElementById('table-fixed').style.display = 'block';
					}
				});
		} else {
			document.getElementById('table-fixed').style.display = 'none';
		}
	})

	$(document).on('click', '#search_kode_pallet2', function() {
		let idx = $(this).data('idx');

		if ($(`#preview_input_manual_${idx}`).find("#kode_barcode2").val() != "") {
			$.ajax({
				type: "GET",
				url: "<?= base_url('WMS/PembongkaranBarang/getKodeAutoComplete') ?>",
				data: {
					params: $("#kode_barcode2").val(),
					depo_detail_id_tujuan: $("#pembongkaran-depo_detail_id_tujuan").val(),
				},
				dataType: "JSON",
				success: function(results) {
					$("#konten-table2").empty()
					if (results.length > 0) {
						let data = "";
						$.each(results, function(i, v) {
							data += `<tr onclick="getNoSuratJalanEks2('${v.depo_kode_preffix}', '${v.kode}', '${v.pallet_id}', '${idx}')" style="cursor:pointer">
										<td class="col-xs-1">${i + 1}.</td>
										<td class="col-xs-11">${v.kode}</td>
								</tr>`;
						});

						$(`#preview_input_manual_${idx}`).find('#konten-table2').html(data);
						$(`#preview_input_manual_${idx}`).find('#table-fixed2').css('display', 'block');
					} else {
						$(`#preview_input_manual_${idx}`).find('#table-fixed2').css('display', 'none');
					}
				}
			});
		} else {
			$('#table-fixed2').css('display', 'none');
		}
	})
	//modal konversi
	$(document).on("click", "#check_kode", () => {
		let barcode = $("#kode_barcode").val();
		var idx = 0;

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			$.ajax({
				url: "<?= base_url('WMS/PembongkaranBarang/check_kode_pallet'); ?>",
				type: "POST",
				data: {
					kode_pallet: barcode,
					sku_stock_id: $("#pembongkarandetail2-sku_stock_id").val()
				},
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					var check = 0;
					var checkIdx = 0;
					if (data.type == 200) {
						// message("Success!", data.message, "success");
						$("#kode_barcode").val("");

						$("#table-konversi-detail > tbody tr").each(function() {
							if ($("#pallet_id_" + idx).val() == data.pallet_id) {
								check = 1;
								checkIdx = idx;
								// if ($("#check_locked_" + idx).val() == "LOCKED") {
								//     $("#check_konversi_" + idx).prop("checked", false);
								// } else {
								//     $("#check_konversi_" + idx).prop("checked", true);
								// }
								// $("#btn_form_konversi_" + idx).show();
							}
							idx++;
						});

						if (check == 1) {
							if ($("#check_locked_" + checkIdx).val() == "LOCKED") {
								$("#check_konversi_" + checkIdx).prop("checked", false);
							} else {
								$("#check_konversi_" + checkIdx).prop("checked", true);
							}
							$("#btn_form_konversi_" + checkIdx).show();

							message("Success!", data.message, "success");
						} else {
							message("Error!", "Kode Pallet Tidak Cocok", "error");
						}

					} else if (data.type == 201) {
						message("Error!", data.message, "error");

					} else {
						message("Info!", data.message, "info");

					}
				},
				complete: () => {
					$("#loading_cek_manual").hide();
				},
			});
		}
	});

	function view_scan_pallet_by_one(index) {
		$("#modal_scan_by_one").modal('show');
	}

	$("#btn_close_modal_scan_by_one").on("click", function() {
		$("#modal_scan_by_one").modal('hide');
	});

	$(document).on("click", ".start_scan_by_one", function() {
		const idx = $(this).attr('data-id');
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-MEMUATKAMERA">Memuat Kamera</label></span>',
			timer: 1000,
			timerProgressBar: true,
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
				const b = Swal.getHtmlContainer().querySelector('b')
				timerInterval = setInterval(() => {
					b.textContent = Swal.getTimerLeft()
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			/* Read more about handling dismissals below */
			if (result.dismiss === Swal.DismissReason.timer) {
				$("#modal_scan").modal('show');

				//handle succes scan
				const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {
						html5QrCode2.pause();
						scan2(decodedText);
					}
				};

				const scan2 = (decodedText) => {
					$.ajax({
						url: "<?= base_url('WMS/PembongkaranBarang/check_kode_pallet'); ?>",
						type: "POST",
						data: {
							kode_pallet: decodedText
						},
						dataType: "JSON",
						success: function(data) {
							if (data.type == 200) {
								$("#pallet_kode_tujuan_" + idx).html('');
								$("#pallet_kode_tujuan_" + idx).append(data.kode);
								$("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan")
									.val(
										data.pallet_id);

								Swal.fire("Success!", data.message, "success").then(
									function(result) {
										if (result.value == true) {
											html5QrCode2.stop();
											$("#modal_scan").modal('hide');
										}
									});
							} else if (data.type == 201) {
								Swal.fire("Error!", data.message, "error").then(function(
									result) {
									if (result.value == true) {
										html5QrCode2.resume();
									}
								});
							} else {
								Swal.fire("Info!", data.message, "info").then(function(
									result) {
									if (result.value == true) {
										html5QrCode2.resume();
									}
								});
							}
						},
					});
				}

				// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
				const qrboxFunction2 = function(viewfinderWidth, viewfinderHeight) {
					let minEdgePercentage = 0.9; // 90%
					let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
					let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
					return {
						width: qrboxSize,
						height: qrboxSize
					};
				}

				const config2 = {
					fps: 10,
					qrbox: qrboxFunction2,
				};
				Html5Qrcode.getCameras().then(devices => {
					if (devices && devices.length) {
						$("#select_kamera_by_one").empty();
						$.each(devices, function(i, v) {
							$("#select_kamera_by_one").append(`
                        <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
						});

						$("#select_kamera_by_one :input[name='tools2']").each(function(i, v) {
							if (i == 0) {
								$(this).attr('checked', true);
							}
						});

						let cameraId2 = devices[0].id;
						// html5QrCode.start(devices[0]);
						$('input[name="tools2"]').on('click', function() {
							// alert($(this).val());
							html5QrCode2.stop();
							html5QrCode2.start({
								deviceId: {
									exact: $(this).val()
								}
							}, config2, qrCodeSuccessCallback2);
						});
						//start scan
						html5QrCode2.start({
							deviceId: {
								exact: cameraId2
							}
						}, config2, qrCodeSuccessCallback2);

					}
				}).catch(err => {
					message("Error!", "Kamera tidak ditemukan", "error");
					$("#modal_scan").modal('hide');
				});
			}
		});

	});

	$(document).on("click", ".stop_scan_by_one", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
	});

	// $(document).on("click", ".input_pallet", function() {
	//     $("#kode_pallet_by_one").attr("data-id", $(this).attr('data-id'));
	//     $("#manual_input_pallet").modal('show');
	// });

	$(document).on("change", "#kode_pallet_by_one", function() {
		let pallet_kode = $("#kode_pallet_by_one").val();
		const idx = $(this).attr('data-id');

		$.ajax({
			url: "<?= base_url('WMS/PembongkaranBarang/check_kode_pallet'); ?>",
			type: "POST",
			data: {
				kode_pallet: pallet_kode
			},
			dataType: "JSON",
			success: function(data) {
				$("#pallet_kode_tujuan_" + idx).html('');
				$("#pallet_kode_tujuan_" + idx).append(data.kode);
				$("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan").val(data.pallet_id);
				if (data.type == 200) {
					Swal.fire("Success!", data.message, "success").then(function(result) {
						if (result.value == true) {
							$("#manual_input_pallet").modal('hide');
							$("#kode_pallet_by_one").val("");
						}
					});
				} else if (data.type == 201) {
					message("Error!", data.message, "error");
				} else {
					message("Info!", data.message, "info");
				}
			},
		});
	});

	$(document).on("click", ".tutup_input_manual", function() {
		$("#manual_input_pallet").modal('hide');
		$("#kode_pallet_by_one").val("");
	});

	function reqPalletTujuan(req, idx, pallet_id, pallet_kode) {
		$("#pallet_kode_tujuan_" + idx).html('');
		let ceked = $("#check_konversi_detail3_" + idx).prop('checked');

		if (req == 1 && ceked) {
			// $("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan" + idx).val('');
			$("#span_scan_by_one_" + idx + "").show();
		} else {
			$("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan").val(pallet_id);
			$("#item-" + idx + "-konversiskudetail2-pallet_id_tujuan_baru").val('');
			$("#pallet_kode_tujuan_" + idx).append(pallet_kode);
			$("#span_scan_by_one_" + idx + "").hide();
			$("#preview_input_manual_" + idx).hide();
			$("#pallet_kode_tujuan_" + idx + "").show();
		}
	}

	function scanOrinput(index, e) {
		// console.log('aaa');
		if (e.checked) {
			$("#start_scan_" + index).hide();
			$("#stop_scan_" + index).hide();
			$("#input_manual_" + index).show();
			$("#close_input_" + index).hide();
			$("#preview").hide();
			$("#txtpreviewscan").val("");
			$("#select_kamera").empty();
		} else {
			$("#start_scan_" + index).show();
			$("#input_manual_" + index).hide();
			$("#close_input_" + index).hide();
			$("#preview_input_manual").hide();
			$("#kode_barcode2").val("");
			$("#txtpreviewscan").val("");
			$('#myFileInput').val("");
			$('#show-file').empty();
		}
	}

	// document.getElementById('kode_barcode').addEventListener('keyup', function() {
	//     const typeScan = $("#tempValForScan").val();
	//     if (this.value != "") {
	//         fetch('<?= base_url('WMS/PembongkaranBarang/getKodeAutoComplete?params='); ?>' + this.value)
	//             .then(response => response.json())
	//             .then((results) => {
	//                 if (!results[0]) {
	//                     document.getElementById('table-fixed').style.display = 'none';
	//                 } else {
	//                     let data = "";
	//                     // console.log(results);
	//                     results.forEach(function(e, i) {
	//                         data += `
	// 								<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
	// 										<td class="col-xs-1">${i + 1}.</td>
	// 										<td class="col-xs-11">${e.kode}</td>
	// 								</tr>
	// 								`;
	//                     })

	//                     document.getElementById('konten-table').innerHTML = data;
	//                     // console.log(data);
	//                     document.getElementById('table-fixed').style.display = 'block';
	//                 }
	//             });
	//     } else {
	//         document.getElementById('table-fixed').style.display = 'none';
	//     }
	// });

	function getNoSuratJalanEks(data) {
		$("#kode_barcode").val(data);
		document.getElementById('table-fixed').style.display = 'none'
		$("#check_kode").click()
	}

	// $('#kode_barcode2').on('keyup', function() {
	//     console.log('aa');
	function getKodePallet(e, index) {
		if ($(e).val() != "") {
			$.ajax({
				type: "GET",
				url: "<?= base_url('WMS/PembongkaranBarang/getKodeAutoComplete') ?>",
				data: {
					params: $(e).val(),
					depo_detail_id_tujuan: $("#pembongkaran-depo_detail_id_tujuan").val(),
				},
				dataType: "JSON",
				success: function(results) {
					if (results.length > 0) {
						let data = "";
						$.each(results, function(i, v) {
							data += `<tr onclick="getNoSuratJalanEks2('${v.depo_kode_preffix}', '${v.kode}', '${v.pallet_id}', '${index}')" style="cursor:pointer">
										<td class="col-xs-1">${i + 1}.</td>
										<td class="col-xs-11">${v.kode}</td>
								</tr>`;
						});

						$('#konten-table2').html(data);
						$('#table-fixed2').css('display', 'block');
					} else {
						$('#table-fixed2').css('display', 'none');
					}
				}
			});
		} else {
			$('#table-fixed2').css('display', 'none');
		}
	}
	// });

	function getNoSuratJalanEks2(depo_kode_preffix, data, pallet_id, index) {
		message("Success!", "Kode Pallet " + depo_kode_preffix + "/" + data + " Berhasil Dipilih", "success");

		$("#pallet_kode_tujuan_" + index + "").attr("data-new", "true");

		$("#kode_barcode2").val('');
		$("#pallet_kode_tujuan_" + index + "").html(depo_kode_preffix + '/' + data);
		$("#pallet_kode_tujuan_" + index + "").show()
		$("#preview_input_manual_" + index).hide();
		$("#close_input_" + index).hide();
		$("#input_manual_" + index).show();
		// $("#item-" + index + "-konversiskudetail2-pallet_id_tujuan_baru").val(pallet_id);
		$("#item-" + index + "-konversiskudetail2-pallet_id_tujuan").val(pallet_id);
		document.getElementById('table-fixed2').style.display = 'none'
		// $("#span_scan_by_one_" + index + "").hide();
		// $("#check_kode2").click()
	}
</script>