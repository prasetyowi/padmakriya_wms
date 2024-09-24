<script type="text/javascript">
	var ChannelCode = '';
	var arr_count = [];
	let arrBongkar = []
	let arr_persiapan_pengemasan = [];
	var arr_filter_sku = [];

	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			// if ($('#filter_tanggal').length > 0) {
			//     $('#filter_tanggal').daterangepicker({
			//         'applyClass': 'btn-sm btn-success',
			//         'cancelClass': 'btn-sm btn-default',
			//         locale: {
			//             "format": "DD/MM/YYYY",
			//             applyLabel: 'Apply',
			//             cancelLabel: 'Cancel',
			//         },
			//         'startDate': '<?= date("01-m-Y") ?>',
			//         'endDate': '<?= date("t-m-Y") ?>'
			//     });
			// }
			$("#table_list_data").DataTable();
			// getCount()
		}
	);

	$('#check-all-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
				var data_sku = this.getAttribute('data-sku_id');
				arr_filter_sku.push(data_sku);
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
				arr_filter_sku = [];
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
	$('#btn_refresh').click(function() {
		Swal.fire({
			title: 'Loading ...',
			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			timerProgressBar: false,
			showConfirmButton: false
		});
		setTimeout(() => {
			getCount()
			Swal.close()
		}, 1000);
	})

	function getCount() {
		arr_count = []
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PermintaanPengemasanByQtyDO/getDataSearch') ?>",
			data: {
				filter_tanggal: $("#filter_tanggal").val(),
			},
			dataType: "JSON",
			success: function(response) {
				$.each(response, function(i, v) {
					if (parseInt(v.type_request) == 1) {
						if (parseInt(v.sku_konversi_level) == 0 || parseInt(v.sku_konversi_level) ==
							1) {
							arr_count.push({
								sku_id: v.sku_id,
								sku_nama_produk: v.sku_nama_produk,
								principle: v.principle,
								sku_kode: v.sku_kode,
								sku_nama_produk: v.sku_nama_produk,
								depo_detail_id: v.depo_detail_id,
								client_wms_id: v.client_wms_id,
								brand: v.brand,
								sku_kemasan: v.sku_kemasan,
								sku_satuan: v.sku_satuan,
								sku_expdate: v.sku_expdate,
								sku_qty_draft: v.sku_qty_draft,
								sku_stock: v.sku_stock,
								sku_stock_id: v.sku_stock_id,
								sku_konversi_faktor: v.sku_konversi_faktor,
							})
						}
					}
				});
				// console.log(arr_count);
				$('#spanCount').html('(' + arr_count.length + ')');
			}
		});
	}

	$("#btn-search-data").click(function() {
		let tipe = $('#filter_tipedo').val();
		if (tipe == "") {
			message("Perhatian", "Harap pilih tipe DO terlebih dahulu!", 'warning')
			return false;
		}
		getDatasearch();
	});

	$('#check-all-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxFakturHutang"]:checkbox').each(function() {
				this.checked = true;
				var data_faktur_hutang = JSON.parse(this.getAttribute('data-faktur-hutang'));
				arr_faktur_hutang.push({
					...data_faktur_hutang
				});
				// console.log(this.getAttribute('data-customer'));
			});
		} else {
			$('[name="CheckboxFakturHutang"]:checkbox').each(function() {
				this.checked = false;
				arr_faktur_hutang = [];
			});
		}
	});

	function getDatasearch() {
		if ($.fn.DataTable.isDataTable('#table_list_data')) {
			$('#table_list_data').DataTable().destroy();
		}
		$('#table_list_data > tbody').empty();

		arr_count = [];
		$("#select-all-sku").prop("checked", false);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PermintaanPengemasanByQtyDO/getDataSearch') ?>",
			data: {
				filter_tanggal: $("#filter_tanggal").val(),
				filter_tipedo: $('#filter_tipedo').find('option:selected').attr('data-text')
				// $.trim($('#filter_tipedo').children("option").filter(":selected").text())

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
				$("#table_list_data > tbody").empty('');

				if (response.result.length > 0) {
					$.each(response.result, function(i, v) {
						if (v.is_cukup == 1) {
							$("#table_list_data > tbody").append(`
							<tr style="background:#7EAA92;color:black;">                        
								<td class="text-center" style="color:black;">${i+1}</td>
								<td class="text-center" style="color:black;">${v.sku_kode}</td>
								<td class="text-center" style="color:black;">${v.sku_nama_produk}</td>
								<td class="text-center" style="color:black;">${v.type_item}</td>
								<td class="text-center" style="color:black;">${v.principle}</td>
								<td class="text-center" style="color:black;">${v.brand}</td>
								<td class="text-center" style="color:black;">${v.sku_kemasan}</td>
								<td class="text-center" style="color:black;">${v.sku_satuan}</td>
								<td class="text-center" style="color:black;">${v.sku_qty}</td>
								<td class="text-center" style="color:black;">${v.sku_stock_qty}</td>
								<td class="text-center">
									<a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}')"><i class="fas fa-eye"></i></a>
								</td>
							</tr>
						`);

						} else {
							$("#table_list_data > tbody").append(`
							<tr style="background:#FF6969;color:white;">                        
								<td class="text-center" style="color:black;">${i+1}</td>
								<td class="text-center" style="color:black;">${v.sku_kode}</td>
								<td class="text-center" style="color:black;">${v.sku_nama_produk}</td>
								<td class="text-center" style="color:black;">${v.type_item}</td>
								<td class="text-center" style="color:black;">${v.principle}</td>
								<td class="text-center" style="color:black;">${v.brand}</td>
								<td class="text-center" style="color:black;">${v.sku_kemasan}</td>
								<td class="text-center" style="color:black;">${v.sku_satuan}</td>
								<td class="text-center" style="color:black;">${v.sku_qty}</td>
								<td class="text-center" style="color:black;">${v.sku_stock_qty}</td>
								<td class="text-center">
									<a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}')"><i class="fas fa-eye"></i></a>
								</td>
							</tr>
						`);
						}
					});
				}

				console.log(response.pengemasan.length);

				$('#spanCount').html('(' + response.pengemasan.length + ')');

			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
				$("#btn-search-data").prop("disabled", false);
				$('#spanCount').html('(0)');
			},
			complete: function() {
				Swal.close();

				$('#table_list_data').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
				$("#btn-search-data").prop("disabled", false);
			}
		});
	}

	function showDetail(sku_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PermintaanPengemasanByQtyDO/showDetailDOBySKU') ?>",
			data: {
				sku_id: sku_id,
				filter_tanggal: $("#filter_tanggal").val(),
				filter_tipedo: $('#filter_tipedo').find('option:selected').attr('data-text')
			},
			dataType: "JSON",
			success: function(res) {
				$.each(res, function(i, v) {
					if ($.fn.DataTable.isDataTable('#table-detail-sku')) {
						$('#table-detail-sku').DataTable().clear();
						$('#table-detail-sku').DataTable().destroy();
					}

					$("#table-detail-sku > tbody").append(`
                        <tr>
                            <td class="text-center" style="width: 5%;">${i + 1}</td>
                            <td class="text-center" style="width: 15%;">${v.do_draft_kode}</td>
                            <td class="text-center" style="width: 15%;">${v.do_draft_nama}</td>
                            <td class="text-center">${v.do_draft_alamat}</td>
                            <td class="text-center" style="width: 10%;">${v.do_draft_qty}</td>
                        </tr>
                    `);
				});
				$("#table-detail-sku").DataTable();
				$("#modal-detail-sku").modal('show');
			}
		})
	}

	$("#btn-search-data-pengemasan").click(function() {
		arr_filter_sku = [];
		RequestPengemasan();
	});

	function RequestPengemasan() {

		$("#check-all-sku").prop("checked", false);
		arr_filter_sku = [];

		$.ajax({
			url: "<?= base_url('WMS/PermintaanPengemasanByQtyDO/GetListPengemasan'); ?>",
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
				filter_tanggal: $("#filter_tanggal").val(),
				filter_tipedo: $('#filter_tipedo').find('option:selected').attr('data-text'),
				principle: $("#filter-modal-pembongkaran-principle").val(),
				flag: $("#filter-modal-pembongkaran-flag").val()
			},
			dataType: "JSON",
			success: function(response) {

				if ($.fn.DataTable.isDataTable('#table-pengemasan-sku')) {
					$('#table-pengemasan-sku').DataTable().clear();
					$('#table-pengemasan-sku').DataTable().destroy();
				}

				$("#table-pengemasan-sku thead th:gt(10)").remove();

				if (response.length > 0) {

					$.each(response, function(i, v) {
						$("#table-pengemasan-sku > tbody").append(`
							<tr id="idx-${v.sku_id}">
								<td class="text-center">
									<input type="checkbox" id="check-${i}-sku" name="CheckboxSKU" value="${v.sku_id}" data-sku_id="${v.sku_id}" onclick="UpdateArrFilterSKU('${i}','${v.sku_id}')">
								</td>
								<td class="text-center">${i+1}</td>
								<td class="text-center">${v.sku_kode}</td>
								<td class="text-center">${v.sku_nama_produk}</td>
								<td class="text-center">${v.type_item}</td>
								<td class="text-center">${v.principle}</td>
								<td class="text-center">${v.brand}</td>
								<td class="text-center">${v.sku_kemasan}</td>
								<td class="text-center">${v.sku_satuan}</td>
								<td class="text-center">${v.sku_qty}</td>
								<td class="text-center">${v.sku_stock_qty}</td>
							</tr>
						`);
					});

					$('#table-pengemasan-sku').DataTable({
						lengthMenu: [
							[-1],
							['All']
						],
						ordering: false,
						searching: false
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

		$('#modal-pengemasan-sku').modal('show');
	}

	$("#btn-hitung").click(function() {

		if (arr_filter_sku.length == 0) {
			message('info', 'Tidak sku ada yang bisa dikemas', 'warning')
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Pastikan sku yang anda pilih benar!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				$.ajax({
					url: "<?= base_url('WMS/PermintaanPengemasanByQtyDO/GetListHitungKonversiPengemasan'); ?>",
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
						filter_tanggal: $("#filter_tanggal").val(),
						filter_tipedo: $('#filter_tipedo').find('option:selected').attr('data-text'),
						list_sku_id: arr_filter_sku
					},
					dataType: "JSON",
					success: function(response) {

						$("#modal-pengemasan-hasilsku").modal('show');

						$("#table-pengemasan-hasilsku > tbody").html('');
						$("#table-pengemasan-hasilsku > tbody").empty('');

						if (response.length > 0) {

							$.each(response, function(i, v) {

								if (v.is_pengemasan == '1') {
									$("#table-pengemasan-hasilsku > tbody").append(`
										<tr>
											<td class="text-center">
												${i+1}
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-sku_stock_id" value="${v.sku_stock_id_konversi}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-sku_id" value="${v.sku_id_konversi}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-sku_satuan" value="${v.sku_satuan_konversi}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-client_wms_id" value="${v.client_wms_id}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-depo_detail_id" value="${v.depo_detail_id}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-principle_id" value="${v.principle_id}" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-sku_expdate" value="${v.sku_stock_expired_date}" disabled/>
											</td>
											<td class="text-center td_l1">${v.principle}</td>
											<td class="text-center td_l1">${v.brand}</td>
											<td class="text-center td_l1">${v.sku_kode}</td>
											<td class="text-center td_l1">${v.sku_nama_produk}</td>
											<td class="text-center td_l1">${v.sku_satuan}</td>
											<td class="text-center td_l1">${v.sku_qty}</td>
											<td class="text-center">${v.depo_detail_nama}</td>
											<td class="text-center">${v.sku_stock_qty}</td>
											<td class="text-center">${v.sku_satuan_konversi}</td>
											<td class="text-center">${v.sku_stock_expired_date}</td>
											<td class="text-center">
												<input type="text" class="form-control" id="item-${i}-persiapan_pengemasan-sku_qty_rencana_pengemasan" value="${parseInt(v.sku_qty_konversi_rekomendasi)}" onchange="UpdatePersiapanPengemasan('${i}','${v.sku_stock_id_konversi}')" disabled/>
												<input type="hidden" class="form-control" id="item-${i}-persiapan_pengemasan-sku_qty_rencana_pengemasan_max" value="${parseInt(v.sku_qty_konversi_rekomendasi)}" disabled/>
											</td>
											<td class="text-center">${v.is_pengemasan == '1' ? 'Cukup Untuk Pengemasan' : 'Tidak Cukup Untuk Pengemasan'}</td>
											<td class="text-center">
												<input type="checkbox" class="form-control" id="item-${i}-persiapan_pengemasan-is_pengengemasan" value="1" onclick="handleCheckboxPersiapanPengemasan(this,'${i}','${v.sku_stock_id_konversi}')" />
											</td>
										</tr>
									`);
								} else {
									$("#table-pengemasan-hasilsku > tbody").append(`
										<tr>
											<td class="text-center">${i+1}</td>
											<td class="text-center td_l1">${v.principle}</td>
											<td class="text-center td_l1">${v.brand}</td>
											<td class="text-center td_l1">${v.sku_kode}</td>
											<td class="text-center td_l1">${v.sku_nama_produk}</td>
											<td class="text-center td_l1">${v.sku_satuan}</td>
											<td class="text-center td_l1">${v.sku_qty}</td>
											<td class="text-center" style="background:#FFFC9B">${v.depo_detail_nama}</td>
											<td class="text-center" style="background:#FFFC9B">${v.sku_stock_qty}</td>
											<td class="text-center" style="background:#FFFC9B">${v.sku_satuan_konversi}</td>
											<td class="text-center" style="background:#FFFC9B">${v.sku_stock_expired_date}</td>
											<td class="text-center" style="background:#FFFC9B">
												<input type="text" class="form-control" id="item-${i}-sku_qty_rencana_pengemasan" value="0" disabled/>
											</td>
											<td class="text-center" style="background:#FFFC9B">${v.is_pengemasan == '1' ? 'Cukup Untuk Pengemasan' : 'Tidak Cukup Untuk Pengemasan'}</td>
											<td class="text-center" style="background:#FFFC9B">
												<input type="checkbox" class="form-control" id="item-${i}-is_pengengemasan" value="1" disabled/>
											</td>
										</tr>
									`);
								}
							});

							MergeCommonRows($("#table-pengemasan-hasilsku"));

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
		});

	})

	function MergeCommonRows(table) {
		var firstColumnBrakes = [];
		// iterate through the columns instead of passing each column as function parameter:
		for (var i = 0; i <= 8; i++) {
			var fist = null
			var previous = null,
				cellToExtend = null,
				rowspan = 1;
			table.find(".td_l1:nth-child(" + i + ")").each(function(index, e) {
				var jthis = $(this),
					content = jthis.text();

				if (i === 4) {
					fist = content
				}
				if (i === 8) {
					content = content + " " + fist
				}
				// check if current row "break" exist in the array. If not, then extend rowspan:
				if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
					// hide the row instead of remove(), so the DOM index won't "move" inside loop.
					jthis.addClass('hidden');
					cellToExtend.attr({
						"rowspan": (rowspan = rowspan + 1),
						"style": "text-align:center;vertical-align: middle;"
					});
				} else {
					// store row breaks only for the first column:
					if (i === 1) firstColumnBrakes.push(index);
					rowspan = 1;
					if (i === 8) {
						previous = content + " " + fist;
					} else {
						previous = content;
					}
					cellToExtend = jthis;
				}
			});
		}

		// now remove hidden td's (or leave them hidden if you wish):
		// $('td.hidden').remove();
	}


	function handleCheckboxPersiapanPengemasan(checkbox, index, sku_stock_id) {
		const isChecked = $("#item-" + index + "-persiapan_pengemasan-is_pengengemasan").prop('checked');
		const findIndexData = arr_persiapan_pengemasan.findIndex((value) => value.sku_stock_id == sku_stock_id);

		if (parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val()) > parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan_max").val())) {
			$("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val(parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan_max").val()));
		}

		if (isChecked) {
			if (findIndexData > -1) {
				arr_persiapan_pengemasan[findIndexData] = ({
					'sku_id': $("#item-" + index + "-persiapan_pengemasan-sku_id").val(),
					'hasil': $("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val(),
					'principle_id': $("#item-" + index + "-persiapan_pengemasan-principle_id").val(),
					'client_wms_id': $("#item-" + index + "-persiapan_pengemasan-client_wms_id").val(),
					'sku_stock_id': $("#item-" + index + "-persiapan_pengemasan-sku_stock_id").val(),
					'sku_expdate': $("#item-" + index + "-persiapan_pengemasan-sku_expdate").val(),
					'depo_detail_id': $("#item-" + index + "-persiapan_pengemasan-depo_detail_id").val(),
					'sku_satuan_hasil': $("#item-" + index + "-persiapan_pengemasan-sku_satuan").val()
				});
			} else {
				arr_persiapan_pengemasan.push({
					'sku_id': $("#item-" + index + "-persiapan_pengemasan-sku_id").val(),
					'hasil': $("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val(),
					'principle_id': $("#item-" + index + "-persiapan_pengemasan-principle_id").val(),
					'client_wms_id': $("#item-" + index + "-persiapan_pengemasan-client_wms_id").val(),
					'sku_stock_id': $("#item-" + index + "-persiapan_pengemasan-sku_stock_id").val(),
					'sku_expdate': $("#item-" + index + "-persiapan_pengemasan-sku_expdate").val(),
					'depo_detail_id': $("#item-" + index + "-persiapan_pengemasan-depo_detail_id").val(),
					'sku_satuan_hasil': $("#item-" + index + "-persiapan_pengemasan-sku_satuan").val()
				});
			}

			$("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").prop('disabled', false);
		} else {
			arr_persiapan_pengemasan.splice(findIndexData, 1);
			$("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").prop('disabled', true);
		}
	}

	function UpdatePersiapanPengemasan(index, sku_stock_id) {
		const findIndexData = arr_persiapan_pengemasan.findIndex((value) => value.sku_stock_id == sku_stock_id);

		if (parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val()) > parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan_max").val())) {
			$("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val(parseInt($("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan_max").val()));
		}

		if (findIndexData > -1) {
			arr_persiapan_pengemasan[findIndexData] = ({
				'sku_id': $("#item-" + index + "-persiapan_pengemasan-sku_id").val(),
				'hasil': $("#item-" + index + "-persiapan_pengemasan-sku_qty_rencana_pengemasan").val(),
				'principle_id': $("#item-" + index + "-persiapan_pengemasan-principle_id").val(),
				'client_wms_id': $("#item-" + index + "-persiapan_pengemasan-client_wms_id").val(),
				'sku_stock_id': $("#item-" + index + "-persiapan_pengemasan-sku_stock_id").val(),
				'sku_expdate': $("#item-" + index + "-persiapan_pengemasan-sku_expdate").val(),
				'depo_detail_id': $("#item-" + index + "-persiapan_pengemasan-depo_detail_id").val(),
				'sku_satuan_hasil': $("#item-" + index + "-persiapan_pengemasan-sku_satuan").val()
			});
		}

		console.log(findIndexData);
		console.log(arr_persiapan_pengemasan);
	}

	$("#btn-close").on('click', function() {
		arr_persiapan_pengemasan = [];

		$("#table-pengemasan-hasilsku > tbody").html('');
		$("#table-pengemasan-hasilsku > tbody").empty('');

		$("#modal-pengemasan-hasilsku").modal('hide');
	});

	function handleTextClick(textinput, sku_id, principle, stok, depo_detail_id) {

		if (parseInt(textinput.val()) > parseInt(stok)) {
			message('warning!', 'Tidak Boleh lebih dari stok', 'warning')
			return false;
		}

		arr_persiapan_pengemasan.forEach(function(item) {
			if (item.sku_id === sku_id && item.depo_detail_id === depo_detail_id) {
				item.qty = textinput.val();
			}
		});

	}


	$(document).on('click', '#btn-ajukan-pengemasan', function() {

		// console.log(arr_persiapan_pengemasan);

		// return false;
		if (arr_persiapan_pengemasan.length == 0) {
			message('info', 'Tidak ada yang bisa dikemas', 'warning')
			return false;
		} else {
			messageBoxBeforeRequest('Data akan dikemas, Apa Anda Yakin?', 'Iya, Simpan', 'Tidak, Tutup').then((
				result) => {
				// $('#btn-hitung').prop('disabled', true)
				if (result.value == true) {
					requestAjax(
						"<?= base_url('WMS/PermintaanPengemasanByQtyDO/RequestPengemasan'); ?>", {
							arr_persiapan_pengemasan: arr_persiapan_pengemasan
						}, "POST", "JSON",
						function(response) {
							if (response == 0) {
								let alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
								message("Warning!", "Data gagal dikemas", 'warning');
							} else {
								let alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
								let alert2 = "Silahkan Cek Menu Pengemasan";
								// let alert2 = GetLanguageByKode('CAPTION-ALERT-SILAHKANCEKMENUPENGEMASAN');
								let msg = alert + ", " + alert2;

								message('Success', msg, 'success')
								setTimeout(() => {
									location.reload()
								}, 1000);
							}
						})
				}
			})
		}
	})

	function UpdateArrFilterSKU(index, sku_id) {
		const findIndexData = arr_filter_sku.findIndex((value) => value == sku_id);
		const isChecked = $("#check-" + index + "-sku").prop('checked');

		if (isChecked) {
			if (findIndexData > -1) {
				arr_filter_sku[findIndexData] = sku_id;
			} else {
				arr_filter_sku.push(sku_id);
			}
		} else {
			arr_filter_sku.splice(findIndexData, 1);
		}
	}
</script>