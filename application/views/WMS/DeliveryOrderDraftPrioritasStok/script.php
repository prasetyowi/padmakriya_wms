<script type="text/javascript">
	var ChannelCode = '';
	var arr_count = [];
	let arrBongkar = []
	let arrpush = []
	var arr_delivery_order_detail_prioritas = [];

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

	$("#btn-search-data").click(function() {
		let tipe = $('#filter_tipedo').val();
		if (tipe == "") {
			message("Perhatian", "Harap pilih tipe DO terlebih dahulu!", 'warning')
			return false;
		}
		getDatasearch();
	});

	$('#cball').click(function(e) {
		$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);

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
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftPrioritasStokByFilter') ?>",
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
				$("#table_list_data > tbody").empty();
				let tipe = $("[name='filter_tipeprioritas']").val();
				console.log(tipe);
				let index = 0;
				if (tipe == '') {
					$.each(response, function(i, v) {
						$("#table_list_data > tbody").append(`
                        <tr style="background: ${parseInt(v.type_request) == 0 ? '#aaffaa' : '#ef4836'};color:white;">                        
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${i+1}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kode}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_nama_produk}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.principle}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.brand}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kemasan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_satuan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_qty_draft}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_stock}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}','${v.sku_kode}','${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}','${v.sku_stock}','${v.flag}')"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `);

					});
				} else if (tipe == 1) {
					$.each(response, function(i, v) {
						if (v.type_request == 1) {
							$("#table_list_data > tbody").append(`
                        <tr style="background: ${parseInt(v.type_request) == 0 ? '#aaffaa' : '#ef4836'};color:white;">                        
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${index+1}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kode}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_nama_produk}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.principle}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.brand}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kemasan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_satuan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_qty_draft}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_stock}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}','${v.sku_kode}','${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}','${v.sku_stock}','${v.flag}')"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `);
							index++
						}

					});
				} else if (
					tipe == 2
				) {
					$.each(response, function(i, v) {
						if (v.type_request == 0) {
							$("#table_list_data > tbody").append(`
                        <tr style="background: ${parseInt(v.type_request) == 0 ? '#aaffaa' : '#ef4836'};color:white;">                        
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${index+1}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kode}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_nama_produk}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.principle}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.brand}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_kemasan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_satuan}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.flag}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_qty_draft}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_stock}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}','${v.sku_kode}','${v.sku_nama_produk}','${v.sku_kemasan}','${v.sku_satuan}','${v.sku_stock}','${v.flag}')"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `);
							index++
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
				$("#btn-search-data").prop("disabled", false);
				$('#spanCount').html('(' + arr_count.length + ')');
			},
			complete: function() {
				$('#spanCount').html('(' + arr_count.length + ')');
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

	function showDetail(sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan, sku_stock_qty, flag) {

		$("#modal-detail-sku").modal('show');
		arr_delivery_order_detail_prioritas = [];
		push_arr_delivery_order_detail_prioritas = [];

		$("#DetailSKUHeader-sku_id").val(sku_id);
		$("#DetailSKUHeader-sku_kode").val(sku_kode);
		$("#DetailSKUHeader-sku_nama_produk").val(sku_nama_produk);
		$("#DetailSKUHeader-sku_kemasan").val(sku_kemasan);
		$("#DetailSKUHeader-sku_satuan").val(sku_satuan);
		$("#DetailSKUHeader-sku_stock_qty").val(sku_stock_qty);

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/Get_delivery_order_draft_detail_by_sku_id') ?>",
			data: {
				sku_id: sku_id,
				flag: flag,
				filter_tanggal: $("#filter_tanggal").val(),
				filter_tipedo: $('#filter_tipedo').find('option:selected').attr('data-text')
			},
			dataType: "JSON",
			success: function(res) {

				if (res.length > 0) {
					if ($.fn.DataTable.isDataTable('#table-detail-sku')) {
						$('#table-detail-sku').DataTable().destroy();
					}
					$('#table-detail-sku > tbody').html('');
					$('#table-detail-sku > tbody').empty('');

					// if ($.fn.DataTable.isDataTable('#table-detail-sku')) {
					//     $('#table-detail-sku').DataTable().clear();
					//     $('#table-detail-sku').DataTable().destroy();
					// }

					$.each(res, function(i, v) {

						arr_delivery_order_detail_prioritas.push({
							'delivery_order_detail_draft_id': v.delivery_order_detail_draft_id,
							'delivery_order_draft_id': v.delivery_order_draft_id,
							'sku_id': v.sku_id,
							'sku_qty_asli': v.do_draft_qty,
							'sku_qty': v.do_draft_qty,
							'is_abaikan': v.is_abaikan
						})

						if (v.delivery_order_draft_is_prioritas == "YA") {

							$("#table-detail-sku > tbody").append(`
                                <tr class="bg-info">
                                    <td class="text-center" style="width: 5%;">
                                        ${i+1}
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id" value="${v.delivery_order_detail_draft_id}">
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-delivery_order_draft_id" value="${v.delivery_order_draft_id}">
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_id" value="${v.sku_id}">
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_qty_asli" value="${v.do_draft_qty}">
                                    </td>
                                    <td class="text-left" style="width: 10%;">${v.sales_order_no_po}</td>
                                    <td class="text-left" style="width: 10%;">${v.do_draft_kode}</td>
                                    <td class="text-left" style="width: 10%;">${v.delivery_order_draft_tgl_rencana_kirim}</td>
                                    <td class="text-left" style="width: 15%;">${v.do_draft_nama}</td>
                                    <td class="text-left">${v.do_draft_alamat}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment1}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment2}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment3}</td>
                                    <td class="text-left" style="width: 10%;">${v.delivery_order_draft_is_prioritas}</td>
                                    <td class="text-center" style="width: 10%;">${v.do_draft_qty}</td>
                                    <td class="text-right" style="width: 15%;">
                                        <input style="width:100px" type="text" class="form-control numeric" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_qty" value="${v.do_draft_qty}" onchange="UpdateArrDeliveryOrderDetailPrioritas('${i}','${v.delivery_order_detail_draft_id}')">
                                    </td>
									<td class="text-right" style="width: 10%;">
                                        <input style="width:100px" type="checkbox" id="item-${i}-DeliveryOrderDetailPrioritasStok-is_abaikan" value="1" ${v.is_abaikan == '1' ? 'checked': '' } onchange="UpdateArrDeliveryOrderDetailPrioritas('${i}','${v.delivery_order_detail_draft_id}')">
                                    </td>
                                </tr>
                            `);

						} else {
							$("#table-detail-sku > tbody").append(`
                                <tr>
                                    <td class="text-center" style="width: 5%;">
                                        ${i+1}
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id" value="${v.delivery_order_detail_draft_id}">
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-delivery_order_draft_id" value="${v.delivery_order_draft_id}">
                                        <input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_id" value="${v.sku_id}">
										<input type="hidden" class="form-control" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_qty_asli" value="${v.do_draft_qty}">
                                    </td>
                                    <td class="text-left" style="width: 10%;">${v.sales_order_no_po}</td>
                                    <td class="text-left" style="width: 10%;">${v.do_draft_kode}</td>
                                    <td class="text-left" style="width: 10%;">${v.delivery_order_draft_tgl_rencana_kirim}</td>
                                    <td class="text-left" style="width: 15%;">${v.do_draft_nama}</td>
                                    <td class="text-left">${v.do_draft_alamat}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment1}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment2}</td>
                                    <td class="text-left" style="width: 10%;">${v.segment3}</td>
                                    <td class="text-left" style="width: 10%;">${v.delivery_order_draft_is_prioritas}</td>
                                    <td class="text-center" style="width: 10%;">${v.do_draft_qty}</td>
                                    <td class="text-right" style="width: 15%;">
                                        <input style="width:100px" type="number" min="0"  class="form-control numeric" id="item-${i}-DeliveryOrderDetailPrioritasStok-sku_qty" value="${v.do_draft_qty}" onchange="UpdateArrDeliveryOrderDetailPrioritas('${i}','${v.delivery_order_detail_draft_id}')">
                                    </td>
									<td class="text-right" style="width: 10%;">
                                        <input style="width:100px" type="checkbox" id="item-${i}-DeliveryOrderDetailPrioritasStok-is_abaikan" value="1" ${v.is_abaikan == '1' ? 'checked': '' } onchange="UpdateArrDeliveryOrderDetailPrioritas('${i}','${v.delivery_order_detail_draft_id}')">
                                    </td>
                                </tr>
                            `);
						}
					});

					$('#table-detail-sku').DataTable({
						columnDefs: [{
							sortable: true,
							// targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});

				}
			}
		})
	}

	$(document).on("input", ".numeric", function(event) {
		this.value = this.value.replace(/[^\d.]+/g, '');
	});

	function UpdateArrDeliveryOrderDetailPrioritas(index, delivery_order_detail_draft_id) {
		const findIndexData = arr_delivery_order_detail_prioritas.findIndex((value) => value.delivery_order_detail_draft_id == delivery_order_detail_draft_id);

		arr_delivery_order_detail_prioritas[findIndexData] = ({
			'delivery_order_detail_draft_id': $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id').val(),
			'delivery_order_draft_id': $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-delivery_order_draft_id').val(),
			'sku_id': $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-sku_id').val(),
			'sku_qty_asli': $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-sku_qty_asli').val(),
			'sku_qty': $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-sku_qty').val(),
			'is_abaikan': typeof $('#item-' + index + '-DeliveryOrderDetailPrioritasStok-is_abaikan:checked').val() !== 'undefined' ? "1" : "0"
		});

	}

	$("#btn_simpan_do_prioritas_stok").click(function() {
		let push_arr_delivery_order_detail_prioritas = [];
		let total_sku_qty = 0;
		cek_error = 0;

		// if (arr_list_multi_sku.length == 0) {

		//     let alert = "List SKU Tidak Boleh Kosong";
		//     message_custom("Error", "error", alert);

		//     return false;
		// }

		$.each(arr_delivery_order_detail_prioritas, function(i, v) {
			if (v.is_abaikan != "1") {
				if (parseInt(v.sku_qty_asli) != parseInt(v.sku_qty)) {
					push_arr_delivery_order_detail_prioritas.push({
						'delivery_order_detail_draft_id': v.delivery_order_detail_draft_id,
						'delivery_order_draft_id': v.delivery_order_draft_id,
						'sku_id': v.sku_id,
						'sku_qty_asli': v.sku_qty_asli,
						'sku_qty': v.sku_qty,
						'is_abaikan': v.is_abaikan
					});
				}

				total_sku_qty += parseInt(v.sku_qty);

				if (parseInt(v.sku_qty) < 0) {
					let alert = "Qty Tidak Boleh Minus";
					message_custom("Error", "error", alert);

					cek_error++;
					return false;
				}
			}
		});
		setTimeout(() => {

			if (push_arr_delivery_order_detail_prioritas.length == 0) {
				let alert = "Tidak Ada Qty Order yang Diubah";
				message_custom("Error", "error", alert);

				return false;
			}

			if (parseInt($("#DetailSKUHeader-sku_stock_qty").val()) < total_sku_qty) {
				let alert = "Total Qty Order Edit Melebihi SKU Stock Qty";
				message_custom("Error", "error", alert);

				return false;
			}

			if (cek_error == 0) {

				Swal.fire({
					title: "Apakah anda yakin?",
					text: "Qty 0 Akan Dihapus dari Detail DO!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya",
					cancelButtonText: "Tidak"
				}).then((result) => {
					if (result.value == true) {

						$.ajax({
							async: false,
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/Update_delivery_order_draft_prioritas_stok'); ?>",
							type: "POST",
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false
								});

								$("#btn_simpan_do_prioritas_stok").prop("disabled", true);
							},
							data: {
								arr_list_do_detail: push_arr_delivery_order_detail_prioritas
							},
							dataType: "JSON",
							success: function(response) {

								if (response.status == 1) {

									var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
									// var alert = "Data Berhasil Disimpan";
									message_custom("Success", "success", alert);
									$("#modal-detail-sku").modal('hide');

									getDatasearch();

									// ResetForm();
								} else {
									var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
									// var alert = response.data.message;
									message_custom("Error", "error", alert);
								}

								$("#btn_simpan_do_prioritas_stok").prop("disabled", false);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");

								$("#btn_simpan_do_prioritas_stok").prop("disabled", false);
							},
							complete: function() {
								// Swal.close();
								$("#btn_simpan_do_prioritas_stok").prop("disabled", false);
							}
						});
					}
				});

			}

		}, 1000);
	});

	const handlerPrioritas = () => {

		Swal.fire({
			title: 'Loading ...',
			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			timerProgressBar: false,
			showConfirmButton: false
		});
		var totalValue = parseInt($('#DetailSKUHeader-sku_stock_qty').val());
		var $qtyRows = $('#table-detail-sku tbody tr');
		$('#table-detail-sku').fadeIn()

		$qtyRows.each(function(index) {
			var $qtyCell = $(this).find('td:nth-child(11)');
			var $qtyEditCell = $(this).find('td:nth-child(12)');
			var qtyValue = parseInt($qtyCell.text());
			var valueqty = $(`#item-${index}-DeliveryOrderDetailPrioritasStok-sku_qty`);
			var sku_qty_asli = $(`#item-${index}-DeliveryOrderDetailPrioritasStok-sku_qty_asli`).val();
			var is_abaikan = typeof $(`#item-${index}-DeliveryOrderDetailPrioritasStok-is_abaikan:checked`).val() !== 'undefined' ? "1" : "0";

			if (is_abaikan == "1") {
				$qtyEditCell.find('input').val(qtyValue);
			} else {
				if (totalValue >= qtyValue) {
					$qtyCell.text(qtyValue);
					$qtyEditCell.find('input').val(qtyValue);
					// valueqty.val(totalValue)
					totalValue -= qtyValue;
				} else {
					$qtyEditCell.find('input').val(totalValue);
					totalValue = 0;
				}
			}

			var delivery_order_detail_draft_id = $(`#item-${index}-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id`).val();
			UpdateArrDeliveryOrderDetailPrioritas(index, delivery_order_detail_draft_id)
		});
		Swal.close()
		// if (totalValue > 0) {
		// 	alert('Stock tidak cukup!');
		// }
	}
	const handlerProrate = () => {
		Swal.fire({
			title: 'Loading ...',
			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			timerProgressBar: false,
			showConfirmButton: false
		});
		var rows = $('#table-detail-sku tbody tr');
		var sum = 0;

		$('#table-detail-sku').fadeIn()
		// Menghitung total Qty Order
		rows.each(function() {
			sum += parseFloat($(this).children('td').eq(11).text());
		});

		var value = parseInt($('#DetailSKUHeader-sku_stock_qty').val());

		var tampung = 0;
		rows.each(function(index) {
			var qtyedit = $(this).find('td:nth-child(12)');
			var tds = $(this).children('td');
			var qtyOrder = parseFloat(tds.eq(10).text());
			var sku_qty_asli = $(`#item-${index}-DeliveryOrderDetailPrioritasStok-sku_qty_asli`).val();
			var is_abaikan = typeof $(`#item-${index}-DeliveryOrderDetailPrioritasStok-is_abaikan:checked`).val() !== 'undefined' ? "1" : "0";

			if (is_abaikan == "1") {
				qtyedit.find('input').val(sku_qty_asli);
			} else {
				var qtybagi = Math.min(value / rows.length, qtyOrder);

				var remainingValue = value - (qtybagi * rows.length);

				if (remainingValue < 0) {
					qtybagi = qtybagi + remainingValue / rows.length;
				}

				var roundedQty = Math.min(Math.round(qtybagi), value);

				tampung += roundedQty
				console.log(tampung, roundedQty, value);
				if (tampung > value) {
					// $qtyEditCell.find('input').val(totalValue);
					// tds.eq(12).find('input').val(roundedQty - tampung);
					qtyedit.find('input').val(roundedQty - 1);

					// console.log(roundedQty - tampung);
				} else {
					// console.log(roundedQty);
					// tds.eq(12).find('input').val(roundedQty);
					qtyedit.find('input').val(roundedQty);
				}
			}
			var delivery_order_detail_draft_id = $(`#item-${index}-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id`).val();

			UpdateArrDeliveryOrderDetailPrioritas(index, delivery_order_detail_draft_id)

		});
		Swal.close()

	}
	const handlerReset = () => {
		Swal.fire({
			title: 'Loading ...',
			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			timerProgressBar: false,
			showConfirmButton: false
		});

		var totalValue = parseInt($('#DetailSKUHeader-sku_stock_qty').val());

		var qtyRows = $('#table-detail-sku tbody tr');
		qtyRows.each(function(i) {
			var valueqty = $(`#item-${i}-DeliveryOrderDetailPrioritasStok-sku_qty`)

			var qtyCell = $(this).find('td:nth-child(11)');
			var qtyEditCell = $(this).find('td:nth-child(12)');
			valueqty.val(qtyCell.text())
			var delivery_order_detail_draft_id = $(`#item-${i}-DeliveryOrderDetailPrioritasStok-delivery_order_detail_draft_id`).val();
			UpdateArrDeliveryOrderDetailPrioritas(i, delivery_order_detail_draft_id)

		});
		Swal.close()
	}
</script>