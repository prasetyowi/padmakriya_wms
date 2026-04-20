<script>
	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	$(document).ready(
		function() {
			if ($('#filter_tanggal').length > 0) {
				$('#filter_tanggal').daterangepicker({
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

			$(".select2").select2();
			// GetLaporanStockRekapData();
		}
	);

	const handlerFilterData = (event) => {

		$('#table_export_penerimaan_penjualan tbody').fadeOut("slow", function() {
			$(this).hide();

			$('#table_export_penerimaan_penjualan > tbody').empty('');

			if ($.fn.DataTable.isDataTable('#table_export_penerimaan_penjualan')) {
				$('#table_export_penerimaan_penjualan').DataTable().clear();
				$('#table_export_penerimaan_penjualan').DataTable().destroy();
			}

		}).fadeIn("slow", function() {
			$(this).show();

			$('#table_export_penerimaan_penjualan').DataTable({
				"scrollX": true,
				'paging': true,
				'searching': true,
				'ordering': true,
				'processing': true,
				'serverSide': true,
				'lengthMenu': [
					[50, 100, 250, 1000], // -1 untuk menunjukkan opsi "All"
					[50, 100, 250, 1000] // Teks yang ditampilkan untuk opsi "All"
				],
				// 'deferLoading': 0,
				'ajax': {
					url: "<?= base_url('WMS/ExportData/Bosnet/Get_bosnet_penerimaan_barang_penjualan_by_filter') ?>",
					type: "POST",
					dataType: "json",
					data: function(data) {
						data.tanggal = $("#filter_tanggal").val(),
							data.principle = $('#filter_principle').val(),
							data.driver = $('#filter_driver').val()
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});
					},
					complete: function() {
						Swal.close();

					},
				},
				'columns': [{
						data: 'sales_order_no_po',
					},
					{
						data: null,
						render: function(data, type, row, meta) {
							return `<span class="text-left"></span>`
						},
						orderable: false
					},
					{
						data: 'sku_konversi_group'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_jumlah_terima',
						render: function(data, type, row, meta) {
							sku_jumlah_terima = isNaN(parseInt(data)) ? 0 : parseInt(data);
							return parseInt(sku_jumlah_terima);
						},
					},
					{
						data: 'kode_gudang'
					},
					{
						data: 'tipe_persediaan'
					}
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-left',
					},
					{
						targets: 1,
						className: 'text-left'
					},
					{
						targets: 2,
						className: 'text-left'
					},
					{
						targets: 3,
						className: 'text-left'
					},
					{
						targets: 4,
						className: 'text-right'
					},
					{
						targets: 5,
						className: 'text-left'
					},
					{
						targets: 5,
						className: 'text-left'
					},
				],
				dom: "Blfrtip",
				buttons: [
					// {
					// 		extend: "print",
					// 		text: '<i class="fa fa-print"></i> Print',
					// 		className: 'btn btn-warning',
					// 		customize: function(win) {

					// 			var last = null;
					// 			var current = null;
					// 			var bod = [];

					// 			var css = '@page { size: landscape; }',
					// 				head = win.document.head || win.document.getElementsByTagName('head')[0],
					// 				style = win.document.createElement('style');

					// 			style.type = 'text/css';
					// 			style.media = 'print';

					// 			if (style.styleSheet) {
					// 				style.styleSheet.cssText = css;
					// 			} else {
					// 				style.appendChild(win.document.createTextNode(css));
					// 			}

					// 			head.appendChild(style);
					// 		}
					// 	},
					{
						text: '<i class="fa fa-file-excel-o"></i> Excel',
						className: 'btn btn-success',
						action: function(e, dt, button, config) {

							// ambil parameter filter (samakan dengan datatable)
							let params = {
								tanggal: $("#filter_tanggal").val(),
								principle: $('#filter_principle').val(),
								driver: $('#filter_driver').val()
							};

							// buat form POST
							let $form = $('<form>', {
								method: 'POST',
								action: '<?= base_url("WMS/ExportData/Bosnet/ExportExcelDetailPenerimaanPenjualan") ?>'
							});

							// inject parameter ke form
							$.each(params, function(key, value) {
								$form.append(
									$('<input>', {
										type: 'hidden',
										name: key,
										value: value
									})
								);
							});

							// submit
							$('body').append($form);
							$form.submit();
							$form.remove();
						}
					},
					// {
					// 	extend: 'csvHtml5',
					// 	text: '<i class="fa fa-file"></i> CSV',
					// 	className: 'btn btn-info',
					// },
					// {
					// 	extend: 'pdfHtml5',
					// 	orientation: 'landscape',
					// 	text: '<i class="fa fa-file-pdf-o"></i> PDF',
					// 	className: 'btn btn-danger',
					// 	pageSize: 'A4'
					// }
				],
				initComplete: function() {
					parent_dt = $('#table_export_penerimaan_penjualan').closest('.dataTables_wrapper')
					parent_dt.find('.dataTables_filter').css('width', 'auto')
					var input = parent_dt.find('.dataTables_filter input').unbind(),
						self = this.api(),
						$searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(function() {
							self.search(input.val()).draw();
						}),
						$clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(function() {
							input.val('');
							$searchButton.click();
						})
					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);
					parent_dt.find('.dataTables_filter input').keypress(function(e) {
						var key = e.which;
						if (key == 13) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		});

	}

	function getDetail(penerimaan_surat_jalan_id, penerimaan_surat_jalan_no_sj, principle_id, principle_kode, tgl) {
		$("#title_sj").text(penerimaan_surat_jalan_no_sj);
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/ExportData/Bosnet/Get_bosnet_penerimaan_surat_jalan_by_id') ?>",
			data: {
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id,
				principle_id: principle_id,
				principle_kode: principle_kode,
				tgl: tgl
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				if ($.fn.DataTable.isDataTable('#table_detail_penerimaan_surat_jalan')) {
					$('#table_detail_penerimaan_surat_jalan').DataTable().clear();
					$('#table_detail_penerimaan_surat_jalan').DataTable().destroy();
				}

				$('#table_detail_penerimaan_surat_jalan > tbody').empty('');

				// $('#table_detail_penerimaan_surat_jalan').fadeOut("slow", function() {
				// 	$(this).hide();

				// }).fadeIn("slow", function() {
				// 	$(this).show();

				if (response.length != 0) {
					var principal = '';
					var depo = '';
					var co = '';

					$.each(response, function(i, v) {
						principal = v.principle_kode;
						depo = v.depo_eksternal_kode;
						co = v.penerimaan_surat_jalan_no_sj;
						// <td style="vertical-align: middle;text-align:center;">${v.sku_konversi_group}</td>
						// 		<td style="vertical-align: middle;text-align:center;">${v.sku_nama_produk}</td>
						// 		<td style="vertical-align: middle;text-align:center;">${v.sku_jumlah_barang}</td>
						// 		<td style="vertical-align: middle;text-align:center;">${v.sku_harga}</td>
						$("#table_detail_penerimaan_surat_jalan > tbody").append(`
								<tr>
									<td style="vertical-align: middle;text-align:center;">${v.principle_kode}</td>
									<td style="vertical-align: middle;text-align:center;">${v.principle_nama}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_konversi_group}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_nama_produk}</td>
									<td style="vertical-align: middle;text-align:center;">${v.penerimaan_surat_jalan_tgl}</td>
									<td style="vertical-align: middle;text-align:center;">${v.penerimaan_surat_jalan_no_sj}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_jumlah_barang}</td>
							 		<td style="vertical-align: middle;text-align:center;">${v.sku_harga}</td>
									<td style="vertical-align: middle;text-align:center;">${v.depo_eksternal_kode}</td>
								</tr>
							`);
					});

					// console.log(tbody_str);

					$("#table_detail_penerimaan_surat_jalan").DataTable({
						paging: false,
						ordering: false,
						info: false,
						searching: false,
						dom: "Blfrtip",
						buttons: [{
								extend: "print",
								text: '<i class="fa fa-print"></i> Print',
								className: 'btn btn-warning',
								customize: function(win) {

									var last = null;
									var current = null;
									var bod = [];

									var css = '@page { size: landscape; }',
										head = win.document.head || win.document.getElementsByTagName('head')[0],
										style = win.document.createElement('style');

									style.type = 'text/css';
									style.media = 'print';

									if (style.styleSheet) {
										style.styleSheet.cssText = css;
									} else {
										style.appendChild(win.document.createTextNode(css));
									}

									head.appendChild(style);
								}
							},
							// {
							// 	text: '<i class="fa fa-file-excel-o"></i> Excel (XLS)',
							// 	className: 'btn btn-success',
							// 	action: function(e, dt, button, config) {
							// 		// exportToXLS(dt);
							// 		var data = dt.buttons.exportData(); // Mengambil data dari DataTable

							// 		// Kirim data melalui form
							// 		var $form = $('<form method="POST" action="<?= base_url("WMS/ExportData/Bosnet/ExportExcelDetailPenerimaanSJ") ?>">');
							// 		$form.append($('<input type="hidden" name="data">').val(JSON.stringify(data)));
							// 		$('body').append($form);
							// 		$form.submit();

							// 		// $.ajax({
							// 		// 	url: '<?= base_url("WMS/ExportData/Bosnet/ExportExcelDetailPenerimaanSJ") ?>', // URL ke controller
							// 		// 	type: 'POST',
							// 		// 	dataType: 'json',
							// 		// 	data: {
							// 		// 		data: data // Mengirim data ke controller
							// 		// 	},
							// 		// 	success: function(response) {
							// 		// 		// Proses jika sukses
							// 		// 	},
							// 		// 	error: function(xhr, status, error) {
							// 		// 		// Proses jika ada error
							// 		// 	}
							// 		// });
							// 	}
							// }, 
							{
								extend: 'excelHtml5',
								text: '<i class="fa fa-file-excel-o"></i> Excel',
								className: 'btn btn-success',
								filename: `${principal}_${depo}_${co}`
							},
							{
								extend: 'csvHtml5',
								text: '<i class="fa fa-file"></i> CSV',
								className: 'btn btn-info',
							},
							{
								extend: 'pdfHtml5',
								orientation: 'landscape',
								text: '<i class="fa fa-file-pdf-o"></i> PDF',
								className: 'btn btn-danger',
								pageSize: 'A4'
							}
						],
						lengthMenu: [
							[-1],
							['All'],
						],
					});
				}
				// });

				// console.log(response);
				$("#modal_detail").modal('show');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});
	}

	function exportToXLS(dt) {
		var data = dt.buttons.exportData({
			format: {
				body: function(data, row, column, node) {
					return data; // Ubah jika perlu untuk membersihkan data
				}
			}
		});

		// Format data menjadi array of objects untuk setiap baris
		var formattedData = data.body.map(function(row) {
			var rowData = {};
			data.header.forEach(function(header, index) {
				rowData[header] = row[index];
			});
			return rowData;
		});

		var ws = XLSX.utils.json_to_sheet(formattedData);
		var wb = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

		XLSX.writeFile(wb, "data.xls", {
			bookType: 'xls',
			type: 'binary'
		});
	}

	function formatBulanKolom(tanggal) {
		var date = new Date(tanggal);
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var formattedDate = months[date.getMonth()] + " " + date.getFullYear();

		return formattedDate;
	}
</script>