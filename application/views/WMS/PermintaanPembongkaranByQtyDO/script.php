<script type="text/javascript">
	var ChannelCode = '';
	var arr_count = [];
	let arrBongkar = []
	let arrpush = []
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

	function getCount() {
		arr_count = []
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PermintaanPembongkaranByQtyDO/getDataSearch') ?>",
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
			url: "<?= base_url('WMS/PermintaanPembongkaranByQtyDO/getDataSearch') ?>",
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
								// depo_detail_id: v.depo_detail_id,
								client_wms_id: v.client_wms_id,
								brand: v.brand,
								sku_kemasan: v.sku_kemasan,
								sku_satuan: v.sku_satuan,
								sku_qty_draft: v.sku_qty_draft,
								sku_stock: v.sku_stock,
								sku_stock_id: v.sku_stock_id,
								sku_konversi_faktor: v.sku_konversi_faktor,
								// depo_detail_nama: v.depo_detail_nama,
								flag: v.flag,
								sku_konversi_group: v.sku_konversi_group,
								sku_konversi_level: v.sku_konversi_level,
							})
						}
					}
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
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_qty_draft}</td>
                            <td class="text-center" style="color: ${parseInt(v.type_request) == 0 ? 'green' : 'white'};">${v.sku_stock}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info" id="detail_do_sku" onclick="showDetail('${v.sku_id}')"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    `);
				});
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

	function showDetail(sku_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PermintaanPembongkaranByQtyDO/showDetailDOBySKU') ?>",
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

	$("#btn-search-data-pembongkaran").click(function() {
		RequestPembongkaran();
	});

	function RequestPembongkaran() {
		if (arr_count.length == 0) {
			message("info", "Data Kosong, Silahkan cari!", 'info')
			return false;
		}
		arrBongkar = []
		$("#btn-ajukan").hide();

		$.ajax({
			url: "<?= base_url('WMS/PermintaanPembongkaranByQtyDO/GetListPembongkaran'); ?>",
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
				principle: $("#filter-modal-pembongkaran-principle").val(),
				flag: $("#filter-modal-pembongkaran-flag").val(),
				arr_list_pembongkaran: arr_count
			},
			dataType: "JSON",
			success: function(response) {

				if ($.fn.DataTable.isDataTable('#table-pembongkaran-sku')) {
					$('#table-pembongkaran-sku').DataTable().clear();
					$('#table-pembongkaran-sku').DataTable().destroy();
				}

				$("#table-pembongkaran-sku thead th:gt(10)").remove();

				if (response.length > 0) {

					$.each(response, function(i, v) {
						$("#table-pembongkaran-sku > tbody").append(`
							<tr id="idx-${v.sku_id}">
								<td class="text-center"><input type="checkbox" id="cbpilih-${i}" name="cbpilih" value="${v.sku_id}" data-index='${i}' data-depo_detail_id='${v.depo_detail_id}' data-client_wms_id='${v.client_wms_id}' data-sku_stock_id='${v.sku_stock_id}' data-sku_konversi_faktor='${v.sku_konversi_faktor}' data-sku_konversi_group='${v.sku_konversi_group}' data-sku_konversi_level='${v.sku_konversi_level}'></td>
								<td class="text-center">${i+1}</td>
								<td class="text-center">${v.sku_kode}</td>
								<td class="text-center">${v.sku_nama_produk}</td>
								<td class="text-center">${v.flag}</td>
								<td class="text-center">${v.principle}</td>
								<td class="text-center">${v.brand}</td>
								<td class="text-center">${v.sku_kemasan}</td>
								<td class="text-center">${v.sku_satuan}</td>
								<td class="text-center">${v.sku_qty_draft}</td>
								<td class="text-center">${v.sku_stock}</td>
							</tr>
						`);
					});

					$('#table-pembongkaran-sku').DataTable({
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

		$('#modal-pembongkaran-sku').modal('show');
	}

	$("#btn-hitung").click(function() {

		// var result = arr_count.reduce((unique, o) => {
		// 	if (!unique.some(obj => obj.sku_barang_id === o.sku_barang_id && obj.sku_expdate)) {
		// 		unique.push(o);
		// 	}
		// 	return unique;
		// }, []);
		let arrdetailtemp = [];
		$("#table-pembongkaran-sku > tbody").each(function(index, element) {
			$(this).find(':checkbox:checked').each(function(idx) {

				arrdetailtemp.push({
					idx: $(this).attr('data-index'),
					sku_id: $(this).val(),
					sku_kode: $(this).closest('tr').find("td").eq(2).text(),
					sku_nama_produk: $(this).closest('tr').find("td").eq(3).text(),
					depo_detail_nama: $(this).closest('tr').find("td").eq(4).text(),
					principle: $(this).closest('tr').find("td").eq(5).text(),
					brand: $(this).closest('tr').find("td").eq(6).text(),
					sku_kemasan: $(this).closest('tr').find("td").eq(7).text(),
					sku_satuan: $(this).closest('tr').find("td").eq(8).text(),
					sku_qty_draft: $(this).closest('tr').find("td").eq(9).text(),
					sku_stock: $(this).closest('tr').find("td").eq(10).text(),
					depo_detail_id: $(this).attr('data-depo_detail_id'),
					client_wms_id: $(this).attr('data-client_wms_id'),
					sku_stock_id: $(this).attr('data-sku_stock_id'),
					sku_konversi_faktor: $(this).attr('data-sku_konversi_faktor'),
					sku_konversi_group: $(this).attr('data-sku_konversi_group'),
					sku_konversi_level: $(this).attr('data-sku_konversi_level'),
				})
			})
		})

		if (arrdetailtemp.length == 0) {
			let alert = GetLanguageByKode('CAPTION-ALERT-PILIHSKU');
			message("Warning!", "Mohon Pilih Sku", 'info');
			return false;

		} else if (arrdetailtemp.length > 1000) {
			let alert = GetLanguageByKode('CAPTION-ALERT-MAKSIMALSKU') + "1000";
			message("Warning!", alert, 'info');
			return false;
		}

		var uniqueSkuIds = [];

		// Flag to check for duplicates
		var hasDuplicates = false;

		arrdetailtemp.forEach(function(item) {
			var combination = item.sku_id + '-' + item.principle;
			if (uniqueSkuIds.includes(combination)) {
				hasDuplicates = true;
				return;
			}
			uniqueSkuIds.push(item.sku_id);
		});

		// Check if duplicates were found
		if (hasDuplicates) {
			message('Warning!', "Data sku ada yang sama, mohon pilih salah satu", 'info')
			return false
		}

		arrBongkar = []
		let helper = {};
		let arrdetail = arr_count.reduce(function(r, o) {
			const key = o.sku_id + '-' + o.principle;

			if (!helper[key]) {
				helper[key] = Object.assign({}, o); // create a copy of o
				r.push(helper[key]);
			} else {
				helper[key].sku_qty_draft += o.sku_qty_draft;
				helper[key].sku_stock += o.sku_stock;
			}
			return r;
		}, []);

		if (arr_count.length == 0) {
			message("Warning!", "Data Kosong", "warning!")
			return false;
		}
		$cek = 0;
		let arrtemp = []
		let arrtempsucces = []
		messageBoxBeforeRequest('Data Akan Dihitung', 'Iya, Hitung', 'Tidak, Tutup').then((result) => {
			// $('#btn-hitung').prop('disabled', true)
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/PermintaanPembongkaranByQtyDO/HitungQtyByKemasan'); ?>", {
					arr_detail: arrdetailtemp
				}, "POST", "JSON", function(response) {
					$('#modal-pembongkaran-hasilsku').modal('show')
					// return false;
					var newHeader = $("<th>").text("Besar Sku Satuan");
					var newHeader1 = $("<th>").text("Besar Qty");
					var newHeader2 = $("<th>").text("Hasil Bongkar");
					var newHeader3 = $("<th>").text("Keterangan");
					var thStyles = {
						"text-align": "center",
						"vertical-align": "middle",
						"color": "white"
					};
					var tdStyles = {
						"text-align": "center",
						"vertical-align": "middle",

					};
					// $('#modal-pembongkaran-hasilsku').fadeOut()
					$("#table-pembongkaran-hasilsku tbody").empty()
					// // setTimeout(() => {
					// // 	$.each(arr_count, function(i, v) {
					// // 		$("#table-pembongkaran-sku > tbody").append(`
					// // 		<tr id="idx-${v.sku_id}">
					// // 			<td class="text-center" ><input type="checkbox" id="cbpilih-${i}" name="cbpilih" value="${v.sku_id}" data-index='${i}' data-depo_detail_id='${v.depo_detail_id}' data-client_wms_id='${v.client_wms_id}' data-sku_stock_id='${v.sku_stock_id}' data-sku_konversi_faktor='${v.sku_konversi_faktor}' data-sku_konversi_group='${v.sku_konversi_group}' data-sku_konversi_level='${v.sku_konversi_level}'></td>
					// // 			<td class="text-center">${i+1}</td>
					// // 			<td class="text-center" >${v.sku_kode}</td>
					// // 			<td class="text-center" >${v.sku_nama_produk}</td>
					// // 			<td class="text-center" >${v.depo_detail_nama}</td>
					// // 			<td class="text-center" >${v.principle}</td>
					// // 			<td class="text-center" >${v.brand}</td>
					// // 			<td class="text-center" >${v.sku_kemasan}</td>
					// // 			<td class="text-center" >${v.sku_satuan}</td>
					// // 			<td class="text-center" >${v.sku_qty_draft}</td>
					// // 			<td class="text-center" >${v.sku_stock}</td>

					// // 		</tr>
					// // `);
					// 	})

					// }, 500);
					// $('#modal-pembongkaran-sku').modal('show')
					setTimeout(() => {
						newHeader.css(thStyles);
						newHeader1.css(thStyles);
						newHeader2.css(thStyles);
						newHeader3.css(thStyles);

						var currentIndex = 0;
						var indexThreshold = 10
						let newArray = [];
						if (response == 0) {
							arrtemp = []
							arrtempsucces = []
							var rowCount = $("#table-pembongkaran-hasilsku > tbody tr")
								.length;
							for (let index = 0; index < rowCount; index++) {
								arrtemp.push(index + 1)
								$("#table-pembongkaran-hasilsku thead th:gt(" +
									indexThreshold + ")").remove();
								$("#table-pembongkaran-hasilsku thead tr").append(newHeader,
									newHeader1, newHeader2, newHeader3);

								$("#table-pembongkaran-hasilsku tbody tr").each(function() {
									newRow = $("<td>").text(res.sku_satuan2);
									newRow1 = $("<td>").text(res.stok);
									newRow2 = $("<td>").text(res.hasil);
									newRow3 = $("<td>").text(res.keterangan);
									// $(this).find("td:nth-child(9)").remove();
									$(`#idx-${res.index}`).find("td:gt(" +
										indexThreshold + ")").remove();
									$(`#idx-${res.index}`).append(newRow, newRow1,
										newRow2);
									// $(this).find("td:nth-child(9)").remove();

								});
							}
							setTimeout(() => {
								Swal.fire({
									html: `Item Ke <strong>${arrtemp.join(", ")}</strong> Tidak Bisa Dibongkar`,
									allowOutsideClick: false,
									icon: 'warning',
									confirmButtonText: 'Ok',
								}).then((result) => {
									if (result.value == true) {
										// location.reload()
									}
								})

							}, 500);
							// $('#btn-hitung').prop('disabled', false)
						} else {
							// $('#btn-hitung').prop('disabled', false)
							arrtemp = []
							arrtempsucces = []
							var newRow = ''
							let sumcols = '';
							var newRow1 = ''
							var prevData = null;
							var rowspanCount = 0;
							var cek = 0;
							var tableBody = $("#table-pembongkaran-hasilsku tbody")
							// $.each(arrdetail, function(i, v) {
							$.each(response, function(idx, item) {
								sumcols = item.data.length
								$.each(item.data, function(index, res) {
									var row = $('<tr>');
									var cells;

									if (prevData === null || JSON.stringify(
											prevData) !== JSON.stringify(
											res)) {
										// Data is different, don't apply rowspan
										rowspanCount = 1;
										cek = 1
									} else {
										cek++
										// Data is the same, increment rowspan count
										rowspanCount++;
									}

									// Create table cells
									var cbinputText = $('<input>').attr({
										"type": "checkbox",
										"disabled": parseInt(res
												.stok) < parseInt(
												res.hasil) ||
											parseInt(res.stok) ==
											0 ? 'disabled' : null,
										"data-id": res.sku_id,
										'data-depo_detail_id': res
											.depo_detail_id,
										'data-client_wms_id': res
											.client_wms_id,
										'data-sku_stock_id': res
											.sku_stock_id == null ?
											'' : res.sku_stock_id,
										'data-sku_stock': res
											.sku_stock_id == null ?
											'' : res.sku_stock_id,
										'data-sku_id': res.sku_id,
										'data-sku_expdate': res
											.sku_expired_date,
										'data-principle': res
											.principle,
										"id": `cb-${res.sku_id}-${res.principle}`,
										"onchange": `handleCheckboxClick($(this),'${res.sku_id}','${res.principle}', '${res.depo_detail_id}', ${index}, '${res.sku_satuan}')`
									}).val(0);

									var inputText = $('<input>').attr(
										'type', 'number').val(res.hasil);
									inputText.attr({
										'disabled': 'disabled',
										'class': 'form-control inputText',
										'data-depo_detail_id': res
											.depo_detail_id,
										'data-client_wms_id': res
											.client_wms_id,
										'data-sku_stock_id': res
											.sku_stock_id,
										'data-sku_id': res.sku_id,
										'id': `text-${res.principle}-${res.sku_id}-${res.depo_detail_id}-${index}`,
										'data-sku_expdate': res
											.sku_expired_date,
										"onchange": `handleTextClick($(this),'${res.sku_id}','${res.principle}','${res.stok}', '${res.depo_detail_id}')`
									})

									cbinputText.attr('class',
										'form-control checkbox-group');


									if (parseInt(res.stok) < parseInt(res
											.hasil) || parseInt(res.stok) ==
										0) {
										cells = [
											$('<td>').text(res
												.principle).css(
												"display", "none"),
											$('<td>').text(idx + 1)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_nama_produk).attr(
												'class',
												'td_l1 text-center'),
											// $('<td>').text(res.sku_kode).attr('class','td_l1'),
											$('<td>').text(res
												.principle).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res.brand)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_satuan).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_qty_draft).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res
												.depo_detail_nama)
											.attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').text(res.stok)
											.attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').text(res
												.sku_satuan2).attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').text(res
												.sku_expired_date)
											.attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').text(res.hasil)
											.attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').text(res
												.keterangan).attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').append(inputText)
											.attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),
											$('<td>').append(
												cbinputText).attr({
												'class': 'td_l1 text-center',
												'style': "background-color:yellow",
											}),

										];
									} else {
										cells = [
											$('<td>').text(res
												.principle).css(
												"display", "none"),
											$('<td>').text(idx + 1)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_nama_produk).attr(
												'class',
												'td_l1 text-center'),
											// $('<td>').text(res.sku_kode).attr('class','td_l1'),
											$('<td>').text(res
												.principle).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res.brand)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_satuan).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_qty_draft).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res
												.depo_detail_nama).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res.stok)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_satuan2).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res
												.sku_expired_date).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').text(res.hasil)
											.attr('class',
												'td_l1 text-center'),
											$('<td>').text(res
												.keterangan).attr(
												'class',
												'td_l1 text-center'),
											$('<td>').append(inputText)
											.attr({
												'class': 'td_l1 text-center',
											}),
											$('<td>').append(
												cbinputText).attr(
												'class',
												'td_l1 text-center'),
										];
									}

									// Set rowspan attribute conditionally
									// cells[0].attr('rowspan', cek === 1 ? 2 : 1).css('text-align', 'center').text(cek === 1 ? res.sku_kode : '');

									// // cells[0].textContent = cek === 1 ? res.sku_kode : '';
									// cells[1].textContent = cek === 1 ? res.brand : '';
									// cells[2].textContent = cek === 1 ? res.sku_kemasan : '';
									// cells[3].textContent = cek === 1 ? res.sku_satuan : '';
									// cells[4].textContent = res.keterangan;
									// cells[5].textContent = res.stok;
									// cells[6].textContent = res.hasil;

									// Append cells to the row
									cells.forEach(function(cell, index) {
										row.append(cell);

									});

									// Append the row to the table body
									tableBody.append(row);

									// Update prevData for the next iteration
									prevData = res;
								})
							});

							// })
							MergeCommonRows($("#table-pembongkaran-hasilsku"))
						}
					}, 500);
					// $("#table-pembongkaran-sku tbody td:gt(10)").remove();


				});
			} else {
				// $('#btn-hitung').prop('disabled', false)
			}
		});
		// $.ajax({
		// 	type: 'POST',
		// 	url: "<?= base_url('WMS/PermintaanPembongkaranByQtyDO/HitungQtyByKemasan') ?>",
		// 	data: {
		// 		arr_detail: result
		// 	},
		// 	dataType: "JSON",
		// 	beforeSend: function() {
		// 		Swal.fire({
		// 			title: 'Loading ...',
		// 			html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
		// 			timerProgressBar: false,
		// 			showConfirmButton: false
		// 		});
		// 		// $("#btn-search-data").prop("disabled", true);
		// 	},
		// 	success: function(response) {
		// 		let arrtemp = []
		// 		$.each(result, function(i, v) {
		// 			$.each(response, function(idx, item) {
		// 				$.each(item.data, function(index, res) {
		// 					if (res.sku_id == v.sku_id) {

		// 					} else {
		// 						arrtemp.push(i + 1)
		// 					}
		// 				})
		// 			})
		// 		})
		// 		let td
		// 		$.each(arrtemp, function(i, v) {
		// 			console.log(i);
		// 			$(`#idx-${i+1}`).css({
		// 				"background-color": "yellow",
		// 				"font-weight": "bold",
		// 				// Add more styles as needed
		// 			});
		// 		})
		// 		message('warning!', `Item Ke <strong>${arrtemp.join(", ")}</strong> Tidak Bisa Dibongkar`)

		// 	}
		// })

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


	function handleCheckboxClick(checkbox, sku_id, principle, depo_detail_id, index, sku_satuan) {
		const isChecked = checkbox.prop('checked');
		let inputText = $(`#text-${principle}-${sku_id}-${depo_detail_id}-${index}`);

		var indexArr = arrpush.findIndex(function(item) {
			return item.sku_id === sku_id;
		});

		if (isChecked) {
			if (indexArr !== -1) {
				arrpush.splice(indexArr, 1);
				arrpush.push({
					'sku_id': sku_id,
					'hasil': inputText.val(),
					'principle_id': checkbox.attr('data-principle'),
					'client_wms_id': checkbox.attr('data-client_wms_id'),
					'sku_stock_id': checkbox.attr('data-sku_stock_id'),
					'sku_expdate': checkbox.attr('data-sku_expdate'),
					'depo_detail_id': checkbox.attr('data-depo_detail_id'),
					'sku_satuan_hasil': sku_satuan
				});
			} else {
				arrpush.push({
					'sku_id': sku_id,
					'hasil': inputText.val(),
					'principle_id': checkbox.attr('data-principle'),
					'client_wms_id': checkbox.attr('data-client_wms_id'),
					'sku_stock_id': checkbox.attr('data-sku_stock_id'),
					'sku_expdate': checkbox.attr('data-sku_expdate'),
					'depo_detail_id': checkbox.attr('data-depo_detail_id'),
					'sku_satuan_hasil': sku_satuan
				});
			}
			inputText.prop('disabled', false);
		} else {
			arrpush.splice(indexArr, 1);
			inputText.prop('disabled', true);
		}

		const checkboxes = $(
			`input[type="checkbox"][data-id="${checkbox.data('id')}"][data-depo_detail_id="${checkbox.data('depo_detail_id')}"]`
		);

		checkboxes.each(function() {
			if (!$(this).is(checkbox)) {
				$(this).prop('checked', false);
			}
		});

		$('.inputText').each(function() {
			if ($(this).attr('data-sku_id') == sku_id) {
				if ($(this).attr('id') != `text-${principle}-${sku_id}-${depo_detail_id}-${index}`) {
					$(this).prop('disabled', true);
				}
			}
		})
		console.log(arrpush);
	}

	$("#btn-close").on('click', function() {
		arrpush = [];
		$("#modal-pembongkaran-hasilsku").modal('hide');
	});

	function handleTextClick(textinput, sku_id, principle, stok, depo_detail_id) {

		if (parseInt(textinput.val()) > parseInt(stok)) {
			message('warning!', 'Tidak Boleh lebih dari stok', 'warning')
			return false;
		}
		arrpush.forEach(function(item) {
			if (item.sku_id === sku_id && item.depo_detail_id === depo_detail_id) {

				item.qty = textinput.val();
			}
		});

	}


	$(document).on('click', '#btn-ajukan', function() {
		const groupedData = arrpush.reduce((acc, curr) => {
			const existingItem = acc.find(item => item.principle === curr.principle_id && item
				.depo_detail_id === curr.depo_detail_id);
			if (existingItem) {
				existingItem.data.push(curr);
			} else {
				acc.push({
					principle: curr.principle_id,
					depo_detail_id: curr.depo_detail_id,
					client_wms_id: curr.client_wms_id,
					data: [curr]
				});
			}
			return acc;
		}, []);

		// return false;
		if (groupedData.length == 0) {
			message('info', 'Tidak ada yang bisa dibongkar', 'warning')
			return false;
		} else {
			messageBoxBeforeRequest('Data akan dibongkar, Apa Anda Yakin?', 'Iya, Simpan', 'Tidak, Tutup').then((
				result) => {
				// $('#btn-hitung').prop('disabled', true)
				if (result.value == true) {
					requestAjax(
						"<?= base_url('WMS/PermintaanPembongkaranByQtyDO/requestPembongkaran'); ?>", {
							arr_detail: groupedData
						}, "POST", "JSON",
						function(response) {
							if (response == 0) {
								let alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
								message("Warning!", "Data gagal dibongkar", 'warning');
							} else {
								let alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
								let alert2 = GetLanguageByKode(
									'CAPTION-ALERT-SILAHKANCEKMENUPEMBONGKARAN');
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
</script>