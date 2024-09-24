<script type="text/javascript">
	var jumlah_sku = 0;
	var sku_qty_sisa_konversi = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_sku_exp = [];
	let arr_konversi_detail = [];
	let arr_konversi_detail2 = [];
	let arr_konversi_detail3 = [];
	let arr_pallet_id = [];
	var arr_list_hasil_sku_konversi_pengemasan = [];
	var idx_hasil_sku_konversi_pengemasan = 0;
	var arr_list_sku_konversi_group = [];
	var list_selisih = 0;
	var list_sisa = 0;
	var idx_pallet_asal = 0;
	var arr_konversi_sku_pallet_asal = [];

	const html5QrCode = new Html5Qrcode("preview");
	let timerInterval
	loadingBeforeReadyPage()
	$(document).ready(function() {
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
		// console.log(result);

		// if (result != 1) {
		// 	$("#btn_konfirmasi_konversi").prop("disabled", true);
		// }

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
			url: "<?= base_url('WMS/KonversiPengemasanBarang/delete_tr_konversi_sku_detail2_temp'); ?>",
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
			foreach ($HasilKonversiPengemasan as $i => $detail) :
				if ($detail['tr_konversi_sku_detail_id'] != "") {
			?>
					arr_list_hasil_sku_konversi_pengemasan.push({
						'idx': "<?= $i + 1 ?>",
						'tr_konversi_sku_detail_id': "<?= $detail['tr_konversi_sku_detail_id'] ?>",
						'sku_id_awal': "<?= $detail['sku_id_awal'] ?>",
						'sku_id': "<?= $detail['sku_id'] ?>",
						'sku_kode': "<?= $detail['sku_kode'] ?>",
						'sku_nama_produk': "<?= $detail['sku_nama_produk'] ?>",
						'sku_konversi_group': "<?= $detail['sku_konversi_group'] ?>",
						'sku_satuan': "<?= $detail['sku_satuan'] ?>",
						'sku_kemasan': "<?= $detail['sku_kemasan'] ?>",
						'sku_stock_expired_date': "<?= $detail['sku_stock_expired_date'] ?>",
						'sku_qty': <?= $detail['sku_qty'] ?>,
						'sku_qty_konversi_plan': <?= $detail['sku_qty_konversi_plan'] ?>,
						'sku_qty_hasil_konversi': <?= $detail['sku_qty_hasil_konversi'] ?>,
						'sku_qty_sisa_konversi': <?= $detail['sku_qty_sisa_konversi'] ?>
					});
			<?php }
			endforeach; ?>

			<?php foreach ($PalletAsal as $key => $value) : ?>
				arr_konversi_sku_pallet_asal.push({
					'idx': <?= $key + 1 ?>,
					'tr_konversi_sku_detail_id': "<?= $value['tr_konversi_sku_detail_id'] ?>",
					'tr_konversi_sku_id': "<?= $value['tr_konversi_sku_id'] ?>",
					'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
					'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
					'sku_stock_qty_pallet': <?= $value['sku_stock_qty_pallet'] ?>,
					'sku_stock_qty': <?= $value['sku_stock_qty'] ?>
				});
			<?php endforeach; ?>
		<?php } else if ($act == "detail") { ?>
			<?php foreach ($PalletAsal as $key => $value) : ?>
				arr_konversi_sku_pallet_asal.push({
					'idx': <?= $key + 1 ?>,
					'tr_konversi_sku_detail_id': "<?= $value['tr_konversi_sku_detail_id'] ?>",
					'tr_konversi_sku_id': "<?= $value['tr_konversi_sku_id'] ?>",
					'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
					'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
					'sku_stock_qty_pallet': <?= $value['sku_stock_qty_pallet'] ?>,
					'sku_stock_qty': <?= $value['sku_stock_qty'] ?>
				});
			<?php endforeach; ?>
		<?php } ?>

		setTimeout(() => {

			if (arr_list_hasil_sku_konversi_pengemasan.length == 0) {
				$("#btn_konfirmasi_konversi").prop("disabled", true);
			}

			GetListHasilKonversiPengemasan();
			Get_list_sku_konversi_group_by_tr_konversi_sku_id();

		}, 1000);
	});

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

	function GetKonversiDetailWithPalletById() {

		$.ajax({
			url: "<?= base_url('WMS/KonversiPengemasanBarang/GetKonversiDetailWithPalletById'); ?>",
			type: "POST",
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			data: {
				tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
				arr_konversi_sku_pallet_asal: arr_konversi_sku_pallet_asal
			},
			dataType: "JSON",
			success: function(response) {

				$("#table-sku-pengemasan > tbody").html('');
				$("#table-sku-pengemasan > tbody").empty('');

				if (response.length > 0) {

					$.each(response, function(i, v) {
						$("#table-sku-pengemasan > tbody").append(`
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
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_qty_pallet_ambil}</td>
								<td style="text-align: center; vertical-align: middle;">
									<button class="btn btn-primary btn-sm" id="button_modal_pallet_asal" onclick="Get_pallet_by_sku_stock_id('${v.tr_konversi_sku_detail_id}','${v.sku_stock_id}','${v.sku_id}','${v.sku_kode}','${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}','${v.tr_konversi_sku_detail_qty_plan}')"><i class="fa fa-search"></i></button>
								</td>
							</tr>		
						`);
					});

				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

	}

	$("#pembongkaran-tr_konversi_sku_id").on("change", function() {

		if ($("#pembongkaran-tr_konversi_sku_id").val() != "") {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/KonversiPengemasanBarang/GetKonversiById') ?>",
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
				requestAjax("<?= base_url('WMS/KonversiPengemasanBarang/insert_tr_konversi_sku'); ?>", {
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
								"<?= base_url(); ?>WMS/KonversiPengemasanBarang/PembongkaranMenu";
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
		// 			url: "<?= base_url('WMS/KonversiPengemasanBarang/insert_tr_konversi_sku'); ?>",
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
		// 						location.href = "<?= base_url(); ?>WMS/KonversiPengemasanBarang/PembongkaranMenu";
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
		var cek_error = 0;

		console.log(arr_list_hasil_sku_konversi_pengemasan);

		if (arr_list_hasil_sku_konversi_pengemasan.length <= 0) {
			let alert_tes = "Tidak Ada SKU Yang Dikemas";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		$.each(arr_list_hasil_sku_konversi_pengemasan, function(i, v) {
			if (v.sku_qty_konversi_plan <= 0) {
				let alert_tes = "Qty Plan Pengemasan Tidak Boleh Minus Atau 0";
				message_custom("Error", "error", alert_tes);

				cek_error++;
				return false;
			}

			if (v.sku_qty_hasil_konversi <= 0) {
				let alert_tes = "Qty Hasil Konversi Pengemasan Tidak Boleh Minus Atau 0";
				message_custom("Error", "error", alert_tes);

				cek_error++;
				return false;
			}
		});

		if (list_selisih > 0) {
			let alert_tes = "Qty Sisa Tidak Mencukupi Untuk Pengemasan";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		if (list_sisa > 0) {
			let alert_tes = "Tidak Boleh Ada Qty Sisa";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		setTimeout(() => {
			if (cek_error == 0) {

				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
					.then((result) => {
						if (result.value == true) {
							requestAjax("<?= base_url('WMS/KonversiPengemasanBarang/update_tr_konversi_sku'); ?>", {
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
								konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
								tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update").val(),
								pallet_id_tujuan: $("#pallet_id_tujuan").val(),
								detail: arr_list_hasil_sku_konversi_pengemasan,
								list_pallet_asal: arr_konversi_sku_pallet_asal
							}, "POST", "JSON", function(response) {
								if (response == 1) {
									$("#loadingview").hide();

									let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									message_custom("Success", "success", alert_tes);

									sessionStorage.setItem('konfirmasiEnabled', true);
									// $("#btn_konfirmasi_konversi").prop("disabled", false);
									setTimeout(() => {
										// location.href =
										//     "<?= base_url(); ?>WMS/KonversiPengemasanBarang/PembongkaranMenu";
										location.reload();
									}, 1000);
								} else if (response == 3) {
									messageNotSameLastUpdated();
									return false;
								} else {
									$("#loadingview").hide();

									let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									message_custom("Success", "success", alert_tes);
								}
							}, "#btn_update_konversi")
						}
					});
			}

		}, 1000);

	});

	$("#btn_reject_konversi").on("click", function() {
		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
			.then((result) => {
				if (result.value == true) {
					requestAjax("<?= base_url('WMS/KonversiPengemasanBarang/reject_tr_konversi_sku'); ?>", {
						tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
						client_wms_id: $("#pembongkaran-client_wms_id").val(),
						depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
						tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
						tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
						tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
						tr_konversi_sku_status: "Canceled",
						tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
						tr_konversi_sku_tgl_create: "",
						tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
						konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
						tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update").val(),
						pallet_id_tujuan: $("#pallet_id_tujuan").val(),
						detail: arr_list_hasil_sku_konversi_pengemasan,
						list_pallet_asal: arr_konversi_sku_pallet_asal
					}, "POST", "JSON", function(response) {
						if (response == 1) {
							$("#loadingview").hide();

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
							message_custom("Success", "success", alert_tes);

							sessionStorage.setItem('konfirmasiEnabled', true);
							// $("#btn_konfirmasi_konversi").prop("disabled", false);
							setTimeout(() => {
								location.href = "<?= base_url(); ?>WMS/KonversiPengemasanBarang/detail/?id=" + $("#pembongkaran-tr_konversi_sku_id").val();
							}, 1000);
						} else if (response == 3) {
							messageNotSameLastUpdated();
							return false;
						} else {
							$("#loadingview").hide();

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
							message_custom("Success", "success", alert_tes);
						}
					}, "#btn_reject_konversi")
				}
			});

	});

	$("#btn_konfirmasi_konversi").on("click", function() {
		var idx = 0;
		var cek_error = 0;

		if (arr_list_hasil_sku_konversi_pengemasan.length <= 0) {
			let alert_tes = "Tidak Ada SKU Yang Dikemas";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		$.each(arr_list_hasil_sku_konversi_pengemasan, function(i, v) {
			if (v.sku_qty_konversi_plan <= 0) {
				let alert_tes = "Qty Plan Pengemasan Tidak Boleh Minus Atau 0";
				message_custom("Error", "error", alert_tes);

				cek_error++;
				return false;
			}
		});

		if (list_selisih > 0) {
			let alert_tes = "Qty Sisa Tidak Mencukupi Untuk Pengemasan";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		if (list_sisa > 0) {
			let alert_tes = "Tidak Boleh Ada Qty Sisa";
			message_custom("Error", "error", alert_tes);

			return false;
		}

		setTimeout(() => {
			if (cek_error == 0) {

				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
					.then((result) => {
						if (result.value == true) {
							requestAjax("<?= base_url('WMS/KonversiPengemasanBarang/konfirmasi_tr_konversi_sku'); ?>", {
								tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
								client_wms_id: $("#pembongkaran-client_wms_id").val(),
								depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
								tipe_konversi_id: $("#pembongkaran-tipe_konversi_id").val(),
								tr_konversi_sku_kode: $("#pembongkaran-tr_konversi_sku_id").text(),
								tr_konversi_sku_tanggal: $("#pembongkaran-tr_konversi_sku_tanggal").val(),
								tr_konversi_sku_status: "Completed",
								tr_konversi_sku_keterangan: $("#pembongkaran-tr_konversi_sku_keterangan").val(),
								tr_konversi_sku_tgl_create: "",
								tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
								konversi_is_need_approval: $("#pembongkaran-konversi_is_need_approval:checked").val(),
								tr_konversi_sku_tgl_update: $("#pembongkaran-tr_konversi_sku_tgl_update").val(),
								pallet_id_tujuan: $("#pallet_id_tujuan").val(),
								detail: arr_list_hasil_sku_konversi_pengemasan,
								list_pallet_asal: arr_konversi_sku_pallet_asal
							}, "POST", "JSON", function(response) {
								if (response == 1) {
									$("#loadingview").hide();

									let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									message_custom("Success", "success", alert_tes);

									sessionStorage.setItem('konfirmasiEnabled', true);
									// $("#btn_konfirmasi_konversi").prop("disabled", false);
									setTimeout(() => {
										location.href = "<?= base_url(); ?>WMS/KonversiPengemasanBarang/detail/?id=" + $("#pembongkaran-tr_konversi_sku_id").val();
									}, 1000);
								} else if (response == 3) {
									messageNotSameLastUpdated();
									return false;
								} else {
									$("#loadingview").hide();

									let alert_tes = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									message_custom("Success", "success", alert_tes);
								}
							}, "#btn_update_konversi")
						}
					});
			}

		}, 1000);
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
			url: "<?= base_url('WMS/KonversiPengemasanBarang/GetKonversiDetailPallet') ?>",
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
								<td style="text-align: center; vertical-align: middle;"><button type="button" class="btn btn-primary btn-sm" id="btn_form_konversi_${i}" style="${v.tr_konversi_sku_detail2_qty > 0 ? '' : 'display:none;' }" onclick="GetFormKonversiSKUDetail2('${tr_konversi_sku_detail_id}','${i}','${satuan}')"><span name="CAPTION-KONVERSI">Konversi</span></button></td>
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

	function GetFormKonversiSKUDetail2(tr_konversi_sku_detail_id, index, satuan) {
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
			url: "<?= base_url('WMS/KonversiPengemasanBarang/Getkonversiskudetail2') ?>",
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
										<input type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${sku_sisa}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" disabled />
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
										<input type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${sku_sisa}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}','${v.tr_konversi_sku_detail2_qty}')" disabled />
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
		var total_qty = 0;
		var max_qty = 0;
		var qty_sisa = parseInt($("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val());
		var pallet_id = $("#filter_pallet_id").val();

		$("#table-konversi-by-sku > tbody tr").each(function() {
			var checkbox = $(this).find('[id^="check_konversi_detail3_"]');
			var isChecked = checkbox.is(":checked");
			var checked = $('[id="check_konversi_detail3_' + idx + '"]:checked').length;

			if (isChecked) {
				var qtyInput = $("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty");
				var qtyValue = parseInt(qtyInput.val());
				total_qty += qtyValue;
			}

			if (checked > 0) {
				if ($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val() == 0) {
					check_qty_detail3++;
				}
				check_checked++;
			}

			idx++;
		});

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
						url: "<?= base_url('WMS/KonversiPengemasanBarang/insert_tr_konversi_sku_detail2_temp'); ?>",
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
				url: "<?= base_url('WMS/KonversiPengemasanBarang/check_kode_pallet'); ?>",
				type: "POST",
				data: {
					depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
					kode_pallet: decodedText
				},
				dataType: "JSON",
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					$("#pallet_id_tujuan").val(data.pallet_id);

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
	});

	$(document).on('click', '#search_kode_pallet', function() {
		if ($("#kode_barcode").val() != "") {
			fetch('<?= base_url(); ?>WMS/KonversiPengemasanBarang/getKodeAutoComplete?params=' + $("#kode_barcode").val() + '&depo_detail_id_tujuan=' + $("#pembongkaran-depo_detail_id").val())
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
	//modal konversi
	$(document).on("click", "#check_kode", () => {
		let barcode = $("#kode_barcode").val();
		var idx = 0;

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			$.ajax({
				url: "<?= base_url('WMS/KonversiPengemasanBarang/check_kode_pallet'); ?>",
				type: "POST",
				data: {
					depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
					kode_pallet: barcode
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
						$("#kode_barcode").val(data.kode);
						$("#pallet_id_tujuan").val(data.pallet_id);
						message("Success!", data.message, "success");

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

	// $(document).on("click", ".input_pallet", function() {
	//     $("#kode_pallet_by_one").attr("data-id", $(this).attr('data-id'));
	//     $("#manual_input_pallet").modal('show');
	// });

	$(document).on("change", "#kode_pallet_by_one", function() {
		let pallet_kode = $("#kode_pallet_by_one").val();
		const idx = $(this).attr('data-id');

		$.ajax({
			url: "<?= base_url('WMS/KonversiPengemasanBarang/check_kode_pallet'); ?>",
			type: "POST",
			data: {
				depo_detail_id: $("#pembongkaran-depo_detail_id").val(),
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
				url: "<?= base_url('WMS/KonversiPengemasanBarang/getKodeAutoComplete') ?>",
				data: {
					params: $(e).val(),
					depo_detail_id_tujuan: $("#pembongkaran-depo_detail_id").val(),
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

	$('#btn_tambah_konversi_pengemasan').click(function(event) {

		idx_hasil_sku_konversi_pengemasan = arr_list_hasil_sku_konversi_pengemasan.length + 1;

		arr_list_hasil_sku_konversi_pengemasan.push({
			'idx': idx_hasil_sku_konversi_pengemasan,
			'tr_konversi_sku_detail_id': '',
			'sku_id_awal': '',
			'sku_id': '',
			'sku_kode': '',
			'sku_nama_produk': '',
			'sku_konversi_group': '',
			'sku_satuan': '',
			'sku_kemasan': '',
			'sku_stock_expired_date': '',
			'sku_qty': 0,
			'sku_qty_konversi_plan': 0,
			'sku_qty_hasil_konversi': 0,
			'sku_qty_sisa_konversi': 0
		});

		GetListHasilKonversiPengemasan();
	});

	function GetListHasilKonversiPengemasan() {

		$("#table-sku-pengemasan-konversi > tbody").html('');
		$("#table-sku-pengemasan-konversi > tbody").empty('');

		if (arr_list_hasil_sku_konversi_pengemasan.length > 0) {

			$.each(arr_list_hasil_sku_konversi_pengemasan, function(i, v) {

				$("#table-sku-pengemasan-konversi > tbody").append(`
					<tr>
						<td style="text-align: center; vertical-align: middle;">
							${i+1}
							<input type="hidden" id="item-${i}-konversiskudetail2-idx" class="form-control" value="${v.idx}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_id" class="form-control" value="${v.sku_id}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_id_awal" class="form-control" value="${v.sku_id_awal}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_kemasan" class="form-control" value="${v.sku_kemasan}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_qty" class="form-control" value="${v.sku_qty}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_qty_sisa_konversi" class="form-control" value="${v.sku_qty_sisa_konversi}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_kode_input" class="form-control text-right" value="${v.sku_kode}">
							<input type="hidden" id="item-${i}-konversiskudetail2-sku_nama_produk_input" class="form-control text-right" value="${v.sku_nama_produk}">
						</td>
						<td style="text-align: left; vertical-align: middle;width:15%;">
							<select id="item-${i}-konversiskudetail2-sku_konversi_group" class="form-control select2" onchange="UpdateListHasilKonversiPengemasan('sku_konversi_group','${i}')">
							</select>
						</td>
						<td style="text-align: left; vertical-align: middle;width:15%;">
							<select id="item-${i}-konversiskudetail2-sku_satuan" class="form-control select2" onchange="UpdateListHasilKonversiPengemasan('sku_satuan','${i}')">
							</select>
						</td>
						<td style="text-align: left; vertical-align: middle;">
							<span id="item-${i}-konversiskudetail2-sku_kode">${v.sku_kode}</span>
						</td>
						<td style="text-align: left; vertical-align: middle;width:15%;">
							<span id="item-${i}-konversiskudetail2-sku_nama_produk">${v.sku_nama_produk}</span>
						</td>
						<td style="text-align: left; vertical-align: middle;">
							<input type="date" id="item-${i}-konversiskudetail2-sku_stock_expired_date" class="form-control" value="${v.sku_stock_expired_date}" onchange="UpdateListHasilKonversiPengemasan('sku_stock_expired_date','${i}')">
						</td>
						<td style="text-align: left; vertical-align: middle;width:10%;">
							<input type="number" id="item-${i}-konversiskudetail2-sku_qty_konversi_plan" class="form-control text-right" value="${v.sku_qty_konversi_plan}" onchange="UpdateListHasilKonversiPengemasan('sku_qty_konversi_plan','${i}')">
						</td>
						<td style="text-align: right; vertical-align: middle;width:10%;">
							<input type="number" id="item-${i}-konversiskudetail2-sku_qty_hasil_konversi" class="form-control text-right" value="${v.sku_qty_hasil_konversi}" disabled>
						</td>
						<td style="text-align: center; vertical-align: middle;">
							<button type="button" class="btn btn-danger btn-sm" onClick="DeleteHasilKonversiPengemasan('${i}')"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);

			});

			$.each(arr_list_hasil_sku_konversi_pengemasan, function(i, v) {

				$("#item-" + i + "-konversiskudetail2-sku_konversi_group").html('');
				$("#item-" + i + "-konversiskudetail2-sku_satuan").html('');

				$.ajax({
					url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_sku_konversi_group_by_tr_konversi_sku_id'); ?>",
					type: "POST",
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn_save_mutasi").prop("disabled", true);
					},
					data: {
						tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val()
					},
					dataType: "JSON",
					success: function(response) {
						if (response.length > 0) {
							$("#item-" + i + "-konversiskudetail2-sku_konversi_group").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);
							$.each(response, function(i2, v2) {
								$("#item-" + i + "-konversiskudetail2-sku_konversi_group").append(`<option value="${v2.sku_konversi_group + " || " + v2.sku_id}" ${v2.sku_konversi_group == v.sku_konversi_group ? 'selected':''}>${v2.sku_konversi_group}</option>`);
							});
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");
					},
					complete: function() {
						Swal.close();
					}
				});

				$.ajax({
					url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_sku_satuan_by_tr_konversi_sku_id'); ?>",
					type: "POST",
					beforeSend: function() {
						// Swal.fire({
						// 	title: 'Loading ...',
						// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						// 	timerProgressBar: false,
						// 	showConfirmButton: false
						// });

						// $("#btn_save_mutasi").prop("disabled", true);
					},
					data: {
						tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
						sku_konversi_group: v.sku_konversi_group,
						sku_id: v.sku_id
					},
					dataType: "JSON",
					success: function(response) {
						if (response.length > 0) {
							$("#item-" + i + "-konversiskudetail2-sku_satuan").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);
							$.each(response, function(i2, v2) {
								$("#item-" + i + "-konversiskudetail2-sku_satuan").append(`<option value="${v2.sku_satuan}" ${v2.sku_satuan == v.sku_satuan ? 'selected' : '' }>${v2.sku_satuan}</option>`);
							});
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						// message("Error", "Error 500 Internal Server Connection Failure", "error");
						console.log("Error 500 Internal Server Connection Failure");
					},
					complete: function() {
						Swal.close();
					}
				});

			});
		}

	}

	function Get_list_sku_konversi_group_by_tr_konversi_sku_id() {

		list_selisih = 0;
		list_sisa = 0;

		$.ajax({
			url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_list_sku_konversi_group_by_tr_konversi_sku_id'); ?>",
			type: "POST",
			beforeSend: function() {
				// Swal.fire({
				// 	title: 'Loading ...',
				// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				// 	timerProgressBar: false,
				// 	showConfirmButton: false
				// });
			},
			data: {
				tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
				arr_list_hasil_sku_konversi_pengemasan: arr_list_hasil_sku_konversi_pengemasan,
				arr_konversi_sku_pallet_asal: arr_konversi_sku_pallet_asal
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-sku-konversi-group > tbody").html('');
				$("#table-sku-konversi-group > tbody").empty('');

				if (response.length > 0) {

					$.each(response, function(i, v) {

						if (parseInt(v.sku_qty_sisa_konversi) > 0) {
							list_sisa++;
						}

						if (v.is_selisih == "1") {

							$("#table-sku-konversi-group > tbody").append(`
								<tr class="bg-danger">
									<td style="text-align: center; vertical-align: middle;">${i+1}</td>
									<td style="text-align: left; vertical-align: middle;width:30%;">${v.sku_konversi_group}</td>
									<td style="text-align: right; vertical-align: middle;width:30%;">${v.tr_konversi_sku_detail_qty_plan}</td>
									<td style="text-align: right; vertical-align: middle;width:30%;">${v.sku_qty_sisa_konversi}</td>
								</tr>
							`);

							list_selisih++;

						} else {
							$("#table-sku-konversi-group > tbody").append(`
								<tr>
									<td style="text-align: center; vertical-align: middle;">${i+1}</td>
									<td style="text-align: left; vertical-align: middle;width:30%;">${v.sku_konversi_group}</td>
									<td style="text-align: right; vertical-align: middle;width:30%;">${v.tr_konversi_sku_detail_qty_plan}</td>
									<td style="text-align: right; vertical-align: middle;width:30%;">${v.sku_qty_sisa_konversi}</td>
								</tr>
							`);
						}
					})
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				// Swal.close();
			}
		});

	}

	function UpdateListHasilKonversiPengemasan(tipe, index) {

		// console.log(no_urut);

		const idx = $("#item-" + index + "-konversiskudetail2-idx").val();
		const sku_konversi_group = $("#item-" + index + "-konversiskudetail2-sku_konversi_group").val().split(" || ");
		const sku_satuan = $("#item-" + index + "-konversiskudetail2-sku_satuan").val();

		const findIndexData = arr_list_hasil_sku_konversi_pengemasan.findIndex((value) => value.idx == idx);
		// console.log(findIndexData);

		if (tipe == "sku_konversi_group") {
			$("#item-" + index + "-konversiskudetail2-sku_kode").html('');
			$("#item-" + index + "-konversiskudetail2-sku_nama_produk").html('');
			$("#item-" + index + "-konversiskudetail2-sku_satuan").html('');

			$.ajax({
				url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_sku_satuan_by_tr_konversi_sku_id'); ?>",
				type: "POST",
				beforeSend: function() {
					// Swal.fire({
					// 	title: 'Loading ...',
					// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					// 	timerProgressBar: false,
					// 	showConfirmButton: false
					// });
				},
				data: {
					tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
					sku_konversi_group: sku_konversi_group[0],
					sku_id: sku_konversi_group[1]
				},
				dataType: "JSON",
				success: function(response) {
					if (response.length > 0) {
						$("#item-" + index + "-konversiskudetail2-sku_satuan").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);
						$.each(response, function(i2, v2) {
							$("#item-" + index + "-konversiskudetail2-sku_satuan").append(`<option value="${v2.sku_satuan}" ${v2.sku_satuan == sku_satuan ? 'selected' : '' }>${v2.sku_satuan}</option>`);
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					// message("Error", "Error 500 Internal Server Connection Failure", "error");
					console.log("Error 500 Internal Server Connection Failure");
				},
				complete: function() {
					// Swal.close();
				}
			});

		} else if (tipe == "sku_satuan") {
			$("#item-" + index + "-konversiskudetail2-sku_kode").html('');
			$("#item-" + index + "-konversiskudetail2-sku_nama_produk").html('');

			$.ajax({
				url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_sku_by_tr_konversi_sku_id'); ?>",
				type: "POST",
				beforeSend: function() {
					// Swal.fire({
					// 	title: 'Loading ...',
					// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					// 	timerProgressBar: false,
					// 	showConfirmButton: false
					// });
				},
				data: {
					tr_konversi_sku_id: $("#pembongkaran-tr_konversi_sku_id").val(),
					sku_konversi_group: sku_konversi_group[0],
					sku_satuan: sku_satuan
				},
				dataType: "JSON",
				success: function(response) {
					if (response.length > 0) {
						$.each(response, function(i, v) {
							$("#item-" + index + "-konversiskudetail2-sku_id_awal").val(sku_konversi_group[1]);
							$("#item-" + index + "-konversiskudetail2-sku_id").val(v.sku_id);
							$("#item-" + index + "-konversiskudetail2-sku_kode_input").val(v.sku_kode);
							$("#item-" + index + "-konversiskudetail2-sku_kode").append(v.sku_kode);
							$("#item-" + index + "-konversiskudetail2-sku_nama_produk_input").val(v.sku_nama_produk);
							$("#item-" + index + "-konversiskudetail2-sku_nama_produk").append(v.sku_nama_produk);
							$("#item-" + index + "-konversiskudetail2-sku_kemasan").val(v.sku_kemasan);
							$("#item-" + index + "-konversiskudetail2-sku_qty").val(0);
							$("#item-" + index + "-konversiskudetail2-sku_qty_konversi_plan").val(0);
							$("#item-" + index + "-konversiskudetail2-sku_qty_hasil_konversi").val(0);
							$("#item-" + index + "-konversiskudetail2-sku_qty_sisa_konversi").val(0);
						});

					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
				},
				complete: function() {
					Swal.close();
				}
			});

		} else if (tipe == "sku_qty_konversi_plan") {
			const sku_composite = sku_satuan.replace(/\D/g, '');

			$.ajax({
				url: "<?= base_url('WMS/KonversiPengemasanBarang/Exec_proses_konversi_sku_packing'); ?>",
				type: "POST",
				beforeSend: function() {
					// Swal.fire({
					// 	title: 'Loading ...',
					// 	html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					// 	timerProgressBar: false,
					// 	showConfirmButton: false
					// });
				},
				data: {
					sku_id: sku_konversi_group[1],
					sku_satuan: sku_satuan,
					sku_qty: $("#item-" + index + "-konversiskudetail2-sku_qty_konversi_plan").val()
				},
				dataType: "JSON",
				success: function(response) {
					if (response.length > 0) {
						$.each(response, function(i, v) {
							$("#item-" + index + "-konversiskudetail2-sku_qty").val(v.qty);
							$("#item-" + index + "-konversiskudetail2-sku_qty_hasil_konversi").val(v.hasil);
							$("#item-" + index + "-konversiskudetail2-sku_qty_sisa_konversi").val(v.sisa);
						});
					} else {
						$("#item-" + index + "-konversiskudetail2-sku_qty").val(0);
						$("#item-" + index + "-konversiskudetail2-sku_qty_hasil_konversi").val(0);
						$("#item-" + index + "-konversiskudetail2-sku_qty_sisa_konversi").val(0);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
				},
				complete: function() {
					Swal.close();
				}
			});

		}
		setTimeout(() => {

			if (findIndexData > -1) {
				//

				isNaN(parseInt($("#item-" + index + "-konversiskudetail2-sku_qty").val())) ? 0 : parseInt($("#item-" + index + "-konversiskudetail2-sku_qty").val())

				arr_list_hasil_sku_konversi_pengemasan[findIndexData] = ({
					'idx': idx,
					'tr_konversi_sku_detail_id': "",
					'sku_id_awal': sku_konversi_group[1],
					'sku_id': $("#item-" + index + "-konversiskudetail2-sku_id").val(),
					'sku_kode': $("#item-" + index + "-konversiskudetail2-sku_kode_input").val(),
					'sku_nama_produk': $("#item-" + index + "-konversiskudetail2-sku_nama_produk_input").val(),
					'sku_konversi_group': sku_konversi_group[0],
					'sku_satuan': $("#item-" + index + "-konversiskudetail2-sku_satuan").val(),
					'sku_kemasan': $("#item-" + index + "-konversiskudetail2-sku_kemasan").val(),
					'sku_stock_expired_date': $("#item-" + index + "-konversiskudetail2-sku_stock_expired_date").val(),
					'sku_qty': isNaN(parseInt($("#item-" + index + "-konversiskudetail2-sku_qty").val())) ? 0 : parseInt($("#item-" + index + "-konversiskudetail2-sku_qty").val()),
					'sku_qty_konversi_plan': isNaN(parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_konversi_plan").val())) ? 0 : parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_konversi_plan").val()),
					'sku_qty_hasil_konversi': isNaN(parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_hasil_konversi").val())) ? 0 : parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_hasil_konversi").val()),
					'sku_qty_sisa_konversi': isNaN(parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_sisa_konversi").val())) ? 0 : parseInt($("#item-" + index + "-konversiskudetail2-sku_qty_sisa_konversi").val()),
				});
			}

			Get_list_sku_konversi_group_by_tr_konversi_sku_id();

		}, 1000);

		// console.log(arr_list_hasil_sku_konversi_pengemasan);

		// console.log(arr_list_hasil_sku_konversi_pengemasan);
	}

	function DeleteHasilKonversiPengemasan(index) {

		const idx = $("#item-" + index + "-konversiskudetail2-idx").val();

		const findIndexData = arr_list_hasil_sku_konversi_pengemasan.findIndex((value) => value.idx == idx);

		if (findIndexData > -1) { // only splice array when item is found
			arr_list_hasil_sku_konversi_pengemasan.splice(findIndexData, 1); // 2nd parameter means remove one item only
		}

		GetListHasilKonversiPengemasan();
		Get_list_sku_konversi_group_by_tr_konversi_sku_id();

	}

	function Get_pallet_by_sku_stock_id(tr_konversi_sku_detail_id, sku_stock_id, sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan, tr_konversi_sku_detail_qty_plan) {

		$("#modal-pallet-asal").modal('show');

		$("#filter_pallet_asal_tr_konversi_sku_detail_id").val(tr_konversi_sku_detail_id);
		$("#filter_pallet_asal_sku_stock_id").val(sku_stock_id);
		$("#filter_pallet_asal_sku_id").val(sku_id);
		$("#filter_pallet_asal_sku_kode").val(sku_kode);
		$("#filter_pallet_asal_sku").val(sku_nama_produk);
		$("#filter_pallet_asal_sku_kemasan").val(sku_kemasan);
		$("#filter_pallet_asal_sku_satuan").val(sku_satuan);
		$("#filter_pallet_asal_qty_plan").val(tr_konversi_sku_detail_qty_plan);

		GetListPalletAsal();
	}

	$("#btn_tambah_pallet_asal").on("click", function() {

		idx_pallet_asal = arr_konversi_sku_pallet_asal.length + 1;

		arr_konversi_sku_pallet_asal.push({
			'idx': idx_pallet_asal,
			'tr_konversi_sku_detail_id': $("#filter_pallet_asal_tr_konversi_sku_detail_id").val(),
			'tr_konversi_sku_id': $("#pembongkaran-tr_konversi_sku_id").val(),
			'pallet_id_asal': '',
			'sku_stock_id': $("#filter_pallet_asal_sku_stock_id").val(),
			'sku_stock_qty_pallet': 0,
			'sku_stock_qty': 0
		});

		GetListPalletAsal();
	});

	function GetListPalletAsal() {

		$.ajax({
			url: "<?= base_url('WMS/KonversiPengemasanBarang/GetListPalletAsal'); ?>",
			type: "POST",
			async: false,
			data: {
				sku_stock_id: $("#filter_pallet_asal_sku_stock_id").val(),
				arr_konversi_sku_pallet_asal: arr_konversi_sku_pallet_asal
			},
			dataType: "JSON",
			success: function(response) {

				$("#table-pallet-asal > tbody").html('');
				$("#table-pallet-asal > tbody").empty('');

				if (response.length > 0) {
					$.each(response, function(i, v) {
						$("#table-pallet-asal > tbody").append(`
							<tr id="row-${i}">
								<td style="text-align: center; vertical-align: middle;">
									${i+1}
									<input type="hidden" id="item-${i}-konversi_sku_pallet_asal-idx" value="${v.idx}">
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<select class="form-control" id="item-${i}-konversi_sku_pallet_asal-pallet_id_asal" style="width:100%;" onchange="UpdateListPallet('${i}','${v.idx}')">
									</select>							
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<input type="text" class="form-control" id="item-${i}-konversi_sku_pallet_asal-sku_stock_qty_pallet" style="width:100%;" value="${v.sku_stock_qty_pallet}" disabled>							
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<input type="text" class="form-control" id="item-${i}-konversi_sku_pallet_asal-sku_stock_qty" style="width:100%;" value="${v.sku_stock_qty}" onchange="UpdateListPallet('${i}','${v.idx}')">								
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<button class="btn btn-danger btn-small" onclick="DeleteListPallet('${v.idx}')"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						`);
					});

					$.each(response, function(i, v) {
						$.ajax({
							url: "<?= base_url('WMS/KonversiPengemasanBarang/Get_pallet_by_sku_stock_id'); ?>",
							type: "POST",
							async: false,
							data: {
								sku_stock_id: $("#filter_pallet_asal_sku_stock_id").val()
							},
							dataType: "JSON",
							success: function(response2) {
								// console.log(i);
								$("#item-" + i + "-konversi_sku_pallet_asal-pallet_id_asal").html('');
								$("#item-" + i + "-konversi_sku_pallet_asal-pallet_id_asal").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);

								if (response2.length > 0) {

									$.each(response2, function(i2, v2) {
										$("#item-" + i + "-konversi_sku_pallet_asal-pallet_id_asal").append(`<option value="${v2.pallet_id}" data-${i}-pallet_asal-sku_stock_id="${v2.sku_stock_id}" data-${i}-pallet_asal-sku_stock_expired_date="${v2.sku_stock_expired_date}" data-${i}-pallet_asal-sku_stock_qty="${v2.sku_stock_qty}" ${v.pallet_id_asal == v2.pallet_id ? 'selected' : '' }>${v2.pallet_kode}</option>`);
									});
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");
							}
						});
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			}
		});

	}

	function UpdateListPallet(index, idx) {

		const findIndexData = arr_konversi_sku_pallet_asal.findIndex((value) => value.idx == idx);
		const sku_stock_qty_pallet = parseInt($('#item-' + index + '-konversi_sku_pallet_asal-pallet_id_asal option:selected').attr('data-' + index + '-pallet_asal-sku_stock_qty'));

		if (findIndexData > -1) {

			$("#item-" + index + "-konversi_sku_pallet_asal-sku_stock_qty_pallet").val(sku_stock_qty_pallet);

			arr_konversi_sku_pallet_asal[findIndexData] = ({
				'idx': idx,
				'tr_konversi_sku_detail_id': $("#filter_pallet_asal_tr_konversi_sku_detail_id").val(),
				'tr_konversi_sku_id': $("#pembongkaran-tr_konversi_sku_id").val(),
				'pallet_id_asal': $("#item-" + index + "-konversi_sku_pallet_asal-pallet_id_asal").val(),
				'sku_stock_id': $("#filter_pallet_asal_sku_stock_id").val(),
				'sku_stock_qty_pallet': isNaN(sku_stock_qty_pallet) ? 0 : sku_stock_qty_pallet,
				'sku_stock_qty': $("#item-" + index + "-konversi_sku_pallet_asal-sku_stock_qty").val()
			});
		}

		console.log($("#item-" + index + "-konversi_sku_pallet_asal-sku_stock_qty_pallet").val());

		GetListPalletAsal();
	}

	function DeleteListPallet(idx) {

		const findIndexData = arr_konversi_sku_pallet_asal.findIndex((value) => value.idx == idx);

		if (findIndexData > -1) { // only splice array when item is found
			arr_konversi_sku_pallet_asal.splice(findIndexData, 1); // 2nd parameter means remove one item only
		}

		GetListPalletAsal();

	}

	$('#btn_save_pallet_asal').click(function(event) {
		let pallet_id_asal = "";
		let sku_stock_id_awal = "";
		let cek_error = 0;

		$.each(arr_konversi_sku_pallet_asal, function(i, v) {
			if (pallet_id_asal == v.pallet_id_asal && sku_stock_id_awal == v.sku_stock_id) {
				let alert = "Pallet Tidak Boleh Sama Pada 1 SKU Stock";
				message("Error", alert, "error");
				DeleteListPallet(v.idx);

				cek_error++;
				return false;
			}

			pallet_id_asal = v.pallet_id_asal;
			sku_stock_id_awal = v.sku_stock_id;
		});

		setTimeout(() => {
			if (cek_error == 0) {
				$("#modal-pallet-asal").modal('hide');
				GetKonversiDetailWithPalletById();
				Get_list_sku_konversi_group_by_tr_konversi_sku_id();
			}
		}, 1000);
	});
</script>