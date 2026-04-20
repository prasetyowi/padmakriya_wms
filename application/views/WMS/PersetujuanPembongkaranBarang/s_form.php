<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_sku_exp = [];
	let arr_sku_stock_id = [];
	let arr_konversi_detail = [];
	let arr_konversi_detail2 = [];
	let tempPrinciple = "";
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2({
				width: '100%'
			});

			$.ajax({
				async: false,
				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/delete_tr_konversi_sku_detail2_temp'); ?>",
				type: "POST",
				dataType: "JSON",
				success: function(data) {
					console.log(data);
				}
			});

			<?php if ($act == "edit") { ?>
				<?php foreach ($KonversiDetail as $i => $detail) : ?>
					tempPrinciple = "<?= $detail['principle_id'] ?>";
					arr_konversi_detail.push({
						'sku_id': "<?= $detail['sku_id'] ?>",
						'sku_stock_id': "<?= $detail['sku_stock_id'] ?>",
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

				initDataSKU();
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

	$('#persetujuanpembongkaran-konversi_is_need_approval').click(function(event) {
		if (this.checked) {
			$("#persetujuanpembongkaran-tr_konversi_sku_status").val("In Progress Approval");
		} else {
			$("#persetujuanpembongkaran-tr_konversi_sku_status").val("Draft");
		}
	});

	$("#persetujuanpembongkaran-tipe_konversi_id").on("change", function() {
		arr_sku = [];
		$("#table-sku-pembongkaran > tbody").empty();
	});

	$("#persetujuanpembongkaran-client_wms_id").on("change", function() {
		var perusahaan = $("#persetujuanpembongkaran-client_wms_id").val();
		reset_table_sku();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetPrincipleByPerusahaan') ?>",
			data: {
				perusahaan: perusahaan
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter-principle").html('');

				if (response != 0) {
					$("#filter-principle").append(
						`<option value=""><span name="CAPTION-SEMUA">All</span></option>`);
					$.each(response, function(i, v) {
						$("#filter-principle").append(
							`<option value="${v.principle_id}">${v.principle_kode}</option>`
						);
					});
				}

				// $("#persetujuanpembongkaran-principle_id").html('');

				// if (response != 0) {
				//     $("#persetujuanpembongkaran-principle_id").append(
				//         `<option value=""><span name="CAPTION-PILIHPRINCIPLE">** Pilih Principle **</span></option>`);
				//     $.each(response, function(i, v) {
				//         $("#persetujuanpembongkaran-principle_id").append(
				//             `<option value="${v.principle_id}">${v.principle_kode}</option>`
				//         );
				//     });
				// }

				// if (perusahaan == "") {
				//     $("#persetujuanpembongkaran-principle_id").append(
				//         `<option value=""><span name="CAPTION-PILIHPRINCIPLE">** Pilih Principle **</span></option>`);
				// }
			}
		});

	});

	$("#persetujuanpembongkaran-depo_detail_id").on("change", function() {
		reset_table_sku();

		var selectedValue = $(this).val();
		$("#persetujuanpembongkaran-gudang-tujuan").val(selectedValue);
		$("#persetujuanpembongkaran-gudang-tujuan").trigger('change');
	});

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

	$('#select-koversi').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxKonversi"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxKonversi"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$("#btn-choose-prod-delivery").on("click", function() {
		var perusahaan = $("#persetujuanpembongkaran-client_wms_id").val();
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();

		if (perusahaan != "") {
			if (tipe_konversi != "") {
				$("#modal-sku").modal('show');
			} else {
				let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
				message_custom("Error", "error", alert_tes);
			}
		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}
	});

	$("#btn-search-sku").on("click", function() {
		initDataSKU();
	});

	$(document).on("click", ".btn-choose-sku-multi", function() {
		var jumlah = $('input[name="CheckboxSKU"]').length;
		var numberOfChecked = $('input[name="CheckboxSKU"]:checked').length;
		var no = 1;
		jumlah_sku = numberOfChecked;

		arr_sku = [];
		arr_sku_exp = [];
		arr_sku_stock_id = [];

		if (numberOfChecked > 0) {
			for (var i = 0; i < jumlah; i++) {
				var checked = $('[id="check_sku_' + i + '"]:checked').length;
				var sku_id = "'" + $("#check_sku_" + i).val() + "'";
				var sku_exp = "'" + $("#sku_exp_" + i).val() + "'";
				var sku_stock_id = "'" + $("#sku_stock_id_" + i).val() + "'";

				if (checked > 0) {
					arr_sku.push(sku_id);
					arr_sku_exp.push(sku_exp);
					arr_sku_stock_id.push(sku_stock_id);
				}
			}

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

			var result3 = arr_sku_stock_id.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_sku = result;
			arr_sku_exp = result2;
			arr_sku_stock_id = result3;

			// console.log(arr_sku);
			// `<button class="btn btn-primary btn-small" onclick="GetSKUKonversi('${v.sku_stock_id}','${v.sku_id}','${v.sku_stock_expired_date}',${i},'${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}')"><i class="fa fa-angle-down"></i></button>`

			$("#table-sku-pembongkaran > tbody").empty();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetSelectedSKU') ?>",
				data: {
					sku_id: arr_sku,
					sku_stock_expired_date: arr_sku_exp,
					sku_stock_id: arr_sku_stock_id
				},
				dataType: "JSON",
				success: function(response) {
					$.each(response, function(i, v) {
						$("#table-sku-pembongkaran > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_id" value="${v.sku_id}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_expired_date" value="${v.sku_stock_expired_date}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_id" value="${v.sku_stock_id}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-principle" value="${v.principle}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_konversi_hasil"/>
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-kode-label">${v.sku_kode}</span>
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_kode" class="form-control sku-kode" value="${v.sku_kode}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_nama_produk" class="form-control sku-nama-produk" value="${v.sku_nama_produk}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date2}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td style="text-align: center; vertical-align: middle;width:15%;">
									<input type="number" id="item-${i}-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" min="0" class="form-control" value="${v.tr_konversi_sku_detail2_qty}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<button class="btn btn-danger btn-small" onclick="DeleteSKU(this,${i})"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						`);
					});
				}
			});

		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih SKU!'
			});
		}
	});

	function initDataSKU() {
		var perusahaan = $("#persetujuanpembongkaran-client_wms_id").val();
		var depo_detail_id = $("#persetujuanpembongkaran-depo_detail_id").val();
		var principle = $("#filter-principle").val();
		// var principle = $("#persetujuanpembongkaran-principle_id").val();
		var sku_kode = $("#filter-sku-kode").val();
		var sku_nama_produk = $("#filter-sku-nama-produk").val();
		var sku_satuan = $("#filter-sku-satuan").val();
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();

		$("#loadingsku").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/search_filter_chosen_sku') ?>",
			data: {
				perusahaan: perusahaan,
				depo_detail_id: depo_detail_id,
				principle: tempPrinciple != "" ? tempPrinciple : principle,
				sku_kode: sku_kode,
				sku_nama_produk: sku_nama_produk,
				sku_satuan: sku_satuan,
				tipe_konversi: tipe_konversi
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				$("#loadingsku").hide();

				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table-sku')) {
						$('#table-sku').DataTable().destroy();
					}
					$("#table-sku > tbody").empty();

					$.each(response, function(i, v) {
						$("#table-sku > tbody").append(`
							<tr>
								<td width="5%" style="text-align: center; vertical-align: middle;">
									<input type="checkbox" name="CheckboxSKU" id="check_sku_${i}" value="${v.sku_id}">
									<input type="hidden" id="sku_exp_${i}" value="${v.sku_stock_expired_date}">
									<input type="hidden" id="sku_stock_id_${i}" value="${v.sku_stock_id}">
								</td>
								<td width="15%" style="text-align: center; vertical-align: middle;">${v.sku_induk}</td>
								<td width="15%" style="text-align: center; vertical-align: middle;">${v.sku_kode}</td>
								<td width="25%" style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
								<td width="8%" style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
								<td width="8%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td width="10%" style="text-align: center; vertical-align: middle;">${v.principle}</td>
								<td width="10%" style="text-align: center; vertical-align: middle;">${v.brand}</td>
								<td width="10%" style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date2}</td>
								<td width="10%" style="text-align: center; vertical-align: middle;">${v.sku_stock_batch_no}</td>
								<td width="10%" style="text-align: center; vertical-align: middle;">${v.sku_stock}</td>
							</tr>
						`);
					});

					$('#table-sku').DataTable({
						"searching": false,
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});
				} else {
					$("#table-sku > tbody").html(
						`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
				}
			}
		});
	}

	$("#btnsavekonversi").on("click", function() {
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();
		var qty_plan = parseInt($("#filter_konversi_qty_plan").val());
		var idx = 0;
		var cek_qty = 0;
		var cek_completed = 0;

		arr_konversi_detail = [];

		$("#table-sku-pembongkaran > tbody tr").each(function() {

			if ($("#item-" + idx + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val() >
				0) {
				arr_konversi_detail.push({
					'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
					'sku_stock_id': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_stock_id").val(),
					'sku_stock_expired_date': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
					'tr_konversi_sku_detail_qty_plan': $("#item-" + idx +
						"-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val(),
					'principle_kode': $("#item-" + idx +
						"-persetujuanpembongkarandetail-principle").val(),
					'sku_konversi_hasil': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_konversi_hasil").val()
				});
			} else {
				cek_qty++;
			}

			idx++;
		});

		console.log(arr_konversi_detail);

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Pastikan data yang sudah anda input benar!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				//ajax save data

				$("#loadingview").show();

				if (tipe_konversi == "") {
					let alert_tes = $('span[name="CAPTION-ALERT-PILIHTIPEKONVERSI"]').eq(0).text();
					message_custom("Error", "error", alert_tes);
				} else {
					cek_completed++;
				}

				if (arr_konversi_detail.length == 0) {
					let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
					message_custom("Error", "error", alert_tes);
				} else {
					cek_completed++;
				}

				if (cek_qty > 0) {
					let alert_tes = $('span[name="CAPTION-ALERT-CAPTION-SKUQTYTIDAKBOLEH0"]').eq(0).text();
					message_custom("Error", "error", alert_tes);
				} else {
					cek_completed++;
				}

				if (cek_completed == 3) {
					$.ajax({
						async: false,
						url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/insert_tr_konversi_sku'); ?>",
						type: "POST",
						data: {
							tr_konversi_sku_id: $("#persetujuanpembongkaran-tr_konversi_sku_id")
								.val(),
							client_wms_id: $("#persetujuanpembongkaran-client_wms_id").val(),
							depo_detail_id: $("#persetujuanpembongkaran-depo_detail_id").val(),
							tipe_konversi_id: $("#persetujuanpembongkaran-tipe_konversi_id").val(),
							tr_konversi_sku_kode: "",
							tr_konversi_sku_tanggal: $(
								"#persetujuanpembongkaran-tr_konversi_sku_tanggal").val(),
							tr_konversi_sku_status: $(
								"#persetujuanpembongkaran-tr_konversi_sku_status").val(),
							tr_konversi_sku_keterangan: $(
								"#persetujuanpembongkaran-tr_konversi_sku_keterangan").val(),
							tr_konversi_sku_tgl_create: "",
							tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
							konversi_is_need_approval: $(
									"#persetujuanpembongkaran-konversi_is_need_approval:checked")
								.val(),
							is_from_do: 0,
							detail: arr_konversi_detail
						},
						dataType: "JSON",
						success: function(data) {
							if (data == 1) {
								$("#loadingview").hide();

								let alert_tes = $(
										'span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0)
									.text();
								message_custom("Success", "success", alert_tes);

								setTimeout(() => {
									location.href =
										"<?= base_url(); ?>WMS/PersetujuanPembongkaranBarang/PersetujuanPembongkaranMenu";
								}, 500);
							} else {
								$("#loadingview").hide();

								let alert_tes = $(
										'span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
									.text();
								message_custom("Error", "error", alert_tes);
							}
						}
					});

				} else {
					cek_qty = 0;
					cek_completed = 0;
					$("#loadingview").hide();
				}
			}
		});
	});

	$("#btnupdatekonversi").on("click", function() {
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();
		var qty_plan = parseInt($("#filter_konversi_qty_plan").val());
		var idx = 0;
		var cek_qty = 0;
		var cek_completed = 0;

		arr_konversi_detail = [];

		$("#table-sku-pembongkaran > tbody tr").each(function() {

			if ($("#item-" + idx + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val() >
				0) {
				arr_konversi_detail.push({
					'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
					'sku_stock_id': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_stock_id").val(),
					'sku_stock_expired_date': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
					'tr_konversi_sku_detail_qty_plan': $("#item-" + idx +
						"-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val(),
					'principle_kode': $("#item-" + idx +
						"-persetujuanpembongkarandetail-principle").val(),
					'sku_konversi_hasil': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_konversi_hasil").val()
				});
			} else {
				cek_qty++;
			}

			idx++;
		});

		console.log(arr_konversi_detail);
		// return false;

		if (tipe_konversi == "") {
			let alert_tes = $('span[name="CAPTION-ALERT-PILIHTIPEKONVERSI"]').eq(0).text();
			message_custom("Error", "error", alert_tes);

			return false;
		}

		if (arr_konversi_detail.length == 0) {
			let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
			message_custom("Error", "error", alert_tes);

			return false;
		}

		if (cek_qty > 0) {
			let alert_tes = $('span[name="CAPTION-ALERT-CAPTION-SKUQTYTIDAKBOLEH0"]').eq(0).text();
			message_custom("Error", "error", alert_tes);

			return false;
		}

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax(
					"<?= base_url('WMS/PersetujuanPembongkaranBarang/update_tr_konversi_sku'); ?>", {
						tr_konversi_sku_id: $("#persetujuanpembongkaran-tr_konversi_sku_id").val(),
						client_wms_id: $("#persetujuanpembongkaran-client_wms_id").val(),
						depo_detail_id: $("#persetujuanpembongkaran-depo_detail_id").val(),
						tipe_konversi_id: $("#persetujuanpembongkaran-tipe_konversi_id").val(),
						tr_konversi_sku_kode: $("#persetujuanpembongkaran-tr_konversi_sku_kode").val(),
						tr_konversi_sku_tanggal: $("#persetujuanpembongkaran-tr_konversi_sku_tanggal")
							.val(),
						tr_konversi_sku_status: $("#persetujuanpembongkaran-tr_konversi_sku_status")
							.val(),
						tr_konversi_sku_keterangan: $(
							"#persetujuanpembongkaran-tr_konversi_sku_keterangan").val(),
						tr_konversi_sku_tgl_create: "",
						tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
						konversi_is_need_approval: $(
							"#persetujuanpembongkaran-konversi_is_need_approval:checked").val(),
						detail: arr_konversi_detail,
						tr_konversi_sku_tgl_update: $(
							"#persetujuanpembongkaran-tr_konversi_sku_tgl_update").val()
					}, "POST", "JSON",
					function(response) {
						if (response == 1) {
							$("#loadingview").hide();
							let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0)
								.text();
							message_custom("Success", "success", alert_tes);

							setTimeout(() => {
								location.href =
									"<?= base_url(); ?>WMS/PersetujuanPembongkaranBarang/PersetujuanPembongkaranMenu";
							}, 500);
						} else if (response == 2) {
							messageNotSameLastUpdated();
							return false;
						} else {
							$("#loadingview").hide();

							let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0)
								.text();
							message_custom("Error", "error", alert_tes);
						}
					}, "#btnupdatekonversi")
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

		// 		if (tipe_konversi == "") {
		// 			let alert_tes = $('span[name="CAPTION-ALERT-PILIHTIPEKONVERSI"]').eq(0).text();
		// 			message_custom("Error", "error", alert_tes);
		// 		} else {
		// 			cek_completed++;
		// 		}

		// 		if (arr_konversi_detail.length == 0) {
		// 			let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
		// 			message_custom("Error", "error", alert_tes);
		// 		} else {
		// 			cek_completed++;
		// 		}

		// 		if (cek_qty > 0) {
		// 			let alert_tes = $('span[name="CAPTION-ALERT-CAPTION-SKUQTYTIDAKBOLEH0"]').eq(0).text();
		// 			message_custom("Error", "error", alert_tes);
		// 		} else {
		// 			cek_completed++;
		// 		}

		// 		if (cek_completed == 3) {
		// 			$.ajax({
		// 				async: false,
		// 				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/update_tr_konversi_sku'); ?>",
		// 				type: "POST",
		// 				data: {
		// 					tr_konversi_sku_id: $("#persetujuanpembongkaran-tr_konversi_sku_id").val(),
		// 					client_wms_id: $("#persetujuanpembongkaran-client_wms_id").val(),
		// 					depo_detail_id: $("#persetujuanpembongkaran-depo_detail_id").val(),
		// 					tipe_konversi_id: $("#persetujuanpembongkaran-tipe_konversi_id").val(),
		// 					tr_konversi_sku_kode: $("#persetujuanpembongkaran-tr_konversi_sku_kode").val(),
		// 					tr_konversi_sku_tanggal: $("#persetujuanpembongkaran-tr_konversi_sku_tanggal").val(),
		// 					tr_konversi_sku_status: $("#persetujuanpembongkaran-tr_konversi_sku_status").val(),
		// 					tr_konversi_sku_keterangan: $("#persetujuanpembongkaran-tr_konversi_sku_keterangan").val(),
		// 					tr_konversi_sku_tgl_create: "",
		// 					tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
		// 					konversi_is_need_approval: $("#persetujuanpembongkaran-konversi_is_need_approval:checked").val(),
		// 					detail: arr_konversi_detail
		// 				},
		// 				dataType: "JSON",
		// 				success: function(data) {
		// 					if (data == 1) {
		// 						$("#loadingview").hide();

		// 						let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
		// 						message_custom("Success", "success", alert_tes);

		// 						setTimeout(() => {
		// 							location.href = "<?= base_url(); ?>WMS/PersetujuanPembongkaranBarang/PersetujuanPembongkaranMenu";
		// 						}, 500);
		// 					} else if (data == 2) {
		// 						$("#loadingview").hide();
		// 						let alert_tes = $('span[name="CAPTION-ALERT-CHECKQTYPLANSKUDENGANFORMKONVERSI"]').eq(0).text();
		// 						message_custom("Error", "error", alert_tes);

		// 					} else {
		// 						$("#loadingview").hide();

		// 						let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0).text();
		// 						message_custom("Error", "error", alert_tes);
		// 					}
		// 				}
		// 			});

		// 			// console.log(arr_konversi_detail);

		// 		} else {
		// 			cek_qty = 0;
		// 			cek_completed = 0;
		// 			$("#loadingview").hide();
		// 		}
		// 	}
		// });
	});

	function GetSKUKonversi(sku_stock_id, sku_id, sku_stock_expired_date, index, sku, kemasan, satuan) {
		var sku_qty = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val();
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();
		var total_nilai = 0;

		$("#filter_konversi_sku").val('');
		$("#filter_konversi_kemasan").val('');
		$("#filter_konversi_satuan").val('');
		$("#filter_konversi_qty_plan").val('');
		$("#filter_konversi_qty_sisa").val('');
		// $("#filter_konversi_sku_stock_id").val('');

		if (tipe_konversi != "") {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetSKUKonversiBySKU') ?>",
				data: {
					sku_stock_id: sku_stock_id,
					sku_id: sku_id,
					sku_stock_expired_date: sku_stock_expired_date,
					satuan: satuan,
					tipe_konversi: $("#persetujuanpembongkaran-tipe_konversi_id option:selected").text()
				},
				dataType: "JSON",
				success: function(response) {
					$("#table-konversi > tbody").empty();

					if (response != 0) {
						$("#modal-konversi").modal('show');

						$.each(response, function(i, v) {
							$("#table-konversi > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">
									<input type="checkbox" name="CheckboxKonversi" id="check_konversi_${i}" value="1" onclick="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}')" ${v.tr_konversi_sku_detail2_qty != '0' ? 'checked' : '' }>
									<input type="hidden" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail_id" value="${v.sku_id}">
									<input type="hidden" id="item-${i}-konversiskudetail2-sku_id" value="${v.sku_id_konversi}">
									<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
									<input type="hidden" id="item-${i}-konversiskudetail2-sku_stock_id" value="${v.sku_stock_id}">
								</td>
								<td width="30%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td width="30%" style="text-align: center; vertical-align: middle;">
									<input type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${v.tr_konversi_sku_detail2_qty}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}')" />
								</td>
								<td width="30%" style="text-align: center; vertical-align: middle;"><span id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty_result">${v.tr_konversi_sku_detail2_qty_result}</span></td>
							</tr>
						`);
							total_nilai += v.tr_konversi_sku_detail2_qty;
						});

						$("#filter_konversi_sku").val(sku);
						$("#filter_konversi_kemasan").val(kemasan);
						$("#filter_konversi_satuan").val(satuan);
						$("#filter_konversi_qty_plan").val(sku_qty);
						$("#filter_konversi_qty_sisa").val(sku_qty - total_nilai);
						// $("#filter_konversi_sku_stock_id").val(sku_stock_id);
					} else {
						$("#filter_konversi_sku").val(sku);
						$("#filter_konversi_kemasan").val(kemasan);
						$("#filter_konversi_satuan").val(satuan);
						$("#filter_konversi_qty_plan").val(sku_qty);
						$("#modal-konversi").modal('hide');
					}
				}
			});

		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-PILIHSKUKONVERSI"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}
	}

	$("#btn_save_konversi").on("click", function() {
		var qty_sisa = parseInt($("#filter_konversi_qty_sisa").val());
		var qty_plan = parseInt($("#filter_konversi_qty_plan").val());
		var idx = 0;
		var total_nilai = 0;
		arr_konversi_detail2 = [];

		$("#table-konversi > tbody tr").each(function() {
			var checked = $('[id="check_konversi_' + idx + '"]:checked').length;

			if (checked > 0) {
				arr_konversi_detail2.push({
					'tr_konversi_sku_detail_id': $("#item-" + idx +
						"-konversiskudetail2-tr_konversi_sku_detail_id").val(),
					'sku_stock_id': $("#item-" + idx + "-konversiskudetail2-sku_stock_id").val(),
					'sku_id': $("#item-" + idx + "-konversiskudetail2-sku_id").val(),
					'sku_stock_expired_date': $("#item-" + idx +
						"-konversiskudetail2-sku_stock_expired_date").val(),
					'tr_konversi_sku_detail2_qty': $("#item-" + idx +
						"-konversiskudetail2-tr_konversi_sku_detail2_qty").val(),
					'tr_konversi_sku_detail2_qty_result': $("#item-" + idx +
						"-konversiskudetail2-tr_konversi_sku_detail2_qty_result").text()
				});

				total_nilai += parseInt($("#item-" + idx +
					"-konversiskudetail2-tr_konversi_sku_detail2_qty").val());
			}
			idx++;
		});

		qty_sisa = qty_plan - total_nilai;

		// console.log(arr_konversi_detail2);

		if (qty_sisa == 0) {
			$("#loadingkonversi").show();

			$.ajax({
				async: false,
				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/insert_tr_konversi_sku_detail2_temp'); ?>",
				type: "POST",
				data: {
					detail: arr_konversi_detail2
				},
				dataType: "JSON",
				success: function(data) {
					if (data == 1) {
						$("#loadingkonversi").hide();

						let alert_tes = $('span[name="CAPTION-ALERT-DATABERHASILDISIMPAN').eq(0).text();
						message_custom("Success", "success", alert_tes);

						$("#modal-konversi").modal('hide');
					} else {
						$("#loadingkonversi").hide();

						let alert_tes = $('span[name="CAPTION-ALERT-DATAGAGALDISIMPAN"]').eq(0).text();
						message_custom("Error", "error", alert_tes);
					}
				}
			});
		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-NILAIBELUMTERKONVERSISEMUA"]').eq(0).text();
			message_custom("Error", "error", alert_tes);
		}
	});

	function GetHasilKonversi(index, konversi, sku_konversi_faktor_param, konversi_operator) {
		var qty_plan = parseInt($("#filter_konversi_qty_plan").val());
		var nilai_konversi = parseInt($("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val()) *
			sku_konversi_faktor_param;
		var qty_sisa = 0;
		var total_nilai = 0;
		var idx = 0;
		var checked = $('[id="check_konversi_' + index + '"]:checked').length;
		var sisa_bagi = nilai_konversi % parseInt(konversi);
		var hasil_konversi = (nilai_konversi - sisa_bagi) / parseInt(konversi);

		$("#table-konversi > tbody tr").each(function() {
			var checked = $('[id="check_konversi_' + idx + '"]:checked').length;

			if (checked > 0) {
				total_nilai += parseInt($("#item-" + idx + "-konversiskudetail2-tr_konversi_sku_detail2_qty")
					.val());
			}
			idx++;
		});

		qty_sisa = qty_plan - total_nilai;

		$("#filter_konversi_qty_sisa").val(qty_sisa);

		$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").html('');
		$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").html('');

		if (nilai_konversi <= (qty_sisa + nilai_konversi)) {
			if (checked > 0) {
				if (konversi > 0) {
					if (sisa_bagi > 0) {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
						$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							sisa_bagi);
					} else {
						$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append(
							hasil_konversi);
					}
				} else {
					let alert_tes = $('span[name="CAPTION-ALERT-SKUKONVERSIFAKTOR0"]').eq(0).text();
					message_custom("Error", "error", alert_tes);

				}
			} else {
				$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append('0');
				$("#item-" + (index + 1) + "-konversiskudetail2-tr_konversi_sku_detail2_qty_result").append('0');
			}

		} else {
			let alert_tes = $('span[name="CAPTION-ALERT-NILAIKONVERSIMELEBIHIQTYPLAN"]').eq(0).text();
			message_custom("Error", "error", alert_tes);

			$("#item-" + index + "-konversiskudetail2-tr_konversi_sku_detail2_qty").val(0);
		}

	}

	function DeleteSKU(row, index) {
		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		arr_sku.splice(index, 1)
		arr_sku_exp.splice(index, 1)
		arr_sku_stock_id.splice(index, 1)

		// console.log(index + " - " + arr_sku);
		// `<button class="btn btn-primary btn-small" onclick="GetSKUKonversi('${v.sku_stock_id}','${v.sku_id}','${v.sku_stock_expired_date}',${i},'${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}')"><i class="fa fa-angle-down"></i></button>`

		setTimeout(() => {
			$("#table-sku-pembongkaran > tbody").empty();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetSelectedSKU') ?>",
				data: {
					sku_id: arr_sku,
					sku_stock_expired_date: arr_sku_exp,
					sku_stock_id: arr_sku_stock_id
				},
				dataType: "JSON",
				success: function(response) {
					$.each(response, function(i, v) {
						$("#table-sku-pembongkaran > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_id" value="${v.sku_id}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_expired_date" value="${v.sku_stock_expired_date}" />
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_stock_id" value="${v.sku_stock_id}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-kode-label">${v.sku_kode}</span>
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_kode" class="form-control sku-kode" value="${v.sku_kode}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
									<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_nama_produk" class="form-control sku-nama-produk" value="${v.sku_nama_produk}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date2}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td style="text-align: center; vertical-align: middle;width:15%;">
									<input type="number" id="item-${i}-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan" class="form-control" value="${v.tr_konversi_sku_detail2_qty}" />
								</td>
								<td style="text-align: center; vertical-align: middle;">
									<button class="btn btn-danger btn-small" onclick="DeleteSKU(this,${i})"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						`);
					});
				}
			});
		}, 1000);

	}

	function reset_table_sku() {
		arr_sku = [];
		$("#table-sku-pembongkaran > tbody").empty();
		initDataSKU();
	}
</script>