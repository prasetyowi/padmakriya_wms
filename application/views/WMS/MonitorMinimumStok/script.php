<script type="text/javascript">
	var ChannelCode = '';
	var arr_list_sku = [];

	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
		}
	);

	$('#select-all-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
				var data_sku = JSON.parse(this.getAttribute('data-sku'));
				arr_list_sku.push({
					...data_sku
				});
				// console.log(this.getAttribute('data-sku'));
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
				arr_list_sku = [];
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

	$("#btn-search-data").click(function() {
		GetMonitorMinimumStok();
	});

	function GetMonitorMinimumStok() {
		arr_list_sku = [];

		$("#select-all-sku").prop("checked", false);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MonitorMinimumStok/GetMonitorMinimumStok') ?>",
			data: {
				principle: $("#filter-principle").val(),
				depo_detail_id: $("#filter-depo_detail_id").val(),
				min_stock: $("#filter-minimum-stock").val(),
			},
			dataType: "JSON",

			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
				$("#btn-search-data").prop("disabled", true);
			},
			success: function(response) {
				$("#table_list_data > tbody").html('');
				$("#table_list_data > tbody").empty();

				$.each(response, function(i, v) {

					if (v.max_level == v.sku_konversi_level) {
						$("#table_list_data > tbody").append(`
							<tr style="background: ${v.status_stock == 1 ? 'green' : 'red'};color:white;">
								<td class="text-center"></td>
								<td class="text-center">${v.sku_kode}</td>
								<td class="text-center">${v.sku_nama_produk}</td>
								<td class="text-center">${v.principle}</td>
								<td class="text-center">${v.brand}</td>
								<td class="text-center">${v.sku_kemasan}</td>
								<td class="text-center">${v.sku_satuan}</td>
								<td class="text-center">${v.sku_stock_expired_date}</td>
								<td class="text-center">${v.sku_stock}</td>
								<td class="text-center">${v.min_stock}</td>
							</tr>
						`);
					} else {
						var data_sku = []
						if (v.status_stock == 0) {
							data_sku = {
								'sku_stock_id': v.sku_stock_id,
								'no_urut': i,
								'client_wms_id': v.client_wms_id,
								'sku_id': v.sku_id,
								'sku_nama_produk': v.sku_nama_produk,
								'principle': v.principle,
								'brand': v.brand,
								'sku_kemasan': v.sku_kemasan,
								'sku_satuan': v.sku_satuan,
								'sku_konversi_group': v.sku_konversi_group,
								'depo_detail_id': v.depo_detail_id,
								'depo_detail_nama': v.depo_detail_nama,
								'sku_stock_expired_date': v.sku_stock_expired_date,
								'sku_stock_batch_no': v.sku_stock_batch_no,
								'sku_stock': v.sku_stock,
								'min_stock': v.min_stock
							}

							$("#table_list_data > tbody").append(`
								<tr style="background: ${v.status_stock == 1 ? 'green' : 'red'};color:white;">
									<td class="text-center">
										<input type="checkbox" id="item-${i}-sku_stock_id" name="CheckboxSKU" data-sku='${JSON.stringify({...data_sku})}' value="${v.sku_stock_id}" onclick="PustToArraySKU('${v.sku_stock_id}','${i}','${v.client_wms_id}','${v.sku_id}','${v.sku_nama_produk}','${v.principle}','${v.brand}','${v.sku_kemasan}','${v.sku_satuan}','${v.sku_konversi_group}','${v.depo_detail_id}','${v.depo_detail_nama}','${v.sku_stock_expired_date}','${v.sku_stock_batch_no}','${v.sku_stock}')"/>
									</td>
									<td class="text-center">${v.sku_kode}</td>
									<td class="text-center">${v.sku_nama_produk}</td>
									<td class="text-center">${v.principle}</td>
									<td class="text-center">${v.brand}</td>
									<td class="text-center">${v.sku_kemasan}</td>
									<td class="text-center">${v.sku_satuan}</td>
									<td class="text-center">${v.sku_stock_expired_date}</td>
									<td class="text-center">${v.sku_stock}</td>
									<td class="text-center">
										<input type="text" id="item-${i}-min_stock" class="form-control" value="${v.min_stock}" onchange="UpdateArraySKU('${v.sku_stock_id}','${i}','${v.client_wms_id}','${v.sku_id}','${v.sku_nama_produk}','${v.principle}','${v.brand}','${v.sku_kemasan}','${v.sku_satuan}','${v.sku_konversi_group}','${v.depo_detail_id}','${v.depo_detail_nama}','${v.sku_stock_expired_date}','${v.sku_stock_batch_no}','${v.sku_stock}')"/>
									</td>
								</tr>
							`);
						} else {

							$("#table_list_data > tbody").append(`
								<tr style="background: ${v.status_stock == 1 ? 'green' : 'red'};color:white;">
									<td class="text-center"></td>
									<td class="text-center">${v.sku_kode}</td>
									<td class="text-center">${v.sku_nama_produk}</td>
									<td class="text-center">${v.principle}</td>
									<td class="text-center">${v.brand}</td>
									<td class="text-center">${v.sku_kemasan}</td>
									<td class="text-center">${v.sku_satuan}</td>
									<td class="text-center">${v.sku_stock_expired_date}</td>
									<td class="text-center">${v.sku_stock}</td>
									<td class="text-center">${v.min_stock}</td>
								</tr>
							`);

						}
					}
				});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
				$("#btn-search-data").prop("disabled", false);
			},
			complete: function() {
				Swal.close();
				$("#btn-search-data").prop("disabled", false);
			}
		});

	}

	function PustToArraySKU(sku_stock_id, idx, client_wms_id, sku_id, sku_nama_produk, principle, brand, sku_kemasan, sku_satuan, sku_konversi_group, depo_detail_id, depo_detail_nama, sku_stock_expired_date, sku_stock_batch_no, sku_stock) {
		var checked = $('[id="item-' + idx + '-sku_stock_id"]:checked').length;

		$("#select-all-sku").prop("checked", false);

		if (checked > 0) {

			arr_list_sku.push({
				'sku_stock_id': sku_stock_id,
				'no_urut': i,
				'client_wms_id': client_wms_id,
				'sku_id': sku_id,
				'sku_nama_produk': sku_nama_produk,
				'principle': principle,
				'brand': brand,
				'sku_kemasan': sku_kemasan,
				'sku_satuan': sku_satuan,
				'sku_konversi_group': sku_konversi_group,
				'depo_detail_id': depo_detail_id,
				'depo_detail_nama': depo_detail_nama,
				'sku_stock_expired_date': sku_stock_expired_date,
				'sku_stock_batch_no': sku_stock_batch_no,
				'sku_stock': sku_stock,
				'min_stock': $("#item-" + idx + "-min_stock").val()
			})
		} else {
			const findIndexData = arr_list_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
			if (findIndexData > -1) { // only splice array when item is found
				arr_list_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
		}

	}

	function UpdateArraySKU(sku_stock_id, idx, client_wms_id, sku_id, sku_nama_produk, principle, brand, sku_kemasan, sku_satuan, sku_konversi_group, depo_detail_id, depo_detail_nama, sku_stock_expired_date, sku_stock_batch_no, sku_stock) {
		var checked = $('[id="item-' + idx + '-sku_stock_id"]:checked').length;

		const findIndexData = arr_list_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
		if (checked > 0) {

			arr_list_sku[findIndexData] = {
				'sku_stock_id': sku_stock_id,
				'no_urut': i,
				'client_wms_id': client_wms_id,
				'sku_id': sku_id,
				'sku_nama_produk': sku_nama_produk,
				'principle': principle,
				'brand': brand,
				'sku_kemasan': sku_kemasan,
				'sku_satuan': sku_satuan,
				'sku_konversi_group': sku_konversi_group,
				'depo_detail_id': depo_detail_id,
				'depo_detail_nama': depo_detail_nama,
				'sku_stock_expired_date': sku_stock_expired_date,
				'sku_stock_batch_no': sku_stock_batch_no,
				'sku_stock': sku_stock,
				'min_stock': $("#item-" + idx + "-min_stock").val()
			}
		} else {
			if (findIndexData > -1) { // only splice array when item is found
				arr_list_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
		}

	}

	function PembongkaranBarang() {

		// console.log(arr_sku_konversi_group);

		if ($("#filter-depo_detail_id").val() != "") {

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MonitorMinimumStok/Konversi_sku') ?>",
				data: {
					arr_list_sku: arr_list_sku,
					depo_detail_id: $("#filter-depo_detail_id").val(),
				},
				dataType: "JSON",
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
					$("#btn-search-data").prop("disabled", true);
				},
				success: function(response) {
					$.each(response, function(i, v) {
						if (v.kode == "203") {
							var msg = GetLanguageByKode("CAPTION-ALERT-QTYDOTIDAKADASTOCK");

							new PNotify
								({
									title: 'Error',
									text: msg,
									type: 'error',
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});

						} else if (v.kode == "204") {
							var msg = GetLanguageByKode("CAPTION-ALERT-QTYDOTIDAKADASTOCK");

							new PNotify
								({
									title: 'Error',
									text: msg,
									type: 'error',
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});

						}
					});

				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
					$("#btn-search-data").prop("disabled", false);
				},
				complete: function() {
					Swal.close();
					$("#btn-search-data").prop("disabled", false);
				}
			});

			setTimeout(() => {

				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/MonitorMinimumStok/KonversiSKUFromDO') ?>",
					data: {
						depo_detail_id: $("#filter-depo_detail_id").val()
					},
					dataType: "JSON",
					beforeSend: function() {

						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn-search-data").prop("disabled", true);
						$("#loadingview").show();
					},
					success: function(response) {

						if (response.data != 0) {

							Swal.close();

							$("#modal-pembongkaran-sku").modal('show');

							$('#table-pembongkaran-sku').fadeOut("slow", function() {
								$(this).hide();

								$("#table-pembongkaran-sku > tbody").empty();

								if ($.fn.DataTable.isDataTable('#table-pembongkaran-sku')) {
									$('#table-pembongkaran-sku').DataTable().clear();
									$('#table-pembongkaran-sku').DataTable().destroy();
								}

							}).fadeIn("slow", function() {
								$(this).show();

								$.each(response.data, function(i, v) {
									$("#table-pembongkaran-sku > tbody").append(`
								<tr>
									<td class="text-center">
										${v.sku_kode}
										<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_id" value="${v.sku_id}">
                                        <input type="hidden" id="item-${i}-persetujuanpembongkarandetail-principle_kode" value="${v.principle_kode}">
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle_kode}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle_brand_nama}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkaran-client_wms_id" class="form-control select2" disabled>
											<option value = ""> ** <span name="CAPTION-PILIH"> PILIH </span> **</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkaran-depo_detail_id" class="form-control select2" onchange="GetSKUStockByGudang('${i}')" disabled>
											<option value="${v.depo_detail_id}">${v.depo_detail_nama}</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkarandetail-sku_stock_expired_date" class="form-control" disabled>
										    <option value="${v.sku_stock_id}">${v.sku_stock_expired_date} || ${v.sku_stock_batch_no}</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">${v.sku_qty}</td>
								</tr>
							`).fadeIn("slow");
									// });
								});

								$.each(response.data, function(i, v) {
									$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").html('');
									<?php if (isset($Perusahaan) && $Perusahaan != "0") { ?>
										$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
											`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
										);
										<?php foreach ($Perusahaan as $row) : ?>
											$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
												`<option value="<?= $row['client_wms_id'] ?>" ${v.client_wms_id == '<?= $row['client_wms_id'] ?>' ? 'selected' : ''}><?= $row['client_wms_nama'] ?></option>`
											);
										<?php endforeach; ?>
									<?php } else { ?>
										$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
											`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
										);
									<?php } ?>
								});

								$('#table-pembongkaran-sku').DataTable({
									lengthMenu: [
										[50, 100, 200, -1],
										[50, 100, 200, 'All']
									],
									ordering: false,
									searching: false,
									"scrollX": true
								});

								$(".select2").select2();
							});

						} else {
							$("#modal-pembongkaran-sku").modal('hide');

							let alert_tes = GetLanguageByKode('CAPTION-ALERT-PERSETUJUANPEMBONGKARANTIDAKADA');
							let alert_text = GetLanguageByKode('CAPTION-ALERT-LAKUKANAPPROVEDODAHULU');
							message_custom(alert_tes, "error", alert_text);

						}

						$("#btn-search-data").prop("disabled", false);
						$("#loadingview").hide();

					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");

						$("#btn-search-data").prop("disabled", false);
						$("#loadingview").hide();
					},
					complete: function() {

						$("#btn-search-data").prop("disabled", false);
						$("#loadingview").hide();
					}
				});

			}, 1000);

		} else {
			let alert_tes = GetLanguageByKode('CAPTION-PILIHGUDANG');
			message_custom("Error", "error", alert_tes);
		}

	}

	$("#btnsavekonversi").on("click", function() {
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();
		var cek_completed = 0;
		var cek_exp_date = 0;
		var cek_pembongkaran_completed = 0;

		var gudang_penerima = "";

		var arr_konversi_detail = [];
		var arr_client_wms_konversi = [];
		var arr_client_wms_konversi_detail = [];

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/MonitorMinimumStok/get_client_wms_konversi_sku') ?>",
			dataType: "JSON",
			async: false,
			success: function(response) {

				if (response != "0") {
					$.each(response, function(i, v) {
						arr_client_wms_konversi.push({
							'tr_konversi_sku_id': v.tr_konversi_sku_id,
							'client_wms_id': v.client_wms_id
						})
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure, Get Client WMS Failed", "error");
			}
		});

		// alert('tes')

		$("#table-pembongkaran-sku > tbody tr").each(function(idx) {

			var sku_stock_expired_date = $("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date option:selected").text();
			var arr_sku_stock_expired_date = sku_stock_expired_date.split(" || ");

			// console.log($("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date").val());
			if ($("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date").val() != "") {
				arr_konversi_detail.push({
					'client_wms_id': $("#item-" + idx + "-persetujuanpembongkaran-client_wms_id").val(),
					'depo_detail_id': $("#item-" + idx + "-persetujuanpembongkaran-depo_detail_id").val(),
					'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
					'sku_stock_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
					'sku_stock_expired_date': arr_sku_stock_expired_date[0],
					'tr_konversi_sku_detail_qty_plan': 1,
					'principle_kode': $("#item-" + idx + "-persetujuanpembongkarandetail-principle_kode").val()
				});

			} else {
				cek_exp_date++;

			}
		});

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

				if (cek_exp_date > 0) {
					let alert_tes = GetLanguageByKode('CAPTION-ALERT-PILIHTANGGALKADALUWARSA');
					message_custom("Error", "error", alert_tes);
				} else {

					if (arr_konversi_detail.length > 0) {

						if (arr_client_wms_konversi.length > 0) {

							$.each(arr_client_wms_konversi, function(i, v) {

								// GetKonversiFromDO();
								arr_client_wms_konversi_detail = [];
								cek_completed = 0;
								gudang_penerima = "";

								$.each(arr_konversi_detail, function(idx, val) {

									if (v.client_wms_id == $("#item-" + idx + "-persetujuanpembongkaran-client_wms_id").val()) {

										gudang_penerima = val.depo_detail_id;

										arr_client_wms_konversi_detail.push({
											'client_wms_id': val.client_wms_id,
											'depo_detail_id': val.depo_detail_id,
											'sku_id': val.sku_id,
											'sku_stock_id': val.sku_stock_id,
											'sku_stock_expired_date': val.sku_stock_expired_date,
											'tr_konversi_sku_detail_qty_plan': val.tr_konversi_sku_detail_qty_plan,
											'principle_kode': val.principle_kode
										})
									}

								});

								if (tipe_konversi == "") {
									let alert_tes = GetLanguageByKode(
										'CAPTION-ALERT-PILIHTIPEKONVERSI');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (arr_client_wms_konversi_detail.length == 0) {
									let alert_tes = GetLanguageByKode(
										'CAPTION-ALERT-PILIHSKUKONVERSI');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (v.client_wms_id == "") {
									let alert_tes = GetLanguageByKode('CAPTION-MSG03');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (v.depo_detail_id == "") {
									let alert_tes = GetLanguageByKode('CAPTION-PILIHGUDANG');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (cek_completed == 4) {

									$.ajax({
										url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/insert_tr_konversi_sku_from_another_menu'); ?>",
										type: "POST",
										data: {
											// tr_konversi_sku_id: $("#persetujuanpembongkaran-tr_konversi_sku_id").val(),
											// client_wms_id: $("#persetujuanpembongkaran-client_wms_id").val(),
											// depo_detail_id: $("#persetujuanpembongkaran-depo_detail_id").val(),
											tr_konversi_sku_id: v.tr_konversi_sku_id,
											client_wms_id: v.client_wms_id,
											depo_detail_id: gudang_penerima,
											tipe_konversi_id: $("#persetujuanpembongkaran-tipe_konversi_id").val(),
											tr_konversi_sku_kode: "",
											tr_konversi_sku_tanggal: $("#persetujuanpembongkaran-tr_konversi_sku_tanggal").val(),
											tr_konversi_sku_status: $("#persetujuanpembongkaran-tr_konversi_sku_status").val(),
											tr_konversi_sku_keterangan: "MINIMUM STOCK",
											tr_konversi_sku_tgl_create: "",
											tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
											konversi_is_need_approval: $("#persetujuanpembongkaran-konversi_is_need_approval:checked").val(),
											is_from_do: 0,
											detail: arr_client_wms_konversi_detail
										},
										dataType: "JSON",
										beforeSend: function() {

											Swal.fire({
												title: 'Loading ...',
												html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
												timerProgressBar: false,
												showConfirmButton: false
											});

											$("#btnsavekonversi").prop("disabled", true);
											$("#btn-search-data").prop("disabled", true);
											$("#loadingview").show();
										},
										success: function(data) {
											if (data == 1) {
												console.log("insert_tr_konversi_sku_from_another_menu berhasil");
												// $.ajax({
												// 	type: 'POST',
												// 	url: "<?= base_url('WMS/MonitorMinimumStok/UpdateSKUQtyKonversi') ?>",
												// 	dataType: "JSON",
												// 	data: {
												// 		tr_konversi_sku_id: v.tr_konversi_sku_id,
												// 		tr_konversi_sku_keterangan: "MINIMUM STOCK"
												// 	},
												// 	async: false,
												// 	success: function(
												// 		response) {
												// 		console.log("UpdateSKUQtyKonversi success");
												// 	},
												// 	error: function(xhr,
												// 		ajaxOptions,
												// 		thrownError) {
												// 		message("Error", "Error 500 Internal Server Connection Failure, Update SKU Qty Konversi Failed", "error");

												// 		$("#btnsavekonversi").prop("disabled", false);
												// 		$("#btn-search-data").prop("disabled", false);
												// 		$("#loadingview").hide();
												// 	},
												// })
											} else {
												console.log("insert_tr_konversi_sku_from_another_menu gagal");
											}

											$("#btnapprovdodraft").prop("disabled", false);
											$("#btn-search-data-do-draft").prop("disabled", false);
											$("#loadingviewdodraft").hide();
										},
										error: function(xhr, ajaxOptions, thrownError) {
											message("Error", "Error 500 Internal Server Connection Failure", "error");

											$("#btnsavekonversi").prop("disabled", false);
											$("#btn-search-data").prop("disabled", false);
											$("#loadingview").hide();
										},
										complete: function() {
											cek_pembongkaran_completed++;
										}
									});

								} else {
									cek_completed = 0;
									$("#loadingviewdodraft").hide();
								}
							});

						} else {
							// var alert = GetLanguageByKode('CAPTION-ALERT-PILIHSKUKONVERSI');
							var alert = "client_wms_konversi not found";
							message("Error", alert, "error");

							// alert('client_wms_konversi tidak ada');
						}

					} else {
						// let alert_tes = GetLanguageByKode('CAPTION-ALERT-PILIHSKUKONVERSI');
						var alert = "arr_konversi_detail not found";
						message_custom("Error", "error", alert_tes);

						// alert('arr_konversi_detail tidak ada');
					}
				}

				setTimeout(() => {

					if (cek_pembongkaran_completed > 0 && arr_konversi_detail.length > 0) {

						var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
						message("Success", alert, "success");

						$("#modal-pembongkaran-sku").modal('hide');

						$("#btnsavekonversi").prop("disabled", false);
						$("#btn-search-data").prop("disabled", false);
						$("#loadingview").hide();

						GetKonversiFromDO();

					} else {

						cek_pembongkaran_completed = 0;

						var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
						message("Error", alert, "error");
					}

				}, 1000);
			}
		});
	});
</script>